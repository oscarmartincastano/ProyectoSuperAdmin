@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-lg-12 m-b-10">

            <div class="p-l-20 p-r-20 p-b-10 pt-3">
                <div>
                    <h3 class="text-primary no-margin">Añadir desactivación periódica</h3>
                </div>
            </div>

            <div class="p-t-15 p-b-15 p-l-20 p-r-20">
                <div class="card ">
                    <div class="card-header">
                        <div class="card-title">Información</div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('add_desactivacion', ['slug_instalacion' => request()->slug_instalacion]) }}"
                            method="post" role="form" class="form-horizontal">
                            @csrf
                            <div class="form-group">
                                <label for="espacio">Espacio</label>
                                <select required class="full-width form-control" name="espacio">
                                    @foreach (auth()->user()->instalacion->pistas as $item)
                                        <option value="{{ $item->id }}">{{ count(auth()->user()->instalacion->deportes) > 1 ? $item->tipo . '.' : '' }} {{ $item->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="fecha_inicio">Fecha inicio</label>
                                <input type="date" class="form-control" placeholder="Fecha inicio" name="fecha_inicio" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="form-group">
                                <label for="fecha_fin">Fecha fin</label>
                                <input type="date" class="form-control" placeholder="Fecha fin" name="fecha_fin" required>
                            </div>
                            <div class="form-group">
                                <label for="tipo">Días</label>
                                <select required class="full-width select2 select-desactivacion" data-init-plugin="select2" name="dias[]" id="desactivaciones[]" multiple>
                                    <option></option>
                                    <option value="1">Lunes</option>
                                    <option value="2">Martes</option>
                                    <option value="3">Miércoles</option>
                                    <option value="4">Jueves</option>
                                    <option value="5">Viernes</option>
                                    <option value="6">Sábado</option>
                                    <option value="0">Domingo</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="hora_inicio">Hora inicio</label>
                                <input type="time" class="form-control" placeholder="Hora inicio" name="hora_inicio" required>
                            </div>
                            <div class="form-group">
                                <label for="hora_fin">Hora fin</label>
                                <input type="time" class="form-control" placeholder="Hora fin" name="hora_fin" required>
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
           
            $(".select2").select2({
                placeholder: "Selecciona días..."
            });
        });
    </script>
@endsection