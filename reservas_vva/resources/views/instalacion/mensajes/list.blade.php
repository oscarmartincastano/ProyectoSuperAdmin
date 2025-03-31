@extends('layouts.admin')

@section('pagename', 'Últimos mensajes enviados')

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
                    <h3 class="text-primary no-margin">Mensajes difusión</h3>
                </div>
            </div>
            
            <div class="p-t-15 p-b-15 p-l-20 p-r-20">
                <div class="card ">
                    <div class="card-header">
                        <div class="card-title">Últimos mensajes</div>
                    </div>
                    <div class="card-body">
                        <a href="/{{ request()->slug_instalacion }}/admin/mensajes/add" class="text-white btn btn-primary">Añadir nuevo</a>
                        <table class="table table-hover" id="table-reservas" style="width: 100% !important;">
                            <thead>
                                <tr>
                                    <th data-priority="1">Fecha</th>
                                    <th data-priority="2">Título</th>
                                    <th>Contenido</th>
                                    <th>Tipo mensaje</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($mensajes as $item)
                                    <tr>
                                        <td>{{ $item->fecha_inicio }} - {{ $item->fecha_fin }}</td>
                                        <td>{{ $item->titulo }}</td>
                                        <td><div style="text-overflow: ellipsis;
                                            overflow: hidden; 
                                            width: 160px; 
                                            height: 1.2em; 
                                            white-space: nowrap;">{!! $item->contenido !!}</div></td>
                                        <td>{!! $item->tipo_mensaje !!}</td>
                                        <td>
                                            <a href="#" class="btn btn-warning abrir-mensaje"><i class="fa-solid fa-eye"></i></a>
                                            <a href="/{{ request()->slug_instalacion }}/admin/mensajes/{{ $item->id }}/edit" class="btn btn-primary"><i class="fa-solid fa-edit"></i></a>
                                            <a href="/{{ request()->slug_instalacion }}/admin/mensajes/{{ $item->id }}/delete" onclick="confirm('¿Estás seguro que quieres eliminar este mensaje?')" class="btn btn-danger"><i class="fa-solid fa-trash"></i></a>
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
<div class="modal fade in modal_auto_open p-0" id="modal-mensaje" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content m-0" style="max-height: calc(100vh - 17px);">
            <div class="modal-header">
            <h4 class="h4 mb-0"></h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body p-4">
                
            </div>
            <div class="modal-footer">
                <p class="text-center mt-2"><button type="button"  data-dismiss="modal" class="btn btn-secondary">Entendido</button></p>
            </div>
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

            $('.abrir-mensaje').click(function (e) { 
                e.preventDefault();
                $('#modal-mensaje h4').html($(this).parent().prev().prev().html());
                $('#modal-mensaje .modal-body').html($(this).parent().prev().find('div').html());
                $('#modal-mensaje').modal('show');
            });
        });
    </script>
@endsection