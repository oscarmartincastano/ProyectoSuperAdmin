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
                    <h3 class="text-primary no-margin">{{ auth()->user()->instalacion->nombre }}</h3>
                </div>
            </div>
            
            <div class="p-t-15 p-b-15 p-l-20 p-r-20">
                <div class="card ">
                    <div class="card-header">
                        <div class="card-title">Listado de espacios</div>
                    </div>
                    <div class="card-body">
                        <a href="/{{ request()->slug_instalacion }}/admin/pistas/add" class="text-white btn btn-primary">Añadir nueva</a>
                        <table class="table table-hover" id="table-reservas" style="width: 100% !important;">
                            <thead>
                                <tr>
                                    <th data-priority="1">Nombre corto</th>
                                    <th>Nombre</th>
                                    <th data-priority="2">Tipo</th>
                                    <th>Horario</th>
                                    <th>Accion</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pistas as $item)
                                    <tr>
                                        <td>{{ $item->nombre_corto }}</td>
                                        <td>{{ $item->nombre }}</td>
                                        <td>{{ $item->tipo }}. {{ $item->subtipo ?? '' }}</td>
                                        <td>
                                            @foreach ($item->horario_deserialized as $horario)
                                               @if (count($horario['dias']) == 7)
                                                    <strong>Todos los días:</strong>
                                                @else
                                                    @if (checkConsec($horario['dias']))
                                                        @foreach ($horario['dias'] as $index => $dia)
                                                            @switch($dia)
                                                                @case(1)
                                                                    @php $horario['dias'][$index] = 'lunes' @endphp
                                                                    @break
                                                                @case(2)
                                                                    @php $horario['dias'][$index] = 'martes' @endphp
                                                                    @break
                                                                @case(3)
                                                                    @php $horario['dias'][$index] = 'miércoles' @endphp
                                                                    @break
                                                                @case(4)
                                                                    @php $horario['dias'][$index] = 'jueves' @endphp
                                                                    @break
                                                                @case(5)
                                                                    @php $horario['dias'][$index] = 'viernes' @endphp
                                                                    @break
                                                                @case(6)
                                                                    @php $horario['dias'][$index] = 'sábado' @endphp
                                                                    @break
                                                                @case(7)
                                                                    @php $horario['dias'][$index] = 'domingo' @endphp
                                                                    @break
                                                                @default
                                                            @endswitch
                                                        @endforeach
                                                        <strong>@if (count($horario['dias']) > 1) {{ ucfirst($horario['dias'][0]) }} a {{ $horario['dias'][count($horario['dias']) - 1] }} @else {{ ucfirst($horario['dias'][0]) }} @endif</strong></div>
                                                    @else
                                                        @foreach ($horario['dias'] as $index => $dia)
                                                            @switch($dia)
                                                                @case(1)
                                                                    @php $horario['dias'][$index] = 'lunes' @endphp
                                                                    @break
                                                                @case(2)
                                                                    @php $horario['dias'][$index] = 'martes' @endphp
                                                                    @break
                                                                @case(3)
                                                                    @php $horario['dias'][$index] = 'miércoles' @endphp
                                                                    @break
                                                                @case(4)
                                                                    @php $horario['dias'][$index] = 'jueves' @endphp
                                                                    @break
                                                                @case(5)
                                                                    @php $horario['dias'][$index] = 'viernes' @endphp
                                                                    @break
                                                                @case(6)
                                                                    @php $horario['dias'][$index] = 'sábado' @endphp
                                                                    @break
                                                                @case(7)
                                                                    @php $horario['dias'][$index] = 'domingo' @endphp
                                                                    @break
                                                                @default
                                                            @endswitch
                                                        @endforeach
                                                        <strong>
                                                            @foreach ($horario['dias'] as $index => $dia)
                                                               @if ($index == 0){{ ucfirst($dia) }}@else{{ $dia }}@endif<i></i>@if ($index != count($horario['dias']) - 1), @endif
                                                            @endforeach
                                                        </strong>
                                                    @endif
                                                @endif
                                                    @foreach ($horario['intervalo'] as $int)
                                                        <li style="margin-left: 10px">{{ $int['hinicio'] }}h -{{ $int['hfin'] }}h cada {{ $int['secuencia'] }} min.</li>
                                                    @endforeach
                                            @endforeach
                                        </td>
                                        <td>
                                            <a href="/{{ request()->slug_instalacion }}/admin/pistas/{{ $item->id }}/edit" class="btn btn-primary"><i class="fas fa-edit"></i></i></a>
                                            <a href="/{{ request()->slug_instalacion }}/admin/pistas/{{ $item->id }}/desactivar" class="btn btn-secondary" style="@if ($item->active) background: grey;color:white @else background: green;color:white @endif">@if (!$item->active) <i class="fas fa-eye-slash"></i></i> @else <i class="fas fa-eye"></i> @endif</a>
                                        </td>
                                    </tr>
                                @endforeach
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
    });
</script>
@endsection