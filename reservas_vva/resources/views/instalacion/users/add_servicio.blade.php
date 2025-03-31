@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-lg-12 m-b-10">

            <div class="p-l-20 p-r-20 p-b-10 pt-3">
                <div>
                    <h3 class="text-primary no-margin">Añadir servicio para "{{ $user->name }}"</h3>
                </div>
            </div>

            <div class="p-t-15 p-b-15 p-l-20 p-r-20">
                <div class="card ">
                    <div class="card-header">
                        <div class="card-title">Información</div>
                    </div>
                    <div class="card-body">
                        <form method="post" role="form">
                            @csrf
                            <div class="form-group row">
                                <label>Servicio</label>
                                <select name="servicio" class="form-control" required>
                                    <option value="" disabled selected>Selecciona un servicio</option>
                                    @foreach($servicios as $servicio)
                                        <option value="{{ $servicio->id }}">{{ $servicio->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group row">
                                <label>Mes</label>
                                <select name="mes" class="form-control" required>
                                    <option value="" disabled selected>Selecciona un mes</option>
                                    @foreach($meses as $index => $mes)
                                        <option value="{{ $index + 1 }}">{{ $mes }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group row">
                                <label>Año</label>
                                <select name="anio" class="form-control" required>
                                    <option value="" disabled selected>Selecciona un año</option>
                                    @foreach($anios as $anio)
                                        <option value="{{ $anio }}">{{ $anio }}</option>
                                    @endforeach
                                </select>
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
