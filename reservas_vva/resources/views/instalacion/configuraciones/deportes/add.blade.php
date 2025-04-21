@extends('layouts.admin')

@section('content')

<div class="row">
    <div class="col-lg-12 m-b-10">
        
        <div class="p-l-20 p-r-20 p-b-10 pt-3">
                <div>
                    <h3 class="text-primary no-margin">@if(isset(request()->id)) Editar @else Añadir @endif deporte</h3>
                </div>
        </div>
        
        <div class="p-t-15 p-b-15 p-l-20 p-r-20">
                <div class="card ">
                    <div class="card-header">
                        <div class="card-title">Añadir instalación</div>
                    </div>
                    <div class="card-body">
                        <form action="#" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="fecha_inicio">Nombre</label>
                                <input @if(isset(request()->id)) value="{{ $deporte->nombre }}" @endif type="text" class="form-control" placeholder="Nombre" name="nombre"  required>
                            </div>
                            <input type="submit" value="@if(isset(request()->id)) Editar @else Añadir @endif" class="btn btn-primary">
                        </form>
                    </div>
                </div>
                <p class="small no-margin">
                </p>
        </div>
    </div>
</div>
@endsection