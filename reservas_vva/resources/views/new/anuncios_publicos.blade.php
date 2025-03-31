@extends('new.layout.base')

@section('pagename', 'Tablón de anuncios')

@section('style')
    <style>
.wraper_imagen {
  position:relative;
  margin: 10px auto;
  left: 0;
  right: 0;
  width:94%;
  margin-bottom: 30px;
}
.bubble_amarillo_parado_left{
	position: relative;
	color: #073552;
	width: 95%;
	padding: 16px;
    left:19px;
	background-color: #F8D766;
	border: 8px solid #f4cc2c;
	-webkit-border-radius: 30px;
	-moz-border-radius: 30px;
	border-radius: 30px;
}

.bubble_amarillo_parado_left:before {
	content: ' ';
	position: absolute;
	width: 0;
	height: 0;
	left: -50px;
	top: 5px;
	border: 25px solid;
	border-color: transparent #f4cc2c #f4cc2c transparent;
}
.bubble_amarillo_parado_left:after {
	content: ' ';
	position: absolute;
	width: 0;
	height: 0;
	left: -30px;
	top: 10px;
	border: 18px solid;
	border-color: transparent #F8D766 #F8D766 transparent;
}

@media( min-width: 200px ) and ( max-width: 570px ) {
    .wraper_imagen {
        position:relative;
        margin: 10px auto;
        left: 0;
        right: 0;
        width:94%;
        margin-bottom: 30px;
    }

    .bubble_amarillo_parado_left{
        position: relative;
        color: #073552;
        width: 95%;
        padding: 16px;
        left:19px;
        background-color: #F8D766;
        border: 8px solid #f4cc2c;
        -webkit-border-radius: 30px;
        -moz-border-radius: 30px;
        border-radius: 30px;
    }

    .bubble_amarillo_parado_left:before {
        content: ' ';
        position: absolute;
        width: 0;
        height: 0;
        left: -50px;
        top: 5px;
        border: 25px solid;
        border-color: transparent #f4cc2c #f4cc2c transparent;
    }
    .bubble_amarillo_parado_left:after {
        content: ' ';
        position: absolute;
        width: 0;
        height: 0;
        left: -30px;
        top: 10px;
        border: 18px solid;
        border-color: transparent #F8D766 #F8D766 transparent;
    }
}
    </style>
@endsection

@section('content')

    <div class="container is-max-desktop mt-2">
        <div class="container mt-3">
            <div class="row">
                <div class="col-12">
                    <div class=" mb-4 rounded-3">
                        <div class="container-fluid pt-3 p-0">
                            <h1 class="display-5 fw-bold">Últimas noticias</h1>
                            <div class="timeline_noticias">
                                @foreach ($mensajes as $item)
                                    <div class="wraper_imagen">
                                        <div class="bubble_amarillo_parado_left">
                                            <div><strong>{{ $item->titulo }}</strong></div>
                                            <div>{!! $item->contenido !!}</div>
                                            @if(file_exists(public_path() . "/img/mensajes_difusion/{$item->id}.jpg"))
                                                <div style="margin-top: 20px;text-align: center;width:100%">
                                                    <img src="/img/mensajes_difusion/{{ $item->id }}.jpg" alt="">
                                                </div>
                                            @endif
                                            <div style="font-size: 11px; text-align: right">{{ date('d/m/Y', strtotime($item->fecha_inicio)) }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
