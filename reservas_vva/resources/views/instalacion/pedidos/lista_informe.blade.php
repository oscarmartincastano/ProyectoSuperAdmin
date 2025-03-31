@extends('layouts.admin')

@section('content')

<div class="row">
    <div class="col-lg-12 m-b-10">

            <div class="p-l-20 p-r-20 p-b-10 pt-3">
                <div>
                    <h3 class="text-primary no-margin">Lista informe pedidos hechos {{ date('d/m/Y', strtotime(request()->fecha_inicio)) }} - {{ date('d/m/Y', strtotime(request()->fecha_fin)) }}</h3>
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
                        <table class="table table-hover"  id="table-reservass" style="width:100% !important;">
                            <thead>
                                <tr>
                                    <th data-priority="1">Reserva</th>
                                    <th>Fecha pago</th>
                                    <th>Pedido</th>
                                    <th data-priority="2">Usuario</th>
                                    <th>Fecha Alquiler</th>
                                    <th>Horas</th>
                                    <th>Coste</th>
                                    @if(request()->tipo != 'Piscina')<th>Espacio</th>@endif
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pedidos as $item)
                                    <tr>
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
                                        @if(request()->tipo != 'Piscina')<td>@if($item->reserva == null) --- @else {{ $item->reserva->pista->nombre }} @endif</td>@endif
                                        <td>
                                            @if ($item->estado  == 'pagado')
                                                <span class="text-success">
                                                    @if($item->amount == 0)
                                                        BONO
                                                    @else
                                                        @if ($item->reserva && $item->reserva->creado_por == 'admin')
                                                            EFECTIVO
                                                        @else
                                                            TARJETA
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
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>



                            </tfoot>
                        </table>
                        <h5 style="padding-left: 5%;">TOTAL: {{ $total }} €</h5>

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
            $('#table-reservass').DataTable({
                responsive: true,
                dom: 'Bfrtip',
                "info": false,
                "paging": false,
                "buttons": [{
                    text: `<i class="fa fa-print" aria-hidden="true"></i> &nbsp; EXPORTAR PDF`,
                    extend: "pdf",
                    footer: true
                }, {
                    text: `<i class="fa fa-print" aria-hidden="true"></i> &nbsp; EXPORTAR EXCEL`,
                    extend: "excel",
                    footer: true
                }],
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
