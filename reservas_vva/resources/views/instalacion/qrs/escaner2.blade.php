<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Escaner</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
        integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <table class="table">
        @foreach ($logs as $key => $log)
            @php
                $checkPedido = false;
                if (substr($log->code, 0, 2) == 'p:' or substr($log->code, 0, 2) == 'p>') {
                    $checkPedido = true;
                }
            @endphp
            <tr>
                <td>
                    @if ($log->status == 'valida')
                        <i class="text-success fas fa-check"></i>
                    @else
                        <i class="text-danger fas fa-times"></i>
                    @endif
                </td>
                <td>

                    @if ($log->status == 'valida')
                        <strong class="text-success">
                            @if ($checkPedido)
                                Ped. válido
                            @else
                                @if ($log->entrada != null)
                                    @php
                                        $tipo = $log->entrada->tipo;
                                    @endphp
                                @endif
                                @if ($log->entrada != null)
                                    Válida 
                                @endif
                            @endif
                        </strong>
                    @elseif($log->status == 'no-existe')
                        <strong class="text-danger">No existe</strong>
                    @elseif($log->status == 'repetida')
                        Ya validada
                    @elseif($log->status == 'fecha-mal')
                        Fecha errónea
                    @elseif($log->status == 'no-existe')
                        Pedido no válido
                    @elseif($log->status == 'fecha-incorrecta')
                        Fecha incorrecta
                    @elseif($log->status == 'ya validada')
                        Pedido ya validado
                    @elseif($log->status == 'p-ya-validado')
                        Pedido ya validado
                    @endif
                </td>
                <td>
                    @php
                    $participante = \App\Models\Participante::where('id', $log->participante_id)->first();
                    $evento = \App\Models\Evento::where('id', $log->evento_id)->first();
                    @endphp
                    {{ $evento->nombre }} {{$participante ? '('.$participante->fecha_pedido.')' : ''}}
                </td>
                <td>

                    @if ($participante != null)
                        @if(request()->slug_instalacion == "feria-jamon-villanuevadecordoba" && ($evento->id == 6 || $evento->id == 7))
                            Invitado
                        @else
                        @php
                            try {
                                echo $participante->usuario->name;
                            } catch (\Throwable $th) {
                                echo "Invitado";
                            }
                        @endphp
                        {{-- {{ $participante->usuario->name }} --}}
                        @endif
                    @endif
                </td>
                <td style="font-size:14px">
                    @if (isset($log->entrada) && !$checkPedido)
   
                    @endif
                    @if ($checkPedido && isset($log->entrada->pedido))
                        @php $count = 1; @endphp
                        @foreach (unserialize($log->entrada->pedido->products) as $key => $value)
                            {!! $value[array_keys($value)[0]] !!} @if ($count != 3 && $count != 6)
                                -
                            @endif
                            @if ($count == 3)
                                |
                            @endif
                            @php $count++; @endphp
                        @endforeach
                        |
                    @endif
                    {!! \Carbon\Carbon::parse($log->created_at)->format('H:i:s') !!}
                </td>

            </tr>
        @endforeach
    </table>
    <div style="background:white;position:fixed;width:100%;padding:20px 10px;bottom:0;">
        <form action="/{{request()->slug_instalacion . '/api/escanear2'}}" method="POST">
            {!! csrf_field() !!}
            <input type="text" autofocus="true" class="form-control" name="code" id="inputCode">
        </form>
    </div>
    <div class="modal" id="myModal" tabindex="-1">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-body">
                    @if (\Session::has('status'))
                        @if (\Session::get('status') == 'valida')
                            <i style="font-size:122px" class="text-success fas fa-check-circle"></i>
                        @else
                            <i style="font-size:122px" class="text-danger fas fa-times-circle"></i>
                        @endif
                    @endif

                </div>
            </div>
        </div>
    </div>
    <style>
        .modal-content {
            align-items: center;
        }

        .modal-dialog {
            top: 25%;
        }
    </style>
    <input type="hidden" id="status" value="{!! \Session::get('status') !!}">
</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
    integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous">
</script>
@if (\Session::has('status'))
    <script type="text/javascript">
        var success = new Audio('/audio/success.mp3');
        var failed = new Audio('/audio/failed.mp3');
        var status = $('#status').val();
        $(window).on('load', function() {
            console.log(status);
            // if (status == 1) {
            //     success.play();
            // } else {
            //     failed.play();
            // }

            $('#myModal').modal('show');
            setTimeout(() => {
                $('#myModal').modal('hide');
                $('input').focus();
            }, 1500);
        });
        
    </script>
@endif
<script>
    $(document).ready(function() {
        $('#inputCode').focus();
        setTimeout(() => {
            $('#inputCode').focus();
        }, 1000);

        $('form').on('submit', function(e) {
            let transformed = $('#inputCode').val()
        .replace(/Ñ/g, ':')  // Reemplazar "Ñ" por ":"
        .replace(/î/g, '{i')  // Reemplazar "î" por "{i"
        .replace(/ñ/g, ';')   // Reemplazar "ñ" por ";"
        .replace(/¨/g, '"')   // Reemplazar "¨" por '"'
        .replace(/\*/g, '}');  // Reemplazar "*" por "}"

        transformed = transformed.charAt(0).toLowerCase() + transformed.slice(1);

         $('#inputCode').val(transformed);
        });

        if ('{{ session('status') }}' === 'false') {
        // Limpiar el input si hubo un error
            $('#inputCode').val(''); // Limpiar el valor del input
        }

    });
</script>

</html>
