@extends('layouts.superadmin')

@section('header', 'Listado de Instalaciones')

@section('content')
@if (!Auth::check())
<script>
    window.location.href = "{{ route('superadmin.login') }}";
</script>
@endif
    <a href="{{ route('superadmin.create') }}" class="btn btn-success mb-3">Nueva Instalación</a>
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-slate-800 text-white">
            <h5 class="mb-0"><i class="fas fa-search"></i> Buscar Instalaciones</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('superadmin.index') }}">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Buscar por nombre o ID"
                        value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Buscar
                    </button>
                </div>
            </form>
        </div>
    </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($ayuntamientos as $ayuntamiento)
                <tr>
                    <td>{{ $ayuntamiento->id }}</td>
                    <td>{{ $ayuntamiento->name }}</td>
                    <td>
                        <a href="{{ $ayuntamiento->url }}" class="btn btn-primary">Ver Online</a>
                        <a href="{{ route('superadmin.edit', $ayuntamiento->id) }}" class="btn btn-warning">Editar</a>
                        <a href="{{ route('superadmin.editUsers', $ayuntamiento->id) }}" class="btn btn-info">
                            <i class="fas fa-users"></i> Editar Usuarios
                        </a>
                        <form id="delete-form-{{ $ayuntamiento->id }}" action="{{ route('superadmin.destroy', $ayuntamiento->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $ayuntamiento->id }})">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

<!-- Paginación -->
<div class="d-flex justify-content-center mt-4">
    <nav>
        <ul class="pagination pagination-lg">
            {{ $ayuntamientos->links('pagination::bootstrap-4') }}
        </ul>
    </nav>
</div>
@endsection