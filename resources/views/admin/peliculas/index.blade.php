@extends('layouts.app')

@section('title', 'Administrar Catálogo')

@section('content')
<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-film"></i> Administrar Catálogo</h1>
        <a href="{{ route('admin.peliculas.create') }}" class="btn-success">
            <i class="fas fa-plus-circle"></i> + Agregar Película
        </a>
    </div>

    @if(session('success'))
        <div class="alert-success-custom">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert-error-custom">{{ session('error') }}</div>
    @endif

    <div class="data-table">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Género</th>
                    <th>Director</th>
                    <th>Año</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Disponibles</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($peliculas as $pelicula)
                <tr>
                    <td>#{{ $pelicula->id }}</td>
                    <td><strong>{{ $pelicula->titulo }}</strong></td>
                    <td>{{ $pelicula->genero }}</td>
                    <td>{{ $pelicula->director }}</td>
                    <td class="text-center">{{ $pelicula->año }}</td>
                    <td class="text-success">${{ number_format($pelicula->precio_alquiler, 2) }}</td>
                    <td class="text-center">{{ $pelicula->copias_totales }}</td>
                    <td>
                        @if($pelicula->copias_en_estante > 0)
                            <span class="stock-badge available">{{ $pelicula->copias_en_estante }} disponibles</span>
                        @else
                            <span class="stock-badge out">Agotado</span>
                        @endif
                    </td>
                    <td class="actions">
                        <button class="btn-edit" onclick="confirmEdit({{ $pelicula->id }})">
                            <i class="fas fa-edit"></i> Editar
                        </button>
                        <button class="btn-delete" onclick="confirmDelete({{ $pelicula->id }}, '{{ $pelicula->titulo }}')">
                            <i class="fas fa-trash"></i> Eliminar
                        </button>
                        <form action="{{ route('admin.peliculas.destroy', $pelicula) }}" method="POST" style="display: none;" id="form-delete-{{ $pelicula->id }}">
                            @csrf
                            @method('DELETE')
                        </form>
                    </td>
                </tr>
                @empty
                    <tr>
                        <td colspan="9" style="text-align: center;">No hay películas registradas</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="pagination">
            {{ $peliculas->links() }}
        </div>
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
.btn-success {
    background: #4caf50;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 5px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-weight: bold;
    transition: all 0.3s;
}
.btn-success:hover {
    background: #45a049;
    transform: scale(1.05);
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
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}
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
.text-success {
    color: #4caf50;
    font-weight: bold;
}
.text-center {
    text-align: center;
}
.stock-badge {
    display: inline-block;
    padding: 0.2rem 0.5rem;
    border-radius: 5px;
    font-size: 0.8rem;
}
.stock-badge.available {
    background: #4caf50;
    color: white;
}
.stock-badge.out {
    background: #f44336;
    color: white;
}
.actions {
    display: flex;
    gap: 0.5rem;
}
.btn-edit {
    background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
    color: white;
    padding: 0.3rem 0.8rem;
    border-radius: 5px;
    text-decoration: none;
    font-size: 0.8rem;
    border: none;
    cursor: pointer;
    transition: all 0.3s;
}
.btn-edit:hover {
    transform: translateY(-2px);
    box-shadow: 0 2px 5px rgba(255,152,0,0.3);
}
.btn-delete {
    background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%);
    color: white;
    border: none;
    padding: 0.3rem 0.8rem;
    border-radius: 5px;
    cursor: pointer;
    font-size: 0.8rem;
    transition: all 0.3s;
}
.btn-delete:hover {
    transform: translateY(-2px);
    box-shadow: 0 2px 5px rgba(244,67,54,0.3);
}
.pagination {
    padding: 1rem;
    text-align: center;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmEdit(id) {
    Swal.fire({
        title: '¿Editar película?',
        text: "Vas a modificar los datos de esta película",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#ff9800',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, editar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `/admin/peliculas/${id}/edit`;
        }
    });
}

function confirmDelete(id, titulo) {
    Swal.fire({
        title: '¿Eliminar película?',
        text: `¿Estás seguro de eliminar "${titulo}"? Esta acción no se puede deshacer.`,
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

setTimeout(function() {
    let alerts = document.querySelectorAll('.alert-success-custom, .alert-error-custom');
    alerts.forEach(alert => {
        alert.style.opacity = '0';
        setTimeout(() => alert.remove(), 300);
    });
}, 3000);
</script>
@endsection