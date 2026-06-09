<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

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
     * Muestra el formulario para editar un usuario.
     */
    public function edit($id)
    {
        // Seguridad: Solo administradores
        if (auth()->user()->rol !== 'admin') {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        $usuario = User::findOrFail($id);

        // Prevenir que el admin se edite a sí mismo desde aquí (opcional pero seguro)
        if ($usuario->id === auth()->id()) {
            return redirect()->route('admin.usuarios.index')
                ->with('error', '❌ No puedes editarte a ti mismo desde aquí. Usa la sección "Mi Perfil".');
        }

        return view('admin.usuarios.edit', compact('usuario'));
    }

    /**
     * Actualiza la información de un usuario en el sistema.
     */
    public function update(Request $request, $id)
    {
        // Seguridad: Solo administradores
        if (auth()->user()->rol !== 'admin') {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        $usuario = User::findOrFail($id);

        // Prevenir auto-edición
        if ($usuario->id === auth()->id()) {
            return redirect()->route('admin.usuarios.index')
                ->with('error', '❌ No puedes editarte a ti mismo desde aquí.');
        }

        // Validación de campos
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($usuario->id),
            ],
            'rol' => 'required|in:admin,trabajador,cliente',
            'telefono' => 'nullable|string|max:15',
            'dui' => 'nullable|string|max:10',
            'direccion' => 'nullable|string|max:255',
            'password' => 'nullable|min:8|confirmed',
        ]);

        try {
            // Preparar datos para actualizar
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'rol' => $request->rol,
                'telefono' => $request->telefono,
                'dui' => $request->dui ?? '',
                'direccion' => $request->direccion ?? '',
            ];

            // Si se proporcionó una nueva contraseña, la actualizamos
            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            // Actualizar usuario
            $usuario->update($data);

            return redirect()->route('admin.usuarios.index')
                ->with('success', '✅ Usuario actualizado exitosamente: ' . $usuario->name);

        } catch (\Exception $e) {
            return back()->withInput()->with('error', '❌ Error al actualizar: ' . $e->getMessage());
        }
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
            'dui' => 'nullable|string|max:10',
            'direccion' => 'nullable|string|max:255',
        ]);

        try {
            // 2. Crear el registro inyectando la estructura de datos obligatoria
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'rol' => $request->rol,
                'telefono' => $request->telefono,
                'dui' => $request->dui ?? '',
                'direccion' => $request->direccion ?? '',
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

        // Restricción 3: Validar que un cliente no deje órdenes de alquiler huérfanas y activas
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