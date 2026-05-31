@extends('layouts.app')

@section('title', 'Usuarios')

@section('content')
<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-users"></i> Usuarios del Sistema</h1>
        <button class="btn-primary" onclick="showAddUserModal()">
            <i class="fas fa-user-plus"></i> Agregar Usuario
        </button>
    </div>

    @if(session('success'))
        <div class="alert-success-custom">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert-error-custom">{{ session('error') }}</div>
    @endif

    <div class="stats-cards">
        <div class="stat-card blue">
            <i class="fas fa-user-cog"></i>
            <div class="number">{{ $usuarios->where('rol', 'admin')->count() }}</div>
            <div class="label">Administradores</div>
        </div>
        <div class="stat-card green">
            <i class="fas fa-user-tie"></i>
            <div class="number">{{ $usuarios->where('rol', 'trabajador')->count() }}</div>
            <div class="label">Trabajadores</div>
        </div>
        <div class="stat-card purple">
            <i class="fas fa-user"></i>
            <div class="number">{{ $usuarios->where('rol', 'cliente')->count() }}</div>
            <div class="label">Clientes</div>
        </div>
    </div>

    <div class="data-table">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Rol</th>
                    <th>Fecha Registro</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($usuarios as $usuario)
                <tr>
                    <td>#{{ $usuario->id }}</td>
                    <td><strong>{{ $usuario->name }}</strong></td>
                    <td>{{ $usuario->email }}</td>
                    <td>{{ $usuario->telefono ?? 'No registrado' }}</td>
                    <td>
                        @if($usuario->rol == 'admin')
                            <span class="role-admin"><i class="fas fa-crown"></i> Admin</span>
                        @elseif($usuario->rol == 'trabajador')
                            <span class="role-worker"><i class="fas fa-user-tie"></i> Trabajador</span>
                        @else
                            <span class="role-client"><i class="fas fa-user"></i> Cliente</span>
                        @endif
                    </td>
                    <td>{{ $usuario->created_at->format('d/m/Y') }}</td>
                    <td>
                        @if($usuario->rol != 'admin')
                            <button class="btn-delete" onclick="confirmDeleteUser({{ $usuario->id }}, '{{ $usuario->name }}')">
                                <i class="fas fa-trash"></i> Eliminar
                            </button>
                            <form action="{{ route('admin.usuarios.destroy', $usuario->id) }}" method="POST" style="display: none;" id="form-delete-{{ $usuario->id }}">
                                @csrf
                                @method('DELETE')
                            </form>
                        @else
                            <span class="badge-info">Cuenta principal</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="pagination">
            {{ $usuarios->links() }}
        </div>
    </div>
</div>

<!-- Modal Agregar Usuario -->
<div id="addUserModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-user-plus"></i> Agregar Nuevo Usuario</h3>
            <span class="close" onclick="closeModal()">&times;</span>
        </div>
        <form action="{{ route('admin.usuarios.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Nombre Completo</label>
                <input type="text" name="name" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Teléfono</label>
                <input type="text" name="telefono">
            </div>
            <div class="form-group">
                <label>Contraseña</label>
                <input type="password" name="password" required>
            </div>
            <div class="form-group">
                <label>Rol</label>
                <select name="rol" required>
                    <option value="cliente">Cliente</option>
                    <option value="trabajador">Trabajador (Encargado)</option>
                    <option value="admin">Administrador</option>
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeModal()">Cancelar</button>
                <button type="submit" class="btn-save">Guardar Usuario</button>
            </div>
        </form>
    </div>
</div>

<style>
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    color: white;
}
.btn-primary {
    background: #667eea;
    color: white;
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}
.alert-success-custom {
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    color: #155724;
    padding: 1rem;
    border-radius: 10px;
    margin-bottom: 1rem;
    border-left: 4px solid #4caf50;
    animation: slideIn 0.3s ease;
}
.alert-error-custom {
    background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
    color: #721c24;
    padding: 1rem;
    border-radius: 10px;
    margin-bottom: 1rem;
    border-left: 4px solid #f44336;
    animation: slideIn 0.3s ease;
}
@keyframes slideIn {
    from { transform: translateX(100%); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}
.stats-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}
.stat-card {
    background: white;
    border-radius: 10px;
    padding: 1.5rem;
    text-align: center;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}
.stat-card.blue { border-top: 4px solid #2196f3; }
.stat-card.green { border-top: 4px solid #4caf50; }
.stat-card.purple { border-top: 4px solid #9c27b0; }
.stat-card i { font-size: 2rem; margin-bottom: 0.5rem; }
.stat-card .number { font-size: 2rem; font-weight: bold; }
.stat-card .label { color: #666; }
.data-table {
    background: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}
table {
    width: 100%;
    border-collapse: collapse;
}
th, td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid #eee;
}
th {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}
tr:hover {
    background: #f5f5f5;
}
.role-admin { color: #f44336; font-weight: bold; }
.role-worker { color: #2196f3; font-weight: bold; }
.role-client { color: #4caf50; font-weight: bold; }
.badge-info {
    background: #e0e0e0;
    padding: 0.2rem 0.5rem;
    border-radius: 3px;
    font-size: 0.8rem;
}
.btn-delete {
    background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%);
    color: white;
    border: none;
    padding: 0.3rem 0.8rem;
    border-radius: 5px;
    cursor: pointer;
    transition: all 0.3s;
}
.btn-delete:hover {
    transform: translateY(-2px);
}
.pagination {
    padding: 1rem;
    text-align: center;
}
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
}
.modal-content {
    background: white;
    margin: 5% auto;
    width: 90%;
    max-width: 500px;
    border-radius: 10px;
    animation: slideDown 0.3s ease;
}
@keyframes slideDown {
    from { transform: translateY(-50px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}
.modal-header {
    padding: 1rem;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.close {
    font-size: 1.5rem;
    cursor: pointer;
}
.form-group {
    padding: 0.5rem 1rem;
}
.form-group label {
    display: block;
    margin-bottom: 0.3rem;
    font-weight: bold;
}
.form-group input, .form-group select {
    width: 100%;
    padding: 0.5rem;
    border: 1px solid #ddd;
    border-radius: 5px;
}
.modal-footer {
    padding: 1rem;
    border-top: 1px solid #eee;
    display: flex;
    justify-content: flex-end;
    gap: 0.5rem;
}
.btn-cancel {
    background: #9e9e9e;
    color: white;
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}
.btn-save {
    background: #4caf50;
    color: white;
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function showAddUserModal() {
    document.getElementById('addUserModal').style.display = 'block';
}
function closeModal() {
    document.getElementById('addUserModal').style.display = 'none';
}
function confirmDeleteUser(id, nombre) {
    Swal.fire({
        title: '¿Eliminar usuario?',
        text: `¿Estás seguro de eliminar a "${nombre}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#f44336',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(`form-delete-${id}`).submit();
        }
    });
}
</script>
@endsection