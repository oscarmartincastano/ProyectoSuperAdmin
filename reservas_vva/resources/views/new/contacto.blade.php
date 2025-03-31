@extends('new.layout.base')

@section('style')
<style>
    .titulo-card {
        margin-bottom: 16px;
        border-bottom: 1px solid rgba(47,51,51,.1);
        padding-bottom: 24px;
        font-weight: 600;
        line-height: 1.4;
        font-size: 32px;
    }
    .contenido-principal {
        padding: 20px;
    }
    th {
        border-top: 0 !important;
    }
    .form-perfil label {
        color: rgba(47,51,51,.4);
        display: block;
        font-size: 14px;
        line-height: 16px;
        height: 16px;
        font-weight: 500;
    }
    .form-perfil input:not([type="checkbox"]), .form-perfil textarea {
        display: block;
        width: 100%;
        font-size: 16px;
        padding: 7px 0;
        line-height: 1.8;
        background-color: transparent;
        border: 0;
        border-bottom: 1px solid rgba(14,36,51,0.3);
        -webkit-transition: border-color .25s;
        transition: border-color .25s;
    }
    .form-perfil input:not([type="checkbox"]), .form-perfil textarea:focus {
        border: 0;
        border-bottom: 1px solid #335fff;
        outline: 0;
        box-shadow: none;
    }
    .btn-form {
        color: #fff;
        border-color: transparent;
        background-color: #335fff;
        border-radius: 32px;
    }
    .nav-tabs {
        display: flex;
        align-items: center;
        font-size: 16px;
        border-bottom: 0;
    }
    .nav-tabs a {
        display: block;
        padding: 1em 0;
        line-height: 1em;
        border-bottom: 2px solid transparent;
        text-decoration: none;
        opacity: .6;
        -webkit-transition: all .15s;
        transition: all .15s;
        position: relative;
        opacity: 0.75;
        color: black;
    }
    .nav-tabs a.active {
        border-bottom-color: #335fff;
        opacity: 1;
    }
    .nav-tabs li:nth-child(2)>a {
        margin-left: 2em;
    }
    .tab-content{
        margin-top: 32px;
    }
    ::placeholder {
        opacity: 0.55 !important;
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card shadow-box card-reserva mb-4">
            <div class="card-body" style="padding: 48px;">
                @if(request()->slug_instalacion  == "villafranca-de-cordoba")
                   <div class="titulo-card">Formulario de contacto para información, dudas y sugerencias.</div>
                @else
                    <div class="titulo-card">Contacto</div>
                @endif
                <div class="contenido-card form-perfil">
                    <div id="mi-perfil">
                        <form action="#" method="post" id="formulariocontacto">
                            @csrf
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
                                <input class="form-check-input" type="checkbox" id="priv" name="check_politica">
                                <label class="form-check-label" for="priv" required>
                                    He leído y acepto la <a href="/{{ request()->slug_instalacion }}/privacidad">política de privacidad</a>.
                                </label>
                            </div>
                            <div class="mt-3">
                                <button class="w-100 btn btn-form g-recaptcha" data-sitekey="6LdGgp4kAAAAAIzIRoWWjDN5dAUK7uDHw3kYdkXY" 
                                data-callback='onSubmit' 
                                data-action='submit'>Enviar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(session()->has('success'))
    <script>
        alert("{{ session()->get('success') }}");
    </script>
@endif

@endsection

@section('script')
<script src="https://www.google.com/recaptcha/api.js"></script>

<script>
    function onSubmit(token) {
    if (document.getElementById("priv").checked == false) {
        alert("Debes aceptar la política de privacidad");
        return false;
    }
    if (document.getElementById("name").value == "") {
        alert("Debes introducir un nombre");
        return false;
    }
    if (document.getElementById("email").value == "") {
        alert("Debes introducir un email");
        return false;
    }
    if (document.getElementById("asunto").value == "") {
        alert("Debes introducir un asunto");
        return false;
    }
    if (document.getElementById("mensaje").value == "") {
        alert("Debes introducir un mensaje");
        return false;
    }

    document.getElementById("formulariocontacto").submit();
}

  </script>
@endsection
