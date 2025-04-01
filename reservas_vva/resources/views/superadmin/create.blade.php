@extends('layouts.superadmin')

@section('title', 'Crear Ayuntamiento')
@section('header', 'Crear Nuevo Ayuntamiento')

@section('content')
@if (!Auth::check())
<script>
    window.location.href = "{{ route('superadmin.login') }}";
</script>
@endif
    <h1 class="mb-4 text-center">Formulario de Creación</h1>

    {{-- Formulario para crear un nuevo ayuntamiento --}}
    <form action="{{ route('superadmin.store') }}" method="POST" class="bg-light p-4 rounded shadow-sm">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Nombre</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                value="{{ old('name') }}" required>
            @error('name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="url" class="form-label">Ruta</label>
            <input type="text" class="form-control @error('url') is-invalid @enderror" id="url" name="url"
                value="{{ old('url') }}" required>
            @error('url')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="bd_nombre" class="form-label">Nombre de la base de datos</label>
            <input type="text" class="form-control @error('bd_nombre') is-invalid @enderror" id="bd_nombre"
                name="bd_nombre" value="{{ old('bd_nombre') }}" required>
            @error('bd_nombre')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Crear</button>
    </form>
@endsection