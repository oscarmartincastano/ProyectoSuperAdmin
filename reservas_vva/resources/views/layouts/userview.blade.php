<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="/img/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Bulma Version 0.9.0-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/bulma@0.9.0/css/bulma.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/pickadate.js/3.5.6/themes/default.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/pickadate.js/3.5.6/themes/default.date.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.1/js/bootstrap.bundle.min.js" integrity="sha512-mULnawDVcCnsk9a4aG1QLZZ6rcce/jSzEGqUkeOLy0b6q0+T6syHrxlsAGH7ZVoqC93Pd0lBqd6WguPWih7VHA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    @yield('style')

    <style>
        .active{
            color: #3273dc;
        }

        a.button.active{
            border: 1px solid #3273dc;
            color: #3273dc;
        }
        footer.footer{
            background-color: white !important;
        }
        .modal-backdrop {
            z-index: 39 !important;
        }
        .navbar-burger:hover {
            background-color: transparent;
        }
        .navbar-brand, .navbar-tabs{
            align-items: center;
            padding: 10px;
        }
        nav.navbar{
            box-shadow: rgba(0, 0, 0, 0.1) 0px -4px 15px -3px, rgba(0, 0, 0, 0.05) 0px 4px 6px -2px;
        }
        .footer {
            padding: 0rem 1.5rem 2rem;
        }
        body>nav.navbar .container {
            margin: 0;
        }
        @media (max-width: 600px) {
            a.navbar-item {
                padding: 0;
            }
            a.navbar-item>img{
                max-height: 32px !important;
            }
            body>nav.navbar {
                position: fixed;
                top: 0;
                width: 100%;
            }
            body>main{
                margin-top: 73px;
            }
            h1.titulo-pagina {
                margin-top: 103px !important;
            }
        }
        @media (max-width: 1025px) {
            .navbar-end>a.navbar-item{
                padding: 13px;
            }
            body>nav.navbar {
                display: block;
                padding: 0;
            }
            nav.navbar .navbar-brand{
                justify-content: space-between;
                width: 100%;
            }
            .navbar-end>a:first-child {
                border-top: 1px solid #ccc;
            }
        }
        @media (min-width: 1025px) {
            .contenedor-navbar{
                width: 100%;
                display: flex;
            }
        }
        footer {
        background-color: #203a74;
    }

    footer h6 {
        color: white;
    }

    footer ul>li {
        display: flex;
        gap: 4%;
        align-items: center;
        color: white;
    }

    #lista1Footer>li {
        padding: 5px;
        border-bottom: #ef7d1a8a 1px solid;
    }

    #lista2Footer>li {
        line-height: 1.75;
    }

    #lista3Footer>li {
        line-height: 2;
    }

    footer ul>li>a {
        color: white;
        text-decoration: none;
    }

    footer ul>li>a:hover {
        text-decoration: none;
        color: white;
    }

    footer>section:first-of-type{
        margin-bottom: 20px;
    }

    .imagenFooter {
        width: 100%;
    }

    #redes{
        gap: 5px;
    }

    #divLogos{
        align-self: center;
    }

    @media (max-width: 768px) {

        footer.p-5{
        padding: 3rem 0 3rem 0 !important;
        box-sizing: border-box;
    }

    footer>section>div{
        padding-right: 0;
    }

    #divNuestroHorario{
        background-color: white;
        padding: 10px 0 10px 0;
    }

    #divNuestroHorario>strong{
        color: #203a74 !important;
    }

    #divNuestroHorario>ul>li{
        color: #203a74;
    }

    #lista2Footer > li:nth-child(1) > strong, #lista2Footer > li:nth-child(3) > strong, #lista2Footer > li:nth-child(5) > a > span{
        color: #203a74 !important;
    }



    footer>section>div.col-md-3{
        padding-left: 0;
        padding-right: 0;
    }

    footer>section>div.col-md-2{
        padding-left: 0;
        padding-right: 0;
    }

    footer>section>div.col-xl-2{
        padding-left: 0;
        padding-right: 0;
    }

    footer>section{
        gap: 20px;
    }

    #divLogos{
        align-items: center !important;
    }
}
    </style>

    <title>Instalación - @yield('pagename')</title>
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
    <nav class="navbar" role="navigation" aria-label="main navigation">
        <div class="contenedor-navbar">
            <div class="navbar-brand">
                <a class="navbar-item" href="/{{ request()->slug_instalacion }}" style="padding: 10px">

                    @if (file_exists(public_path() . '/img/'.request()->slug_instalacion.'.png'))
                        <img src="{{ asset('img/'.request()->slug_instalacion.'.png') }}" style="max-height: 50px" />
                    @else
                        <img src="/img/tallerempresarial.png" style="max-height: 50px" />
                    @endif
                </a>

                <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false"
                    data-target="navbarBasicExample">
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                </a>
            </div>

            <div id="navbarBasicExample" class="navbar-menu">
                <div class="navbar-end">
                    <a href="/{{ request()->slug_instalacion }}" class="navbar-item {{request()->is('/') ? 'active' : '' }}"> Inicio </a>
                    {{-- <a href="" class="navbar-item"> Reservar </a> --}}
                    @if (App\Models\Instalacion::where('slug', request()->slug_instalacion)->first()->html_normas)
                        <a href="/{{ request()->slug_instalacion }}/normas" class="navbar-item"> Normas </a>
                    @endif
                    @if (\Auth::check())
                    @php
                        $pendientePago = check_pendientePago(auth()->user()->id);
                    @endphp
                        @if (auth()->user()->rol != 'admin')
                            <a href="/{{ request()->slug_instalacion }}/mis-reservas" class="navbar-item {{request()->is(request()->slug_instalacion . '/mis-reservas') ? 'active' : '' }}"><i class="fas fa-book-open mr-2"></i> Mis reservas @if($pendientePago > 0)<span class="ml-2 badge rounded-pill bg-warning">{!! $pendientePago !!}</span>@endif</a>
                            <a href="/{{ request()->slug_instalacion }}/perfil" class="navbar-item"><i class="fas fa-user mr-2"></i> Mi perfil </a>
                        @else
                            <a href="/{{ request()->slug_instalacion }}/perfil" class="navbar-item"><i class="fas fa-unlock-alt mr-2"></i> Administración </a>
                        @endif
                        <a href="/{{ request()->slug_instalacion }}/contacto" class="navbar-item"><i class="fa-solid fa-phone mr-2"></i> Contacto</a>
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="navbar-item">
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                            <i class="fas fa-power-off mr-2"></i> Cerrar sesión
                        </a>
                    @else
                    <a href="/{{ request()->slug_instalacion }}/contacto" class="navbar-item"><i class="fa-solid fa-phone mr-2"></i> Contacto</a>
                            <a href="{{ route('register_user_instalacion', ['slug_instalacion' => request()->slug_instalacion]) }}" class="navbar-item"><i class="fa-solid fa-user-plus mr-2"></i> Registrarse</a>
                        <a href="{{ route('login_instalacion', ['slug_instalacion' => request()->slug_instalacion]) }}" class="navbar-item"><i class="fas fa-sign-in-alt mr-2"></i> Acceder</a>
                    @endif
                </div>
            </div>
        </div>
    </nav>
    <main>
        @yield('content')
    </main>

    <footer class="p-5">
        <section class="row d-flex justify-content-center">

            <div class="col-md-2 col-xl-2 d-flex flex-column" id="divLogos">
                <div class="d-flex">
                    <!-- <img src="img/LOGO-versiones-02.png" id="logo" alt="logo"> -->
                    <a href="#top">
                        <img id="logo" srcset="/img/LOGO-DEF.png 500w,
                        /img/LOGO-versiones-02.png 1000w" sizes="(max-width: 499px) 500px,
                    1000px" alt="Logo">
                    </a>

                    <a href="#top">
                        <img src="/img/LOGO-versiones-03.png" id="logo2" alt="nombre">
                    </a>

                </div>
                {{-- <a href="https://tallerempresarial.es/fondo-europeo-de-desarrollo-regional/" target="_blank">
                    <img src="img/subvencion.png" alt="logo justificación taller" class="imagenFooter">
                </a> --}}
            </div>
            <div class="col-md-3 col-xl-2 d-flex flex-column align-items-center">
                <strong style="color: white;" id="cabeceraInformacion">Más información</strong>
                <ul style="list-style: none;" class="p-0" id="lista1Footer">
                    <li><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chevron-right"
                            width="22" height="22" viewBox="0 0 24 24" stroke-width="1.5" stroke="white" fill="none"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <polyline points="9 6 15 12 9 18" />
                        </svg>
                        <a href="/{{ request()->slug_instalacion }}/condiciones-generales" target="_blank">Condiciones generales</a>
                    </li>
                    <li><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chevron-right"
                            width="22" height="22" viewBox="0 0 24 24" stroke-width="1.5" stroke="white" fill="none"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <polyline points="9 6 15 12 9 18" />
                        </svg>
                        <a href="/{{ request()->slug_instalacion }}/privacidad" target="_blank">Política de privacidad</a>
                    </li>
                    <li><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chevron-right"
                            width="22" height="22" viewBox="0 0 24 24" stroke-width="1.5" stroke="white" fill="none"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <polyline points="9 6 15 12 9 18" />
                        </svg>
                        <a href="/{{ request()->slug_instalacion }}/terminos-condiciones"" target="_blank">Términos y condiciones</a>
                    </li>
                </ul>
            </div>
            <div class="col-md-2 col-xl-2 d-flex flex-column align-items-center" id="divNuestroHorario">
                <strong style="color: white;">Soporte técnico</strong>
                <ul class="p-0" style="list-style: none;" id="lista2Footer">
                    <li><strong style="color: white">Lunes a Jueves</strong></li>
                    <li>9:00 - 14:00 | 16:00 - 19:00</li>
                    <li><strong style="color: white">Viernes</strong></li>
                    <li>9:00 - 14:00</li>
                    <li><span style="color: rgb(10,206,133);">
                        <i class="fa fa-phone" aria-hidden="true"></i>
                        </span>
                        <a href="tel:675045062"><span>675 045 062</span></a>
                    </li>
                </ul>
            </div>

        </section>

        <section class="row text-center">
            <div class="col-md-12 d-flex justify-content-center align-items-center">
                <p class="p-0 m-0" style="color: white;">© Copyright 2015 - 2023 | Todos los derechos reservados | <a href="https://tallerempresarial.es/" target="_blank" style="color: white; text-decoration: none;">Taller Empresarial 2.0</a></p>
            </div>
        {{--   <div class="col-md-4 d-flex justify-content-center align-items-center" id="redes">
                <a href="https://www.instagram.com/tallerempresarial2.0/" target="_blank">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-instagram" width="22" height="22" viewBox="0 0 24 24" stroke-width="1.5" stroke="white" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <rect x="4" y="4" width="16" height="16" rx="4" />
                        <circle cx="12" cy="12" r="3" />
                        <line x1="16.5" y1="7.5" x2="16.5" y2="7.501" />
                    </svg>
                </a>
                <a href="https://www.facebook.com/tallerempresarial" target="_blank">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-facebook" width="22" height="22" viewBox="0 0 24 24" stroke-width="1.5" stroke="white" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M7 10v4h3v7h4v-7h3l1 -4h-4v-2a1 1 0 0 1 1 -1h3v-4h-3a5 5 0 0 0 -5 5v2h-3" />
                    </svg>
                </a>
                <a href="https://twitter.com/tallerempresa" target="_blank">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-twitter" width="22" height="22" viewBox="0 0 24 24" stroke-width="1.5" stroke="white" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M22 4.01c-1 .49 -1.98 .689 -3 .99c-1.121 -1.265 -2.783 -1.335 -4.38 -.737s-2.643 2.06 -2.62 3.737v1c-3.245 .083 -6.135 -1.395 -8 -4c0 0 -4.182 7.433 4 11c-1.872 1.247 -3.739 2.088 -6 2c3.308 1.803 6.913 2.423 10.034 1.517c3.58 -1.04 6.522 -3.723 7.651 -7.742a13.84 13.84 0 0 0 .497 -3.753c-.002 -.249 1.51 -2.772 1.818 -4.013z" />
                    </svg>
                </a>
                <a href="mailto:info@tallerempresarial.es" target="_blank">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-mail" width="22" height="22" viewBox="0 0 24 24" stroke-width="1.5" stroke="white" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <rect x="3" y="5" width="18" height="14" rx="2" />
                        <polyline points="3 7 12 13 21 7" />
                    </svg>
                </a>
            </div> --}}
        </section>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script>
        // Hamburger menu functionality
        document.addEventListener('DOMContentLoaded', () => {
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
