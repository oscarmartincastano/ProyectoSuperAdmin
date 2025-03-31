@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-lg-12 m-b-10">

            <div class="p-l-20 p-r-20 p-b-10 pt-3">
                <div>
                    <h3 class="text-primary no-margin">Editar "{{ $pista->nombre }}"</h3>
                </div>
            </div>

            <div class="p-t-15 p-b-15 p-l-20 p-r-20">
                <div class="card ">
                    <div class="card-header">
                        <div class="card-title">Información</div>
                    </div>
                    <div class="card-body">
                        <form
                            action="{{ route('edit_pista', ['id' => $pista->id, 'slug_instalacion' => $pista->instalacion->slug]) }}"
                            method="post" role="form" class="form-horizontal">
                            @csrf
                            <div class="form-group row">
                                <label class="col-md-2 control-label">Nombre</label>
                                <input value="{{ $pista->nombre }}" name="nombre" type="text" placeholder="Nombre..."
                                    class="form-control col-md-10" required>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 control-label">Nombre corto</label>
                                <input value="{{ $pista->nombre_corto }}" name="nombre_corto" type="text" placeholder="Nombre corto..."
                                    class="form-control col-md-10">
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 control-label">Precio (€)</label>
                                <input value="{{ $pista->precio }}" name="precio" type="number" placeholder="Precio..."
                                    class="form-control col-md-10">
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 control-label">Deporte</label>
                                <select class="form-control col-md-10" name="id_deporte">
                                    @foreach (\App\Models\Deporte::all() as $item)
                                        <option {{ $pista->id_deporte == $item->id  ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 control-label">Tipo</label>
                                <select class="form-control col-md-10" name="tipo">
                                    @if (auth()->user()->instalacion->id == 4)
                                        <option {{ $pista->tipo == 'C.C. Centro' ? 'selected' : '' }}  value="C.C. Centro">C.C. Centro</option>
                                        <option {{ $pista->tipo == 'C.C. Arrabal del Sur' ? 'selected' : '' }}  value="C.C. Arrabal del Sur">C.C. Arrabal del Sur</option>
                                        <option {{ $pista->tipo == 'C.C. Fuensanta' ? 'selected' : '' }}  value="C.C. Fuensanta">C.C. Fuensanta</option>
                                        <option {{ $pista->tipo == 'C.C. de Iniciativas Culturales Osio' ? 'selected' : '' }}  value="C.C. de Iniciativas Culturales Osio">C.C. de Iniciativas Culturales Osio</option>
                                        <option {{ $pista->tipo == 'C.C. Norte' ? 'selected' : '' }}  value="C.C. Norte">C.C. Norte</option>
                                        <option {{ $pista->tipo == 'C.C. El Naranjo' ? 'selected' : '' }}  value="C.C. El Naranjo">C.C. El Naranjo</option>
                                        <option {{ $pista->tipo == 'C.C. Levante' ? 'selected' : '' }}  value="C.C. Levante">C.C. Levante</option>
                                        <option {{ $pista->tipo == 'C.C. Lepanto' ? 'selected' : '' }}  value="C.C. Lepanto">C.C. Lepanto</option>
                                        <option {{ $pista->tipo == 'C.C. Moreras' ? 'selected' : '' }}  value="C.C. Moreras">C.C. Moreras</option>
                                        <option {{ $pista->tipo == 'C.C. Sociocultural El Parque' ? 'selected' : '' }}  value="C.C. Sociocultural El Parque">C.C. Sociocultural El Parque</option>
                                        <option {{ $pista->tipo == 'C.C. Vallehermoso' ? 'selected' : '' }}  value="C.C. Vallehermoso">C.C. Vallehermoso</option>
                                        <option {{ $pista->tipo == 'C.C. Poniente Sur' ? 'selected' : '' }}  value="C.C. Poniente Sur">C.C. Poniente Sur</option>
                                        <option {{ $pista->tipo == 'C.C. Sebastián Cuevas' ? 'selected' : '' }}  value="C.C. Sebastián Cuevas">C.C. Sebastián Cuevas</option>
                                        <option {{ $pista->tipo == 'C.C. Chari Navarro' ? 'selected' : '' }}  value="C.C. Chari Navarro">C.C. Chari Navarro</option>
                                        <option {{ $pista->tipo == 'C.C. Cerro Muriano' ? 'selected' : '' }}  value="C.C. Cerro Muriano">C.C. Cerro Muriano</option>
                                        <option {{ $pista->tipo == 'C.C. Santa Cruz' ? 'selected' : '' }}  value="C.C. Santa Cruz">C.C. Santa Cruz</option>
                                        <option {{ $pista->tipo == 'C.C. Rafael Villar' ? 'selected' : '' }}  value="C.C. Rafael Villar">C.C. Rafael Villar</option>
                                        <option {{ $pista->tipo == 'C.C. Villarrubia' ? 'selected' : '' }}  value="C.C. Villarrubia">C.C. Villarrubia</option>
                                        <option {{ $pista->tipo == 'C.C. Trassierra' ? 'selected' : '' }}  value="C.C. Trassierra">C.C. Trassierra</option>
                                    @else
                                        <option {{ $pista->tipo == 'Tenis' ? 'selected' : '' }} value="Tenis">Tenis</option>
                                        <option {{ $pista->tipo == 'Pádel' ? 'selected' : '' }} value="Pádel">Pádel</option>
                                        <option {{ $pista->tipo == 'Fútbol' ? 'selected' : '' }} value="Fútbol">Fútbol</option>
                                        <option {{ $pista->tipo == 'Fútbol Sala' ? 'selected' : '' }} value="Fútbol Sala">Fútbol Sala</option>
                                        <option {{ $pista->tipo == 'Baloncesto' ? 'selected' : '' }} value="Baloncesto">Baloncesto</option>
                                        <option {{ $pista->tipo == 'Ping pong' ? 'selected' : '' }} value="Ping pong">Ping pong</option>
                                        <option {{ $pista->tipo == 'Sala' ? 'selected' : '' }} value="Sala">Sala</option>
                                        <option {{ $pista->tipo == 'Pabellon' ? 'selected' : '' }} value="Pabellon">Pabellón</option>
                                        <option {{ $pista->tipo == 'Gimnasio' ? 'selected' : '' }} value="Gimnasio">Gimnasio</option>
                                        <option {{ $pista->tipo == 'Atletismo' ? 'selected' : '' }} value="Atletismo">Atletismo</option>
                                        <option {{ $pista->tipo == 'Piscina' ? 'selected' : '' }} value="Piscina">Piscina</option>
                                        <option {{ $pista->tipo == 'Mercadillo' ? 'selected' : '' }} value="Mercadillo">Mercadillo</option>
                                        <option {{ $pista->tipo == 'Padbol' ? 'selected' : '' }} value="Padbol">Padbol</option>
                                        <option {{ $pista->tipo == 'Bádminton' ? 'selected' : '' }} value="Bádminton">Bádminton</option>
                                    @endif
                                </select>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 control-label">Horario para alquiler</label>
                                <div class="col-md-10">
                                    <div>
                                        @foreach (unserialize($pista->horario) as $index => $horario)
                                            <div class="my-1 border" style="padding: 14px"
                                                data-index="{{ $index }}">
                                                @if ($loop->index != 0)
                                                    <a href="#" class="btn btn-danger delete-horario"
                                                        style="position: absolute;right:1%;">X</a>
                                                @endif
                                                <strong class="mb-2">Horario
                                                    <span>{{ $loop->index + 1 }}</span></strong>
                                                <div class="row my-1" style="gap: 18px">
                                                    <div class="form-check primary"><input type="checkbox"
                                                            name="horario[{{ $index }}][dias][]" id="Lunes[0]"
                                                            value="1" @if (in_array('1', $horario['dias'])) checked @endif>
                                                        <label for="Lunes[0]">
                                                            Lunes</label>
                                                    </div>
                                                    <div class="form-check primary"><input type="checkbox"
                                                            name="horario[{{ $index }}][dias][]" id="Martes[0]"
                                                            value="2" @if (in_array('2', $horario['dias'])) checked @endif>
                                                        <label for="Martes[0]">
                                                            Martes</label>
                                                    </div>
                                                    <div class="form-check primary"><input type="checkbox"
                                                            name="horario[{{ $index }}][dias][]" id="Miércoles[0]"
                                                            value="3" @if (in_array('3', $horario['dias'])) checked @endif>
                                                        <label for="Miércoles[0]">
                                                            Miércoles</label>
                                                    </div>
                                                    <div class="form-check primary"><input type="checkbox"
                                                            name="horario[{{ $index }}][dias][]" id="Jueves[0]"
                                                            value="4" @if (in_array('4', $horario['dias'])) checked @endif>
                                                        <label for="Jueves[0]">
                                                            Jueves</label>
                                                    </div>
                                                    <div class="form-check primary"><input type="checkbox"
                                                            name="horario[{{ $index }}][dias][]" id="Viernes[0]"
                                                            value="5" @if (in_array('5', $horario['dias'])) checked @endif>
                                                        <label for="Viernes[0]">
                                                            Viernes</label>
                                                    </div>
                                                    <div class="form-check primary"><input type="checkbox"
                                                            name="horario[{{ $index }}][dias][]" id="Sábado[0]"
                                                            value="6" @if (in_array('6', $horario['dias'])) checked @endif>
                                                        <label for="Sábado[0]">
                                                            Sábado</label>
                                                    </div>
                                                    <div class="form-check primary"><input type="checkbox"
                                                            name="horario[{{ $index }}][dias][]" id="Domingo[0]"
                                                            value="7" @if (in_array('7', $horario['dias'])) checked @endif>
                                                        <label for="Domingo[0]">
                                                            Domingo</label>
                                                    </div>
                                                </div>
                                                <div>
                                                    @foreach ($horario['intervalo'] as $intindice => $intervalo)
                                                        <div class="row my-2 p-2 border interval-horario align-items-center"
                                                            {{-- style="gap: 18px" --}} data-index="{{ $intindice }}">
                                                            <div class="col-md-4 mb-2"><label class="mb-0">Hora inicio: </label><input
                                                                    class="form-control" type="time"
                                                                    name="horario[{{ $index }}][intervalo][{{ $intindice }}][hinicio]"
                                                                    id="hora_inicio" value="{{ $intervalo['hinicio'] }}">
                                                            </div>
                                                            <div class="col-md-4 mb-2"><label class="mb-0">Hora Fin: </label><input
                                                                    class="form-control" type="time"
                                                                    name="horario[{{ $index }}][intervalo][{{ $intindice }}][hfin]"
                                                                    id="hora_fin" value="{{ $intervalo['hfin'] }}"></div>
                                                            <div class="col-md-4 mb-2">
                                                                <label class="mb-0">Secuencia (min):</label>
                                                                <select class="form-control"
                                                                    name="horario[{{ $index }}][intervalo][{{ $intindice }}][secuencia]"
                                                                    id="">
                                                                    @for ($i = 1; $i < 9; $i++)
                                                                        <option value="{{ $i * 15 }}"
                                                                            @if ($intervalo['secuencia'] == $i * 15) selected @endif>
                                                                            {{ $i * 15 }}</option>
                                                                    @endfor
                                                                    <option @if ($intervalo['secuencia'] > 120 || $intervalo['secuencia']%15 != 0) selected @endif value="completo">Intervalo completo</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                                <label class="mb-0">Tipo precio extra:</label>
                                                                <select class="form-control tipo_pextra" name="horario[{{ $index }}][intervalo][{{ $intindice }}][tipopextra]">
                                                                    <option @if (isset($intervalo['tipopextra']) && $intervalo['tipopextra'] == 'fijo') selected @endif value="fijo">Precio fijo adicional</option>
                                                                    <option @if (isset($intervalo['tipopextra']) && $intervalo['tipopextra'] == 'multiplicacion') selected @endif value="multiplicacion">Multiplicador sobre el precio</option>
                                                                </select>
                                                                {{-- <input name="horario[{{ $index }}][intervalo][{{ $intindice }}][pextra]" type="number" step=".01" placeholder="Precio extra..."
                                                                    class="form-control" value="{{ $intervalo['pextra'] ?? 0 }}" required> --}}
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                                <label class="mb-0">@if (isset($intervalo['tipopextra']) && $intervalo['tipopextra'] == 'multiplicacion') Multiplicador sobre el precio (%): @else Precio extra (€): @endif</label>
                                                                <input name="horario[{{ $index }}][intervalo][{{ $intindice }}][pextra]" type="number" step=".01" placeholder="Precio extra..."
                                                                    class="form-control pextra" value="{{ $intervalo['pextra'] ?? 0 }}" required>
                                                            </div>
                                                            @if ($loop->index != 0)
                                                                <div>
                                                                    <a href="#"
                                                                        class="btn btn-danger btn-borrar-intervalo">X</a>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                    <div class="mt-2">
                                                        <a href="#" class="btn btn-success add-int-horario">Añadir intervalo
                                                            horario</a>
                                                    </div>

                                                </div>
                                            </div>
                                        @endforeach

                                        <a href="#" class="add-dia btn btn-success mt-1 add-dia-reserva">Añadir días de
                                            reserva</a>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="form-group row">
                                <label class="col-md-2 control-label">Desactivación horas periódicas</label>
                                <div class="col-md-10">
                                    <div class="my-1 mt-3 border" style="padding: 14px" data-index="{{ $index }}">
                                        <div class="row my-1" style="gap: 18px">
                                            <div class="form-check primary">
                                                <input type="checkbox">
                                                <label>Lunes</label>
                                            </div>
                                            <div class="form-check primary">
                                                <input type="checkbox">
                                                <label>Martes</label>
                                            </div>
                                            <div class="form-check primary">
                                                <input type="checkbox">
                                                <label>Miércoles</label>
                                            </div>
                                            <div class="form-check primary">
                                                <input type="checkbox">
                                                <label>Jueves</label>
                                            </div>
                                            <div class="form-check primary">
                                                <input type="checkbox">
                                                <label> Viernes</label>
                                            </div>
                                            <div class="form-check primary">
                                                <input type="checkbox">
                                                <label>Sábado</label>
                                            </div>
                                            <div class="form-check primary">
                                                <input type="checkbox">
                                                <label>Domingo</label>
                                            </div>
                                        </div>
                                        <select required class="full-width select2 select-desactivacion"
                                            data-init-plugin="select2" name="desactivaciones[${$(this).prev().data('index') + 1}]" id="desactivaciones[]"
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
                                                <input type="time" name="hora" class="form-control">

                                            </div>
                                            <div class="w-100">
                                                <label class="mb-1">Hora fin</label>
                                                <input type="time" name="hora" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <a href="#" class="btn btn-success mt-1 add-desactivacion">Añadir desactivación</a>
                                </div>
                            </div> --}}
                            {{-- <div class="form-group row">
                                <label class="col-md-2 control-label">Precio por tramo (€)</label>
                                <input name="precio_por_tramo" type="number" step=".01" placeholder="Precio..."
                                    class="form-control col-md-10">
                            </div> --}}
                            <div class="form-group row">
                                <label class="col-md-2 control-label">Número de reservas por tramo</label>
                                <input name="reservas_por_tramo" type="number" value="{{ $pista->reservas_por_tramo }}"
                                    min="1" placeholder="Reservas por tramo..." class="form-control col-md-10">
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 control-label">Permite cancelación</label>
                                <select class="form-control col-md-10" name="allow_cancel" id="allow_cancel">
                                    <option value="1" @if ($pista->allow_cancel) selected @endif>Sí</option>
                                    <option value="0" @if (!$pista->allow_cancel) selected @endif>No</option>
                                </select>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 control-label">Días de antelación</label>
                                <input name="max_dias_antelacion" type="text" placeholder="Días máximos de antelación..." class="form-control col-md-10" value="{{ $pista->max_dias_antelacion }}" required>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 control-label">Antelación de la reserva (horas)</label>
                                <input class="form-control col-md-10" type="number" name="atenlacion_reserva"
                                    id="atenlacion_reserva" value="{{ $pista->atenlacion_reserva }}" required>
                                {{-- <select class="form-control col-md-10" name="atenlacion_reserva" id="">
                                    @for ($i = 1; $i < 30; $i++)
                                        <option value="{{ $i }}" @if ($pista->atenlacion_reserva == $i) selected @endif>{{ $i }} días</option>
                                    @endfor
                                </select> --}}
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 control-label">Permite reservar varios tramos</label>
                                <select class="form-control col-md-10" name="allow_more_res" id="">
                                    <option value="0" @if (!$pista->allow_more_res) selected @endif>No
                                    </option>
                                    <option value="1" @if ($pista->allow_more_res) selected @endif>Sí
                                    </option>
                                </select>
                            </div>
                            <div class="form-group row"><label class="col-md-2 control-label">Bloquear pista al reservar</label>
                                @foreach($pistas as $item)
                                    @if($item->id != $pista->id)
                                    <div class="form-check primary ml-3">
                                <input type="checkbox"
                                       name="bloqueo[]" id="{{$item->nombre}}"
                                       value="{{$item->id}}"
                                       @if(isset($pista->bloqueo))
                                        @foreach(unserialize($pista->bloqueo) as $pistabloqueo)
                                            @if($pistabloqueo == $item->id)
                                            checked
                                            @endif
                                        @endforeach
                                       @endif
                                >
                                        <label for="{{$item->nombre}}">{{$item->nombre}}</label>
                                    </div>
                                    @endif
                            @endforeach
                            </div>

                            <button class="btn btn-primary btn-lg m-b-10 mt-3" type="submit">Editar</button>
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
                $(this).before(`<div class="my-1 mt-3 border" style="padding: 14px" data-index="${$(this).prev().data('index') + 1}">
                                        <select required class="full-width select2 select-desactivacion"
                                            data-init-plugin="select2" name="desactivaciones[${$(this).prev().data('index') + 1}]" id="desactivaciones[]"
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
                                                <input type="time" name="hora" class="form-control">

                                            </div>
                                            <div class="w-100">
                                                <label class="mb-1">Hora fin</label>
                                                <input type="time" name="hora" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                `).prev().find('.select2').select2({placeholder: "Selecciona días..."});
            }); */

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
            /* $(".select2").select2({
                placeholder: "Selecciona días..."
            }); */
        });
    </script>
@endsection
