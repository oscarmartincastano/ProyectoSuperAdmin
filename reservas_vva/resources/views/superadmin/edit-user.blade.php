@extends('layouts.superadmin')

@section('header', 'Editar Usuarios - ' . $ayuntamiento->name)
@section('content')
    <a href="{{ route('superadmin.index') }}" class="btn btn-secondary mb-3">
        <i class="fas fa-arrow-left"></i> Volver
    </a>

    <!-- Buscador -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-slate-800 text-white">
            <h5 class="mb-0"><i class="fas fa-search"></i> Buscar Usuarios</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('superadmin.editUsers', $ayuntamiento->id) }}">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Buscar por nombre o email"
                        value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Buscar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de Usuarios -->
    <div class="card shadow-sm">
        <div class="card-header bg-slate-800 text-white">
            <h5 class="mb-0"><i class="fas fa-users"></i> Lista de Usuarios</h5>
        </div>
        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead style="background-color: #4CAF50; color: white;">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Subrol</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($usuarios as $usuario)
                        <tr>
                            <td>{{ $usuario->id }}</td>
                            <td>{{ $usuario->name }}</td>
                            <td>{{ $usuario->email }}</td>
                            <td>
                                <form method="POST" action="{{ route('superadmin.updateUserRole', [$ayuntamiento->id, $usuario->id]) }}">
                                    @csrf
                                    @method('PUT')
                                    <select name="rol" class="form-select">
                                        <option value="user" {{ $usuario->rol == 'user' ? 'selected' : '' }}>User</option>
                                        <option value="admin" {{ $usuario->rol == 'admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="manager" {{ $usuario->rol == 'manager' ? 'selected' : '' }}>Manager</option>
                                    </select>
                            </td>
                            <td>
                                    <select name="subrol" class="form-select">
                                        <option value="user" {{ $usuario->subrol == 'user' ? 'selected' : '' }}>User</option>
                                        <option value="admin" {{ $usuario->subrol == 'admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="manager" {{ $usuario->subrol == 'manager' ? 'selected' : '' }}>Manager</option>
                                    </select>
                            </td>
                            <td>
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="fas fa-save"></i> Actualizar
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('superadmin.deleteUser', [$ayuntamiento->id, $usuario->id]) }}" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">No se encontraron usuarios.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Paginación -->
    <div class="d-flex justify-content-center mt-4">
        {{ $usuarios->appends(['search' => request('search')])->links('pagination::bootstrap-4') }}
    </div>
@endsection