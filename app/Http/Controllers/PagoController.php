<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PagoController extends Controller
{
    public function index()
    {
        $pagos = Pago::with(['usuario', 'prestamo'])->orderBy('created_at', 'desc')->paginate(10);
        return view('pagos.index', compact('pagos'));
    }
    
    public function caja()
    {
        // Obtener la fecha actual en zona horaria de El Salvador
        $hoy = Carbon::now('America/El_Salvador')->toDateString();
        
        $totalHoy = Pago::whereDate('created_at', $hoy)->sum('monto');
        $pagosHoy = Pago::whereDate('created_at', $hoy)->get();
        
        return view('pagos.caja', compact('totalHoy', 'pagosHoy'));
    }
}