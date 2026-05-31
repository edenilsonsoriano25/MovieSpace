<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Prestamo;
use App\Models\Pago;
use Illuminate\Http\Request;

class ReporteController extends Controller
{
    public function index()
    {
        $totalPrestamos = Prestamo::count();
        $totalIngresos = Pago::sum('monto');
        $prestamosActivos = Prestamo::where('estado_prestamo', 'activo')->count();
        
        return view('admin.reportes.index', compact('totalPrestamos', 'totalIngresos', 'prestamosActivos'));
    }
    
    public function generarPDF($mes, $anio)
    {
        // Implementar generación de PDF con dompdf
        return redirect()->back()->with('info', 'Reporte en construcción');
    }
}