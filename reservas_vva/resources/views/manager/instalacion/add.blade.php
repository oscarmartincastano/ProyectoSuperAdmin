@extends('layouts.admin')

@section('content')

<div class="row">
    <div class="col-lg-12 m-b-10">
        
        <div class="p-l-20 p-r-20 p-b-10 pt-3">
                <div>
                    <h3 class="text-primary no-margin">@if(isset(request()->id)) Editar @else Añadir @endif instalación</h3>
                </div>
        </div>
        
        <div class="p-t-15 p-b-15 p-l-20 p-r-20">
                <div class="card ">
                    <div class="card-header">
                        <div class="card-title">Añadir instalación</div>
                    </div>
                    <div class="card-body">
                        <form action="#" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="logo">Logo</label>
                                @if(isset(request()->id))
                                <div class="border p-1">
                                    <img src="/img/{{ $instalacion->slug }}.png" height="100">
                                </div>
                                @endif
                                <input name="logo" type="file" placeholder="Logo..." class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="fecha_inicio">Nombre</label>
                                <input @if(isset(request()->id)) value="{{ $instalacion->nombre }}" @endif type="text" class="form-control" placeholder="Nombre" name="nombre"  required>
                            </div>
                            <div class="form-group">
                                <label for="slug">Slug</label>
                                <input @if(isset(request()->id)) value="{{ $instalacion->slug }}" @endif type="text" class="form-control" placeholder="Slug" name="slug"  >
                            </div>
                            <div class="form-group">
                                <label for="direccion">Direccion</label>
                                <input @if(isset(request()->id)) value="{{ $instalacion->direccion }}" @endif type="text" class="form-control" placeholder="Direccion" name="direccion"  >
                            </div>
                            <div class="form-group">
                                <label for="tlfno">Teléfono</label>
                                <input  type="text" class="form-control" placeholder="Teléfono" name="tlfno"  >
                            </div>
                            @if(!isset(request()->id))
                            <label>Usuario admin de acceso</label>
                            <div class="border p-2 mb-3">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" placeholder="Email" name="email"  >
                                </div>
                                <div class="form-group">
                                    <label for="password">Contraseña</label>
                                    <input type="password" class="form-control" placeholder="Contraseña" name="password"  >
                                </div>
                            </div>
                            @endif
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