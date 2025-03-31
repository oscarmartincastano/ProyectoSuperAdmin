@extends('layouts.userview')

@section('pagename', 'Mis reservas')

@section('style')
    <style>
        .reserva{
            margin-top: 25px;
        }
        .reserva .card{
            color: white;
            border-radius: 5px;
        }
        select.form-control{
            height: calc(1em + 0.75rem + 2px);
            padding: 0;
            padding-left: 6px;
            font-size: 13px;
            font-weight: bold;
        }
        label.col-form-label{
            font-weight: bold;
        }
        h1{
            font-size: 1.1rem;
            font-weight: bold;
        }
        .horario{
            font-weight: bold;
        }
        .fecha-title{
            font-size: 15px;
        }
        .boton-cancelar{
            position: absolute;
            right: 10px;
            bottom: 5px;
        }
        .h2{
            font-size: 1.7rem;
            font-weight: bold;
            margin-bottom: 0;
        }
        .table-reservas thead{
            font-weight: bold;
        }
        #DataTables_Table_0_length > label > select{
            padding-right: 24px;
        }
        .pagination{
            margin-top: 15px !important;
        }
        nav.navbar{
            margin-bottom: 25px;
        }
        @media (min-width: 1215px) {
            table.table-reservas{
                display: table;
                border: 0;
            }
        }
        @media (max-width: 900px) {
            table.table-reservas{
                font-size: 14px;
            }
            a.cancel {
                font-size: 14px;
                padding: 1px 8px;
            }
        }
    </style>
@endsection

@section('content')

<div class="container is-max-desktop mt-2">
    @php
        $checkPendiente = check_pendientePago(auth()->user()->id);
    @endphp
    <div class="container mt-3">
        <h1 class="title titulo-pagina">Mis Reservas</h1>
        @if($checkPendiente > 0)
        <div class="alert alert-warning" role="alert">
            Tienes {!! $checkPendiente !!} reservas pendientes de pago. En 5 minutos expirarán y serán liberadas las plazas.
        </div>
        @endif
        <div class="card" style="box-shadow: none">
            <div class="card-body">
                <div class="list-reservas row">
                     
                    {{-- <h2 class="h2 text-success">Reservas activas</h2> --}}
                    {{-- @if (count(auth()->user()->reservas_activas) == 0)
                        <p class="mt-3 mb-4">No hay reservas activas actualmente.</p>
                    @endif
                    @foreach (auth()->user()->reservas_activas as $item)
                        <div class="col-md-6 reserva">
                            <div class="card" style="background:url(/img/deportes/fondo-{{ strtolower($item->pista->tipo) }}.jpg);background-size: cover; ">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <h1><i class="far fa-calendar-check mr-2"></i> {{ date('d-m-Y', $item->timestamp) }}</h1>
                                        <h1><i class="far fa-clock mr-2"></i> {{ date('H:i', $item->timestamp) }} a {{ date('H:i',strtotime (date('H:i', $item->timestamp) . " +{$item->minutos_totales} minutes")) }}</h1> 
                                    </div>
                                    <div class="form-group row mt-5 mb-2">
                                        <label class="col-sm-3 col-form-label py-0">Deporte:</label>
                                        <div class="col-sm-9">
                                            <div>{{ $item->pista->tipo }}</div>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-2">
                                        <label class="col-sm-3 col-form-label py-0">Espacio:</label>
                                        <div class="col-sm-9">
                                            <div>{{ $item->pista->nombre }}</div>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-2">
                                        <label class="col-sm-3 col-form-label py-0">Fecha:</label>
                                        <div class="col-sm-9">
                                            <div>{{ date('d/m/Y', $item->timestamp) }}</div>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-2">
                                        <label class="col-sm-3 col-form-label py-0">Hora:</label>
                                        <div class="col-sm-9">
                                            <div>{{ date('H:i', $item->timestamp) }} - {{ date('H:i',strtotime (date('H:i', $item->timestamp) . " +{$item->minutos_totales} minutes")) }}</div>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-2">
                                        <label class="col-sm-3 col-form-label py-0">Minutos totales:</label>
                                        <div class="col-sm-9">
                                            <div>{{ $item->minutos_totales }} minutos</div>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-2">
                                        <label class="col-sm-3 col-form-label py-0">Reserva realizada:</label>
                                        <div class="col-sm-9">
                                            <div>{{ date('d/m/Y, H:i', strtotime($item->created_at)) }}</div>
                                        </div>
                                    </div>
                                    @if (auth()->user()->instalacion->configuracion->allow_cancel)
                                        <div class="form-group row mt-4 mb-2 boton-cancelar">
                                            <div class="col-sm-12 text-right d-flex justify-content-end" style="gap: 14px">
                                                <form action="/mis-reservas/{{ $item->id }}/cancel" method="post">
                                                    @csrf
                                                    <button class="cancel btn btn-danger">
                                                        <i class="fas fa-times"></i> Cancelar
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                    @if (count(auth()->user()->reservas_pasadas) > 0)
                        <h2 class="h2 mt-5">Reservas pasadas</h2>
                    @endif
                    @foreach (auth()->user()->reservas_pasadas as $item)
                        <div class="col-md-6 reserva">
                            <div class="card" style="background:url(/img/deportes/fondo-{{ strtolower($item->pista->tipo) }}.jpg);background-size: cover; ">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <h1><i class="far fa-calendar-check mr-2"></i> {{ date('d-m-Y', $item->timestamp) }}</h1>
                                        <h1><i class="far fa-clock mr-2"></i> {{ date('H:i', $item->timestamp) }} a {{ date('H:i',strtotime (date('H:i', $item->timestamp) . " +{$item->minutos_totales} minutes")) }}</h1> 
                                    </div>
                                    <div class="form-group row mt-5 mb-2">
                                        <label class="col-sm-3 col-form-label py-0">Deporte:</label>
                                        <div class="col-sm-9">
                                            <div>{{ $item->pista->tipo }}</div>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-2">
                                        <label class="col-sm-3 col-form-label py-0">Espacio:</label>
                                        <div class="col-sm-9">
                                            <div>{{ $item->pista->nombre }}</div>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-2">
                                        <label class="col-sm-3 col-form-label py-0">Fecha:</label>
                                        <div class="col-sm-9">
                                            <div>{{ date('d/m/Y', $item->timestamp) }}</div>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-2">
                                        <label class="col-sm-3 col-form-label py-0">Hora:</label>
                                        <div class="col-sm-9">
                                            <div>{{ date('H:i', $item->timestamp) }} - {{ date('H:i',strtotime (date('H:i', $item->timestamp) . " +{$item->minutos_totales} minutes")) }}</div>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-2">
                                        <label class="col-sm-3 col-form-label py-0">Minutos totales:</label>
                                        <div class="col-sm-9">
                                            <div>{{ $item->minutos_totales }} minutos</div>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-2">
                                        <label class="col-sm-3 col-form-label py-0">Reserva realizada:</label>
                                        <div class="col-sm-9">
                                            <div>{{ date('d/m/Y, H:i', strtotime($item->created_at)) }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    @if (count(auth()->user()->reservas_canceladas) > 0)
                        <h2 class="h2 mt-5 text-danger">Reservas canceladas</h2>
                    @endif
                    @foreach (auth()->user()->reservas_canceladas as $item)
                        <div class="col-md-6 reserva">
                            <div class="card" style="background:url(/img/deportes/fondo-{{ strtolower($item->pista->tipo) }}.jpg);background-size: cover; ">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <h1><i class="far fa-calendar-check mr-2"></i> {{ date('d-m-Y', $item->timestamp) }}</h1>
                                        <h1><i class="far fa-clock mr-2"></i> {{ date('H:i', $item->timestamp) }} a {{ date('H:i',strtotime (date('H:i', $item->timestamp) . " +{$item->minutos_totales} minutes")) }}</h1> 
                                    </div>
                                    <div class="form-group row mt-5 mb-2">
                                        <label class="col-sm-3 col-form-label py-0">Deporte:</label>
                                        <div class="col-sm-9">
                                            <div>{{ $item->pista->tipo }}</div>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-2">
                                        <label class="col-sm-3 col-form-label py-0">Espacio:</label>
                                        <div class="col-sm-9">
                                            <div>{{ $item->pista->nombre }}</div>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-2">
                                        <label class="col-sm-3 col-form-label py-0">Fecha:</label>
                                        <div class="col-sm-9">
                                            <div>{{ date('d/m/Y', $item->timestamp) }}</div>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-2">
                                        <label class="col-sm-3 col-form-label py-0">Hora:</label>
                                        <div class="col-sm-9">
                                            <div>{{ date('H:i', $item->timestamp) }} - {{ date('H:i',strtotime (date('H:i', $item->timestamp) . " +{$item->minutos_totales} minutes")) }}</div>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-2">
                                        <label class="col-sm-3 col-form-label py-0">Minutos totales:</label>
                                        <div class="col-sm-9">
                                            <div>{{ $item->minutos_totales }} minutos</div>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-2">
                                        <label class="col-sm-3 col-form-label py-0">Reserva realizada:</label>
                                        <div class="col-sm-9">
                                            <div>{{ date('d/m/Y, H:i', strtotime($item->created_at)) }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach --}}
                </div>
            </div>
        </div>
        
        <div class="text-right p-3">
            {{ $reservas->links() }}
        </div>
    </div>
</div>

@endsection

@section('script')
    <script>
        $(document).ready(function () {
            setTimeout(() => {
                // Recargar cada 5 minutos para eliminar reservas expiradas
                location.reload();
            }, 29000);
            /* $('.table-reservas').dataTable({
                "info": false,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
                },
                "order": [[ 6, "desc"], [ 0, "asc"], [ 3, "desc"]]
            }); */
        });
    </script>
@endsection