@extends('new.layout.base')

@section('style')
<style>
    body > main {
        background: white;
    }

    .menu-main-header{
        display: none;
    }

    .titulo-card {
        margin-bottom: 16px;
        border-bottom: 1px solid rgba(47,51,51,.1);
        padding-bottom: 24px;
        font-weight: 600;
        line-height: 1.4;
        font-size: 32px;
    }
    .contenido-principal {
        padding: 20px;
    }
    th {
        border-top: 0 !important;
    }
    .table-eventos td {
        vertical-align: middle;
    }
    .nav-tabs {
        display: flex;
        align-items: center;
        font-size: 16px;
        border-bottom: 0;
    }
    .nav-tabs a {
        display: block;
        padding: 1em 0;
        line-height: 1em;
        border-bottom: 2px solid transparent;
        text-decoration: none;
        opacity: .6;
        -webkit-transition: all .15s;
        transition: all .15s;
        position: relative;
        opacity: 0.75;
        color: black;
    }
    .nav-tabs a.active {
        border-bottom-color: #335fff;
        opacity: 1;
    }
    .nav-tabs li:nth-child(2)>a {
        margin-left: 2em;
    }
    .tab-content{
        margin-top: 32px;
    }
    .footer{
        display: none;
    }
    @media(max-width: 992px) {
        .contenido-principal {
            padding: unset !important;
            padding-top: 0px !important;
        }
    }
</style>
@endsection


@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card shadow-box card-reserva mb-4">
            <div class="card-body" style="padding: 48px; text-align:center" id="div-pass">
                <form method="POST" action="{{ route('password.check',request()->slug_instalacion) }}">
                    @csrf
                    <p>Introduce la contraseña para mostrar el listado</p>
                    <div class="form-group" style="display:flex; justify-content:center">
                        <input type="password" class="form-control" style="width:20%" name="pw_dev" id="pw_dev" placeholder="Contraseña">
                    </div>
                    <button type="submit" class="btn btn-primary mb-2" id="control-pass">Aceptar</button>
                </form>
            </div>
            
            <div class="row" id="hidden-div" style="display: none">
                <div class="col-lg-12 m-b-10">
                        <div class="p-t-15 p-b-15 p-l-20 p-r-20">
                            <div class="card ">
                                <div class="card-header">
                                    <div class="card-title">Listado de reservas @if(\Route::current()->getName() == "reservas.list_piscina") piscina @endif</div>
                                </div>
                                <div class="card-body">
                                    {{-- <a href="/{{ request()->slug_instalacion }}/admin/reservas/add" class="btn btn-outline-primary mr-2">Añadir desactivación periódica</a>
                                    <a href="/{{ request()->slug_instalacion }}/admin/reservas/add" class="text-white btn btn-primary">Añadir reserva periódica</a> --}}
                                    <div style="display: flex;justify-content: flex-end;gap:10px;display: none">
                                        <div id="table-reservas_buscar" class="dataTables_filter text-right">
                                            <label for="buscar">
                                                <input id="buscar" type="search" class="form-control input-sm" placeholder="Buscar por cliente..." aria-controls="table-users">
                                            </label>
                                        </div>
                                    </div>
                                    <table class="table table-hover" id="table-reservas" style="width: 100% !important;">
                                        <thead>
                                            <tr>
                                                <th data-priority="1">ID</th>
                                                <th>Pedido</th>
                                                <th data-priority="2">Cliente</th>
                                                <th>Fecha de alquiler</th>
                                                <th>Horas</th>
                                                <th>Día de la semana</th>
                                                <th>
                                                    @if(\Route::current()->getName() == "reservas.list_piscina")
                                                    Espacio
                                                    @else
                                                    Tipo
                                                    @endif
                                                </th>
                                                <th>Estado pago</th>
                                                @if(\Route::current()->getName() == "reservas.list_piscina")
                                                <th>Asistencia</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($reservas as $item)
                                                <tr>
                                                    <td data-sort="{{ $item->id }}">#{{ $item->id}}</td>
                                                    <td>{{ $item->pedido->id ?? '---' }}</td>
                                                    <td>{{ $item->user->name }}</td>
                                                    <td>{{ date('d/m/Y', $item->timestamp) }}</td>
                                                    <td>{{ \Carbon\Carbon::createFromTimestamp($item->timestamp)->format('H:i') }} - {{ \Carbon\Carbon::createFromTimestamp($item->timestamp)->addMinutes($item->minutos_totales)->format('H:i') }}</td>
                                                    <td style="text-transform:capitalize">{{ \Carbon\Carbon::parse($item->timestamp)->formatLocalized('%A') }}</td>
                                                    <td>
                                                        @if(\Route::current()->getName() != "reservas.list_piscina")
                                                            {{ $item->pista->nombre }}
                                                        @endif
                                                        @if($item->tipo)
                                                            @if(\Route::current()->getName() != "reservas.list_piscina") - @endif{{ $item->tipo }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($item->estado  == 'active')
                                                            <span class="text-success">
                                                                @if($item->tipo && substr($item->tipo, 0, 4) == 'Bono')
                                                                    BONO
                                                                @else
                                                                    @if($item->creado_por == 'admin' && $item->pedido->tipo_pago == "efectivo")
                                                                        EFECTIVO
                                                                    @elseif($item->creado_por == 'admin' && $item->pedido->tipo_pago == "tarjeta")
                                                                        TARJETA
                                                                    @else
                                                                        TARJETA
                                                                    @endif
                                                                @endif
                                                            </span>
                                                        @endif
                                                        @if($item->estado == 'pendiente')
                                                            <span class="text-warning">PAGO PENDIENTE</span>
                                                        @endif
                                                        @if($item->estado == 'canceled')
                                                            <span class="text-danger">CANCELADO</span>
                                                        @endif
                                                    </td>
                                                    @if(\Route::current()->getName() == "reservas.list_piscina")
                                                    <td class="text-uppercase">
                                                        @if($item->estado != 'canceled')
                                                        <div class="dropdown dropdown-default w-100">
                                                            <button style="text-transform: uppercase;width:100%;display:inline-block" aria-label="" class="btn dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            {{ $item->estado_asistencia ?? 'POR LLEGAR' }}
                                                            </button>
                                                            <div class="dropdown-menu">
                                                                @if($item->estado_asistencia)<a class="dropdown-item" href="{{ route('reserva.actualizar_asistencia', ['slug_instalacion' => request()->slug_instalacion, 'id' => $item->id, 'estado' => 'Por llegar']) }}">Por llegar</a>@endif
                                                                @if($item->estado_asistencia != 'Llegada')<a class="dropdown-item" href="{{ route('reserva.actualizar_asistencia', ['slug_instalacion' => request()->slug_instalacion, 'id' => $item->id, 'estado' => 'Llegada']) }}">Llegada</a>@endif
                                                                @if($item->estado_asistencia != 'Desierta')<a class="dropdown-item" href="{{ route('reserva.actualizar_asistencia', ['slug_instalacion' => request()->slug_instalacion, 'id' => $item->id, 'estado' => 'Desierta']) }}">Desierta </a>@endif
                                                            </div>
                                                          </div>
                                                        @else
                                                        ---
                                                        @endif
                                                    </td>
                                                    @endif
                                                    {{-- <td>
                                                        @if ($item->estado  == 'active' && strtotime(date('Y-m-d H:i', $item->timestamp) . ' +' . $item->minutos_totales . ' minutes') > strtotime(date('Y-m-d H:i')))
                                                            <a class="cancel btn btn-primary text-white btn-accion-reserva" data-intervalo="{{ \Carbon\Carbon::createFromTimestamp($item->timestamp)->format('H:i') }} - {{ \Carbon\Carbon::createFromTimestamp($item->timestamp)->addMinutes($item->minutos_totales)->format('H:i') }}" data-reserva="{{ $item->id }}" data-user="{{ $item->user->name }}" title="Cancelar reserva">
                                                                Acción
                                                            </a>
                                                        @endif
                                                    </td> --}}
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

        </div>
    </div>
</div>

@if ($showHiddenDiv)
    <script>
        document.getElementById('hidden-div').style.display = 'block';
        document.getElementById('div-pass').style.display = 'none';
    </script>
@endif

@endsection

@section('script')
<script>
    $(document).ready(function () {
        $('#table-reservas').DataTable({
                responsive: true,
                "info": false,
                "paging": true,

                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
                },
                "order": [0, "desc"]
            });
    });
</script>
@endsection
