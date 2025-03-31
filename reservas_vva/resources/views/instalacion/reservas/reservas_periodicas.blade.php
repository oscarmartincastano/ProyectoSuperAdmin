@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-lg-12 m-b-10">

            <div class="p-l-20 p-r-20 p-b-10 pt-3">
                <div>
                    <h3 class="text-primary no-margin">Reservas</h3>
                </div>
            </div>

            <div class="p-t-15 p-b-15 p-l-20 p-r-20">
                <div class="card ">
                    <div class="card-header">
                        <div class="card-title">Reservas periódicas</div>
                    </div>
                    <div class="card-body">
                        <a href="/{{ request()->slug_instalacion }}/admin/reservas/periodicas/add" class="btn btn-primary text-white mb-2">Añadir reserva periódica</a>
                        <table class="table table-hover" id="table-reservas" style="width: 100% !important;">
                            <thead>
                                <tr>
                                    <th data-priority="1">Usuario</th>
                                    <th data-priority="2">Espacio</th>
                                    <th>Fecha inicio</th>
                                    <th>Fecha fin</th>
                                    <th>Día de la semana</th>
                                    <th>Horas</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reservas_periodicas as $item)
                                    @if (strtotime(date('Y-m-d')) < strtotime($item->fecha_fin))
                                        <tr>
                                            <td><a href="/{{ request()->slug_instalacion }}/admin/users/{{ $item->user->id }}/ver">{{ $item->user->name }}</a></td>
                                            <td>{{ count(auth()->user()->instalacion->deportes) > 1 ? $item->pista->tipo . '.' : '' }} {{ $item->pista->nombre }}</td>
                                            <td>{{ date('d/m/Y', strtotime($item->fecha_inicio)) }}</td>
                                            <td>{{ date('d/m/Y', strtotime($item->fecha_fin)) }}</td>
                                            <td>
                                                @foreach (unserialize($item->dias) as $index => $dia)
                                                    @switch($dia)
                                                        @case(0)
                                                            Domingo
                                                            @break
                                                        @case(1)
                                                            Lunes
                                                            @break
                                                        @case(2)
                                                            Martes
                                                            @break
                                                        @case(3)
                                                            Miércoles
                                                            @break
                                                        @case(4)
                                                            Jueves
                                                            @break
                                                        @case(5)
                                                            Viernes
                                                            @break
                                                        @case(6)
                                                            Sábado
                                                            @break
                                                        @default
                                                    @endswitch
                                                    {{ $index != count(unserialize($item->dias))-1 ? '|' : '' }}
                                                @endforeach
                                            </td>
                                            <td>{{ $item->hora_inicio }} - {{ $item->hora_fin }}</td>
                                            <td>
                                                <a data-item="{{$item->id}}"  href="#" class="btn btn-danger text-white cancelar_reserva_p" title="Borrar estas reservas periódicas">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                              <div class="modal-content">
                                <div class="modal-header mb-3">
                                  <h5 class="modal-title" id="exampleModalLabel">Reservas periódicas</h5>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                <div class="modal-body text-danger">
                                    Se van a eliminar todas las reservas que no estén pagadas.<br/>
                                    La que están pagadas se mantendrán con la opción de cancelarlas 24 horas antes de la reserva.
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-dismiss="modal" id="cerrar-modal">Cerrar</button>
                                  <button type="button" class="btn btn-danger" id="confirmar-eliminacion">Eliminar reserva periódica</button>
                                </div>
                              </div>
                            </div>
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

            let item_id="";
            $(".cancelar_reserva_p").click(function(e) {
                item_id = $(this).data('item');
                $("#exampleModal").modal('show');
                console.log(item_id);
            });

            $("#confirmar-eliminacion").click(function(e) {
                window.location.href = `/{{ request()->slug_instalacion }}/admin/reservas/periodicas/${item_id}/borrar`;
            });
        });
    </script>
@endsection
