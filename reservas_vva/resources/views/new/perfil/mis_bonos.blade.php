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
            <a href="/{{ request()->slug_instalacion }}/new/mis-eventos">Mis eventos</a>
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
                    <div class="titulo-card">Mis bonos</div>
                    <div class="contenido-card">
                        <table class="table table-bonos w-100" style="overflow-x: auto !important;-webkit-overflow-scrolling: touch !important;">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Usos restantes</th>
                                    <th>Precio</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($bonos_usuario as $item)
                                <tr>
                                    <td>{{$item->bono->nombre}}</td>
                                    <td>{{$item->num_usos}}</td>
                                    <td>{{$item->precio}}€</td>
                                    @if($item->estado == 'En proceso')
                                        <td class="warning"><b>En proceso de pago</b></td>
                                    @elseif($item->estado == 'active')
                                        <td style="color: green;"><b>Disponible</b></td>
                                    @elseif($item->num_usos == 0)
                                        <td style="color: red;"><b>Usos agotados</b></td>
                                    @endif

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
@endsection

@section('script')
<script>
    $(document).ready(function () {
        $('.table-bonos').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
        });
    });
</script>

@endsection
