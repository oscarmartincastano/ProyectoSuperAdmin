<h1>Entrada no creada</h1>

<div class="mb-5">
    <ul>
        @foreach ($pedidoLogNoCorrespondidos as $item)
            <li><strong>ID Usuario: </strong>{{ $item->user_id }}</li>
            <li><strong>Nombre Participante: </strong>{{ $item->nombre_participante }}</li>
            <li><strong>Tipo entrada: </strong>{{ $item->tipo_entrada }}</li>
            <li><strong>Precio: </strong>{{ $item->precio }}</li>
            <li><strong>ID Pedido: </strong>{{ $item->pedido_id }}</li>
            <p>----------------------------------------------------------------------</p>
        @endforeach
    </ul>
</div>
