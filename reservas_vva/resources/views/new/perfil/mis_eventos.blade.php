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
        padding: 20px;
    }
    th {
        border-top: 0 !important;
    }
    .table-eventos td {
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
    @media(max-width: 992px) {
        .contenido-principal {
            padding: unset !important;
            padding-top: 0px !important;
        }
    }

</style>
@if (request()->slug_instalacion == "villafranca-navidad" or request()->slug_instalacion == "villafranca-actividades" or request()->slug_instalacion == "ciprea24" and request()->slug_instalacion == "eventos-bodega" or request()->slug_instalacion == "feria-jamon-villanuevadecordoba")
    <style>
        .minWidthEvento{
            min-width: 200px !important;
        }
        .minWidthFecha{
            min-width: 150px !important;
        }
        .minWidthParticipante{
            min-width: 400px !important;
        }
    </style>
@endif
@endsection

@section('post-header')
    <div class="post-header">
        <div class="menu-header">
            <a href="/{{ request()->slug_instalacion }}/perfil" class="{{ request()->is(request()->slug_instalacion . '/new/perfil') ? 'active' : '' }}">Mi perfil</a>
            @if (request()->slug_instalacion != "villafranca-navidad" and request()->slug_instalacion != "villafranca-actividades" and request()->slug_instalacion != "ciprea24" and request()->slug_instalacion != "eventos-bodega" and request()->slug_instalacion != "la-guijarrosa" and request()->slug_instalacion != "feria-jamon-villanuevadecordoba")
            <a href="/{{ request()->slug_instalacion }}/mis-reservas" class="{{ request()->is(request()->slug_instalacion . '/new/mis-reservas') ? 'active' : '' }}">Mis reservas</a>
            @endif
            <a href="/{{ request()->slug_instalacion }}/mis-eventos" class="{{ request()->is(request()->slug_instalacion . '/new/mis-eventos') ? 'active' : '' }}">Mis eventos</a>
            @if (request()->slug_instalacion != "villafranca-navidad" and request()->slug_instalacion != "villafranca-actividades" and request()->slug_instalacion != "ciprea24" and request()->slug_instalacion != "eventos-bodega" and request()->slug_instalacion != "feria-jamon-villanuevadecordoba")
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
                    <div class="titulo-card">Mis eventos</div>

                    <div class="contenido-card">
                        <ul class="nav nav-tabs">
                            @if (request()->slug_instalacion != "villafranca-navidad" and request()->slug_instalacion != "villafranca-actividades" and request()->slug_instalacion != "ciprea24" and request()->slug_instalacion != "eventos-bodega" and request()->slug_instalacion != "la-guijarrosa" and request()->slug_instalacion != "feria-jamon-villanuevadecordoba")
                            <li class="active"><a href="#mensuales" class="active show">Escuelas</a></li>
                            @endif
                            @if (request()->slug_instalacion != "villafranca-navidad" and request()->slug_instalacion != "villafranca-actividades" and request()->slug_instalacion != "ciprea24" and request()->slug_instalacion != "eventos-bodega" and request()->slug_instalacion != "la-guijarrosa" and request()->slug_instalacion != "feria-jamon-villanuevadecordoba")
                            <li ><a href="#casuales">Eventos</a></li>
                            @else
                            <li class="active"><a href="#casuales" id="eventosTab" class="active show">Eventos</a></li>
                            @endif
                        </ul>
                        <div class="tab-content">
                            <div id="mensuales" class="tab-pane fade @if (request()->slug_instalacion != "villafranca-navidad" and request()->slug_instalacion != "villafranca-actividades" and request()->slug_instalacion != "ciprea24" and request()->slug_instalacion != "eventos-bodega" and request()->slug_instalacion != "feria-jamon-villanuevadecordoba" ) active in show @endif">
                                <table class="table table-eventos  w-100" style="overflow-x: auto !important;-webkit-overflow-scrolling: touch !important;">
                                    <thead>
                                        <tr>
                                            <th>Cartel</th>
                                            <th>Evento</th>
                                            <th>Mes</th>
                                            <th>Datos participante</th>
                                            <th>Estado</th>


                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse (auth()->user()->participaciones->where('tipo_evento', 'Mensual') as $item)
                                        @php
                                        $fechaFin = strtotime($item->evento->insc_fecha_fin);
                                        $fechaActual = strtotime(date('Y-m-d'));// Obtén la fecha actual
                                        @endphp
                                        @if ($fechaFin > $fechaActual)
                                        <tr>
                                            <td><img src="/img/eventos//{{ request()->slug_instalacion }}/{{ $item->evento->id }}.jpg" alt="" style="max-width:50px"></td>
                                            <td>{{ $item->evento->nombre }}</td>
                                            <td>
                                                @foreach ($item->meses_suscrito as $mes)
                                                    @if ($mes->pedido->estado == 'pagado')
                                                        {{ strftime('%B %Y', strtotime('01-'.$mes->num_mes.'-'.$mes->num_year)) }}<br>
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                <ul style="list-style:none; padding:0">
                                                    @foreach ($item->valores_campos_personalizados as $valores_participantes)
                                                        <li><strong>{{ $valores_participantes->campo->label }}: </strong>{{ $valores_participantes->valor }}</li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                            <td class="text-uppercase">
                                                @if ($item->ultimo_mes_suscrito->pedido->estado == 'En proceso')

                                                        Pago de {{ date('d')<=25 ? strftime('%B') : strftime('%B', strtotime('01-' . (date('m')+1) . '-'.date('Y'))) }} en proceso

                                                @else


                                                           @if($item->ultimo_mes_suscrito->num_mes == date('m') && date('Y') == $item->ultimo_mes_suscrito->num_year)
                                                            <span class="text-success">request()->slug_instalacion != "villafranca-navidad" and request()->slug_instalacion != "villafranca-actividades"</span>
                                                        @else

                                                               @if (date('m') == $item->ultimo_mes_suscrito->num_mes + 1 && date('d') <= 25)
                                                                <a href="/{{ request()->slug_instalacion }}/mis-eventos/{{ $item->id }}/renovar" onclick="return confirm('¿Estás seguro que quieres renovar tu inscripción en este evento?')"  class="btn btn-warning">Renovar <br> {{ date('d')<=10 ? strftime('%B') : strftime('%B', strtotime('01-' . (date('m')+1) . '-'.date('Y'))) }}</a>
                                                            @elseif($item->ultimo_mes_suscrito->num_mes > date('m') && date('Y') == $item->ultimo_mes_suscrito->num_year)
                                                                <span class="text-success">A la espera de comenzar</span>
                                                            @else
                                                                   @if(date('y') >= $item->ultimo_mes_suscrito->num_year)
                                                                       @elseif((date('m') - $item->ultimo_mes_suscrito->num_mes) < 2)
                                                                <a href="/{{ request()->slug_instalacion }}/mis-eventos/{{ $item->id }}/renovar" onclick="return confirm('¿Estás seguro que quieres renovar tu inscripción en este evento?')"  class="btn btn-warning" >Renovar <br> {{ date('d')<=10 ? strftime('%B') : strftime('%B', strtotime('01-' . (date('m')+1) . '-'.date('Y'))) }}</a>
                                                                       @else
                                                                <span class="text-danger">Evento pasado</span>
                                                                       @endif

                                                                   @endif

                                                             @endif
                                                        @endif

                                            </td>

                                        </tr>
                                        @endif
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">No se encuentran registros</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div id="casuales" class="tab-pane fade @if (request()->slug_instalacion == "villafranca-navidad" or request()->slug_instalacion == "villafranca-actividades" or request()->slug_instalacion == "ciprea24" or request()->slug_instalacion == "eventos-bodega" or request()->slug_instalacion != "feria-jamon-villanuevadecordoba") in active show @endif">
                                <table class="table table-eventos  w-100" style="overflow-x: auto !important;-webkit-overflow-scrolling: touch !important;">
                                    <thead>
                                        <tr>
                                            @if (request()->slug_instalacion == "villafranca-navidad" or request()->slug_instalacion == "villafranca-actividades" or request()->slug_instalacion == "ciprea24" or request()->slug_instalacion == "eventos-bodega")
                                            @else
                                            <th>Cartel</th>
                                            <th>Evento</th>
                                            <th>Fecha</th>
                                            @endif
                                            <th>Datos participante</th>
                                            @if (request()->slug_instalacion == "villafranca-actividades" or request()->slug_instalacion == "ciprea24" or request()->slug_instalacion == "eventos-bodega" or request()->slug_instalacion != "feria-jamon-villanuevadecordoba")
                                                <th>Evento</th>
                                                <th>Fecha</th>
                                            @endif
                                            @if (request()->slug_instalacion == "villafranca-navidad" or request()->slug_instalacion == "villafranca-actividades" or request()->slug_instalacion == "ciprea24" or request()->slug_instalacion == "eventos-bodega" or request()->slug_instalacion != "feria-jamon-villanuevadecordoba")
                                            @else
                                            <th>Estado</th>
                                            @endif
                                            {{-- @if (request()->slug_instalacion == "villafranca-navidad" or request()->slug_instalacion == "villafranca-actividades")
                                            <th>Entradas</th>
                                            @endif --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            if(request()->slug_instalacion == "villafranca-actividades" or request()->slug_instalacion == "ciprea24" or request()->slug_instalacion == "eventos-bodega" or request()->slug_instalacion == "la-guijarrosa" ){
                                                $participaciones = auth()->user()->participaciones;
                                            }elseif(request()->slug_instalacion == "feria-jamon-villanuevadecordoba"){
                                                $participaciones = auth()->user()->participaciones()->groupBy('id_pedido')->get();
                                            }
                                            else{
                                                $participaciones = auth()->user()->participaciones;
                                            }
                                        @endphp


                                        @forelse ($participaciones as $item)
                                            @if ($item->id_evento)
                                                <tr>

                                                    @if (request()->slug_instalacion != "villafranca-navidad" and request()->slug_instalacion != "villafranca-actividades" and request()->slug_instalacion != "ciprea24" or request()->slug_instalacion != "eventos-bodega" or request()->slug_instalacion != "feria-jamon-villanuevadecordoba")

                                                        <td><img src="/img/eventos/{{ request()->slug_instalacion }}/{{ $item->evento->id }}.jpg" alt="" @if (request()->slug_instalacion == "eventos-bodega")

                                                            style="max-width:300px"
                                                            @else
                                                            style="max-width:50px"
                                                            @endif></td>
                                                        <td class="minWidthEvento">{{ $item->evento->nombre }}</td>
                                                        <td class="minWidthFecha">{{ date('d/m', strtotime($item->evento->fecha_inicio)) }} - {{ date('d/m', strtotime($item->evento->fecha_fin)) }}</td>
                                                    @endif
                                                    <td class="minWidthParticipante">
                                                        <ul style="list-style:none; padding:0">
                                                            @foreach ($item->valores_campos_personalizados as $valores_participantes)
                                                                @if($valores_participantes->valor)
                                                                    <li><strong>{{ $valores_participantes->campo->label }}: </strong> @if($valores_participantes->valor =="on") Si @elseif($valores_participantes->valor =="off")No @else{{ $valores_participantes->valor }}@endif</li>
                                                                @endif
                                                            @endforeach
                                                            {{-- @if (request()->slug_instalacion == "la-guijarrosa")
                                                                <li><strong>Nombre y apellidos: </strong> {{auth()->user()->name}}</li>
                                                                <li><strong>Teléfono: </strong> {{auth()->user()->tlfno}}</li>
                                                                <li><strong>Email: </strong> {{auth()->user()->email}}</li>
                                                            @endif --}}
                                                            @if (request()->slug_instalacion == "villafranca-navidad" or request()->slug_instalacion == "villafranca-actividades" or request()->slug_instalacion == "ciprea24" or request()->slug_instalacion == "eventos-bodega" or request()->slug_instalacion == "feria-jamon-villanuevadecordoba")
                                                            @php
                                                                $pedido = App\Models\Pedido::find($item->id_pedido);
                                                                $estado = $pedido->estado;
                                                            @endphp
                                                            <a href="/{{request()->slug_instalacion}}/descargar-entradas/{{$pedido->id}}" class="btn btn-primary mt-4" >Descargar</a>
                                                            @endif
                                                        </ul>
                                                    </td>
                                                    @if (request()->slug_instalacion == "villafranca-navidad" or request()->slug_instalacion == "villafranca-actividades" or request()->slug_instalacion == "ciprea24" or request()->slug_instalacion == "eventos-bodega" or request()->slug_instalacion != "feria-jamon-villanuevadecordoba")
                                                    @php
                                                        $pedido = App\Models\Pedido::find($item->id_pedido);
                                                        $estado = $pedido->estado;
                                                    @endphp
                                                    @endif
                                                    @if (request()->slug_instalacion == "villafranca-actividades")
                                                    @if($item->valores_campos_personalizados[1]->valor == null)
                                                        <td>{{$item->evento->nombre}} ({{$item->evento->precio_participante}}€)</td>
                                                    @elseif($item->valores_campos_personalizados[1]->valor == "Solo DJ's")
                                                        <td>{{$item->evento->nombre}} (3€)</td>
                                                    @else
                                                        <td>{{$item->evento->nombre}} (18€)</td>
                                                    @endif
                                                    <td>{{$item->fecha_pedido}} - {{$item->hora_inicio}}</td>
                                                    @endif
                                                    @if (request()->slug_instalacion == "ciprea24")
                                                        <td>
                                                            {{$item->evento->nombre}} <br>
                                                            <small><i>({{$item->valores_campos_personalizados[15]->valor}})</i></small>

                                                        </td>
                                                        @php
                                                            $fechaFormat_inicio = \Carbon\Carbon::parse($item->evento->fecha_inicio)->locale('es_ES')->format('d/m/Y');
                                                            $fechaFormat_fin = \Carbon\Carbon::parse($item->evento->fecha_fin)->locale('es_ES')->format('d/m/Y');
                                                        @endphp
                                                        <td>
                                                            {{$fechaFormat_inicio}} - {{$fechaFormat_fin}}
                                                        </td>
                                                    @endif
                                                    @if (request()->slug_instalacion == "villafranca-navidad" or request()->slug_instalacion == "villafranca-actividades" or request()->slug_instalacion == "ciprea24" or request()->slug_instalacion == "eventos-bodega" or request()->slug_instalacion != "feria-jamon-villanuevadecordoba")
                                                    @else
                                                    <td class="text-uppercase">
                                                        @if(strtotime($item->evento->fecha_inicio) <= strtotime(date('Y-m-d')) && strtotime($item->evento->fecha_fin) >= strtotime(date('Y-m-d')))

                                                        <span class="text-success">En proceso</span>
                                                        @elseif(strtotime($item->evento->fecha_fin) < strtotime(date('Y-m-d')))
                                                        Terminado
                                                        @else
                                                        @if (request()->slug_instalacion == "villafranca-navidad" or request()->slug_instalacion == "villafranca-actividades" or request()->slug_instalacion == "ciprea24" or request()->slug_instalacion == "eventos-bodega" )
                                                            @php
                                                                $pedido = App\Models\Pedido::find($item->id_pedido);
                                                                $estado = $pedido->estado;
                                                            @endphp
                                                        @if ($estado == "pagado")
                                                            <span class="text-success">Pagado</span>
                                                        @else
                                                            <span class="text-danger">Cancelado</span>
                                                        @endif
                                                        @elseif(request()->slug_instalacion == "feria-jamon-villanuevadecordoba")
                                                        <span class="text-success">Pagado</span>
                                                        @else
                                                        <span class="text-success">Inscrito</span>
                                                        @endif
                                                        @endif
                                                        @endif
                                                    </td>

                                                    {{-- @if (request()->slug_instalacion == "villafranca-navidad" or request()->slug_instalacion == "villafranca-actividades")
                                                        <td>
                                                            <a href="https://gestioninstalacion.es/villafranca-navidad/descargar-entradas/{{$pedido->id}}" class="btn btn-primary" >Descargar</a>
                                                        </td>
                                                    @endif --}}
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
        </div>
    </div>
@endsection

@section('script')
<script>
    $(document).ready(function () {
        $(".nav-tabs a").click(function(){
            $('.tab-content>div').removeClass('in active show');
            $('.nav-tabs li, .nav-tabs a').removeClass('active show');
            $(this).tab('show');

        });
        $('.active').click();
        window.location.hash = $('.active').attr('href');
    });
</script>
@endsection
