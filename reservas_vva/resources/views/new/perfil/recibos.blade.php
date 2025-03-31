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
                    <div class="titulo-card">Recibos
                        @if ($recibos_no_pagados > 0)
                            <span class="text-danger">({{ $recibos_no_pagados }} recibos no pagados)</span>
                        @endif
                    </div>
                    <div class="contenido-card">
                        <table class="table w-100" style="overflow-x: auto !important;-webkit-overflow-scrolling: touch !important;">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Servicio contratado</th>
                                    <th>Precio</th>
                                    <th>Mes</th>
                                    <th>Estado</th>
                                    <th>Año</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $unpaid_services = [];
                                    $tiene_colectivas = false;
                                    $mes_actual = \Carbon\Carbon::now()->formatLocalized('%B');
                                    $anio_actual = \Carbon\Carbon::now()->format('Y');
                                    foreach ($recibos as $item) {
                                        if ($item->servicio->nombre == 'Clases colectivas' && $item->estado == 'pagado' && \Carbon\Carbon::parse($item->created_at)->formatLocalized('%B') == $mes_actual && \Carbon\Carbon::parse($item->created_at)->format('Y') == $anio_actual){
                                            $tiene_colectivas = true;
                                            break;
                                        }
                                    }
                                @endphp
                                @forelse ($recibos as $item)
                                    @php
                                        $current_month = \Carbon\Carbon::parse($item->created_at)->month;
                                        $is_unpaid_service = in_array($item->servicio->id, $unpaid_services);
                                    @endphp
                                    <tr>
                                        <td>#{!! $item->id !!}</td>
                                        <td>{!! $item->servicio->nombre !!}</td>
                                        <td>{!! $item->servicio->precio !!}€</td>
                                        <td>{!! ucfirst(\Carbon\Carbon::parse($item->created_at)->formatLocalized('%B')) !!}</td>
                                        @if ($tiene_colectivas && $item->servicio->nombre == "Gimnasio")
                                            <td>Tiene clases colectivas</td>
                                        @else
                                            <td>{!! ucfirst($item->estado) !!}</td>
                                        @endif
                                        <td>{!! ucfirst(\Carbon\Carbon::parse($item->created_at)->format('Y')) !!}</td>
                                        @if( $item->pedido_id == null && $item->estado == 'pendiente')
                                            @php
                                                $button_disabled = $is_unpaid_service ? 'disabled' : '';
                                                if (!$is_unpaid_service) {
                                                    $unpaid_services[] = $item->servicio->id;
                                                }
                                            @endphp
                                            <td><a href="/{{ $instalacion->slug }}/servicios/{{$item->servicio->id}}/{{$item->id}}/renovar" class="btn btn-success {{ $button_disabled }}">Renovar</a></td>
                                        @else
                                            <td></td>
                                        @endif
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
