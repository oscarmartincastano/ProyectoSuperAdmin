@extends('layouts.admin')

@section('style')
    <style>
        .btn-dia {
            border: 1px solid #e5e5e5;
        }

        .btn-dia .numero {
            padding: 10px;
            line-height: 30px;
            font-size: 14px;
            border-radius: 40px;
        }

        .btn-dia .numero.hoy{
            border: 2px solid #6dc3ee !important;
        }
        .btn-dia:hover .numero.hoy{
            background: #6dc3ee !important;
            color: white;
        }

        .btn-dia .numero.fecha-anterior{
            border: none !important;
            color: rgb(92, 92, 92);
        }

        .btn-dia:not(.active) .numero.reservas-activas{
            border: 2px solid #f15934;
        }
        .btn-dia:not(.active):hover .numero.reservas-activas{
            background: #f15934;
            color: white;
        }


        .col.text-center {
            padding: 0;
        }

        .dia {
            border: 1px solid #015e8c;
            font-size: 14px;
            background: #015e8c;
            color: white;
        }

        .mes {
            border: 1px solid #0073AA;
            font-size: 14px;
            background: #0073AA;
            color: white;
        }

        .btn-dia:hover {
            background: #d9f2ff;
        }

        .btn-dia:hover span.numero {
            color: rgb(122, 122, 122);
        }

        .btn-dia.active {
            background-color: #ddd;
            border-color: #ddd;
        }

        .btn-dia.active span {
            background-color: #fff;
            border-color: #fff;
            color: black;
        }

        .selector-pistas {
            display: flex;
            align-content: center;
            align-items: center;
            gap: 12px;
        }

        .selector-pistas i {
            font-size: 18px;
        }

        .next-prev-week a {
            padding: 12px 18px !important;
        }

        .reservas-dia {
            padding: 2%;
            background: #ddd;
            min-height: 180px;
        }

        .reservas-dia>div {
            padding: 30px 35px 10px;
            background: white;
        }

        .nav-tabs.nav-tabs-fillup {
            border-bottom: 1px solid #007be8;
        }
        .tab-content h4{
            font-weight: 300;
        }
        .tab-content h4 span, strong{
            font-weight: bold;
        }

        .far.fa-clock{
            padding: 1px;
        }
        table.table-timeslots{
            height: 1rem;
        }
        .reservas-dia{
            display: none;
        }
        .table-timeslots td{
            border-bottom: 1px solid rgba(184, 184, 184, 0.7);
            vertical-align: top !important;
        }
        .reserva-card{
            border: 1px solid rgba(184, 184, 184, 0.692);
            border-radius: 5px;
            margin-bottom: 20px;
            padding: 15px;
            background: white;
            position: relative;
        }
        .reserva-card h4{
            font-size: 17px;
            line-height: 17px;
            margin-top: 0;
            margin-bottom: 0;
        }
        .reserva-card i{
            margin-right: 0.5rem;
        }
        .text-active{
            color: #008000;
        }
        .text-canceled{
            color: #ea2c54;
        }
        .text-pasado{
            color: #47525e;
        }
        .timeslot-time{
            width: 25%;
            border-right: 2px solid rgba(220, 222, 224, 0.7);
            font-weight: bold;
        }
        .nav-tabs li a span{
            display: inline !important;
        }
        .nav-tabs li a.active span.num-reservas{
            margin-left: 11px;
            padding: 5px 9px;
            background: white;
            border-radius: 50%;
            color: #353f4d;
        }
        .nav-tabs li a span.num-reservas{
            margin-left: 11px;
            padding: 5px 9px;
            background: #007be8;
            border-radius: 50%;
            color: white;
        }
        .help{
            font-size: 12px;
            color: rgba(6, 18, 35, 0.67);
            letter-spacing: normal;
            line-height: 18px;
            display: block;
            margin-top: 6px;
            margin-left: 3px;
        }
        body > div.page-container > div.page-content-wrapper > div.content.sm-gutter > div{
            padding: 0 !important;
        }
        .inline-block{
            display: inline-block;
        }
        tr.desactivado td{
            background: #ddd !important;
        }
        .mostrar-fecha{
            display: inline-flex;
            padding: 11px 18px !important;
            border: 1px solid rgba(6, 18, 35, 0.17);
        }
        .exmp-wrp {
            background:#0073AA;
            position: relative;
            display: inline-block;
        }
        #week{
            background:#0073AA
            url("data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' width='20' height='22' viewBox='0 0 20 22'><g fill='none' fill-rule='evenodd' stroke='%23fff' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' transform='translate(1 1)'><rect width='18' height='18' y='2' rx='2'/><path d='M13 0L13 4M5 0L5 4M0 8L18 8'/></g></svg>")
            right 1rem
            center
            no-repeat;
            cursor: pointer;
        }
        #month{
            background:#0073AA
            url("data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' width='20' height='22' viewBox='0 0 20 22'><g fill='none' fill-rule='evenodd' stroke='%23fff' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' transform='translate(1 1)'><rect width='18' height='18' y='2' rx='2'/><path d='M13 0L13 4M5 0L5 4M0 8L18 8'/></g></svg>")
            right 1rem
            center
            no-repeat;
            cursor: pointer;
        }
        .btn-wrp {
            width: 181px;
            border: 1px solid #dadada;
            display: inline-block;
            position: relative;
            z-index: 10;
        }

        .btn-clck {
            border: none;
            background: transparent;
            width: 100%;
            padding: 10px;
        }

        .btn-clck::-webkit-calendar-picker-indicator {
            right: -10px;
            position: absolute;
            width: 78px;
            height: 40px;
            color: rgba(204, 204, 204, 0);
            opacity: 0
        }

        .btn-clck::-webkit-inner-spin-button {
            -webkit-appearance: none;
        }
        .btn-clck::-webkit-clear-button {
            margin-right:75px;
        }
        .btn-week {
            height: 96%;
            background: #0073AA;
            border: none;
            color: #fff;
            padding: 13px;
            position: absolute;
            left: 3px;
            top: 1px;
            z-index: 11;
            width: 140px;
            cursor: auto !important;
        }

        .loader-bg, .loader-bg-pista {
            width: 100%;
            height: 100%;
            background: #ffffff;
            position: unset;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999999999;
            display: none;
        }
        .loader-bg svg, .loader-bg-pista svg{
            width: 10vw;
            opacity: .7;
        }

        @media (max-width: 700px) {
            .dias .col.text-center{
                padding: 0;
                font-size: 13px;
            }
            .btn-dia {
                padding: 1rem !important;
            }
            .reservas-dia>div{
                padding: 0;
            }
            .table-timeslots tr {
                display: block;
            }
            .table-timeslots tr>td:first-child{
                border-top: 2px solid rgba(220, 222, 224, 0.7);
                border-right: 2px solid rgba(220, 222, 224, 0.7);
                border-left: 2px solid rgba(220, 222, 224, 0.7);
                border-bottom: 0 !important;
            }
            .table-timeslots tr>td:not(:first-child){
                border-right: 2px solid rgba(220, 222, 224, 0.7);
                border-left: 2px solid rgba(220, 222, 224, 0.7);
            }
            .table-timeslots td{
                display: block;
                width: 100% !important;
                border-right: 0;
            }
            .table-timeslots td.timeslot-time{
                text-align: center;
            }
            .reserva-card>div{
                flex-direction: column;
            }
            .reserva-card>div>h4{
                margin-bottom: 12px;
            }
            span.num-reservas{
                margin-left: 11px;
                padding: 5px 7px;
                background: #007be8;
                border-radius: 50%;
                color: white;
                text-align: center;
            }
        }
    </style>
@endsection

@section('content')
<div class="modal fade slide-up disable-scroll" id="modalSlideUp" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content-wrapper">
        <div class="modal-content">
          <div class="modal-header clearfix text-left">
            <button aria-label="" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-icon">close</i>
            </button>
            <h5>Reserva <span></span></h5>
            <p class="p-b-10 user"><strong>Usuario: </strong><span></span></p>
          </div>
          <div class="modal-body">
            <form role="form" method="POST" action="#">
                @csrf
                <div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                @if(request()->slug_instalacion != "vvadecordoba")
                                    <input type="hidden" name="estado" id="estado-reserva" value="">
                                    <label class="mb-1">Motivo de cancelación<span></span></label>
                                @else
                                    <label class="mb-1">Observaciones<span></span></label>
                                @endif
                                <textarea name="observaciones" rows="5" class="form-control"></textarea>
                            </div>
                            <span class="help">Puede quedarse vacío si no tiene.</span>
                        </div>
                    </div>
                </div>
                <div class="text-right mt-4">
                    <input type="hidden" name="accion">
                    <button type="submit" data-accion="active" class="submit-form-validar btn btn-primary m-t-5 mr-2 float-left">Añadir observaciones</button>
                    <button type="submit" data-accion="canceled" class="submit-form-validar btn btn-danger m-t-5 mr-2">Cancelarla</button>
                    @if(request()->slug_instalacion != "vvadecordoba")
                        <button type="submit" data-accion="active" class="submit-form-validar btn btn-success m-t-5 mr-2" style="display:none">Pagada</button>
                    @else
                        <button type="submit" data-accion="active" class="submit-form-validar btn btn-success m-t-5 mr-2">Pagada</button>
                    @endif
                        {{-- <button type="submit" data-accion="desierta" class="submit-form-validar btn btn-warning m-t-5">Desierta</button> --}}
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
  </div>
  <div class="modal fade slide-up disable-scroll" id="modalCancelarReserva" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content-wrapper">
        <div class="modal-content">
          <div class="modal-header clearfix text-left">
            <button aria-label="" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-icon">close</i>
            </button>
            <h5>Cambiar reserva <span></span></h5>
            <p class="p-b-10 user"><strong>Usuario: </strong><span></span></p>
          </div>
          <div class="modal-body">
            <form role="form" method="POST" action="#">
                @csrf
                <div>
                    <div class="row">
                        <div class="col-md-12">
                            <label class="mb-1">Puedes pasar esta reserva a otra fecha: <span></span></label>
                            <div class="form-group">
                                <input type="date" name="date" id="date" class="form-control">
                            </div>
                            <div class="form-group">
                                <input type="time" name="hora" id="hora" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="mb-1">Puedes cambiar la pista de esta reserva: <span></span></label>
                            <div class="form-group">
                                <select name="pista" id="pista" class="form-control">
                                    @foreach ($pistas as $pista)
                                        {{-- seleccionar por defecto la pista seleccionada  --}}
                                        <option value="{{ $pista->id }}">{{ $pista->nombre }}</option>
                                                                           
                                    @endforeach
                                    <option value="">Selecciona una pista</option>
                                   
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12 mt-2">
                            <input type="submit" value="Cambiar" class="btn btn-success">
                        </div>
                    </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <div class="row m-0">
        <div class="col-lg-12 m-b-10">

            <div class="p-l-20 p-r-20 p-b-10 pt-3">
                <div>
                    <h3 data-slug="{{ auth()->user()->instalacion->slug }}" class="text-primary no-margin inst-name">{{ auth()->user()->instalacion->nombre }}</h3>
                    @if (request()->slug_instalacion == "la-guijarrosa")
                            <div id="apertura-puerta" >
                                    <div class="mt-2">
                                        @csrf
                                        <button type="button" class="btn btn-success" id="apertura">Abrir portillo</button>
                                        @csrf
                                        <button type="button" class="btn btn-success" id="apertura-torno">Abrir torno</button>
                                        <button type="button" class="btn btn-success" id="salida">Salida torno</button>
                                        <button type="button" class="btn btn-success" id="apertura_gym">Abrir puerta gimnasio</button>
                                    </div>
                            </div>

                            <input class="hidden"  type="text"  id="longitud">
                            <input class="hidden"  type="text"  id="latitud">
                        @endif
                </div>
            </div>

            <div class="p-t-15 p-b-15 p-l-20 p-r-20">
                <div class="card ">
                    <div class="card-body">
                        <div class="row d-flex justify-content-between mb-2">
                            <div class="next-prev-week">
                                <div>{{-- {{ dd(request()->semana) }} --}}
                                    @if ($instalacion->tipo_reservas_id == 1)
                                        <a href="/{{ request()->slug_instalacion }}/admin/reservas?{{ request()->week ? 'week=' . request()->week . '&&' : '' }}semana={{ request()->semana == null || request()->semana == 0 ? '-1' : request()->semana-1 }}" class="btn btn-prev"><</a>
                                        <div class="exmp-wrp">
                                            <button class="btn-week">{!! date('W') == \Carbon\Carbon::parse(iterator_to_array($period)[0])->format('W') ? 'Semana actual' :  \Carbon\Carbon::parse(iterator_to_array($period)[0])->formatLocalized('%d %b') . ' - ' . \Carbon\Carbon::parse(iterator_to_array($period)[count(iterator_to_array($period))-1])->formatLocalized('%d %b') !!}</button>
                                            <div class="btn-wrp">
                                                <form action="#" method="get">
                                                    <input type="week" name="week" id="week" value="{{ \Carbon\Carbon::parse(iterator_to_array($period)[0])->format('Y').'-W'.\Carbon\Carbon::parse(iterator_to_array($period)[0])->format('W') }}" class="btn-clck">
                                                </form>
                                            </div>
                                        </div>
                                        {{-- <a href="#" class="btn btn-select-week">{!! request()->semana == null || request()->semana == 0 ? 'Semana actual' :  \Carbon\Carbon::parse(iterator_to_array($period)[0])->formatLocalized('%d %b') . ' - ' . \Carbon\Carbon::parse(iterator_to_array($period)[count(iterator_to_array($period))-1])->formatLocalized('%d %b') !!} </a> --}}
                                        <a href="/{{ request()->slug_instalacion }}/admin/reservas?{{ request()->week ? 'week=' . request()->week . '&&' : '' }}semana={{ request()->semana == null || request()->semana == 0 ? '1' : request()->semana+1 }}" class="btn btn-next">></a>
                                    @else
                                        <a href="/{{ request()->slug_instalacion }}/admin/reservas?{{ request()->month ? 'month=' . request()->month. '&&' : '' }}mes={{ request()->mes == null || request()->mes == 0 ? '-1' : request()->mes-1 }}" class="btn btn-prev"><</a>
                                        <div class="exmp-wrp">
                                            <button class="btn-week">{!! date('Y-m') == $newMonthYear ? 'Mes actual' :  \Carbon\Carbon::createFromFormat('Y-m-d', $newMonthYear."-01")->formatLocalized('%B').' - ' . \Carbon\Carbon::createFromFormat('Y-m', $newMonthYear)->year !!}</button>
                                            <div class="btn-wrp">
                                                <form action="#" method="get">
                                                    <input type="month" name="month" id="month" value="" class="btn-clck">
                                                </form>
                                            </div>
                                        </div>
                                        {{-- <a href="#" class="btn btn-select-week">{!! request()->semana == null || request()->semana == 0 ? 'Semana actual' :  \Carbon\Carbon::parse(iterator_to_array($period)[0])->formatLocalized('%d %b') . ' - ' . \Carbon\Carbon::parse(iterator_to_array($period)[count(iterator_to_array($period))-1])->formatLocalized('%d %b') !!} </a> --}}
                                        <a href="/{{ request()->slug_instalacion }}/admin/reservas?{{ request()->month ? 'month=' . request()->month. '&&' : '' }}mes={{ request()->mes == null || request()->mes == 0 ? '1' : request()->mes+1 }}" class="btn btn-next">></a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @if ($instalacion->tipo_reservas_id == 1)
                            <div class="row">
                                <div class="col text-center text-uppercase p-3 mes">
                                    {{ \Carbon\Carbon::parse($period->start)->formatLocalized('%B') }}
                                    {{ \Carbon\Carbon::parse($period->start)->formatLocalized('%Y') }}</div>
                            </div>
                            <div class="row dias">
                                @foreach ($period as $fecha)
                                    <div class="col text-center">
                                        @php
                                            $diaSemana = ["DOMINGO", "LUNES", "MARTES", "MIÉRCOLES", "JUEVES", "VIERNES", "SÁBADO"];
                                        @endphp
                                        <div class="text-uppercase p-3 dia">
                                            {{ $diaSemana[\Carbon\Carbon::parse($fecha)->dayOfWeek] }} </div>
                                        <div><a data-fecha="{{ $fecha->format('Y-m-d') }}" data-fecha_long="{{  $fecha->format('d/m/Y') }}" @if(auth()->user()->instalacion->check_reservas_dia($fecha->format('Y-m-d'))) data-toggle="tooltip" data-placement="top" title="{{ auth()->user()->instalacion->check_reservas_dia($fecha->format('Y-m-d')) }} Reservas pendientes" @endif href="#" class="btn-dia w-100 h-100 d-block p-5 @if(Session::get('dia_reserva_hecha')) {{ $fecha->format('Y-m-d') == Session::get('dia_reserva_hecha') ? 'active' : '' }} @else {{ $fecha->format('d/m/Y') == date('d/m/Y') ? 'active' :  ''}} @endif"><span
                                            class="numero {{ auth()->user()->instalacion->check_reservas_dia($fecha->format('Y-m-d')) ? 'reservas-activas' : '' }} {{ $fecha->format('Y-m-d')<date('Y-m-d') ? 'fecha-anterior' : ($fecha->format('Y-m-d')==date('Y-m-d') ? 'hoy' : '') }}">{{ $fecha->format('d') }}</span></a></div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="row reservas-dia" style="display: block">
                                <div class="loader-bg" style="display: none">
                                    @include('instalacion.loader.loader')
                                </div>
                                <div class="col">
                                    <div class="pb-3">
                                        <div class="input-group">
                                            <div class="input-group-append ">
                                                <span class="input-group-text" style="border-left: 1px solid #06122324 !important;"><i class="fas fa-search"></i></span>
                                            </div>
                                            <input type="text" class="form-control" id="searcher-reservas" placeholder="Buscar reservas...">
                                        </div>
                                    </div>
                                    <ul class="nav nav-tabs nav-tabs-fillup d-none d-md-flex d-lg-flex d-xl-flex"
                                        data-init-reponsive-tabs="dropdownfx">
                                        @foreach ($pistas as $i => $item)
                                            <li class="nav-item">
                                                <a href="#" data-fecha="{{ date('Y-m-d') }}" data-pista="{{ $item->id }}" id="tab-espacio-{{ $item->id }}" class="{{ $i == 0 ? 'active' : '' }} tab-pista" data-toggle="tab"
                                                    data-target="#espacio-{{ $item->id }}"><span>{{ $item->nombre_corto ?? $item->nombre }}</span><span class=""></span></a>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <div class="loader-bg-pista" style="display: none">
                                        @include('instalacion.loader.loader')
                                    </div>
                                    <div class="tab-content reservas-dia">
                                        @foreach ($pistas as $i => $pista)
                                            <div class="tab-pane {{ $i == 0 ? 'active' : '' }}" id="espacio-{{ $pista->id }}">
                                                <div><h4 class="d-inline-block"><strong>{{ $pista->nombre }}</strong> Reservas para <span class="fecha"></span></h4> <a href="/{{ request()->slug_instalacion }}/admin/reservas/{{ $pista->id }}/desactivar-dia/{{ date('Y-m-d') }}" class="btn btn-outline-primary ml-3 btn-off-dia">DESACTIVAR DÍA COMPLETO</a>  <a href="/{{ request()->slug_instalacion }}/admin/reservas/{{ $pista->id }}/activar-dia/{{ date('Y-m-d') }}" class="btn btn-outline-primary ml-3 btn-on-dia">ACTIVAR DÍA COMPLETO</a></div>
                                                <div id="content-espacio-{{ $pista->id }}">

                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @else
                                @include('instalacion.reservas-meses')
                        @endif
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
        $(document).ready(function() {
            $('.dias').on('click', '.btn-dia', function(e) {
                e.preventDefault();

                $('.loader-bg').show().next().hide();
                $('.reservas-dia').show();

                $('.btn-dia').removeClass('active');
                $(this).addClass('active');
                $('span.fecha').html($(this).data('fecha_long'));
               /*  $('.btn-off-dia').attr('href', `/{{ request()->slug_instalacion }}/admin/reservas/ $pista->id /desactivar-dia/${$(this).data('fecha')}`);
                $('.btn-on-dia').attr('href', `/{{ request()->slug_instalacion }}/admin/reservas/ $pista->id /activar-dia/${$(this).data('fecha')}`); */
                $('.tab-pista').data('fecha', $(this).data('fecha'));
                $(`.nav-item a span:last-child`).removeClass('num-reservas').html(``);
                $('li span.num-reservas').remove();
                $('.nav-item:first-child a').click();
                $.ajax({
                    url: `/{{ request()->slug_instalacion }}/admin/reservas/numero/${$(this).data('fecha')}/`,
                    success: data => {
                        /* console.log(data); */
                        console.log(data);
                        for (const index in data) {

                            $(`#tab-espacio-${index} span:last-child`).addClass('num-reservas').html(`${data[index]}`);
                            $(`div.cs-options li[data-value="#espacio-${index}"]`).find(':nth-child(2)').remove();
                            $(`div.cs-options li[data-value="#espacio-${index}"]`).append(`<span class="num-reservas">${data[index]}</span>`);
                            /* console.log(`#tab-espacio-${index} span:last-child`); */
                        }

                        $('.loader-bg').hide().next().show();
                    },
                    error: data => {
                        console.log(data)
                    }
                });
                /* $.ajax({
                    url: `/${$('.inst-name').data('slug')}/admin/reservas/${$(this).data('fecha')}`,
                    success: data => {
                        data.forEach(pista => {
                            let string = '';
                            string += `<table class="table table-timeslots table-hover">
                                            <tbody>`;
                            pista.res_dia.forEach(item => {
                                item.forEach(intervalo => {
                                    string += `<tr ${intervalo.desactivado ? 'class="desactivado"' : ''}>
                                            <td class="timeslot-time"><div style="margin-bottom:20px;"><i class="far fa-clock"></i> ${intervalo.string}</div>`;

                                    if (intervalo.num_res < pista.reservas_por_tramo) {
                                        if (intervalo.desactivado) {
                                            string += `<div><form class="inline-block" method="POST" action="/{{ auth()->user()->instalacion->slug }}/admin/reservas/${pista.id}/activar/${intervalo.timestamp}${intervalo.desactivado == 2 ? '?periodic=1' : ''}">@csrf <button type="submit" class="btn btn-outline-success">Activar</button> </form> <a href="/{{ auth()->user()->instalacion->slug }}/admin/reservas/${pista.id}/reservar/${intervalo.timestamp}" class="btn btn-primary">Reservar</a></div></td><td class="timeslot-reserve">`;
                                        }else{
                                            string += `<div><form class="inline-block" method="POST" action="/{{ auth()->user()->instalacion->slug }}/admin/reservas/${pista.id}/desactivar/${intervalo.timestamp}">@csrf <button type="submit" class="btn btn-outline-primary">Desactivar</button> </form> <a href="/{{ auth()->user()->instalacion->slug }}/admin/reservas/${pista.id}/reservar/${intervalo.timestamp}" class="btn btn-primary">Reservar</a></div></td><td class="timeslot-reserve">`;
                                        }
                                    } else {
                                        string += `</td><td class="timeslot-reserve">`;
                                    }

                                    if (intervalo.reservas.length > 0) {
                                        intervalo.reservas.forEach(reserva => {
                                            console.log(reserva);
                                            string += `<div class="reserva-card"><div class="d-flex justify-content-between align-items-center">
                                                        <h4><a href="/{{ request()->slug_instalacion }}/admin/users/${reserva.usuario.id}/ver">#${reserva.id} ${reserva.usuario.name}</a> <span class="capitalize text-${reserva.estado}">(${reserva.estado == 'active' ? 'Activo' : (reserva.estado == 'pasado' ? 'Pasado' : 'Cancelado')})</span></h4>`;
                                            if (reserva.estado == 'active') {
                                                string += `<div><a href="#" class="btn btn-primary btn-acciones-reserva" data-intervalo="${reserva.string_intervalo}" data-reserva="${reserva.id}" data-user="${reserva.usuario.name}">Acciones</a></div></div>`;
                                            }else if (reserva.estado == 'canceled') {
                                                string += `<div> <a href="#" class="btn btn-info">(${reserva.formated_updated_at}) Cancelado</a></div></div>`;
                                            }else{
                                                string += `<div> <a href="#" class="btn btn-white">(${reserva.formated_updated_at}) Pasada</a></div></div>`;
                                            }
                                            $(reserva.valores_campos_pers).each(function (index, element) {
                                                string += `<div class="mt-2"><strong><i class="fas fa-plus mr-1"></i> ${element.campo.label}: </strong>${element.valor}</div>`;
                                            });
                                            if (reserva.observaciones) {
                                                string += `<div class="mt-2"><strong><i class="far fa-comment-dots mr-1"></i> Observaciones reserva: </strong>${reserva.observaciones}</div>`;
                                            }
                                            if (reserva.reserva_periodica) {
                                                string += `<div class="mt-2"><strong><i class="fas fa-user-shield mr-1"></i> Reserva periódica</strong></div>`;
                                            }
                                            if (reserva.creado_por == 'admin') {
                                                string += `<div class="mt-2"><strong><i class="fas fa-user-shield mr-1"></i> Observaciones admin: </strong>creada por admin`;
                                            }
                                            if (reserva.observaciones_admin) {
                                                string += `, ${reserva.observaciones_admin}`;
                                            }
                                            string += `</div></div>`;
                                        });
                                    }else{
                                        string += `No Reservado`;
                                    }
                                    string += `</td></tr>`;
                                });
                            });
                            string += `</tbody></table></div>`;
                            $(`#content-espacio-${pista.id}`).html(`${string}`);
                            if (pista.num_reservas_dia > 0) {
                                $(`#tab-espacio-${pista.id} span:last-child`).addClass('num-reservas').html(`${pista.num_reservas_dia}`);
                            } else{
                                $(`#tab-espacio-${pista.id} span:last-child`).removeClass('num-reservas').html(``);
                            }
                        });

                        $('.loader-bg').hide().next().show();
                    },
                    error: data => {
                        console.log(data)
                    }
                }); */
            });
            $('.nav-tabs').on('click', '.tab-pista', function(e) {
                e.preventDefault();
                $('.loader-bg-pista').show().next().hide();
                $('.btn-off-dia').attr('href', `/{{ request()->slug_instalacion }}/admin/reservas/${$(this).data('pista')}/desactivar-dia/${$(this).data('fecha')}`);
                $('.btn-on-dia').attr('href', `/{{ request()->slug_instalacion }}/admin/reservas/${$(this).data('pista')}/activar-dia/${$(this).data('fecha')}`);
                $.ajax({
                    url: `/{{ request()->slug_instalacion }}/admin/reservas/${$(this).data('fecha')}/${$(this).data('pista')}`,
                    success: data => {
                        let string = '';
                        console.log('Antes');
                        console.log(data.res_dia);
                        if (typeof data.res_dia === 'object') {
                            data.res_dia = Object.values(data.res_dia);
                        }
                        console.log('Después');
                        console.log(data.res_dia);


                        string += `<table class="table table-timeslots table-hover">
                                        <tbody>`;
                        data.res_dia.forEach(item => {
                            item.forEach(intervalo => {
                                string += `<tr ${intervalo.desactivado ? 'class="desactivado"' : ''}>
                                        <td class="timeslot-time"><div style="margin-bottom:20px;"><i class="far fa-clock"></i> ${intervalo.string == '22:30 - 23:59' ? '22:30 - 02:30' : intervalo.string}${intervalo.num_res ? "<br>(Total: " + intervalo.num_res + ' res.)' : ''}</div>`;

                                if (intervalo.num_res < data.reservas_por_tramo) {
                                    if (intervalo.desactivado) {
                                        string += `<div><form class="inline-block" method="POST" action="/{{ request()->slug_instalacion }}/admin/reservas/${data.id}/activar/${intervalo.timestamp}${intervalo.desactivado == 2 ? '?periodic=1' : ''}">@csrf <button type="submit" class="btn btn-outline-success">Activar</button> </form> <a href="/{{ request()->slug_instalacion }}/admin/reservas/${data.id}/reservar/${intervalo.timestamp}" class="btn btn-primary" style="padding: 0 14px;">Reservar</a></div></td><td class="timeslot-reserve">`;
                                    }else{
                                        string += `<div><form style="margin-bottom:7px" class="inline-block" method="POST" action="/{{ request()->slug_instalacion }}/admin/reservas/${data.id}/desactivar/${intervalo.timestamp}">@csrf <button type="submit" class="btn btn-outline-primary">Desactivar</button> </form> <a href="/{{ request()->slug_instalacion }}/admin/reservas/${data.id}/reservar/${intervalo.timestamp}" class="btn btn-primary" style="padding: 0 14px;">Reservar</a></div></td><td class="timeslot-reserve">`;
                                    }
                                } else {
                                    string += `</td><td class="timeslot-reserve">`;
                                }

                                if (intervalo.reservas.length > 0) {
                                    intervalo.reservas.forEach(reserva => {
                                        console.log(reserva);
                                        string += `<div class="reserva-card"><div class="d-flex justify-content-between align-items-center">
                                                    <h4><a href="/{{ request()->slug_instalacion }}/admin/users/${reserva.usuario.id}/ver">#${reserva.id} ${reserva.reserva_multiple ? ' - #' + (+reserva.id + +reserva.numero_reservas-1) : ''} ${reserva.usuario.name} ${reserva.reserva_multiple ? '(' + reserva.numero_reservas + ' reservas)' : ''}</a></h4>`;
                                        if (reserva.estado == 'active') {
                                            /* <a href="#" class="btn btn-primary btn-acciones-reserva" data-intervalo="${reserva.string_intervalo}" data-reserva="${reserva.id}" data-reserva_string="${reserva.reserva_multiple ? reserva.id + ' - #' + (+reserva.id + +reserva.numero_reservas) : reserva.id}" data-user="${reserva.usuario.name}">Acciones</a> */
                                           /* string += `<div><h4 class="text-success" style="font-weight:bold; margin-bottom:5px;">Pagado</h4><br><h4 style="margin-top:-18px">${reserva.creado_por == 'admin' ? 'Efectivo' : 'Tarjeta'}: ${reserva.pedido ? reserva.pedido.amount : ''} €</h4></div></div>`;
                                            */ /* if(reserva.user.id_instalacion !=1){ */
                                                string += `<div><h4 class="text-success" style="font-weight:bold; margin-bottom:5px;">Pagado</h4><br><h4 style="margin-top:-18px;margin-right:30px;   ">${reserva.creado_por == 'admin' ? 'Efectivo' : 'Tarjeta'}: ${reserva.pedido ? reserva.pedido.amount : ''} €</h4></div></div><a style="position:absolute;right:0px;margin-top:20px;" href="#" class="btn btn-warning btn-acciones-reserva" data-estado="${reserva.estado}" data-observ="${reserva.observaciones_admin}"  data-intervalo="${reserva.string_intervalo}" data-reserva="${reserva.id}" data-reserva_string="${reserva.reserva_multiple ? reserva.id + ' - #' + (+reserva.id + +reserva.numero_reservas-1) : reserva.id}" data-user="${reserva.usuario.name}">Cambiar estado</a>`;
                                                $(`#estado-reserva`).val(`${reserva.estado}`);
                                           /*  }else{
                                                string += `<div><h4 class="text-success" style="font-weight:bold; margin-bottom:5px;">Pagado</h4><br><h4 style="margin-top:-18px">${reserva.creado_por == 'admin' ? 'Efectivo' : 'Tarjeta'}: ${reserva.pedido ? reserva.pedido.amount : ''} €</h4></div></div>`;
                                            } */
                                        }else if (reserva.estado == 'canceled') {
                                            string += `<h4 class="text-danger" style="font-weight:bold">Cancelado</h4></div>`;
                                        }else if (reserva.estado == 'pendiente') {
                                            string += `<h4 class="text-warning" style="font-weight:bold">Pendiente pago</h4></div><a style="position:absolute;right:35px;margin-top:10px" href="#" class="btn btn-warning btn-acciones-reserva" data-observ="${reserva.observaciones_admin}" data-estado="${reserva.estado}" data-intervalo="${reserva.string_intervalo}" data-reserva="${reserva.id}" data-reserva_string="${reserva.reserva_multiple ? reserva.id + ' - #' + (+reserva.id + +reserva.numero_reservas-1) : reserva.id}" data-user="${reserva.usuario.name}">Cambiar estado</a>`;
                                        }else if (reserva.estado == 'desierta') {
                                            string += `<h4 class="text-warning" style="font-weight:bold">Desierta</h4></div>`;
                                        }else{
                                            string += `<h4 style="color:#28a745;font-weight:bold">Validada</h4></div>`;
                                        }
                                        if (reserva.estado != 'canceled') {
                                            string += `<a style="position:absolute;right:0;bottom:0;padding-right:3px" href="#" data-toggle="tooltip" data-placement="top" title="Cambiar de fecha" class="btn btn-light btn-cancelar-reserva" data-piscina="1" data-fecha="${reserva.fecha}" data-hora="${reserva.string_intervalo}" data-intervalo="${reserva.string_intervalo}" data-reserva="${reserva.id}" data-reserva_string="${reserva.reserva_multiple ? reserva.id + ' - #' + (+reserva.id + +reserva.numero_reservas-1) : reserva.id}" data-user="${reserva.usuario.name}" data-pista="${reserva.id_pista}"><i class="fa-solid fa-calendar-days"></i></a>`;
                                        }
                                        if (reserva.id_pista == 10 && reserva.estado != 'pendiente' && reserva.estado != 'canceled') {
                                            string += `<a style="position:absolute;right:70px;bottom: 0px;" href="#" class="btn btn-primary btn-acciones-reserva" data-piscina="1" data-observ="${reserva.observaciones_admin}" data-intervalo="${reserva.string_intervalo}" data-reserva="${reserva.id}" data-reserva_string="${reserva.reserva_multiple ? reserva.id + ' - #' + (+reserva.id + +reserva.numero_reservas-1) : reserva.id}" data-user="${reserva.usuario.name}"><i class="far fa-comment-dots mr-0"></i></a>`;
                                        }
                                        if (reserva.id_pista == 10 && reserva.estado != 'canceled') {
                                            string += `<a href="/{{ request()->slug_instalacion }}/admin/reservas/cancelar-reserva/${reserva.id}" onclick="return confirm('¿Estás seguro que quieres cancelar la reserva #${reserva.reserva_multiple ? reserva.id + ' - #' + (+reserva.id + +reserva.numero_reservas-1) : reserva.id}? Se cancelará la reserva y el pedido.')" style="position:absolute;right:36px;bottom:0" href="#" data-toggle="tooltip" data-placement="top" title="Cancelar la reserva" class="btn btn-danger btn-borrar-reserva" data-piscina="1" data-observ="${reserva.observaciones_admin}" data-intervalo="${reserva.string_intervalo}" data-reserva="${reserva.id}" data-reserva_string="${reserva.reserva_multiple ? reserva.id + ' - #' + (+reserva.id + +reserva.numero_reservas-1) : reserva.id}" data-user="${reserva.usuario.name}"><i class="fa-solid fa-trash mr-0"></i></a>`;
                                        }
                                        $(reserva.valores_campos_pers).each(function (index, element) {

                                            string += `<div class="mt-5"><strong><i class="fas fa-plus mr-1"></i> ${element.campo.label}: </strong>${element.valor}</div>`;
                                        });
                                        if (reserva.observaciones) {
                                            string += `<div class="mt-2"><strong><i class="far fa-comment-dots mr-1"></i> Observaciones reserva: </strong>${reserva.observaciones}</div>`;
                                        }
                                        if (reserva.reserva_periodica) {
                                            string += `<div class="mt-2"><strong><i class="fas fa-user-shield mr-1"></i> Reserva periódica</strong></div>`;
                                        }
                                        /* if (reserva.reserva_multiple) {
                                            string += `<div class="mt-2"><strong><i class="fa-solid fa-calendar-plus"></i> Reserva múltiple: </strong>${reserva.numero_reservas} reservas</div>`;
                                        } */
                                        if (reserva.observaciones_admin) {
                                            if(reserva.user.id_instalacion !=1){

                                              string += `<div class="mt-2" style="display:none;"><strong><i class="far fa-comment-dots mr-1"></i>Observaciones: </strong> ${reserva.observaciones_admin}</div>`;
                                            }else{

                                                string += `<div class="mt-2" style="display:block;"><strong><i class="far fa-comment-dots mr-1"></i>Observaciones: </strong> ${reserva.observaciones_admin}</div>`;
                                            }
                                        }
                                        if (reserva.creado_por == 'admin') {
                                            string += `<div class="mt-2"><strong><i class="fas fa-user-shield mr-1"></i> Creada por ADMIN</strong></div>`;
                                        }
                                        string += `</div>`;
                                    });
                                }else{
                                    string += `No Reservado`;
                                }
                                string += `</td></tr>`;
                            });
                        });
                        string += `</tbody></table></div>`;
                        $(`#content-espacio-${data.id}`).html(`${string}`);
                        /* if (data.num_reservas_dia > 0) {
                            $(`#tab-espacio-${data.id} span:last-child`).addClass('num-reservas').html(`${data.num_reservas_dia}`);
                        } else{
                            $(`#tab-espacio-${data.id} span:last-child`).removeClass('num-reservas').html(``);
                        } */


                        $('.loader-bg-pista').hide().next().show();
                        $("[data-toggle=tooltip").tooltip();
                    },
                    error: data => {
                        console.log(data)
                    }
                });
            });

            $('body').on('change', 'select.cs-select.cs-skin-slide.full-width', function(e) {
                e.preventDefault();
                $(`[data-target="${$(this).val()}"]`).click();
            });

            $('.btn-dia.active').click();
            $('.reservas-dia').on('click', '.btn-acciones-reserva', function (e) {
                e.preventDefault();
                let modal = $('#modalSlideUp');
                modal.modal('show').find('h5 span').html(`#${$(this).data('reserva_string')}: ${$(this).data('intervalo')}`);
                modal.find('.user span').html($(this).data('user'));
      /*           if($('#estado-reserva').val() == 'active'){
                    $('.boton-pagada').css('display','none');
                }else{
                    $('.boton-pagada').css('display','block');
                } */
                if ($(this).data('piscina')) {
                    modal.find('.btn-update-observaciones').hide();
                    modal.find('textarea').html($(this).data('observ')).focus();
                    modal.find('.submit-form-validar.btn-success').html('Añadir').prev().hide();
                } else {
                    modal.find('.btn-update-observaciones').show();
                    modal.find('textarea').html($(this).data('observ'));
                    modal.find('.submit-form-validar.btn-success').html('Pagada').prev().show();
                }
                modal.find('form').attr('action', `/{{ request()->slug_instalacion }}/admin/reservas/validar/${$(this).data('reserva')}`);

            });
            $('#modalSlideUp').on('click', '.submit-form-validar', function (e) {
                e.preventDefault();
                $(this).parent().find('input').val($(this).data('accion'));
                $('#modalSlideUp').find('form').submit();
            });

            $('input#week').change(function (e) {
                /* console.log(value); */
                $(this).parent().submit();
            });

            $('input#month').change(function (e) {
                /* console.log(value); */
                $(this).parent().submit();
            });

            $('.reservas-dia').on('keyup', '#searcher-reservas', function (e) {
                let filter = $(this).val().toUpperCase();
                $('table.table-timeslots .timeslot-reserve .reserva-card').filter(function () {
                    $(this).toggle($(this).text().toUpperCase().indexOf(filter) > -1)
                 });
            });

            $('.reservas-dia').on('click', '.btn-cancelar-reserva', function (e) {
                    e.preventDefault();
                    let modal = $('#modalCancelarReserva');
                    modal.modal('show').find('h5 span').html(`#${$(this).data('reserva_string')}: ${$(this).data('intervalo')}`);
                    modal.find('.user span').html($(this).data('user'));
                    modal.find('input#date').val($(this).data('fecha'));
                    modal.find('input#hora').val($(this).data('hora').substring(0, 5)); 
                    console.log(modal.find('input#date'));
                 

                    let fecha = $(this).data('fecha'); // Fecha seleccionada
                    let hora = $(this).data('hora'); // Hora seleccionada
                    let url = `/{{ request()->slug_instalacion }}/admin/reservas/pistas-disponibles?fecha=${fecha}&hora=${hora}`;
                    let reservaPista = $(this).data('pista');
                    console.log(reservaPista);
                    

                    $.ajax({
                        url: url,
                        method: 'GET',
                        success: function (data) {
                            let options = '<option value="">Selecciona una pista</option>';
                            console.log($(this).data('pista'));
                            data.forEach(pista => {
                                // si la pista es la misma que la de la reserva, la seleccionamos por defecto
                                if (pista.id == reservaPista) {
                                    options += `<option value="${pista.id}" selected>${pista.nombre}</option>`;
                                } else {
                                    options += `<option value="${pista.id}">${pista.nombre}</option>`;
                                }
                            });
                            modal.find('select#pista').html(options);
                        },
                        error: function () {
                            modal.find('select#pista').html('<option value="">Error al cargar pistas</option>');
                        }

                    });

                    modal.find('form').attr('action', `/{{ request()->slug_instalacion }}/admin/reservas/reschedule/${$(this).data('reserva')}`);
            });
        });
    </script>
    @if (!Session::get('dia_reserva_hecha'))
        @if ((isset(request()->semana) && request()->semana != 0) || isset(request()->week) && date('W') != \Carbon\Carbon::parse(iterator_to_array($period)[0])->format('W'))
            <script>
                $(document).ready(function () {
                    $('.btn-dia')[0].click();
                });
            </script>
        @endif
    @endif
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCT5hS4KbxtQbH2VQfDb7KWFK-BN7vVyeA"></script>


<script type="text/javascript">



    const watchId = navigator.geolocation.watchPosition(position => initMap(position),
    );





    function error() {
        alert('error, active su ubicación para abrir la puerta');
    }

    function stopWatch() {
        navigator.geolocation.clearWatch(watchId);
    }

    function initMap(position) {
        const { latitude, longitude } = position.coords;

        const center = {lat: latitude, lng: longitude};
        const options = {zoom: 15, scaleControl: true, center: center};
        // map = new google.maps.Map(document.getElementById('map'), options);

        const point = {lat: latitude, lng: longitude};

        // create marker
        // var marker = new google.maps.Marker({position: point, map: map});

        navigator.geolocation.clearWatch(watchId);

        const latitud=point.lat;

        $("#latitud").val(latitud);

        const longitud=point.lng;
        $("#longitud").val(longitud);

    }



</script>

<script>

    $("#apertura_gym").click(function() {

        $("#apertura_gym").html("Abriendo...").delay(1000);
        var formdata= {
            lat: $("#latitud").val(),
            lng: $("#longitud").val(),

        };

        console.log(formdata);

        $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },

            url : '/{{request()->slug_instalacion}}/apertura-gym',
            data :formdata,
            type : 'POST',
            dataType : 'json',
            success : function(json) {
                console.log(json.success);

            },
            error : function(json , xhr, status) {
                console.log(json.responseText);
            },
            complete : function(json , xhr, status) {
                location.reload();

                console.log('completa');
                $("#apertura_gym").html("Abrir portillo");
            }


        });
    });
    $("#apertura").click(function() {


        $("#apertura").html("Abriendo...").delay(1000);
        var formdata= {
            lat: $("#latitud").val(),
            lng: $("#longitud").val(),

        };

        console.log(formdata);

        $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },

            url : '/{{request()->slug_instalacion}}/aperturaadmin',
            data :formdata,
            type : 'POST',
            dataType : 'json',
            success : function(json) {
                console.log(json.success);

            },
            error : function(json , xhr, status) {
                console.log(json.responseText);
            },
            complete : function(json , xhr, status) {
                location.reload();

                console.log('completa');
                $("#apertura").html("Abrir portillo");
            }


        });
    });

    $("#apertura-torno").click(function() {


        $("#apertura").html("Abriendo...").delay(1000);
        var formdata= {
            lat: $("#latitud").val(),
            lng: $("#longitud").val(),

        };

        console.log(formdata);

        $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },

            url : '/{{request()->slug_instalacion}}/apertura-torno',
            data :formdata,
            type : 'POST',
            dataType : 'json',
            success : function(json) {
                console.log(json.success);

            },
            error : function(json , xhr, status) {
                console.log(json.error);
            },
            complete : function(json , xhr, status) {
                location.reload();

                console.log('completa');
                $("#apertura").html("Entrando");
            }


        });
        });

        $("#salida").click(function() {


            $("#salida").html("Abriendo...").delay(1000);
            var formdata= {
                lat: $("#latitud").val(),
                lng: $("#longitud").val(),

            };

            $.ajax({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },

                url : '/{{request()->slug_instalacion}}/salida-torno',
                data :formdata,
                type : 'POST',
                dataType : 'json',
                success : function(json) {
                    console.log(json.success);

                },
                error : function(json , xhr, status) {
                    console.log(json.error);
                },
                complete : function(json , xhr, status) {
                    location.reload();

                    console.log('completa');
                    $("#salida").html("Saliendo");
                }


            });
        });


</script>
@endsection
