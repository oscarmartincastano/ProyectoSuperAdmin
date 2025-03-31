@extends('layouts.admin')

@section('style')
    <style>
        .clickable {
            cursor: pointer;
        }
    </style>    
@endsection

@section('content')

    <div class="row justify-content-center">
        <div class="col-lg-12 m-b-10">
            <div class="p-l-20 p-r-20 p-b-10 pt-3">
                <div>
                    <h3 class="text-primary no-margin">Listado</h3>
                </div>
            </div>
    <div class="card">
        <h5 class="card-header text-center">Registros de aperturas</h5>
        <div class="card-body text-center">
            <table class="table registros-puerta w-100" style="overflow-x: auto !important;-webkit-overflow-scrolling: touch !important;">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Usuario</th>
                    <th scope="col">Fecha</th>
                    <th scope="col">Acción</th>
                    <th scope="col">Tipo de entrada</th>
                </tr>
                </thead>
                <tbody>
                @foreach($registros as $registro)
                    <tr>
                        <th scope="row">{{$registro->id}}</th>
                        <td class="clickable" data-href="/{{ request()->slug_instalacion }}/admin/users/{{ $registro->usuarios->id }}/ver">{{$registro->usuarios->name}}</td>
                        <td>{{Carbon\Carbon::parse($registro->fecha_apertura)->format('d-m-Y H:i:s')}}</td>
                        <td scope="col" class="registro_{{$registro->acciones->tipo}}" >
                            @if($registro->estado == 'entrada_portillo')
                                {{$registro->acciones->mensaje}} - Portillo
                            @elseif($registro->estado == 'entrada_torno')
                                {{$registro->acciones->mensaje}} - Entrada torno
                            @elseif($registro->estado == 'salida_torno')
                                {{$registro->acciones->mensaje}} - Salida torno
                            @elseif($registro->estado == 'entrada_gimnasio_usuario')
                                {{$registro->acciones->mensaje}} - Entrada gimnasio
                            @else
                                {{$registro->acciones->mensaje}}
                            @endif
                        </td>
                        <td scope="col">{{$registro->tipo}}</td>

                    </tr>
                @endforeach

                </tbody>
            </table>
             <!-- Controles de paginación -->
            <div class="mt-3">
                {{ $registros->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script>
        $(document).ready( function () {

            $('.registros-puerta').DataTable({
                responsive:true,
                "info": false,
                "paging": false,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
                },
                "order": [[ 0, "desc"]]
        });

        $('table').on('click', '.clickable', function (e) {
            if (e.target.tagName == 'TD') {
                window.open($(this).data("href"), '_blank'); // Abre en una nueva pestaña
            }
        });
        } );
    </script>
@endsection