@extends('layouts.superadmin')

@section('header', 'Crear Nueva Instalación')

@section('content')
@if (!Auth::check())
<script>
    window.location.href = "{{ route('superadmin.login') }}";
</script>
@endif

    {{-- Formulario para crear una nueva instalación --}}
    <form action="{{ route('superadmin.store') }}" method="POST" class="bg-light p-4 rounded shadow-sm">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Nombre de la instalación</label>
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

        <div class="mb-3">
            <label for="direccion" class="form-label">Direccion</label>
            <input type="text" class="form-control @error('direccion') is-invalid @enderror" id="direccion"
                name="direccion" value="{{ old('direccion') }}" required>
            @error('direccion')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="tlfno" class="form-label">Teléfono</label>
            <input type="text" class="form-control @error('tlfno') is-invalid @enderror" id="tlfno"
                name="tlfno" value="{{ old('tlfno') }}" required>
            @error('tlfno')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="slug" class="form-label">Slug</label>
            <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug"
                value="{{ old('slug') }}" required>
            @error('slug')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror   
        </div>
        <button type="submit" class="btn btn-primary">Crear</button>
    </form>
@endsection