<?php

namespace App\Http\Controllers;

use App\Models\Pelicula;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeliculaController extends Controller
{
    public function index()
    {
        $peliculas = Pelicula::all();
        return view('peliculas.index', compact('peliculas'));
    }
    
    public function adminIndex()
    {
        if (auth()->user()->rol !== 'admin') {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }
        
        $peliculas = Pelicula::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.peliculas.index', compact('peliculas'));
    }
    
    public function create()
    {
        if (auth()->user()->rol !== 'admin') {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }
        
        return view('admin.peliculas.create');
    }
    
    public function store(Request $request)
    {
        if (auth()->user()->rol !== 'admin') {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }
        
        $request->validate([
            'titulo' => 'required|string|max:255',
            'sinopsis' => 'required|string',
            'genero' => 'required|string|max:100',
            'director' => 'required|string|max:255',
            'año' => 'required|integer|min:1900|max:2026',
            'precio_alquiler' => 'required|numeric|min:0',
            'copias_totales' => 'required|integer|min:1',
            'copias_en_estante' => 'required|integer|min:0',
        ]);
        
        Pelicula::create([
            'titulo' => $request->titulo,
            'sinopsis' => $request->sinopsis,
            'genero' => $request->genero,
            'director' => $request->director,
            'año' => $request->año,
            'precio_alquiler' => $request->precio_alquiler,
            'copias_totales' => $request->copias_totales,
            'copias_en_estante' => $request->copias_en_estante,
        ]);
        
        return redirect()->route('admin.peliculas.index')
            ->with('success', '✅ Película agregada exitosamente');
    }
    
    public function edit(Pelicula $pelicula)
    {
        if (auth()->user()->rol !== 'admin') {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }
        
        return view('admin.peliculas.edit', compact('pelicula'));
    }
    
    public function update(Request $request, Pelicula $pelicula)
    {
        if (auth()->user()->rol !== 'admin') {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }
        
        $request->validate([
            'titulo' => 'required|string|max:255',
            'sinopsis' => 'required|string',
            'genero' => 'required|string|max:100',
            'director' => 'required|string|max:255',
            'año' => 'required|integer|min:1900|max:2026',
            'precio_alquiler' => 'required|numeric|min:0',
            'copias_totales' => 'required|integer|min:1',
            'copias_en_estante' => 'required|integer|min:0',
        ]);
        
        $pelicula->update([
            'titulo' => $request->titulo,
            'sinopsis' => $request->sinopsis,
            'genero' => $request->genero,
            'director' => $request->director,
            'año' => $request->año,
            'precio_alquiler' => $request->precio_alquiler,
            'copias_totales' => $request->copias_totales,
            'copias_en_estante' => $request->copias_en_estante,
        ]);
        
        return redirect()->route('admin.peliculas.index')
            ->with('success', '✅ Película actualizada exitosamente');
    }
    
    public function destroy(Pelicula $pelicula)
    {
        if (auth()->user()->rol !== 'admin') {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }
        
        $tienePrestamos = $pelicula->detallePrestamos()->whereHas('prestamo', function($q) {
            $q->where('estado_prestamo', 'activo');
        })->exists();
        
        if ($tienePrestamos) {
            return back()->with('error', '❌ No se puede eliminar la película porque tiene préstamos activos.');
        }
        
        $pelicula->delete();
        
        return redirect()->route('admin.peliculas.index')
            ->with('success', '✅ Película eliminada exitosamente');
    }
    
    public function search(Request $request)
    {
        $query = $request->get('search');
        
        $peliculas = Pelicula::where('titulo', 'LIKE', "%{$query}%")
            ->orWhere('genero', 'LIKE', "%{$query}%")
            ->orWhere('director', 'LIKE', "%{$query}%")
            ->get();
            
        return response()->json($peliculas);
    }
}