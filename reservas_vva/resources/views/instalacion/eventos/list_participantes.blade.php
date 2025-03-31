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
            {{-- <div class="p-l-20 p-r-20 p-b-10 pt-3">
                <div>
                    <h3 class="text-primary no-margin">Participantes"</h3>
                </div>
            </div> --}}

            <div class="p-t-15 p-b-15 p-l-20 p-r-20">
                <div class="card ">
                    <div class="card-header">
                        <div class="card-title">Participantes</div>
                        <div class="mt-3">
                            <span><strong>Total: {{$pedido->sum('amount')}}€</strong></span>
                        </div>
                        <div class="mt-3">
                            <span><strong>Total participantes: {{$participantespago->count()}}</strong></span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                                <div>
                                    <table class="table table-hover" id="table-reservas" style="width: 100% !important;">
                                        <thead>
                                            <tr>
                                                <th data-priority="1">USUARIO COMPRADOR</th>
                                                <th>NOMBRE Y APELLIDOS</th>
                                                <th data-priority="2">EVENTO INSCRITO</th>
                                                <th>PEDIDO ASOCIADO</th>
                                                <th>TIPO DE INSCRIPCIÓN</th>
                                                <th>#</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($participantes as $participante)

                                                @if($participante->pedido->estado == 'pagado')
                                                    <tr>
                                                        <td><a href="/{{ request()->slug_instalacion }}/admin/users/{{ $participante->usuario->id }}/ver">{{ $participante->usuario->name }}</a></td>
                                                        <td>
                                                            {{$participante->valores_campos_personalizados[0]->valor}}
                                                        </td>
                                                        @if ($participante->evento)
                                                            <td>{{ $participante->evento->nombre }}</td>
                                                        @else
                                                            <td>{{ $participante->servicio->nombre }}</td>
                                                        @endif
                                                        <td>
                                                            <a href="/{{ request()->slug_instalacion }}/admin/pedidos/{{ $participante->pedido->id }}/ver">{{ $participante->pedido->id }}</a>
                                                        </td>
                                                        <td>{{$participante->valores_campos_personalizados[6]->valor ?? ''}}</td>
                                                        <td>
                                                            <div class="d-flex"  style="gap: 3px">
                                                            <a href="/{{ request()->slug_instalacion }}/admin/eventos/{{ $participante->id_evento }}/participante/{{ $participante->id }}" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                                                                <a href="/{{ request()->slug_instalacion }}/admin/eventos/{{ $participante->id_evento }}/participante/{{ $participante->id }}/delete"  onclick="return confirm('¿Estás seguro que quieres eliminar este participante?');"  class="btn btn-danger"><i class="fas fa-trash"></i></a>
                                                        </div>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="text-right p-3">
                                        {{ $participantes->links() }}
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

        $('#table-reservas').DataTable({
                 responsive:true,
                "info": false,
                "paging": false,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
                },
                "order": [[ 0, "desc"]]
            });

        $(".nav-tabs a").click(function(){
            $('.tab-content>div').removeClass('in active show');
            $('.nav-tabs li, .nav-tabs a').removeClass('active show');
            $(this).tab('show');
        });
    });
</script>
@endsection
