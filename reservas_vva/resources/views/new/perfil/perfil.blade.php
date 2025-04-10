@extends('new.layout.base')
@php
$slug = request()->slug_instalacion;
$registro = DB::connection('superadmin')->table('superadmin')->where('url','https://gestioninstalacion.es/'.$slug)->first();

$tipoCalendario = $registro->tipo_calendario;

if((str_contains(request()->url(),'reservas') || str_contains(request()->url(),'eventos')) && $tipoCalendario == 0){
    header("Location: /$slug");
    exit();
}
@endphp
@section('style')
<style>
    .titulo-card {
        margin-bottom: 16px;
        border-bottom: 1px solid rgba(47,51,51,.1);
        padding-bottom: 24px;
        font-weight: 600;
        line-height: 1.4;
        font-size: 32px;
    }
    .contenido-principal {
        padding: 80px;
    }
    th {
        border-top: 0 !important;
    }
    .form-perfil label {
        color: rgba(47,51,51,.4);
        display: block;
        font-size: 14px;
        line-height: 16px;
        height: 16px;
        font-weight: 500;
    }
    .form-perfil input, .form-perfil textarea {
        display: block;
        width: 100%;
        font-size: 16px;
        padding: 7px 0;
        line-height: 1.8;
        background-color: transparent;
        border: 0;
        border-bottom: 1px solid rgba(14,36,51,0.3);
        -webkit-transition: border-color .25s;
        transition: border-color .25s;
    }
    .form-perfil input:focus, .form-perfil textarea:focus {
        border: 0;
        border-bottom: 1px solid #335fff;
        outline: 0;
        box-shadow: none;
    }
    .btn-form {
        color: #fff;
        border-color: transparent;
        background-color: #335fff;
        border-radius: 32px;
    }
    .nav-tabs {
        display: flex;
        align-items: center;
        font-size: 16px;
        border-bottom: 0;
    }
    .nav-tabs a {
        display: block;
        padding: 1em 0;
        line-height: 1em;
        border-bottom: 2px solid transparent;
        text-decoration: none;
        opacity: .6;
        -webkit-transition: all .15s;
        transition: all .15s;
        position: relative;
        opacity: 0.75;
        color: black;
    }
    .nav-tabs a.active {
        border-bottom-color: #335fff;
        opacity: 1;
    }
    .nav-tabs li:nth-child(2)>a {
        margin-left: 2em;
    }
    .tab-content{
        margin-top: 32px;
    }
    ::placeholder {
        opacity: 0.55 !important;
    }
    @media(max-width: 992px) {
        .contenido-principal {
            padding: unset !important;
            padding-top: 80px !important;
        }
    }
    @media(max-width: 582px) {
        .contenido-principal {
            padding-top: 0 !important;
        }
    }
</style>
@endsection

@section('post-header')
    <div class="post-header">
        <div class="menu-header">
            <a href="/{{ request()->slug_instalacion }}/perfil" class="{{ request()->is(request()->slug_instalacion . '/new/perfil') ? 'active' : '' }}">Mi perfil</a>
            @if($tipoCalendario !=0)
            @if (request()->slug_instalacion != "villafranca-navidad" and request()->slug_instalacion != "villafranca-actividades" and request()->slug_instalacion != "ciprea24" and request()->slug_instalacion != "eventos-bodega" and request()->slug_instalacion != "la-guijarrosa" and request()->slug_instalacion != "feria-jamon-villanuevadecordoba")
            <a href="/{{ request()->slug_instalacion }}/mis-reservas" class="{{ request()->is(request()->slug_instalacion . '/new/mis-reservas') ? 'active' : '' }}">Mis reservas</a>
            <a href="/{{ request()->slug_instalacion }}/mis-eventos" class="{{ request()->is(request()->slug_instalacion . '/new/mis-eventos') ? 'active' : '' }}">Mis eventos</a>
            @endif
            @if (request()->slug_instalacion != "villafranca-navidad" and request()->slug_instalacion != "villafranca-actividades" and request()->slug_instalacion != "ciprea24" and request()->slug_instalacion != "eventos-bodega" and request()->slug_instalacion != "feria-jamon-villanuevadecordoba")
            <a href="/{{ request()->slug_instalacion }}/new/mis-servicios" class="{{ request()->is(request()->slug_instalacion . '/new/mi-perfil') ? 'active' : '' }}">Mis servicios</a>
            <a href="/{{ request()->slug_instalacion }}/new/mis-recibos" class="{{ request()->is(request()->slug_instalacion . '/new/mis-recibos') ? 'active' : '' }}">Mis recibos</a>
            @endif
            @endif
            @if (request()->slug_instalacion == "santaella")
            <a href="/{{ request()->slug_instalacion }}/new/mis-bonos" class="{{ request()->is(request()->slug_instalacion . '/new/mis-bonos') ? 'active' : '' }}">Mis bonos</a>
            @endif
        </div>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card shadow-box card-reserva mb-4">
            <div class="card-body" style="padding: 48px;">
                <div class="titulo-card">Perfil</div>
                <div class="contenido-card form-perfil">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#mi-perfil" class="active show">Mi perfil</a></li>
                        <li><a href="#passwd">Cambiar contraseña</a></li>
                        @if (request()->slug_instalacion == "la-guijarrosa")
                            <li style="margin-left: 2em;"><a href="#apertura-puerta">Apertura puertas</a></li>
                        @endif
                    </ul>
                    <div class="tab-content">
                        <div id="mi-perfil" class="tab-pane fade in active show">
                            <form action="#" method="post">
                                @csrf
                                <div class="form-group">
                                    <label for="name">Nombre y apellidos</label>
                                    <input class="form-control" type="text" name="name" id="name" value="{{ auth()->user()->name }}">
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input class="form-control" type="email" name="email" id="email" value="{{ auth()->user()->email }}">
                                </div>
                                <div class="form-group">
                                    <label for="tlfno">Teléfono</label>
                                    <input class="form-control" type="text" name="tlfno" id="tlfno" value="{{ auth()->user()->tlfno }}">
                                </div>

                                @if (request()->slug_instalacion == "la-guijarrosa" || request()->slug_instalacion == "santaella")
                                @php
                                    $servicios = \App\Models\Servicio::where('duracion','mensual')->get();
                                    $servicios_usuarios = auth()->user()->servicio_usuario->whereIn('id_servicio',$servicios->pluck('id'));
                                    $tiene_pendientes = false;
                                    foreach ($servicios_usuarios as $key => $value) {
                                        if (count($value->recibos_sin_pago)>0) {
                                            $tiene_pendientes = true;
                                        }
                                    }
                                @endphp
                                    @if (!$tiene_pendientes)
                                        <div class="form-group">
                                            <label for="pago_recurrente" >¿Activar pago recurrente?</label>
                                            <input type="checkbox" style="width: fit-content" name="pago_recurrente" id="pago_recurrente" {{ auth()->user()->pago_recurrente == "on" ? 'checked' : '' }}>
                                        </div>
                                    @else
                                        <i>Si quieres activar el pago recurrente tendras que tener todos los recibos pagados</i>
                                    @endif

                                @endif

                                <div class="mt-3">
                                    <button class="w-100 btn btn-form">Guardar cambios</button>
                                    @if(request()->slug_instalacion == "santaella" && auth()->user()->id == 3070)
                                        <button type="button" class="w-100 btn btn-danger mt-2" onclick="if(confirm('¿Estás seguro de que deseas eliminar tu perfil? Esta acción no se puede deshacer.')) { document.getElementById('delete-profile-form').submit(); }">Eliminar perfil</button>
                                        <form id="delete-profile-form" action="{{ route('delete.perfil', ['slug_instalacion' => request()->slug_instalacion]) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    @endif
                                </div>
                            </form>

                        </div>
                        <div id="passwd" class="tab-pane fade">
                            <form action="#" method="post">
                                @csrf
                                {{-- <div class="form-group">
                                    <label for="#">Contraseña actual</label>
                                    <input class="form-control" type="password" name="password" placeholder="Escribe la contraseña actual">
                                </div> --}}
                                <div class="form-group">
                                    <label for="#">Contraseña</label>
                                    <input class="form-control" type="password" name="password" placeholder="Escribe la nueva contraseña">
                                </div>
                                <div class="form-group">
                                    <label for="#">Repite la nueva contraseña</label>
                                    <input class="form-control" type="password" name="password_rep"  placeholder="Escribe de nuevo la nueva contraseña">
                                </div>
                                <div class="mt-3">
                                    <button class="w-100 btn btn-form">Cambiar contraseña</button>
                                </div>
                            </form>
                        </div>
                        @if (request()->slug_instalacion == "la-guijarrosa")
                            <div id="apertura-puerta" class="tab-pane fade">
                                @if(!isset($paso))
                                    <div class="col-md-3 text-center">
                                        <p>No tiene permiso para acceder a las instalaciones.</p>
                                    </div>
                                @elseif($paso->activo == 'on')
                                    <div class="text-center">
                                        @csrf
                                        @if(!isset($ultimoRegistro))
                                            <button type="button" class="btn btn-success" id="apertura">Abrir torno</button>
                                            @elseif(isset($ultimoRegistro) && $ultimoRegistro->estado == null || $ultimoRegistro->estado == 'salida_torno' || $ultimoRegistro->estado == 'entrada_gimnasio_usuario')
                                            <button type="button" class="btn btn-success" id="apertura">Abrir torno</button>
                                        @else
                                            <button type="button" class="btn btn-success" id="salida">Salida torno</button>
                                        @endif
                                        <button type="button" class="btn btn-success" id="apertura_gym">Abrir puerta gimnasio</button>
                                    </div>
                                    @else
                                        <div class="col-md-3 text-center">
                                            <p>No tiene permiso para acceder a las instalaciones.</p>
                                        </div>
                                    @endif
                            </div>

                            <input class="visually-hidden"  type="text"  id="longitud">
                            <input class="visually-hidden"  type="text"  id="latitud">
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
   $(document).ready(function(){
        $(".nav-tabs a").click(function(){
            $('.tab-content>div').removeClass('in active show');
            $('.nav-tabs li, .nav-tabs a').removeClass('active show');
            $(this).tab('show');
        });
    });
</script>


<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCT5hS4KbxtQbH2VQfDb7KWFK-BN7vVyeA"></script>


<script type="text/javascript">



    const watchId = navigator.geolocation.watchPosition(position => initMap(position),
    );





    function error() {
        alert('error, active su ubicación para abrir la puerta');
    }

    function stopWatch() {
        navigator.geolocation.clearWatch(watchId);
    }

    function initMap(position) {
        const { latitude, longitude } = position.coords;

        const center = {lat: latitude, lng: longitude};
        const options = {zoom: 15, scaleControl: true, center: center};
        // map = new google.maps.Map(document.getElementById('map'), options);

        const point = {lat: latitude, lng: longitude};

        // create marker
        // var marker = new google.maps.Marker({position: point, map: map});

        navigator.geolocation.clearWatch(watchId);

        const latitud=point.lat;

        $("#latitud").val(latitud);

        const longitud=point.lng;
        $("#longitud").val(longitud);

    }



</script>

<script>

    $("#apertura_gym").click(function() {


        $("#apertura_gym").html("Abriendo...").delay(1000);
        var formdata= {
            lat: $("#latitud").val(),
            lng: $("#longitud").val(),

        };

        console.log(formdata);

        $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },

            url : '/{{request()->slug_instalacion}}/apertura-gym',
            data :formdata,
            type : 'POST',
            dataType : 'json',
            success : function(json) {
                console.log(json.success);

            },
            error : function(json , xhr, status) {
                console.log(json.error);
            },
            complete : function(json , xhr, status) {
                location.reload();
                console.log('completa');
                $("#apertura_gym").html("Entrando");
            }


        });
    });
    $("#apertura").click(function() {


        $("#apertura").html("Abriendo...").delay(1000);
        var formdata= {
            lat: $("#latitud").val(),
            lng: $("#longitud").val(),

        };

        console.log(formdata);

        $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },

            url : '/{{request()->slug_instalacion}}/apertura-torno',
            data :formdata,
            type : 'POST',
            dataType : 'json',
            success : function(json) {
                console.log(json.success);

            },
            error : function(json , xhr, status) {
                console.log(json.error);
            },
            complete : function(json , xhr, status) {
                location.reload();

                console.log('completa');
                $("#apertura").html("Entrando");
            }


        });
    });

    $("#salida").click(function() {


        $("#salida").html("Abriendo...").delay(1000);
        var formdata= {
            lat: $("#latitud").val(),
            lng: $("#longitud").val(),

        };

        $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },

            url : '/{{request()->slug_instalacion}}/salida-torno',
            data :formdata,
            type : 'POST',
            dataType : 'json',
            success : function(json) {
                console.log(json.success);

            },
            error : function(json , xhr, status) {
                console.log(json.error);
            },
            complete : function(json , xhr, status) {
                location.reload();

                console.log('completa');
                $("#salida").html("Saliendo");
            }


        });
    });

</script>
@endsection
