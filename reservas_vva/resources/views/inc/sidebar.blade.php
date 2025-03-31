<nav class="page-sidebar" data-pages="sidebar">
    <!-- BEGIN SIDEBAR MENU TOP TRAY CONTENT-->
    <!-- END SIDEBAR MENU TOP TRAY CONTENT-->
    <!-- BEGIN SIDEBAR MENU HEADER-->
    <div class="sidebar-header">
            <img src="/img/{{ request()->slug_instalacion }}.png" alt="logo" class="brand"
                data-src="/img/{{ request()->slug_instalacion }}.png" data-src-retina="/img/{{ request()->slug_instalacion }}.png"
                style="max-height: 100px;max-width:92%">

    </div>
    <!-- END SIDEBAR MENU HEADER-->
    <!-- START SIDEBAR MENU -->
    <div class="sidebar-menu">
        <!-- BEGIN SIDEBAR MENU ITEMS-->
        @if(auth()->user()->rol == 'manager')
        <ul class="menu-items">
            <li class="m-t-10 {{ request()->is('/manager') ? 'active' : '' }}">
                <a href="/manager">
                    <span class="title">Inicio</span>
                </a>
                <span class="icon-thumbnail"><i data-feather="home"></i></span>
            </li>
            <li class="m-t-10 {{ request()->is('/manager/devoluciones') ? 'active' : '' }}">
                <a href="/manager/devoluciones">
                    <span class="title">Devoluciones</span>
                </a>
                <span class="icon-thumbnail"><i class="fa-solid fa-repeat"></i></span>
            </li>
            <li class="m-t-10 {{ request()->is('/manager/instalaciones') ? 'active' : '' }}">
                <a href="/manager/instalaciones">
                    <span class="title">Instalaciones</span>
                </a>
                <span class="icon-thumbnail"><i class="fa-regular fa-building"></i></span>
            </li>
            <li class="m-t-10 {{ request()->is('/manager/servicios') ? 'active' : '' }}">
                <a href="/manager/servicios">
                    <span class="title">Servicios</span>
                </a>
                <span class="icon-thumbnail"><i class="fa-solid fa-hand-holding"></i></span>
            </li>

            <li class="m-t-10 {{ request()->is('/manager/deportes') ? 'active' : '' }}">
                <a href="/manager/deportes">
                    <span class="title">Deportes</span>
                </a>
                <span class="icon-thumbnail"><i class="far fa-futbol"></i></span>
            </li>
            <li class="m-t-10">
                <a href="{{ route('logout.manager') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <form id="logout-form" action="{{ route('logout.manager') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                    <span class="title">Cerrar sesión</span>
                </a>
                <span class="icon-thumbnail"><i data-feather="power"></i></span>
            </li>
        </ul>
        @else
        <ul class="menu-items">
            <li class="m-t-10 {{ request()->is(request()->slug_instalacion . '/admin') ? 'active' : '' }}">
                <a href="/{{ request()->slug_instalacion }}/admin/">
                    <span class="title">Inicio</span>
                </a>
                <span class="icon-thumbnail"><i data-feather="home"></i></span>
            </li>
            @if (request()->slug_instalacion != "villafranca-navidad" && request()->slug_instalacion != "villafranca-actividades" or request()->slug_instalacion != "ciprea24" or request()->slug_instalacion == "eventos-bodega" or request()->slug_instalacion == "feria-jamon-villanuevadecordoba")
            <li class=" {{ request()->is(request()->slug_instalacion . '/admin/reservas*') ? 'open active' : '' }}">
                <a href="javascript:;"><span class="title">Reservas</span>
                <span class="arrow {{ request()->is(request()->slug_instalacion . '/admin/reservas*') ? 'open active' : '' }}"></span></a>
                <span class="icon-thumbnail"><i data-feather="book"></i></span>
                <ul class="sub-menu p-0" style=" {{ request()->is(request()->slug_instalacion . '/admin/reservas*') ? 'display:block' : '' }}">
                    @if(auth()->user()->subrol != 'piscina')
                    <li class="{{ request()->is(request()->slug_instalacion . '/admin/reservas/list') ? 'active' : '' }}">
                        <a href="/{{ request()->slug_instalacion }}/admin/reservas/list">Listado</a>
                        <span class="icon-thumbnail">li</span>
                    </li>
                    @endif
                    @if(request()->slug_instalacion == 'vvadecordoba')
                    @if(auth()->user()->subrol != 'deportes')
                    {{-- <li class="{{ request()->is(request()->slug_instalacion . '/admin/reservas/list-piscina') ? 'active' : '' }}">
                        <a href="/{{ request()->slug_instalacion }}/admin/reservas/list-piscina">Listado Piscina</a>
                        <span class="icon-thumbnail">lp</span>
                    </li>
                    <li class="{{ request()->is(request()->slug_instalacion . '/admin/reservas/list-piscina-asistentes') ? 'active' : '' }}">
                        <a href="/{{ request()->slug_instalacion }}/admin/reservas/list-piscina-asistentes">Listado nº asistentes piscina</a>
                        <span class="icon-thumbnail">ap</span>
                    </li> --}}
                    @endif
                    @endif
                    @if(auth()->user()->subrol == 'admin')
                    <li class="{{ request()->is(request()->slug_instalacion . '/admin/reservas/periodicas')  ? 'active' : '' }}">
                        <a href="/{{ request()->slug_instalacion }}/admin/reservas/periodicas">Reservas periódicas</a>
                        <span class="icon-thumbnail">rp</span>
                    </li>
                    <li class="{{ request()->is(request()->slug_instalacion . '/admin/reservas/desactivaciones') ? 'active' : '' }}">
                        <a href="/{{ request()->slug_instalacion }}/admin/reservas/desactivaciones">Desactivaciones periódicas</a>
                        <span class="icon-thumbnail">dp</span>
                    </li>
                    @endif
                </ul>
            </li>

            @endif
            @if(auth()->user()->subrol != 'piscina')
            <li class=" {{ request()->is(request()->slug_instalacion . '/admin/orders*') ? 'open active' : '' }}">
                <a href="javascript:;"><span class="title">Pedidos</span>
                <span class="arrow {{ request()->is(request()->slug_instalacion . '/admin/orders*') ? 'open active' : '' }}"></span></a>
                <span class="icon-thumbnail"><i class="fa-solid fa-dollar-sign"></i></span>
                <ul class="sub-menu" style=" {{ request()->is(request()->slug_instalacion . '/admin/orders*') ? 'display:block' : '' }}">
                    @if (request()->slug_instalacion != "villafranca-navidad" && request()->slug_instalacion != "villafranca-actividades" && request()->slug_instalacion != "ciprea24" or request()->slug_instalacion == "eventos-bodega" or request()->slug_instalacion == "feria-jamon-villanuevadecordoba")
                    <li class="{{ request()->is(request()->slug_instalacion . '/admin/orders') ? 'active' : '' }}">
                        <a href="/{{ request()->slug_instalacion }}/admin/orders/reservas">Reservas</a>
                        <span class="icon-thumbnail">re</span>
                    </li>
                @endif
                  <li class="{{ request()->is(request()->slug_instalacion . '/admin/orders/tipos-clientes') ? 'active' : '' }}">
                      <a href="/{{ request()->slug_instalacion }}/admin/orders/eventos">Eventos</a>
                      <span class="icon-thumbnail">ev</span>
                    </li>
                    @if (request()->slug_instalacion == "villafranca-navidad" || request()->slug_instalacion == "villafranca-actividades" || request()->slug_instalacion == "ciprea24" || request()->slug_instalacion == "eventos-bodega" || request()->slug_instalacion == "feria-jamon-villanuevadecordoba" && (Auth::user()->id == 2713 || Auth::user()->id == 2833 ))
                        <li class="{{ request()->is(request()->slug_instalacion . '/admin/orders/create-entradas') ? 'active' : '' }}">
                            <a href="/{{ request()->slug_instalacion }}/admin/orders/create-entradas">Crear entradas</a>
                            <span class="icon-thumbnail">ce</span>
                        </li>
                    @endif
                </ul>
            </li>
            @endif
           {{--  @if(request()->slug_instalacion == 'vvadecordoba')
            <li class="{{ request()->is(request()->slug_instalacion . '/admin/orders?tipo=Piscina') ? 'active' : '' }}">
                <a href="/{{ request()->slug_instalacion }}/admin/orders/Piscina">
                    <span class="title">Pedidos piscina</span>
                </a>
                <span class="icon-thumbnail"><i class="fa-solid fa-dollar-sign"></i></span>
            </li>@endif --}}
            @if (request()->slug_instalacion != "villafranca-navidad" && request()->slug_instalacion != "villafranca-actividades" && request()->slug_instalacion != "ciprea24" && request()->slug_instalacion != "eventos-bodega" && request()->slug_instalacion != "feria-jamon-villanuevadecordoba")

            <li class="{{ request()->is(request()->slug_instalacion . '/admin/servicios/') ? 'active' : '' }}">
                <a href="/{{ request()->slug_instalacion }}/admin/servicios/">
                    <span class="title">Servicios</span>
                </a>
                <span class="icon-thumbnail"><i class="fa-solid fa-bullseye"></i></span>
            </li>
            <li class="{{ request()->is(request()->slug_instalacion . '/admin/orders/informes') ? 'active' : '' }}">
                <a href="/{{ request()->slug_instalacion }}/admin/orders/informes">
                    <span class="title">Informes</span>
                </a>
                <span class="icon-thumbnail"><i class="fa-solid fa-file-lines"></i></span>
            </li>
            <li class="{{ request()->is(request()->slug_instalacion . '/admin/mensajes*') ? 'active' : '' }}">
                <a href="/{{ request()->slug_instalacion }}/admin/mensajes" >
                    <span class="title">Mensajes informativos</span>
                </a>
                <span class="icon-thumbnail"><i data-feather="bell"></i></span>
            </li>
            @endif

            <li class=" {{ request()->is(request()->slug_instalacion . '/admin/eventos*') ? 'open active' : '' }}">
                <a href="javascript:;"><span class="title">Eventos</span>
                <span class="arrow {{ request()->is(request()->slug_instalacion . '/admin/eventos*') ? 'open active' : '' }}"></span></a>
                <span class="icon-thumbnail"><i class="fa-solid fa-trophy"></i></span>
                <ul class="sub-menu" style=" {{ request()->is(request()->slug_instalacion . '/admin/eventos*') ? 'display:block' : '' }}">
                  <li class="{{ request()->is(request()->slug_instalacion . '/admin/eventos') ? 'active' : '' }}">
                    <a href="/{{ request()->slug_instalacion }}/admin/eventos">Listado</a>
                    <span class="icon-thumbnail">in</span>
                  </li>
                  @if (request()->slug_instalacion == "villafranca-navidad" || request()->slug_instalacion == "villafranca-actividades" || request()->slug_instalacion == "ciprea24" || request()->slug_instalacion == "eventos-bodega" || request()->slug_instalacion == "feria-jamon-villanuevadecordoba")
                    <li class="{{ request()->is(request()->slug_instalacion . '/admin/eventos/checkin') ? 'active' : '' }}">
                        <a href="/{{ request()->slug_instalacion }}/admin/eventos/checkin">Check in</a>
                        <span class="icon-thumbnail">ch</span>
                    </li>
                 @else
                 @endif
                  <li class="{{ request()->is(request()->slug_instalacion . '/admin/eventos/tipos-clientes') ? 'active' : '' }}">
                      <a href="/{{ request()->slug_instalacion }}/admin/eventos/tipos-clientes">Tipos clientes</a>
                      <span class="icon-thumbnail">ti</span>
                    </li>
                    <li class="{{ request()->is(request()->slug_instalacion . '/admin/eventos/listado-participantes') ? 'active' : '' }}">
                        <a href="/{{ request()->slug_instalacion }}/admin/eventos/listado-participantes">Listado participantes</a>
                    <span class="icon-thumbnail">lp</span>
                    </li>
                    <li class="{{ request()->is(request()->slug_instalacion . '/admin/eventos/informes-participantes') ? 'active' : '' }}">
                        <a href="/{{ request()->slug_instalacion }}/admin/eventos/informes-participantes">Informes de participantes</a>
                        <span class="icon-thumbnail"><i class="fa-solid fa-file-lines"></i></span>
                    </li>
                </ul>
            </li>
            {{-- <li class="{{ request()->is(request()->slug_instalacion . '/admin/eventos*') ? 'active' : '' }}">
                <a href="/{{ request()->slug_instalacion }}/admin/eventos" >
                    <span class="title">Evento</span>
                </a>
                <span class="icon-thumbnail"><i class="fa-solid fa-trophy"></i></span>
            </li> --}}

            @if(auth()->user()->subrol == 'admin' and request()->slug_instalacion != "villafranca-navidad" and request()->slug_instalacion != "villafranca-actividades" and request()->slug_instalacion != "ciprea24" and request()->slug_instalacion != "eventos-bodega" and request()->slug_instalacion != "feria-jamon-villanuevadecordoba")



            <li class="{{ request()->is(request()->slug_instalacion . '/admin/pistas*') ? 'active' : '' }}">
                <a href="/{{ request()->slug_instalacion }}/admin/pistas">
                    <span class="title">Espacios</span>
                </a>
                <span class="icon-thumbnail"><i class="material-icons sports_tennis">&#xea32;</i></span>
            </li>
            @endif
            <li class="{{ request()->is(request()->slug_instalacion . '/admin/users*') ? 'active' : '' }}">
                <a href="/{{ request()->slug_instalacion }}/admin/users">
                    <span class="title" style="position: relative">Usuarios @if (count(auth()->user()->instalacion->users_sin_validar))<mark class="mark">{{ count(auth()->user()->instalacion->users_sin_validar) }}</mark>@endif</span>
                </a>
                <span class="icon-thumbnail"><i data-feather="users"></i></span>
            </li>
            @if(request()->slug_instalacion == 'la-guijarrosa' || request()->slug_instalacion == 'santaella')
            <li class=" {{ request()->is(request()->slug_instalacion . '/admin/puertas*') ? 'open active' : '' }}">
                <a href="javascript:;"><span class="title">Control de accesos</span>
                <span class="arrow {{ request()->is(request()->slug_instalacion . '/admin/puertas*') ? 'open active' : '' }}"></span></a>
                <span class="icon-thumbnail"><i class="fa-solid fa-door-closed"></i></span>
                <ul class="sub-menu" style=" {{ request()->is(request()->slug_instalacion . '/admin/puertas*') ? 'display:block' : '' }}">
                  <li class="{{ request()->is(request()->slug_instalacion . '/admin/puertas/listado') ? 'active' : '' }}">
                    <a href="/{{ request()->slug_instalacion }}/admin/puertas/listado">Listado de Accesos</a>
                    <span class="icon-thumbnail">la</span>
                  </li>
                    <li class="{{ request()->is(request()->slug_instalacion . '/admin/puertas/usuarios_accesos') ? 'active' : '' }}">
                        <a href="/{{ request()->slug_instalacion }}/admin/puertas/usuarios_accesos">Listado usuarios</a>
                        <span class="icon-thumbnail">aa</span>
                    </li>
                </ul>
              </li>
            @endif
            {{-- <li class="{{ request()->is(request()->slug_instalacion . '/admin/cobro*') ? 'active' : '' }}">
                <a href="/{{ request()->slug_instalacion }}/admin/cobro">
                    <span class="title">Cobros</span>
                </a>
                <span class="icon-thumbnail"><i data-feather="credit-card"></i></span>
            </li> --}}
            @if(auth()->user()->subrol == 'admin' or request()->slug_instalacion == "los-agujetas-de-villafranca")
            <li class=" {{ request()->is(request()->slug_instalacion . '/admin/configuracion*') ? 'open active' : '' }}">
              <a href="javascript:;"><span class="title">Configuracion</span>
              <span class="arrow {{ request()->is(request()->slug_instalacion . '/admin/configuracion*') ? 'open active' : '' }}"></span></a>
              <span class="icon-thumbnail"><i data-feather="settings"></i></span>
              <ul class="sub-menu" style=" {{ request()->is(request()->slug_instalacion . '/admin/configuracion*') ? 'display:block' : '' }}">
                <li class="{{ request()->is(request()->slug_instalacion . '/admin/configuracion/instalacion') ? 'active' : '' }}">
                  <a href="/{{ request()->slug_instalacion }}/admin/configuracion/instalacion">Instalación</a>
                  <span class="icon-thumbnail">in</span>
                </li>
                  <li class="{{ request()->is(request()->slug_instalacion . '/admin/configuracion/servicios') ? 'active' : '' }}">
                      <a href="/{{ request()->slug_instalacion }}/admin/configuracion/servicios">Servicios</a>
                      <span class="icon-thumbnail">se</span>
                  </li>
                  
                  @if((request()->slug_instalacion == 'santaella'))
                  <li class="{{ request()->is(request()->slug_instalacion . '/admin/configuracion/bonos') ? 'active' : '' }}">
                    <a href="/{{ request()->slug_instalacion }}/admin/configuracion/bonos">Bonos</a>
                    <span class="icon-thumbnail">bn</span>
                </li>
                @endif
                <li class="{{ request()->is(request()->slug_instalacion . '/admin/configuracion/pistas-reservas') ? 'active' : '' }}">
                    <a href="/{{ request()->slug_instalacion }}/admin/configuracion/pistas-reservas">Pistas y reservas</a>
                    <span class="icon-thumbnail">pi</span>
                  </li>
                  <li class="{{ request()->is(request()->slug_instalacion . '/admin/configuracion/dias-festivos') ? 'active' : '' }}">
                    <a href="/{{ request()->slug_instalacion }}/admin/configuracion/dias-festivos">Días festivos</a>
                    <span class="icon-thumbnail">df</span>
                  </li>
              </ul>
            </li>

            <li class="{{ request()->is(request()->slug_instalacion . '/admin/campos-adicionales') ? 'active' : '' }}">
              <a href="/{{ request()->slug_instalacion }}/admin/campos-adicionales">Campos adicionales en reservas</a>
              <span class="icon-thumbnail"><i data-feather="plus-circle"></i></span>
            </li>
            @endif
            {{-- <li class="{{ request()->is(request()->slug_instalacion . '/admin/configuracion*') ? 'active' : '' }}">
                <a href="/{{ request()->slug_instalacion }}/admin/configuracion">
                    <span class="title">Configuracion</span>
                </a>
                <span class="icon-thumbnail"><i data-feather="settings"></i></span>
            </li> --}}
            <li>
                <a href="{{ route('logout', ['slug_instalacion' => request()->slug_instalacion]) }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <form id="logout-form" action="{{ route('logout', ['slug_instalacion' => request()->slug_instalacion]) }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                    <span class="title">Cerrar sesión</span>
                </a>
                <span class="icon-thumbnail"><i data-feather="power"></i></span>
            </li>
        </ul>
        @endif
        <div class="clearfix"></div>
    </div>
    <!-- END SIDEBAR MENU -->
</nav>
