@extends('layouts.admin')

@php
    function checkConsec($d) {
        for($i=0;$i<count($d);$i++) {
            if(isset($d[$i+1]) && $d[$i]+1 != $d[$i+1]) {
                return false;
            }
        }
        return true;
    }
@endphp

@section('content')
<div class="row">
    <div class="col-lg-12 m-b-10">

            <div class="p-l-20 p-r-20 p-b-10 pt-3">
                <div>
                    <h3 class="text-primary no-margin">Configuraciones</h3>
                </div>
            </div>

            <div class="p-t-15 p-b-15 p-l-20 p-r-20">
                <div class="card ">
                    <div class="card-header">
                        <div class="card-title">Instalación</div>
                    </div>
                    <div class="card-body">

                        <table class="table table-condensed table-hover" id="table-reservas" style="width: 100% !important;">

                            <tbody>
                                <tr>
                                    <th>Tipo de calendario</th>
                                    @if($tipoCalendario == 0)
                                    <td>Calendario 1</td>
                                    @elseif($tipoCalendario == 1)
                                    <td>Calendario 2</td>
                                    @endif
                                    <td><a href="/{{ request()->slug_instalacion }}/admin/configuracion/instalacion/edit/tipo_calendario" class="btn btn-primary"><i class="fas fa-edit"></i></a></td>
                                </tr>
                                <tr>
                                    <th>Nombre</th>
                                    <td>{{ $instalacion->nombre }}</td>
                                    <td><a href="/{{ request()->slug_instalacion }}/admin/configuracion/instalacion/edit/nombre" class="btn btn-primary"><i class="fas fa-edit"></i></a></td>
                                </tr>
                                <tr>
                                    <th>Logo</th>
                                    <td><img src="/img/{{ $instalacion->slug }}.png" style="max-width: 200px"></td>
                                    <td><a href="/{{ request()->slug_instalacion }}/admin/configuracion/instalacion/edit/logo" class="btn btn-primary"><i class="fas fa-edit"></i></a></td>
                                </tr>
                                <tr>
                                    <th>Portada</th>
                                    <td><img src="/img/portadas-inst/{{ $instalacion->slug }}.jpg" style="max-width: 200px"></td>
                                    <td><a href="/{{ request()->slug_instalacion }}/admin/configuracion/instalacion/edit/cover" class="btn btn-primary"><i class="fas fa-edit"></i></a></td>
                                </tr>
                                @if($instalacion->ver_horario==true)
                                <tr>
                                    <th>Horario instalación</th>
                                    <td>
                                        {{-- <pre>{{ print_r($instalacion, true) }}</pre> --}}
                                        @if($instalacion->horario)
                                            @foreach (unserialize($instalacion->horario) as $index => $horario)
                                                <div class="d-flex align-items-center">
                                                    <div>{{ $index }} -></div>
                                                    <div class="ml-2 border p-1">
                                                        @foreach ($horario['intervalo'] as $item)
                                                            {{ $item['hinicio'] }} - {{ $item['hfin'] }}<br>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td><a href="/{{ request()->slug_instalacion }}/admin/configuracion/instalacion/edit/horario" class="btn btn-primary"><i class="fas fa-edit"></i></a></td>
                                </tr>
                                @endif

                                @php
                                // dd($instalacion->servicios);
                                @endphp
                                @if($instalacion->ver_servicios==true)
                                <tr>
                                    <th>Servicios</th>
                                    <td>
                                        @if ($instalacion->servicios && $instalacion->servicios != null && $instalacion->servicios != '[]')
                                            @foreach (unserialize($instalacion->servicios) as $item)
                                                @if(\App\Models\Servicios_adicionales::find($item))
                                                    {{ \App\Models\Servicios_adicionales::find($item)->nombre }},
                                                @else
                                                    {{ \App\Models\Servicio::find($item)->nombre }},
                                                @endif
                                            @endforeach
                                        @endif
                                    </td>
                                    <td><a href="/{{ request()->slug_instalacion }}/admin/configuracion/instalacion/edit/servicios" class="btn btn-primary"><i class="fas fa-edit"></i></a></td>
                                </tr>

                                @endif
                                {{-- <tr>
                                    <th>Horario de la instalación</th>
                                    <td>sdf</td>
                                    <td><a href="/{{ request()->slug_instalacion }}/admin/configuracion/instalacion/edit/horario" class="btn btn-primary"><i class="fas fa-edit"></i></a></td>
                                </tr> --}}
                                <tr>
                                    <th>Galería</th>
                                    <td class="d-flex">
                                        @if (file_exists(public_path() . '/img/galerias/'. $instalacion->slug))

                                            @foreach (\File::files(public_path() . '/img/galerias/'.$instalacion->slug) as $item)
                                                <div class="position-relative p-2 mr-2">
                                                    <img src="/img/galerias/{{ $instalacion->slug }}/{{pathinfo($item)['basename']}}"
                                                        style="width: 30px">
                                                </div>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td><a href="/{{ request()->slug_instalacion }}/admin/configuracion/instalacion/edit/galeria" class="btn btn-primary"><i class="fas fa-edit"></i></a></td>
                                </tr>
                                <tr>
                                    <th>Dirección</th>
                                    <td>{{ $instalacion->direccion }}</td>
                                    <td><a href="/{{ request()->slug_instalacion }}/admin/configuracion/instalacion/edit/direccion" class="btn btn-primary"><i class="fas fa-edit"></i></a></td>
                                </tr>
                                <tr>
                                    <th>Teléfono</th>
                                    <td>{{ $instalacion->tlfno }}</td>
                                    <td><a href="/{{ request()->slug_instalacion }}/admin/configuracion/instalacion/edit/tlfno" class="btn btn-primary"><i class="fas fa-edit"></i></a></td>
                                </tr>
                                @if($instalacion->ver_normas==true)
                                <tr>
                                    <th>Html normas</th>
                                    <td>{{ $instalacion->html_normas }}</td>
                                    <td><a href="/{{ request()->slug_instalacion }}/admin/configuracion/instalacion/edit/html_normas" class="btn btn-primary"><i class="fas fa-edit"></i></a></td>
                                </tr>
                                @endif
                                @if($instalacion->ver_politica==true)
                                <tr>
                                    <th>Html política de privacidad</th>
                                    <td>{{ $instalacion->politica }}</td>
                                    <td><a href="/{{ request()->slug_instalacion }}/admin/configuracion/instalacion/edit/politica" class="btn btn-primary"><i class="fas fa-edit"></i></a></td>
                                </tr>
                                @endif
                                @if($instalacion->ver_condiciones==true)
                                <tr>
                                    <th>Html condiciones</th>
                                    <td>{{ $instalacion->condiciones }}</td>
                                    <td><a href="/{{ request()->slug_instalacion }}/admin/configuracion/instalacion/edit/condiciones" class="btn btn-primary"><i class="fas fa-edit"></i></a></td>
                                </tr>
                                @endif
                                <tr>
                                    <th>Normas de visualización</th>
                                    <td>Visualización</td>
                                    <td><a href="/{{ request()->slug_instalacion }}/admin/configuracion/instalacion/edit/normas_visualizacion" class="btn btn-primary"><i class="fas fa-edit"></i></a></td>
                                </tr>
                                <tr>
                                    <th>Prefijo pedido</th>
                                    <td>{{ $instalacion->prefijo_pedido  }}-########</td>
                                    <td><a href="/{{ request()->slug_instalacion }}/admin/configuracion/instalacion/edit/prefijo_pedido" class="btn btn-primary"><i class="fas fa-edit"></i></a></td>
                                </tr>
                                <tr style="cursor: not-allowed;">
                                    <th>Slug</th>
                                    <td>{{ $instalacion->slug }}</td>
                                    <td></td>
                                </tr>
                                {{-- <tr>
                                    <th style="border-bottom: 1px solid #dee2e6;">Tipo de visualización reservas</th>
                                    <td>{{ $instalacion->tipo_reservas->nombre }}</td>
                                    <td><a href="/{{ request()->slug_instalacion }}/admin/configuracion/instalacion/edit/tipo_reservas" class="btn btn-primary"><i class="fas fa-edit"></i></a></td>
                                </tr> --}}
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
