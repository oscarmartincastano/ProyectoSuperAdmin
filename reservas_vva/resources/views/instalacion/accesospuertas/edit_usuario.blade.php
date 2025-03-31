@extends('layouts.admin')

@section('content')
    <div class="row justify-content-center mt-md-5">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Editar usuario: {{$acceso->usuarios->name}}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('usuario_update', [request()->slug_instalacion , $acceso->id]) }}">
                        @csrf
                        <div class="row mb-3">
                            <h4>Configurar acceso</h4>
                        </div>
                        <input type="hidden" name="usuario_id" value="{{$acceso->usuarios->id}}">
                        <div class="row mb-3">
                            <label for="finicio" class="col-md-4 col-form-label text-md-end">Inicio de entrada</label>

                            <div class="col-md-6">
                                <input id="finicio" type="date" class="form-control" name="finicio" value="{{$acceso->inicio}}" placeholder="Fecha de inicio"  required>

                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="ffin" class="col-md-4 col-form-label text-md-end">Fin de entrada*</label>

                            <div class="col-md-6">
                                <input id="ffin" type="date" class="form-control" name="ffin" value="{{$acceso->fin}}" placeholder="Fecha fin" >*
                                Dejar en blanco si no tiene fecha de fin
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="apertura" class="col-md-4 col-form-label text-md-end">Hora mínima de apertura</label>

                            <div class="col-md-6">
                                <input id="apertura" type="time" class="form-control" name="apertura" value="{{$acceso->apertura}}" required >

                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="apertura" class="col-md-4 col-form-label text-md-end">Hora máxima de apertura</label>

                            <div class="col-md-6">
                                <input id="apertura" type="time" class="form-control" name="cierre" value="{{$acceso->cierre}}" required >

                            </div>
                        </div>
                        <div class="row mb-3 ">
                            <div class="col-md-4 m-auto">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" name="activo" @if($acceso->activo=="on")checked @endif>
                                    <label class="form-check-label" for="flexSwitchCheckDefault">¿Quieres dejar el usuario activo?</label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3 ">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary">Guardar usuario</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>



@endsection