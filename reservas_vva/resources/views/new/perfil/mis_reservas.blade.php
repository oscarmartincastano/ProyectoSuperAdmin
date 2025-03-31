@extends('new.layout.base')

@section('style')
<style>
    .titulo-card {
        margin-bottom: 16px;
        border-bottom: 1px solid rgba(47,51,51,.1);
        padding-bottom: 24px;
        font-weight: 600;
        line-height: 1.4;
        font-size: 32px;
    }
    .contenido-principal {
        padding: 80px;
    }
    th {
        border-top: 0 !important;
    }
    @media(max-width: 992px) {
        .contenido-principal {
            padding: unset !important;
            padding-top: 80px !important;
        }
        .hide-responsive {
            display: none;
        }
        .show-responsives {
            display: block !important;
            font-size: 14px;
        }
    }
    @media(max-width: 582px) {
        .contenido-principal {
            padding-top: 0 !important;
        }
    }
    .show-responsives {
        display: none;
    }
</style>
@endsection

@section('post-header')
    <div class="post-header">
        <div class="menu-header">
            <a href="/{{ request()->slug_instalacion }}/perfil" class="{{ request()->is(request()->slug_instalacion . '/perfil') ? 'active' : '' }}">Mi perfil</a>
            @if (request()->slug_instalacion != "villafranca-navidad" and request()->slug_instalacion != "villafranca-actividades" and request()->slug_instalacion != "ciprea24" and request()->slug_instalacion != "eventos-bodega" and request()->slug_instalacion != "la-guijarrosa")
            <a href="/{{ request()->slug_instalacion }}/mis-reservas" class="{{ request()->is(request()->slug_instalacion . '/mis-reservas') ? 'active' : '' }}">Mis reservas</a>
            @endif
            <a href="/{{ request()->slug_instalacion }}/new/mis-eventos" class="{{ request()->is(request()->slug_instalacion . '/new/mi-perfil') ? 'active' : '' }}">Mis eventos</a>
            @if (request()->slug_instalacion != "villafranca-navidad")
            <a href="/{{ request()->slug_instalacion }}/new/mis-servicios" class="{{ request()->is(request()->slug_instalacion . '/new/mi-perfil') ? 'active' : '' }}">Mis servicios</a>
            <a href="/{{ request()->slug_instalacion }}/new/mis-recibos" class="{{ request()->is(request()->slug_instalacion . '/new/mis-recibos') ? 'active' : '' }}">Mis recibos</a>
            @endif
            @if (request()->slug_instalacion == "santaella")
            <a href="/{{ request()->slug_instalacion }}/new/mis-bonos" class="{{ request()->is(request()->slug_instalacion . '/new/mis-bonos') ? 'active' : '' }}">Mis bonos</a>
            @endif
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-box card-reserva mb-4">
                <div class="card-body" style="padding: 35px; overflow-y: hidden">
                    <div class="titulo-card">Mis reservas</div>
                    <div class="contenido-card">
                        <table class="table table-reservas w-100" style="overflow-x: auto !important;-webkit-overflow-scrolling: touch !important;">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Espacio</th>
                                    <th>Fecha de alquiler</th>
                                    {{-- <th>Día de la semana</th> --}}
                                    <th class="hide-responsive">Horas</th>
                                    {{-- <th>Hora final</th> --}}
                                    <th>Estado</th>
                                    {{-- <th>#</th> --}}
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($reservas as $item)
                                <tr>
                                    <td>#{{ $item->id }}</td>
                                    <td>{{ $item->pista->nombre }} @if($item->tipo) - {{ $item->tipo }} @endif</td>
                                    <td>{{ date('d/m/Y', $item->timestamp) }}<div class="show-responsives">{{ \Carbon\Carbon::createFromTimestamp($item->timestamp)->format('H:i')  }} - {{ \Carbon\Carbon::createFromTimestamp($item->timestamp)->addMinutes($item->minutos_totales)->format('H:i') == '23:59' ? '02:30' : \Carbon\Carbon::createFromTimestamp($item->timestamp)->addMinutes($item->minutos_totales)->format('H:i') }}</div></td>
                                    {{-- <td style="text-transform:capitalize">{{ \Carbon\Carbon::parse($item->fecha)->translatedFormat('l') }}</td> --}}
                                    <td class="hide-responsive">{{ \Carbon\Carbon::createFromTimestamp($item->timestamp)->format('H:i')  }} - {{ \Carbon\Carbon::createFromTimestamp($item->timestamp)->addMinutes($item->minutos_totales)->format('H:i') == '23:59' ? '02:30' : \Carbon\Carbon::createFromTimestamp($item->timestamp)->addMinutes($item->minutos_totales)->format('H:i') }}</td>
                                    {{-- <td>{{ \Carbon\Carbon::createFromTimestamp($item->timestamp)->addMinutes($item->minutos_totales)->format('H:i') }}</td> --}}
                                    <td>
                                        @if ($item->estado  == 'active')
                                            @if (strtotime(date('Y-m-d H:i', $item->timestamp) . ' +' . $item->minutos_totales . ' minutes') > strtotime(date('Y-m-d H:i')))

                                                <span class="text-success">@if($item->tipo && substr($item->tipo, 0, 4) == 'Bono') Bono @else Pagado @endif</span>
                                            @else
                                                Pasado
                                            @endif
                                        @endif
                                        @if($item->estado == 'pendiente')
                                            <span class="text-warning">Pendiente de pago</span>

                                        @endif
                                        @if($item->estado == 'desierta')
                                            <span class="text-warning">Desierta</span>
                                        @endif
                                        @if($item->estado == 'canceled')
                                            <span class="text-danger">Cancelada</span>
                                        @endif
                                        @if($item->estado == 'pasado')
                                            <span class="text-success">Validada</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{-- @if ($item->id == 92)
                                            {{dd(\Carbon\Carbon::parse($item->created_at)->addMinutes('5')->greaterThan(\Carbon\Carbon::now()))}}
                                        @endif --}}
                                        {{-- @if($item->pedido != null && $item->estado == 'pendiente' && \Carbon\Carbon::parse($item->created_at)->addMinutes('5')->greaterThan(\Carbon\Carbon::now())) --}}
                                        @if($item->pedido != null && $item->estado == 'pendiente' && (\Carbon\Carbon::parse($item->created_at)->addMinutes('10')->greaterThan(\Carbon\Carbon::now()) || $item->reserva_periodica))

                                            <a href="{!! route('redsys.pagar', [request()->slug_instalacion,$item->pedido->id.rand(0,9)]) !!}" class="btn btn-primary btn-sm btn-small">Pagar ahora</a>


                                            @else
                                            @if(request()->slug_instalacion != "vvadecordoba")


                                                    @if ($item->estado  == 'active' && strtotime(date('Y-m-d H:i')) < strtotime(date('Y-m-d H:i', $item->timestamp)."- 1 days") )
                                            <form action="/{{ request()->slug_instalacion }}/mis-reservas/{{ $item->id }}/cancel" method="post">
                                                @csrf
                                            </form>

                                            <a class="cancel btn btn-danger" title="Cancelar reserva" onclick="if(confirm('¿Estás seguro que quieres cancelar esta reserva?')){$(this).prev().submit()}">
                                                <i class="fas fa-times"></i>
                                            </a>
                                        @endif

                                    @endif
                                        @endif
                                    </td>


                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No se encuentran registros</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
    $(document).ready(function () {
        $('.table-reservas').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
        });
    });
</script>

@endsection
