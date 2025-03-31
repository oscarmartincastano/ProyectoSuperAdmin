@extends('layouts.admin')

@section('content')
    <div class="row justify-content-center mt-md-5">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Nuevo usuario</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('usuario_store', request()->slug_instalacion) }}">
                    @csrf
                        <input type="hidden" name="usuario_id" id="usuario_id" value="">
                        <div class="row mb-3">
                            <h4>Configurar acceso</h4>
                        </div>

                        <div class="col-sm-9 mb-3" style="padding: 0 !important">
                            <select required class="full-width select2 select-cliente" data-init-plugin="select2" name="user_id" id="user_id" >
                                <option></option>
                                <option value="new_user">Usuario</option>
                                @foreach ($users as $item)
                                    @if ($item->id != auth()->user()->id)
                                        <option value="{{ $item->id }}">{{ $item->name }} ({{ $item->email }})</option>
                                    @endif
                                @endforeach
                            </select>
                            <div class="datos_new_client row border p-2" style="display: none"></div>
                        </div>

                        <div class="row mb-3">
                            <label for="finicio" class="col-md-4 col-form-label text-md-end">Inicio de entrada</label>

                            <div class="col-md-6">
                                <input id="finicio" type="date" class="form-control" name="finicio" value="" placeholder="Fecha de inicio"  required>

                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="ffin" class="col-md-4 col-form-label text-md-end">Fin de entrada*</label>

                            <div class="col-md-6">
                                <input id="ffin" type="date" class="form-control" name="ffin" value="" placeholder="Fecha fin" >*
                                Dejar en blanco si no tiene fecha de fin
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="apertura" class="col-md-4 col-form-label text-md-end">Hora mínima de apertura</label>

                            <div class="col-md-6">
                                <input id="apertura" type="time" class="form-control" name="apertura" value="" required >

                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="apertura" class="col-md-4 col-form-label text-md-end">Hora máxima de apertura</label>

                            <div class="col-md-6">
                                <input id="cierre" type="time" class="form-control" name="cierre" value="" required >

                            </div>
                         
                        </div>
                        <div class="row mb-3 ">
                            <div class="col-md-4 m-auto">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" name="activo">
                                <label class="form-check-label" for="flexSwitchCheckDefault">¿Quieres dejar el usuario activo?</label>
                            </div>
                            </div>
                        </div>

                        <div class="row mb-3 ">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary">Guardar usuario</button>
                            </div>
                        </div>





                    </form>
                </div>
            </div>
        </div>
    </div>

    @section('script')
        <script>
                $(document).ready(function() {
                    $(".select2").select2({
                        placeholder: "Selecciona un usuario"
                    });
                    $("#user_id").change(function (e) { 
                        let id = $(this).val();
                        $.ajax({
                            type: "GET",
                            url: "/{{ request()->slug_instalacion }}/admin/puertas/usuarios_accesos/"+id,
                            success: function (response) {
                                console.log(response);
                                if (response.length > 0) {
                                    let id_accesso = response[0].id;
                                    let apertura = response[0].apertura;
                                    let cierre = response[0].cierre;
                                    let finicio = response[0].inicio;
                                    let ffin = response[0].fin;
                                    let activo = response[0].activo;

                                    // /usuarios_accesos/
                                    $("form").attr("action", "/{{ request()->slug_instalacion }}/admin/puertas/usuarios_accesos/"+id_accesso+'/update');

                                    $("#usuario_id").val(id);
                                    $("#finicio").val(finicio);
                                    $("#ffin").val(ffin);
                                    $("#apertura").val(apertura);
                                    $("#cierre").val(cierre);
                                    if (activo == "on") {
                                        $("#flexSwitchCheckDefault").prop("checked", true);
                                    } else {
                                        $("#flexSwitchCheckDefault").prop("checked", false);
                                    }
                                    

                                }
                            },
                           
                        });                        
                    });
                });
        </script>
    @endsection
@endsection