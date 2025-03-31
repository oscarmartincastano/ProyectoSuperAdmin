@extends('new.layout.base')

@section('style')
    <style>
        .titulo-card {
            margin-bottom: 16px;
            border-bottom: solid 1px #eaeaea;
            padding-bottom: 9px;
            font-weight: 600;
            line-height: 1.4;
            font-size: 19px;
        }

        .contenido-principal {
            padding: 80px;
        }

        th {
            border-top: 0 !important;
        }

        .cartel-evento img {
            width: 100%;
            padding: 20px;
            padding-bottom: 0px;
            max-width: 450px;
        }

        .titulo-evento {
            color: #000000;
            font-size: 26px;
            font-weight: bold;
            line-height: 30px;
            text-transform: uppercase;
        }

        .fecha-evento {
            font-size: 18px;
        }

        .card-body {
            padding: 10px 20px;
        }
        .titulos {
            margin-bottom: 15px;
        }
        .fecha-evento span {
            background: #335fff;
            color: white;
            font-size: 14px;
            font-weight: bold;
            padding: 2px;
        }
        .contenido-principal{
            padding: 20px 0;
        }
        .description-card p {
            margin-bottom: 0.25rem;
        }
        @media (min-width: 576px) {
            .modal-dialog {
                max-width: 740px;
            }
        }
        #modal-participantes  label {
        color: rgba(47,51,51,.4);
        display: block;
        font-size: 14px;
        line-height: 16px;
        height: 16px;
        font-weight: 500;
    }
    #modal-participantes  input, #modal-participantes  textarea {
        display: block;
        width: 100%;
        font-size: 16px;
        padding: 7px 0;
        line-height: 1.8;
        border: 0;
        border-bottom: 1px solid rgba(14,36,51,0.3);
        -webkit-transition: border-color .25s;
        transition: border-color .25s;
    }
    #modal-participantes  input:focus, #modal-participantes  textarea:focus {
        border: 0;
        border-bottom: 1px solid #335fff;
        outline: 0;
        box-shadow: none;
    }
    #modal-participantes .modal-content{
        top: unset !important;
    }

    body > main > div > div > div.col-md-8 > div:nth-child(3) > div > div.contenido-card > ul > li:nth-child(3) > div.contenido-parte.precio-inscripcion > div:nth-child(1){
        display: none;
    }

    @media (max-width: 750px) {
            .titulos{
                padding: 0 15px;
            }

            .card-reserva{
                margin-left: 15px;
                margin-right: 15px;
            }
    }
    @font-face{
        font-family: 'BODAS';
        src: url('{{ asset('fonts/CAMPOS.TTF') }}') format('truetype');
        font-display: swap;
    }


    </style>
    @if (request()->slug_instalacion == "eventos-bodega")
        <style>
            .contenido-card em{
                color: #721e27;
            }
        </style>
    @endif
@endsection

@section('content')
    <div class="row">
        <div id="precio_persona" class="d-none" data-precio_persona="{{ $evento->precio_participante }}"></div>
        <div class="col-md-4">
            <div class="cartel-evento mb-4">
                <img src="/img/eventos/{{ request()->slug_instalacion }}/{{ $evento->id }}.jpg">
            </div>

        </div>
        <div class="col-md-8">
            <div class="titulos">
                <h1 class="titulo-evento"                                                         @if (request()->slug_instalacion == "eventos-bodega")
                    style="font-family: 'BODAS';font-size: 30px;"
                @endif>{{ $evento->nombre }}</h1>
                @if (date('d/m/Y', strtotime($evento->fecha_inicio)) == date('d/m/Y', strtotime($evento->fecha_fin)))
                    <div class="fecha-evento"><span>{{ date('d/m/Y', strtotime($evento->fecha_inicio)) }}</span></div>
                @else
                <div class="fecha-evento"><span>Del {{ date('d/m/Y', strtotime($evento->fecha_inicio)) }} al
                    {{ date('d/m/Y', strtotime($evento->fecha_fin)) }}</span></div>
                @endif
            </div>
            <div class="card shadow-box card-reserva mb-4">
                <div class="card-body">
                    <div class="titulo-card">Información del evento</div>
                    <div class="contenido-card description-card">
                        {!! $evento->descripcion !!}
                    </div>
                </div>
                @if ($instalacion->finalidad_eventos == FINALIDAD_ENTRADA)
                <div class="text-center mb-3" >
                    @if(Auth::check())
                        @if (request()->slug_instalacion == "villafranca-navidad" or request()->slug_instalacion == "eventos-bodega")
                            <a href="#" class="btn btn-primary btn-modal-inscribirse">Comprar</a>
                            @elseif(request()->slug_instalacion == "feria-jamon-villanuevadecordoba" and $evento->entradas_agotadas == 1)
                            <a href="#" class="btn disabled" style="color:red">
                                <b>Bonos agotados</b>
                            @elseif(request()->slug_instalacion == "feria-jamon-villanuevadecordoba")
                            <a href="#" class="btn btn-primary btn-modal-inscribirse">Comprar bonos</a>
                            @else
                            <a href="#" class="btn btn-primary btn-modal-inscribirse">Inscribirse</a>
                            @endif
                    @else
                        @if(request()->slug_instalacion == "feria-jamon-villanuevadecordoba" and $evento->entradas_agotadas == 1)
                        <a href="#" class="btn disabled" style="color:red">
                            <b>Bonos agotados</b>
                            </a>
                        @elseif(request()->slug_instalacion == "feria-jamon-villanuevadecordoba")
                            <p>Es necesario estar registrado para comprar los bonos</p>
                            <a href="/{{ request()->slug_instalacion }}/register?evento={{$evento->id}}" class="btn btn-primary ">Regístrate para comprar los bonos.</a>

                            @else
                            <p>Es necesario estar registrado para comprar las entradas</p>
                            <a href="/{{ request()->slug_instalacion }}/register?evento={{$evento->id}}" class="btn btn-primary ">Regístrate</a>
                        @endif
                    @endif
                </div>
                @endif
            </div>
            @if ($instalacion->finalidad_eventos != FINALIDAD_ENTRADA)
            <div class="card shadow-box mb-4" >
                <div class="card-body">
                    <div class="titulo-card">
                        @if (request()->slug_instalacion == "eventos-bodega")
                        Comprar
                        @else
                        Inscribirse
                        @endif
                    </div>
                    <div class="contenido-card">
                        <ul class="list-group group-horario">
                            @if($evento->renovacion_mes)
                            <li class="list-group-item d-block" style="border-top:0;border-left:0;border-right:0;">
                                <div class="titulo-parte" style="font-weight: bold;">Mes de la inscripción</div>
                                <div class="contenido-parte" style="text-transform: capitalize;">{{ date('d')<=31 ? strftime('%B') : strftime('%B', strtotime('01-' .(date('m')+1). '-'.date('Y'))) }}</div>
                            </li>
                            @endif

                            <li class="list-group-item d-block" style="border-top:0;border-left:0;border-right:0">
                                <div class="titulo-parte" style="font-weight: bold">Plazo de inscripción</div>
                                @if($evento->renovacion_mes)
                                <div class="contenido-parte">
                                    Mensual
                                </div>
                                @else
                                <div class="contenido-parte">Del
                                    {{ date('d/m/Y', strtotime($evento->insc_fecha_inicio)) }} al
                                    {{ date('d/m/Y', strtotime($evento->insc_fecha_fin)) }}</div>
                                @endif
                            </li>
                            @if (request()->slug_instalacion != 'villafranca-navidad')

                            <li class="list-group-item d-block" style="border-top:0;border-left:0;border-right:0">
                                <div class="titulo-parte" style="font-weight: bold">Plazas disponibles</div>
                                <div class="contenido-parte">{{ $evento->num_participantes > 5000 ? 'Ilimitadas' : $evento->num_participantes }}  @if (request()->slug_instalacion == "la-guijarrosa")equipos @else plazas @endif</div>
                            </li>
                            @endif
                            <li class="list-group-item d-block" style="border: 0">
                                <div class="titulo-parte precio-titulo" style="font-weight: bold">Precio</div>
                                @if ($evento->precio_participante==0)
                                <div class="contenido-parte precio-inscripcion">Gratis</div>
                                @else
                                <div class="contenido-parte precio-inscripcion">{{ $evento->precio_participante }}€/persona</div>
                                @endif
                                @if (request()->slug_instalacion == "la-guijarrosa")
                                    <div class="contenido-parte ">{{ $evento->precio_participante }}€/equipo</div>
                                @endif
                            </li>
                        </ul>
                        {{-- @if (!$evento->renovacion_mes || ($evento->renovacion_mes && date('d')<15)) --}}
                            <div class="text-center my-3">
                                @if(Auth::check())
                                        @if (request()->slug_instalacion == "villafranca-navidad" or request()->slug_instalacion == "eventos-bodega")
                                        <a href="#" class="btn btn-primary btn-modal-inscribirse">Comprar</a>
                                        @else
                                        <a href="#" class="btn btn-primary btn-modal-inscribirse">Inscribirse</a>
                                        @endif
                                @else
                                @if(request()->slug_instalacion == "campamentos-vva")
                                <p>Es necesario iniciar sesión para poderse inscribir</p>
                                <a href="/{{ request()->slug_instalacion }}/login?evento={{$evento->id}}" class="btn btn-primary ">Acceder</a>
                                @else
                                <p>Es necesario estar registrado para comprar las entradas</p>
                                <a href="/{{ request()->slug_instalacion }}/register?evento={{$evento->id}}" class="btn btn-primary ">Regístrate</a>
                                @endif
                                @endif
                                {{-- <form action="#" method="post">
                                    @csrf
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-check mr-1"></i> Inscribirse</button>
                                </form> --}}
                            </div>
                        {{-- @endif --}}
                    </div>
                </div>
            </div>
            @endif

            <div class="card shadow-box mb-4">
                <div class="card-body">
                    <div class="titulo-card">Dónde nos encontramos</div>
                    <div class="contenido-card">
                        <div style="width: 100%"><iframe width="100%" height="180" frameborder="0" scrolling="no"
                                marginheight="0" marginwidth="0"
                                src="https://maps.google.com/maps?width=100%25&amp;height=600&amp;hl=en&amp;q={{ $evento->localizacion }}+(Your%20Business%20Name)&amp;t=&amp;z=14&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal-participantes" tabindex="-1" role="dialog" style="padding-right: 0">
            <div class="modal-dialog" role="document">
            <div class="modal-content m-0" style="top:25vh">
                <div class="modal-header">
                @if (request()->slug_instalacion != "la-guijarrosa" && request()->slug_instalacion != "feria-jamon-villanuevadecordoba")
                <h4 class="h4 mb-0">Datos de personas</h4>
                @elseif (request()->slug_instalacion == "feria-jamon-villanuevadecordoba")
                <h4 class="h4 mb-0">Datos de compra</h4>
                @else
                <h4 class="h4 mb-0">Nombre del equipo</h4>
                @endif
                <button type="button" class="close" id="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body p-4">
                    {{-- <p class="mb-4 text-center" style="font-size: 17px">Nos falta el dato de tu <b>DIRECCIÓN COMPLETA</b> para el funcionamiento de la aplicación. Añade tu dirección desde aquí o tu cuenta puede ser bloqueada: </p> --}}
                    <form method="POST" action="#">
                        @csrf
                        <input type="hidden" name="hora_inicio" value="{{ \Carbon\Carbon::parse($evento->fecha_inicio)->format("H:i") }}">
                        <input type="hidden" name="hora_fin" value="{{ \Carbon\Carbon::parse($evento->fecha_fin)->format("H:i") }}">
                        <div class="div-participantes" style="max-height: 70vh;overflow-y: auto;">
                            <div data-index="0" class="mb-3 div-empleado">
                                @if(request()->slug_instalacion != "feria-jamon-villanuevadecordoba")
                                <label class="text-uppercase label-participante d-flex justify-content-between"><div>Persona <span class="num-participante">1</span></div><div @if(request()->slug_instalacion == "eventos-bodega")  style="color: #721e27;font-size: 16px;font-style: italic;"@endif><strong>Precio: </strong><span class="precio-persona"></span></div></label>
                                @else
                               <label class="text-uppercase label-participante d-flex justify-content-between"><div><strong style="color: #000000 !important; font-size: 16px">Precio: </strong><span class="precio-persona" style="font-size: 16px; color: #000000 !important"></span></div></label>

                                @endif
                                <div class="p-3 border" >

                                    @foreach ($evento->tipo_participante->campos_personalizados as $item)
                                        <div class="form-group">
                                            <label for="#" @if($item->tipo == 'checkbox')style="display: none;" @endif>{{ $item->label }}</label>




                                            @if ($item->tipo == 'textarea')
                                                <textarea class="form-control" data-campo="{{ $item->id }}" name="campo_adicional[0][{{ $item->id }}]" rows="3" {{ $item->required ? 'required' : '' }}></textarea>
                                            @elseif($item->tipo == 'select')
                                                <select class="form-control select_adicional" data-campo="{{ $item->id }}" name="campo_adicional[0][{{ $item->id }}]">
                                                    @foreach (unserialize($item->opciones) as $option)

                                                        <option data-value="{{ $option['texto'] }}"  data-precio_extra_opcion={{ $option['pextra'] }} value="{{ $option['texto'] }}">{{ $option['texto'] }}</option>
                                                    @endforeach
                                                </select>
                                            @elseif($item->tipo == 'checkbox')
                                                <input type="{{ $item->tipo }}" data-campo="{{ $item->id }}" name="campo_adicional[0][{{ $item->id }}]"  style="display: inline-flex;width: 5%; margin-right: 2px;" placeholder="{{ $item->label }}" {{ $item->required ? 'required' : '' }}>{{ $item->label }}
                                            @elseif($item->tipo == 'date' && request()->slug_instalacion == "villafranca-de-cordoba")
                                                @if ($evento->id == 43)
                                                    @php
                                                        $max = "2021-12-31";
                                                    @endphp
                                                @elseif ($evento->id == 41)
                                                    @php
                                                        $max = "2022-12-31";
                                                    @endphp
                                                @else
                                                    @php
                                                        $max = "2020-12-31";
                                                    @endphp
                                                @endif
                                            <input type="{{ $item->tipo }}" data-campo="{{ $item->id }}" name="campo_adicional[0][{{ $item->id }}]" class="form-control" placeholder="{{ $item->label }}" {{ $item->required ? 'required' : '' }} max="{{$max}}">

                                            @else
                                            @if(auth()->check() && request()->slug_instalacion == "feria-jamon-villanuevadecordoba")
                                            @if ($item->label === 'Nombre y apellidos')
                                                <input value="{{ auth()->user()->name }}" data-campo="{{ $item->id }}" name="campo_adicional[0][{{ $item->id }}]" class="form-control" placeholder="{{ $item->label }}" {{ $item->required ? 'required' : '' }}>
                                            @elseif ($item->label === 'Correo electrónico')
                                                <input value="{{ auth()->user()->email }}" data-campo="{{ $item->id }}" name="campo_adicional[0][{{ $item->id }}]" class="form-control" placeholder="{{ $item->label }}" {{ $item->required ? 'required' : '' }}>
                                            @elseif ($item->label === 'Teléfono')
                                                <input value="{{ auth()->user()->tlfno }}" data-campo="{{ $item->id }}" name="campo_adicional[0][{{ $item->id }}]" class="form-control" placeholder="{{ $item->label }}" {{ $item->required ? 'required' : '' }}>
                                            @elseif ($item->label === 'Código postal')
                                                <input value="{{ auth()->user()->codigo_postal }}" data-campo="{{ $item->id }}" name="campo_adicional[0][{{ $item->id }}]" class="form-control" placeholder="{{ $item->label }}" {{ $item->required ? 'required' : '' }}>
                                            @else
                                                <input value="" data-campo="{{ $item->id }}" name="campo_adicional[0][{{ $item->id }}]" class="form-control" placeholder="{{ $item->label }}" {{ $item->required ? 'required' : '' }}>
                                            @endif
                                        @else
                                            <input data-campo="{{ $item->id }}" name="campo_adicional[0][{{ $item->id }}]" class="form-control" placeholder="{{ $item->label }}" {{ $item->required ? 'required' : '' }}>
                                        @endif

                                            @endif

                                        </div>
                                    @endforeach
                                    <button class="btn btn-danger btn-sm btn-eliminar-participante" type="button" title="Eliminar participante" style="display: none">
                                        Eliminar <i class="fas fa-trash-alt"></i>
                                    </button>
                                    <input type="hidden" name="campo_adicional[0][precio]" class="precio_participante">
                                </div>
                            </div>
                            @if (request()->slug_instalacion == "ciprea24")

                            <div class="form-check" style="display: flex; margin-bottom: 5px; align-items: center;">
                                <input class="form-check-input" type="checkbox" id="defaultCheck1" required style="width: 1em; border: 1px solid rgba(0,0,0,.25);">
                                <label class="form-check-label" for="defaultCheck1">
                                    Acepto los <a href="/ciprea24/terminos-de-compra" target="_blank">términos y condiciones de compra</a> / I accept <a href="/ciprea24/terminos-de-compra/en">the terms and conditions of purchase</a>
                                </label>
                            </div>
                            @endif
                            @if (request()->slug_instalacion != "feria-jamon-villanuevadecordoba")
                            <a href="#" class="btn btn-secondary btn-add-participante mt-1"><i class="fas fa-plus"></i>

                                @if ($instalacion->finalidad_eventos == FINALIDAD_ENTRADA)
                                Añadir persona
                                @else
                                Añadir participante
                                @endif
                            </a>
                            @endif
                            {{-- aceptar terminos y condiciones --}}
                        </div>


                        <button type="submit" class="btn btn-primary w-100 mt-3">Enviar</button>
                    </form>
                    {{-- <p class="text-center mt-2"><a href="#"data-dismiss="modal" aria-label="Close" class="btn btn-primary">Entendido</a></p> --}}
                </div>
            </div>
            </div>
        </div>
    </div>
@endsection
@if (request()->slug_instalacion == "eventos-bodega")
    <div class="bodegas-true"></div>
@endif
@section('script')
<script>
    $(document).ready(function () {
        $('.btn-modal-inscribirse').click(function (e) {
            e.preventDefault();
            $('#modal-participantes').modal('show');
            calcular_precios();
        });
        $('#close').click(function (e) {
            e.preventDefault();
            $('#modal-participantes').modal('hide');
         });

        $('.btn-add-participante').click(function (e) {
            e.preventDefault();
            let new_index = $(this).prev().data('index') + 1;
            console.log(new_index);
            let new_participante = $(this).prev().clone();
            let contador = $('.div-empleado').length;

            /* if (contador >= 6 && $(".bodegas-true").length > 0) {
                alert("No puedes añadir más de 6 participantes");
                return;

            } */

            new_participante.attr('data-index', new_index).find('input, textarea').val('');
            new_participante.find('.num-participante').html(new_index+1);

            $(new_participante.find('.form-group>*:not(label)')).each(function (index, element) {
                $(element).attr('name', `campo_adicional[${new_index}][${$(element).data('campo')}]`);
            });

            new_participante.find('.precio_participante').attr('name', `campo_adicional[${new_index}][precio]`);

            new_participante.find('.btn-eliminar-participante').show();
            console.log($('.btn-eliminar-participante'));

            $(this).before(new_participante);


            calcular_precios();
            $('.btn-eliminar-participante').click(function (e) {
                e.preventDefault();
                $(this).closest('.div-empleado').remove();
                calcular_precios();
            });

            dni= "input[name='campo_adicional["+(new_index-1)+"][5]']";
            dninuevo= "input[name='campo_adicional["+(new_index)+"][5]']";
            insertardni= $(dni).val();

            nombre= "input[name='campo_adicional["+(new_index-1)+"][4]']";
            nombrenuevo= "input[name='campo_adicional["+(new_index)+"][4]']";
            insertarnombre= $(nombre).val();

            telf= "input[name='campo_adicional["+(new_index-1)+"][6]']";
            telfnuevo= "input[name='campo_adicional["+(new_index)+"][6]']";
            insertartelf= $(telf).val();

            $(dninuevo).val(insertardni);
            $(nombrenuevo).val(insertarnombre);
            $(telfnuevo).val(insertartelf);

        });

        $('form').on('change', 'select', function (e) {
            e.preventDefault();
            calcular_precios();
        });

        let arrayPrecios = [];
        $('[data-precio_extra_opcion]').each(function( index ) {
            arrayPrecios.push($(this).data('precio_extra_opcion'));
        });
        arrayPrecios = [...new Set(arrayPrecios)];
        // let precio = $('#precio_persona').data('precio_persona') + $('[data-precio_extra_opcion]:selected').data('precio_extra_opcion');
        // precio = isNaN(precio) ? $('#precio_persona').data('precio_persona') : precio;
        let htmlPrecios = '';
        arrayPrecios.forEach(function (precio) {
            htmlPrecios += `<div class="contenido-parte precio-inscripcion">
                ${precio} €/participante
            </div>`;
        });
        console.log(arrayPrecios);

        $('.precio-inscripcion').html(htmlPrecios != [0] ? htmlPrecios : 'Gratis');
        if (arrayPrecios.length > 0) {
            $(".precio-titulo").html('Precios');

        }

        let calcular_precios = function() {
            $('[data-precio_extra_opcion]').parent().each(function (index, element) {
                let precio = $('#precio_persona').data('precio_persona') + $(this).find('option:selected').data('precio_extra_opcion');
                precio = isNaN(precio) ? $('#precio_persona').data('precio_persona') : precio;
                $(this).closest('.div-empleado').find('.precio-persona').html(precio + ' €');
                $(this).closest('.div-empleado').find('.precio_participante').val(precio);
            });

            if (!$('[data-precio_extra_opcion]').length) {
                $('.precio-persona').html($('#precio_persona').data('precio_persona') + ' €');
                $('.precio_participante').val($('#precio_persona').data('precio_persona'));
            }
            console.log($('.precio_participante'));
        }


    });
</script>
@if (request()->slug_instalacion == "villafranca-navidad")
    <script>
    $(document).ready(function () {
        $('.btn-modal-inscribirse').trigger('click');
    });
    </script>
@endif
@endsection
