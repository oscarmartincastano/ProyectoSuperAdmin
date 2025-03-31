@extends('layouts.userview')

@section('pagename', 'Normas de la instalación')

@section('content')

<div class="container">
    <h1 class="title text-center mt-5 titulo-pagina">Normas de la instalación</h1>
    <div class="divider mb-2 pb-0" style="padding: 7px">
        <div></div>
    </div>
    <div class="row" style="place-content: center">
        <div class="col-md-12" style="padding: calc(var(--bs-gutter-x) * .5)">
            {!! html_entity_decode($instalacion->html_normas) !!}
        </div>
    </div>
</div>

@endsection