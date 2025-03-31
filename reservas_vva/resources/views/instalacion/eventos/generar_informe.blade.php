@extends('layouts.admin')

@section('pagename', 'Generar informes participantes')
@section('content')
    <div class="row">
        <div class="col-lg-12 m-b-10">

            <div class="p-l-20 p-r-20 p-b-10 pt-3">
                <div>
                    <h3 class="text-primary no-margin">Informes participantes</h3>
                </div>
            </div>

            <div class="p-t-15 p-b-15 p-l-20 p-r-20">
                <div class="card ">
                    <div class="card-header">
                        <div class="card-title">Generar informe</div>
                    </div>
                    <div class="card-body">
                        <form action="#" method="get" role="form" enctype="multipart/form-data">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>Fecha Inicio</label>
                                    <input name="fecha_inicio" type="date" class="form-control" value="{{ date('Y-m-d') }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Fecha Fin</label>
                                    <input name="fecha_fin" type="date" class="form-control" value="{{ date('Y-m-d') }}">
                                </div>
                            </div>
                            <div class="row">
                                {{-- <div class="form-group col-md-6">
                                    <label>Tipo de transacción</label>
                                    <select name="tipo" class="form-control">
                                        <option>---</option>
                                        <option value="tarjeta">Tarjeta</option>
                                        <option value="En proceso">Pendiente pago</option>
                                    </select>
                                </div> --}}
                                <div class="form-group col-md-6">
                                    <label>Evento</label>
                                    <select name="tipo_evento" class="form-control">
                                        <option>---</option>
                                        @foreach (auth()->user()->instalacion->eventos_all as $item)
                                            <option value="{{ $item }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                {{--<div class="form-group col-md-6">
                                    <label>Mes</label>
                                    <select name="meses" class="form-control">
                                        <option>---</option>
                                        @foreach ($meses as $index => $item)

                                            <option value="{{$item->num_mes}}">{{ strftime('%B %Y', strtotime('01-' . $item->num_mes . '-'.$item->num_year)) }}</option>
                                        @endforeach
                                    </select>
                                </div> --}}
                            </div>
                            <div class="row">
                                <button class="btn btn-primary btn-lg m-b-10 mt-3 w-100" type="submit">Generar</button>
                            </div>
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
            
            
        });
    </script>
@endsection