@extends('layouts.superadmin')

@section('header', 'Listado de Ayuntamientos')

@section('content')
@if (!Auth::check())
<script>
    window.location.href = "{{ route('superadmin.login') }}";
</script>
@endif
    <a href="{{ route('superadmin.create') }}" class="btn btn-success mb-3">Nuevo Ayuntamiento</a>
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
@endsection