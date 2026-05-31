<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    /**
     * Muestra el listado global de usuarios registrados en el sistema.
     */
    public function index()
    {
        // Doble seguridad perimetral: Saca a cualquiera que no sea administrador
        if (auth()->user()->rol !== 'admin') {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        $usuarios = User::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.usuarios.index', compact('usuarios'));
    }

    /**
     * Procesa e inserta un nuevo usuario en la base de datos de MovieSpace.
     */
    public function store(Request $request)
    {
        // 1. Validar rigurosamente todos los campos del formulario
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'rol' => 'required|in:admin,trabajador,cliente',
            'telefono' => 'nullable|string|max:15',
            'dui' => 'nullable|string|max:10',        // Soportado para evitar fallos NOT NULL de MySQL
            'direccion' => 'nullable|string|max:255',  // Soportado para evitar fallos NOT NULL de MySQL
        ]);

        try {
            // 2. Crear el registro inyectando la estructura de datos obligatoria
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'rol' => $request->rol,
                'telefono' => $request->telefono,
                'dui' => $request->dui ?? '',             // Si viene vacío, se guarda cadena limpia
                'direccion' => $request->direccion ?? '', // Si viene vacío, se guarda cadena limpia
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()->route('admin.usuarios.index')
                ->with('success', '✅ Usuario ' . $request->rol . ' creado exitosamente: ' . $request->name);
        } catch (\Exception $e) {
            // En caso de fallar algo físico en MySQL de tu PC, te reportará la razón exacta
            return back()->withInput()->with('error', '❌ Error crítico en MySQL: ' . $e->getMessage());
        }
    }

    /**
     * Elimina un usuario del sistema aplicando restricciones de integridad.
     */
    public function destroy($id)
    {
        $usuario = User::findOrFail($id);

        // Restricción 1: Bloquear la eliminación del administrador del sistema
        if ($usuario->rol === 'admin') {
            return back()->with('error', '❌ No se puede eliminar al administrador principal.');
        }

        // Restricción 2: Evitar el "suicidio" de cuenta (eliminar la sesión propia activa)
        if ($usuario->id === auth()->id()) {
            return back()->with('error', '❌ No puedes eliminar tu propio usuario.');
        }

        // Restricción 3: Validar que un cliente no deje órdenes de alquiler huerfanas y activas
        if ($usuario->rol === 'cliente') {
            $tienePrestamosActivos = $usuario->prestamosCliente()
                ->where('estado_prestamo', 'activo')
                ->exists();

            if ($tienePrestamosActivos) {
                return back()->with('error', '❌ No se puede eliminar el cliente porque tiene préstamos activos en mostrador.');
            }
        }

        // Si pasa todos los filtros de seguridad, se ejecuta el borrado físico
        $usuario->delete();

        return back()->with('success', '✅ Usuario eliminado correctamente del sistema.');
    }
}
