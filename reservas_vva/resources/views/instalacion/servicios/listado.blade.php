@extends('layouts.admin')

@section('pagename', 'Listado servicios')

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
                    <h3 class="text-primary no-margin">Listado servicios contratados</h3>
                </div>
            </div>

            <div class="p-t-15 p-b-15 p-l-20 p-r-20">
                <div class="card ">
                    <div class="card-header">
                        <div class="card-title">Serivicios contratados</div>
                    </div>
                    <div class="card-body">
                        <table class="table table-hover" id="table-reservas" style="width: 100% !important;">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Cliente</th>
                                    <th>Servicio contratado</th>
                                    <th>Fecha de expiración</th>
                                    <th>Activo</th>
                                </tr>
                            </thead>
                            <tbody>
                            @if(count($servicio)>0)
                                @foreach ($servicio as $item)
                                    <tr>
                                        <td>{{$item->id_usuario}}</td>
                                        <td><a href="/{{ request()->slug_instalacion }}/admin/users/{{ $item->user2->id }}/ver">{{ $item->user2->name }}</a></td>
                                        <td>{!! $item->servicio->nombre !!}</td>
                                        <td>{!! \Carbon\Carbon::parse($item->fecha_expiracion)->format('d-m-Y') !!}</td>
                                        <td>
                                            @if($item->activo == 'si')
                                                SI
                                            @else
                                                NO
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                @else
                                <tr><td colspan="4">No hay servicios activos</td></tr>
                            @endif
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
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'excel',
                    text: 'Exportar Excel',
                    exportOptions: {
                        modifier: {
                            page: 'current'
                        }
                    }
                }
            ],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
                },
                "order": [[ 0, "desc"]]
            });
        });
    </script>
@endsection
