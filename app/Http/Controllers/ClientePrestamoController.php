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
            ->orderBy('created_at', 'desc')
            ->get();

        return view('clientes.historial-alquileres', compact('prestamos'));
    }

    /**
     * Vista intermedia para confirmar el alquiler desde la web.
     */
    public function alquilar(Pelicula $pelicula)
    {
        $prestamoActivo = Prestamo::where('id_usuario', auth()->id())
            ->where('estado_prestamo', 'activo')
            ->exists();

        if ($prestamoActivo) {
            return redirect()->route('peliculas.index')
                ->with('error', '❌ Ya tienes un préstamo activo. Debes devolver tus copias físicas antes de alquilar otra.');
        }

        if ($pelicula->copias_en_estante <= 0) {
            return redirect()->route('peliculas.index')
                ->with('error', '❌ No hay existencias disponibles de esta película en mostrador.');
        }

        return view('clientes.confirmar-alquiler', compact('pelicula'));
    }

    /**
     * Procesa la reserva o alquiler directo que hace el cliente.
     */
    public function clienteStore(Request $request)
    {
        $request->validate([
            'pelicula_id' => 'required|exists:peliculas,id',
            'dias_prestamo' => 'required|integer|min:1|max:7',
            'metodo_pago' => 'required|in:efectivo,tarjeta,transferencia'
        ]);

        $prestamoActivo = Prestamo::where('id_usuario', auth()->id())
            ->where('estado_prestamo', 'activo')
            ->exists();

        if ($prestamoActivo) {
            return redirect()->route('peliculas.index')->with('error', '❌ Ya tienes un arriendo activo.');
        }

        $pelicula = Pelicula::find($request->pelicula_id);

        if ($pelicula->copias_en_estante <= 0) {
            return redirect()->route('peliculas.index')->with('error', '❌ Stock agotado.');
        }

        DB::beginTransaction();
        try {
            $fechaSalida = Carbon::now();
            $dias = (int) $request->dias_prestamo;
            $fechaLimite = $fechaSalida->copy()->addDays($dias);

            // Registramos el préstamo vinculando al administrador por defecto (ID: 1) o un ID operativo existente
            $prestamo = Prestamo::create([
                'id_usuario' => auth()->id(),
                'id_trabajador' => 1,
                'fecha_salida' => $fechaSalida,
                'fecha_limite' => $fechaLimite,
                'estado_prestamo' => 'activo'
            ]);

            DetallePrestamo::create([
                'id_prestamo' => $prestamo->id,
                'id_pelicula' => $pelicula->id,
                'precio_alquiler_momento' => $pelicula->precio_alquiler,
                'monto_multa' => 0
            ]);

            $pelicula->decrement('copias_en_estante');

            Pago::create([
                'id_prestamo' => $prestamo->id,
                'id_usuario' => auth()->id(),
                'monto' => $pelicula->precio_alquiler,
                'concepto' => 'alquiler',
                'metodo_pago' => $request->metodo_pago,
                'fecha_pago' => $fechaSalida
            ]);

            DB::commit();
            return redirect()->route('mis-prestamos')
                ->with('success', '✅ ¡Película apartada! Retira tu copia física en sucursal antes del ' . $fechaLimite->format('d/m/Y'));
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('peliculas.index')->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
