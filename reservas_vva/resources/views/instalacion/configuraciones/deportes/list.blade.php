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
                        <div class="card-title">Deportes</div>
                    </div>
                    <div class="card-body">
                        <a href="/{{ request()->slug_instalacion }}/admin/deportes/add" class="text-white btn btn-primary">Añadir nueva</a>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($deportes as $item)
                                    <tr>
                                        <td>{{ $item->nombre }}</td>
                                        <td>
                                            <a href="/{{ request()->slug_instalacion }}/admin/deportes/{{ $item->id }}" class="btn btn-primary"><i class="far fa-edit"></i></a>
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