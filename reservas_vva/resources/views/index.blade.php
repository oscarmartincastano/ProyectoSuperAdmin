@extends('layouts.home')
@section('content')

    <body>
        <a href="https://wa.me/34675045062" target="_blank">
            <svg id="btn-whatsapp" xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-whatsapp"
                width="22" height="22" viewBox="0 0 24 24" stroke-width="1.5" stroke="white" fill="none"
                stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                <path d="M3 21l1.65 -3.8a9 9 0 1 1 3.4 2.9l-5.05 .9"></path>
                <path
                    d="M9 10a0.5 .5 0 0 0 1 0v-1a0.5 .5 0 0 0 -1 0v1a5 5 0 0 0 5 5h1a0.5 .5 0 0 0 0 -1h-1a0.5 .5 0 0 0 0 1">
                </path>
            </svg>
        </a>
        <section id="sectionHeader">
            <div class="ast-container row">
                <div class="col-12 col-lg-6 d-flex flex-column justify-content-center">
                    <h3 style="font-size: 44px; color: #203a74; margin-bottom: 50px;">Digitaliza tu instalación</h3>
                    <p style="color: #4a4a4a; font-weight: 900; font-size: 22px; margin-bottom: 50px">Te ofrecemos una solución integral
                        diseñada
                        para simplificar la gestión de tu espacio y eventos que organices, de forma fácil, ahorrando tiempo
                        y dinero con una única herramienta de gestión.

                        En Gestión Instalación nos adaptamos a todo tipo de sectores.
                    </p>
                    <a class="button-inic d-flex justify-content-center align-items-center" href="https://wa.me/34675045062"
                        target="_blank">
                        <span class="button-content-inic">Solicita una demo</span>
                    </a>
                </div>
                <div class="col-12 col-lg-6 text-center">
                    <img src="img/inicio/gestion-instalacion-inicio.png" alt="Sistema de reserva de instalaciones" title="Sistema de reserva de instalaciones" style="width: 75%;">
                </div>
            </div>
        </section>

        <section style="background-color: var(--secundary-color); color: white; text-align: center;" id="sectionTaller">
            <div class="text-center tituloItalic" style="font-size: 32px; margin-bottom:8px !important; line-height: 53px; display: inline; font-family: 'Roboto-black';">
                ¿Listo para llevar tu <h1 style="display: inline">instalación deportiva</h1> al siguiente nivel?<br />En Gestión Instalación te ayudamos</div>
        </section>

        <section style="margin-inline: auto;" id="sectionPorque">
            <h2 class="text-center tituloItalic" style="font-size: 32px; margin-bottom:50px !important; color: #203a74;">
                ¿Por qué gestionar las
                reservas con nosotros?</h2>
            <div>
                <article class="d-flex article-why-choose">
                    <div>
                        <img src="img/inicio/iconos/facilidad.png" alt="Facilidad a la hora de gestionar tu instalación" title="Facilidad a la hora de gestionar tu instalación">
                    </div>
                    <div>
                        <h6>Facilidad</h6>
                        <p>Nuestra interfaz es amigable lo que hará que la gestión la realices de una forma más intuitiva y
                            sencilla.</p>
                    </div>
                </article>

                <article class="d-flex article-why-choose">
                    <div>
                        <img src="img/inicio/iconos/funcionalidad.png" alt="Múltiples posibilidades en tu software de reservas" title="Múltiples posibilidades en tu software de reservas">
                    </div>
                    <div>
                        <h6>Funcionalidades</h6>
                        <p>Te ofrecemos la posibilidad de gestionar el control de accesos, el aforo, la gestión financiera,
                            gestión de cobro y mucho más!!!</p>
                    </div>
                </article>

                <article class="d-flex article-why-choose">
                    <div>
                        <img src="img/inicio/iconos/soporte.png" alt="Soporte permanente en tu software de reservas" title="Soporte permanente en tu software de reservas">
                    </div>
                    <div>
                        <h6>Soporte</h6>
                        <p>Te acompañamos de forma permanente, ofreciéndote el soporte que necesites en para tus operaciones
                            o incidencias que puedan surgir.</p>
                    </div>
                </article>

                <article class="d-flex article-why-choose">
                    <div>
                        <img src="img/inicio/iconos/personalizacion.png" alt="Personalización en tu software de reservas" title="Personalización en tu software de reservas">
                    </div>
                    <div>
                        <h6>Personalización</h6>
                        <p>Te escuchamos y adaptamos la herramienta a las necesidades de tu espacio, y creando soluciones
                            que se ajusten perfectamente a él.
                        </p>
                    </div>
                </article>

            </div>
        </section>
        <section id="sectionServicios">
            <h2 class="text-center tituloItalic" style="font-size: 32px; color: #203a74;">Nuestros servicios</h2>
            <div class="row d-flex justify-content-center ast-container g-md-3" id="container-destino">
                <div class="col-7 col-md-4 flex-column d-flex justify-content-center align-items-center">
                    <img class="gestion-bubble" src="img/circulo-gestion.png" alt="Versatilidad en la reserva de las instalaciones" title="Versatilidad en la reserva de las instalaciones">
                    <h6 class="text-center tituloDestino">Versatilidad en reservas</h6>
                    <p>Configura y gestiona pistas de pádel, tenis, fútbol, salas polivalentes y más, todo en un solo lugar.
                    </p>
                </div>
                <div class="col-7 col-md-4 flex-column d-flex flex-column justify-content-center align-items-center">
                    <img class="gestion-bubble" src="img/circulo-gestion.png" alt="Gestiona eventos e inscripciones desde la aplicación" title="Gestiona eventos e inscripciones desde la aplicación">
                    <h6 class="text-center tituloDestino">Gestión de eventos e inscripciones</h6>
                    <p>Desde carreras populares, torneos de pádel, hasta cursillos de natación, facilitamos la inscripción y
                        administración de cualquier tipo de
                        eventos.</p>
                </div>
                <div class="col-7 col-md-4 flex-column d-flex flex-column justify-content-center align-items-center">
                    <img class="gestion-bubble" src="img/circulo-gestion.png" alt="Gestiona tus clases colectivas desde el software de reservas"title="Gestiona tus clases colectivas desde el software de reservas">
                    <h6 class="text-center tituloDestino">Clases colectivas a tu medida</h6>
                    <p>Ofrece y gestiona clases como pilates, spinning, jornadas de formación, con un control total sobre
                        las plazas disponibles
                    </p>
                </div>
            </div>
        </section>
        <section id="sectionClientes">
            <div id="clientes" class="ast-container">
                <h2 class="text-center px-3 tituloItalic" style="font-size:32px; color: white;">Nuestra app es ideal
                    para <br/> la gestión de espacios como</h2>
                <div class="d-flex justify-content-center align-items-center px-3 py-5" id="divOfrecer">
                    <article>
                        <div>
                            <img src="{{ asset('img/inicio/clientes/instalaciones-dep.png') }}"
                                alt="Reserva de instalaciones deportivas" title="Reserva de instalaciones deportivas">
                        </div>
                        <div>
                            <p>Instalaciones deportivas</p>
                        </div>
                    </article>
                    <article>
                        <div>
                            <img src="{{ asset('img/inicio/clientes/comunidades.png') }}"
                                alt="Reserva de instalaciones para comunidades de vecinos" title="Reserva de instalaciones para comunidades de vecinos">
                        </div>
                        <div>
                            <p>Comunidades de vecinos</p>
                        </div>
                    </article>
                    <article>
                        <div>
                            <img src="{{ asset('img/inicio/clientes/entrenador-personal.png') }}"
                                alt="Reserva de instalaciones para entrenadores personales" title="Reserva de instalaciones para entrenadores personales">
                        </div>
                        <div>
                            <p>Entrenadores personales</p>
                        </div>
                    </article>
                    <article>
                        <div>
                            <img src="{{ asset('img/inicio/clientes/club-social.webp') }}"
                                alt="Reserva de instalaciones para clubes sociales" title="Reserva de instalaciones para clubes sociales">
                        </div>
                        <div>
                            <p>Clubes sociales</p>
                        </div>
                    </article>
                    <article>
                        <div>
                            <img src="{{ asset('img/inicio/clientes/fisio.png') }}" alt="Reserva de instalaciones para centros de fisioterapia" title="Reserva de instalaciones para centros de fisioterapia">
                        </div>
                        <div>
                            <p>Centros de fisioterapia</p>
                        </div>
                    </article>
                    <article>
                        <div>
                            <img src="{{ asset('img/inicio/clientes/dental.png') }}" alt="Reserva de instalaciones para clínicas dentales" title="Reserva de instalaciones para clínicas dentales">
                        </div>
                        <div>
                            <p>Clínicas dentales</p>
                        </div>
                    </article>
                    <article>
                        <div>
                            <img src="{{ asset('img/inicio/clientes/formacion.png') }}" alt="Reserva de instalaciones para centros de formación" title="Reserva de instalaciones para centros de formación">
                        </div>
                        <div>
                            <p>Centros de formación</p>
                        </div>
                    </article>
                    <article>
                        <div>
                            <img src="{{ asset('img/inicio/clientes/gimnasio.png') }}" alt="Reserva de instalaciones para gimnasios" title="Reserva de instalaciones para gimnasios">
                        </div>
                        <div>
                            <p>Gimnasios</p>
                        </div>
                    </article>
                    <article>
                        <div>
                            <img src="{{ asset('img/inicio/clientes/ayto-cordoba.webp') }}"
                                alt="Reserva de instalaciones para ayuntamientos" title="Reserva de instalaciones para ayuntamientos">
                        </div>
                        <div>
                            <p>Ayuntamientos</p>
                        </div>
                    </article>
                </div>
            </div>
        </section>
        <section class="text-center" style="background-color: var(--secundary-color);" id="ventajas">
            <h2 style="color: white; font-size: 32px; margin-bottom: 30px;" class="text-center tituloItalic">Solicita una
                demostración gratuita</h2>
            <a class="button" href="https://wa.me/34675045062" target="_blank">
                <span class="button-content">¿Comenzamos?</span>
            </a>
        </section>
        <section id="priceSection" class="ast-container-2">
            <h2 class="sectionTitle">Planes de precios</h2>
            <h4 class="sectionSubTitle">Descubre Gestión Instalación, una aplicación totalmente personalizable a tus
                necesidades</h4>
            <div class="pricePlans">

                <article>
                    <div class="plansTitle">BÁSICO</div>
                    <div>
                        <div class="priceItem">40 € / mes</div>
                        <div class="priceNote">IVA no incl.</div>
                        <div class="priceButton">
                            <a href="https://wa.me/34675045062" target="_blank">Contactar</a>
                        </div>
                        <div class="
                  characterItem">
                            <ul>
                                <li>Configuración de espacios</li>
                                <li>Configuración de horarios</li>
                                <li>Configuración de reservas</li>
                                <li>Panel de administración</li>
                                <li>App móvil genérica</li>
                                <li>Copias de seguridad díarias</li>
                                <li>No incluye pago telemático</li>

                                <li>Límite: Precio para un máximo de 5 espacios. Para más espacios consulta nuestro soporte
                                </li>

                            </ul>

                        </div>

                    </div>

                </article>
                <article>
                    <div class="plansTitle">PROFESIONAL</div>
                    <div>
                        <div class="priceItem">85€ / mes</div>
                        <div class="priceNote">IVA no incl.</div>
                        <div class="priceButton">
                            <a href="https://wa.me/34675045062" target="_blank">Contactar</a>
                        </div>
                        <div class="
                  characterItem">

                            <ul>
                                <li>Plan BÁSICO</li>
                                <li>App móvil personalizada</li>
                                <li>Pago por TPV</li>
                                <li>Limite: Precio para un máximo de 10 espacios. Las comisiones bancarias del cobro serán
                                    repercutidas</li>
                            </ul>
                        </div>

                    </div>
                </article>
                <article>
                    <div class="plansTitle">PERSONALIZADO</div>
                    <div>
                        <div class="priceItem">Consultar</div>
                        <div class="priceNote">IVA no incl.</div>
                        <div class="priceButton">
                            <a href="https://wa.me/34675045062" target="_blank">Contactar</a>
                        </div>
                        <div class="
                  characterItem">
                            <ul>
                                <li>Plan PROFESIONAL</li>
                                <li>TPV Personalizado</li>
                                <li>Inscripciones a eventos</li>
                                <li>Venta de entradas</li>
                                <li>Escuelas deportivas</li>
                                <li>Servicios recurrentes</li>
                                <li>Lista de espera</li>
                                <li>Gestión de familias</li>
                                <li>Gestión de socios</li>
                                <li>Control de gastos</li>
                                <li>Gestión de remesas</li>
                                <li>Monedero electrónico</li>
                                <li>Nota: Posibilidad de hacer desarrollos personalizados bajo presupuesto</li>
                            </ul>
                        </div>

                    </div>
                </article>
            </div>

        </section>

        <section class="text-center">
            <img src="img/circulo-gestion.png" alt="Reserva de instalaciones deportivas" title="Reserva de instalaciones deportivas" style="width: 60px;">
        </section>

        <section class="row ast-container-2" id="descargar">
            <div class="col-12 col-lg-5">
                <img src="img/inicio/app-1.png" alt="Explora nuestro software de reservas" title="Explora nuestro software de reservas">
            </div>
            <div class="col-12 col-lg-7">
                <h4 style="color: #203a74; font-size: 44px; margin-bottom: 20px;">Explora nuestra app</h4>
                <p style="margin-bottom: 20px; text-align:justify">En Gestión Instalación, ponemos a tu disposición una
                    aplicación móvil
                    que permite a tus usuarios realizar reservas de manera rápida y sencilla desde sus dispositivos
                    móviles. Además, ofrecemos la opción de personalizar completamente esta experiencia, desarrollando una
                    aplicación exclusiva para tu instalación deportiva. Esta aplicación a medida incluirá tu identidad
                    visual y nombre corporativo, reforzando tu marca y ofreciendo una experiencia única a tus clientes.
                </p>
                <div class="d-flex" style="gap: 10px;">
                    <img class="imgApp" src="img/app_google_play-400x139-1.png" alt="Explora nuestro software de reservas en Google play" title="Explora nuestro software de reservas en Google play">
                    <img class="imgApp" src="img/Publicar-una-App-en-el-App-Store-400x138-1.png" alt="Explora nuestro software de reservas en App Store" title="Explora nuestro software de reservas en App Store">
                </div>
            </div>
        </section>
    </body>
@endsection
