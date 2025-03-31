<!-- filepath: e:\xampp\htdocs\reservas_vva\resources\views\layouts\superadmin.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SuperAdmin - @yield('pagename')</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <!-- Custom Styles -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa; /* Fondo claro */
        }

        .navbar {
            background-color: #203a74; /* Azul oscuro */
        }

        .navbar-brand, .navbar-nav .nav-link {
            color: white !important;
        }

        .navbar-brand:hover, .navbar-nav .nav-link:hover {
            color: #ef7d1a !important; /* Naranja */
        }

        .active {
            color: #ef7d1a !important; /* Naranja para el enlace activo */
        }
    </style>
</head>

<body>
    {{-- si no estoy iniciado sesion lo mando a la ruta de login --}}
    @if (!Auth::check())
    <script>
        window.location.href = "{{ route('superadmin.login') }}";
    </script>
@endif

    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">SuperAdmin</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('superadmin.showCreateUserForm') }}">Crear Usuario</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('superadmin.logout') }}">Cerrar sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container mt-4">
        <h1>Listado de Ayuntamientos</h1>

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
                            <a href="{{$ayuntamiento->url}}" class="btn btn-primary">Ver</a>
                            <a href="{{ route('superadmin.edit', $ayuntamiento->id) }}" class="btn btn-warning">Editar</a>
                            <form id="delete-form-{{ $ayuntamiento->id }}" action="{{ route('superadmin.destroy', $ayuntamiento->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $ayuntamiento->id }})">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>

    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "Esta acción no se puede deshacer.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-form-${id}`).submit();
            }
        });
    }
</script>
</body>

</html>