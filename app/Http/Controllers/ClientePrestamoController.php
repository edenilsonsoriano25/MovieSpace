<?php

namespace App\Http\Controllers;

use App\Models\Prestamo;
use App\Models\Pelicula;
use App\Models\DetallePrestamo;
use App\Models\Pago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Routing\Controllers\HasMiddleware;

class ClientePrestamoController extends Controller implements HasMiddleware
{
    /**
     * Define los middlewares del controlador.
     * Solo exige que el usuario esté logueado, sin importar su rol.
     */
    public static function middleware(): array
    {
        return [
            'auth',
        ];
    }

    /**
     * Pantalla privada del cliente para ver lo que ha alquilado.
     */
    public function misPrestamos()
    {
        $usuario = auth()->user();

        // Cargamos los préstamos del cliente en sesión de forma directa y segura
        $prestamos = Prestamo::with(['detalles.pelicula'])
            ->where('id_usuario', $usuario->id)
            ->whereIn('estado_prestamo', ['activo', 'pendiente', 'completado', 'rechazado']) // Mostrar todos
            ->orderBy('created_at', 'desc')
            ->get();

        return view('clientes.historial-alquileres', compact('prestamos'));
    }

    /**
     * Vista intermedia para confirmar la reserva/solicitud desde la web.
     */
    public function alquilar(Pelicula $pelicula)
    {
        // Verificar si ya tiene un préstamo ACTIVO (no pendiente)
        $prestamoActivo = Prestamo::where('id_usuario', auth()->id())
            ->where('estado_prestamo', 'activo')
            ->exists();

        if ($prestamoActivo) {
            return redirect()->route('peliculas.index')
                ->with('error', '❌ Ya tienes un préstamo activo. Debes devolver tus copias físicas antes de solicitar otra.');
        }

        // Verificar si ya tiene una solicitud PENDIENTE de esta película
        $solicitudPendiente = Prestamo::where('id_usuario', auth()->id())
            ->where('estado_prestamo', 'pendiente')
            ->whereHas('detalles', function($q) use ($pelicula) {
                $q->where('id_pelicula', $pelicula->id);
            })->exists();

        if ($solicitudPendiente) {
            return redirect()->route('peliculas.index')
                ->with('error', '❌ Ya tienes una solicitud pendiente para esta película. Espera la respuesta del personal.');
        }

        if ($pelicula->copias_en_estante <= 0) {
            return redirect()->route('peliculas.index')
                ->with('error', '❌ No hay existencias disponibles de esta película en mostrador.');
        }

        return view('clientes.confirmar-alquiler', compact('pelicula'));
    }

    /**
     * Procesa la SOLICITUD de reserva que hace el cliente (queda PENDIENTE).
     */
    public function clienteStore(Request $request)
    {
        $request->validate([
            'pelicula_id' => 'required|exists:peliculas,id',
            'dias_prestamo' => 'required|integer|min:1|max:7',
            'metodo_pago' => 'required|in:efectivo,tarjeta,transferencia'
        ]);

        // Verificar si ya tiene un préstamo ACTIVO
        $prestamoActivo = Prestamo::where('id_usuario', auth()->id())
            ->where('estado_prestamo', 'activo')
            ->exists();

        if ($prestamoActivo) {
            return redirect()->route('peliculas.index')->with('error', '❌ Ya tienes un arriendo activo.');
        }

        $pelicula = Pelicula::find($request->pelicula_id);

        // Verificar stock (solo para mostrar disponibilidad, la reserva no consume stock)
        if ($pelicula->copias_en_estante <= 0) {
            return redirect()->route('peliculas.index')->with('error', '❌ No hay copias disponibles en este momento.');
        }

        DB::beginTransaction();
        try {
            $fechaSalida = Carbon::now();
            $dias = (int) $request->dias_prestamo;
            $fechaLimite = $fechaSalida->copy()->addDays($dias);

            // 🔥 CAMBIO IMPORTANTE: El préstamo se crea como 'pendiente', NO como 'activo'
            $prestamo = Prestamo::create([
                'id_usuario' => auth()->id(),
                'id_trabajador' => null,  // Aún no asignado, lo asigna el trabajador al aprobar
                'fecha_salida' => $fechaSalida,
                'fecha_limite' => $fechaLimite,
                'estado_prestamo' => 'pendiente',  // ✅ Cambiado de 'activo' a 'pendiente'
                'multa_total' => 0
            ]);

            DetallePrestamo::create([
                'id_prestamo' => $prestamo->id,
                'id_pelicula' => $pelicula->id,
                'precio_alquiler_momento' => $pelicula->precio_alquiler,
                'monto_multa' => 0
            ]);

            // 🔥 NO se descuenta el stock aquí (solo cuando el trabajador apruebe)
            // $pelicula->decrement('copias_en_estante'); ← COMENTADO

            // 🔥 NO se crea el pago aquí (el pago se hace al retirar en sucursal)
            // Pago::create(...); ← COMENTADO

            DB::commit();

            return redirect()->route('mis-prestamos')
                ->with('success', '📋 ¡Solicitud enviada! El personal revisará tu solicitud y te notificará cuando esté aprobada.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('peliculas.index')->with('error', 'Error al enviar solicitud: ' . $e->getMessage());
        }
    }
}