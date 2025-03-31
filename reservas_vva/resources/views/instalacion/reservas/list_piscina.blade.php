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
                    <h3 class="text-primary no-margin"></h3>
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
                        <table class="table table-hover" id="table-reservas">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Cliente</th>
                                    <th>Fecha de alquiler</th>
                                    <th>Horas</th>
                                    <th>Día de la semana</th>
                                    <th>Tipo</th>
                                    <th>Estado reserva</th>
                                    {{-- <th>#</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reservas as $item)
                                    @if($item->id_pista == 10)
                                    <tr>
                                        <td data-sort="{{ $item->id }}">#{{$item->id}}</td>
                                        <td><a href="/{{ request()->slug_instalacion }}/admin/users/{{ $item->user->id }}/ver">{{ $item->user->name }}</a></td>
                                        <td>{{ date('d/m/Y', $item->timestamp) }}</td>
                                        <td>{{ \Carbon\Carbon::createFromTimestamp($item->timestamp)->format('H:i') }} - {{ \Carbon\Carbon::createFromTimestamp($item->timestamp)->addMinutes($item->minutos_totales)->format('H:i') }}</td>
                                        <td style="text-transform:capitalize">{{ \Carbon\Carbon::parse($item->timestamp)->formatLocalized('%A') }}</td>
                                        <td>{{ $item->tipo }}</td>
                                        <td>
                                            @if ($item->estado  == 'active')
                                                @if (strtotime(date('Y-m-d H:i', $item->timestamp) . ' +' . $item->minutos_totales . ' minutes') > strtotime(date('Y-m-d H:i')))
                                                    <span class="text-success">@if($item->tipo && substr($item->tipo, 0, 4) == 'Bono') Bono @else Pagado @endif</span>
                                                @else
                                                    Pasado
                                                @endif
                                            @endif
                                            @if($item->estado == 'pendiente')
                                                <span class="text-warning">Pendiente de pago</span>
                                            @endif
                                            @if($item->estado == 'desierta')
                                                <span class="text-warning">Desierta</span>
                                            @endif
                                            @if($item->estado == 'canceled')
                                                <span class="text-danger">Cancelada</span>
                                            @endif
                                            @if($item->estado == 'pasado')
                                                <span class="text-success">Validada</span>
                                            @endif
                                        </td>
                                        {{-- <td>
                                            @if ($item->estado  == 'active' && strtotime(date('Y-m-d H:i', $item->timestamp) . ' +' . $item->minutos_totales . ' minutes') > strtotime(date('Y-m-d H:i')))
                                                <a class="cancel btn btn-primary text-white btn-accion-reserva" data-intervalo="{{ \Carbon\Carbon::createFromTimestamp($item->timestamp)->format('H:i') }} - {{ \Carbon\Carbon::createFromTimestamp($item->timestamp)->addMinutes($item->minutos_totales)->format('H:i') }}" data-reserva="{{ $item->id }}" data-user="{{ $item->user->name }}" title="Cancelar reserva">
                                                    Acción
                                                </a>
                                            @endif
                                        </td> --}}
                                    </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
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
            $('#table-reservas').DataTable({
                "info": false,
                "paging": false,
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
        });
    </script>
@endsection