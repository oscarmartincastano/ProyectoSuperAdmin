@extends('layouts.admin')

@section('pagename', 'Generar informes pedidos')
@section('content')
    <div class="row">
        <div class="col-lg-12 m-b-10">

            <div class="p-l-20 p-r-20 p-b-10 pt-3">
                <div>
                    <h3 class="text-primary no-margin">Informes pedidos</h3>
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
                                <div class="form-group col-md-6">
                                    <label>Tipo de transacción</label>
                                    <select name="tipo" class="form-control">
                                        <option>---</option>
                                        <option value="tarjeta">Tarjeta</option>
                                        <option value="efectivo">Efectivo</option>
                                        <option value="bono">Bono</option>
                                        <option value="En proceso">Pendiente pago</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Tipo de espacio</label>
                                    <select name="tipo_espacio" class="form-control">
                                        <option>---</option>
                                        @foreach (auth()->user()->instalacion->deportes as $item)
                                            <option value="{{ $item }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            {{-- <div class="row">
                                <div class="form-group col-md-6">
                                    <label>Cliente</label>
                                    <select class="full-width select2 select-socio" data-init-plugin="select2" name="id_socio">
                                        <option></option>
                                        @foreach ($socios as $item)
                                            @if ($item->numero)
                                                <option data-numero="{{ $item->numero }}" value="{{ $item->id }}">Socio nº {{ $item->numero }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Usuario</label>
                                    <select class="full-width select2 select-cliente" data-init-plugin="select2" name="id_usuario" id="id_usuario">
                                        <option></option>
                                    </select>
                                </div>
                            </div> --}}
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