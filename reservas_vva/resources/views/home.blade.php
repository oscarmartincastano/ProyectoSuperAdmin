@extends('layouts.userview')

@section('pagename', 'Inicio')

@section('content')

@if($errors->any())
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body bg-danger">
                <p class="mb-0 text-center text-white">{{$errors->first()}}</p>
            </div>
        </div>
    </div>
</div>
@endif
@if(session()->has('message'))
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body bg-success">
                <p class="mb-0 text-center text-white">{{ session()->get('message') }}</p>
            </div>
        </div>
    </div>
</div>
@endif

<div class="container">
    <h1 class="title text-center mt-5 titulo-pagina">Selecciona espacio</h1>
    <div class="divider mb-5" style="padding: 5px !important;">
        <div></div>
    </div>
    <div class="row" style="place-content: center">
        @foreach (array_reverse($instalacion->deportes) as $index => $item)
        
        @php
            $url_pista = '/'.request()->slug_instalacion.'/'.$item;
            if($item == 'Piscina' && strtotime(date('Y-m-d H:i:s')) < strtotime('2022-06-22 19:00:00')) {
                $url_pista = '#';
            }
        @endphp

            <div class="col-md-4" style="padding: calc(var(--bs-gutter-x) * .5)">
                @if (!file_exists(public_path() . '/img/deportes/'.strtr(lcfirst($item), ' ', '_').'.jpg'))
                    <a style="
                    display: flex;
                    align-content: center;
                    align-items: center;
                    justify-content: center;
                    background: linear-gradient(0deg, rgba(36, 36, 36, 0.5), rgba(36, 36, 36, 0.5));
                    font-family: 'Fira Sans', sans-serif;
                    text-transform: uppercase;
                    font-weight: bold;
                    color: white;
                    font-size:2em" href="{!! $url_pista !!}">
                        <img style="visibility: hidden" src="{{ asset('img/deportes/piscina.jpg') }}">
                        <span style="position: absolute">{{ $item }}</span>
                    </a>
                @else
                    <a href="{!! $url_pista !!}"><img src="{{ $item == 'Piscina' ?  (strtotime(date('Y-m-d H:i:s')) < strtotime('2022-06-22 19:00:00') ? asset('img/deportes/'.strtr(lcfirst($item), ' ', '_').'.jpg') : asset('img/deportes/piscina2.jpg')) : asset('img/deportes/'.strtr(lcfirst($item), ' ', '_').'.jpg') }}"></a>
                @endif
            </div>
        @endforeach
    </div>
</div>

@endsection