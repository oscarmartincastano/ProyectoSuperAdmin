<style>
    .ui-datepicker-calendar{
        display: none;
    }
    .ui-datepicker-current{
        display: none;
    }
</style>
<div class="filtros p-0 d-flex">
        <div>
            <select class="w-100 form-control select2 select-pista">
                @foreach ($instalacion->deportes_clases as $index => $item)
                    <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="position-relative">
            <div class="div-hoy" data-hoy="{{Carbon\Carbon::createFromFormat('m', date('m'))->locale('es')->monthName;}} {{date('Y')}}">
                {{Carbon\Carbon::createFromFormat('m', date('m'))->locale('es')->monthName;}} {{date('Y')}}
            </div>

            <div class="div-alt-select-fecha">
                <input type="text" class="form-control" id="alt-select-fecha" readonly="readonly">
            </div>
            <input type="text" class="form-control select-fecha" value="{{date("Y-m-d")}}"
                min="{{date("Y-m-d")}}" readonly="readonly">
        </div>

    </div>
    <div class="card-body p-0">
        <div class="tabla-horario">
            <div class="loader-horario text-center" style="display: none">
                <div>
                    <div class="spinner-border text-primary mb-3" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <div>Buscando disponibilidad</div>
                </div>
            </div>
            <div class="pistas-horario" style="width: 20%;">
                <div class="celda" style="height: 40px; line-height: 40px;"></div>
                @foreach ($pistas as $index => $pista)
                    <div class="celda" style="height: 40px; line-height: 40px;">
                        {{ $pista->nombre }}
                    </div>
                @endforeach
                <div id="ver-zonas-anteriores">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-layout-sidebar-right-expand" width="22" height="22" viewBox="0 0 24 24" stroke-width="1.5" stroke="#FFFFFF" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M4 4m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" />
                        <path d="M15 4v16" />
                        <path d="M10 10l-2 2l2 2" />
                      </svg>
                </div>
                <div id="ver-mas-zonas">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-layout-sidebar-left-expand" width="22" height="22" viewBox="0 0 24 24" stroke-width="1.5" stroke="#FFFFFF" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M4 4m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" />
                        <path d="M9 4v16" />
                        <path d="M14 10l2 2l-2 2" />
                      </svg>
                </div>
            </div>
            <div class="tramos" style="width: 80%;">
                <div class="horas-tramos">
                    @php //Contar numero de sabados del mes
                        // $now = Carbon\Carbon::now(); // Obtener la fecha actual
                        // $month = $now->month; // Obtener el número de mes actual

                        // $startDate = $now->startOfMonth(); // Crear una instancia de Carbon con el primer día del mes actual
                        // $endDate = $startDate->copy()->endOfMonth(); // Obtener la fecha del último día del mes actual

                        // $count = 0; // Contador para almacenar el número de sábados

                        // while ($startDate->lte($endDate)) {
                        //     if ($startDate->dayOfWeek == Carbon\Carbon::SATURDAY) {
                        //         $count++;
                        //     }
                        //     $startDate->addDay();
                        // }
                        $fechas = obtener_dias_horario_mes(\Carbon\Carbon::now()->timestamp);
                    @endphp
                    @foreach ($fechas as $fecha)
                        <div class="celda" style="flex:1; height: 40px; line-height: 40px;">
                            @php
                                $carbonDate = \Carbon\Carbon::createFromTimestamp($fecha);
                                // Obtener el nombre del día (en inglés)
                                $nombreDia = $carbonDate->format('l');
                                $numeroDiaMes = $carbonDate->day;
                                $numeroDia = $carbonDate->dayOfWeek;
                                $nombreDiaEspanol = [
                                    'domingo', 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado'
                                ][$numeroDia];
                            @endphp
                            {{-- {{$nombreDiaEspanol}} - {{$numeroDiaMes}} --}}
                            <span class="nombre-dia-abreviado" data-dia="{{$numeroDia}}"></span> - {{ $numeroDiaMes }}
                        </div>
                    @endforeach
                </div>
                <div class="horas-tramos-pistas">
                    @foreach ($pistas as $index => $pista)
                        <div class="slots-pista">
                            <div class="slots-horas">
                                @for ($i = 0; $i < count($fechas); $i++)
                                    <div class="celda" style="flex:1; height: 40px; line-height: 40px;">
                                    </div>
                                @endfor

                                {{-- @foreach ($pista->horario_con_reservas_por_mes(\Carbon\Carbon::now()->month) as $item) --}}
                                @foreach ($pista->horario_con_reservas_por_mes(\Carbon\Carbon::now()->timestamp) as $item)
                                @foreach ($item as $intervalo)
                                {{-- {{dd($intervalo)}}; --}}

                                        <div data-left="{{ $intervalo['hora'] }}"
                                            data-width="{{ $intervalo['width'] }}"
                                            class="slot celda slot-reserva"
                                            style="left:{{ $intervalo['hora'] }}%;width: {{ $intervalo['width'] }}%;height:40px;position:absolute;z-index:20">
                                            <div

                                                @if (!$intervalo['valida'])

                                                    @switch($intervalo['estado'])

                                                    @case('reservado')
                                                    class="btn-reservado"
                                                    @break
                                                    @case('desactivado')
                                                    class="btn-no-disponible"
                                                    @break

                                                    @endswitch
                                                @endif>
                                                <a
                                                    @if (!$intervalo['valida']) href="#"  class="d-block h-100"  @else data-toggle="tooltip" data-html="true" data-placement="top"
                                                    title="{{ $pista->nombre }}" class="d-block h-100" href="/{{ request()->slug_instalacion }}/{{ $pista->tipo }}/{{ $pista->id }}/{{ $intervalo['timestamp'] }}" @endif><span class="show-responsive">  @if (!$intervalo['valida'])

                                                            @switch($intervalo['estado'])

                                                                @case('reservado')
                                                                RESERVADA
                                                                @break
                                                                @case('desactivado')
                                                                NO DISPONIBLE
                                                                @break


                                                            @endswitch
                                                            @else



                                                        @endif




                                                    </span></a>
                                            </div>
                                        </div>
                                    @endforeach
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- <div class="block-before"
                    style="top: {{ $pistas->count() * (40/$pistas->count()) }}px;width: {{ $block_pista }}px"></div> --}}
            </div>
        </div>

        <div class="leyenda text-right">
            <div class="reservada">Reservada</div>
            <div class="disponible">Libre</div>
            <div class="no-disponible">No disponible</div>
        </div>
</div>

<script>


    // Evento para actualizar los nombres de los días cuando cambia el ancho de la pantalla

    // Llamada inicial para configurar los nombres de los días
    window.addEventListener("DOMContentLoaded", function (){
        function actualizarNombresDias(){
            const anchoPantalla = window.innerWidth;
            const celdas = document.querySelectorAll('.nombre-dia-abreviado');

            const diasMovil = ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"];
            const diasEscritorio = ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"];

            celdas.forEach(function (celda) {
                const numeroDia = celda.dataset.dia;

                celda.textContent = anchoPantalla < 550 ? diasMovil[numeroDia] : diasEscritorio[numeroDia];
            });

        }
        actualizarNombresDias();
        window.addEventListener('resize', actualizarNombresDias);
    });
</script>
