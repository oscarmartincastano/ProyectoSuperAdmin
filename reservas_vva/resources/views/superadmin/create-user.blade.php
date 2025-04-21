@extends('layouts.superadmin')

@section('header', 'Crear Nuevo Usuario')

@section('content')
@if (!Auth::check())
<script>
    window.location.href = "{{ route('superadmin.login') }}";
</script>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

    <form action="{{ route('superadmin.createUser') }}" method="POST" class="mt-4">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Nombre</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Correo Electrónico</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
        </div>
        <div class="mb-3">
            <label for="database" class="form-label">Base de Datos</label>
            <select class="form-control @error('database') is-invalid @enderror" id="database" name="database" required>
                <!-- Opción para la base de datos superadmin -->
                <option value="superadmin" {{ old('database') == 'superadmin' ? 'selected' : '' }}>
                    SuperAdmin (superadmin)
                </option>
                <!-- Opciones dinámicas de $superadmin_bds -->
                @foreach ($superadmin_bds as $db)
                    <option value="{{ $db->bd_nombre }}" {{ old('database') == $db->bd_nombre ? 'selected' : '' }}>
                        {{ $db->name }} ({{ $db->bd_nombre }})
                    </option>
                @endforeach
            </select>
            @error('database')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="mb-3">
            <label for="instalacion" class="form-label">Instalación</label>
            <select class="form-control @error('instalacion') is-invalid @enderror" id="instalacion" name="instalacion">
                <option value="">Seleccione una instalación</option>
            </select>
            @error('instalacion')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <!-- Select para Rol y Subrol -->
        <div class="mb-3 d-none" id="roles-container">
            <label for="rol" class="form-label">Rol</label>
            <select class="form-control @error('rol') is-invalid @enderror" id="rol" name="rol">
                <option value="">Seleccione un rol</option>
                <option value="user" {{ old('rol') == 'user' ? 'selected' : '' }}>User</option>
                <option value="admin" {{ old('rol') == 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
            @error('rol')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="mb-3 d-none" id="subroles-container">
            <label for="subrol" class="form-label">Subrol</label>
            <select class="form-control @error('subrol') is-invalid @enderror" id="subrol" name="subrol">
                <option value="">Seleccione un subrol</option>
                <option value="user" {{ old('subrol') == 'user' ? 'selected' : '' }}>User</option>
                <option value="admin" {{ old('subrol') == 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
            @error('subrol')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <button type="submit" class="btn btn-primary">Crear Usuario</button>
        </form>
        
        <script>
            document.getElementById('database').addEventListener('change', function () {
                const database = this.value;
                const instalacionSelect = document.getElementById('instalacion');
                const rolesContainer = document.getElementById('roles-container');
                const subrolesContainer = document.getElementById('subroles-container');
        
                // Limpiar las opciones del select de instalaciones
                instalacionSelect.innerHTML = '<option value="">Seleccione una instalación</option>';
                instalacionSelect.removeAttribute('required'); // Eliminar el atributo required inicialmente
        
                // Ocultar los selects de rol y subrol por defecto
                rolesContainer.classList.add('d-none');
                subrolesContainer.classList.add('d-none');
        
                if (database && database !== 'superadmin') {
                    // Mostrar los selects de rol y subrol
                    rolesContainer.classList.remove('d-none');
                    subrolesContainer.classList.remove('d-none');
        
                    // Realizar la solicitud AJAX para obtener las instalaciones
                    fetch('{{ route('superadmin.getInstalaciones') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ database })
                    })
                    .then(response => response.json())
                    .then(data => {
                        instalacionSelect.setAttribute('required', true);
                        data.forEach(instalacion => {
                            const option = document.createElement('option');
                            option.value = instalacion.id;
                            option.textContent = instalacion.nombre;
                            instalacionSelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error al obtener las instalaciones:', error));
                }
            });
        </script>
        @endsection