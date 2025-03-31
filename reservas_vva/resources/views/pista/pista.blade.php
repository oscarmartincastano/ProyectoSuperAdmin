@extends('layouts.userview')

@section('pagename', $pista_selected->tipo)

@section('style')
    <style>
        #select2-pista-select2-container{
            text-align: center;
            color: white;
            font-size: 20px;
        }
        .select2-container--default .select2-selection--single{
            background-color: #373d43;
        }
        #select2-pista-select2-results{
            color: white;
            background-color: #373d43;
        }
        body > span > span > span.select2-search.select2-search--dropdown{
            background-color: #373d43;
        }
        body > main > div.container.is-max-desktop > div > div > div > div.pistas > span > span.selection > span {
            height: auto;
            padding: 12px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow{
            height: 26px;
            position: absolute;
            top: 13px;
            right: 11px;
            width: 20px;
        }
        .select2-container--default .select2-results__option--selected{
            color: #373d43;
        }
        .datepicker.date-input {
            color: white;
            background: #6c757d;
        }
        #form-dia > div > a {
            border-right: 1px solid white;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow b{
            border-color: #fff transparent transparent transparent !important;
            color: white !important;
        }
        .picker__box{
            padding-top: 18px;
        }

    </style>
    @if (count($pistas) > 1)
        <style>
            .pistas {
                padding-top: 50px;
            }
        </style>
    @endif
    @if (count($pistas) > 4)
        <style>
            .seleccionar-pista-label{
                top: 60px !important;
            }
        </style>
    @endif
    @foreach ($valid_period as $fecha)
        <style>
            div[aria-label="{!! $fecha->format('d/m/Y') !!}"] {
                border: 1px solid #0089ec;
            }
        </style>
    @endforeach
@endsection

@section('content')
    <div id="url_instalacion" style="display: none">/{{ request()->slug_instalacion }}/{{ request()->deporte }}/</div>
    <section class="hero is-medium">
        <div class="has-text-centered title-div title-pista-section" style="background:linear-gradient(0deg, rgba(36, 36, 36, 0.5), rgba(36, 36, 36, 0.5))@if (file_exists(public_path() . '/img/deportes/banner-'. str_replace(' ', '_', lcfirst($pista_selected->tipo)) .'.jpg')), url(/img/deportes/banner-{{ str_replace(' ', '_', lcfirst($pista_selected->tipo)) }}.jpg) @endif center;
            background-size:cover;">
            <h1 class="title">{{ $pista_selected->tipo }}<br>{{ $pista_selected->subtipo ?? '' }}</h1>
        </div>
    </section>

    @if( request()->slug_instalacion == "vvadecordoba" && request()->deporte = "piscina" )
    <div class="container is-max-desktop" style="text-align: center">
        <img src="https://gestioninstalacion.es/img/piscina_23.jpg"  style="margin-left: auto;margin-right: auto; width: 75%;" >

    </div>
@endif
    <div class="container is-max-desktop">
        <div class="columns">
            <div class="column is-full">
                <div class="div-reservas">
                    <div class="pistas">
                        @if (count($pistas) > 1)
                            <div class="seleccionar-pista-label" style="position: absolute; color: white;top:11px;font-size: 18px">Selecciona espacio: </div>
                        @endif
                        @if (count($pistas) > 4)
                            <select name="pista" class="form-select" id="pista-select2">
                                @foreach ($pistas as $pista)
                                    <option value="{{ $pista->id }}" @if (request()->id_pista == $pista->id) selected @endif>{{ $pista->nombre }}</option>
                                @endforeach
                            </select>
                        @else
                            @foreach ($pistas as $index => $pista)
                                <div class="@if ($pista->id == $pista_selected->id) active @endif"><a class=" select-pista"
                                        data-id_pista="{{ $pista->id }}"
                                        href="/{{ request()->slug_instalacion }}/{{ request()->deporte }}/{{ isset(request()->subtipo) ? request()->subtipo . '/' : '' }}{{ $pista->id }}">{{ $pista->nombre }}</a>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <div class="calendario-horarios">
                        <div class="navigator">
                            <div class="semanas">
                                <a class="button" href="/{{ request()->slug_instalacion }}/{{ request()->deporte }}/{{ isset(request()->subtipo) ? request()->subtipo . '/' : '' }}{{ $pista_selected->id }}?semana={{ request()->semana == null || request()->semana == 0 ? '-1' : request()->semana-1 }}">
                                    <
                                </a>
                                <a class="button {{ request()->semana == null || request()->semana == 0 ? 'active' : '' }}" href="/{{ request()->slug_instalacion }}/{{ request()->deporte }}/{{ isset(request()->subtipo) ? request()->subtipo . '/' : '' }}{{ $pista_selected->id }}">
                                    Hoy
                                </a>
                                <a class="button" href="?semana={{ request()->semana == null || request()->semana == 0 ? '1' : request()->semana+1 }}">
                                    >
                                </a>
                            </div>
                            <div class="calendario">
                                <div class="text-center mb-2" style="font-size: 18px">Selecciona día:</div>
                                <form id="form-dia" method="get" action="/{{ request()->slug_instalacion }}/{{ request()->deporte }}/{{ isset(request()->subtipo) ? request()->subtipo . '/' : '' }}{{ $pista_selected->id }}">
                                    <div class="input-group diapicker">
                                        <a href="#" class="btn btn-secondary"><i class="fas fa-calendar"></i></a>
                                        <input type="hidden" id="dia" class="datepicker date-input form-control" name="dia" value="{{ request()->dia == null ? date('d/m/Y') : request()->dia }}">
                                    </div>
                                </form>
                            </div>
                            <div style="text-transform: capitalize" class="mes">
                                {{ \Carbon\Carbon::parse(iterator_to_array($period)[0])->translatedFormat('d M') . ' - ' . \Carbon\Carbon::parse(iterator_to_array($period)[count(iterator_to_array($period))-1])->translatedFormat('d M')}}
                            </div>
                        </div>
                    </div>
                    <div class="thead">
                        {{-- <div>
                            <div class="th">Hora</div>
                            <div class="th">6:00 - 7:00</div>
                            <div class="th">7:00 - 8:00</div>
                            <div class="th">8:00 - 9:00</div>
                            <div class="th">9:00 - 10:00</div>
                            <div class="th">10:00 - 11:00</div>
                            <div class="th">11:00 - 12:00</div>
                            <div class="th">12:00 - 13:00</div>
                            <div class="th">13:00 - 14:00</div>
                            <div class="th">14:00 - 15:00</div>
                            <div class="th">15:00 - 16:00</div>
                            <div class="th">16:00 - 17:00</div>
                            <div class="th">17:00 - 18:00</div>
                            <div class="th">18:00 - 19:00</div>
                            <div class="th">19:00 - 20:00</div>
                            <div class="th">20:00 - 21:00</div>
                            <div class="th">21:00 - 22:00</div>
                            <div class="th">22:00 - 23:00</div>
                        </div> --}}
                        @foreach ($period as $fecha)
                            <div class="th" style="text-transform: capitalize">
                                <div style="height:4rem">
                                    {{ \Carbon\Carbon::parse($fecha)->translatedFormat('l') }}<br>{{ $fecha->format('d M') }}
                                </div>
                                @foreach ($pista_selected->horario_con_reservas_por_dia($fecha->format('Y-m-d')) as $item)
                                    @foreach ($item as $intervalo)
                                        @if($intervalo['string'] == '22:30 - 23:59')
                                            @if (strtotime($fecha->format('Y-m-d')) < strtotime('2022-08-31'))
                                                <div @if($intervalo['string'] == '22:30 - 23:59') style="height:7rem" @else @if($intervalo['height'] < 17) style="height:{{ $intervalo['height']/2 }}rem" @else style="height:{{ $intervalo['height']/4 }}rem" @endif @endif>
                                                    <a @if (!$intervalo['valida']) href="#" class="btn-no-disponible" @else href="/{{ request()->slug_instalacion }}/{{ request()->deporte }}/{{ $pista_selected->id }}/{{ $intervalo['timestamp'] }}" class="btn-reservar" @endif>
                                                        {{ $intervalo['string'] == '22:30 - 23:59' ? '22:30 - 02:30' : $intervalo['string']}}
                                                        {{-- @if($pista_selected->tipo == 'Piscina' && $intervalo['valida'])
                                                            <br>
                                                            (Libres: {{ $pista_selected->reservas_por_tramo-$intervalo['num_res'] }})
                                                        @endif --}}
                                                    </a>
                                                </div>
                                            @endif
                                        @else
                                            <div @if($intervalo['string'] == '22:30 - 23:59') style="height:7rem" @else @if($intervalo['height'] < 17) style="height:{{ $intervalo['height']/2 }}rem" @else style="height:{{ $intervalo['height']/4 }}rem" @endif @endif>
                                                <a @if (!$intervalo['valida']) href="#" class="btn-no-disponible" @else href="/{{ request()->slug_instalacion }}/{{ request()->deporte }}/{{ $pista_selected->id }}/{{ $intervalo['timestamp'] }}" class="btn-reservar" @endif>
                                                    {{ $intervalo['string'] == '22:30 - 23:59' ? '22:30 - 02:30' : $intervalo['string']}}
                                                    {{-- @if($pista_selected->tipo == 'Piscina' && $intervalo['valida'])
                                                        <br>
                                                        (Libres: {{ $pista_selected->reservas_por_tramo-$intervalo['num_res'] }})
                                                    @endif --}}
                                                </a>
                                            </div>
                                        @endif
                                    @endforeach
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if( request()->slug_instalacion == "vvadecordoba" && request()->deporte = "piscina" )
        <div class="container is-max-desktop" style="text-align: center; margin-top: 3%;">
            <img src="https://gestioninstalacion.es/img/normaspiscina.jpg"  style="margin-left: auto;margin-right: auto; width: 100%;" >

        </div>
    @endif

@endsection

@section('script')
    <script>
        $(document).ready(function () {
            $('.btn-no-disponible').click(function (e) {
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

            $("#dia").focus(function(){
                document.activeElement.blur();
            });

            $(".diapicker").on("click", function (e) {
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

            $('#pista-select2').change(function (e) {
                window.location.href = $('#url_instalacion').html() + $(this).val() + "?dia={{ request()->dia }}&dia_submit={{ request()->dia_submit }}";
            });
        });
    </script>
@endsection
