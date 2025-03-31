@extends('layouts.admin')

@section('pagename', 'Eventos creados')

@section('style')
    <style>
        .clickable {
            cursor: pointer;
        }
    </style>    
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12 m-b-10">
        
            <div class="p-l-20 p-r-20 p-b-10 pt-3">
                <div>
                    <h3 class="text-primary no-margin">Tipos de participante</h3>
                </div>
            </div>
            
            <div class="p-t-15 p-b-15 p-l-20 p-r-20">
                <div class="card ">
                    <div class="card-header">
                        <div class="card-title">Tipoas de participante creados</div>
                    </div>
                    <div class="card-body">
                        <a href="/{{ request()->slug_instalacion }}/admin/eventos/tipos-clientes/add" class="text-white btn btn-primary">Añadir nuevo</a>
                        <table class="table table-hover" id="table-reservas" style="width: 100% !important;">
                            <thead>
                                <tr>
                                    <th data-priority="1">Nombre</th>
                                    <th>Campos</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tipos_participantes as $item)
                                    <tr>
                                        <td>{{ $item->nombre }}</td>
                                        <td>|
                                            @foreach ($item->campos_personalizados as $item)
                                                {{ $item->label }} |
                                            @endforeach
                                        </td>
                                        <td><a href="#" class="btn btn-primary"><i class="fas fa-edit"></i></a></td>
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