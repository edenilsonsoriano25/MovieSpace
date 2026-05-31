<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use Illuminate\Http\Request;

class PagoController extends Controller
{
    public function index()
    {
        $pagos = Pago::with(['usuario', 'prestamo'])->orderBy('created_at', 'desc')->paginate(10);
        return view('pagos.index', compact('pagos'));
    }
    
    public function caja()
    {
        $totalHoy = Pago::whereDate('created_at', today())->sum('monto');
        $pagosHoy = Pago::whereDate('created_at', today())->get();
        return view('pagos.caja', compact('totalHoy', 'pagosHoy'));
    }
}