<h1>Se ha cancelado su reserva del día {{ \Carbon\Carbon::parse($reserva->fecha)->format('d-m-Y') }} a la hora - {{ \Carbon\Carbon::createFromTimestamp($reserva->timestamp)->format('H:i') }} - {{ \Carbon\Carbon::createFromTimestamp($reserva->timestamp)->addMinutes($reserva->minutos_totales)->format('H:i') }}</h1>

<div>
    <ul>
        <li><strong>Espacio: </strong>{{ $reserva->pista->tipo }}. {{ $reserva->pista->nombre }}</li>
        <li><strong>Fecha: </strong>{{ date('d/m/Y', $reserva->timestamp) }}</li>
        <li><strong>Horario: </strong>{{ \Carbon\Carbon::createFromTimestamp($reserva->timestamp)->format('H:i') }} - {{ \Carbon\Carbon::createFromTimestamp($reserva->timestamp)->addMinutes($reserva->minutos_totales)->format('H:i') }}</li>
        <li><strong>Motivo de la cancelación: </strong>{{ $reserva->observaciones }}</li>
    </ul>
    <p>En breve recibirá el importe de la reserva.</p>
</div>
