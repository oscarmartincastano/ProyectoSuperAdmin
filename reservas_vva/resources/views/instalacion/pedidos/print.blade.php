<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
    <style>
        html{
            display: flex;
            justify-content: center;
        }
        h6{
            margin: 0;
            font-size: 13px;
        }
        body{
            font-family: Arial, Helvetica, sans-serif;
            display: flex;
            flex-direction: column;
            gap: 32px;
            align-items: center;
            text-align: center;
            font-size: 12px;
            max-width: 302.36220472px;
            padding: 15px;
        }
        .totales table{
            float: right;
        }
        .cliente{
            text-align: left;
        }
        .datos{
            display: flex;
            width: 100%;
            justify-content: space-between;
            gap: 60px;
        }
        td,th{
            text-align: left;
            padding: .5rem;
        }
        .center{
            text-align: center;
        }
        .factura{
            font-size: 12px;
            text-align: right;
        }
        .total-title{
            font-weight: bold;
            font-size: 13px;
            text-align: right;
        }
        .totales{
            margin-top: 5px;
        }
        .totales td{ 
            padding: .2rem .5rem;
        }
        .right{
            text-align: right;
        }
        .tabla-consumiciones div{
            text-align: center;
            font-size: 13px;
            line-height: 18px;
        }
        .pagebreak {
            clear: both;
            page-break-after: always;
        }
        table{
            border-spacing: 0;
        }
        .factura>div {
            font-size: 10px !important;
            margin-bottom: 4px;
        }
        .totales>div:not(:first-Child){
            font-size: 13px;
            line-height: 18px;
        }
        .totales>div:not(:first-Child) {
            text-align: left
        }
        .titulo-importe {
            margin-bottom:4px;margin-top:8px;padding:3px;text-align: center;font-size:16px;font-weight:bold;box-shadow: rgb(0 0 0) 0px 0px 2px;
        }
        .conceptos {
            padding: 3px 20px;
            min-height: 80px;
        }
    </style>
</head>
<body>
    {{-- <div class="datos-empresa">
        <img src="/img/matagrande-factura.jpg" style="width: 80px">
        <div style="margin-top: 5px">
            <div> SL</div>
            <div>asdfasdf</div>
            <div>asdfasdf</div>
            <div>asdfasdf</div>
            <div>asdfasdf</div>
        </div>
    </div> --}}
    <div class="datos">
        <div class="cliente">
            <div class="datos-cliente">
                <img src="{{ asset('img/'.request()->slug_instalacion.'.png') }}" style="max-width: 60px; max-height:60px">
            </div>
        </div>
        <div class="factura">
            <div style="font-weight: bold">{{ auth()->user()->instalacion->nombre }}</div>
            {{-- <div>CIF: 29565884A</div> --}}
            <div>Tlfno: {{ auth()->user()->instalacion->tlfno }}</div>
            <div>{{ auth()->user()->instalacion->direccion }}</div>
        </div>
    </div>

    <div style="width: 100%">
        <div class="tabla-consumiciones">
            <div>ID Pedido: {{ $pedido->id }}</div>
            <div>Fecha: {{ date('d/m/Y H:i:s', strtotime($pedido->created_at)) }}</div>
            {{-- <div>Justificante de Pago: <span style="text-transform: capitalize">{{ $pedido->justificante_cobro }}</span></div> --}}
        </div>
        <div class="totales">
            <div class="titulo-importe">
                Conceptos e Importe
            </div>
            <div class="conceptos">
                <div>
                    @if($pedido->reservas->count())
                        Reserva #{{ $pedido->reservas->first()->id }}@if($pedido->reservas->count() > 1) - #{{ $pedido->reservas->last()->id }} @endif
                    @else
                        Evento "{{ $pedido->evento->nombre }}" ({{ $pedido->participantes->count() }} inscripciones)
                    @endif - [{{ $pedido->amount }} €]
                </div>
            </div>
        </div>
        <div class="total_y_restante">
            <div class="titulo-importe" style="font-weight: 200;margin-bottom:0;font-size:14px;">
                Total: {{ $pedido->amount }} € (IVA Incluido 21%)
            </div>
            {{-- <div class="titulo-importe" style="font-weight: 200;margin-top:0;font-size:14px;">
                Saldo restante: {{ $pedido->user->saldo }} €
            </div> --}}
        </div>
    </div>
    <script>window.print()</script>
</body>
<div class="pagebreak"> </div>
</html>