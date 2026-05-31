<?php

namespace App\Http\Controllers;

use App\Models\Prestamo;
use App\Models\Pelicula;
use App\Models\DetallePrestamo;
use App\Models\Pago;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
// Implementación obligatoria del contrato de Middlewares para Laravel 11
use Illuminate\Routing\Controllers\HasMiddleware;

class PrestamoController extends Controller implements HasMiddleware
{
    /**
     * Define los middlewares del controlador de forma estática (Laravel 11).
     */
    public static function middleware(): array
    {
        return [
            // Aplica el middleware 'auth' a todos los métodos operativos
            'auth',
        ];
    }

    /**
     * Listado global de préstamos para el personal administrativo y técnico.
     */
    public function index()
    {
        if (auth()->user()->rol === 'cliente') {
            return redirect()->route('mis-prestamos');
        }

        $prestamos = Prestamo::with(['usuario', 'trabajador', 'detalles.pelicula'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('prestamos.index', compact('prestamos'));
    }

    /**
     * Formulario de apertura de alquileres en mostrador físico.
     */
    public function create()
    {
        if (auth()->user()->rol === 'cliente') {
            return redirect()->route('mis-prestamos');
        }

        $clientes = User::where('rol', 'cliente')->get();
        $peliculas = Pelicula::where('copias_en_estante', '>', 0)->get();

        return view('prestamos.create', compact('clientes', 'peliculas'));
    }

    /**
     * Procesa la inserción del préstamo desde el mostrador (Staff).
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_usuario' => 'required|exists:users,id',
            'peliculas' => 'required|array|min:1',
            'peliculas.*' => 'exists:peliculas,id',
            'dias_prestamo' => 'required|integer|min:1|max:7',
            'metodo_pago' => 'required|in:efectivo,tarjeta,transferencia'
        ]);

        // Evita que un afiliado acumule más de una orden activa de forma simultánea
        $prestamoActivo = Prestamo::where('id_usuario', $request->id_usuario)
            ->where('estado_prestamo', 'activo')
            ->exists();

        if ($prestamoActivo) {
            return back()->with('error', '❌ El cliente seleccionado ya posee una orden de arriendo activa en el sistema.');
        }

        // Validación rigurosa perimetral de stock físico antes de abrir la transacción
        foreach ($request->peliculas as $peliculaId) {
            $pelicula = Pelicula::find($peliculaId);
            if ($pelicula->copias_en_estante <= 0) {
                return back()->with('error', "❌ Operación abortada. No quedan copias físicas en estante para: {$pelicula->titulo}");
            }
        }

        DB::beginTransaction();
        try {
            $fechaSalida = Carbon::now();
            $dias = (int) $request->dias_prestamo;
            $fechaLimite = $fechaSalida->copy()->addDays($dias);

            $prestamo = Prestamo::create([
                'id_usuario' => $request->id_usuario,
                'id_trabajador' => auth()->id(), // Registra la firma del empleado en turno
                'fecha_salida' => $fechaSalida,
                'fecha_limite' => $fechaLimite,
                'estado_prestamo' => 'activo'
            ]);

            $totalPagar = 0;

            foreach ($request->peliculas as $peliculaId) {
                $pelicula = Pelicula::find($peliculaId);

                DetallePrestamo::create([
                    'id_prestamo' => $prestamo->id,
                    'id_pelicula' => $peliculaId,
                    'precio_alquiler_momento' => $pelicula->precio_alquiler,
                    'monto_multa' => 0
                ]);

                // Decrementa las existencias físicas del estante de CDs
                $pelicula->decrement('copias_en_estante');
                $totalPagar += $pelicula->precio_alquiler;
            }

            // Registra la auditoría del cobro en el libro diario de caja
            Pago::create([
                'id_prestamo' => $prestamo->id,
                'id_usuario' => auth()->id(),
                'monto' => $totalPagar,
                'concepto' => 'alquiler',
                'metodo_pago' => $request->metodo_pago,
                'fecha_pago' => $fechaSalida
            ]);

            DB::commit();
            return redirect()->route('prestamos.index')
                ->with('success', '✅ Alquiler en mostrador procesado. Total liquidado: $' . number_format($totalPagar, 2));
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error crítico de base de datos: ' . $e->getMessage());
        }
    }

    /**
     * Procesa el retorno y recepción de las películas devueltas en mostrador.
     */
    public function devolucion(Prestamo $prestamo)
    {
        if ($prestamo->estado_prestamo !== 'activo') {
            return back()->with('error', '❌ Esta orden de préstamo ya ha sido cerrada previamente.');
        }

        DB::beginTransaction();
        try {
            $fechaEntrega = Carbon::now();
            $fechaLimite = Carbon::parse($prestamo->fecha_limite);

            $multaTotal = 0;

            // Lógica algorítmica de cálculo de mora y penalizaciones de MovieSpace
            if ($fechaEntrega->gt($fechaLimite)) {
                $diasRetraso = (int) $fechaEntrega->diffInDays($fechaLimite);
                $multaPorDia = 1.50; // Tarifa fija por retraso diario de CDs
                $multaTotal = $diasRetraso * $multaPorDia;

                // Divide la multa equitativamente entre los productos arrendados
                $montoPorPelicula = $multaTotal / max($prestamo->detalles->count(), 1);
                foreach ($prestamo->detalles as $detalle) {
                    $detalle->update(['monto_multa' => $montoPorPelicula]);
                }
            }

            $prestamo->update([
                'fecha_entrega_real' => $fechaEntrega,
                'estado_prestamo' => 'completado',
                'multa_total' => $multaTotal
            ]);

            // Devuelve e incrementa el stock físico de las películas al estante
            foreach ($prestamo->detalles as $detalle) {
                $detalle->pelicula->increment('copias_en_estante');
            }

            // Si acumuló mora, se inyecta la penalización contable a la caja chica
            if ($multaTotal > 0) {
                Pago::create([
                    'id_prestamo' => $prestamo->id,
                    'id_usuario' => auth()->id(),
                    'monto' => $multaTotal,
                    'concepto' => 'multa',
                    'metodo_pago' => request('metodo_pago', 'efectivo'),
                    'fecha_pago' => $fechaEntrega
                ]);
            }

            DB::commit();

            $mensaje = $multaTotal > 0
                ? "⚠️ Devolución recibida con retraso. Se cargó una multa de: $" . number_format($multaTotal, 2)
                : "✅ Devolución completada exitosamente. CDs reingresados al estante.";

            return redirect()->route('prestamos.index')->with('success', $mensaje);
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error en el reingreso: ' . $e->getMessage());
        }
    }
}
