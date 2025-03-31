@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-lg-12 m-b-10">
        
            <div class="p-l-20 p-r-20 p-b-10 pt-3">
                <div>
                    <h3 class="text-primary no-margin"></h3>
                </div>
            </div>
            
            <div class="p-t-15 p-b-15 p-l-20 p-r-20">
                <div class="card ">
                    <div class="card-header">
                        <div class="card-title">Nº de asistentes por día</div>
                    </div>
                    <div class="card-body">
                        <form method="GET">
                            <div class="form-group">
                                <select id="mes" name="mes" class="form-control">
                                    <option value="6" {{(!request()->mes && date('m') == 6) || request()->mes == 6 ? 'selected' : '' }}>JUNIO</option>
                                    <option value="7" {{(!request()->mes && date('m') == 7) || request()->mes == 7 ? 'selected' : '' }}>JULIO</option>
                                    <option value="8" {{(!request()->mes && date('m') == 8) || request()->mes == 8 ? 'selected' : '' }}>AGOSTO</option>
                                </select>
                            </div>
                        </form>
                        <table class="table table-hover text-center" >
                            <thead>
                                <tr>
                                    <th class="text-left">Día</th>
                                    <th>Reservas pagadas</th>
                                    <th>Reservas pendientes</th>
                                    <th>Reservas canceladas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($period as $fecha)
                                    <tr>
                                        <td class="text-left">{{ $fecha->format('d M') }}</td>
                                        <td>{{ $piscina->reservas_activas_por_dia($fecha->format('Y-m-d'))->count() }}</td>
                                        <td>{{ $piscina->reservas_pendientes_por_dia($fecha->format('Y-m-d'))->count() }}</td>
                                        <td>{{ $piscina->reservas_canceladas_por_dia($fecha->format('Y-m-d'))->count() }}</td>
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
        $('#mes').change(function (e) { 
            $(this).parent().parent().submit();
        });
    });
</script>
@endsection