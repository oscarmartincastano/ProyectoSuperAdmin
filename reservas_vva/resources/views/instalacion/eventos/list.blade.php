@extends('layouts.admin')

@section('pagename', 'Eventos creados')

@section('style')
    <style>
        .clickable {
            cursor: pointer;
        }
        .nav-tabs a.active, .nav-tabs a.active:focus {
            color: white !important;
            background: #0057bc !important;
        }
    </style>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12 m-b-10">

            <div class="p-l-20 p-r-20 p-b-10 pt-3">
                <div>
                    <h3 class="text-primary no-margin">Eventos</h3>
                </div>
            </div>

            <div class="p-t-15 p-b-15 p-l-20 p-r-20">
                <div class="card ">
                    <div class="card-header">
                        <div class="card-title">Eventos creados</div>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-tabs justify-content-center mb-2">
                            <li class="active" onclick="return false;"><a href="#mensuales" class="active show">Escuelas</a></li>
                            <li><a href="#casuales" onclick="return false;">Eventos</a></li>
                        </ul>
                        <a href="/{{ request()->slug_instalacion }}/admin/eventos/add" class="text-white btn btn-primary">Añadir nuevo</a>
                        <div class="tab-content">
                            <div id="mensuales" class="tab-pane fade in active show">
                                <table class="table table-hover" id="table-reservas" style="width: 100% !important;">
                                    <thead>
                                        <tr>
                                            <th data-priority="1">Cartel</th>
                                            <th data-priority="2">Fecha</th>
                                            <th>Fecha de inscripción</th>
                                            <th>Título</th>
                                            <th>Nº Máx de participantes</th>
                                            {{-- <th>Nº participantes actuales</th> --}}
                                            <th>Tipo participantes</th>
                                            <th>Tipo inscripción</th>
                                            <th>#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($eventos->where('renovacion_mes', 1) as $item)
                                            @php
                                                $fechaFin = strtotime($item->insc_fecha_fin);
                                                $fechaActual = strtotime(date('Y-m-d'));// Obtén la fecha actual                                    
                                            @endphp
                                             @if ($fechaFin > $fechaActual)
                                                <tr>
                                                    <td><img src="/img/eventos/{{ request()->slug_instalacion }}/{{ $item->id }}.jpg" style="max-height:125px;max-width:95px"></td>
                                                    <td>{{ date('d/m', strtotime($item->fecha_inicio)) }} - {{ date('d/m', strtotime($item->fecha_fin)) }}</td>
                                                    <td>{{ date('d/m', strtotime($item->insc_fecha_inicio)) }} - {{ date('d/m', strtotime($item->insc_fecha_fin)) }}</td>
                                                    <td>{{ $item->nombre }}</td>
                                                    <td>{{ $item->num_participantes > 5000 ? 'Ilimitado' : $item->num_participantes }}</td>
                                                    {{-- <td>{{ $item->participantes->where('estado', 'active')->count() }}</td> --}}
                                                    <td>{{ $item->tipo_participante->nombre }}</td>
                                                    <td>{{ $item->renovacion_mes ? 'Inscripción mensual' : 'Inscripción normal' }}</td>
                                                    <td>
                                                        <div class="d-flex" style="gap: 3px">
                                                            <a href="/{{ request()->slug_instalacion }}/admin/eventos/{{ $item->id }}" class="btn btn-warning"><i class="far fa-eye"></i></a>
                                                            <a href="/{{ request()->slug_instalacion }}/admin/eventos/{{ $item->id }}/edit" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div id="casuales" class="tab-pane fade">
                                <table class="table table-hover" id="table-reservas2" style="width: 100% !important;">
                                    <thead>
                                        <tr>
                                            <th data-priority="1">Cartel</th>
                                            <th data-priority="2">Fecha</th>
                                            <th>Fecha de inscripción</th>
                                            <th>Título</th>
                                            <th>Nº Máx de participantes</th>
                                            <th>Nº participantes actuales</th>
                                            <th>Tipo participantes</th>
                                            <th>Tipo inscripción</th>
                                            <th>#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($eventos->where('renovacion_mes', 0) as $item)
                                            <tr>
                                                <td><img src="/img/eventos/{{ request()->slug_instalacion }}/{{ $item->id }}.jpg" style="max-height:125px;max-width:95px"></td>
                                                <td>{{ date('d/m', strtotime($item->fecha_inicio)) }} - {{ date('d/m', strtotime($item->fecha_fin)) }}</td>
                                                <td>{{ date('d/m', strtotime($item->insc_fecha_inicio)) }} - {{ date('d/m', strtotime($item->insc_fecha_fin)) }}</td>
                                                <td>{{ $item->nombre }}</td>
                                                <td>{{ $item->num_participantes > 5000 ? 'Ilimitado' : $item->num_participantes }}</td>
                                                <td>{{ $item->participantes->where('estado', 'active')->count() }}</td>
                                                <td>{{ $item->tipo_participante->nombre }}</td>
                                                <td>{{ $item->renovacion_mes ? 'Inscripción mensual' : 'Inscripción normal' }}</td>
                                                <td>
                                                    <div class="d-flex" style="gap: 3px">
                                                        <a href="/{{ request()->slug_instalacion }}/admin/eventos/{{ $item->id }}" class="btn btn-warning"><i class="far fa-eye"></i></a>
                                                        <a href="/{{ request()->slug_instalacion }}/admin/eventos/{{ $item->id }}/edit" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
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

            $('#table-reservas2').DataTable({
                 responsive:true,
                "info": false,
                "paging": false,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
                },
                "order": [[ 0, "desc"]]
            });

        $(".nav-tabs a").click(function(){
            $('.tab-content>div').removeClass('in active show');
            $('.nav-tabs li, .nav-tabs a').removeClass('active show');
            $(this).tab('show');
        });
    });
</script>
@endsection
