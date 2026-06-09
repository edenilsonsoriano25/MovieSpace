<?php

namespace App\Http\Controllers;

use App\Models\Prestamo;
use App\Models\Pelicula;
use App\Models\DetallePrestamo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SolicitudController extends Controller
{
    /**
     * Cliente: Enviar solicitud de alquiler
     */
    public function store(Request $request)
    {
        // Verificar que el usuario esté autenticado y sea cliente
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para solicitar un alquiler.');
        }
        
        if (auth()->user()->rol !== 'cliente') {
            return back()->with('error', 'Solo los clientes pueden realizar solicitudes de alquiler.');
        }

        $request->validate([
            'pelicula_id' => 'required|exists:peliculas,id',
            'dias_prestamo' => 'required|integer|min:1|max:7',
            'metodo_pago' => 'required|in:efectivo,tarjeta,transferencia',
            'nota_adicional' => 'nullable|string|max:500'
        ]);

        $pelicula = Pelicula::find($request->pelicula_id);

        // Verificar stock
        if ($pelicula->copias_en_estante <= 0) {
            return back()->with('error', '❌ No hay copias disponibles de esta película.');
        }

        // Verificar si ya tiene solicitud pendiente de esta película
        $solicitudPendiente = Prestamo::where('id_usuario', auth()->id())
            ->where('estado_prestamo', 'pendiente')
            ->whereHas('detalles', function($q) use ($request) {
                $q->where('id_pelicula', $request->pelicula_id);
            })->exists();

        if ($solicitudPendiente) {
            return back()->with('error', '❌ Ya tienes una solicitud pendiente para esta película.');
        }

        DB::beginTransaction();
        try {
            $fechaSalida = Carbon::now();
            $fechaLimite = $fechaSalida->copy()->addDays($request->dias_prestamo);

            // Crear préstamo con estado 'pendiente'
            $prestamo = Prestamo::create([
                'id_usuario' => auth()->id(),
                'id_trabajador' => null,  // Aún no asignado
                'fecha_salida' => $fechaSalida,
                'fecha_limite' => $fechaLimite,
                'estado_prestamo' => 'pendiente',
                'multa_total' => 0
            ]);

            // Guardar detalle
            DetallePrestamo::create([
                'id_prestamo' => $prestamo->id,
                'id_pelicula' => $pelicula->id,
                'precio_alquiler_momento' => $pelicula->precio_alquiler,
                'monto_multa' => 0
            ]);

            // Guardar nota adicional (si agregaste la columna)
            if ($request->nota_adicional) {
                // Para guardar la nota sin modificar la BD, podemos usar una sesión o un campo extra
                // Opción 1: Guardar en sesión (temporal)
                session(['nota_solicitud_' . $prestamo->id => $request->nota_adicional]);
                
                // Opción 2: Si quieres persistir, ejecuta este SQL primero:
                // ALTER TABLE prestamos ADD COLUMN nota_solicitud TEXT NULL;
                // $prestamo->update(['nota_solicitud' => $request->nota_adicional]);
            }

            // Guardar método de pago en sesión para que el trabajador lo vea
            session(['metodo_pago_' . $prestamo->id => $request->metodo_pago]);

            DB::commit();

            return redirect()->route('peliculas.index')
                ->with('success', '✅ ¡Solicitud enviada! Espera la confirmación del personal de mostrador.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error al enviar la solicitud: ' . $e->getMessage());
        }
    }

    /**
     * Trabajador/Admin: Ver todas las solicitudes pendientes
     */
    public function pendientes()
    {
        // Verificar rol directamente en el controlador
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        
        if (auth()->user()->rol === 'cliente') {
            return redirect()->route('mis-prestamos')->with('error', 'No tienes permiso para ver esta sección.');
        }

        $solicitudes = Prestamo::with(['usuario', 'detalles.pelicula'])
            ->where('estado_prestamo', 'pendiente')
            ->orderBy('created_at', 'asc')
            ->paginate(10);

        return view('prestamos.solicitudes', compact('solicitudes'));
    }

    /**
     * Trabajador/Admin: Aprobar solicitud
     */
    public function aprobar($id)
    {
        // Verificar rol directamente en el controlador
        if (auth()->user()->rol === 'cliente') {
            return redirect()->route('mis-prestamos')->with('error', 'No tienes permiso para realizar esta acción.');
        }

        $prestamo = Prestamo::with('detalles.pelicula')->findOrFail($id);

        if ($prestamo->estado_prestamo !== 'pendiente') {
            return back()->with('error', 'Esta solicitud ya fue procesada.');
        }

        // Verificar que aún haya stock disponible
        foreach ($prestamo->detalles as $detalle) {
            if ($detalle->pelicula->copias_en_estante <= 0) {
                return back()->with('error', "❌ No hay stock disponible para {$detalle->pelicula->titulo}. La solicitud no puede ser aprobada.");
            }
        }

        DB::beginTransaction();
        try {
            $prestamo->update([
                'estado_prestamo' => 'activo',
                'id_trabajador' => auth()->id()
            ]);

            // Decrementar stock
            foreach ($prestamo->detalles as $detalle) {
                $detalle->pelicula->decrement('copias_en_estante');
            }

            DB::commit();

            return redirect()->route('solicitudes.pendientes')
                ->with('success', '✅ Solicitud aprobada. El cliente puede retirar la película en sucursal.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error al aprobar: ' . $e->getMessage());
        }
    }

    /**
     * Trabajador/Admin: Rechazar solicitud
     */
    public function rechazar($id)
    {
        // Verificar rol directamente en el controlador
        if (auth()->user()->rol === 'cliente') {
            return redirect()->route('mis-prestamos')->with('error', 'No tienes permiso para realizar esta acción.');
        }

        $prestamo = Prestamo::findOrFail($id);

        if ($prestamo->estado_prestamo !== 'pendiente') {
            return back()->with('error', 'Esta solicitud ya fue procesada.');
        }

        $prestamo->update([
            'estado_prestamo' => 'rechazado'
        ]);

        return redirect()->route('solicitudes.pendientes')
            ->with('success', '❌ Solicitud rechazada. El cliente será notificado.');
    }
}