@extends('layouts.admin')

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
                    <h3 class="text-primary no-margin">{{ auth()->user()->instalacion->nombre }}</h3>
                </div>
            </div>
            
            <div class="p-t-15 p-b-15 p-l-20 p-r-20">
                <div class="card ">
                    <div class="card-header">
                        <div class="card-title">Listado de usuarios</div>
                    </div>
                    <div class="card-body">
                        
                        @if (count(auth()->user()->instalacion->users_sin_validar))
                            <a href="/{{ request()->slug_instalacion }}/admin/users/novalid" class="btn btn-info" style="padding-right: 40px">Usuarios no aprobados @if (count(auth()->user()->instalacion->users_sin_validar))<mark class="mark" style="border: 0; top:2px;left:172px;">{{ count(auth()->user()->instalacion->users_sin_validar) }}</mark>@endif</a>
                        @endif
                        <a href="/{{ request()->slug_instalacion }}/admin/users/add" class="text-white btn btn-primary">Añadir nuevo</a>
                        <div style="display: flex;justify-content: flex-end;gap:10px">
                            <div id="table-users_buscar" class="dataTables_filter text-right">
                                <label for="buscar">
                                    <input id="buscar" type="search" class="form-control input-sm" placeholder="Buscar..." aria-controls="table-users">
                                </label>
                            </div>
                        </div>
                        <table class="table table-hover" id="table-users" style="width: 100% !important;">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th>Tlfno</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $item)
                                    @if ($item->aprobado)
                                        <tr class="clickable" data-href="/{{ request()->slug_instalacion }}/admin/users/{{ $item->id }}/ver">
                                            <td>{{ $item->name }} {{ $item->rol == 'admin' ? '(admin)' : '' }}</td>
                                            <td>{{ $item->email }}</td>
                                            <td>{{ $item->tlfno }}</td>
                                            <td data-order="{{ $item->rol }}">
                                                <a href="/{{ $instalacion->slug }}/admin/users/{{ $item->id }}" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                                                @if ($item->id != auth()->user()->id) <a href="/{{ $instalacion->slug }}/admin/users/{{ $item->id }}/desactivar" class="btn-activate btn {{ !$item->deleted_at ? 'btn-danger' : 'btn-success' }}" onclick="return confirm('¿Estás seguro que quieres {{ !$item->deleted_at ? 'desactivar' : 'activar' }} este usuario?');" title="{{ !$item->deleted_at ? 'Desactivar' : 'Activar' }} usuario"><i data-feather="power"></i></a>@endif
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                        <div class="text-right p-3">
                            {{ $users->links() }}
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
            /* $('#table-users').DataTable({
                "info": false,
                "paging": false,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
                },
                "order": [[ 3, "asc" ]]
            }); */

            $('#table-users').DataTable({
                 responsive:true,
                "info": false,
                "paging": false,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
                },
                "order": [[ 0, "desc"]]
            });

            $('table').on('click', '.clickable', function (e) {
                if (e.target.tagName == 'TD') {
                    window.location = $(this).data("href");
                }
            });

            $('.card-body').on('keyup', '#table-users_buscar input', function (e) { 
                if ($(this).val().length == 0 || $(this).val().length > 3) {
                    if ($(this).val() == '') {
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url:"{{ route('users.list_datatable', ['slug_instalacion' => request()->slug_instalacion]) }}",
                            method:"POST",
                            data:{page: '{{ request()->page ?? "1" }}'},
                            success: function(response) {
                                $('#table-users tbody').empty();

                                $.each(response['data'], function (index, item) { 
                                    $('#table-users tbody').append(`
                                        <tr class="clickable" data-href="/{{ request()->slug_instalacion }}/admin/users/${item['id']}/ver">
                                            <td>${item['name']} ${item['rol'] == 'admin' ? '(admin)' : ''}</td>
                                            <td>${item['email']}</td>
                                            <td>${item['tlfno'] ?? ''}</td>
                                            <td>
                                                <a href="/{{ request()->slug_instalacion }}/admin/users/${item['id']}" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                                            </td>
                                        </tr>
                                    `);
                                });
                            },
                            error: function (er) { 
                                console.log(er);
                            }
                        });
                    } else {
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url:"{{ route('users.list_datatable', ['slug_instalacion' => request()->slug_instalacion]) }}",
                            method:"POST",
                            data:{text: $(this).val()},
                            success: function(response) {
                                $('#table-users tbody').empty();

                                $.each(response['data'], function (index, item) { 
                                    $('#table-users tbody').append(`
                                        <tr class="clickable" data-href="/{{ request()->slug_instalacion }}/admin/users/${item['id']}/ver">
                                            <td>${item['name']} ${item['rol'] == 'admin' ? '(admin)' : ''}</td>
                                            <td>${item['email']}</td>
                                            <td>${item['tlfno'] ?? ''}</td>
                                            <td>
                                                <a href="/{{ request()->slug_instalacion }}/admin/users/${item['id']}" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                                            </td>
                                        </tr>
                                    `);
                                });
                            },
                            error: function (er) { 
                                console.log(er);
                            }
                        });
                    }
                }
            });
        });
    </script>
@endsection