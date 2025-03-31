@extends('layouts.admin')

@section('content')
    <div class="row justify-content-center mt-md-5">
        <div class="col-md-8">

            <div class="row">
                <div class="col-md-3 text-center mb-2">

                    <a href="{{route('usuarios_create', request()->slug_instalacion)}}"  class="btn btn-primary">Añadir usuario + </a>
            </div>

                @if(session()->has('message'))
                    <div class="alert alert-success">
                        {{ session()->get('message') }}
                    </div>
                @endif


                <div class="card" id="tabla_usuario">
                <h5 class="card-header text-center">Usuarios con acceso</h5>
                <div class="card-body text-center">
                    <table class="table" id="users_table">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Usuario</th>
                            <th scope="col">Acción</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($accesos as $acceso)
                            <tr>
                                <th scope="row">{{$acceso->id}}</th>
                                <td>{{ $acceso->usuarios ? $acceso->usuarios->name : 'Usuario no encontrado' }}</td>
                                <th scope="col"  style="color:green;">
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <a href="{{route('usuario_edit',[request()->slug_instalacion , $acceso->id])}}" class="btn btn-warning">Editar</a>

                                        <a href="/{{ request()->slug_instalacion }}/admin/puertas/usuarios_accesos/{{ $acceso->user_id }}/borrar"  onclick="return confirm('¿Estás seguro que quieres eliminar este acceso?');"  class="btn btn-danger">Eliminar</a>

                                    </div></th>

                            </tr>

                            @endforeach


                        </tbody>
                    </table>
                </div>
            </div>



        </div>



    </div>


    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<script>

    $( document ).ready(function() {
        /* $(".borrar").click(function () {
            var newURL="/admin/puertas/usuarios_accesos/"+$(this).data("usuario")+"/borrar";
            $("#delete_button").attr("href", newURL);
            $("#user_name").text($(this).data("nombre"));
           console.log($(this).data("usuario"));
        }); */

        $('#users_table').DataTable({

            order: [[0, 'desc']],
            language: {
                "decimal": "",
                "emptyTable": "No hay información",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "Mostrar _MENU_ Entradas",
                "loadingRecords": "Cargando...",
                "processing": "Procesando...",
                "search": "Buscar:",
                "zeroRecords": "Sin resultados encontrados",
                "paginate": {
                    "first": "Primero",
                    "last": "Ultimo",
                    "next": "Siguiente",
                    "previous": "Anterior"
                },



            }
        });

    });

</script>



@endsection