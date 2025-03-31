<h1>Se ha hecho una reserva a su nombre - {{ $user->instalacion->nombre }}</h1>

<div>
    <ul>
        <li><strong>Espacio: </strong>{{ $reserva->pista->tipo }}. {{ $reserva->pista->nombre }}</li>
        <li><strong>Fecha: </strong>{{ date('d/m/Y', $reserva->timestamp) }}</li>
        <li><strong>Horario: </strong>{{ \Carbon\Carbon::createFromTimestamp($reserva->timestamp)->format('H:i') }} - {{ \Carbon\Carbon::createFromTimestamp($reserva->timestamp)->addMinutes($reserva->minutos_totales)->format('H:i') }}</li>
        <li><strong>Tiempo total: </strong>{{ $reserva->minutos_totales }} minutos</li>
        @foreach ($reserva->valores_campos_personalizados as $element)
            <li><strong>{{ $element->campo->label }}: </strong>{{ $element->valor }}</li>
        @endforeach
    </ul>
</div>
