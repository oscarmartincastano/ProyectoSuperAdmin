@extends('layouts.admin')

@section('content')

<div class="row">
    <div class="col-lg-12 m-b-10">
        
            <div class="p-l-20 p-r-20 p-b-10 pt-3">
                <div>
                    <h3 class="text-primary no-margin">Panel de administración</h3>
                </div>
            </div>
            
            <div class="p-t-15 p-b-15 p-l-20 p-r-20">
                <div class="card ">
                    <div class="card-header">
                        <div class="card-title">Instalaciones</div>
                    </div>
                    <div class="card-body">
                        <a href="/{{ request()->slug_instalacion }}/manager/instalaciones/add" class="text-white btn btn-primary">Añadir nueva</a>
                        <table class="table table-hover table-responsive">
                            <thead>
                                <tr>
                                    <th>id</th>
                                    <th>Logo</th>
                                    <th>Instalación</th>
                                    <th>Tlfno</th>
                                    <th>slug</th>
                                    <th>Acceso</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($instalaciones as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td><img src="/img/{{ $item->slug }}.png" height="50"></td>
                                        <td>{{ $item->nombre }}</td>
                                        <td>{{ $item->tlfno }}</td>
                                        <td>{{ $item->slug }}</td>
                                        <td><a href="http://gestioninstalacion.es/{{ $item->slug }}" target="_blank">http://gestioninstalacion.es/{{ $item->slug }}</a></td>
                                        <td>
                                            <div class="d-flex" style="gap: 5px">
                                                <a href="/{{ request()->slug_instalacion }}/manager/instalaciones/{{ $item->id }}/edit" class="btn btn-primary"><i data-feather="edit"></i></a>
                                            </div>
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