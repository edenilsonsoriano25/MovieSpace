<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function index()
    {
        if (auth()->user()->rol !== 'admin') {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }
        
        $usuarios = User::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.usuarios.index', compact('usuarios'));
    }
    
    public function store(Request $request)
    {
        // Validar los datos
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'rol' => 'required|in:admin,trabajador,cliente',
            'telefono' => 'nullable|string|max:15',
        ]);
        
        try {
            // Crear el usuario
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'rol' => $request->rol,
                'telefono' => $request->telefono,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            return redirect()->route('admin.usuarios.index')
                ->with('success', '✅ Usuario ' . $request->rol . ' creado exitosamente: ' . $request->name);
                
        } catch (\Exception $e) {
            return back()->with('error', '❌ Error al crear usuario: ' . $e->getMessage());
        }
    }
    
    public function destroy($id)
    {
        $usuario = User::findOrFail($id);
        
        if ($usuario->rol === 'admin') {
            return back()->with('error', '❌ No se puede eliminar al administrador principal.');
        }
        
        if ($usuario->id === auth()->id()) {
            return back()->with('error', '❌ No puedes eliminar tu propio usuario.');
        }
        
        if ($usuario->rol === 'cliente') {
            $tienePrestamosActivos = $usuario->prestamosCliente()
                ->where('estado_prestamo', 'activo')
                ->exists();
            
            if ($tienePrestamosActivos) {
                return back()->with('error', '❌ No se puede eliminar el cliente porque tiene prestamos activos.');
            }
        }
        
        $usuario->delete();
        
        return back()->with('success', '✅ Usuario eliminado correctamente');
    }
}