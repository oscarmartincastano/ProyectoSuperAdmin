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
                    <h3 class="text-primary no-margin">Reservas @if(\Route::current()->getName() == "reservas.list_piscina") piscina @endif</h3>
                </div>
            </div>

            <div class="p-t-15 p-b-15 p-l-20 p-r-20">
                <div class="card ">
                    <div class="card-header">
                        <div class="card-title">Listado de reservas @if(\Route::current()->getName() == "reservas.list_piscina") piscina @endif</div>
                    </div>
                    <div class="card-body">
                        {{-- <a href="/{{ request()->slug_instalacion }}/admin/reservas/add" class="btn btn-outline-primary mr-2">Añadir desactivación periódica</a>
                        <a href="/{{ request()->slug_instalacion }}/admin/reservas/add" class="text-white btn btn-primary">Añadir reserva periódica</a> --}}
                        <div style="display: flex;justify-content: flex-end;gap:10px;display: none">
                            <div id="table-reservas_buscar" class="dataTables_filter text-right">
                                <label for="buscar">
                                    <input id="buscar" type="search" class="form-control input-sm" placeholder="Buscar por cliente..." aria-controls="table-users">
                                </label>
                            </div>
                        </div>
                        <table class="table table-hover" id="table-reservas" style="width: 100% !important;">
                            <thead>
                                <tr>
                                    <th data-priority="1">ID</th>
                                    <th>Pedido</th>
                                    <th data-priority="2">Cliente</th>
                                    <th>Fecha de alquiler</th>
                                    <th>Horas</th>
                                    <th>Día de la semana</th>
                                    <th>
                                        @if(\Route::current()->getName() == "reservas.list_piscina")
                                        Espacio
                                        @else
                                        Tipo
                                        @endif
                                    </th>
                                    <th>Estado pago</th>
                                    @if(\Route::current()->getName() == "reservas.list_piscina")
                                    <th>Asistencia</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reservas as $item)
                                    <tr>
                                        <td data-sort="{{ $item->id }}">#{{ $item->id}}</td>
                                        <td>{{ $item->pedido->id ?? '---' }}</td>
                                        <td><a href="/{{ request()->slug_instalacion }}/admin/users/{{ $item->user->id }}/ver">{{ $item->user->name }}</a></td>
                                        <td>{{ date('d/m/Y', $item->timestamp) }}</td>
                                        <td>{{ \Carbon\Carbon::createFromTimestamp($item->timestamp)->format('H:i') }} - {{ \Carbon\Carbon::createFromTimestamp($item->timestamp)->addMinutes($item->minutos_totales)->format('H:i') }}</td>
                                        <td style="text-transform:capitalize">{{ \Carbon\Carbon::parse($item->timestamp)->formatLocalized('%A') }}</td>
                                        <td>
                                            @if(\Route::current()->getName() != "reservas.list_piscina")
                                                {{ $item->pista->nombre }}
                                            @endif
                                            @if($item->tipo)
                                                @if(\Route::current()->getName() != "reservas.list_piscina") - @endif{{ $item->tipo }}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($item->estado  == 'active')
                                                <span class="text-success">
                                                    @if($item->tipo && substr($item->tipo, 0, 4) == 'Bono')
                                                        BONO
                                                    @else
                                                        @if($item->creado_por == 'admin' && $item->pedido->tipo_pago == "efectivo")
                                                            EFECTIVO
                                                        @elseif($item->creado_por == 'admin' && $item->pedido->tipo_pago == "tarjeta")
                                                            TARJETA
                                                        @else
                                                            TARJETA
                                                        @endif
                                                    @endif
                                                </span>
                                            @endif
                                            @if($item->estado == 'pendiente')
                                                <span class="text-warning">PAGO PENDIENTE</span>
                                            @endif
                                            @if($item->estado == 'canceled')
                                                <span class="text-danger">CANCELADO</span>
                                            @endif
                                        </td>
                                        @if(\Route::current()->getName() == "reservas.list_piscina")
                                        <td class="text-uppercase">
                                            @if($item->estado != 'canceled')
                                            <div class="dropdown dropdown-default w-100">
                                                <button style="text-transform: uppercase;width:100%;display:inline-block" aria-label="" class="btn dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                {{ $item->estado_asistencia ?? 'POR LLEGAR' }}
                                                </button>
                                                <div class="dropdown-menu">
                                                    @if($item->estado_asistencia)<a class="dropdown-item" href="{{ route('reserva.actualizar_asistencia', ['slug_instalacion' => request()->slug_instalacion, 'id' => $item->id, 'estado' => 'Por llegar']) }}">Por llegar</a>@endif
                                                    @if($item->estado_asistencia != 'Llegada')<a class="dropdown-item" href="{{ route('reserva.actualizar_asistencia', ['slug_instalacion' => request()->slug_instalacion, 'id' => $item->id, 'estado' => 'Llegada']) }}">Llegada</a>@endif
                                                    @if($item->estado_asistencia != 'Desierta')<a class="dropdown-item" href="{{ route('reserva.actualizar_asistencia', ['slug_instalacion' => request()->slug_instalacion, 'id' => $item->id, 'estado' => 'Desierta']) }}">Desierta </a>@endif
                                                </div>
                                              </div>
                                            @else
                                            ---
                                            @endif
                                        </td>
                                        @endif
                                        {{-- <td>
                                            @if ($item->estado  == 'active' && strtotime(date('Y-m-d H:i', $item->timestamp) . ' +' . $item->minutos_totales . ' minutes') > strtotime(date('Y-m-d H:i')))
                                                <a class="cancel btn btn-primary text-white btn-accion-reserva" data-intervalo="{{ \Carbon\Carbon::createFromTimestamp($item->timestamp)->format('H:i') }} - {{ \Carbon\Carbon::createFromTimestamp($item->timestamp)->addMinutes($item->minutos_totales)->format('H:i') }}" data-reserva="{{ $item->id }}" data-user="{{ $item->user->name }}" title="Cancelar reserva">
                                                    Acción
                                                </a>
                                            @endif
                                        </td> --}}
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="text-right p-3">
                            {{ $reservas->links() }}
                        </div>
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
                 responsive:true,
                "info": false,
                "paging": false,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
                },
                "order": [[ 0, "desc"]]
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

            $('.card-body').on('keyup', '#table-reservas_buscar input', function (e) {
                if ($(this).val().length == 0 || $(this).val().length > 3) {
                    if ($(this).val() == '') {
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url:"{{ route('reservas.list_datatable', ['slug_instalacion' => request()->slug_instalacion]) }}",
                            method:"POST",
                            data:{page: '{{ request()->page ?? "1" }}'},
                            success: function(response) {
                                $('#table-reservas tbody').empty();
                                $.each(response['data'], function (index, item) {
                                    let date = new Date(item['timestamp']*1000);
                                    $('#table-reservas tbody').append(`
                                    <tr>
                                        <td>#${item['id']}</td>
                                        <td>${item['id_pedido'] ?? '---'}</td>
                                        <td><a href="/{{ request()->slug_instalacion }}/admin/users/${item['user_id']}/ver">${item['user_name']}</a></td>
                                        <td>${date.toLocaleString("es-ES", {day: "numeric"})}/${date.toLocaleString("es-ES", {month: "numeric"})}/${date.toLocaleString("es-ES", {year: "numeric"})}</td>
                                        <td>${date.toLocaleTimeString().slice(0, -3)} - ${new Date(date.getTime() + item['minutos_totales']*60000).toLocaleTimeString().slice(0, -3)}</td>
                                        <td style="text-transform:capitalize">${date.toLocaleString("es", {weekday: "long"})}</td>
                                        <td>${item['nombre']} ${item['tipo'] != null ? '-' + item['tipo'] : ''}</td>
                                        <td>
                                            ${item['estado'] == 'active' && item['tipo'] && item['tipo'].split(' ')[0] == 'Bono' ? '<span class="text-success">BONO</span>' : ''}
                                            ${item['estado'] == 'active' && item['tipo'] != null && item['tipo'].split(' ')[0] != 'Bono' && item['creado_por'] == 'admin' ? '<span class="text-success">EFECTIVO</span>' : ''}
                                            ${item['estado'] == 'active' && item['tipo'].split(' ')[0] != 'Bono' && item['creado_por'] != 'admin' ? '<span class="text-success">TARJETA</span>' : ''}
                                            ${item['estado'] == 'pendiente' ? '<span class="text-warning">PAGO PENDIENTE</span>' : ''}
                                            ${item['estado'] == 'canceled' ? '<span class="text-danger">CANCELADO</span>' : ''}

                                        </td>
                                    </tr>
                                    `);
                                });
                            },
                            error: function (er) {
                                console.log(er);
                            }
                        });
                    } else {
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url:"{{ route('reservas.list_datatable', ['slug_instalacion' => request()->slug_instalacion]) }}",
                            method:"POST",
                            data:{text: $(this).val()},
                            success: function(response) {
                                $('#table-reservas tbody').empty();
                                $.each(response['data'], function (index, item) {
                                    console.log(item);
                                    let date = new Date(item['timestamp']*1000);
                                    $('#table-reservas tbody').append(`
                                    <tr>
                                        <td>#${item['id']}</td>
                                        <td>${item['id_pedido'] ?? '---'}</td>
                                        <td><a href="/{{ request()->slug_instalacion }}/admin/users/${item['user_id']}/ver">${item['user_name']}</a></td>
                                        <td>${date.toLocaleString("es-ES", {day: "numeric"})}/${date.toLocaleString("es-ES", {month: "numeric"})}/${date.toLocaleString("es-ES", {year: "numeric"})}</td>
                                        <td>${date.toLocaleTimeString().slice(0, -3)} - ${new Date(date.getTime() + item['minutos_totales']*60000).toLocaleTimeString().slice(0, -3)}</td>
                                        <td style="text-transform:capitalize">${date.toLocaleString("es", {weekday: "long"})}</td>
                                        <td>${item['nombre']} ${item['tipo'] != null ? '-' + item['tipo'] : ''}</td>
                                        <td>
                                            ${item['estado'] == 'active' && item['tipo'] && item['tipo'].split(' ')[0] == 'Bono' ? '<span class="text-success">BONO</span>' : ''}
                                            ${item['estado'] == 'active' && item['tipo'] != null && item['tipo'].split(' ')[0] != 'Bono' && item['creado_por'] == 'admin' ? '<span class="text-success">EFECTIVO</span>' : ''}
                                            ${item['estado'] == 'active' && item['tipo'].split(' ')[0] != 'Bono' && item['creado_por'] != 'admin' ? '<span class="text-success">TARJETA</span>' : ''}
                                            ${item['estado'] == 'pendiente' ? '<span class="text-warning">PAGO PENDIENTE</span>' : ''}
                                            ${item['estado'] == 'canceled' ? '<span class="text-danger">CANCELADO</span>' : ''}

                                        </td>
                                    </tr>
                                    `);
                                });
                            },
                            error: function (er) {
                                console.log(er);
                            }
                        });
                    }
                }
            });
        });
    </script>
@endsection
