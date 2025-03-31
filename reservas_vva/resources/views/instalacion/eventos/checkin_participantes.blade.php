@extends('layouts.admin')

@section('pagename', 'Check in entradas')

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
                        <div class="card-title">Check In entradas</div>
                        <div class="mt-3">
                            <span><strong>Total: {{$pedido->sum('amount')}}€</strong></span>
                        </div>
                        <div class="mt-3">
                            <span><strong>Total participantes: {{$participantespago}}</strong></span>
                        </div>
                        <div class="mt-3">
                        <label for="dayFilter" class="d-flex align-items-center" style="gap: 10px">
                            <strong>Filtrar por día:</strong>
                            <form action="{{route('checkin_participantes_filter', request()->slug_instalacion)}}" method="POST" class="d-flex" style="width: 20%;">
                                @csrf
                                <select name="dayFilter" id="dayFilter" class="form-control d-inline-block">
                                    <option value="">Todos</option>
                                    @foreach ($dias as $key => $dia)
                                            @if ($fecha == $key)
                                                <option value="{{$key}}" selected>{{$dia}}</option>
                                            @else
                                            <option value="{{$key}}">{{$dia}}</option>

                                            @endif
                                            
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-primary d-inline-block" style="width: 100px">Filtrar</button>
                            </form>

                        </label>
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
                                            <th>Código de pedido</th>
                                            <th data-priority="2">EVENTO INSCRITO</th>
                                            <th>Fecha de evento</th>
                                            <th>Check in</th>
                                            {{-- <th>#</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($participantes as $participante)
                                            @if($participante->pedido and $participante->pedido->estado == 'pagado')
                                                <tr>
                                                    <td><a href="/{{ request()->slug_instalacion }}/admin/users/{{ $participante->usuario->id }}/ver">{{ $participante->usuario->name }}</a></td>
                                                    <td>
                                                        {{$participante->valores_campos_personalizados[0]->valor}}
                                                    </td>
                                                    <td>
                                                        @if ($participante->pedido)
                                                        {{$participante->pedido->id}}
                                                        @else
                                                        Pedido no encontrado
                                                        @endif
                                                    </td>
                                                    <td>{{$participante->evento->nombre}}</td>
                                                    <td>
                                                        {{$participante->fecha_pedido}}
                                                        @if ($participante->fecha_pedido == "Bono")
                                                            <small>(24/12/2023 - 31/12/2023)</small>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($participante->bonosParticipantes && $participante->bonosParticipantes->isNotEmpty())
                                                            @foreach ($participante->bonosParticipantes as $bono)
                                                                <p>{{ $bono->fecha }} <i class="fas fa-check text-success"></i></p>
                                                            @endforeach
                                                        @elseif ($participante->estado_pedido == 'validado')
                                                            Validado <i class="fas fa-check text-success"></i>
                                                        @else
                                                            No validado <i class="fas fa-times text-danger"></i>
                                                        @endif
                    
                                                    </td>
   {{--                                                  <td>
                                                        <div class="d-flex"  style="gap: 3px">
                                                        <a href="/{{ request()->slug_instalacion }}/admin/eventos/{{ $participante->id_evento }}/participante/{{ $participante->id }}" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                                                            <a href="/{{ request()->slug_instalacion }}/admin/eventos/{{ $participante->id_evento }}/participante/{{ $participante->id }}/delete"  onclick="return confirm('¿Estás seguro que quieres eliminar este participante?');"  class="btn btn-danger"><i class="fas fa-trash"></i></a>
                                                    </div>
                                                    </td> --}}
                                                </tr>
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
<script>
    $(document).ready(function () {

        // Inicializar DataTable con los botones para exportar
        $('#table-reservas').DataTable({
            responsive: true,
            "info": false,
            "paging": true,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            "order": [[0, "desc"]],
            dom: 'Bfrtip',  // Definir la posición de los botones
            buttons: [
                {
                    extend: 'excel', // Establecer la exportación a Excel
                    text: 'Exportar Excel', // Texto del botón
                    title: 'Check In Bonos', // Título del archivo Excel
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5] // Aquí puedes definir las columnas que quieres exportar
                    }
                }
            ]
        });

        $(".nav-tabs a").click(function(){
            $('.tab-content>div').removeClass('in active show');
            $('.nav-tabs li, .nav-tabs a').removeClass('active show');
            $(this).tab('show');
        });
    });
</script>
@endsection
