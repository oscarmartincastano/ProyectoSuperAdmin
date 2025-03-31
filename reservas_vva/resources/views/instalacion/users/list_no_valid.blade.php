@extends('layouts.admin')

@section('style')
    <style>
        .clickable {
            cursor: pointer;
        }
    </style>    
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12 m-b-10">
        
            <div class="p-l-20 p-r-20 p-b-10 pt-3">
                <div>
                    <h3 class="text-primary no-margin">Usuarios sin validar</h3>
                </div>
            </div>
            
            <div class="p-t-15 p-b-15 p-l-20 p-r-20">
                <div class="card ">
                    <div class="card-header">
                        <div class="card-title">Listado de usuarios sin validar</div>
                    </div>
                    <div class="card-body">
                        <table class="table table-condensed table-hover" id="table-users">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th>Tlfno</th>
                                    <th>Rol</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($instalacion->users_sin_validar as $item)
                                    <tr class="clickable" data-href="/{{ request()->slug_instalacion }}/admin/users/{{ $item->id }}/ver">
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->email }}</td>
                                        <td>{{ $item->tlfno }}</td>
                                        <td>{{ $item->rol }}</td>
                                        <td>
                                            <a href="/{{ $instalacion->slug }}/admin/users/{{ $item->id }}/validar" class="btn-activate btn btn-success" onclick="return confirm('¿Estás seguro que quieres validar este usuario?');" title="Validar usuario">Validar</a>
                                            <a href="/{{ $instalacion->slug }}/admin/users/{{ $item->id }}/borrar-permanente" class="btn-activate btn btn-danger" onclick="return confirm('¿Estás seguro que quieres borrar este usuario?');" title="Borrar usuario">Borrar</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <p class="small no-margin">
                </p>
            </div>
        
    </div>
</div>
@endsection