<h1>Nueva usuario registrado</h1>

<div>
    <ul>
        <li><strong>Nombre: </strong>{{ $user->name }}</li>
        <li><strong>Email: </strong>{{ $user->email }}</li>
        <li><strong>Fecha de registro: </strong>{{ $user->created_at }}</li>
    </ul>
</div>

<div style="margin-top:20px">
    Puedes validarlos en el listado de validación de usuarios.<br>
    <a href="{{ url('') }}/{{ $user->instalacion->slug }}/admin/users/novalid">Ver listado</a>
</div>