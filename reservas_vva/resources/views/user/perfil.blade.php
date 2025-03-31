@extends('layouts.userview')

@section('pagename', 'Inicio')

@section('style')
    <style>

    </style>
@endsection

@section('content')

    <div class="container is-max-desktop">

        <div class="container mt-3">
            <h1 class="title text-left">Mi perfil</h1>
            @if(session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    Se han editado sus datos correctamente.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            @if(session()->has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    No se han editado tus datos. Las <span style="font-weight: bold">contraseñas</span> no coinciden.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <div class="card">
                <div class="card-body">
                    <h2><i class="fas fa-edit mr-1"></i> Editar perfil</h2>
                    <form action="/{{ request()->slug_instalacion }}/perfil/edit" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="name">Nombre</label>
                            <input type="text" class="form-control" name="name" id="name"
                                value="{{ auth()->user()->name }}">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" name="email" id="email"
                                value="{{ auth()->user()->email }}">
                        </div>
                        <div class="form-group">
                            <label for="tlfno">Teléfono</label>
                            <input type="text" class="form-control" name="tlfno" id="tlfno"
                                value="{{ auth()->user()->tlfno }}" placeholder="Teléfono">
                        </div>
                        <div class="form-group">
                            <label for="password">Cambiar contraseña</label>
                            <div class="row m-0 gap-3">
                                <input value="" type="password" class="form-control col" name="password" id="password"
                                    placeholder="Contraseña...">
                                <input value="" type="password" class="form-control col" name="password_rep" id="password"
                                    placeholder="Repetir contraseña...">
                            </div>
                        </div>
                        <input type="submit" value="Cambiar" class="btn btn-primary">
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
