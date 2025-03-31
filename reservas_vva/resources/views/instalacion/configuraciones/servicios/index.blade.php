@extends('layouts.admin')

@php
    function checkConsec($d) {
        for($i=0;$i<count($d);$i++) {
            if(isset($d[$i+1]) && $d[$i]+1 != $d[$i+1]) {
                return false;
            }
        }
        return true;
    }
@endphp

@section('content')

    <div class="row">
        <div class="col-lg-12 m-b-10">

            <div class="p-l-20 p-r-20 p-b-10 pt-3">
                <div>
                    <h3 class="text-primary no-margin">{{ auth()->user()->instalacion->nombre }}</h3>
                </div>
            </div>

            <div class="p-t-15 p-b-15 p-l-20 p-r-20">
                <div class="card ">
                    <div class="card-header">
                        <div class="card-title">Listado de servicios</div>
                    </div>
                    <div class="card-body">
                        <a href="/{{ request()->slug_instalacion }}/admin/configuracion/servicios/crear" class="text-white btn btn-primary">Añadir servicio</a>
                        @if (request()->slug_instalacion != "superate")
                        <a href="/{{ request()->slug_instalacion }}/admin/configuracion/servicios/listar_participantes" class="btn btn-success">Ver abonados</a>
                            
                        @endif
                        <table class="table table-hover" id="table-reservas" style="width: 100% !important;">
                            <thead>
                            <tr>
                                <th data-priority="1">Nombre</th>
                                <th>tipo</th>
                                <th data-priority="2">precio</th>
                                <th>Tipo de deporte</th>
                                <th>Pista</th>
                                <th></th>

                            </tr>
                            </thead>
                            <tbody>
                            @foreach($servicios as $servicio)
                            <tr>
                                <td>{{$servicio->nombre}}</td>
                                <td>{{$servicio->tipo}}</td>
                                <td>{{$servicio->precio}}€</td>
                                <td>{{$servicio->espacio->nombre}}</td>
                                <td>@if(isset($servicio->pista_id))
                                        {{$servicio->pista->nombre}}
                                    @else
                                        Todos
                                    @endif</td>
                                <td><div class="d-flex" style="gap: 3px">
                                        <a href="/{{ request()->slug_instalacion }}/admin/configuracion/servicios/{{$servicio->id}}/edit" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                                        <a href="/{{ request()->slug_instalacion }}/admin/configuracion/servicios/{{ $servicio->id }}/delete" class="btn btn-danger" onclick="return confirm('¿Estás seguro que quieres borrar este servicio?')"><i class="fas fa-trash"></i></a>

                                    </div></td>
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

@section('script')
    <script>
        $(document).ready(function () {

            $('#table-reservas').DataTable({
                responsive:true,
                "info": false,
                "paging": false,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
                },
                "order": [[ 0, "desc"]]
            });
        });
    </script>
@endsection
