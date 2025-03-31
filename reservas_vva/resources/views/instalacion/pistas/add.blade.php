@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-lg-12 m-b-10">

            <div class="p-l-20 p-r-20 p-b-10 pt-3">
                <div>
                    <h3 class="text-primary no-margin">Añadir espacio</h3>
                </div>
            </div>

            <div class="p-t-15 p-b-15 p-l-20 p-r-20">
                <div class="card ">
                    <div class="card-header">
                        <div class="card-title">Información</div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('add_pista', ['slug_instalacion' => $instalacion->slug]) }}" method="post" role="form" class="form-horizontal">
                            @csrf
                            <div class="form-group row">
                                <label class="col-md-2 control-label">Nombre</label>
                                <input name="nombre" type="text" placeholder="Nombre..." class="form-control col-md-10" required>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 control-label">Nombre corto</label>
                                <input name="nombre_corto" type="text" placeholder="Nombre corto..."
                                    class="form-control col-md-10">
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 control-label">Precio (€)</label>
                                <input name="precio" type="number" placeholder="Precio..."
                                    class="form-control col-md-10">
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 control-label">Deporte</label>
                                <select class="form-control col-md-10" name="id_deporte">
                                    @foreach (\App\Models\Deporte::all() as $item)
                                        <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 control-label">Tipo</label>
                                <select class="form-control col-md-10" name="tipo">
                                        <option value="Tenis">Tenis</option>
                                        <option value="Pádel">Pádel</option>
                                        <option value="Fútbol">Fútbol</option>
                                        <option value="Baloncesto">Baloncesto</option>
                                        <option value="Ping pong">Ping pong</option>
                                        <option value="Sala">Sala</option>
                                        <option value="Pabellon">Pabellón</option>
                                        <option value="Gimnasio">Gimnasio</option>
                                        <option value="Atletismo">Atletismo</option>
                                        <option value="Piscina">Piscina</option>
                                        <option value="Mercadillo">Mercadillo</option>
                                        <option value="Padbol">Padbol</option>
                                        <option value="Bádminton">Bádminton</option>
                                </select>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 control-label">Horario para alquiler</label>
                                <div class="col-md-10">
                                    <div>
                                        <div class="ml-1 my-1 border" style="padding: 14px" data-index="0">
                                            <strong class="mb-2">Horario <span>1</span></strong>
                                            <div class="row my-1" style="gap: 18px">
                                                <div class="form-check primary"><input type="checkbox" name="horario[0][dias][]" id="Lunes[0]" value="1"> <label for="Lunes[0]">
                                                    Lunes</label> </div>
                                                <div class="form-check primary"><input type="checkbox" name="horario[0][dias][]" id="Martes[0]" value="2"> <label for="Martes[0]">
                                                    Martes</label> </div>
                                                <div class="form-check primary"><input type="checkbox" name="horario[0][dias][]" id="Miércoles[0]" value="3"> <label for="Miércoles[0]">
                                                    Miércoles</label> </div>
                                                <div class="form-check primary"><input type="checkbox" name="horario[0][dias][]" id="Jueves[0]" value="4"> <label for="Jueves[0]">
                                                    Jueves</label> </div>
                                                <div class="form-check primary"><input type="checkbox" name="horario[0][dias][]" id="Viernes[0]" value="5"> <label for="Viernes[0]">
                                                    Viernes</label> </div>
                                                <div class="form-check primary"><input type="checkbox" name="horario[0][dias][]" id="Sábado[0]" value="6"> <label for="Sábado[0]">
                                                    Sábado</label> </div>
                                                <div class="form-check primary"><input type="checkbox" name="horario[0][dias][]" id="Domingo[0]" value="7"> <label for="Domingo[0]">
                                                    Domingo</label> </div>
                                            </div>
                                            <div>
                                                <div class="row my-2 p-2 border interval-horario align-items-center" data-index="0">
                                                    <div class="col-md-4 mb-2"><label class="mb-0">Hora inicio: </label><input
                                                            class="form-control" type="time" name="horario[0][intervalo][0][hinicio]"
                                                            id="hora_inicio"></div>
                                                    <div class="col-md-4 mb-2"><label class="mb-0">Hora Fin: </label><input
                                                            class="form-control" type="time" name="horario[0][intervalo][0][hfin]"
                                                            id="hora_fin"></div>
                                                    <div class="col-md-4 mb-2">
                                                        <label class="mb-0">Secuencia (min):</label>
                                                        <select class="form-control" name="horario[0][intervalo][0][secuencia]" id="">
                                                            @for ($i = 1; $i < 9; $i++)
                                                                <option value="{{ $i * 15 }}">{{ $i * 15 }}</option>
                                                            @endfor
                                                            <option value="completo">Intervalo completo</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <label class="mb-0">Tipo precio extra:</label>
                                                        <select class="form-control tipo_pextra" name="horario[0][intervalo][0][tipopextra]">
                                                            <option value="fijo">Precio fijo adicional</option>
                                                            <option value="multiplicacion">Multiplicador sobre el precio</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <label class="mb-0">Precio extra (€):</label>
                                                        <input
                                                            class="form-control" type="number" value="0" min="0" name="horario[0][intervalo][0][pextra]"
                                                            id="pextra" style="width: 126px;">
                                                    </div>
                                                </div>
                                                <div class="mt-2">
                                                    <a href="#" class="btn btn-success add-int-horario">Añadir intervalo horario</a>
                                                </div>

                                            </div>
                                        </div>

                                        <a href="#" class="add-dia btn btn-success mt-1 add-dia-reserva">Añadir días de reserva</a>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="form-group row">
                                <label class="col-md-2 control-label">Desactivación horas periódicas</label>
                                <div class="col-md-10">

                                    <a href="#" class="btn btn-success mt-1 add-desactivacion">Añadir desactivación</a>
                                </div>
                            </div> --}}
                            <div class="form-group row">
                                <label class="col-md-2 control-label">Número de reservas por tramo</label>
                                <input name="reservas_por_tramo" type="number" value="1"  min="1" placeholder="Reservas por tramo..."
                                    class="form-control col-md-10">
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 control-label">Días de antelación</label>
                                <input name="max_dias_antelacion" type="text" placeholder="Días máximos de antelación..." value="7" class="form-control col-md-10" required>
                            </div>
                            {{-- <div class="form-group row">
                                <label class="col-md-2 control-label">Precio por tramo (€)</label>
                                <input name="precio_por_tramo" type="number" step=".01" placeholder="Precio..."
                                    class="form-control col-md-10">
                            </div> --}}
                            <div class="form-group row">
                                <label class="col-md-2 control-label">Antelación de la reserva (horas)</label>
                                <input class="form-control col-md-10" type="number" name="atenlacion_reserva" id="atenlacion_reserva" value="0" placeholder="Antelación de reserva (horas)...">
                                {{-- <select class="form-control col-md-10" name="atenlacion_reserva" id="">
                                    @for ($i = 1; $i < 30; $i++)
                                        <option value="{{ $i }}">{{ $i }} días</option>
                                    @endfor
                                </select> --}}
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 control-label">Permite cancelación</label>
                                <select class="form-control col-md-10" name="allow_cancel" id="allow_cancel">
                                    <option value="1" >Sí</option>
                                    <option value="0" >No</option>
                                </select>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 control-label">Permite reservar varios tramos</label>
                                <select class="form-control col-md-10" name="allow_more_res" id="allow_more_res">
                                    <option value="1" >Sí</option>
                                    <option value="0" >No</option>
                                </select>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 control-label">Desactivada</label>
                                <div class="form-check mb-4">
                                    <input class="form-check-input" type="checkbox" value="0" id="active" name="active">
                                    <label class="form-check-label" for="active">
                                        Crear pista desactivada
                                    </label>
                                </div>
                            </div>
                            <div class="form-group row"><label class="col-md-2 control-label">Bloquear pista al reservar</label>
                                @foreach($pistas as $item)

                                        <div class="form-check primary ml-3">
                                            <input type="checkbox"
                                                   name="bloqueo[]" id="{{$item->nombre}}"
                                                   value="{{$item->id}}">
                                            <label for="{{$item->nombre}}">{{$item->nombre}}</label>
                                        </div>

                                @endforeach
                            </div>

                            <button class="btn btn-primary btn-lg m-b-10 mt-3" type="submit">Añadir</button>
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
        $(document).ready(function() {
            $('form').on('click', '.add-int-horario', function(e) {
                e.preventDefault();
                $(this).parent().prev().after(`<div class="row my-2 border interval-horario align-items-center" data-index="${$(this).parent().prev().data('index') + 1}">
                                                    <div class="col-md-4 mb-2"><label class="mb-0">Hora inicio: </label><input
                                                            class="form-control" type="time" name="horario[${$(this).parent().parent().parent().data('index')}][intervalo][${$(this).parent().prev().data('index') + 1}][hinicio]"
                                                            id="hora_inicio"></div>
                                                    <div class="col-md-4 mb-2"><label class="mb-0">Hora Fin: </label><input
                                                            class="form-control" type="time" name="horario[${$(this).parent().parent().parent().data('index')}][intervalo][${$(this).parent().prev().data('index') + 1}][hfin]"
                                                            id="hora_fin"></div>
                                                    <div class="col-md-4 mb-2">
                                                        <label class="mb-0">Secuencia (min):</label>
                                                        <select class="form-control" name="horario[${$(this).parent().parent().parent().data('index')}][intervalo][${$(this).parent().prev().data('index') + 1}][secuencia]" id="">
                                                            @for ($i = 1; $i < 9; $i++)
                                                                <option value="{{ $i * 15 }}">{{ $i * 15 }}</option>
                                                            @endfor
                                                            <option value="completo">Intervalo completo</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <label class="mb-0">Tipo precio extra:</label>
                                                        <select class="form-control tipo_pextra" name="horario[${$(this).parent().parent().parent().data('index')}][intervalo][${$(this).parent().prev().data('index') + 1}][tipopextra]">
                                                            <option value="fijo">Precio fijo adicional</option>
                                                            <option value="multiplicacion">Multiplicador sobre el precio</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <label class="mb-0">Precio extra (€):</label>
                                                        <input class="form-control" name="horario[${$(this).parent().parent().parent().data('index')}][intervalo][${$(this).parent().prev().data('index') + 1}][pextra]" type="number" step=".01" placeholder="Precio extra..."
                                                            class="form-control col-md-10" value="0" required>
                                                    </div>
                                                    <div>
                                                        <a href="#" class="btn btn-danger btn-borrar-intervalo">X</a>
                                                    </div>
                                                </div>`);
            });

            $('.add-dia-reserva').click(function(e) {
                e.preventDefault();
                $(this).prev().after(`<div class="ml-1 my-1 border" style="padding: 14px" data-index="${$(this).prev().data('index') + 1}">
                                            <strong class="mb-2">Horario <span>${parseInt($(this).prev().find('span').html()) + 1}</span></strong>
                                            <a href="#" class="btn btn-danger delete-horario" style="position: absolute;right:1%;">X</a>
                                            <div class="d-flex my-1" style="gap: 18px">
                                                <div class="form-check primary"><input type="checkbox" name="horario[${$(this).prev().data('index') + 1}][dias][]" id="Lunes[${$(this).prev().data('index') + 1}]" value="1"> <label for="Lunes[${$(this).prev().data('index') + 1}]">
                                                    Lunes</label> </div>
                                                <div class="form-check primary"><input type="checkbox" name="horario[${$(this).prev().data('index') + 1}][dias][]" id="Martes[${$(this).prev().data('index') + 1}]" value="2"> <label for="Martes[${$(this).prev().data('index') + 1}]">
                                                    Martes</label> </div>
                                                <div class="form-check primary"><input type="checkbox" name="horario[${$(this).prev().data('index') + 1}][dias][]" id="Miércoles[${$(this).prev().data('index') + 1}]" value="3"> <label for="Miércoles[${$(this).prev().data('index') + 1}]">
                                                    Miércoles</label> </div>
                                                <div class="form-check primary"><input type="checkbox" name="horario[${$(this).prev().data('index') + 1}][dias][]" id="Jueves[${$(this).prev().data('index') + 1}]" value="4"> <label for="Jueves[${$(this).prev().data('index') + 1}]">
                                                    Jueves</label> </div>
                                                <div class="form-check primary"><input type="checkbox" name="horario[${$(this).prev().data('index') + 1}][dias][]" id="Viernes[${$(this).prev().data('index') + 1}]" value="5"> <label for="Viernes[${$(this).prev().data('index') + 1}]">
                                                    Viernes</label> </div>
                                                <div class="form-check primary"><input type="checkbox" name="horario[${$(this).prev().data('index') + 1}][dias][]" id="Sábado[${$(this).prev().data('index') + 1}]" value="6"> <label for="Sábado[${$(this).prev().data('index') + 1}]">
                                                    Sábado</label> </div>
                                                <div class="form-check primary"><input type="checkbox" name="horario[${$(this).prev().data('index') + 1}][dias][]" id="Domingo[${$(this).prev().data('index') + 1}]" value="7"> <label for="Domingo[${$(this).prev().data('index') + 1}]">
                                                    Domingo</label> </div>
                                            </div>
                                            <div>
                                                <div class="row my-2 border interval-horario" data-index="0">
                                                    <div class="col-md-4 mb-2"><label class="mb-0">Hora inicio: </label><input
                                                            class="form-control" type="time" name="horario[${$(this).prev().data('index') + 1}][intervalo][0][hinicio]"
                                                            id="hora_inicio"></div>
                                                    <div class="col-md-4 mb-2"><label class="mb-0">Hora Fin: </label><input
                                                            class="form-control" type="time" name="horario[${$(this).prev().data('index') + 1}][intervalo][0][hfin]"
                                                            id="hora_fin"></div>
                                                    <div class="col-md-4 mb-2">
                                                        <label class="mb-0">Secuencia (min):</label>
                                                        <select class="form-control" name="horario[${$(this).prev().data('index') + 1}][intervalo][0][secuencia]" id="">
                                                            @for ($i = 1; $i < 9; $i++)
                                                                <option value="{{ $i * 15 }}">{{ $i * 15 }}</option>
                                                            @endfor
                                                            <option value="completo">Intervalo completo</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <label class="mb-0">Tipo precio extra:</label>
                                                        <select class="form-control tipo_pextra" name="horario[${$(this).prev().data('index') + 1}][intervalo][0][tipopextra]">
                                                            <option value="fijo">Precio fijo adicional</option>
                                                            <option value="multiplicacion">Multiplicador sobre el precio</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <label class="mb-0">Precio extra (€):</label>
                                                        <input class="form-control" name="horario[${$(this).prev().data('index') + 1}][intervalo][0][pextra]"type="number" step=".01" placeholder="Precio extra..."
                                                            class="form-control" value="0" required>
                                                    </div>
                                                </div>
                                                <div class="mt-2">
                                                    <a href="#" class="btn btn-success add-int-horario">Añadir intervalo horario</a>
                                                </div>

                                            </div>
                                        </div>`);
            });

            /* $('form').on('click', '.add-desactivacion', function(e) {
                e.preventDefault();
                $(this).before(`<div class="my-1 mb-3 border" style="padding: 14px" data-index="${$(this).prev().data('index') ? $(this).prev().data('index') + 1 : 0}">
                                        <select required class="full-width select2 select-desactivacion"
                                            data-init-plugin="select2" name="desactivaciones[${$(this).prev().data('index') ? $(this).prev().data('index') + 1 : 0}][dias][]"
                                            multiple>
                                            <option></option>
                                            <option value="1">Lunes</option>
                                            <option value="2">Martes</option>
                                            <option value="3">Miércoles</option>
                                            <option value="4">Jueves</option>
                                            <option value="5">Viernes</option>
                                            <option value="6">Sábado</option>
                                            <option value="7">Domingo</option>
                                        </select>
                                        <div class="d-flex mt-3">
                                            <div class="w-100">
                                                <label class="mb-1">Hora inicio</label>
                                                <input required type="time"  name="desactivaciones[${$(this).prev().data('index') ? $(this).prev().data('index') + 1 : 0}][hora_inicio]" class="form-control">

                                            </div>
                                            <div class="w-100">
                                                <label class="mb-1">Hora fin</label>
                                                <input required type="time"  name="desactivaciones[${$(this).prev().data('index') ? $(this).prev().data('index') + 1 : 0}][hora_fin]" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                `).prev().find('.select2').select2({placeholder: "Selecciona días..."});
            });
 */
            $('form').on('click', '.btn-borrar-intervalo', function(e) {
                e.preventDefault();
                $(this).parent().parent().remove();
            });

            $('form').on('click', '.delete-horario', function(e) {
                e.preventDefault();
                $(this).parent().remove();
            });

            $('form').on('change', '.tipo_pextra', function() {
                if ($(this).find('option:selected').val()=='fijo') {
                $(this).parent().next().find('label').html('Precio extra (€):');
                } else{
                    $(this).parent().next().find('label').html('Multiplicador sobre el precio (%): ');
                }
            });
        });
    </script>
@endsection
