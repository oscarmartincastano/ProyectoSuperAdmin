<div class="row">
    <div class="col text-center text-uppercase p-3 mes">
        {{\Carbon\Carbon::createFromFormat('Y-m', $newMonthYear)->formatLocalized('%B').' ' . \Carbon\Carbon::createFromFormat('Y-m', $newMonthYear)->year}}</div>
</div>
<div class="row dias">
    @foreach ($fechasMes as $fecha)
        <div class="col text-center">
            <div class="text-uppercase p-3 dia">
                {{ \Carbon\Carbon::parse($fecha)->formatLocalized('%A') }}</div>
            <div><a data-fecha="{!! $fecha = \Carbon\Carbon::createFromTimestamp($fecha);
                $fechaFormateada = $fecha->format('Y-m-d')!!}" data-fecha_long="{{  $fecha->format('d/m/Y') }}" @if(auth()->user()->instalacion->check_reservas_dia($fecha->format('Y-m-d'))) data-toggle="tooltip" data-placement="top" title="{{ auth()->user()->instalacion->check_reservas_dia($fecha->format('Y-m-d')) }} Reservas pendientes" @endif href="#" class="btn-dia w-100 h-100 d-block p-5 @if(Session::get('dia_reserva_hecha')) {{ $fecha->format('Y-m-d') == Session::get('dia_reserva_hecha') ? 'active' : '' }} @else {{ $fecha->format('d/m/Y') == date('d/m/Y') ? 'active' :  ''}} @endif"><span
                class="numero {{ auth()->user()->instalacion->check_reservas_dia($fecha->format('Y-m-d')) ? 'reservas-activas' : '' }} {{ $fecha->format('Y-m-d')<date('Y-m-d') ? 'fecha-anterior' : ($fecha->format('Y-m-d')==date('Y-m-d') ? 'hoy' : '') }}">{{ $fecha->format('d') }}</span></a></div>
        </div>
    @endforeach
</div>
<div class="row reservas-dia" style="display: block">
    <div class="loader-bg" style="display: none">
        @include('instalacion.loader.loader')
    </div>
    <div class="col">
        <div class="pb-3">
            <div class="input-group">
                <div class="input-group-append ">
                    <span class="input-group-text" style="border-left: 1px solid #06122324 !important;"><i class="fas fa-search"></i></span>
                </div>
                <input type="text" class="form-control" id="searcher-reservas" placeholder="Buscar reservas...">
            </div>
        </div>
        <ul class="nav nav-tabs nav-tabs-fillup d-none d-md-flex d-lg-flex d-xl-flex"
            data-init-reponsive-tabs="dropdownfx">
            @foreach ($pistas as $i => $item)
                <li class="nav-item">
                    <a href="#" data-fecha="{{ date('Y-m-d') }}" data-pista="{{ $item->id }}" id="tab-espacio-{{ $item->id }}" class="{{ $i == 0 ? 'active' : '' }} tab-pista" data-toggle="tab"
                        data-target="#espacio-{{ $item->id }}"><span>{{ $item->nombre_corto ?? $item->nombre }}</span><span class=""></span></a>
                </li>
            @endforeach
        </ul>
        <div class="loader-bg-pista" style="display: none">
            @include('instalacion.loader.loader')
        </div>
        <div class="tab-content reservas-dia">
            @foreach ($pistas as $i => $pista)
                <div class="tab-pane {{ $i == 0 ? 'active' : '' }}" id="espacio-{{ $pista->id }}">
                    <div><h4 class="d-inline-block"><strong>{{ $pista->nombre }}</strong> Reservas para <span class="fecha"></span></h4> <a href="/{{ request()->slug_instalacion }}/admin/reservas/{{ $pista->id }}/desactivar-dia/{{ date('Y-m-d') }}" class="btn btn-outline-primary ml-3 btn-off-dia">DESACTIVAR DÍA COMPLETO</a>  <a href="/{{ request()->slug_instalacion }}/admin/reservas/{{ $pista->id }}/activar-dia/{{ date('Y-m-d') }}" class="btn btn-outline-primary ml-3 btn-on-dia">ACTIVAR DÍA COMPLETO</a></div>
                    <div id="content-espacio-{{ $pista->id }}">

                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
