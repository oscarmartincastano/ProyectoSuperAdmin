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
            {{-- // nombre	email	servicio	ultimo recibo pagado activo --}}

                                    <table class="table table-hover " id="table-reservas" style="width: 100% !important;">
                                        <thead>
                                            <tr>
                                                <th data-priority="1">Nombre</th>
                                                <th>Email</th>
                                                <th>Servicio</th>
                                                <th>Último recibo pagado</th>
                                                <th>Activo</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data as $item)
                                                <tr>
                                                    <td>{{$item['nombre']}}</td>
                                                    <td>{{$item['email']}}</td>
                                                    <td>{{$item['servicio']}}</td>
                                                    <td>{{$item['ultimo_recibo_pagado']}}</td>
                                                    <td>{{$item['activo']}}</td>
                                                </tr>
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
            responsive: true,
            "info": false,
            "paging": true,
            language: {
            url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            dom: 'Bfrtip',
            "buttons": [
            {
                extend: 'csv',
                charset: 'UTF-8',
                bom: true,
                text: 'Exportar CSV',
                className: 'buttons-csv',
                filename: 'Listado de abonados',
                exportOptions: {
                columns: [0, 1, 2, 3, 4]
                },
                fieldSeparator: ';',
                extension: '.csv',
                title: 'Listado de abonados',
                customize: function (csv) {
                return csv.replace(/"/g, '');
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
