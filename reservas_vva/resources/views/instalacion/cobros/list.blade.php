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
                        <div class="card-title">Listado de cobros</div>
                    </div>
                    <div class="card-body">
                        <a href="/{{ request()->slug_instalacion }}/admin/cobro/add" class="text-white btn btn-primary">Añadir nuevo</a>
                        <table class="table table-condensed table-hover" id="table-users">
                            <thead>
                                <tr>
                                    <th>Usuario</th>
                                    <th>Fecha</th>
                                    <th>Concepto</th>
                                    <th>Cantidad</th>
                                    <th>Notas</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($instalacion->cobros as $item)
                                    <tr>
                                        <td><a href="/{{ request()->slug_instalacion }}/admin/users/{{ $item->user->id }}/ver">{{ $item->user->name }}</a></td>
                                        <td>{{ $item->fecha }}</td>
                                        <td>{{ $item->concepto }}</td>
                                        <td>{{ $item->cantidad }}€</td>
                                        <td>{{ $item->notas }}</td>
                                        <td>
                                            <a href="/{{ request()->slug_instalacion }}/admin/cobro/{{ $item->id }}" class="btn btn-primary"><i data-feather="edit"></i></a> <a href="/{{ request()->slug_instalacion }}/admin/cobro/{{ $item->id }}/delete" onclick="return confirm('¿Estás seguro que quieres borrar este cobro?')" class="btn btn-danger"><i data-feather="trash"></i></a>
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