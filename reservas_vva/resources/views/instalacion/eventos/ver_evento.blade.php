@extends('layouts.admin')

@section('pagename', 'Listado participantes')

@section('style')
    <style>
        .clickable {
            cursor: pointer;
        }
        .nav-tabs a.active, .nav-tabs a.active:focus {
            color: white !important;
            background: #0057bc !important;
        }
        div.cs-skin-slide .cs-options ul li span {
            text-transform: uppercase;
        }
        .nav-tabs-fillup {
            display: none !important;
        }
        .nav-tab-dropdown {
            display: inline-block !important;
        }
        .nav-tab-dropdown.cs-wrapper.full-width {
            padding: 0 15px;
        }
        .nav-tab-dropdown .cs-select .cs-placeholder {
            font-size: 16px;
            font-weight: bold;
        }
    </style>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12 m-b-10">
        <div class="p-l-20 p-r-20 p-b-10 pt-3">
            <div>
                <h3 class="text-primary no-margin tituloEvento">Evento "{{ $evento->nombre }}"</h3>
            </div>
            <a href="/{{ request()->slug_instalacion }}/admin/eventos/" class="btn btn-warning mt-2"><i class="fas fa-arrow-left mr-2"></i> Volver</a>
        </div>

            <div class="p-t-15 p-b-15 p-l-20 p-r-20">
                <div class="card ">
                    <div class="card-header">
                        <div class="card-title">Participantes {{$evento->nombre}}</div>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                                <div>
                                    <table class="table table-hover" id="table-reservas" style="width: 100% !important;">
                                        <thead>
                                            <tr>
                                                <th data-priority="1">USUARIO COMPRADOR</th>
                                                @foreach($cabeceras_campos as $cabecera)
                                                    <th style="text-transform: uppercase;">{{$cabecera->label}}</th>
                                                @endforeach
                                                <th>Fecha creación pedido</th>
                                                <th>Fecha completado pedido</th>
                                                <th data-priority="3">Importe</th>
                                                <th data-priority="4">#</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <div class="text-right">
                                                @if(request()->slug_instalacion == "feria-jamon-villanuevadecordoba")
                                                Total: {{ $participantes_evento->count() }} participantes<br>
    
                                                @php
                                                    $amount_total = 0;
                                                @endphp
                                            
                                                @foreach ($participantes_evento as $participante)
                                                    @php
                                                        // Sumar el importe del pedido del participante
                                                        $amount_pedido = $participante->pedido->amount;
                                                        $amount_total += $amount_pedido;
                                                    @endphp
                                                @endforeach
                                            
                                                Total generado: <span id="totalGenerado">{{ $amount_total }}</span>€
                                                @else
                                                Total: {{$participantes_evento->count()}} participantes<br>
                                                @if (request()->slug_instalacion == "ciprea24")
                                                    @php
                                                        $amount_total = 0;
                                                    @endphp
                                                    @foreach ($participantes_evento as $participante)
                                                        @php
                                                            $amount_pedido = $participante->pedido->amount;
                                                            $amount_total += $amount_pedido;
                                                        @endphp
                                                    @endforeach
                                                    Total generado: <span id="totalGenerado">{{$amount_total}}</span>€
                                                @else
                                                Total generado: <span id="totalGenerado">{{$participantes_evento->count() * $evento->precio_participante}}</span>€
                                                @endif
                                                    @endif
                                                
                                          
                                            </div>
                                            @foreach ($participantes_evento as $participante)
                                                @if ($participante->pedido)
                                                    @if($participante->pedido->estado == 'pagado')
                                                        <tr>
                                                            <td><a href="/{{ request()->slug_instalacion }}/admin/users/{{ $participante->usuario->id }}/ver">{{ $participante->usuario->name }}</a></td>
{{--                                                            {{dd($participante->valores_campos_personalizados)}}
 --}}                                                            @foreach($participante->valores_campos_personalizados as $content)

                                                                <td>
                                                                    {{$content->valor}}
                                                                </td>
                                                            @endforeach

                                                            <td>
                                                                {{\Carbon\Carbon::parse($participante->pedido->created_at)->format('d-m-Y h:i:s')}}
                                                            </td>
                                                            <td>
                                                                {{\Carbon\Carbon::parse($participante->pedido->updated_at)->format('d-m-Y h:i:s')}}
                                                            </td>
                                                            <td>
                                                                @if (request()->slug_instalacion == "ciprea24")
                                                                    @php
                                                                        $amount_pedido = $participante->pedido->amount;
                                                                        echo $amount_pedido . '€';
                                                                    @endphp
                                                                @elseif(request()->slug_instalacion == "feria-jamon-villanuevadecordoba")
                                                                    @php
                                                                        $amount_pedido = $participante->pedido->amount;
                                                                        echo $amount_pedido . '€';
                                                                    @endphp
                                                                @else
                                                                    {{$evento->precio_participante}}€
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <div class="d-flex"  style="gap: 3px">
                                                                    <a href="/{{ request()->slug_instalacion }}/admin/eventos/{{ $participante->id_evento }}/participante/{{ $participante->id }}" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                                                                    <a href="/{{ request()->slug_instalacion }}/admin/eventos/{{ $participante->id_evento }}/participante/{{ $participante->id }}/delete"  onclick="return confirm('¿Estás seguro que quieres eliminar este participante?');"  class="btn btn-danger"><i class="fas fa-trash"></i></a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endif
                                                    @if ($loop->last)
                                                        <tr>
                                                            <td></td>
                                                            @foreach($cabeceras_campos as $cabecera)
                                                                <td></td>
                                                            @endforeach
                                                            <td></td>
                                                            <td></td>
                                                            @if (request()->slug_instalacion == "ciprea24")
                                                                <td> <strong style="font-weight: bold">Total: </strong>{{$amount_total}}€</td>
                                                                @elseif(request()->slug_instalacion == "feria-jamon-villanuevadecordoba")
                                                                <td><strong style="font-weight: bold">Total: </strong>{{ $amount_total }}€</td>
                                                            @else
                                                                <td> <strong style="font-weight: bold">Total: </strong>{{$participantes_evento->count() * $evento->precio_participante}}€</td>

                                                            @endif
                                                            <td></td>
            
                                                        </tr>
                                                    @endif
                                            @endforeach
      
                                        </tbody>
                                    </table>

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

        let tituloExcel = $('.tituloEvento').text() + ' - ' + $('#totalGenerado').text() + '€';

        $('#table-reservas').DataTable({
            responsive:true,
            "info": false,
            "paging": false,
            "order": [[ 0, "desc"]],
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'excel',
                    text: 'Exportar Excel',
                    title: tituloExcel,
                    filename: function(){
                        return $('.tituloEvento').text();
                    },
 
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4,5,7],
                        // ordena para desc
                    }
                }
            ],
        });

        $(".nav-tabs a").click(function(){
            $('.tab-content>div').removeClass('in active show');
            $('.nav-tabs li, .nav-tabs a').removeClass('active show');
            $(this).tab('show');
        });
    });
</script>
@endsection
