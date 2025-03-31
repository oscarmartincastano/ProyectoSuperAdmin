@extends('layouts.admin')

@section('pagename', 'Eventos creados')

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

        .fade:not(.show){
            opacity: 100 !important;
            display: none !important;
        }
    </style>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12 m-b-10">
            <div class="p-l-20 p-r-20 p-b-10 pt-3">
                <div>
                    <h3 class="text-primary no-margin">Evento "{{ $evento->nombre }}"</h3>
                </div>
                <a href="/{{ request()->slug_instalacion }}/admin/eventos/" class="btn btn-warning mt-2"><i class="fas fa-arrow-left mr-2"></i> Volver</a>
            </div>

            <div class="p-t-15 p-b-15 p-l-20 p-r-20">
                <div class="card ">
                    <div class="card-header">
                        <div class="card-title">Participantes</div>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <ul class="nav nav-tabs justify-content-center mb-2">
                                <li class="active" onclick="return false;"><a href="#pagados" class="active show">Pagados</a></li>
                                <li><a href="#pendientes" onclick="return false;">Pendientes de pago</a></li>
                            </ul>

                            <div id="pagados" class="tab-pane fade in active show">

                        @if($meses->count())

                        <ul class="nav nav-tabs nav-tabs-fillup" data-init-reponsive-tabs="dropdownfx">
                            @foreach ($meses as $index => $item)
                                <li class="nav-item">
                                    <a href="#"  @if($index == 0) class="active" @endif data-toggle="tab" data-target="#item{{ $item->num_year }}_{{ $item->num_mes }}"><span class="text-uppercase">{{ strftime('%B %Y', strtotime('01-' . $item->num_mes . '-'.$item->num_year)) }}</span></a>
                                </li>
                            @endforeach
                            {{-- <li class="nav-item">
                              <a href="#" class="active" data-toggle="tab" data-target="#tab-fillup1"><span>Home</span></a>
                            </li>
                            <li class="nav-item">
                              <a href="#" data-toggle="tab" data-target="#tab-fillup2"><span>Profile</span></a>
                            </li>
                            <li class="nav-item">
                              <a href="#" data-toggle="tab" data-target="#tab-fillup3"><span>Messages</span></a>
                            </li> --}}
                        </ul>
                        @endif

                            @foreach ($meses as $index => $item)
                                <div id="item{{ $item->num_year }}_{{ $item->num_mes }}" class="tab-pane fade in @if($index == 0) active show  @endif">
                                    <table class="table table-hover tabla-pago1" id="table-reservas-pagado{{ $item->num_year }}_{{ $item->num_mes }}">
                                        <thead>
                                            <tr>
                                                <th>Usuario comprador</th>
                                                @foreach ($evento->tipo_participante->campos_personalizados as $campo)
                                                    <th>{{ $campo->label }}</th>
                                                @endforeach
                                                {{-- <th>Número de pedido</th> --}}
                                                <th>#</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($participantes_evento->where('num_mes', $item->num_mes)->where('num_year', $item->num_year) as $participante)
                                                @if($participante->pedido->estado =="pagado")
                                                <tr>
                                                    <td><a href="/{{ request()->slug_instalacion }}/admin/users/{{ $participante->participante->usuario->id }}/ver">{{ $participante->participante->usuario->name }}</a></td>
                                                    @foreach ($participante->participante->valores_campos_personalizados as $element)
                                                        <td>{{ $element->valor }}</td>
                                                    @endforeach
                                                    {{-- <th>{{$participante->pedido->id}}</th> --}}
                                                    <td>{{$participante->pedido->estado}}</td>
                                                    <td>
                                                        <div class="d-flex"  style="gap: 3px">
                                                            <a href="/{{ request()->slug_instalacion }}/admin/eventos/{{ $evento->id }}/participante/{{ $participante->participante->id }}" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                                                            <a href="/{{ request()->slug_instalacion }}/admin/eventos/{{ $evento->id }}/participante/{{ $participante->participante->id }}/delete"  onclick="return confirm('¿Estás seguro que quieres eliminar este participante?');"  class="btn btn-danger"><i class="fas fa-trash"></i></a>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endforeach
                        </div>

                            <div id="pendientes" class="tab-pane fade in show">

                                @if($meses->count())

                                    <ul class="nav nav-tabs nav-tabs-fillup" data-init-reponsive-tabs="dropdownfx">
                                        @foreach ($meses as $index => $item)
                                            <li class="nav-item">
                                                <a href="#"  @if($index == 0) class="active" @endif data-toggle="tab" data-target="#item{{ $item->num_year }}_{{ $item->num_mes }}"><span class="text-uppercase">{{ strftime('%B %Y', strtotime('01-' . $item->num_mes . '-'.$item->num_year)) }}</span></a>
                                            </li>
                                        @endforeach
                                        {{-- <li class="nav-item">
                                          <a href="#" class="active" data-toggle="tab" data-target="#tab-fillup1"><span>Home</span></a>
                                        </li>
                                        <li class="nav-item">
                                          <a href="#" data-toggle="tab" data-target="#tab-fillup2"><span>Profile</span></a>
                                        </li>
                                        <li class="nav-item">
                                          <a href="#" data-toggle="tab" data-target="#tab-fillup3"><span>Messages</span></a>
                                        </li> --}}
                                    </ul>
                                @endif

                                @foreach ($meses as $index => $item)
                                    <div id="item{{ $item->num_year }}_{{ $item->num_mes }}" class="tab-pane fade in @if($index == 0) active show @endif">
                                        <table class="table table-hover" id="table-reservas-pendiente">
                                            <thead>
                                            <tr>
                                                <th>Usuario comprador</th>
                                                @foreach ($evento->tipo_participante->campos_personalizados as $campo)
                                                    <th>{{ $campo->label }}</th>
                                                @endforeach
                                                <th>#</th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach ($participantes_evento->where('num_mes', $item->num_mes)->where('num_year', $item->num_year) as $participante)
                                                @if($participante->pedido->estado =="En proceso")
                                                <tr>
                                                    <td><a href="/{{ request()->slug_instalacion }}/admin/users/{{ $participante->participante->usuario->id }}/ver">{{ $participante->participante->usuario->name }}</a></td>
                                                    @foreach ($participante->participante->valores_campos_personalizados as $element)
                                                        <td>{{ $element->valor }}</td>
                                                    @endforeach
                                                    <td>
                                                        <div class="d-flex"  style="gap: 3px">
                                                            <a href="/{{ request()->slug_instalacion }}/admin/eventos/{{ $evento->id }}/participante/{{ $participante->participante->id }}" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                                                            <a href="/{{ request()->slug_instalacion }}/admin/eventos/{{ $evento->id }}/participante/{{ $participante->participante->id }}/delete"  onclick="return confirm('¿Estás seguro que quieres eliminar este participante?');"  class="btn btn-danger"><i class="fas fa-trash"></i></a>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endif
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endforeach
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
    $(document).ready(function () {
        $(".nav-tabs a").click(function(){
            $('.tab-content>div').removeClass('in active show');
            $('.nav-tabs li, .nav-tabs a').removeClass('active show');
            $(this).tab('show');
        });

        $fecha = $('.tabla-pago1');
        
        
        Object.values($fecha).forEach(s => {
            $(s).DataTable({
                 responsive:true,
                "info": false,
                "paging": false,
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'excel',
                    text: 'Exportar Excel',
                    exportOptions: {
                        modifier: {
                            page: 'current'
                        }
                    }
                }
            ],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
                },
                "order": [[ 0, "desc"]]
            });
        });

            $('#table-reservas-pendiente').DataTable({
                 responsive:true,
                "info": false,
                "paging": false,
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'excel',
                    text: 'Exportar Excel',
                    exportOptions: {
                        modifier: {
                            page: 'current'
                        }
                    }
                }
            ],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
                },
                "order": [[ 0, "desc"]]
            });
    });
</script>
@endsection
