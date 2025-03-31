@extends('new.layout.base')

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
    @media(max-width: 992px) {
        .contenido-principal {
            padding: unset !important;
            padding-top: 80px !important;
        }
        .hide-responsive {
            display: none;
        }
        .show-responsives {
            display: block !important;
            font-size: 14px;
        }
    }
    @media(max-width: 582px) {
        .contenido-principal {
            padding-top: 0 !important;
        }
    }
    .show-responsives {
        display: none;
    }
</style>
@endsection

@section('post-header')
    <div class="post-header">
        <div class="menu-header">
            <a href="/{{ request()->slug_instalacion }}/perfil" class="{{ request()->is(request()->slug_instalacion . '/perfil') ? 'active' : '' }}">Mi perfil</a>
            @if (request()->slug_instalacion != "villafranca-navidad" and request()->slug_instalacion != "villafranca-actividades" and request()->slug_instalacion != "ciprea24" and request()->slug_instalacion != "eventos-bodega" and request()->slug_instalacion != "la-guijarrosa")
            <a href="/{{ request()->slug_instalacion }}/mis-reservas" class="{{ request()->is(request()->slug_instalacion . '/mis-reservas') ? 'active' : '' }}">Mis reservas</a>
            @endif
            <a href="/{{ request()->slug_instalacion }}/new/mis-eventos" class="{{ request()->is(request()->slug_instalacion . '/new/mi-perfil') ? 'active' : '' }}">Mis eventos</a>
            @if (request()->slug_instalacion != "villafranca-navidad")
            <a href="/{{ request()->slug_instalacion }}/new/mis-servicios" class="{{ request()->is(request()->slug_instalacion . '/new/mi-perfil') ? 'active' : '' }}">Mis servicios</a>
            <a href="/{{ request()->slug_instalacion }}/new/mis-recibos" class="{{ request()->is(request()->slug_instalacion . '/new/mis-recibos') ? 'active' : '' }}">Mis recibos</a>
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
                <div class="card-body" style="padding: 35px; overflow-y: hidden">
                    <div class="titulo-card">Mis servicios</div>
                    <div class="contenido-card">


                        <table class="table  w-100" style="overflow-x: auto !important;-webkit-overflow-scrolling: touch !important;">
                            <thead>
                                <tr>
                                    <th>Servicio contratado</th>
                                    <th>Fecha expiración</th>
                                    <th>Contratado</th>

                                    @if (request()->slug_instalacion == "los-agujetas-de-villafranca")
                                    <th>Datos Participante</th>
                                    @endif
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody>

                                @forelse ($servicio_usuario as $item)
                                <tr>

                                    <td>
                                        {!! $item->servicio->nombre !!}
                                    </td>
                                    <td>{!! \Carbon\Carbon::parse($item->fecha_expiracion)->format('d-m-Y') !!}</td>
                                    <td>
                                        @if($item->activo == 'si')
                                            SI
                                        @else
                                            NO
                                        @endif
                                    </td>
                                    @if (request()->slug_instalacion == "los-agujetas-de-villafranca")
                                    <td>

                                        @php
                                        if($item->servicio->participantes->where('id_usuario',$item->id_usuario)->count() > 0){
                                            $campos = $item->servicio->participantes->where('id_usuario',$item->id_usuario);
                                        }else{
                                            $campos = [];
                                        }
                                        @endphp
                                        @foreach ($campos as $participante_campo)
                                            <div class="card p-3 mt-2">
                                            @foreach ($participante_campo->valores_campos_personalizados as $campo)
                                                <div>
                                                    <strong>{!! $campo->campo->label !!}:</strong>
                                                    @if ($campo->campo->tipo == 'select')
                                                    @php
                                                    $opciones = unserialize($campo->campo->opciones);
                                                    @endphp
                                                    @foreach ($opciones as $opcion)
                                                        @if ($opcion['pextra'] == $campo->valor)
                                                            {!! $opcion['texto'] !!}<br>
                                                        @endif
                                                    @endforeach
                                                    @else
                                                    {!! $campo->valor !!}<br>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>

                                        @endforeach

                                    </td>
                                    @endif
                                    <td>
                                        @if(count($item->recibos_sin_pago)>0)
                                            <a href="/{{ $instalacion->slug }}/new/mis-recibos" class="btn btn-warning">Tiene recibos pendientes</a>
                                        @elseif($item->activo == 'si' && count($item->recibos_sin_pago)==0)
                                            <a href="#" class="btn btn-danger btn-dar-baja" data-toggle="modal" data-target="#confirmacion-modal" data-id="{{ $item->id }}">Dar de baja</a>
                                        @elseif($item->activo == 'no' && count($item->recibos_sin_pago)==0)
                                        <a href="/{{ $instalacion->slug }}/servicios/{{$item->servicio->id}}/contratar-de-nuevo" class="btn btn-success contratar">Contratar de nuevo</a>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No se encuentran registros</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirmacion-modal" tabindex="-1" role="dialog" aria-labelledby="confirmacionBajaModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmacionBajaModalLabel">Confirmación de baja</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="cerrar-modal-btn">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ¿Está seguro de que desea dar de baja este servicio?
                </div>
                <div class="modal-footer">
                    <a href="" class="btn btn-danger" id="aceptar">Aceptar</a>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" id="cancelar-baja-btn">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
    $(document).ready(function () {
        $('.table-reservas').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
        });


        $('.btn-dar-baja').click(function (e) {
                let slug= "{!!$instalacion->slug!!}";
                $('#aceptar').attr('href','/'+slug+'/new/mis-servicios/'+$(this).data('id')+'/baja')
                $('#confirmacion-modal').modal('show');
        });

        $('#cancelar-baja-btn, #cerrar-modal-btn').click(function() {
            $('#confirmacion-modal').modal('hide');
        });

    });
</script>
@endsection
