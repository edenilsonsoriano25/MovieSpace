<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pelicula;
use App\Models\Prestamo;
use App\Models\Pago;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    public function index()
    {
        // 1. KPIs Globales Historicos
        $totalPrestamos = Prestamo::count();
        $totalIngresos = Pago::sum('monto');
        $prestamosActivos = Prestamo::where('estado_prestamo', 'activo')->count();

        // 2. Obtener Historial Mensual Agrupado Consolidador Real (Año Actual)
        $anioActual = Carbon::now()->year;

        // Inicializamos arreglos limpios para los 12 meses
        $conteosMensuales = array_fill(1, 12, 0);
        $ingresosMensuales = array_fill(1, 12, 0);
        $multasMensuales = array_fill(1, 12, 0);

        // Agrupación de Préstamos por mes
        $prestamosPorMes = Prestamo::select(
            DB::raw('MONTH(fecha_salida) as mes'),
            DB::raw('COUNT(*) as total')
        )
            ->whereYear('fecha_salida', $anioActual)
            ->groupBy('mes')
            ->get();

        foreach ($prestamosPorMes as $p) {
            $conteosMensuales[$p->mes] = $p->total;
        }

        // Agrupación de Pagos por mes (Suma de caja general)
        $pagosPorMes = Pago::select(
            DB::raw('MONTH(created_at) as mes'),
            DB::raw('SUM(monto) as total')
        )
            ->whereYear('created_at', $anioActual)
            ->groupBy('mes')
            ->get();

        foreach ($pagosPorMes as $p) {
            $ingresosMensuales[$p->mes] = (float)$p->total;
        }

        // Agrupación de recargos liquidados por concepto de multa real en el año
        // Se calcula basándose en préstamos devueltos que registraron valor físico guardado en multa_total
        $multasPorMes = Prestamo::select(
            DB::raw('MONTH(fecha_entrega_real) as mes'),
            DB::raw('SUM(multa_total) as total')
        )
            ->whereYear('fecha_entrega_real', $anioActual)
            ->where('estado_prestamo', 'devuelto')
            ->groupBy('mes')
            ->get();

        foreach ($multasPorMes as $m) {
            $multasMensuales[$m->mes] = (float)$m->total;
        }

        // 3. Distribución para gráfico circular (Alquiler base vs Multas acumuladas)
        $totalMultasHistorico = Prestamo::where('estado_prestamo', 'devuelto')->sum('multa_total');
        $totalAlquilerPuro = max($totalIngresos - $totalMultasHistorico, 0);

        return view('admin.reportes.index', compact(
            'totalPrestamos',
            'totalIngresos',
            'prestamosActivos',
            'conteosMensuales',
            'ingresosMensuales',
            'multasMensuales',
            'totalMultasHistorico',
            'totalAlquilerPuro'
        ));
    }

    public function dashboard()
    {
        $cajaHoy = Pago::whereDate('created_at', Carbon::today())->sum('monto');
        $prestamosActivos = Prestamo::where('estado_prestamo', 'activo')->count();
        $totalPeliculas = Pelicula::count();
        $devolucionesRetrasadas = Prestamo::where('estado_prestamo', 'activo')
            ->where('fecha_limite', '<', Carbon::now())
            ->count();

        return view('admin.dashboard', compact(
            'cajaHoy',
            'prestamosActivos',
            'totalPeliculas',
            'devolucionesRetrasadas'
        ));
    }

    public function generarPDF($mes, $anio)
    {
        return redirect()->back()->with('info', 'Reporte en construcción');
    }
}
