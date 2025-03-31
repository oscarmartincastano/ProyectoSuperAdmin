@extends('layouts.userview')

@section('pagename', 'Contacto')

@section('style')
    <style>

    </style>
@endsection

@section('content')

    <div class="container is-max-desktop">

        <div class="container mt-3">
            <h1 class="title text-left">Contacto</h1>
            <p style="font-size: 20px;margin-bottom: 22px;">
                Contacta con nosotros si tienes algún problema para realizar las reservas, registrarte, iniciar sesión o con el funcionamiento de la web. Para contactar
                con nosotros rellena el siguiente formulario indicando tus datos para que podamos estar en contacto contigo. 
            </p>
            <div class="card">
                <div class="card-body">
                    <h2><i class="fas fa-phone mr-1"></i> Contacto</h2>
                    <form action="#" method="post">
                        @csrf
                        @if(session()->has('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{session()->get('success')}}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        <div class="form-group">
                            <label for="name">Nombre*</label>
                            <input type="text" class="form-control" name="name" id="name" placeholder="Nombre..." required>
                        </div>
                        <div class="form-group">
                            <label for="tlfno">Teléfono (opcional)</label>
                            <input type="text" class="form-control" name="tlfno" id="tlfno" placeholder="Teléfono...">
                        </div>
                        <div class="form-group">
                            <label for="email">Email*</label>
                            <input type="email" class="form-control" name="email" id="email" placeholder="Email..." required>
                        </div>
                        <div class="form-group">
                            <label for="asunto">Asunto*</label>
                            <input type="text" class="form-control" name="asunto" id="asunto" placeholder="Asunto..." required>
                        </div>
                        <div class="form-group">
                            <label for="mensaje">Mensaje*</label>
                            <textarea name="mensaje" id="mensaje"  rows="5" class="form-control" placeholder="Mensaje..." required></textarea>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="priv">
                            <label class="form-check-label" for="priv" required>
                                He leído y acepto la <a href="/{{ request()->slug_instalacion }}/privacidad">política de privacidad</a>.
                            </label>
                        </div>
                        <input type="submit" value="Enviar" class="btn btn-primary w-100">
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
