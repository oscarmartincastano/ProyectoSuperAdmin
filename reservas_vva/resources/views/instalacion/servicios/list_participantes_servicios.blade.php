@extends('layouts.admin')

@section('pagename', 'Listado abonados')

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
        .dt-button {
            display: none;
        }
        .buttons-csv {
            display: block;
            background: #28a745;
            color: white;
            padding: 5px 10px;
        }
    </style>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12 m-b-10">
            {{-- <div class="p-l-20 p-r-20 p-b-10 pt-3">
                <div>
                    <h3 class="text-primary no-margin">Participantes"</h3>
                </div>
            </div> --}}

            <div class="p-t-15 p-b-15 p-l-20 p-r-20">
                <div class="card ">
                    <div class="card-header">
                        <div class="card-title">Abonados</div>
                        <div class="mt-3">
                            <span><strong class="total">Total: €</strong></span>
                        </div>
                        <div class="mt-3">
                            <span><strong>Total abonados: {{$participantesexportar->count()}}</strong></span>
                        </div>
                        {{-- <div class="mt-3">
                            <form action="/{{ request()->slug_instalacion }}/admin/configuracion/servicios/exportar_abonados" method="POST">
                                @csrf
                                <input type="hidden" name="participantes" value="{{serialize($participantespago->toArray())}}">
                                <input type="submit" value="Exportar" class="btn btn-primary btn-sm">
                            </form>
                        </div> --}}
            
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                                <div>
                                    <table class="table table-hover " id="table-reservas" style="width: 100% !important;">
                                        <thead>
                                            <tr>
                                                <th data-priority="1">USUARIO COMPRADOR</th>
                                                {{-- <th>DATOS ABONADO</th> --}}
                                                    @php
                                                    if($participantes[0]->servicio->participantes->count() > 0){
                                                        $campos = $participantes[0]->valores_campos_personalizados;
                                                    }else{
                                                        $campos = [];
                                                    }
                                                    @endphp
                                                    @foreach ($campos as $campo)
                                                        <th data-priority="2">{!! $campo->campo->label !!}</th>
                                                    @endforeach
                                                    
                                                <th data-priority="2">EVENTO INSCRITO</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($participantes as $participante)
                                                @if($participante->pedido->estado == 'pagado')
                                                @php
                                                if($participante->servicio->participantes->count() > 0){
                                                    $campos = $participante->valores_campos_personalizados;
                                                }else{
                                                    $campos = [];
                                                }
                                                @endphp
                                                            @if (count($campos) == 11)

                                                    <tr>
                                                        <td>
                                                            <a href="/{{ request()->slug_instalacion }}/admin/users/{{$participante->usuario->id}}/ver">{{$participante->usuario->name}}</a>
                                                        </td>

                                                                
                                                            
                                                            @foreach ($campos as $campo)
                                                                @if ($campo->campo->tipo == 'select')
                                                                @php
                                                                $opciones = unserialize($campo->campo->opciones);
                                                                @endphp
                                                                @foreach ($opciones as $opcion)
                                                                    @if ($opcion['pextra'] == $campo->valor)
                                                                        <td>{!! $opcion['texto'] !!}</td>
                                                                        @if ($opcion['pextra'] == 'Si')
                                                                            <div class="d-none precioAbonado"  data-precio="15"></div>
                                                                        @else
                                                                            <div class="d-none precioAbonado" data-precio="10"></div>
                                                                        @endif
                                                                    @endif
                                                                @endforeach
                                                                @else
                                                                <td>{!! $campo->valor !!}</td>
                                                                
                                                                @endif
                                                            @endforeach
                                                        <td>
                                                            {{$participante->servicio->nombre}}
                                                        </td>
                                                    </tr>
                                                    @endif

                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="text-right p-3">
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
<script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
<script>
    $(document).ready(function () {

        $('#table-reservas').DataTable({
                 responsive:true,
                "info": false,
                "paging": true,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
                },
                dom: 'Bfrtip',
                // quiero que se exporten con tildes y cada uno en su columna y no todo junto
                "buttons": [
                    {
                        extend: 'csv',
                        charset: 'UTF-8',
                        bom: true,
                        text: 'Exportar CSV',
                        className: 'buttons-csv',
                        filename: 'Listado de abonados',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
                        }
                    }
                ],
                "orderable": true,

            });

        $(".nav-tabs a").click(function(){
            $('.tab-content>div').removeClass('in active show');
            $('.nav-tabs li, .nav-tabs a').removeClass('active show');
            $(this).tab('show');
        });

        let total = 0;
        $.each($(".precioAbonado"), function (indexInArray, valueOfElement) { 
            total += $(this).data('precio');
        });
        $('.total').text('Total: '+total+'€');
    });
</script>
@endsection
