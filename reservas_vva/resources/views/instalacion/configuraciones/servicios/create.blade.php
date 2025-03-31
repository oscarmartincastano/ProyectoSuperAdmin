@extends('layouts.admin')

@section('pagename', 'Añadir servicio')

@section('content')
    <div class="row">
        <div class="col-lg-12 m-b-10">

            <div class="p-l-20 p-r-20 p-b-10 pt-3">
                <div>
                    <h3 class="text-primary no-margin">Añadir  servicio</h3>
                </div>
            </div>

            <div class="p-t-15 p-b-15 p-l-20 p-r-20">
                <div class="card ">
                    <div class="card-header">
                        <div class="card-title">Nuevo servicio</div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('crear_servicio',auth()->user()->instalacion->slug)}}" method="post" role="form" id="formulario">
                            @csrf
                            <div class="form-group row">
                                <label>Nombre</label>
                                <input  name="nombre" type="text" placeholder="Nombre..." class="form-control" required>
                            </div>
                            <div class="form-group row">
                                <label>Espacio</label>
                                <select name="espacio" id="espacio" class="form-control">
                                    @foreach (auth()->user()->instalacion->deportes_clases as $item)
                                        <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="row" id="div_promo" style="display: none;">
                                <div class="col-sm">
                                    <label>Deporte</label>
                                    <div class="input-group mb-3">
                                        <select id="promocion"  name="deporte" class="form-control select3 select-promo">

                                        </select>
                                    </div>
                                </div>
                            </div>
                            ¿Puede realizar reservas?
                            <div class="form-check">

                                <input class="form-check-input" type="radio" name="reserva" id="reservasi"   value="Si" checked>
                                <label class="form-check-label" for="reservasi">
                                    Si
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="reserva" id="reservano"  value="No">
                                <label class="form-check-label" for="reservano">
                                    No
                                </label>
                            </div>
                            ¿Tiene campos personalizados?
                            <div class="form-check">

                                <input class="form-check-input" type="radio" name="campos" id="campossi"   value="Si" checked>
                                <label class="form-check-label" for="campossi">
                                    Si
                                </label>
                            </div>
                            <div class="form-check">

                                <input class="form-check-input" type="radio" name="campos" id="camposno"   value="No" checked>
                                <label class="form-check-label" for="camposno">
                                    No
                                </label>
                            </div>

                            <div class="form-group d-none select-participante card p-3">
                                <label>Tipos de participante</label>
                                <select class="form-control" name="tipos_participante" id="tipos_participante" style="width: 100%;">
                                    @foreach (auth()->user()->instalacion->tipos_participante as $item)
                                        <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group row">
                                <label>Tipo</label>
                                <select name="tipo" id="tipo" class="form-control">
                                    <option value="entrada">Entrada/bono de usos</option>
                                    <option value="suscripcion" selected>Suscripción</option>
                                </select>
                            </div>
                            <div class="form-group row">
                                <label>Forma de pago</label>
                                <select name="formapago" id="formapago" class="form-control">
                                    <option value="tarjeta">Pago manual por tarjeta</option>
                                    <option value="recurrente" selected>Cobro Automatizado por tarjeta</option>
                                </select>
                            </div>
                            <div class="form-group row">
                                <label>Duración</label>
                                <select  name="duracion" class="form-control">
                                    <option value="diario">Diario</option>
                                    <option value="semanal">Semanal</option>
                                    <option value="quincenal">Quincenal</option>
                                    <option value="trimestral">Trimestral</option>
                                    <option value="semestral">Semestral</option>
                                    <option value="mensual">Mensual</option>
                                    <option value="mensual">Anual</option>
                                </select>
                            </div>
                            <div class="form-group row">
                                <label>Precio (€)</label>
                                <input  name="precio" type="number" placeholder="Precio..." class="form-control" step=".01" required>
                            </div>
                            @if (isset($servicios) and $servicios->count() > 0)
                                
                            <div class="form-group row card p-3">
                                <label for="descuento"><strong>Descuentos</strong></label>
                                <small class="mb-3">Indica cual será el nuevo precio del servicio al tener contratado este</small>
                                @foreach ($servicios as $servicio)
                                    <label for="descuento_{{$servicio->id}}">{{$servicio->nombre}}</label>
                                    <input type="number" name="descuento[{{$servicio->id}}]" id="descuento_{{$servicio->id}}" value="{{$servicio->precio}}" class="form-control">
                                @endforeach
                            </div>
                            @endif

                            <div class="form-group">
                                <label>Descripción</label>
                                <div class="quill-wrapper">
                                    <div id="quill">


                                    </div>
                                </div>
                                <input type="hidden" name="contenido" class="contenido">

                            </div>

                            <button class="btn btn-primary btn-lg m-b-10 mt-3" type="submit">@if(isset(request()->id)) Editar @else Añadir @endif</button>
                        </form>
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

        $.ajax({
            url:`/@php echo auth()->user()->instalacion->slug; @endphp/admin/configuracion/servicios/deportes/${$('#espacio').val()}`,
            success: data =>{
                $('.select3.select-promo').empty();
                $('#div_promo').show();
                $('.select3.select-promo').append(`<option value="0">Todos</option>`);
                $.each(data, function (i, val) {
                    $('.select3.select-promo').append(`<option value="${val.id}">${val.nombre}</option>`);
                });

            },
            error: er => {
                console.log(er);
            }
        });

        $("#campossi").click(function(){
            $(".select-participante").removeClass('d-none');
        });
        $("#camposno").click(function(){
            $(".select-participante").addClass('d-none');
        });

        $('form').on('change', '#espacio', function () {
            $.ajax({
                url:`/@php echo auth()->user()->instalacion->slug; @endphp/admin/configuracion/servicios/deportes/${$('#espacio').val()}`,
                success: data =>{
                    $('.select3.select-promo').empty();
                    $('#div_promo').show();
                    $('.select3.select-promo').append(`<option value="0">Todos</option>`);
                    $.each(data, function (i, val) {
                        $('.select3.select-promo').append(`<option value="${val.id}">${val.nombre}</option>`);
                    });

                },
                error: er => {
                    console.log(er);
                }
            });
        });
        var toolbarOptions = [
            ['bold', 'italic', 'underline', 'strike'],
            ['blockquote', 'code-block'],

            [{ 'header': 1 }, { 'header': 2 }],
            [{ 'list': 'ordered' }, { 'list': 'bullet' }],
            [{ 'script': 'sub' }, { 'script': 'super' }],
            [{ 'indent': '-1' }, { 'indent': '+1' }],
            [{ 'direction': 'rtl' }],

            [{ 'size': ['small', false, 'large', 'huge'] }]
                [{ 'header': [1, 2, 3, 4, 5, 6, false] }],

            [{ 'color': [] }, { 'background': [] }],
            [{ 'font': [] }],
            [{ 'align': [] }],

            ['clean']
        ];

        var quill = new Quill('#quill', {
            modules: {
                toolbar: toolbarOptions
            },
            placeholder: 'Describe el servicio',
            theme: 'snow'
        });



        $('#formulario').submit(function (e) {
            $('.contenido').val(quill.root.innerHTML);
        });
    });
</script>
    @endsection

