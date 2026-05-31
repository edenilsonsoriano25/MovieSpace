<?php

namespace App\Http\Controllers;

use App\Models\Prestamo;
use App\Models\Pelicula;
use App\Models\DetallePrestamo;
use App\Models\Pago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PrestamoController extends Controller
{
    public function index()
    {
        if (auth()->user()->rol === 'cliente') {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }
        
        $prestamos = Prestamo::with(['usuario', 'trabajador', 'detalles.pelicula'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('prestamos.index', compact('prestamos'));
    }
    
    public function create()
    {
        if (auth()->user()->rol === 'cliente') {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }
        
        $clientes = \App\Models\User::where('rol', 'cliente')->get();
        $peliculas = Pelicula::where('copias_en_estante', '>', 0)->get();
        
        return view('prestamos.create', compact('clientes', 'peliculas'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'id_usuario' => 'required|exists:users,id',
            'peliculas' => 'required|array|min:1',
            'peliculas.*' => 'exists:peliculas,id',
            'dias_prestamo' => 'required|integer|min:1|max:7',
            'metodo_pago' => 'required|in:efectivo,tarjeta,transferencia'
        ]);
        
        $prestamoActivo = Prestamo::where('id_usuario', $request->id_usuario)
            ->where('estado_prestamo', 'activo')
            ->exists();
            
        if ($prestamoActivo) {
            return back()->with('error', '❌ El cliente tiene un préstamo activo.');
        }
        
        foreach ($request->peliculas as $peliculaId) {
            $pelicula = Pelicula::find($peliculaId);
            if ($pelicula->copias_en_estante <= 0) {
                return back()->with('error', "❌ No hay stock disponible de: {$pelicula->titulo}");
            }
        }
        
        DB::beginTransaction();
        
        try {
            $fechaSalida = Carbon::now();
            $dias = (int) $request->dias_prestamo;
            $fechaLimite = $fechaSalida->copy()->addDays($dias);
            
            $prestamo = Prestamo::create([
                'id_usuario' => $request->id_usuario,
                'id_trabajador' => auth()->id(),
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
                
                $pelicula->decrement('copias_en_estante');
                $totalPagar += $pelicula->precio_alquiler;
            }
            
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
                ->with('success', '✅ Préstamo registrado exitosamente. Total: $' . number_format($totalPagar, 2));
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
    
    public function devolucion(Prestamo $prestamo)
    {
        if ($prestamo->estado_prestamo !== 'activo') {
            return back()->with('error', 'Este préstamo ya fue completado.');
        }
        
        DB::beginTransaction();
        
        try {
            $fechaEntrega = Carbon::now();
            $fechaLimite = Carbon::parse($prestamo->fecha_limite);
            
            $multaTotal = 0;
            if ($fechaEntrega->gt($fechaLimite)) {
                $diasRetraso = $fechaEntrega->diffInDays($fechaLimite);
                $multaPorDia = 1.50;
                $multaTotal = $diasRetraso * $multaPorDia;
                
                foreach ($prestamo->detalles as $detalle) {
                    $detalle->update(['monto_multa' => $multaTotal / $prestamo->detalles->count()]);
                }
            }
            
            $prestamo->update([
                'fecha_entrega_real' => $fechaEntrega,
                'estado_prestamo' => 'completado',
                'multa_total' => $multaTotal
            ]);
            
            foreach ($prestamo->detalles as $detalle) {
                $detalle->pelicula->increment('copias_en_estante');
            }
            
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
                ? "✅ Devolución completada. Multa: $" . number_format($multaTotal, 2)
                : "✅ Devolución completada exitosamente.";
                
            return redirect()->route('prestamos.index')->with('success', $mensaje);
            
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
    
    public function misPrestamos()
{
    // Obtener el usuario actual
    $usuario = auth()->user();
    
    // Obtener préstamos del usuario
    $prestamos = Prestamo::with(['detalles.pelicula', 'trabajador'])
        ->where('id_usuario', $usuario->id)
        ->orderBy('created_at', 'desc')
        ->get();
    
    // Depuración: ver cuántos préstamos hay
    \Log::info('Usuario ID: ' . $usuario->id);
    \Log::info('Cantidad de préstamos: ' . $prestamos->count());
    
    return view('clientes.mis-prestamos', compact('prestamos'));
}
    
    public function alquilar(Pelicula $pelicula)
    {
        $prestamoActivo = Prestamo::where('id_usuario', auth()->id())
            ->where('estado_prestamo', 'activo')
            ->exists();
            
        if ($prestamoActivo) {
            return redirect()->route('peliculas.index')
                ->with('error', '❌ Tienes un préstamo activo. Debes devolver la película actual antes de alquilar otra.');
        }
        
        if ($pelicula->copias_en_estante <= 0) {
            return redirect()->route('peliculas.index')
                ->with('error', '❌ No hay stock disponible de esta película.');
        }
        
        if (auth()->user()->rol === 'cliente') {
            return view('clientes.confirmar-alquiler', compact('pelicula'));
        }
        
        return redirect()->route('prestamos.create', ['pelicula_id' => $pelicula->id]);
    }
    
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
            return redirect()->route('peliculas.index')
                ->with('error', '❌ Tienes un préstamo activo.');
        }
        
        $pelicula = Pelicula::find($request->pelicula_id);
        
        if ($pelicula->copias_en_estante <= 0) {
            return redirect()->route('peliculas.index')
                ->with('error', '❌ No hay stock disponible.');
        }
        
        DB::beginTransaction();
        
        try {
            $fechaSalida = Carbon::now();
            $dias = (int) $request->dias_prestamo;
            $fechaLimite = $fechaSalida->copy()->addDays($dias);
            
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
                ->with('success', '✅ Alquiler registrado. Devolver antes del ' . $fechaLimite->format('d/m/Y'));
                
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('peliculas.index')
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }
}