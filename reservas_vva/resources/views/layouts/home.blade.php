<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description"
        content="Descubre nuestra solución integral para la reserva de instalaciones y espacios deportivos. Simplificamos la gestión de tus actividades deportivas con eficiencia y  versatilidad.">
        <meta name="keywords" content="Reservas deportivas, gestor de reservas, reservas, instalación, gestión de inscripciones y eventos, gestión de escuelas deportivas">
        <title>Gestión instalación - Software de reserva de instalaciones deportivas</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
        integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Bulma Version 0.9.0-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/bulma@0.9.0/css/bulma.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/pickadate.js/3.5.6/themes/default.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/pickadate.js/3.5.6/themes/default.date.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/landing/styleAlfonso.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/landing/card.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/landing/button.css') }}" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link rel="icon" href="favicon.ico" type="image/x-icon">

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"
        integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pickadate.js/3.5.6/picker.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pickadate.js/3.5.6/picker.date.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pickadate.js/3.5.6/translations/es_ES.js"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.colVis.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.1/js/bootstrap.bundle.min.js"
        integrity="sha512-mULnawDVcCnsk9a4aG1QLZZ6rcce/jSzEGqUkeOLy0b6q0+T6syHrxlsAGH7ZVoqC93Pd0lBqd6WguPWih7VHA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    {{-- SCRIPT SLIDER --}}
    <script src="js/landing.js"></script>
    @yield('style')
    <style>
        :root {
            --main-color: #203a74;
            --secundary-color: #ef7d1a;
        }

        html {
            scroll-padding-top: 96px;
        }

        @font-face {
            font-family: 'Roboto';
            src: url(fonts/Roboto/Roboto-Bold.ttf);
        }

        @font-face {
            font-family: 'Poppins';
            src: url(fonts/Poppins/Poppins-Bold.ttf);
        }

        @font-face {
            font-family: 'Noto Sans';
            src: url(fonts/Noto_Sans/NotoSans-Regular.ttf);
        }

        .tituloPueblos {
            font-family: 'Poppins', sans-serif;
        }


        * {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
        }

        header>nav {
            background-color: #203a74;
        }

        .navbar {
            position: fixed;
            width: 100%;
            top: 0;
            left: 0;
            z-index: 9999;
        }


        .navbar-nav a {
            color: white !important;
        }

        #navbarNav {
            padding-right: 4%;
        }

        #logo {
            width: 80px;
            margin-left: 2%;
        }

        #logo2 {
            width: 230px;
        }

        #button {
            margin-right: 2%;
        }

        #sectionHeader {
            color: black;
            position: relative;
            padding-block: 20px;
        }

        #sectionHeader>div {
            margin: 0 auto;
        }



        .fusion-separator {
            height: 1px;
            width: 100%;
            max-width: 10%;
        }


        #separador {
            border-bottom: 1px solid black;
            width: 100%;
        }

        #ventajasSectionIconos>div {
            margin-bottom: 14px;
        }

        #ventajasSectionIconos>div>div {
            gap: 7px;
        }

        div.carousel-item>div>div:first-of-type {
            height: 300px;
            background-image: url(img/villanueva.jpg);
            background-size: cover;
            /* background-attachment: fixed; */
            background-position: center;
            background-repeat: no-repeat;
        }

        div.carousel-item>div>div:nth-of-type(2) {
            height: 300px;
            background-image: url(img/villafranca.jpg);
            background-size: cover;
            /* background-attachment: fixed; */
            background-position: center;
            background-repeat: no-repeat;
        }

        #usanNuestraApp {
            display: flex;
            justify-content: center;
            gap: 4%;
        }

        #usanNuestraApp>div:first-of-type {
            display: flex;
            justify-content: center;
            flex-direction: column;
            align-items: center;
            height: 300px;
            background-image: url(img/villanuevaCopia.jpg);
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            width: 375px;
        }

        #usanNuestraApp>div:last-of-type {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            width: 375px;
            height: 300px;
            background-image: url(img/villafrancaCopia.jpg);
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .carousel .carousel-indicators li {
            background-color: black;
        }

        .carousel .carousel-indicators li.active {
            background-color: rgba(0, 0, 0, 0.541);
        }

        .carousel-control-next,
        .carousel-control-prev

        /*, .carousel-indicators */
            {
            filter: invert(100%);
        }


        #parrafoOfrecer {
            color: #203a74;
        }

        .imgApp {
            width: 25%;
        }

        #redes {
            gap: 5px;
        }

        @media (max-width: 768px) {
            body {
                overflow-x: hidden;
            }

            #usanNuestraApp {
                flex-direction: column;
                gap: 20px;
            }

            #usanNuestraApp>div:first-of-type {
                width: 100%;
            }

            #usanNuestraApp>div:last-of-type {
                width: 100%;

            }

            #ventajasSectionIconos>div {
                gap: 14px;
            }

            #cabeceraInformacion {
                display: none;
            }

            #imagenJustificacion {
                width: 300px
            }

            #divNuestroHorario {
                background-color: white;
                padding: 10px 0 10px 0;
            }

            #divNuestroHorario>strong {
                color: #203a74 !important;
            }

            #divNuestroHorario>ul>li {
                color: #203a74;
            }
        }

        @media(max-width:500px) {
            #logo2 {
                display: none;
            }

            .imgApp {
                width: 50%;
            }
        }


        @media (max-width: 992px) {
            #navbarNav>ul>li {
                text-align: end;
            }
        }

        @media (min-width: 992px) {
            .navbar-expand-lg .navbar-collapse {
                justify-content: flex-end;
            }

            #navbarNav {
                padding-right: 10%;
            }


        }
    </style>
    <!-- Hotjar Tracking Code for La Guijarrosa -->
    @if (request()->slug_instalacion == "la-guijarrosa")
    <script>
        (function(h,o,t,j,a,r){
            h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
            h._hjSettings={hjid:5337821,hjsv:6};
            a=o.getElementsByTagName('head')[0];
            r=o.createElement('script');r.async=1;
            r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
            a.appendChild(r);
        })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
    </script>
    @endif
</head>

<body>
    <!-- EMPIEZA EL MENÚ MÓVIL -->
    <header>
        <div class="mobile-menu">
            <!-- Añadimos el input y la etiqueta para el botón de hamburguesa -->
            <div class="logo-mobile">
                <a href="/">
                    <img src="img/LOGO-versiones-02.png" alt="Gestión de reservas de instalaciones deportivas" title="Gestión de reservas de instalaciones deportivas">
                    <img src="img/LOGO-versiones-03.png" alt="Gestión de reservas de instalaciones deportivas" title="Gestión de reservas de instalaciones deportivas">
                </a>
            </div>
            <input type="checkbox" id="menuToggle">
            <label class="hamburger-menu" for="menuToggle">☰</label>

            <!-- Añadimos el menú desplegable -->
            <nav class="first-mobile-menu">
                <ul>
                    <li>
                        <a href="/#priceSection">Precios</a>
                    </li>
                    <li>
                        <a href="/#clientes">Clientes</a>
                    </li>
                    <li>
                        <a href="/agente-digitalizador">Kit digital</a>
                    </li>
                    <li class="mobile-featured-menu">
                        <a href="https://wa.me/34675045062" target="_blank">Solicitar demo</a>
                    </li>
                </ul>
            </nav>
        </div>
        <!-- TERMINA EL MENÚ MÓVIL y EMPIEZA EL DESKTOP -->
        <div class="desktop-menu ast-container">
            <div class="logo-desktop">
                <a href="/">
                    <img class="logoIsotipo" src="img/LOGO-versiones-02.png" alt="Gestión de reservas de instalaciones deportivas" title="Gestión de reservas de instalaciones deportivas">
                    <img src="img/LOGO-versiones-03.png" alt="Gestión de reservas de instalaciones deportivas" title="Gestión de reservas de instalaciones deportivas">
                </a>
            </div>
            <div class="menuPrincipal">
                <ul>
                    <li><a href="/#priceSection">Precios</a></li>
                    <li><a href="/#clientes">Clientes</a></li>
                    <li><a href="/agente-digitalizador">Kit digital</a></li>
                </ul>
            </div>
            <div class="featured-menu">
                <a href="https://wa.me/34675045062" target="_blank">Solicitar demo</a>
            </div>
        </div>
    </header>

    {{-- FIN MENU --}}
    <main>
        @yield('content')
    </main>



    <footer>
        <div class="footerContend ast-container-2">
            <ul>
                <li><img src="img/LOGO-versiones-03.png" alt="Gestión de reservas de instalaciones deportivas" title="Gestión de reservas de instalaciones deportivas"></li>
                <li style="text-align: center;"><img class="footerIsotipo" src="img/LOGO-versiones-02.png  "
                    alt="Gestión de reservas de instalaciones deportivas" title="Gestión de reservas de instalaciones deportivas"></li>

            </ul>
            <ul class="footerText">
                <li>Más Información</li>
                <hr class="linea-blanca">
                <li><a href="https://tallerempresarial.es/aviso-legal/" target="_blank">Aviso legal</a></li>
                <li><a href="https://tallerempresarial.es/politica-privacidad/" target="_blank">Política de
                        privacidad</a></li>
                <li><a href="https://tallerempresarial.es/politica-de-cookies/" target="_blank">Política de cookies</a>
                </li>
            </ul>

            <ul class="footerText">
                <li>Atención al Cliente</li>
                <hr class="linea-blanca">
                <li><i class="fa-solid fa-calendar" style="color: #ffffff;"></i> Lunes a Viernes</li>
                <li> <i class="fa-regular fa-clock" style="color: #ffffff;"></i> 09:00-15:00 y 17:00 a 19:00</li>
                <li><a href="tel:675045062"> <i class="fa-solid fa-phone" style="color: #ffffff;"></i> 675 045
                        062</a></li>
            </ul>


            <ul class="footerText">
                <li>Estamos en:</li>
                <hr class="linea-blanca">
                <li>
                    Av. Gran Capitan 12 - 14008 - Córdoba
                </li>
                <li> <a href="https://maps.app.goo.gl/gn755VCVutfrTLex6" target="_blank"><i
                            class="fa-solid fa-location-dot" style="color: #ffffff;"></i> Ver en mapa</a></li>
            </ul>

        </div>
        <div class="author">
            © Copyright 2015 - 2023 | Todos los derechos reservados | <a href="https://tallerempresarial.es"
                target="_blank">Taller Empresarial 2.0</a>
        </div>

    </footer>


    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>
    <script>
        // Hamburger menu functionality
        document.addEventListener('DOMContentLoaded', () => {
            let links = document.querySelectorAll('.first-mobile-menu a');
            links.forEach(function(link) {
                link.addEventListener('click', function(e) {
                    document.getElementById('menuToggle').checked = false;
                });
            });


            const $navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);
            if ($navbarBurgers.length > 0) {
                $navbarBurgers.forEach(el => {
                    el.addEventListener('click', () => {
                        const target = el.dataset.target;
                        const $target = document.getElementById(target);
                        el.classList.toggle('is-active');
                        $target.classList.toggle('is-active');
                    });
                });
            }
        });
    </script>
    @yield('script')
</body>

</html>
