<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="PwZtoQA4Yrgn7creLcg3BTmITL3iYgzoELulzMSV">

    <title>Contratar servicio</title>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"
            integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
          integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Styles -->
    <link href="https://entradas.aquasierra.es/css/app.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
    <style>
        .descripcion {
            font-weight: 300;
        }

        select.form-control {
            height: calc(1em + 0.75rem + 2px);
            padding: 0;
            padding-left: 6px;
            font-size: 13px;
            font-weight: bold;
        }

        label.col-form-label {
            font-weight: bold;
        }

        h1 {
            margin-bottom: 0.5rem;
            font-size: 1.5rem;
            color: rgb(52, 70, 147);
            font-weight: bold;
        }

        .horario {
            color: rgb(52, 70, 147);
            font-weight: bold;
        }

        body {
            font-weight: 600;
        }

        .fecha-title {
            font-size: 15px;
            color: rgb(52, 70, 147);
        }

        .navbar-end .navbar-item{
            font-weight: bold;
            color: #4a4a4a;
            padding: 0.5rem 0.75rem;
        }
        .navbar-end .navbar-item.active{
            color: #3273dc;
        }
        .navbar-end .navbar-item:hover{
            color: #3273dc;
            text-decoration: none;
        }
        .navbar{
            background: white;
        }
        .card-body{
            padding: 1.75rem;
        }
        .navbar>.container {
            align-items: center;
            display: flex;
            min-height: 3.25rem;
            width: 100%;
        }
        .navbar-brand{
            align-items: center;
            padding: 10px;
            display: flex;
            flex-shrink: 0;
            min-height: 3.25rem;
        }
        .navbar-brand>.navbar-item{
            padding: 10px;
            cursor: pointer;
        }
        .navbar-burger span:nth-child(1) {
            top: calc(50% - 9px);
        }
        .navbar-burger span:nth-child(2) {
            top: calc(50% - -1px);
        }
        .navbar-burger span:nth-child(3) {
            top: calc(50% - 4px);
        }
        .navbar-burger span {
            background-color: currentColor;
            display: block;
            height: 1px;
            left: calc(50% - 8px);
            position: absolute;
            transform-origin: center;
            transition-duration: 86ms;
            transition-property: background-color,opacity,transform;
            transition-timing-function: ease-out;
            width: 16px;
            top: calc(50% - 1px);
        }
        .navbar-burger {
            color: #4a4a4a;
            cursor: pointer;
            display: block;
            height: 3.25rem;
            position: relative;
            width: 3.25rem;
            margin-left: auto;
        }
        .navbar-burger{
            display: none;
        }
        @media (max-width: 1200px) {
            .navbar>.container{
                display: block;
            }
            a.navbar-item {
                padding: 0;
            }
            a.navbar-item>img{
                max-height: 32px !important;
            }
            .navbar-burger{
                display: block;
            }
        }
        input.form-control{
            height: calc(1em + 0.75rem + 2px)
        }
        .navbar {
            z-index: 2;
        }
        .navbar-end{
            display: block;
        }
        @media (max-width: 600px) {
            a.navbar-item {
                padding: 0;
            }
            a.navbar-item>img{
                max-height: 32px !important;
            }
            body>nav.navbar {
                position: fixed;
                top: 0;
                width: 100%;
            }
            body>main{
                margin-top: 73px;
            }
            h1.titulo-pagina {
                margin-top: 103px !important;
            }
        }
        @media (max-width: 1025px) {
            .navbar-menu{
                display: none;
            }
            .navbar-end>a.navbar-item{
                padding: 13px;
            }
            body>nav.navbar {
                display: block;
                padding: 0;
            }
            nav.navbar .navbar-brand{
                justify-content: space-between;
                width: 100%;
            }
            .navbar-end>a:first-child {
                border-top: 1px solid #ccc;
            }
            .navbar-menu.is-active {
                display: block;
            }
            .navbar-item{
                display: block
            }
        }
        @media (min-width: 1025px) {
            .contenedor-navbar{
                width: 100%;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
        }
        @media (max-width: 1000px) {
            .card-col-reserva {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }
    </style>
</head>

<body style="background:linear-gradient(0deg, rgba(36, 36, 36, 0.5), rgba(36, 36, 36, 0.5))@if (file_exists(public_path() . '/img/deportes/reserva-'. lcfirst($servicio->espacio->nombre) .'.jpg')), url(/img/deportes/reserva-{{ lcfirst($servicio->espacio->nombre) }}.jpg) @endif;
    background-size:cover;background-position:bottom">

<nav class="navbar" role="navigation" aria-label="main navigation">
    <div class="contenedor-navbar">
        <div class="navbar-brand">
            <a class="navbar-item" href="/{{ request()->slug_instalacion }}" style="padding: 10px">

                @if (file_exists(public_path() . '/img/ceco.png'))
                    <img src="{{ asset('img/'.request()->slug_instalacion.'.png') }}" style="max-height: 50px" />
                @else
                    <img src="/img/tallerempresarial.png" style="max-height: 50px" />
                @endif
            </a>

            <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false"
               data-target="navbarBasicExample">
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
            </a>
        </div>

        <div id="navbarBasicExample" class="navbar-menu">
            <div class="navbar-end">
                <a href="/{{ request()->slug_instalacion }}" class="navbar-item {{request()->is('/') ? 'active' : '' }}"> Inicio </a>
                {{-- <a href="" class="navbar-item"> Reservar </a> --}}
                {{-- <a href="#" class="navbar-item"> Normas </a> --}}
                @if (\Auth::check())
                    @if (auth()->user()->rol != 'admin')
                        <a href="/{{ request()->slug_instalacion }}/mis-reservas" class="navbar-item {{request()->is(request()->slug_instalacion . '/mis-reservas') ? 'active' : '' }}"><i class="fas fa-book-open mr-2"></i> Mis reservas </a>
                        <a href="/{{ request()->slug_instalacion }}/perfil" class="navbar-item"><i class="fas fa-user mr-2"></i> Mi perfil </a>
                    @else
                        <a href="/{{ request()->slug_instalacion }}/perfil" class="navbar-item"><i class="fas fa-unlock-alt mr-2"></i> Administración </a>
                    @endif
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="navbar-item">
                        <form id="logout-form" action="{{ route('logout', ['slug_instalacion' => request()->slug_instalacion]) }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                        <i class="fas fa-power-off mr-2"></i> Cerrar sesión
                    </a>
                @else
                    <a href="{{ route('login_instalacion', ['slug_instalacion' => request()->slug_instalacion]) }}" class="navbar-item"><i class="fas fa-sign-in-alt mr-2"></i> Acceder</a>

                @endif
            </div>
        </div>
    </div>
</nav>
<div id="app">
    <section class="hero is-medium">
        <div class="has-text-centered title-div title-reserva-section" style="padding-top:4.5rem;padding-bottom:3.5rem;margin-bottom:0">
            <h1 class="title text-center mb-0">{{ $servicio->nombre }}</h1>
        </div>
    </section>
    <main>
        <div class="container pb-5">
            <div class="row justify-content-center">
                <div class="col-sm-7 card-col-reserva">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <h1><i class="far fa-calendar-check mr-2"></i> Contratar servicio</h1>
                                <div class="fecha-title"></div>
                            </div>
                            {{-- <div class="row">
                                <div class="col">
                                </div>
                                <div class="col">
                                </div>
                            </div> --}}
                            <p class="descripcion">
                                Esta contratando el servicio de <em>{{$servicio->nombre}}</em>

                                @if (request()->slug_instalacion == 'santaella' && $servicio->id == "6")
                                    <br><span style="font-weight: 600; color:red">IMPORTANTE: Para poder contratar este servicio deberá acreditar el CARNET JOVEN.</span><br>
                                @endif

                                Por favor, revise y confirme
                                los datos siguientes.
                            </p>




                            <form method="POST" @if (request()->slug_instalacion == 'la-guijarrosa') class="form-guijarrosa" @endif>
                                @csrf
                                @if (request()->slug_instalacion == 'la-guijarrosa' && $servicio->id != "11")
                                    <div class="border p-3 mb-3">
                                        <label for="mayor_edad">La persona que usa la instalaciones es mayor de edad:</label>
                                        <select name="mayor_edad" class="form-control mayor-edad-guijarrosa" required>
                                            <option value="">Seleccione una opción</option>
                                            <option value="si">Sí</option>
                                            <option value="no">No</option>
                                        </select>
                                        <div class="autorizacion d-none">
                                            <input type="hidden" readonly class="descarga_documento" value="0">
                                            <input type="checkbox" name="terminos_edad" id="terminos_edad" class="mt-3"> Si marcas esta casilla confirmas que aceptas los términos de este <ins> <a class="documento_enlace link" href="{{ asset('docs/Autorización utilización gimnasio menores de 16 años.docx') }}" target="_blank">documento</a></ins>, y te comprometes a entregarlo firmado y rellenado en formato papel en la instalación.
                                        </div>

                                    </div>
                                @elseif(request()->slug_instalacion == 'la-guijarrosa' && $servicio->id == "11")
                                    <div class="border p-3 mb-3">
                                        <label for="mayor_edad">La persona que usa la instalación es pensionista, discapacitado o jubilado:</label>
                                        <select name="mayor_edad" class="form-control mayor-edad-guijarrosa" required>
                                            <option value="">Seleccione una opción</option>
                                            <option value="si">Sí</option>
                                            <option value="no">No</option>
                                        </select>
                                        {{-- <div class="autorizacion d-none">
                                            <input type="hidden" readonly class="descarga_documento" value="0">
                                            <input type="checkbox" name="terminos_edad" id="terminos_edad" class="mt-3"> Si marcas esta casilla confirmas que aceptas los términos de este <ins> <a class="documento_enlace link" href="{{ asset('docs/Autorización utilización gimnasio menores de 16 años.docx') }}" target="_blank">documento</a></ins>, y te comprometes a entregarlo firmado y rellenado en formato papel en la instalación.
                                        </div> --}}

                                    </div>
                                @endif

                                @if (request()->slug_instalacion != 'los-agujetas-de-villafranca' && request()->slug_instalacion != 'la-guijarrosa')

                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label py-0">Espacio:</label>
                                    <div class="col-sm-9">
                                        <div>{{ $servicio->espacio->nombre }}</div>
                                    </div>
                                </div>
                                @endif
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label py-0">Fecha de contratación:</label>
                                    <div class="col-sm-9">
                                        <div>{{ \Carbon\Carbon::now()->format('d-m-Y') }}</div>
                                    </div>
                                </div>


                                {{-- @if ($pista->reservas_por_tramo > 1)
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label py-0">Nº de reservas:</label>
                                        <div class="col-sm-9">
                                            <input type="number" name="numero_reservas" class="form-control" placeholder="Nº de reservas" value="1" max="{{ $pista->maximo_reservas_para_usuario(auth()->user()) }}" min="1" required>
                                        </div>
                                    </div>
                                @endif --}}

                                {{-- {{ dd($pista->get_precio_total_given_timestamp(request()->timestamp)) }} --}}

                                    @php
                                        $servicios_usuario = \App\Models\Servicio_Usuario::where('id_usuario', \Auth::user()->id)->where('activo', 'si')->get();
                                        foreach ($servicios_usuario as $key => $servicio_usuario) {
                                            $descuento = \App\Models\Descuento::where('id_servicio_descuento', $servicio->id)->where('id_servicio_padre', $servicio_usuario->id_servicio)->first();
                                            if($descuento){
                                                $servicio->precio = $descuento->nuevo_precio;
                                            }
                                        }

                                    @endphp
                                    <input type="hidden" name="precio_servicio" class="precio_servicio" value="{{$servicio->precio}}">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label py-0">Precio:</label>
                                        <div class="col-sm-9">
                                            <div id="precio_total"  data-precio_base={{ $servicio->precio }}><span>{{$servicio->precio}}</span>€</div>
                                        </div>
                                    </div>
                                    @if (request()->slug_instalacion == "los-agujetas-de-villafranca")
                                    <div class="participantes">

                                        <div class="card p-3 cardParticipante" data-indice="0">

                                            @php
                                                $campos_personalizados = [];
                                                if($servicio->tipos_participante){
                                                    $campos_personalizados = $servicio->tipos_participante->campos_personalizados;
                                                }
                                            @endphp
                                            @if (count($campos_personalizados) > 0)
                                                <div class="d-flex headerCard" style="align-items: center; justify-content: space-between;">
                                                    <h5>Campos adicionales</h5>

                                                </div>
                                            @endif
                                            @foreach ($campos_personalizados as $campo)

                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label py-0">{{ $campo->label }}:</label>
                                                    <div class="col-sm-9">
                                                        @php
                                                            $defaultValue = "";
                                                            switch ($campo->label) {
                                                                case 'Nombre':
                                                                $defaultValue = auth()->user()->name;
                                                                break;
                                                                case 'Fecha de nacimiento':
                                                                    $defaultValue = auth()->user()->date_birth;
                                                                    break;
                                                                case 'Teléfono':
                                                                    $defaultValue = auth()->user()->movil ? auth()->user()->movil : auth()->user()->tlfno;
                                                                    break;
                                                                case 'Email':
                                                                    $defaultValue = auth()->user()->email;
                                                                    break;
                                                                default:
                                                                    # code...
                                                                    break;
                                                            }
                                                        @endphp
                                                        @if ($campo->tipo == "select")
                                                                @php
                                                                    $opciones = unserialize($campo->opciones);
                                                                @endphp
                                                            <select name="campo[0][{{ $campo->id }}]" class="form-control mayor-edad" required>
                                                                <option value="">Seleccione una opción</option>
                                                                @foreach ($opciones as $opcion)

                                                                    <option value="{{ $opcion['pextra'] }}" {{ $opcion['pextra'] == $defaultValue ? 'selected' : '' }}>{{ $opcion['texto'] }}</option>
                                                                @endforeach
                                                            </select>
                                                            @else
                                                            <input type="{{$campo->tipo}}" name="campo[0][{{ $campo->id }}]" class="form-control" placeholder="{{ $campo->label }}" value="{{$defaultValue}}" required>

                                                        @endif
                                                    </div>
                                                </div>

                                            @endforeach

                                        </div>
                                    </div>

                                    <button class="addParticipante btn btn-success mt-2 mb-2" type="button" style="width: 100%">Añadir participante +</button>
                                    @endif
                                    <div class="form-group row">
                                    <label class="col-sm-3 col-form-label py-0"></label>
                                    <div class="col-sm-9">
                                        <div class="form-check">
                                            <input required type="checkbox" class="form-check-input" id="terminos">
                                            <label class="form-check-label" for="terminos">Acepto los <a target="_blank" href="/{{ request()->slug_instalacion }}/condiciones-generales">términos y condiciones de compra</a>.</label>
                                        </div>

                                        @if ($servicio->duracion == "anual")
                                            El primer pago se realizará mediante este proceso, los siguientes cobros se realizarán de manera automática los primeros días de enero.
                                        @elseif ($servicio->formapago=="recurrente")
                                            El primer pago se realizará mediante este proceso, los siguientes cobros se realizarán de manera automática (Puedes desactivar esta opción desde tu perfil y lo podrás hacer manualmente).
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row mt-4">
                                    <label class="col-sm-3 col-form-label py-0"></label>
                                    <div class="col-sm-9 text-right d-flex justify-content-end" style="gap: 14px">
                                        <button type="submit" class="btn btn-info text-white">
                                            <i class="fas fa-check"></i>
                                            <div>Contratar</div>
                                        </button>
                                        <a href="/{{ request()->slug_instalacion }}" class="cancel btn btn-danger">
                                            <i class="fas fa-times"></i>
                                            <div>Cancelar</div>
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
<input type="hidden" id="id_usuario" value="{!! \Auth::user()->id ?? '' !!}">
<input type="hidden" id="id_pista" value="{!! request()->id_pista !!}">
<input type="hidden" id="timestamp" value="{!! request()->timestamp !!}">
<input type="hidden" id="url_redirect" value="/{{ request()->slug_instalacion }}/mis-reservas">
<script src="https://cdn.jsdelivr.net/npm/js-cookie@3.0.1/dist/js.cookie.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>


$(function () {
    $(".mayor-edad").change(function (e) {
        e.preventDefault();
        calcularTotal();
    });
    $('.form-guijarrosa').submit(function (e) {
        e.preventDefault();
        if($('.mayor-edad-guijarrosa').val() == "no"){
            if($('.descarga_documento').val() == "0"){
                Swal.fire({
                    title: 'Debes aceptar los términos',
                    text: 'Descarga el archivo haciendo click en el enlace \'documento\' y acepta los términos para continuar con la reserva.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
                return;
            }
            $('.form-guijarrosa').unbind('submit').submit();
        }
        $('.form-guijarrosa').unbind('submit').submit();
    });

    $(".documento_enlace").click(function (e) {
        $('.descarga_documento').val("1");
    });

    $('.mayor-edad-guijarrosa').change(function (e) {
        e.preventDefault();
        if($(this).val() == "no"){
            $('.autorizacion').removeClass('d-none');
            $('.autorizacion input').attr('required', true);
        }else{
            $('.autorizacion').addClass('d-none');
            $('.autorizacion input').removeAttr('required');
        }
    });

    function calcularTotal() {
        let total = 0;
        $.each($('.mayor-edad'), function (indexInArray, valueOfElement) {
            if($(this).val() != ""){
                total += parseInt($(this).val());
            }else{
                total += 15;
            }
        });
        $('.precio_servicio').val(total);
        $("#precio_total span").text(total);
    }

    $('.formReservar').submit(function (e) {
        e.preventDefault();
        $.each($('.cardParticipante select'), function (indexInArray, valueOfElement) {
            $(this).removeAttr('disabled');

        });
        $(this).unbind('submit').submit();

    });

    $(".addParticipante").click(function (e) {
        e.preventDefault();
        let cardParticipante = $(".cardParticipante").first().clone();
        let removeButton = '<button class="removeParticipante btn btn-danger mt-2 mb-2" type="button"><i class="fas fa-trash"></i></button>';
        cardParticipante.find(".headerCard").append(removeButton);
        cardParticipante.css('margin-top', '10px');
        let indice = $(".cardParticipante").last().data('indice') + 1;
        cardParticipante.data('indice', indice);
        cardParticipante.find("input").val("");

        $.each(cardParticipante.find('input'), function (indexInArray, valueOfElement) {
            $(this).attr('name', $(this).attr('name').replace('[0]', '['+indice+']'));
        });

        $.each(cardParticipante.find('select'), function (indexInArray, valueOfElement) {
            $(this).attr('name', $(this).attr('name').replace('[0]', '['+indice+']'));
        });

        $('.participantes').append(cardParticipante);
        $(".removeParticipante").click(function (e) {
            e.preventDefault();
            $(this).closest(".cardParticipante").remove();
            calcularTotal();
        });

        $(".mayor-edad").off('change');


        $(".mayor-edad").change(function (e) {
            e.preventDefault();
            calcularTotal();
        });
    });
});

</script>

</body>

</html>
