<h1 class="page-title">
    <i class=""></i>
    Excel
</h1>

<table class="display" id="data_table">
    <thead>
        <tr>
            <th>Lector ID</th>
            <th>Total de Registros</th> <!-- Añadido para mostrar el número de registros por lector -->
        </tr>
    </thead>
    <tbody>
        @foreach ($lectores as $lector)
            <tr>
                <td>{{ $lector->lector_id }}</td>
                <td>{{ $lector->total }}</td> <!-- Muestra el total de registros -->
            </tr>
        @endforeach
    </tbody>
</table>

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>

<script>
    $(document).ready(function() {
        // Inicializamos DataTables
        $('#data_table').DataTable({
            responsive: true,
            "info": false, // Desactivamos la información de los registros
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json' // Cargamos el idioma español
            },
            dom: 'Bfrtip', // Definimos el contenedor para los botones
            buttons: [
                'colvis',  // Botón para mostrar/ocultar columnas
                'excel',   // Botón para exportar a Excel
                'print'    // Botón para imprimir
            ]
        });
    });
</script>
