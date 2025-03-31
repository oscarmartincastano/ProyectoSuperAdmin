@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-lg-12 m-b-10">

            <div class="p-l-20 p-r-20 p-b-10 pt-3">
                <div>
                    <h3 class="text-primary no-margin">Añadir usuario</h3>
                </div>
            </div>

            <div class="p-t-15 p-b-15 p-l-20 p-r-20">
                <div class="card ">
                    <div class="card-header">
                        <div class="card-title">Información</div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('add_user', ['slug_instalacion' => $instalacion->slug]) }}" method="post" role="form" class="form-horizontal">
                            @csrf
                            <div class="form-group row">
                                <label class="col-md-2 control-label">Nombre</label>
                                <input name="name" type="text" placeholder="Nombre..." class="form-control col-md-10" required>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 control-label">Email</label>
                                <input name="email" type="email" placeholder="Email..." class="form-control col-md-10" required>
                            </div>

                            {{-- @if(request()->slug_instalacion == 'la-guijarrosa' || request()->slug_instalacion == 'santaella')
                                <div class="form-group row">
                                    <label class="col-md-2 control-label">Código tarjeta</label>
                                    <input name="codigotarjeta" type="text" placeholder="Código tarjeta..." class="form-control col-md-10">
                                </div>
                            @endif --}}

                            <div class="form-group row">
                                <label class="col-md-2 control-label">Cuota (opcional)</label>
                                <input name="cuota" type="text" placeholder="Cuota..." class="form-control col-md-10">
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 control-label">Fecha de nacimiento (opcional)</label>
                                <input name="date_birth" type="date" placeholder="Fecha de nacimiento..." class="form-control col-md-10">
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 control-label">Teléfono (opcional)</label>
                                <input name="tlfno" type="text" placeholder="Teléfono..." class="form-control col-md-10">
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 control-label">Contraseña</label>
                                <input name="password" type="password" placeholder="Contraseña..." class="form-control col-md-10" required>
                            </div>
                            <input type="hidden" name="id_instalacion" value="{{ $instalacion->id }}">
                            <button class="btn btn-primary btn-lg m-b-10 mt-3" type="submit">Añadir</button>
                        </form>
                    </div>
                </div>
                <p class="small no-margin">
                </p>
            </div>

        </div>
    </div>
@endsection
