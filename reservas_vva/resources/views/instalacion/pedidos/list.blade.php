@extends('layouts.admin')

@section('content')
<div class="modal fade slide-up disable-scroll" id="modalSlideUp" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content-wrapper">
        <div class="modal-content">
          <div class="modal-header clearfix text-left">
            <button aria-label="" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-icon">close</i>
            </button>
            <h5>Reserva <span></span></h5>
            <p class="p-b-10 user"><strong>Usuario: </strong><span></span></p>
          </div>
          <div class="modal-body">
            <form role="form" method="POST" action="#">
                @csrf
              <div class="form-group-attached">
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group form-group-default">
                      <label>Observaciones <span></span></label>
                      <textarea style="height: 50px" name="observaciones" rows="4" class="form-control"></textarea>
                    </div>
                    <span class="help">Puede quedarse vacío si no tiene.</span>
                </div>
                </div>

                <div class="row">
                    <div class="col-md-7">
                    {{-- <div class="p-t-20 clearfix p-l-10 p-r-10">
                        <div class="pull-left">
                        <p class="bold font-montserrat text-uppercase">TOTAL</p>
                        </div>
                        <div class="pull-right">
                        <p class="bold font-montserrat text-uppercase">$20.00</p>
                        </div>
                    </div> --}}
                    </div>
                    <div class="col-md-5 m-t-10 sm-m-t-10 text-right">
                        <input type="hidden" name="accion">
                        <button type="submit" data-accion="canceled" class="submit-form-validar btn btn-danger m-t-5 mr-2">Cancelarla</button>
                        <button type="submit" data-accion="pasado" class="submit-form-validar btn btn-success m-t-5">Validarla</button>
                    </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
  </div>
<div class="row">
    <div class="col-lg-12 m-b-10">

            <div class="p-l-20 p-r-20 p-b-10 pt-3">
                <div>
                    <h3 class="text-primary no-margin">Pedidos {{ request()->tipo_pedido_pedido }}</h3>
                </div>
            </div>
            <div class="p-t-15 p-b-15 p-l-20 p-r-20">
                <div class="card ">
                    <div class="card-header">
                        <div class="card-title">Listado de pedidos</div>
                    </div>
                    <div class="card-body">
                        {{-- <a href="/{{ request()->slug_instalacion }}/admin/reservas/add" class="btn btn-outline-primary mr-2">Añadir desactivación periódica</a>
                        <a href="/{{ request()->slug_instalacion }}/admin/reservas/add" class="text-white btn btn-primary">Añadir reserva periódica</a> --}}
                        <form  method="GET" action="/{{request()->slug_instalacion}}/admin/orders/eventos/search">
                            <input type="hidden" name="tipo_pedido" value="{{ request()->tipo_pedido }}">
                            <input type="text" name="search" class="form-control" placeholder="Buscar por nombre o numero de pedido" value="{{ request()->search }}">
                            <button type="submit" class="btn btn-primary mt-2">Buscar</button>
                        </form>
                        <table class="table table-hover" id="table-reservas" style="width: 100% !important;">
                            <thead>
                                <tr>
                                    @if(request()->tipo_pedido == 'reservas' || request()->tipo_pedido == 'Piscina')
                                    <th data-priority="1">Reserva</th>
                                    <th>Fecha pago</th>
                                    <th>Pedido</th>
                                    <th data-priority="2">Usuario</th>
                                    <th>Fecha Alquiler</th>
                                    <th>Horas</th>
                                    <th>Coste</th>
                                    <th>Espacio</th>
                                    <th>Estado</th>
                                    @else
                                    <th>Fecha pago</th>
                                    <th>Pedido</th>
                                    <th>Usuario</th>
                                    <th>Numero inscripciones</th>
                                    <th>Coste</th>
                                    <th>Estado</th>
                                    @endif
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $sinpedido = [];
                                @endphp
                                @foreach ($pedidos as $item)
                                    <tr>
                                        @if(request()->tipo_pedido == 'reservas' || request()->tipo_pedido == 'Piscina')
                                            <td>
                                                @if($item->reservas->count())
                                                    #{{ $item->reservas->first()->id }}@if($item->reservas->count() > 1) - #{{ $item->reservas->last()->id }} @endif
                                                @else
                                                    #{{ $item->reserva->id ?? '---' }}
                                                @endif
                                            </td>
                                            <td>{{ strftime('%d %B', strtotime($item->created_at)) }}</td>
                                            <td data-sort="{{ strtotime($item->created_at) }}">{{$item->id}}</td>
                                            <td><a href="/{{ request()->slug_instalacion }}/admin/users/{{ $item->user->id }}/ver">{{ $item->user->name }}</a></td>
                                            <td>@if($item->reserva == null) --- @else {{ date('d/m/Y', $item->reserva->timestamp) }} @endif</td>
                                            <td>@if($item->reserva == null) --- @else {{ \Carbon\Carbon::createFromTimestamp($item->reserva->timestamp)->format('H:i') }} - {{ \Carbon\Carbon::createFromTimestamp($item->reserva->timestamp)->addMinutes($item->reserva->minutos_totales)->format('H:i') }} @endif</td>
                                            <td>{{$item->amount}}€</td>
                                            @if(request()->tipo_pedido != 'Piscina')<td>@if($item->reserva == null) --- @else {{ $item->reserva->pista->nombre }} @endif</td>@endif
                                            <td>
                                                @if ($item->estado  == 'pagado' || $item->estado == 'Devolucion pendiente' || $item->estado == 'Devuelto')
                                                    <span class="text-success">
                                                        @if($item->amount == 0)
                                                            BONO
                                                        @else
                                                        @if ($item->reserva && $item->reserva->creado_por == 'admin' && $item->tipo_pago == "efectivo")
                                                                EFECTIVO
                                                        @elseif($item->reserva && $item->creado_por == 'admin' && $item->tipo_pago == "tarjeta")
                                                                TARJETA
                                                        @else
                                                            @if ($item->estado == 'Devuelto')
                                                                <span style="color: red">DEVUELTO</span>
                                                            @else
                                                                TARJETA
                                                            @endif
                                                        @endif
                                                        @endif
                                                    </span>
                                                @endif
                                                @if($item->estado == 'En proceso')
                                                    <span class="text-warning">PENDIENTE PAGO</span>
                                                @endif
                                                @if($item->estado == 'pendiente')
                                                    <span class="text-warning">PAGO PENDIENTE</span>
                                                @endif
                                                @if($item->estado == 'cancelado')
                                                    <span class="text-danger">CANCELADO</span>
                                                @endif
                                            </td>
                                        @else
                                        <td>{{ strftime('%d %B', strtotime($item->created_at)) }}</td>
                                         @if(request()->slug_instalacion == 'villafranca-navidad' && Auth::user()->id == 2713  && $item->estado != 'En proceso')
                                                <td data-sort="{{ strtotime($item->created_at) }}"><a target="_blank" href="/{{ request()->slug_instalacion }}/admin/orders/ver-pdf/{{ $item->id }}/ver">{{$item->id}} </a> </td>
                                            @else
                                            <td data-sort="{{ strtotime($item->created_at) }}">{{$item->id}}</td>
                                            @endif
                                        <td><a href="/{{ request()->slug_instalacion }}/admin/users/{{ $item->user->id }}/ver">{{ $item->user->name }}</a></td>
                                        <td>{{ $item->participantes->count() }}</td>
                                        @php

                                            if($item->participantes->count() == 0){
                                                $sinpedido[] = $item;
                                            }
                                        @endphp
                                        <td>{{$item->amount}}€</td>
                                        <td>
                                            @if ($item->estado  == 'pagado' || $item->estado == 'Devolucion pendiente' || $item->estado == 'Devuelto')
                                                <span class="text-success">
                                                    @if($item->amount == 0)
                                                        EFECTUADO
                                                    @else
                                                        @if ($item->reserva && $item->reserva->creado_por == 'admin' && $item->tipo_pago == "efectivo")
                                                            EFECTIVO
                                                        @elseif($item->reserva && $item->creado_por == 'admin' && $item->tipo_pago == "tarjeta")
                                                            TARJETA
                                                        @else
                                                            @if ($item->estado == 'Devuelto')
                                                                <span style="color: red">DEVUELTO</span>
                                                            @else
                                                                TARJETA
                                                            @endif
                                                        @endif
                                                    @endif
                                                </span>
                                            @endif
                                            @if($item->estado == 'En proceso')
                                                <span class="text-warning">PENDIENTE PAGO</span>
                                            @endif
                                            @if($item->estado == 'pendiente')
                                                <span class="text-warning">PAGO PENDIENTE</span>
                                            @endif
                                            @if($item->estado == 'cancelado')
                                                <span class="text-danger">CANCELADO</span>
                                            @endif
                                        </td>
                                        @endif
                                        <td class="text-center">
                                            <div>
                                                <div class="mb-1">
                                                    @if ($item->reserva && $item->reserva->creado_por != 'admin')
                                                        @if($item->estado == 'Devolucion pendiente')
                                                            <span class="text-warning">DEVOLUCIÓN PENDIENTE</span>
                                                        @elseif($item->estado == 'Devuelto')
                                                            <span style="color: red">DEVUELTO</span>
                                                        @else
                                                            <a href="#" class="btn btn-outline-primary btn-devolver w-100" data-id="{{ $item->id }}" data-slug="{{request()->slug_instalacion}}"><i class="fa-solid fa-repeat mr-2"></i> Devolución</a>
                                                        @endif
                                                    @endif
                                                </div>
                                                <div>
                                                    @if(request()->slug_instalacion == 'villafranca-navidad' && Auth::user()->id == 2713  && $item->estado != 'En proceso')
                                                        <a href="/{{ request()->slug_instalacion }}/admin/orders/{{ $item->id }}/send" target="_blank" class="btn btn-outline-primary w-100" data-id="{{ $item->id }}"><i class="fa-solid fa-ticket mr-2"></i>Enviar entradas</a>
                                                    @elseif(request()->slug_instalacion == 'villafranca-navidad' && Auth::user()->id == 2713  && $item->estado == 'En proceso')   
                                                    @else
                                                        <a href="/{{ request()->slug_instalacion }}/admin/orders/{{ $item->id }}/print" target="_blank" class="btn btn-outline-primary w-100" data-id="{{ $item->id }}"><i class="fa-solid fa-print mr-2"></i> Factura</a>
                                                        <a href="/{{ request()->slug_instalacion }}/admin/orders/{{ $item->id }}/send_pedido" target="_blank" class="btn btn-outline-primary w-100" data-id="{{ $item->id }}"><i class="fa-solid fa-envelope mr-2"></i>Reenviar correo</a>

                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                          
                            </tbody>
                        </table>
                        <div class="text-right mt-2 links">
                            {{ $pedidos->links() }}
                        </div>
                    </div>
                </div>
                <p class="small no-margin">
                </p>
            </div>

    </div>
</div>
<div class="modal fade slide-up disable-scroll" id="modal-devolver" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content-wrapper">
        <div class="modal-content">
          <div class="modal-header clearfix text-left" style="    padding-top: 8px;">
            <h4 style="font-size: 17px;line-height: 25px">¿Estás seguro que quieres procesar la devolución?</h4>
            <button aria-label="" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-icon">close</i>
            </button>
          </div>
          <div class="modal-body p-4">
            <form role="form" method="get" action="#">
                @csrf
                <div class="form-group">
                    <label>¿Quieres cancelar la reserva de este pedido?</label>
                    <select class="full-width cancel-reserva" name="cancel_reserva" data-init-plugin="select2">
                        <option value="1">Si</option>
                        <option value="2">No</option>
                    </select>
                </div>
                <div class="text-center mt-4">
                    <button class="btn btn-success w-100">
                        Procesar devolución
                    </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
</div>
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            $('.cancel-reserva').select2({
                minimumResultsForSearch: -1
            });
            $('#table-reservas').DataTable({
                responsive: true,
                "info": false,
                "paging": false,
                "searching": false,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
                },
                "order": [0, "desc"]
            });

            $('.btn-accion-reserva').click(function (e) {
                e.preventDefault();
                let modal = $('#modalSlideUp');
                modal.modal('show').find('h5 span').html(`#${$(this).data('reserva')}: ${$(this).data('intervalo')}`);
                modal.find('.user span').html($(this).data('user'));
                modal.find('form').attr('action', `/{{ request()->slug_instalacion }}/admin/reservas/validar/${$(this).data('reserva')}`);
            });

            $('#modalSlideUp').on('click', '.submit-form-validar', function (e) {
                e.preventDefault();
                $(this).parent().find('input').val($(this).data('accion'));
                $('#modalSlideUp').find('form').submit();
            });

            $('.btn-devolver').click(function (e) {
                e.preventDefault();
                $('#modal-devolver').modal('show').find('form').attr('action', `/{{ request()->slug_instalacion }}/admin/orders/${$(this).data('id')}/devolver`);
                /* $.ajax({
                    headers: {
					    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				    },
                    type: "get",
                    url: `/{{ request()->slug_instalacion }}/admin/orders/${$(this).data('id')}/devolver-manager/`,
                    data: {id: $(this).data('id')},
                    dataType : 'json',
                    success: function(json){
                        console.log(json);
                    }
                }); */

            });
        });
    </script>
@endsection
