@extends('layouts.admin')

@section('style')
    <style>
        .col-form-label {
            font-weight: bold;
        }
        thead td{
            font-weight: bold;
        }
        td{
            padding: .75rem !important;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 m-b-10">

            <div class="p-l-20 p-r-20 p-b-10 pt-3">
                <div>
                    <h3 class="text-primary no-margin">Configuraciones</h3>
                </div>
            </div>

            <div class="p-t-15 p-b-15 p-l-20 p-r-20">
                <div class="card ">
                    <div class="card-header">
                        <div class="card-title">Espacios y Reservas</div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('edit_config', ['slug_instalacion' => $instalacion->slug]) }}" method="post">
                            @csrf
                    
                            <!-- Campo: Observaciones -->
                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" value="1" id="observaciones" name="observaciones"
                                    @if ($instalacion->configuracion?->observaciones) checked @endif>
                                <label class="form-check-label" for="observaciones">
                                    Añadir campo de observaciones en la reserva
                                </label>
                            </div>
                    
                            <!-- Campo: Permitir cancelar reservas -->
                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" value="1" id="allow_cancel" name="allow_cancel"
                                    @if ($instalacion->configuracion?->allow_cancel) checked @endif>
                                <label class="form-check-label" for="allow_cancel">
                                    Permitir cancelar reservas
                                </label>
                            </div>
                    
                            <!-- Campo: Bloquear el día de hoy -->
                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" value="1" id="block_today" name="block_today"
                                    @if ($instalacion->configuracion?->block_today) checked @endif>
                                <label class="form-check-label" for="block_today">
                                    Bloquear el día de hoy
                                </label>
                            </div>
                    
                            <!-- Campo: Reservas de lista de espera -->
                            <div class="form-group mb-4">
                                <label for="reservas_lista_espera">Nº de reservas permitidas de lista de espera</label>
                                <input type="number" class="form-control" name="reservas_lista_espera" id="reservas_lista_espera"
                                    value="{{ $instalacion->configuracion?->reservas_lista_espera ?? '' }}">
                            </div>
                            
                            <!-- Campo: Reservas máximas por tipo de espacio -->
                            <div class="form-group mb-4">
                                <label for="max_reservas_tipo_espacio">Reservas máximas por usuario para cada tipo de espacio</label>
                                <div class="border p-3">
                                    @foreach ($instalacion->deportes as $tipo_espacio)
                                        <label for="max_reservas_tipo_espacio[{{ $tipo_espacio }}]">{{ $tipo_espacio }}</label>
                                        <input type="number" class="form-control" name="max_reservas_tipo_espacio[{{ $tipo_espacio }}]" id="max_reservas_tipo_espacio[{{ $tipo_espacio }}]"
                                            value="{{ unserialize($instalacion->configuracion?->max_reservas_tipo_espacio ?? '')[$tipo_espacio] ?? '' }}">
                                    @endforeach
                                </div>
                            </div>
                    
                            <input type="submit" value="Editar" class="btn btn-primary btn-lg m-b-10 mt-3 mt-2">
                        </form>
                    </div>
                </div>
                <p class="small no-margin">
                </p>
            </div>
        </div>
    </div>
@endsection
