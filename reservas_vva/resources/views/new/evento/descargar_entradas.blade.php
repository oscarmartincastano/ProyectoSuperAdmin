<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Entradas</title>
    @php
        if(request()->slug_instalacion == "feria-jamon-villanuevadecordoba" && $evento->id == 6 || $evento->id == 7){
            $url_background = public_path('img/fondo_entrada/invitacion-bonos.jpg');
        }elseif(request()->slug_instalacion == "eventos-bodega" && $evento->id == 23){
            $url_background = public_path('img/fondo_entrada/laultima.jpg');
        }
        elseif(request()->slug_instalacion == "eventos-bodega" && $evento->id == 22){
            $url_background = public_path('img/fondo_entrada/eltarantan.jpg');
        }
        else{
            $url_background = public_path('img/fondo_entrada/'. request()->slug_instalacion .'_fondo.jpg');
        }
    @endphp
    @if (request()->slug_instalacion == "eventos-bodega")
    <style>
    </style>
    @endif
    <style>
        body{
            font-family: Arial, Helvetica, sans-serif;
            width: 100vw;
            margin: 0 auto;
            text-align: center;
            background: url("{{$url_background}}") no-repeat center center;
            background-size: contain;
            height: 100vh;

        }
        img{
            width: 100%;
            max-width: 100px;
            margin: 0 auto;
        }
        .container{
            height: 100vh;
            width: 100%;
            max-width: 500px;
            display: flex;
            gap: 200px;
            flex-direction: column;
            margin: 0 auto;
            justify-content: center;
        }
        /* quiero que la entrada este en el centro de la pagina del pdf verticalmente */

        .entrada{

            width: 100%;
            background: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 10px;
            justify-content: center;
            margin-top: 15px;

        }
        @page {
            size: 21cm 29.7cm;
            margin: 0;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="container">
        @foreach ($participantes as $participante)
        
        @if ($loop->index > 0)
            <div class="page-break"></div>
            
        @endif
        @if (request()->slug_instalacion == "eventos-bodega" && ($evento->id == 23 || $evento->id == 25 || $evento->id == 49 || $evento->id == 50 || $evento->id == 51 || $evento->id == 52 || $evento->id == 53 || $evento->id == 54 || $evento->id == 55 || $evento->id == 56 || $evento->id == 57 || $evento->id == 58 || $evento->id == 58))
            <img src="{{ public_path('img/fondo_entrada/ultima1.png') }}" alt="" style="width: 100%; max-width: 450px; height: auto; margin-bottom:20px; margin-to:20px" >
        @elseif(request()->slug_instalacion == "eventos-bodega" && ($evento->id == 22 || $evento->id == 24 || $evento->id == 26 || $evento->id == 27 || $evento->id == 28 || $evento->id == 29 || $evento->id == 30 || $evento->id == 31 || $evento->id == 32 || $evento->id == 33 || $evento->id == 34 || $evento->id == 35 || $evento->id == 36 || $evento->id == 37 || $evento->id == 38 || $evento->id == 39 || $evento->id == 40 || $evento->id == 41 || $evento->id == 42 || $evento->id == 43 || $evento->id == 44 || $evento->id == 45 || $evento->id == 46 || $evento->id == 47 || $evento->id == 48))
            <img src="{{ public_path('img/fondo_entrada/eltarantan1.png') }}" alt="" style="width: 100%; max-width: 450px; height: auto; margin-bottom:20px; margin-to:20px">
        @else
        @endif
        <div class="entrada ">
            @if (request()->slug_instalacion == "eventos-bodega")
                <div class="numeroEntrada " style="text-align: left"><i>
                    #{{sprintf('%04d', $participante->id);}}</i></div>
            @endif 
            
            @if (request()->slug_instalacion != "eventos-bodega" && request()->slug_instalacion != "ciprea24" && request()->slug_instalacion != "feria-jamon-villanuevadecordoba")
            <h1>Entrada para {{$evento->nombre}}</h1>
            <h2>Invitado: {{$participante->valores_campos_personalizados[0]->valor . ' ' . $participante->valores_campos_personalizados[1]->valor}}</h2>
            @elseif(request()->slug_instalacion == "ciprea24")
            <h2>{{$participante->valores_campos_personalizados[0]->valor . ' ' . $participante->valores_campos_personalizados[1]->valor}}</h2>
            @elseif(request()->slug_instalacion == "feria-jamon-villanuevadecordoba")
                @if($evento->id != 6 && $evento->id != 7)
                    <img src="{{ public_path('img/eventos/feria-jamon-villanuevadecordoba/entradaferiajamon.jpg') }}" alt="" style="width: 100%; max-width: 450px; height: auto;">
                    <h1>{{$evento->nombre}}</h1>
                    <h2>Bono nº {{$loop->index + 1}}</h2>
                @else
                    <h1>{{$evento->nombre}}</h1>
                    <h2>Bono nº {{$loop->index + 1}}</h2>
                @endif
            @else
            <h2>
                Invitado: {{$participante->valores_campos_personalizados[0]->valor}}
            </h2>
            @endif
            @if (request()->slug_instalacion == "villafranca-navidad")
                <h3>Fecha y precio:
                
                    @if ($participante->valores_campos_personalizados[1]->valor == "Tarde (2€)")
                        6 de enero a las 17:00
                    @else
                        {{$participante->valores_campos_personalizados[1]->valor}}
                    @endif 
                    </h3>

            @elseif (request()->slug_instalacion == "villafranca-navidad")
                    <h3>Fecha:
                    @php
                        $fechaFormat_inicio = \Carbon\Carbon::parse($participante->evento->fecha_inicio)->locale('es_ES')->format('d/m/Y');
                        $fechaFormat_fin = \Carbon\Carbon::parse($participante->evento->fecha_fin)->locale('es_ES')->format('d/m/Y');
                        if($participante->tipo_entrada == "Solo DJ's"){
                            $fechaFormat_inicio = $fechaFormat_fin = "03/03/2024";
                            $fecha_inicio = $fecha_fin = "03/03/2024";
                        }else{
                            $fecha_inicio = $participante->evento->fecha_inicio;
                            $fecha_fin = $participante->evento->fecha_fin;
                        }
                    @endphp
                    @if ($fechaFormat_inicio == $fechaFormat_fin)
                        @php
                            $fechaFormat = \Carbon\Carbon::parse($fecha_inicio)->locale('es_ES')->isoFormat('D [de] MMMM [de] YYYY');
                        @endphp
                        {{$fechaFormat}} {{$participante->hora_inicio}} 
                            
            @else
                        @php
                            $fechaFormat = \Carbon\Carbon::parse($fecha_inicio)->locale('es_ES')->isoFormat('D [de] MMMM [de] YYYY');
                        @endphp
                        {{$fechaFormat}}
                    @endif
                    </h3>
                    @if ($participante->tipo_entrada)
                        <h3>Tipo de entrada: {{$participante->tipo_entrada}}</h3>
                    @endif
                    @if(isset($participante->valores_campos_personalizados[1]) and $participante->valores_campos_personalizados[1]->valor != null)
 
                        @if($participante->valores_campos_personalizados[1]->valor == "Solo DJ's")
                            @php
                                $precio = 3;
                            @endphp
                        
                        @else
                            @php
                                $precio = 18;
                            @endphp
                        @endif
                    
                    @else
                        @php
                            $precio = $evento->precio_participante;
                        @endphp
                    @endif
                    <h3>Precio: {{$precio}}€</h3> 
                @else
                {{-- precio --}}
                    @php
                        $fechaFormat_inicio = \Carbon\Carbon::parse($participante->evento->fecha_inicio)->locale('es_ES')->format('d/m/Y');
                        $fechaFormat_fin = \Carbon\Carbon::parse($participante->evento->fecha_fin)->locale('es_ES')->format('d/m/Y');
                    @endphp
                    @if ($fechaFormat_inicio == $fechaFormat_fin)
                        <h3>Fecha: {{$fechaFormat_inicio}}</h3>
                    @else
                        <h3>Fecha: {{$fechaFormat_inicio}} - {{$fechaFormat_fin}}</h3>
                    @endif
                    @if(request()->slug_instalacion != "feria-jamon-villanuevadecordoba")
                        @if (request()->slug_instalacion != "eventos-bodega")
                        <h3>{{$participante->valores_campos_personalizados[15]->valor}}</h3>
                        @else
                         @php
                            $fecha = str_replace('/', '-', $participante->valores_campos_personalizados[1]->valor);
                         @endphp
                        <h3>Fecha de nacimiento: {{
                        \Carbon\Carbon::parse($fecha)->locale('es_ES')->format('d/m/Y')
                        }}</h3>
                        <h3>DNI: {{$participante->valores_campos_personalizados[2]->valor}}</h3>

                        @endif
                    @endif
             
                @endif
                <img src="data:image/png;base64, {!! base64_encode(
                    QrCode::format('png')->margin(0)->backgroundcolor(255, 255, 255)->size(130)->generate(serialize([$participante->id_pedido, $participante->id])),
                ) !!} ">
                
                <br>
                <br>
                <i>Enseña este QR en el evento para poder acceder</i><br>
                <small>Una vez usado ya no tendrá validez</small>
                @if(request()->slug_instalacion == "eventos-bodega")
                    <br><small style="font-size: 16px"><b><i>*No se aceptan devoluciones*</i></b></small>
                @endif
                @if (request()->slug_instalacion == "villafranca-navidad")
                <br>
                <small style="font-size: 13px"><i>*Menos el bono que será válido para todos los eventos menos el musical infantil*</i></small>
                    
            @endif
   
        </div>
        @if (request()->slug_instalacion == "eventos-bodega" && ($evento->id == 23 || $evento->id == 25 || $evento->id == 49 || $evento->id == 50 || $evento->id == 51 || $evento->id == 52 || $evento->id == 53 || $evento->id == 54 || $evento->id == 55 || $evento->id == 56 || $evento->id == 57 || $evento->id == 58 || $evento->id == 58))
                <img src="{{ public_path('img/fondo_entrada/ultima2.png') }}" alt="" style="width: 100% !important; max-width: 600px !important; height: auto !important; margin-top:20px !important;">
        @elseif(request()->slug_instalacion == "eventos-bodega" && ($evento->id == 22 || $evento->id == 24 || $evento->id == 26 || $evento->id == 27 || $evento->id == 28 || $evento->id == 29 || $evento->id == 30 || $evento->id == 31 || $evento->id == 32 || $evento->id == 33 || $evento->id == 34 || $evento->id == 35 || $evento->id == 36 || $evento->id == 37 || $evento->id == 38 || $evento->id == 39 || $evento->id == 41 || $evento->id == 42 || $evento->id == 43 || $evento->id == 44 || $evento->id == 45 || $evento->id == 46 || $evento->id == 47 || $evento->id == 48))
            <img src="{{ public_path('img/fondo_entrada/ultima2.png') }}" alt="" style="width: 100% !important; max-width: 600px !important; height: auto !important; margin-top:20px !important;">
        @else
        @endif    
         
        @endforeach
    </div>
    
</body>

</html>