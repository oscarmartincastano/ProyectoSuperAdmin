@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-lg-12 m-b-10">

            <div class="p-l-20 p-r-20 p-b-10 pt-3">
                <div>
                    <h3 class="text-primary no-margin">Añadir reserva periódica</h3>
                </div>
            </div>

            <div class="p-t-15 p-b-15 p-l-20 p-r-20">
                <div class="card ">
                    <div class="card-header">
                        <div class="card-title">Información</div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('add_reserva_periodica', ['slug_instalacion' => request()->slug_instalacion]) }}"
                            method="post" role="form" class="form-horizontal" id="formulario">
                            @csrf
                            <div class="form-group">
                                <label for="espacio">Espacio</label>
                                <select required class="full-width form-control" name="espacio">
                                    @foreach (auth()->user()->instalacion->pistas as $item)
                                        <option value="{{ $item->id }}">{{ count(auth()->user()->instalacion->deportes) > 1 ? $item->tipo . '.' : '' }} {{ $item->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="espacio">Usuario</label>
                                <select required class="full-width select-user" name="user_id" id="user_id">
                                    <option></option>
                                    @foreach (auth()->user()->instalacion->users as $item)
                                        @if ($item->id != auth()->user()->id)
                                            <option value="{{ $item->id }}">{{ $item->name }} ({{ $item->email }})</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="fecha_inicio">Fecha inicio</label>
                                <input type="date" class="form-control" placeholder="Fecha inicio" name="fecha_inicio" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="form-group">
                                <label for="fecha_fin">Fecha fin</label>
                                <input type="date" class="form-control" placeholder="Fecha fin" name="fecha_fin" required>
                            </div>
                            <div class="form-group">
                                <label for="tipo">Días</label>
                                <select required class="full-width select2 select-desactivacion" data-init-plugin="select2" name="dias[]" id="desactivaciones[]" multiple>
                                    <option></option>
                                    <option value="1">Lunes</option>
                                    <option value="2">Martes</option>
                                    <option value="3">Miércoles</option>
                                    <option value="4">Jueves</option>
                                    <option value="5">Viernes</option>
                                    <option value="6">Sábado</option>
                                    <option value="0">Domingo</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="estado_pedido">Estado:</label>
                                    <select class="form-control full-width" name="estado_pedido" id="estado_pedido">
                                        <option value="pendiente">Pendiente pago</option>
                                        <option value="active">Pagado</option>
                                    </select>
                            </div>
                            <div class="form-group">
                                <label for="tipo_pago">Tipo de pago:</label>
                                    <select class="form-control full-width" name="tipo_pago" id="tipo_pago">
                                        <option value="tarjeta">Tarjeta</option>
                                        <option value="efectivo">Ejectivo pago</option>
                                    </select>
                            </div>
                            <div class="form-group">
                                <label for="hora_inicio">Hora inicio</label>
                                <input type="time" class="form-control" placeholder="Hora inicio" name="hora_inicio" required>
                            </div>
                            <div class="form-group">
                                <label for="hora_fin">Hora fin</label>
                                <input type="time" class="form-control" placeholder="Hora fin" name="hora_fin" required>
                            </div>
                            <button class="btn btn-primary btn-lg m-b-10 mt-3" type="submit" id="addReservaPeriodica">Añadir</button>
                        </form>
                        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                              <div class="modal-content">
                                <div class="modal-header mb-3">
                                  <h5 class="modal-title" id="exampleModalLabel">Reservas</h5>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                <div class="modal-body text-danger">
                                    Ya existen reservas en esa zona entre las fechas y las horas seleccionadas
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-dismiss="modal" id="cerrar-modal">Cerrar</button>
                                  <button type="button" class="btn btn-primary" id="confirmar-reserva">Hacer reserva periódica</button>
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
        $(document).ready(function() {

            $(".select2").select2({
                placeholder: "Selecciona días..."
            });

            $(".select-user").select2({
                placeholder: "Selecciona un usuario"
            });

            $("#confirmar-reserva").click(function(){
                $("#formulario").unbind('submit').submit();
            });

            $("#formulario").submit(function(e){
                e.preventDefault();
                // Hacer consulta ajax por post a la ruta /admin/reservas/periodicas/comprobar-reservas y le pasamos la variable espacio
                $.ajax({
                    url: "{{route('comprobar_reservas_reserva_periodica', ['slug_instalacion' => request()->slug_instalacion])}}",
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "espacio": $("[name='espacio']")[0].value,
                        "fecha_inicio": $("[name='fecha_inicio']")[0].value,
                        "fecha_fin": $("[name='fecha_fin']")[0].value,
                        "hora_inicio": $("[name='hora_inicio']")[0].value,
                        "hora_fin": $("[name='hora_fin']")[0].value,
                        "dias": $("[name='dias[]']").val()
                    },
                    success: function(response) {
                        if(response){
                            $("#exampleModal").modal("show");
                        }else{
                            $("#formulario").unbind('submit').submit();
                        }
                    },
                    error: function(response) {
                        console.log(response);
                    }
                });

            });
        });
    </script>
@endsection
