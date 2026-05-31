@extends('layouts.app')

@section('title', 'Usuarios')

@section('content')
<div class="admin-dark-wrapper">
    <div class="container-fluid px-4 px-md-5">
        
        <div class="admin-page-header">
            <div class="header-left">
                <span class="header-badge"><i class="fas fa-shield-alt"></i> Panel de Control Administrativo</span>
                <h1 class="admin-main-title"><i class="fas fa-users"></i> Usuarios del Sistema</h1>
                <p class="admin-main-subtitle">Administra los accesos globales, asigna roles operativos y audita las cuentas del personal y clientes de Jayaque.</p>
            </div>
            <button class="btn-premium-action btn-add-user" onclick="showAddUserModal()">
                <i class="fas fa-user-plus"></i> Agregar Usuario
            </button>
        </div>

        @if(session('success'))
            <div class="toast-alert alert-success-premium">
                <div class="toast-icon-box"><i class="fas fa-check-circle"></i></div>
                <div class="toast-content">{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="toast-alert alert-error-premium">
                <div class="toast-icon-box"><i class="fas fa-exclamation-circle"></i></div>
                <div class="toast-content">{{ session('error') }}</div>
            </div>
        @endif

        <div class="admin-stats-grid">
            <div class="admin-stat-card border-glow-blue">
                <div class="stat-icon-box icon-blue"><i class="fas fa-user-cog"></i></div>
                <div class="stat-info">
                    <div class="stat-number">{{ \App\Models\User::where('rol', 'admin')->count() }}</div>
                    <div class="stat-label">Administradores</div>
                </div>
            </div>
            
            <div class="admin-stat-card border-glow-teal">
                <div class="stat-icon-box icon-teal"><i class="fas fa-user-tie"></i></div>
                <div class="stat-info">
                    <div class="stat-number">{{ \App\Models\User::where('rol', 'trabajador')->count() }}</div>
                    <div class="stat-label">Trabajadores</div>
                </div>
            </div>
            
            <div class="admin-stat-card border-glow-muted">
                <div class="stat-icon-box icon-muted"><i class="fas fa-user"></i></div>
                <div class="stat-info">
                    <div class="stat-number">{{ \App\Models\User::where('rol', 'cliente')->count() }}</div>
                    <div class="stat-label">Clientes Registrados</div>
                </div>
            </div>
        </div>

        <div class="premium-table-wrapper">
            <table class="premium-data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Correo Electrónico</th>
                        <th>DUI</th>
                        <th>Teléfono</th>
                        <th>Rol Asignado</th>
                        <th>Fecha Registro</th>
                        <th style="text-align: center;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($usuarios as $usuario)
                    <tr>
                        <td class="td-id">#{{ str_pad($usuario->id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td class="td-name"><strong>{{ $usuario->name }}</strong></td>
                        <td class="td-email">{{ $usuario->email }}</td>
                        <td class="td-dui" style="font-family: monospace;">{{ $usuario->dui ?: '—' }}</td>
                        <td class="td-phone">{{ $usuario->telefono ?: '—' }}</td>
                        <td>
                            @if($usuario->rol == 'admin')
                                <span class="badge-role-pill role-pill-admin"><i class="fas fa-crown"></i> Admin</span>
                            @elseif($usuario->rol == 'trabajador')
                                <span class="badge-role-pill role-pill-worker"><i class="fas fa-user-tie"></i> Trabajador</span>
                            @else
                                <span class="badge-role-pill role-pill-client"><i class="fas fa-user"></i> Cliente</span>
                            @endif
                        </td>
                        <td class="td-date">{{ $usuario->created_at->format('d/m/Y') }}</td>
                        <td style="text-align: center;">
                            @if($usuario->rol != 'admin')
                                <button class="btn-table-delete" onclick="confirmDeleteUser({{ $usuario->id }}, '{{ $usuario->name }}')">
                                    <i class="fas fa-trash-alt"></i> Eliminar
                                </button>
                                <form action="{{ route('admin.usuarios.destroy', $usuario->id) }}" method="POST" style="display: none;" id="form-delete-{{ $usuario->id }}">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            @else
                                <span class="badge-system-lock"><i class="fas fa-lock"></i> Principal</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            @if($usuarios->hasPages())
                <div class="premium-pagination-box">
                    {{ $usuarios->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<div id="addUserModal" class="modal-premium-overlay">
    <div class="modal-premium-content">
        <div class="modal-premium-header">
            <h3><i class="fas fa-user-plus"></i> Agregar Nuevo Usuario</h3>
            <span class="close-modal-btn" onclick="closeModal()">&times;</span>
        </div>
        
        <div class="modal-scroll-body">
            <form action="{{ route('admin.usuarios.store') }}" method="POST" class="modal-premium-form" id="createUserForm">
                @csrf
                
                <div class="modal-input-group">
                    <label for="modal-name">Nombre Completo</label>
                    <input type="text" name="name" id="modal-name" placeholder="Ej: Bryan Ismael Coreas" required>
                </div>
                
                <div class="modal-input-group">
                    <label for="modal-email">Correo Electrónico</label>
                    <input type="email" name="email" id="modal-email" placeholder="correo@ejemplo.com" required>
                </div>

                <div class="modal-form-row">
                    <div class="modal-input-group">
                        <label for="modal-dui">DUI <span class="label-optional">(Opcional)</span></label>
                        <input type="text" name="dui" id="modal-dui" placeholder="00000000-0">
                    </div>
                    
                    <div class="modal-input-group">
                        <label for="modal-phone">Teléfono <span class="label-optional">(Opcional)</span></label>
                        <input type="text" name="telefono" id="modal-phone" placeholder="7000-0000">
                    </div>
                </div>
                
                <div class="modal-input-group">
                    <label for="modal-role">Rol del Sistema</label>
                    <div class="select-wrapper">
                        <select name="rol" id="modal-role" required>
                            <option value="cliente">Cliente</option>
                            <option value="trabajador">Trabajador (Encargado)</option>
                            <option value="admin">Administrador</option>
                        </select>
                    </div>
                </div>

                <div class="modal-input-group">
                    <label for="modal-direction">Dirección Residencial <span class="label-optional">(Opcional)</span></label>
                    <input type="text" name="direccion" id="modal-direction" placeholder="Ej: Calle Principal, Jayaque">
                </div>
                
                <div class="modal-input-group">
                    <label for="modal-password">Contraseña Provisional</label>
                    <input type="password" name="password" id="modal-password" placeholder="Mínimo 8 caracteres" minlength="8" required>
                </div>
            </form>
        </div>

        <div class="modal-premium-footer">
            <button type="button" class="btn-modal-cancel" onclick="closeModal()">Cancelar</button>
            <button type="submit" form="createUserForm" class="btn-modal-save">Guardar Registro</button>
        </div>
    </div>
</div>

<style>
    .admin-dark-wrapper {
        background-color: #0f1115;
        min-height: 100vh;
        margin-top: -2rem;
        padding: 3rem 0 5rem 0;
        color: #ffffff;
        font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
    }

    .admin-page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 3rem;
        gap: 1.5rem;
    }

    .header-badge {
        background-color: rgba(255, 65, 108, 0.08);
        color: #ff4b2b;
        font-size: 0.75rem;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 4px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        margin-bottom: 0.5rem;
    }

    .admin-main-title {
        font-size: 2.6rem;
        font-weight: 800;
        margin: 0 0 0.4rem 0;
        background: linear-gradient(45deg, #ff416c, #ff4b2b);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .admin-main-title i {
        color: #ff4b2b;
        -webkit-text-fill-color: initial;
        margin-right: 10px;
    }

    .admin-main-subtitle {
        color: #6c757d;
        font-size: 0.98rem;
        margin: 0;
    }

    .btn-add-user {
        background: linear-gradient(45deg, #ff416c, #ff4b2b);
        color: #ffffff;
        border: none;
        padding: 12px 24px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.92rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 15px rgba(255, 65, 108, 0.3);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        flex-shrink: 0;
    }

    .btn-add-user:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(255, 65, 108, 0.4);
    }

    .admin-stats-grid {
        display: grid !important;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)) !important;
        gap: 1.5rem !important;
        margin-bottom: 3rem;
        width: 100%;
    }

    .admin-stat-card {
        background-color: #1a1d24;
        border-radius: 14px;
        padding: 1.25rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 1.25rem;
        border: 1px solid rgba(255, 255, 255, 0.02);
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }

    .border-glow-blue { border-left: 4px solid #2196f3; }
    .border-glow-teal { border-left: 4px solid #2ec4b6; }
    .border-glow-muted { border-left: 4px solid #6c757d; }

    .stat-icon-box {
        width: 48px;
        height: 48px;
        background-color: #111317;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .icon-blue { color: #2196f3; }
    .icon-teal { color: #2ec4b6; }
    .icon-muted { color: #6c757d; }

    .stat-info { display: flex; flex-direction: column; }
    .stat-number { font-size: 1.8rem; font-weight: 800; color: #fff; margin: 0; }
    .stat-label { font-size: 0.82rem; color: #6c757d; font-weight: 600; }

    .premium-table-wrapper {
        background-color: #1a1d24;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        border: 1px solid rgba(255,255,255,0.02);
    }

    .premium-data-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }

    .premium-data-table th {
        background: linear-gradient(135deg, #1f232b 0%, #14161c 100%);
        color: #ffffff;
        font-size: 0.88rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        padding: 16px 20px;
        border-bottom: 1px solid rgba(255,255,255,0.04);
    }

    .premium-data-table td {
        padding: 16px 20px;
        border-bottom: 1px solid rgba(255,255,255,0.02);
        color: #d1d1d1;
        font-size: 0.95rem;
        vertical-align: middle;
    }

    .premium-data-table tbody tr:hover td {
        background-color: #222731 !important;
        color: #ffffff !important;
        cursor: pointer;
    }

    .td-id { font-family: monospace; color: #ff4b2b !important; font-weight: 600; }
    .td-name { color: #ffffff; }
    .td-email { color: #b3b3b3; }
    .td-date { color: #8a8a8a; font-size: 0.9rem; }

    .badge-role-pill {
        font-size: 0.76rem;
        font-weight: 700;
        padding: 5px 12px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .role-pill-admin { background-color: rgba(244, 67, 54, 0.12); color: #ef5350; }
    .role-pill-worker { background-color: rgba(33, 150, 243, 0.12); color: #42a5f5; }
    .role-pill-client { background-color: rgba(46, 196, 182, 0.12); color: #2ec4b6; }

    .badge-system-lock {
        font-size: 0.8rem;
        font-weight: 600;
        color: #495057;
        background-color: #111317;
        padding: 5px 12px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        border: 1px solid rgba(255,255,255,0.02);
    }

    .btn-table-delete {
        background: rgba(244, 67, 54, 0.08);
        color: #ef5350;
        border: 1px solid rgba(244, 67, 54, 0.2);
        padding: 6px 14px;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .btn-table-delete:hover {
        background: #d32f2f;
        color: #ffffff;
        border-color: transparent;
        box-shadow: 0 4px 12px rgba(211, 47, 47, 0.2);
    }

    .premium-pagination-box {
        padding: 1.25rem;
        border-top: 1px solid rgba(255,255,255,0.03);
        display: flex;
        justify-content: center;
    }

    /* ── MAQUETACIÓN PREMIUM ARQUITECTÓNICA DEL MODAL ── */
    .modal-premium-overlay {
        display: none;
        position: fixed;
        z-index: 2000;
        left: 0; top: 0; width: 100%; height: 100%;
        background: rgba(10, 11, 14, 0.8);
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px);
    }

    .modal-premium-content {
        background-color: #1a1d24;
        margin: 3% auto;
        width: 92%;
        max-width: 520px;
        border-radius: 16px;
        box-shadow: 0 20px 50px rgba(0,0,0,0.6);
        border: 1px solid rgba(255,255,255,0.03);
        animation: modalSlideDown 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
        overflow: hidden;
        
        /* Forzamos control flex total para anclar elementos */
        max-height: 85vh;
        display: flex;
        flex-direction: column;
    }

    @keyframes modalSlideDown {
        from { transform: translateY(-20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    .modal-premium-header {
        padding: 1.25rem 1.5rem;
        background-color: #121419;
        border-bottom: 1px solid rgba(255,255,255,0.04);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-shrink: 0; /* Bloquea el encogimiento */
    }

    .modal-premium-header h3 {
        margin: 0; color: #ffffff; font-size: 1.2rem; font-weight: 700;
        display: flex; align-items: center; gap: 8px;
    }

    .modal-premium-header h3 i { color: #ff4b2b; }

    .close-modal-btn {
        font-size: 1.6rem; color: #6c757d; cursor: pointer; transition: color 0.2s;
        line-height: 1;
    }
    .close-modal-btn:hover { color: #ffffff; }

    /* CONTENEDOR INTELIGENTE CON SCROLL DINÁMICO */
    .modal-scroll-body {
        overflow-y: auto;
        flex-grow: 1;
        padding: 0.5rem 0;
    }

    .modal-premium-form {
        padding: 1rem 1.5rem;
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .modal-form-row { display: flex; gap: 1.25rem; }
    .modal-form-row .modal-input-group { flex: 1; }

    .modal-input-group { display: flex; flex-direction: column; gap: 0.4rem; }
    .modal-input-group label { color: #cdcdcd; font-size: 0.85rem; font-weight: 600; }
    .label-optional { color: #495057; font-size: 0.78rem; }

    .modal-input-group input, 
    .modal-input-group select {
        width: 100%;
        padding: 11px 14px;
        background-color: #111317;
        border: 1px solid rgba(255,255,255,0.05);
        border-radius: 8px;
        color: #ffffff;
        font-size: 0.95rem;
        outline: none;
        box-sizing: border-box;
        font-family: inherit;
        transition: border 0.2s;
    }

    .modal-input-group input:focus,
    .modal-input-group select:focus {
        border-color: #ff4b2b;
    }

    .select-wrapper { position: relative; width: 100%; }
    .select-wrapper select { appearance: none; -webkit-appearance: none; padding-right: 35px; cursor: pointer;}
    .select-wrapper::after {
        content: '\f078'; font-family: 'Font Awesome 5 Free'; font-weight: 900;
        font-size: 0.75rem; color: #6c757d; position: absolute; right: 14px; top: 50%;
        transform: translateY(-50%); pointer-events: none;
    }

    /* PIE DE MODAL BLINDADO CONTRA DESPLAZAMIENTOS */
    .modal-premium-footer {
        background-color: #121419;
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
        border-top: 1px solid rgba(255,255,255,0.04);
        padding: 1rem 1.5rem;
        flex-shrink: 0; /* Forzado a mantenerse estático en la base */
    }

    .btn-modal-cancel {
        background-color: #2a2e35; color: #b3b3b3; border: none; padding: 10px 18px;
        border-radius: 8px; font-weight: 600; font-size: 0.88rem; cursor: pointer; transition: all 0.2s;
    }
    .btn-modal-cancel:hover { background-color: #343a44; color: #ffffff; }

    .btn-modal-save {
        background: linear-gradient(45deg, #2ec4b6, #009688); color: #ffffff; border: none;
        padding: 10px 20px; border-radius: 8px; font-weight: 600; font-size: 0.88rem; cursor: pointer;
        box-shadow: 0 4px 12px rgba(46, 196, 182, 0.2); transition: transform 0.2s;
    }
    .btn-modal-save:hover { transform: translateY(-1px); }

    .toast-alert {
        display: flex; align-items: center; gap: 12px; padding: 12px 18px; border-radius: 10px;
        color: #fff; font-weight: 600; font-size: 0.9rem; margin-bottom: 1.5rem;
        box-shadow: 0 8px 20px rgba(0,0,0,0.2); animation: modalSlideDown 0.3s ease;
    }
    .alert-success-premium { background: #0ca678; border-left: 5px solid #02b875; }
    .alert-error-premium { background: #f03e3e; border-left: 5px solid #ff1a1a; }
    .toast-icon-box { font-size: 1.1rem; }

    @media (max-width: 768px) {
        .admin-page-header { flex-direction: column; align-items: flex-start; gap: 1rem; }
        .modal-form-row { flex-direction: column; gap: 1rem; }
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function showAddUserModal() {
    document.getElementById('addUserModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('addUserModal').style.display = 'none';
    document.getElementById('createUserForm').reset();
}

window.onclick = function(event) {
    const modal = document.getElementById('addUserModal');
    if (event.target == modal) {
        modal.style.display = "none";
        document.getElementById('createUserForm').reset();
    }
}

function confirmDeleteUser(id, nombre) {
    Swal.fire({
        title: '¿Eliminar usuario?',
        text: `¿Estás seguro de eliminar permanentemente a "${nombre}"? Esta acción no se puede deshacer.`,
        icon: 'warning',
        background: '#1a1d24',
        color: '#ffffff',
        showCancelButton: true,
        confirmButtonColor: '#ff5252',
        cancelButtonColor: '#2a2e35',
        confirmButtonText: '<i class="fas fa-trash-alt"></i> Sí, eliminar',
        cancelButtonText: 'Cancelar',
        customClass: {
            popup: 'swal-premium-dark-fix'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(`form-delete-${id}`).submit();
        }
    });
}
</script>
@endsection