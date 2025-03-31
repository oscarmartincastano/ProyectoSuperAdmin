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
                        <div class="card-title">Listado de bonos</div>
                    </div>
                    <div class="card-body">
                        <a href="/{{ request()->slug_instalacion }}/admin/configuracion/bonos/crear" class="text-white btn btn-primary">Añadir Bono</a>
                        <table class="table table-hover" id="table-reservas" style="width: 100% !important;">
                            <thead>
                            <tr>
                                <th data-priority="1">Nombre</th>
                                <th>Precio</th>
                                <th>Número de usos</th>
                                <th>Deporte</th>
                                <th>Activado</th>
                                <th>#</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($bonos as $bono)
                            <tr>
                                <td>{{$bono->nombre}}</td>
                                <td>{{$bono->precio}}€</td>
                                <td>{{$bono->num_usos}}</td>
                                <td>{{$bono->deporte->nombre}}</td>
                                @if($bono->activado == 1)
                                    <td>Si</td>
                                @else
                                    <td>No</td>
                                @endif
                                <td><div class="d-flex" style="gap: 3px">
                                        <a href="/{{ request()->slug_instalacion }}/admin/configuracion/bonos/{{$bono->id}}/edit" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                                        <a href="/{{ request()->slug_instalacion }}/admin/configuracion/bonos/{{ $bono->id }}/delete" class="btn btn-danger" onclick="return confirm('¿Estás seguro que quieres borrar este bono?')"><i class="fas fa-trash"></i></a>
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
