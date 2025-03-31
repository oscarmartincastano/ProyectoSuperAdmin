<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Entradas</title>
    <style>
        body{
            font-family: Arial, Helvetica, sans-serif;
            width: 100vw;
            margin: 0 auto;
            text-align: center;
            background: url("{{ asset('img/fondo_entrada.jpg') }}") no-repeat center center;
            background-size: contain;
            height: 100vh;

        }
        img{
            width: 100%;
            max-width: 100px;
            margin: 0 auto;
        }
        .container{
            width: 100%;
            max-width: 500px;
            display: flex;
            gap: 200px;
            flex-direction: column;
            margin: 0 auto;
            justify-content: center;
            margin-top: 28%;
        }
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
    </style>
</head>
<body>

    <div class="container">
        @foreach ($participantes as $participante)
        <div class="entrada">
            <h1>Entradas para {{$evento->nombre}}</h1>
            <h2>Participante: {{$participante->nombre}}</h2>
            <h3>Fecha y precio:
                
                @if (request()->slug_instalacion = "villafranca-navidad")
                @if ($participante->valores_campos_personalizados[1]->valor == "Tarde (2€)")
                    6 de enero a las 17:00
                @else
                    {{$participante->valores_campos_personalizados[1]->valor}}
                @endif 
                
                    
                @endif
            </h3>
                <img src="data:image/png;base64, {!! base64_encode(
                    QrCode::format('png')->margin(0)->backgroundcolor(255, 255, 255)->size(130)->generate(serialize([$participante->id_pedido, $participante->id])),
                ) !!} ">
                
                <br>
                <br>
                <i>Enseña este QR en el evento para poder acceder</i><br>
                <small>Una vez usado ya no tendrá validez</small>
                <br>
                <small style="font-size: 13px"><i>*Menos el bono que será válido para todos los eventos menos el musical infantil*</i></small>
        </div>
        @endforeach
    </div>
</body>
</html>