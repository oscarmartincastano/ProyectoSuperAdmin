@extends('new.layout.base')

@section('link-script')
    {{-- <link rel="stylesheet" href="/css/carousel.css">
<script src="/js/carousel.js"></script> --}}
@endsection


@section('style')
    <style>
        .card-body .nav-tabs {
            display: flex;
            align-items: center;
            font-size: 16px;
            border-bottom: 0;
        }

        .card-body .nav-tabs a {
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

        @font-face {
            font-family: 'BODAS';
            src: url('{{ asset('fonts/CAMPOS.TTF') }}') format('truetype');
            font-display: swap;
        }

        .card-body .nav-tabs a.active {
            border-bottom-color: #335fff;
            opacity: 1;
        }

        .card-body .nav-tabs li:nth-child(2)>a {
            margin-left: 2em;
        }

        .card-body .tab-content {
            margin-top: 32px;
        }

        #boton-piscina a {
            color: white;
            text-decoration: none;
        }

        #boton-piscina {
            text-decoration: none;
            width: 100%;
            color: white;
            background-image: url('/img/deportes/banner-piscina.jpg');
            background-position: center center;
            text-align: center;
            padding: 3%;
            margin-left: auto;
            margin-right: auto;
            margin-bottom: 3%;
            border-radius: 15px;
            border: 2px solid white;
        }

        #boton-piscina:hover {
            filter: brightness(1.25);
            cursor: pointer;

        }


        .show-responsive {
            font-size: 0.7em;
        }

        .contratar_responsive {
            display: none;
        }

        @media screen and (max-width: 655px) {
            .contratar {
                display: none;
            }

            .contratar_responsive {
                display: block;
            }
        }

        .item-servicio {
            border-left-color: #0d6efd !important;
        }
    </style>
    @if (request()->slug_instalacion == 'eventos-bodega')
        <style>
            .title {
                font-size: 50px !important;
            }

            .contenido-titulo .direccion {
                font-size: 20px;
            }

            .fecha {
                font-size: 20px;
                text-transform: capitalize;
            }

            .dia {
                color: #0d6efd;
            }

            .contenido-evento em {
                color: #721e27;
            }
        </style>
    @endif
@endsection

@section('titulo')
    <section class="titulo-instalacion"
        style="@if (request()->slug_instalacion != 'ciprea24') background:linear-gradient(0deg, rgba(36, 36, 36, 0.5), rgba(36, 36, 36, 0.5)), url({{ asset('img/portadas-inst/' . request()->slug_instalacion . '.jpg') }}); @else position: relative !important;
    background-color: #143e84 !important;
    background-repeat: no-repeat !important;
    background-position: 100% !important;
    background-size: contain !important; background: url({{ asset('img/portadas-inst/' . request()->slug_instalacion . '.jpg') }}); @endif  @if (request()->slug_instalacion == 'villafranca-actividades') background-position: top !important; @endif">
        <div class="contenido-titulo container">
            <h1 class="title">{{ $instalacion->nombre }}</h1>
            @if (request()->slug_instalacion != 'eventos-bodega')

                <div class="direccion">{{ $instalacion->direccion }}</div>

            @endif
            <input type="hidden" class="dias_fest" data-diasf="{{ $dias_festivos }}" value="{{ $dias_festivos }}">
        </div>
    </section>
@endsection

@section('content')
    @if (request()->slug_instalacion == 'la-guijarrosa')
        @php
        dd($instalacion);
            $servicio_usuario = [];
            if (auth()->user()) {
                $servicio_usuario = $servicios_contratados = \App\Models\Servicio_Usuario::where(
                    'id_usuario',
                    auth()->user()->id,
                )->get();
            }
        @endphp
        @foreach ($servicio_usuario as $item3)
            @php
                $fecha_expiracion = \Carbon\Carbon::parse($item3->fecha_expiracion);
                $fecha_actual = \Carbon\Carbon::now();
                $dias_restantes = $fecha_actual->diffInDays($fecha_expiracion);
                if ($fecha_expiracion < $fecha_actual) {
                    $dias_restantes = -1;
                }
            @endphp
            @if ($dias_restantes <= 5 && $item3->activo == 'si')
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    @if ($dias_restantes === 0)
                        <strong>¡Atención!</strong> Hoy es el último día para que caduque su servicio de
                        {{ $item3->servicio->nombre }}.
                    @elseif ($dias_restantes === -1)
                        <strong>¡Atención!</strong> Su servicio de {{ $item3->servicio->nombre }} ha caducado. <a
                            href="/{{ request()->slug_instalacion }}/servicios/{{ $item3->id_servicio }}/contratar-de-nuevo">Renuevelo</a>
                        para seguir disfrutando de sus ventajas.
                    @else
                        <strong>¡Atención!</strong> Le quedan {{ $dias_restantes }} días para que caduque su servicio de
                        {{ $item3->servicio->nombre }}.
                    @endif
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
        @endforeach

    @endif

    <div class="row">
        <div style="margin-bottom:10px">
            @if (request()->slug_instalacion == 'la-guijarrosa')
                <div id="apertura-puerta" class="tab-pane" style="display: flex; align-items: center; flex-wrap: wrap">
                    @if (!isset($paso))
                        <div class="col-md-3 mt-2">
                            <p></p>
                        </div>
                    @elseif($paso->activo == 'on')
                        <div class="mt-2"
                            style="justify-content: space-around; display: flex; width: fit-content; gap: 10px">
                            @csrf
                            @if (!isset($ultimoRegistro))
                                <button type="button" class="btn btn-success" id="apertura">Abrir torno</button>
                            @elseif(
                                (isset($ultimoRegistro) && $ultimoRegistro->estado == null) ||
                                    $ultimoRegistro->estado == 'salida_torno' ||
                                    $ultimoRegistro->estado == 'entrada_gimnasio_usuario')
                                <button type="button" class="btn btn-success" id="apertura">Abrir torno</button>
                            @else
                                <button type="button" class="btn btn-success" id="salida">Salida torno</button>
                            @endif
                            <button type="button" class="btn btn-success" id="apertura_gym">Abrir puerta gimnasio</button>

                            @php
                                $servicio_usuario = [];
                                if (auth()->user()) {
                                    $servicio_usuario = $servicios_contratados = \App\Models\Servicio_Usuario::where(
                                        'id_usuario',
                                        auth()->user()->id,
                                    )->get();
                                }
                            @endphp

                            @foreach ($servicio_usuario as $item)
                                @if ($item->activo == 'si' && count($item->recibos_sin_pago) == 0)
                                    <a href="#" class="btn btn-danger btn-dar-baja" data-toggle="modal"
                                        data-target="#confirmacion-modal" data-id="{{ $item->id }}">Dar de baja
                                        {{ $item->servicio->nombre }}</a>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <div class="col-md-3 mt-2">
                            <p>No tiene permiso para acceder a las instalaciones.</p>
                        </div>
                    @endif

                </div>

                <input class="visually-hidden" type="text" id="longitud">
                <input class="visually-hidden" type="text" id="latitud">
            @endif
        </div>

        <div class="col-md-9">
            @if ($pistas != null)

                @if (request()->slug_instalacion == 'mercadillos-villafranca-de-cordoba')
                    <div class="card shadow-box mb-4">
                        <div class="card-header bg-white font-bold"
                            style="font-weight: bold;padding: 12px;font-size: 18px;">Plano del mercadillo</div>
                        <div class="card-body p-0" style="padding: 12px">
                            <img src="{{ asset('./img/puestosMercadillo-villafranca.jpg') }}"
                                alt="Puestos Mercadillos Villafranca" style="width: 100%; ">
                        </div>
                    </div>

                @endif

                <div class="card shadow-box card-reserva mb-4 @if (request()->slug_instalacion == 'los-agujetas-de-villafranca') d-none @endif"
                    id="contenedor-reservas" style="margin: auto" data-tipo-reserva="{{ $instalacion->tipo_reservas_id }}">
                    @if ($instalacion->tipo_reservas_id == 1)
                        @if ($tipoCalendario == 1)
                            @section('linkCss', '/css/reservas.css')
                            <div class="filtros p-0 d-flex">
                                <div>
                                    <select class="w-100 form-control select2 select-pista">
                                        @foreach ($instalacion->deportes_clases as $index => $item)
                                            <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="position-relative">
                                    <div class="div-hoy">Hoy</div>

                                    <div class="div-alt-select-fecha">
                                        <input type="text" class="form-control" id="alt-select-fecha"
                                            readonly="readonly">
                                    </div>
                                    <input type="text" class="form-control select-fecha" value="{{ date('Y-m-d') }}"
                                        min="{{ date('Y-m-d') }}" readonly="readonly">
                                </div>

                            </div>
                            <div class="card-body p-0">
                                <div class="tabla-horario">
                                    <div class="loader-horario text-center" style="display: none">
                                        <div>
                                            <div class="spinner-border text-primary mb-3" role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                            <div>Buscando disponibilidad</div>
                                        </div>
                                    </div>
                                    <div class="pistas-horario">
                                        <div class="celda" style="height: 40px; line-height: 40px;"></div>
                                        @foreach ($pistas as $index => $pista)
                                            <div class="celda" style="height: 40px; line-height: 40px;">
                                                {{ $pista->nombre }}
                                            </div>
                                        @endforeach
                                        <div id="ver-zonas-anteriores">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="icon icon-tabler icon-tabler-layout-sidebar-right-expand"
                                                width="22" height="22" viewBox="0 0 24 24" stroke-width="1.5"
                                                stroke="#FFFFFF" fill="none" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path
                                                    d="M4 4m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" />
                                                <path d="M15 4v16" />
                                                <path d="M10 10l-2 2l2 2" />
                                            </svg>
                                        </div>
                                        <div id="ver-mas-zonas">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="icon icon-tabler icon-tabler-layout-sidebar-left-expand"
                                                width="22" height="22" viewBox="0 0 24 24" stroke-width="1.5"
                                                stroke="#FFFFFF" fill="none" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path
                                                    d="M4 4m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" />
                                                <path d="M9 4v16" />
                                                <path d="M14 10l2 2l-2 2" />
                                            </svg>
                                        </div>

                                    </div>
                                    <div class="tramos">
                                        <div class="horas-tramos">
                                            @for ($i = 7; $i <= 23; $i++)
                                                <div class="celda" style="width: 40px; height: 40px; line-height: 40px;">
                                                    {{ $i }}
                                                </div>
                                            @endfor
                                        </div>
                                        <div class="horas-tramos-pistas">
                                            @foreach ($pistas as $index => $pista)
                                                <div class="slots-pista">
                                                    <div class="slots-horas">
                                                        @for ($i = 7; $i <= 23; $i++)
                                                            <div class="celda"
                                                                style="width: 40px; height: 40px; line-height: 40px;">
                                                            </div>
                                                        @endfor
                                                        @foreach ($pista->horario_con_reservas_por_dia(date('Y-m-d')) as $item)
                                                            @foreach ($item as $intervalo)
                                                                <div data-left="{{ $intervalo['hora'] }}"
                                                                    data-width="{{ $intervalo['width'] }}"
                                                                    class="slot celda slot-reserva"
                                                                    style="left:{{ $intervalo['hora'] }}px;width: {{ $intervalo['width'] }}px;height:40px;position:absolute;z-index:20">
                                                                    <div
                                                                        @if (!$intervalo['valida']) @switch($intervalo['estado'])

                                                                        @case('reservado')
                                                                        class="btn-reservado"
                                                                        @break
                                                                        @case('desactivado')
                                                                        class="btn-no-disponible"
                                                                        @break

                                                                        @endswitch @endif>
                                                                        <a
                                                                            @if (!$intervalo['valida']) href="#"  class="d-block h-100"  @else data-toggle="tooltip" data-html="true" data-placement="top"
                                                                        title="{{ $pista->nombre }}
                                                                        {{ $intervalo['string'] }}" class="d-block h-100" href="/{{ request()->slug_instalacion }}/{{ $pista->tipo }}/{{ $pista->id }}/{{ $intervalo['timestamp'] }}" @endif><span
                                                                                class="show-responsive">
                                                                                @if (!$intervalo['valida'])
                                                                                    @switch($intervalo['estado'])
                                                                                        @case('reservado')
                                                                                            RESERVADA
                                                                                        @break

                                                                                        @case('desactivado')
                                                                                            NO DISPONIBLE
                                                                                        @break
                                                                                    @endswitch
                                                                                @else
                                                                                    {{ $intervalo['string'] }}
                                                                                @endif




                                                                            </span></a>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        <div class="block-before"
                                            style="top: {{ $pistas->count() * (40 / $pistas->count()) }}px;width: {{ $block_pista }}px">
                                        </div>
                                    </div>
                                </div>

                                <div class="leyenda text-right">
                                    <div class="reservada">Reservada</div>
                                    <div class="disponible">Libre</div>
                                    <div class="no-disponible">No disponible</div>
                                </div>
                            </div>
                        @elseif($tipoCalendario == 2)
                            <style>
                                /* Styles for elements inside col-md-9 */

                                /* Card container */
                                .col-md-9 .card.shadow-box {
                                    box-shadow: -1px 5px 17px 0 rgb(0 0 0 / 10%);
                                }

                                .col-md-9 .card {
                                    background-color: white !important;
                                }

                                .col-md-9 .card-reserva {
                                    margin: auto;
                                }

                                /* Pistas (tracks/courts) section */
                                .col-md-9 .div-reservas {
                                    box-shadow: 0px 0px 20px 0px rgb(204 204 204);
                                }

                                .col-md-9 .div-reservas .pistas {
                                    background: rgba(55, 61, 67, 1);
                                    display: flex;
                                    justify-content: center;
                                    border-radius: 7px 7px 0 0;
                                    flex-direction: column;
                                    align-items: center;
                                }

                                .col-md-9 .div-reservas .pistas a {
                                    color: #828282;
                                    font-size: 20px;
                                    text-decoration: none;
                                }

                                .col-md-9 .div-reservas .pistas .active a {
                                    color: white;
                                }

                                .col-md-9 .div-reservas .pistas a:hover {
                                    color: white;
                                }

                                .col-md-9 .div-reservas .pistas div {
                                    margin: 15px 0;
                                    padding: 0 10px;
                                    display: flex;
                                    justify-content: center;
                                    align-items: center;
                                    width: 220px;
                                }

                                .col-md-9 .div-reservas .pistas div:not(:first-child) {
                                    border-left: 1px solid #ccc;
                                }

                                .col-md-9 .seleccionar-pista-label+div {
                                    border-left: none !important;
                                }

                                /* Calendar and navigation */
                                .col-md-9 .div-reservas .calendario-horarios {
                                    padding: 20px;
                                }

                                .col-md-9 .navigator {
                                    display: flex;
                                    justify-content: space-between;
                                }

                                .col-md-9 .navigator .calendario {
                                    display: none;
                                }

                                .col-md-9 .navigator .semanas a.button {
                                    background-color: #fff;
                                    border-color: #dbdbdb;
                                    border-width: 1px;
                                    color: #363636;
                                    cursor: pointer;
                                    justify-content: center;
                                    padding-bottom: calc(.5em - 1px);
                                    padding-left: 1em;
                                    padding-right: 1em;
                                    padding-top: calc(.5em - 1px);
                                    text-align: center;
                                    white-space: nowrap;
                                }

                                .col-md-9 .navigator .semanas a.button.active {
                                    border: 1px solid #3273dc;
                                    color: #3273dc;
                                }

                                .navigator a {
                                    text-decoration: none;
                                }

                                /* Reservation grid */
                                .col-md-9 .thead {
                                    display: flex;
                                    justify-content: space-evenly;
                                    padding: 20px;
                                }

                                .col-md-9 .thead a {
                                    text-decoration: none;
                                }

                                .col-md-9 .th {
                                    font-weight: bold;
                                    text-align: center;
                                }

                                .col-md-9 .thead>div>div {
                                    height: 6rem;
                                }

                                .col-md-9 .thead>div {
                                    width: 100%;
                                }

                                .col-md-9 .th[style*="text-transform: capitalize"] {
                                    text-transform: capitalize;
                                }

                                /* Time slots */
                                .col-md-9 a.btn-reservar {
                                    background: #52b5f7;
                                    color: white;
                                    display: flex;
                                    justify-content: center;
                                    align-items: center;
                                    height: 100%;
                                    font-size: 14px;
                                    border: 2px solid;
                                }

                                .col-md-9 a.btn-reservar:hover {
                                    background: #0077c7 !important;
                                }

                                .col-md-9 .btn-no-disponible {
                                    font-size: 0.7em;
                                    background: grey !important;
                                    cursor: auto !important;
                                    border: 1px solid;
                                    border-width: 1px;
                                    border-style: solid;
                                    white-space: nowrap;
                                    border-color: #ddd !important;
                                    color: #aaa !important;
                                    display: flex;
                                    justify-content: center;
                                    align-items: center;
                                    height: 100%;
                                    border: 2px solid;
                                }

                                .col-md-9 .btn-no-disponible:hover {
                                    background: #819daf;
                                    color: white;
                                    cursor: default;
                                }

                                .col-md-9 .btn-reservado {
                                    font-size: 0.7em;
                                    background: #8f0d1a !important;
                                    cursor: auto !important;
                                }

                                .col-md-9 .btn-no-disponible>a {
                                    text-decoration: none !important;
                                }

                                /* Datepicker and form elements */
                                .col-md-9 .diapicker {
                                    display: flex;
                                }

                                .col-md-9 .diapicker .btn-secondary {
                                    background-color: #6c757d;
                                    border-color: #6c757d;
                                    color: white;
                                }

                                .col-md-9 .diapicker .form-control {
                                    border: 0 !important;
                                    border-right: 1px solid #f2f2f2 !important;
                                    font-weight: bold;
                                }

                                .col-md-9 input#dia {
                                    text-align: center;
                                }

                                .col-md-9 .datepicker.date-input {
                                    color: white;
                                    background: #6c757d;
                                }

                                /* Loader */
                                .col-md-9 .loader-horario {
                                    flex-grow: 1;
                                    display: flex;
                                    flex-direction: column;
                                    justify-content: center;
                                    align-items: center;
                                    height: 100%;
                                    position: absolute;
                                    width: 100%;
                                    background: white;
                                    z-index: 800000;
                                    font-size: 18px;
                                    font-weight: 500;
                                    line-height: 1.2;
                                }

                                /* Responsive styles */
                                @media (max-width: 992px) {
                                    .col-md-9 .card-reserva {
                                        width: auto !important;
                                    }

                                    .col-md-9 .tabla-horario {
                                        display: block !important;
                                    }

                                    .col-md-9 .show-responsive {
                                        display: block !important;
                                    }

                                    .col-md-9 .slot-reserva a {
                                        display: flex !important;
                                        justify-content: center;
                                        align-items: center;
                                    }
                                }

                                @media (max-width: 600px) {
                                    .col-md-9 .thead {
                                        display: block;
                                    }

                                    .col-md-9 .thead>div.th:not(:first-child) {
                                        display: none;
                                    }

                                    .col-md-9 .thead>div.th>div:first-child {
                                        height: 5rem;
                                    }

                                    .col-md-9 .navigator .semanas,
                                    .col-md-9 .navigator .mes {
                                        display: none;
                                    }

                                    .col-md-9 .navigator .calendario {
                                        display: block;
                                        width: 100%;
                                    }

                                    .col-md-9 .navigator .calendario input {
                                        border-color: #6c757d;
                                    }

                                    .col-md-9 input#dia {
                                        padding-right: 16%;
                                    }

                                    .col-md-9 .filtros>div {
                                        text-align: center;
                                        width: 100%;
                                    }
                                }

                                /* Hidden elements on mobile */
                                .col-md-9 .show-responsive {
                                    display: none;
                                    color: white;
                                }

                                /* Day selector */
                                .col-md-9 .div-hoy {
                                    cursor: pointer;
                                    background: white;
                                    font-weight: bold;
                                    position: absolute;
                                    z-index: 200;
                                    width: 112px;
                                    height: 100%;
                                    display: flex;
                                    align-items: center;
                                    justify-content: flex-start;
                                    padding-left: 14px;
                                }

                                .col-md-9 #alt-select-fecha {
                                    cursor: pointer;
                                    background: white;
                                    font-weight: bold;
                                    position: absolute;
                                    z-index: 199;
                                    width: 100%;
                                    height: 100%;
                                    display: flex;
                                    align-items: center;
                                    justify-content: flex-start;
                                    padding-left: 14px;
                                }

                                /* Select2 styling */
                                .col-md-9 .select2-container--default .select2-selection--single {
                                    border: 0 !important;
                                    font-weight: bold;
                                }

                                .col-md-9 .select2-container--default .select2-selection--single .select2-selection__arrow b {
                                    border-color: #0d6efd transparent transparent transparent !important;
                                }

                                .col-md-9 .select2-container--default {
                                    min-width: 132px;
                                }

                                /* Date selector arrow */
                                .col-md-9 .select-fecha {
                                    cursor: pointer;
                                    position: relative;
                                }

                                .col-md-9 .select-fecha::-webkit-calendar-picker-indicator {
                                    display: none;
                                    -webkit-appearance: none;
                                }

                                .col-md-9 .select-fecha::before {
                                    content: "";
                                    border-color: #0d6efd transparent transparent transparent;
                                    border-style: solid;
                                    border-width: 5px 4px 0 4px;
                                    height: 0;
                                    left: 88%;
                                    margin-left: -4px;
                                    margin-top: -2px;
                                    position: absolute;
                                    top: 50%;
                                    width: 0;
                                    transition: all 0.4s;
                                    z-index: 201;
                                }

                                .col-md-9 .select-fecha:focus::before {
                                    transform: rotate(180deg);
                                }

                                /* Form controls */
                                .col-md-9 .form-control:disabled,
                                .col-md-9 .form-control[readonly] {
                                    background: white !important;
                                }
                            </style>

                            <div class="container is-max-desktop">
                                <div class="columns">
                                    <div class="column is-full">
                                        <div class="div-reservas">
                                            <div class="pistas"
                                                @if ($pista_selected->id_instalacion == 2) style="padding-top:10px" @endif>
                                                @if (count($pistas) > 1)
                                                    <div class="seleccionar-pista-label"
                                                        style="color: white;top:11px;font-size: 18px;@if ($pista_selected->id_instalacion == 2) display:none; @endif">
                                                        Selecciona espacio: </div>
                                                @endif
                                                <div class="pistasnav" style="width: 100%;">
                                                    @if (count($pistas) > 4)
                                                        @if ($pista_selected->id_instalacion != 2)
                                                            <select name="pista" class="form-select"
                                                                id="pista-select2">
                                                                @foreach ($pistas as $pista)
                                                                    <option value="{{ $pista->id }}"
                                                                        @if ($pista_selected->id == $pista->id) selected @endif>
                                                                        {{ $pista->nombre }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        @else
                                                            <ul class="nav nav-pills justify-content-center">
                                                                @foreach ($pistas as $pista)
                                                                    <li class="nav-item">
                                                                        <a class="nav-link @if ($pista_selected->id == $pista->id) active @endif"
                                                                            href="/{{ request()->slug_instalacion }}/{{ $pista->nombre }}/{{ $pista_selected->tipo }}/{{ isset(request()->subtipo) ? request()->subtipo . '/' : '' }}{{ $pista->id }}/{{ $pista->id_instalacion }}"><span>{{ $pista->nombre }}</span>
                                                                            @if (
                                                                                $pista->reservas_given_two_dates(date('Y-m-d'),
                                                                                        iterator_to_array($period)[count(iterator_to_array($period)) - 1]->format('Y-m-d'))->count())
                                                                                <span
                                                                                    class="span-num-res ml-2">{{ $pista->reservas_given_two_dates(date('Y-m-d'), iterator_to_array($period)[count(iterator_to_array($period)) - 1]->format('Y-m-d'))->count() }}</span>
                                                                            @endif
                                                                        </a>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    @else
                                                        @foreach ($pistas as $index => $pista)
                                                            @if ($pista->id == 67)
                                                                <div class="@if ($pista->id == $pista_selected->id) active @endif "
                                                                    @if ($pistas->count() == 1) style="width:100%" @endif>
                                                                    <a class=" select-pista"
                                                                        data-id_pista="{{ $pista->id }}"
                                                                        href="/{{ request()->slug_instalacion }}/{{ $pista->nombre }}/{{ $pista_selected->tipo }}/{{ isset(request()->subtipo) ? request()->subtipo . '/' : '' }}{{ $pista->id }}/{{ $pista->id_instalacion }}">{{ $pista->nombre }}</a>
                                                                </div>
                                                            @endif
                                                            @if ($pista->id != 67)
                                                                <div class="@if ($pista->id == $pista_selected->id) active @endif "
                                                                    @if ($pistas->count() == 1) style="width:100%" @endif>
                                                                    <a class=" select-pista"
                                                                        data-id_pista="{{ $pista->id }}"
                                                                        href="/{{ request()->slug_instalacion }}/{{ $pista->nombre }}/{{ $pista_selected->tipo }}/{{ isset(request()->subtipo) ? request()->subtipo . '/' : '' }}{{ $pista->id }}/{{ $pista->id_instalacion }}">{{ $pista->nombre }}</a>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="calendario-horarios">
                                                <div class="navigator">
                                                    <div class="semanas">
                                                        <a class="button"
                                                            href="/{{ request()->slug_instalacion }}/{{ $pista_selected->tipo }}/{{ isset(request()->subtipo) ? request()->subtipo . '/' : '' }}{{ $pista_selected->id }}?semana={{ request()->semana == null || request()->semana == 0 ? '-1' : request()->semana - 1 }}">
                                                            < </a>
                                                                <a class="button {{ request()->semana == null || request()->semana == 0 ? 'active' : '' }}"
                                                                    href="/{{ request()->slug_instalacion }}/{{ $pista_selected->tipo }}/{{ isset(request()->subtipo) ? request()->subtipo . '/' : '' }}{{ $pista_selected->id }}">
                                                                    Hoy
                                                                </a>
                                                                <a class="button"
                                                                    href="?semana={{ request()->semana == null || request()->semana == 0 ? '1' : request()->semana + 1 }}">
                                                                    >
                                                                </a>
                                                    </div>

                                                    <div class="calendario">
                                                        <form id="form-dia" method="get"
                                                            action="/{{ request()->slug_instalacion }}/{{ $pista_selected->tipo }}/{{ isset(request()->subtipo) ? request()->subtipo . '/' : '' }}{{ $pista_selected->id }}">
                                                            <div class="input-group diapicker">
                                                                <label for="dia" class="btn btn-secondary">
                                                                    <i class="fas fa-calendar"></i>
                                                                </label>
                                                                <input type="date" id="dia"
                                                                    class="form-control date-input" name="dia"
                                                                    value="{{ request()->dia == null ? date('Y-m-d') : \Carbon\Carbon::createFromFormat('d/m/Y', request()->dia)->format('Y-m-d') }}"
                                                                    onchange="cargarHorarios(this.value)">
                                                            </div>
                                                        </form>
                                                    </div>

                                                    @php
                                                        $dias_periodo = iterator_to_array($period);
                                                    @endphp

                                                    <div style="text-transform: capitalize" class="mes">
                                                        {{ \Carbon\Carbon::parse($dias_periodo[0])->translatedFormat('d M') .
                                                            ' - ' .
                                                            \Carbon\Carbon::parse($dias_periodo[count($dias_periodo) - 1])->translatedFormat('d M') }}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="thead">
                                                @php
                                                    // Tomar solo los primeros 8 elementos de $horarios_final
                                                    $horarios_final = collect($horarios_final)->take(8);
                                                @endphp

                                                @foreach ($horarios_final as $horario)
                                                    <div class="th" style="text-transform: capitalize">
                                                        @foreach ($horario as $mini_horario)
                                                            <div style="height:4rem">
                                                                {{ \Carbon\Carbon::parse($mini_horario[0]['timestamp'])->translatedFormat('l') }}
                                                            </div>
                                                            @foreach ($mini_horario as $intervalo)
                                                                <div @if ($intervalo['height'] < 17) style="height: @if (request()->slug_instalacion != 'bancordoba'){{ max($intervalo['height'] / 2, 1.75) }}rem @else 2.5rem @endif "
@else
    style="height:{{ max($intervalo['height'] / 4, 1) }}rem"            @endif>
                                                                    <a @if (!$intervalo['valida']) @if ($intervalo['siguiente_reserva_lista_espera'])
        href="/{{ request()->slug_instalacion }}/{{ $pista_selected->tipo }}/{{ $pista_selected->id }}/{{ $intervalo['timestamp'] }}"
        class="btn-reservar btn-reservar-suplente" style="background: #ff9800"
    @else
        href="#" class="btn-no-disponible" @endif
                                                                    @else
                                                                        href="/{{ request()->slug_instalacion }}/{{ $pista_selected->tipo }}/{{ $pista_selected->id }}/{{ $intervalo['timestamp'] }}"
                                                                        class="btn-reservar" @endif>
                                                                        @if (!$intervalo['reunion'])
                                                                            {{ $intervalo['string'] }}
                                                                        @else
                                                                            <span
                                                                                style="max-width:150px;">{!! strlen($intervalo['reunion']->valor_nombre_reunion) > 17
                                                                                    ? strrev(implode(strrev('<br>'), explode(strrev(' '), strrev($intervalo['reunion']->valor_nombre_reunion), 2)))
                                                                                    : $intervalo['reunion']->valor_nombre_reunion !!}</span>
                                                                        @endif
                                                                        @if ($pista_selected->instalacion->id == 5 && $intervalo['valida'])
                                                                            <br>
                                                                            (Libres:
                                                                            {{ $pista_selected->reservas_por_tramo - $intervalo['num_res'] }})
                                                                        @endif
                                                                    </a>
                                                                </div>
                                                            @endforeach
                                                        @endforeach
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <script>
                                // Evento para actualizar los nombres de los días cuando cambia el ancho de la pantalla

                                // Llamada inicial para configurar los nombres de los días
                                window.addEventListener("DOMContentLoaded", function() {
                                    function actualizarNombresDias() {
                                        const anchoPantalla = window.innerWidth;
                                        const celdas = document.querySelectorAll('.nombre-dia-abreviado');

                                        const diasMovil = ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"];
                                        const diasEscritorio = ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"];

                                        celdas.forEach(function(celda) {
                                            const numeroDia = celda.dataset.dia;

                                            celda.textContent = anchoPantalla < 550 ? diasMovil[numeroDia] : diasEscritorio[
                                                numeroDia];
                                        });

                                    }
                                    actualizarNombresDias();
                                    window.addEventListener('resize', actualizarNombresDias);
                                });
                                $(document).ready(function() {
                                    $('.btn-no-disponible').click(function(e) {
                                        e.preventDefault();
                                    });

                                    var input_date = $('#dia').pickadate({
                                        editable: true,
                                        selectYears: 100,
                                        selectMonths: true,
                                        format: 'dd/mm/yyyy',
                                        min: false,
                                        max: false
                                    });

                                    var picker = input_date.pickadate('picker');

                                    $("#dia").focus(function() {
                                        document.activeElement.blur();
                                    });

                                    $(".diapicker").on("click", function(e) {
                                        e.preventDefault();
                                        e.stopPropagation();

                                        picker.open();

                                        picker.on('set', function(event) {
                                            if (event.select) {
                                                $('#form-dia').submit();
                                            }
                                        });
                                    });

                                    $('#pista-select2').select2();

                                    $('#pista-select2').change(function(e) {
                                        window.location.href = $('#url_instalacion').html() + $(this).val() +
                                            "?dia={{ request()->dia }}&dia_submit={{ request()->dia_submit }}";
                                    });

                                    $('.btn-reservar-suplente').click(function(e) {
                                        e.preventDefault();
                                        $('#modal-reserva-suplente').modal('show').find('.btn-success').attr('href', $(this).attr(
                                            'href'));
                                    });
                                });

                                function cargarHorarios(fechaSeleccionada) {
    const url = `/{{ request()->slug_instalacion }}/{{$pista_selected->nombre}}/{{ $pista_selected->tipo }}/{{ $pista_selected->id }}/horarios?dia=${fechaSeleccionada}`;

    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al obtener los horarios');
            }
            return response.json();
        })
        .then(data => {
            console.log(data); // Verifica los datos aquí
            const thead = document.querySelector('.thead');
            thead.innerHTML = '';

            const aData = data.slice(0, 8); // Limitar a 8 días

            // Recorrer los horarios y construir el contenido dinámicamente
            aData.forEach(horarioArray => {
                const th = document.createElement('div');
                th.classList.add('th');
                th.style.textTransform = 'capitalize';

                // Agregar el nombre del día
                const diaDiv = document.createElement('div');
                diaDiv.style.height = '4rem';

                if (horarioArray[0] && horarioArray[0][0] && horarioArray[0][0].timestamp) {
                    const timestamp = horarioArray[0][0].timestamp;
                    const fecha = new Date(timestamp.toString().length === 10 ? timestamp * 1000 : timestamp);
                    if (!isNaN(fecha.getTime())) {
                        diaDiv.textContent = fecha.toLocaleDateString('es-ES', { weekday: 'long' });
                    } else {
                        diaDiv.textContent = 'Fecha inválida';
                    }
                } else {
                    diaDiv.textContent = 'Fecha no disponible';
                }

                th.appendChild(diaDiv);

                // Agregar los intervalos
                horarioArray.forEach(intervalos => {
                    intervalos.forEach(intervalo => {
                        const intervaloDiv = document.createElement('div');
                        const alturaCalculada = intervalo.height < 17
                            ? intervalo.height / 2
                            : intervalo.height / 4;

                        // Asegurar que la altura mínima sea 1.75rem
                        intervaloDiv.style.height = `calc(${Math.max(alturaCalculada, 1.75)}rem)`;

                        const link = document.createElement('a');
                        link.href = intervalo.valida
                            ? `/{{ request()->slug_instalacion }}/{{ $pista_selected->tipo }}/{{ $pista_selected->id }}/${intervalo.timestamp}`
                            : '#';
                        link.className = intervalo.valida ? 'btn-reservar' : 'btn-no-disponible';
                        link.textContent = intervalo.string;

                        intervaloDiv.appendChild(link);
                        th.appendChild(intervaloDiv);
                    });
                });

                thead.appendChild(th);
            });
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Hubo un problema al cargar los horarios.');
        });
}
                            </script>
                        @else
                            
                        @endif
                    @endif
                </div>
            @endif

            {{--             @if ($instalacion->slug == 'vvadecordoba')

                <div  id="boton-piscina" ><a href="/vvadecordoba/piscina"><h3>Reservar entradas de piscina</h3></a> </div>
                @endif --}}
            @if ($eventos->count())
                <div class="card shadow-box mb-4">
                    <div class="card-header bg-white font-bold" style="font-weight: bold;padding: 12px;font-size: 18px;">
                        @if (
                            $instalacion->finalidad_eventos == FINALIDAD_ENTRADA &&
                                request()->slug_instalacion != 'feria-jamon-villanuevadecordoba')
                            Entradas disponibles a la venta
                        @elseif (request()->slug_instalacion == 'feria-jamon-villanuevadecordoba')
                            Bonos disponibles a la venta
                        @elseif (request()->slug_instalacion == 'la-guijarrosa')
                            Inscripciones
                        @else
                            Escuelas deportivas e inscripciones
                        @endif
                    </div>
                    <div class="card-body p-0" style="padding: 12px">

                        @if (request()->slug_instalacion != 'eventos-bodega' || request()->slug_instalacion != 'feria-jamon-villanuevadecordoba')


                            <ul class="nav nav-tabs justify-content-center">
                                @if (count($eventos->where('renovacion_mes', 1)) > 0)
                                    <li class="active" onclick="return false;"><a href="#mensuales"
                                            class="active show">Escuelas</a></li>
                                @endif
                                <li><a href="#casuales" onclick="return false;"
                                        @if (count($eventos->where('renovacion_mes', 1)) == 0) class="active show" @endif>
                                        @if ($instalacion->finalidad_eventos == FINALIDAD_ENTRADA)
                                            Programación
                                        @else
                                            Eventos
                                        @endif
                                    </a></li>
                            </ul>
                        @endif
                        <div class="tab-content">
                            @if (count($eventos->where('renovacion_mes', 1)) > 0)
                                <div id="mensuales" class="tab-pane fade in active show">
                                @else
                                    <div id="mensuales" class="tab-pane fade ">
                            @endif
                            <ul class="list-group lista-eventos">
                                @foreach ($eventos->where('renovacion_mes', 1) as $item)
                                    <li class="list-group-item item-evento"
                                        style="display: flex;gap:30px;align-items: center;padding: 15px">
                                        <div><img src="/img/eventos/{{ $instalacion->slug }}/{{ $item->id }}.jpg"
                                                style="max-height:125px;max-width:95px"></div>
                                        <div style="width: 100%">
                                            <div class="fecha_inicio_evento d-flex justify-content-between">
                                                <div>{{ $item->nombre }}</div>
                                                <div>
                                                    <a href="/{{ $instalacion->slug }}/evento/{{ $item->id }}"
                                                        class="btn btn-success ">Inscribirse <i
                                                            class="fas fa-arrow-right ml-1"></i></a>
                                                </div>
                                            </div>
                                            <div class="contenido-evento">
                                                <div class="titulo-evento">Inscripción <span class="num-dia-evento">
                                                        {{ date('d') <= 31 ? strftime('%B') : strftime('%B', strtotime('01-' . (date('m') + 1) . '-' . date('Y'))) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        @if (count($eventos->where('renovacion_mes', 1)) > 0)
                            <div id="casuales" class="tab-pane fade ">
                            @else
                                <div id="casuales" class="tab-pane fade in active show">
                        @endif
                        <ul class="list-group lista-eventos">
                            @php
                                // Definimos la consulta base
                                $eventosOrdenados = $eventos->where('renovacion_mes', 0);

                                // Si la condición se cumple, aplicamos el ordenamiento
                                if (request()->slug_instalacion == 'feria-jamon-villanuevadecordoba') {
                                    $eventosOrdenados = $eventosOrdenados->sortBy('created_at');
                                }
                            @endphp
                            @forelse ($eventosOrdenados as $item)
                                <li class="list-group-item item-evento"
                                    style="display: flex;gap:30px;align-items: center;padding: 15px">
                                    @if ($instalacion->slug != 'ciprea24')
                                        <div><img id="{{ $item->id }}" class="imageEvento"
                                                src="/img/eventos/{{ $instalacion->slug }}/{{ $item->id }}.jpg"
                                                style="max-height:125px;max-width:95px">

                                            {{-- modal for image --}}
                                            <div class="modal fade" id="modal-{{ $item->id }}" tabindex="-1"
                                                role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">
                                                                {{ $item->nombre }}</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <img src="/img/eventos/{{ $instalacion->slug }}/{{ $item->id }}.jpg"
                                                                style="max-height:100%;max-width:100%">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    @else
                                    @endif
                                    <div style="width: 100%">
                                        <div class="fecha_inicio_evento d-flex justify-content-between">
                                            @if ($instalacion->finalidad_eventos == FINALIDAD_ENTRADA)
                                                <div class=""
                                                    @if (request()->slug_instalacion == 'eventos-bodega') style="font-family: 'BODAS';font-size: 30px;" @endif>
                                                    {{ $item->nombre }}</div>
                                            @else
                                                <div><span
                                                        class="num-dia-evento">{{ date('d', strtotime($item->fecha_inicio)) }}</span>
                                                    de {{ strftime('%B', strtotime($item->fecha_inicio)) }}</div>
                                            @endif
                                            <div>

                                                @if ($instalacion->finalidad_eventos == FINALIDAD_ENTRADA)
                                                    @if (request()->slug_instalacion == 'eventos-bodega' || request()->slug_instalacion == 'villafranca-de-cordoba' and
                                                            $item->entradas_agotadas == 1)
                                                        <a href="#" class="btn disabled">
                                                            Plazas agotadas
                                                            <i class="fas fa-arrow-right ml-1"></i>
                                                        </a>
                                                    @elseif (request()->slug_instalacion == 'feria-jamon-villanuevadecordoba' and $item->entradas_agotadas == 1)
                                                        <a href="#" class="btn disabled" style="color: red">
                                                            <b>Bonos agotados</b>

                                                        </a>
                                                    @else
                                                        @if (Auth::check() || request()->slug_instalacion == 'feria-jamon-villanuevadecordoba')
                                                            @if (request()->slug_instalacion == 'feria-jamon-villanuevadecordoba' and $item->id == 5)
                                                                <a href="https://tuneldeljamon.com/inicio/index"
                                                                    class="btn btn-success" target="_blank">
                                                                    Comprar
                                                                    <i class="fas fa-arrow-right ml-1"></i>
                                                                </a>
                                                            @else
                                                                <a href="/{{ $instalacion->slug }}/evento/{{ $item->id }}"
                                                                    class="btn btn-success">
                                                                    Comprar
                                                                    <i class="fas fa-arrow-right ml-1"></i>
                                                                </a>
                                                            @endif
                                                        @else
                                                            <a href="/{{ $instalacion->slug }}/register/"
                                                                class="btn btn-success">Comprar <i
                                                                    class="fas fa-arrow-right ml-1"></i></a>
                                                        @endif
                                                    @endif
                                                @else
                                                    {{-- 31,  35 --}}
                                                    @if (request()->slug_instalacion == 'villafranca-de-cordoba' and $item->id == 31 || $item->id == 35)
                                                        <a href="#" class="btn disabled">
                                                            Suspendido
                                                            <i class="fas fa-arrow-right ml-1"></i>
                                                        </a>
                                                    @else
                                                        <a href="/{{ $instalacion->slug }}/evento/{{ $item->id }}"
                                                            class="btn btn-success">
                                                            Inscribirse
                                                            <i class="fas fa-arrow-right ml-1"></i>
                                                        </a>
                                                    @endif
                                                @endif

                                            </div>
                                        </div>
                                        @if (request()->slug_instalacion == 'eventos-bodega')
                                            <div class="fecha">
                                                <span
                                                    class="dia">{{ \Carbon\Carbon::parse($item->fecha_inicio)->IsoFormat('D') }}</span>
                                                <span
                                                    class="mes">{{ \Carbon\Carbon::parse($item->fecha_inicio)->IsoFormat('[de] MMMM') }}</span>

                                            </div>
                                        @endif
                                        <div class="contenido-evento">
                                            @if ($instalacion->finalidad_eventos == FINALIDAD_ENTRADA)
                                                <div>{!! $item->descripcion !!}</div>
                                            @else
                                                <div class="titulo-evento">{{ $item->nombre }}</div>
                                            @endif
                                            @if (request()->slug_instalacion != 'villafranca-navidad' and
                                                    request()->slug_instalacion != 'villafranca-actividades' and
                                                    request()->slug_instalacion != 'ciprea24' and
                                                    request()->slug_instalacion != 'eventos-bodega' and
                                                    request()->slug_instalacion != 'feria-jamon-villanuevadecordoba')
                                                @if (!$item->renovacion_mes)
                                                    <div class="cierre-inscrp" style="margin-top:10px !important;">Nº
                                                        participantes: {{ $item->num_participantes }}</div>
                                                    @if ($item->plazas_disponibles <= 0)
                                                        <div class="cierre-inscrp">Plazas disponibles: 0</div>
                                                    @else
                                                        <div class="cierre-inscrp">Plazas disponibles:
                                                            {{ $item->plazas_disponibles }}</div>
                                                    @endif
                                                    <div class="cierre-inscrp">Cierre de inscripción:
                                                        {{ date('d/m/Y', strtotime($item->insc_fecha_fin)) }}</div>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </li>
                            @empty
                                <div class="text-center pb-4">No se encuentran inscripciones de este tipo.</div>
                            @endforelse
                        </ul>
                    </div>
                </div>
        </div>
    </div>
    @endif

    @if ($servicios->count() and $instalacion->permisos->ver_servicios == 1 and $instalacion->permisos->ver_servicios_admin == 1)
        <div class="card shadow-box mb-4">
            <div class="card-header bg-white font-bold" style="font-weight: bold;padding: 12px;font-size: 18px;">Servicios
            </div>

            <div class="card-body p-0" style="padding: 12px">
                <div class="">
                    <div id="mensuales" class="tab-pane fade in active show">
                        <ul class="list-group" style="padding: 1%;">

                            @php
                                $descuentos = [];
                                if (Auth::user()) {
                                    $servicios_usuario = \App\Models\Servicio_Usuario::where(
                                        'id_usuario',
                                        Auth::user()->id,
                                    )
                                        ->where('activo', 'si')
                                        ->get();
                                    foreach ($servicios_usuario as $servicio_usuario) {
                                        $descuento = \App\Models\Descuento::where(
                                            'id_servicio_padre',
                                            $servicio_usuario->id_servicio,
                                        )
                                            ->select('id_servicio_descuento', 'nuevo_precio')
                                            ->get();
                                        if ($descuento) {
                                            array_push($descuentos, $descuento);
                                        }
                                    }
                                }

                            @endphp
                            @foreach ($servicios as $servicio)
                                @php
                                    $servicio_descuento = false;
                                    if (!empty($descuentos)) {
                                        foreach ($descuentos[0]->toArray() as $key => $value) {
                                            if ($value['id_servicio_descuento'] == $servicio->id) {
                                                $servicio->precio = $value['nuevo_precio'];
                                                $servicio_descuento = true;
                                            }
                                        }
                                    }
                                @endphp
                                <li class="list-group-item item-evento item-servicio"
                                    style="display: flex;gap:30px;align-items: center;padding: 15px">

                                    <div style="width: 100%">
                                        <div class="fecha_inicio_evento d-flex justify-content-between">
                                            <div><span class="">{{ $servicio->nombre }} </span></div>
                                            <div>

                                                @if (Auth::check() &&
                                                        \App\Models\Servicio_Usuario::where('id_usuario', Auth::user()->id)->where('id_servicio', $servicio->id)->where('activo', 'si')->exists())
                                                    <a href="{{-- /{{ $instalacion->slug }}/servicios/{{$servicio->id}}/contratar --}}#" class="btn btn-secondary contratar"
                                                        style="pointer-events: none">Contratado </a>
                                                @elseif(Auth::check() &&
                                                        \App\Models\Servicio_Usuario::where('id_usuario', Auth::user()->id)->where('id_servicio', $servicio->id)->where('activo', 'no')->exists() and
                                                        count(
                                                            \App\Models\Servicio_Usuario::where('id_usuario', Auth::user()->id)->where('id_servicio', $servicio->id)->where('activo', 'no')->first()->recibos_sin_pago) ==
                                                            0)
                                                    <a href="/{{ $instalacion->slug }}/servicios/{{ $servicio->id }}/contratar-de-nuevo"
                                                        class="btn btn-success contratar">Contratar de nuevo</a>
                                                @else
                                                    <a href="/{{ $instalacion->slug }}/servicios/{{ $servicio->id }}/contratar"
                                                        class="btn btn-success contratar">Contratar </a>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="contenido-evento">
                                            <div class="" style="text-transform: capitalize"><b>Precio:</b>
                                                {{ $servicio->precio }}€ </div>
                                            <div class="cierre-inscrp"><b>Forma de pago:</b> <span
                                                    style="text-transform: capitalize">{{ $servicio->duracion }}</span>
                                            </div>
                                            @if (isset($servicio->descripcion))
                                                <div class="cierre-inscrp">{!! $servicio->descripcion !!}</div>
                                            @endif
                                        </div>
                                        @if (Auth::check() &&
                                                \App\Models\Servicio_Usuario::where('id_usuario', Auth::user()->id)->where('id_servicio', $servicio->id)->exists())
                                            <div class="contratar_responsive" style="width: 100%;"><a href="#"
                                                    class="btn btn-secondary"
                                                    style="width: 100%;pointer-events: none">Contratado </a></div>
                                        @else
                                            <div class="contratar_responsive" style="width: 100%;"><a
                                                    href="/{{ $instalacion->slug }}/servicios/{{ $servicio->id }}/contratar"
                                                    class="btn btn-success" style="width: 100%;">Contratar </a></div>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                </div>
            </div>
        </div>

    @endif
    @php
        // Filtramos los bonos que están activos
        //si es la guijarrosa o santaella
        $bonos_activos = collect();
        if (
            (request()->slug_instalacion == 'la-guijarrosa' || request()->slug_instalacion == 'santaella') &&
            Auth::user()
        ) {
            $bonos_activos = $bonos->filter(function ($bono) {
                return $bono->activado == 1;
            });
        }
    @endphp
    @if (
        (request()->slug_instalacion == 'la-guijarrosa' || request()->slug_instalacion == 'santaella') &&
            $bonos_activos->count() > 0)
        <!-- Mostrar los bonos aquí -->
        <div>
            <div class="card shadow-box mb-4">
                <div class="card-header bg-white font-bold" style="font-weight: bold;padding: 12px;font-size: 18px;">Bonos
                </div>

                <div class="card-body p-0" style="padding: 12px">
                    <div class="">
                        <div id="bonos" class="tab-pane fade in active show">
                            <ul class="list-group" style="padding: 1%;">
                                @foreach ($bonos_activos as $bono)
                                    <li class="list-group-item item-evento item-servicio"
                                        style="display: flex;gap:30px;align-items: center;padding: 15px">

                                        <div style="width: 100%">
                                            <div class="fecha_inicio_evento d-flex justify-content-between">
                                                <div><span class="">{{ $bono->nombre }} </span></div>
                                                <div>
                                                    @if (Auth::check() &&
                                                            \App\Models\BonoUsuario::where('id_usuario', Auth::user()->id)->where('id_bono', $bono->id)->where('estado', 'active')->exists())
                                                        <a href="#" class="btn btn-secondary contratar"
                                                            style="pointer-events: none">Contratado</a>
                                                    @else
                                                        <a href="/{{ $instalacion->slug }}/bonos/{{ $bono->id }}/contratar"
                                                            class="btn btn-success contratar">Contratar</a>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="contenido-evento">
                                                <div class="" style="text-transform: capitalize"><b>Precio:</b>
                                                    {{ $bono->precio }}€ </div>
                                                <div class=""><b>Total de usos:</b> {{ $bono->num_usos }} </div>
                                            </div>
                                            <div class="cierre-inscrp">{!! $bono->descripcion !!}</div>
                                            {{-- @if (Auth::check() &&
    \App\Models\Servicio_Usuario::where('id_usuario', Auth::user()->id)->where('id_servicio', $servicio->id)->exists())
                                                <div  class="contratar_responsive" style="width: 100%;"><a href="#" class="btn btn-secondary" style="width: 100%;pointer-events: none" >Contratado </a></div>
                                                @else
                                                <div  class="contratar_responsive" style="width: 100%;"><a href="/{{ $instalacion->slug }}/servicios/{{$servicio->id}}/contratar" class="btn btn-success" style="width: 100%;">Contratar </a></div>

                                            @endif --}}
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- No mostrar nada -->
    @endif

    @if (
        $instalacion->html_normas != '' and
            request()->slug_instalacion != 'eventos-bodega' and
            $instalacion->permisos->ver_normas == 1 and $instalacion->permisos->ver_normas_admin == 1)
        <div class="card card-info shadow-box mb-4">
            <div class="card-header bg-white font-bold">
                @if (request()->slug_instalacion != 'villafranca-navidad' and
                        request()->slug_instalacion != 'villafranca-actividades' and
                        request()->slug_instalacion != 'ciprea24' and
                        request()->slug_instalacion != 'eventos-bodega')
                    <h5 class="mb-0">Información de la instalación</h5>
                @else
                    <h5 class="mb-0">Cartel del evento</h5>
                @endif
            </div>
            <div class="card-body">
                <div class="info-instalacion">
                    @if (request()->slug_instalacion != 'villafranca-navidad' and
                            request()->slug_instalacion != 'villafranca-actividades' and
                            request()->slug_instalacion != 'ciprea24' and
                            request()->slug_instalacion != 'eventos-bodega')
                        {!! html_entity_decode($instalacion->html_normas) !!}
                    @elseif (request()->slug_instalacion == 'villafranca-actividades')
                        <img src="{{ asset('img/eventos/villafranca-actividades/cartel_actividades.jpg') }}"
                            alt="" srcset="" style="max-width: 100%;">
                    @elseif (request()->slug_instalacion == 'eventos-bodega')
                        <img src="{{ asset('img/eventos/eventos-bodega/cartel_bodegas.jpg') }}" alt=""
                            srcset="" style="max-width: 100%;">
                    @else
                        <img src="{{ asset('img/eventos/villafranca-navidad/7.jpg') }}" alt="" srcset=""
                            style="max-width: 100%;">
                    @endif
                </div>
                <div class="separador-linea"></div>
                <button type="button" class="toggle-ver-mas">Ver más +</button>
                <div class="galeria-intalacion" @if (file_exists(public_path() . '/img/galerias/' . $instalacion->slug)) style="height:350px" @endif>
                    @if (file_exists(public_path() . '/img/galerias/' . $instalacion->slug))
                        @foreach (\File::files(public_path() . '/img/galerias/' . $instalacion->slug) as $index => $item)
                            <input type="radio" name="slider" id="item-{{ $index + 1 }}"
                                {{ $index == 0 ? 'checked' : '' }}>
                            {{-- <div class="position-relative border p-2 mr-2">
                                    <img src="/img/galerias/{{ $instalacion->id }}/{{pathinfo($item)['basename']}}"
                                        style="width: 75px">
                                </div> --}}
                        @endforeach
                        <div class="cards">
                            @foreach (\File::files(public_path() . '/img/galerias/' . $instalacion->slug) as $index => $item)
                                <label class="card" for="item-{{ $index + 1 }}" id="song-{{ $index + 1 }}">
                                    <img src="/img/galerias/{{ $instalacion->slug }}/{{ pathinfo($item)['basename'] }}"
                                        alt="song">
                                </label>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
    </div>

    <div class="col-md-3">

        @if (request()->slug_instalacion != 'villafranca-de-cordoba' and
                request()->slug_instalacion != 'villafranca-navidad' and
                request()->slug_instalacion != 'villafranca-actividades' and
                request()->slug_instalacion != 'ciprea24' and
                request()->slug_instalacion != 'eventos-bodega' and
                request()->slug_instalacion != 'feria-jamon-villanuevadecordoba')

            <div class="card shadow-box mb-4">
                @if ($instalacion->slug == 'santaella')
                    <div class="card-header bg-white font-bold" style="font-weight: bold;padding: 12px;font-size: 18px;">
                        Horario del Pabellón de Deportes</div>
                @else
                    <div class="card-header bg-white font-bold" style="font-weight: bold;padding: 12px;font-size: 18px;">
                        Horario</div>
                @endif
                <div class="card-body p-0" style="padding: 12px">{{-- {{ dd(unserialize($instalacion->horario)) }} --}}
                    <ul class="list-group group-horario">
                        @if ($instalacion->horario && $instalacion->permisos->ver_horario == 1 && $instalacion->permisos->ver_horario_admin == 1)
                            @foreach (unserialize($instalacion->horario) as $index => $horario)
                                <li class="list-group-item">
                                    <div>{{ $index }}</div>
                                    <div>
                                        @foreach ($horario['intervalo'] as $item)
                                            @if ($item['hinicio'] == null and $item['hfin'] == null)
                                                Cerrado <br>
                                            @else
                                                {{ $item['hinicio'] }} - {{ $item['hfin'] }}<br>
                                            @endif
                                        @endforeach
                                    </div>
                                </li>
                            @endforeach
                        @else
                            <li class="list-group-item">
                                <div>Horario no disponible</div>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        @endif
        @if($instalacion->permisos->ver_mapa == 1 && $instalacion->permisos->ver_mapa_admin == 1)
        <div class="card shadow-box mb-4">
            <div class="card-body p-0">
                <div style="width: 100%"><iframe width="100%" height="180" frameborder="0" scrolling="no"
                        marginheight="0" marginwidth="0"
                        src="https://maps.google.com/maps?width=100%25&amp;height=600&amp;hl=en&amp;q={{ $instalacion->direccion }}+(Your%20Business%20Name)&amp;t=&amp;z=14&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"></iframe>
                </div>
            </div>
            <div class="card-footer bg-white font-bold" style="font-weight: 500;padding: 10px 8px;">Localización:
                {{ $instalacion->direccion }}</div>
        </div>
        @endif
        @if (request()->slug_instalacion != 'villafranca-de-cordoba')
            @if (count($instalacion->deportes_clases) > 0 && $instalacion->permisos->ver_deportes == 1 && $instalacion->permisos->ver_deportes_admin == 1)
                <div class="card shadow-box mb-4">
                    <div class="card-header bg-white font-bold" style="font-weight: bold;padding: 12px;font-size: 18px;">
                        Deportes</div>
                    <div class="card-body" style="padding: 12px">
                        <ul class="list-tags">
                            @foreach ($instalacion->deportes_clases as $index => $item)
                                @if (file_exists(public_path('img/deportes/icons/' . $item->id . '.png')))
                                    <li><img src="/img/deportes/icons/{{ $item->id }}.png" width="12"
                                            height="12" class="mr-2"> {{ $item->nombre }}</li>
                                @else
                                    <li>{{ $item->nombre }}</li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @if (
                $instalacion->servicios and
                    request()->slug_instalacion != 'villafranca-navidad' and
                    request()->slug_instalacion != 'villafranca-actividades' and
                    request()->slug_instalacion != 'ciprea24' and
                    request()->slug_instalacion != 'eventos-bodega' and
                    $instalacion->permisos->ver_serviciosadicionales == 1 and $instalacion->permisos->ver_serviciosadicionales_admin == 1)
                <div class="card shadow-box mb-4">
                    <div class="card-header bg-white font-bold" style="font-weight: bold;padding: 12px;font-size: 18px;">
                        Servicios</div>
                    <div class="card-body" style="padding: 12px">
                        <ul class="list-tags">
                            @if (unserialize($instalacion->servicios) == null)
                                <li>No hay servicios disponibles</li>
                            @else
                                @foreach (unserialize($instalacion->servicios) as $item)
                                @if(\App\Models\Servicios_adicionales::find($item))
                                    <li><img src="/img/servicios/{{ $item }}.png" width="12" height="12"
                                            class="mr-2"> {{ \App\Models\Servicios_adicionales::find($item)->nombre }}
                                    </li>
                                @endif
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>
            @endif
    </div>
    </div>
    @endif
@endsection

@section('script')
    <script>
        $(document).ready(function() {

            $(".imageEvento").click(function() {
                var id = $(this).attr("id");
                console.log("#modal-" + id);
                $("#modal-" + id).modal("show");
            });

            let tipoReserva = $("#contenedor-reservas").data("tipo-reserva");



            var array = $('.dias_fest').val();
            console.log("Array")
            console.log(array);

            if (tipoReserva == 1) {
                $datepicker = $('.select-fecha');

                $datepicker.datepicker({
                    dateFormat: 'yy-mm-dd',
                    altField: '#alt-select-fecha',
                    altFormat: 'dd/mm/yy',
                    minDate: new Date(),
                    showButtonPanel: true,
                    beforeShowDay: function(date) {
                        var string = jQuery.datepicker.formatDate('yy-mm-dd', date);
                        return [array.indexOf(string) == -1]
                    }
                });



                $('.div-hoy, #alt-select-fecha').click(function(e) {
                    e.preventDefault();
                    $datepicker.datepicker("show");
                });

                $('.select-pista, .select-fecha').change(function(e) {
                    e.preventDefault();
                    $('.loader-horario').show();
                    if ($('.select-fecha').val() != "{{ date('Y-m-d') }}") {
                        $('.div-hoy').hide();
                        $('.block-before').hide();
                    } else {
                        $('.div-hoy').show();
                        $('.block-before').show();
                    }
                    let deporte = $('.select-pista').val();
                    let fecha = $('.select-fecha').val();
                    console.log("Fecha")
                    console.log(fecha);

                    console.log( `/{{ request()->slug_instalacion }}/pistas-por-deporte/${deporte}/${fecha}`)
                    $.ajax({
                        url: `/{{ request()->slug_instalacion }}/pistas-por-deporte/${deporte}/${fecha}`,
                        method: 'GET',
                        dataType: 'json',
                        headers:{'Accept': 'application/json'},
                        success: data => {
                            console.log("DATA")
                            console.log(data);
                            $('.slot.celda.slot-reserva').remove();
                            let html_nombre_pistas =
                                '<div class="celda" style="height: 40px; line-height: 40px;"></div>';
                            let html_horarios_pistas = '';
                            $.each(data, function(index, pista) {
                                html_nombre_pistas +=
                                    `<div class="celda" style="height: 40px; line-height: 40px;">${pista.nombre}</div>`;
                                $(`.slots-pista:nth-child(${index+1})>div`).find(
                                    '.slot-reserva').remove();
                                html_horarios_pistas +=
                                    '<div class="slots-pista"><div class="slots-horas">';
                                for ($i = 7; $i <= 23; $i++) {
                                    html_horarios_pistas +=
                                        `<div class="celda" style="width: 40px; height: 40px; line-height: 40px;"></div>`;
                                }
                                $.each(pista.horario_con_reservas_por_dia, function(i,
                                    val) {
                                    $.each(val, function(i_intervalo,
                                        intervalo) {
                                        console.log(intervalo.valida);
                                        var valor = "";
                                        var texto = intervalo.string;
                                        if (!intervalo.valida) {
                                            switch (intervalo.estado) {
                                                case 'reservado':
                                                    valor =
                                                        "btn-reservado";
                                                    texto = "RESERVADA";
                                                    break;
                                                case 'desactivado':
                                                    valor =
                                                        "btn-no-disponible";
                                                    texto =
                                                        "NO DISPONIBLE";
                                                    break;
                                            }

                                        }

                                        html_horarios_pistas += `<div data-left="${intervalo.hora}" data-width="${intervalo.width}" class="slot celda slot-reserva" style="${$(window).width() >= 992 ? 'left' : 'top'}:${intervalo['hora']}px;${$(window).width() >= 992 ? 'width' : 'height'}:  ${intervalo['width']}px ${$(window).width() >= 992 ? '' : '!important'};height:40px;position:absolute;z-index:20">
                                                                <div class="${valor}">
                                                                    <a ${!intervalo.valida ? 'href="#" class="d-block h-100"' : `data-toggle="tooltip" data-html="true" data-placement="top" title="${pista.nombre}
                                                    ${intervalo.string}" class="d-block h-100" href="/{{ request()->slug_instalacion }}/${pista.tipo}/${pista.id}/${intervalo['timestamp']}"`}><span class="show-responsive">${texto}</span></a>
                                                                </div>
                                                            </div>`;
                                    });
                                });
                                html_horarios_pistas += '</div></div>';
                                $('.pistas-horario').html(html_nombre_pistas);
                            });

                            $(`.horas-tramos-pistas`).html(html_horarios_pistas);

                            $('[data-toggle="tooltip"]').tooltip();

                            $('.loader-horario').hide();
                        },
                        error: function(xhr) {
        console.error("Error en la solicitud:", xhr.responseText);
        if (xhr.responseText.includes('<!DOCTYPE html>')) {
            console.error("El servidor devolvió HTML en lugar de JSON.");
        }
    }
                    });
                });

                if ($(window).width() < 992) {
                    /* $('.horas-tramos-pistas').html($('.horas-tramos-pistas').html().replace('left', 'top').replace('height:40px;', '').replace('width', 'height').replace(';height:', ' !important;height:').replace(';position:absolu', ' !important;position:absolu')) */
                    $('.slot-reserva').each(function() {
                        $(this).attr('style', $(this).attr('style').replace('left', 'top').replace(
                            'height:40px;', '').replace('width', 'height').replace(';height:',
                            ' !important;height:').replace(';position:absolu',
                            ' !important;position:absolu'));
                    });
                    // $('.pistas-horario').children().not(':last-child').not(':nth-last-child(2)').slice(5).addClass('hidden');
                    // mostrarZonasAnterioresBtn.addClass('hidden');
                    // $('.horas-tramos-pistas').children().slice(4).addClass('hidden');
                }
                $(window).resize(function() {
                    if ($(window).width() < 992) {
                        /* $('.horas-tramos-pistas').html($('.horas-tramos-pistas').html().replace('left', 'top').replace('height:40px;', '').replace('width', 'height').replace(';height:', ' !important;height:').replace(';position:absolu', ' !important;position:absolu')) */
                        $('.slot-reserva').each(function() {
                            $(this).attr('style',
                                `top:${$(this).data('left')}px !important;height: ${$(this).data('width')}px !important;position:absolute;z-index:20`
                            );
                        });
                    } else {
                        $('.slot-reserva').each(function() {
                            $(this).attr('style',
                                `left:${$(this).data('left')}px;width: ${$(this).data('width')}px;height:40px;position:absolute;z-index:20`
                            );
                        });
                    }
                });
            } else {
                // START----Si la instalación tiene el segundo tipo de reservas
                $datepicker = $(".select-fecha");
                $datepicker.datepicker({
                    dateFormat: 'mm yy',
                    minDate: new Date(),
                    showButtonPanel: true,
                    changeMonth: true,
                    changeYear: true,
                    beforeShowDay: function(date) {
                        return [false];
                    },
                    onClose: function(dateText, inst) {
                        $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth,
                            1));
                        let meses = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio',
                            'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'
                        ];
                        let nombreMes = meses[inst.selectedMonth];
                        let year = $(this).val().substring($(this).val().length - 4);
                        let fecha = nombreMes + ' ' + year;
                        $('.loader-horario').show();
                        if (fecha != $(".div-hoy").data("hoy")) {
                            $('.div-hoy').html(fecha);
                            $('.block-before').hide();
                        } else {
                            $('.div-hoy').html(fecha);
                            $('.block-before').show();
                        }

                        let deporte = $('.select-pista').val();
                        console.log(inst.selectedYear);

                        $.ajax({
                            url: `/{{ request()->slug_instalacion }}/pistas-por-deporte-mes/${deporte}/${inst.selectedMonth + 1}/${inst.selectedYear}`,
                            success: data => {
                                //como todas las pistas van a tener el mismo horario
                                //solo se coge el horario de la primera pista
                                // $('.horas-tramos').html("hola");
                                $horas_tramos = "";
                                $.each(data[0].horario_con_reservas_por_mes, function(i,
                                    val) {
                                    $.each(val, function(i_intervalo, intervalo) {
                                        let fecha = new Date(intervalo
                                            .timestamp * 1000);
                                        console.log(fecha, intervalo
                                            .timestamp);
                                        let diasSemana = ['Domingo',
                                            'Lunes', 'Martes',
                                            'Miércoles', 'Jueves',
                                            'Viernes', 'Sábado'
                                        ];

                                        let nombreDiaSemana = diasSemana[
                                            fecha.getDay()];

                                        let numeroDiaMes = fecha.getDate();
                                        $horas_tramos +=
                                            `<div class="celda" style="flex: 1;height:40px; line-height: 40px;">${nombreDiaSemana} - ${numeroDiaMes}</div>`
                                    });
                                });
                                $('.horas-tramos').html($horas_tramos);
                                $('.slot.celda.slot-reserva').remove();
                                let html_nombre_pistas =
                                    '<div class="celda" style="height: 40px; line-height: 40px;"></div>';
                                let html_horarios_pistas = '';
                                $.each(data, function(index, pista) {
                                    html_nombre_pistas +=
                                        `<div class="celda" style="height: 40px; line-height: 40px;">${pista.nombre}</div>`;
                                    $(`.slots-pista:nth-child(${index+1})>div`)
                                        .find(
                                            '.slot-reserva').remove();
                                    html_horarios_pistas +=
                                        '<div class="slots-pista"><div class="slots-horas">';
                                    for ($i = 0; $i < pista
                                        .horario_con_reservas_por_mes[0]
                                        .length; $i++) {
                                        html_horarios_pistas +=
                                            `<div class="celda" style="flex: 1; height: 40px; line-height: 40px;"></div>`;
                                    }
                                    $.each(pista.horario_con_reservas_por_mes,
                                        function(i,
                                            val) {
                                            $.each(val, function(i_intervalo,
                                                intervalo) {
                                                // console.log(intervalo.valida);
                                                var valor = "";
                                                var texto = "";
                                                if (!intervalo.valida) {
                                                    switch (intervalo
                                                        .estado) {
                                                        case 'reservado':
                                                            valor =
                                                                "btn-reservado";
                                                            texto =
                                                                "RESERVADA";
                                                            break;
                                                        case 'desactivado':
                                                            valor =
                                                                "btn-no-disponible";
                                                            texto =
                                                                "NO DISPONIBLE";
                                                            break;
                                                    }
                                                }

                                                html_horarios_pistas += `<div data-left="${intervalo.hora}" data-width="${intervalo.width}" class="slot celda slot-reserva" style="left: ${intervalo['hora']}%;width: ${intervalo['width']}%;height:40px;position:absolute;z-index:20">
                                                                <div class="${valor}">
                                                                    <a ${!intervalo.valida ? 'href="#" class="d-block h-100"' : `data-toggle="tooltip" data-html="true" data-placement="top" title="${pista.nombre}" class="d-block h-100" href="/{{ request()->slug_instalacion }}/${pista.tipo}/${pista.id}/${intervalo['timestamp']}"`}><span class="show-responsive">${texto}</span></a>
                                                                </div>
                                                            </div>`;
                                            });
                                        });
                                    html_horarios_pistas += '</div></div>';
                                    $('.pistas-horario').html(html_nombre_pistas);
                                });

                                $(`.horas-tramos-pistas`).html(html_horarios_pistas);

                                $('[data-toggle="tooltip"]').tooltip();

                                $('.loader-horario').hide();
                            },
                            error: data => {
                                console.log("ERROR".data);
                            }
                        });
                    },
                });

                $('.div-hoy, #alt-select-fecha').click(function(e) {
                    e.preventDefault();
                    $datepicker.datepicker("show");
                });

                // END---
            }

            $('.toggle-ver-mas').click(function(e) {
                e.preventDefault();
                $(this).prev().prev().toggleClass('no-max-height');
                if ($(this).prev().hasClass('no-max-height')) {
                    $(this).html('Ver menos -');
                } else {
                    $(this).html('Ver más +');
                }
            });

            $('.select-fecha').click(function(e) {
                /* e.preventDefault(); */
            });

            $(".nav-tabs a").click(function() {
                $('.tab-content>div').removeClass('in active show');
                $('.nav-tabs li, .nav-tabs a').removeClass('active show');
                $(this).tab('show');
            });
        });
    </script>
    @if (request()->slug_instalacion == 'villafranca-navidad' or
            request()->slug_instalacion == 'villafranca-actividades' and request()->slug_instalacion != 'ciprea24' or
            request()->slug_instalacion == 'eventos-bodega')
        <script>
            setTimeout(() => {
                $('.toggle-ver-mas').trigger('click');
                $('.toggle-ver-mas').hide();
            }, 1000);
        </script>
    @endif

    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCT5hS4KbxtQbH2VQfDb7KWFK-BN7vVyeA"></script>


    <script type="text/javascript">
        const watchId = navigator.geolocation.watchPosition(position => initMap(position), );





        function error() {
            alert('error, active su ubicación para abrir la puerta');
        }

        function stopWatch() {
            navigator.geolocation.clearWatch(watchId);
        }

        function initMap(position) {
            const {
                latitude,
                longitude
            } = position.coords;

            const center = {
                lat: latitude,
                lng: longitude
            };
            const options = {
                zoom: 15,
                scaleControl: true,
                center: center
            };
            // map = new google.maps.Map(document.getElementById('map'), options);

            const point = {
                lat: latitude,
                lng: longitude
            };

            // create marker
            // var marker = new google.maps.Marker({position: point, map: map});

            navigator.geolocation.clearWatch(watchId);

            const latitud = point.lat;

            $("#latitud").val(latitud);

            const longitud = point.lng;
            $("#longitud").val(longitud);

        }
    </script>

    <script>
        $("#apertura_gym").click(function() {


            $("#apertura_gym").html("Abriendo...").delay(1000);
            var formdata = {
                lat: $("#latitud").val(),
                lng: $("#longitud").val(),

            };

            console.log(formdata);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },

                url: '/{{ request()->slug_instalacion }}/apertura-gym',
                data: formdata,
                type: 'POST',
                dataType: 'json',
                success: function(json) {
                    console.log(json.success);

                },
                error: function(json, xhr, status) {
                    console.log(json.error);
                },
                complete: function(json, xhr, status) {
                    //console.log(json.responseText);
                    location.reload();
                    $("#apertura_gym").html("Entrando");
                }


            });
        });
        $("#apertura").click(function() {


            $("#apertura").html("Abriendo...").delay(1000);
            var formdata = {
                lat: $("#latitud").val(),
                lng: $("#longitud").val(),

            };

            console.log(formdata);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },

                url: '/{{ request()->slug_instalacion }}/apertura-torno',
                data: formdata,
                type: 'POST',
                dataType: 'json',
                success: function(json) {
                    console.log(json.success);

                },
                error: function(json, xhr, status) {
                    console.log(json.error);
                },
                complete: function(json, xhr, status) {
                    //console.log(json.responseText);
                    location.reload();
                    $("#apertura").html("Entrando");
                }


            });
        });

        $("#salida").click(function() {


            $("#salida").html("Abriendo...").delay(1000);
            var formdata = {
                lat: $("#latitud").val(),
                lng: $("#longitud").val(),

            };

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },

                url: '/{{ request()->slug_instalacion }}/salida-torno',
                data: formdata,
                type: 'POST',
                dataType: 'json',
                success: function(json) {
                    console.log(json.success);

                },
                error: function(json, xhr, status) {
                    console.log(json.error);
                },
                complete: function(json, xhr, status) {

                    console.log('completa');
                    $("#salida").html("Saliendo");
                    location.reload();
                }


            });
        });
    </script>
@endsection
