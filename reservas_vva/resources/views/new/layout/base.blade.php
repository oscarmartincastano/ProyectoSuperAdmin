<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Bulma Version 0.9.0-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/pickadate.js/3.5.6/themes/default.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/pickadate.js/3.5.6/themes/default.date.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href='@yield("linkCss")'>
    @if (file_exists(public_path() . '/img/'.request()->slug_instalacion.'.png'))
    <link rel="icon" type="image/png" href="{{ asset('img/'.request()->slug_instalacion.'.png') }}">
    @else
    <link rel="icon" type="image/png" href="/img/tallerempresarial.png{{-- {{ asset('img/'.request()->slug_instalacion.'.png') }} --}}">
    @endif
    
    <link  href="https://code.jquery.com/ui/1.12.1/themes/ui-lightness/jquery-ui.css"  rel="stylesheet"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js" integrity="sha512-6UofPqm0QupIL0kzS/UIzekR73/luZdC6i/kXDbWnLOJoqwklBK6519iUnShaYceJ0y4FaiPtX/hRnV/X/xlUQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js" integrity="sha384-ODmDIVzN+pFdexxHEHFBQH3/9/vQ9uori45z4JjnFsRydbmQbmL5t1tQ0culUzyK" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script  src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"  integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA=="  crossorigin="anonymous"  referrerpolicy="no-referrer"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.colVis.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    @yield('link-script')
    <title>@yield('name') - {{ $instalacion->nombre }}</title>
    <style>
        .separador-linea{
            height: 1px;
            width: 100%;
            background-color: #cfd3d6;
            position: absolute;
            left: 0;
        }
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: rgb(250,250,250);
            height: 80px;
            padding: 16px 16px 16px 32px;
        }
        main {
            background: #fafafa;
        }
        .titulo-instalacion {
            position: relative !important;
            background-color: #0e2433 !important;
            background-repeat: no-repeat !important;
            background-position: 50% !important;
            background-size: 100% auto !important;
        }

        
        .contenido-titulo {
            margin: 0 auto;
            padding: 32px 0 40px;
            color: white;
            text-shadow: 1px 1px 1px rgb(0 0 0 / 50%);
        }
        .contenido-titulo h1{
            font-size: 64px;
            font-weight: 600;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        .contenido-titulo .direccion{
            font-size: 16px;
            line-height: 1.4;
            text-indent: 4px;
        }
        .shadow-box {
            box-shadow: -1px 5px 17px 0 rgb(0 0 0 / 10%);
        }
        .card {
            background-color: white !important;
        }
        .contenido-principal {
            padding: 50px 0;
        }
        .filtros {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            border-bottom: 1px solid #f2f2f2;
        }
        .filtros .form-control {
            border: 0 !important;
            border-right: 1px solid #f2f2f2 !important;
            font-weight: bold;
        }
        .pistas-horario .celda {
            text-align: center !important;
            font-size: 1em;
            padding: 0 0 0 0px;
            font-weight: bold;
        }
        .tabla-horario {
            display: flex;
            position: relative;
        }

        .tabla-horario .celda{
            text-align: center;
        }
        .horas-tramos {
            pointer-events: none;
            color: rgba(1,1,1,.4);
            display: flex;
        }
        .celda {
            border: 1px solid #f2f2f2;
        }
        .pistas-horario {
            min-width: 160px;
        }
        .pistas-horario .celda{
            text-align: left;
        }
        .horas-tramos-pistas{
            position: relative;
        }
        .horas-tramos-pistas>.slots-pista {
            position: relative;
        }
        .slots-pista{
            height: 40px;
            position: relative;
        }
        .slots-horas{
            display: flex;
        }
        .slots-pista .slot {
            padding: 1px;
        }
        .slots-pista .slot div{
            height: 100%;
            border-radius: 5px;
            background-color: #198754;
            cursor: pointer;
        }
        .slot-reserva > div:hover {
            background-color: #3fbf83;
        }
        .block-before {
            border-right: 2px solid #198754;
            position: absolute;
            bottom: 0;
            background-color: #f9f9f9;
            background-image: linear-gradient(45deg,transparent 25%,#fff 0,#fff 50%,transparent 0,transparent 75%,#fff 0,#fff);
            z-index: 800;
        }
        .tramos{
            position: relative;
        }
        .select2-container--default .select2-selection--single {
            border: 0 !important;
            font-weight: bold;
        }
        .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
            background: #dddddd;
            color: #212529;
        }
        .select2-results__option--selectable{
            font-weight: 500;
        }
        .select2-container--default .select2-results>.select2-results__options {
            max-height: unset;
            border-radius: 0 0 3px 3px;
            border: 1px solid #f2f2f2;
            box-shadow: 0 24px 32px 0 rgb(1 1 1 / 5%);
        }
        .select2-dropdown {
            border: 0;
        }
        .tooltip-inner {
            white-space:pre-wrap;
            padding: 10px;
        }
        .btn-no-disponible {
            font-size: 0.7em;
            background: grey !important;
            cursor: auto !important;
        }

        .btn-reservado {
            font-size: 0.7em;
            background: #8f0d1a !important;
            cursor: auto !important;
        }


        .btn-no-disponible > a {

        text-decoration: none !important;
        }

        .page-header a {
            text-decoration: none;
        }

        #ver-mas-zonas{
            display: none;
        }

        #ver-zonas-anteriores{
            display: none;
        }

    #logo {
    width: 80px;
    margin-left: 2%;
    }

    #logo2 {
        width: 160px;
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

@media(max-width:500px){
    #logo2{
        display: none;
    }

    #divLogos{
        align-items: center !important;
    }
}

        /* .card-reserva {
            max-width: 860px;
        } */
        /* .select-fecha, #alt-select-fecha {
            max-width: 195px;
        } */
        @media(max-width: 992px) {

            /* #ver-mas-zonas{
                display: block;
                position: absolute;
                background-color: #4e8857;
                border: 1px solid white;
                border-radius: 50px;
                padding: 1%;
                display: flex;
                align-items: center;
                left: 96%;
                top: 5%;
            }

            #ver-zonas-anteriores{
                display: block;
                position: absolute;
                background-color: #4e8857;
                border: 1px solid white;
                border-radius: 50px;
                padding: 1%;
                display: flex;
                align-items: center;
                left: 0.5%;
                top: 5%;
            } */

            .pistas-horario .celda {
                text-align: center !important;
                font-size: 0.7em;}

            .pistas-horario {
                min-width: 20%;
            }
            /* borrar */
            /* .pistas-horario {
                background: lightgray;
                display: flex !important;
                position: relative;
            } */

            /* borrar */
            /* .tabla-horario {
                display: block !important;
            } */

            /* borrar */
            /* .tramos {
                display: flex !important;
            } */

            /* borrar */
            /* .horas-tramos {
                display: block !important;
            } */

            /* borrar */
            /* .horas-tramos-pistas {
                display: flex !important;
                width: 100% !important;
            } */

            /* borrar */
            /* .horas-tramos-pistas>div, .horas-tramos-pistas>div>div, .pistas-horario .celda:not(:first-child) {
                width: 100% !important;
            } */

            /* borrar */
            /* .horas-tramos-pistas .celda {
                height:40px !important;
                width: 100% !important;
            } */
            .block-before{
                display: none !important;
            }

            /* borrar */
            /* .slots-horas {
                display: block
            } */
            .pistas-horario .celda:first-child {
                padding: 19px;
            }
            h1 {
                font-size: 2rem !important;
            }
            .contenido-titulo {
                padding: 16px;
            }
            .card-reserva {
                width: auto !important;
            }
            .page-header>div {
                display: none;
            }
            .filtros>div {
                text-align: center;
                width: 100%
            }
            .show-responsive {
                display: block !important;
            }
            .slot-reserva a {
                display: flex !important;
                justify-content: center;
                align-items: center;
            }
            .contenido-principal {
                padding: 50px 15px;
            }
        }




        .show-responsive {
            display: none;
            color: white;
        }
        .leyenda {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding: 8px;
        }
        .leyenda div {
            color: rgba(1,1,1,.4);
            display: flex;
            align-items: center;
            margin-left: 16px;
            cursor: default;
        }
        .leyenda div::before{
            content: "";
            display: block;
            width: 12px;
            height: 12px;
            border: 1px solid #f2f2f2;
            margin-right: 8px;
            border-radius: 4px;
        }
        .disponible::before{
            background-color: #198754;
        }
        .reservada::before{
            background-color: #8f0d1a;
        }
        .no-disponible::before{
              background-color: grey;
          }
        .list-tags {
            list-style: none;
            padding: 0;
            display: flex;
            flex-wrap: wrap;
        }
        .list-tags li {
            display: flex;
            align-items: center;
            margin: 4px;
            padding: 0 8px;
            height: 28px;
            line-height: 28px;
            font-size: 12px;
            font-weight: 500;
            background-color: #e7e9eb;
            border-radius: 14px;
            white-space: nowrap;
            color: #000;
            border: 0;
            cursor: default;
        }
        .group-horario li {
            display: flex;
            justify-content: space-between;
        }
        .group-horario li>div {
            font-weight: 500;
        }
        .card-info p {
            margin-bottom: 0.25rem;
        }
        #ui-datepicker-div {
            z-index: 1000 !important;
            background: white !important;
            padding: 12px;
        }
        #ui-datepicker-div * {
            background: white;
            font-family: Arbeit,sans-serif;
        }
        .ui-datepicker-header {
            border: 0;
        }
        .ui-datepicker-title {
            color: black;
        }
        .ui-datepicker-calendar thead tr {
            font-size: 14px;
            font-weight: 400;
            color: rgba(1,1,1,.6);
            border-bottom: 1px solid rgba(1,1,1,.1);
        }
        body{
            overflow-x: hidden;
        }
        .ui-datepicker-calendar tbody tr td>*{
            border: 0 !important;
            font-size: 16px;
            width: 100%;
            height: 100%;
            border-radius: 40px;
            color: rgb(47,51,51);
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        td .ui-state-active {
            color: #fff !important;
            background-color: #335fff !important;
        }
        .ui-datepicker-calendar tbody tr td{
            height: 40px;
            width: 44px;
        }
        .ui-state-default, .ui-widget-content .ui-state-default, .ui-widget-header .ui-state-default, .ui-button, html .ui-button.ui-state-disabled:hover, html .ui-button.ui-state-disabled:active {
            color: rgb(47,51,51);
            font-weight: 500;
        }
        .ui-datepicker-next-hover {
            border: 0;
        }
        .ui-icon, .ui-widget-content .ui-icon {
            background-image: url(https://code.jquery.com/ui/1.12.1/themes/ui-lightness/images/ui-icons_222222_256x240.png) !important;
        }
        [data-handler="next"] span {
            transform: rotate(90deg);
        }
        .ui-datepicker-prev span {
            transform: rotate(-90deg);
        }
        .ui-widget-header .ui-corner-all {
            border-radius: 45px;
            border: 1px solid #335fff;
        }
        .select-fecha {
            cursor: pointer;
            position: relative;
        }
        .select-fecha::-webkit-calendar-picker-indicator {
            display: none;
            -webkit-appearance: none;
        }
        .select-fecha:focus::before {

        }

        .select-fecha::before, .div-alt-select-fecha::before {
            content: "";
            border-color: #0d6efd transparent transparent transparent;
            border-style: solid;
            border-width: 5px 4px 0 4px;
            height: 0;
            left: 88%;
            margin-left: -4px;
            margin-top: -2px;
            position: absolute;
            top: 50%;
            width: 0;
            transition: all 0.4s;
            z-index: 201;
        }

        body > main > div > div > div.col-md-9 > div.card.shadow-box.card-reserva.mb-4 > div.filtros.p-0.d-flex > div:nth-child(1) > span > span.selection > span > span.select2-selection__arrow > b{
            border-color:#0d6efd transparent transparent transparent !important;
        }
        .select-fecha:focus::before {
            transform: rotate(180deg);
        }
        .loader-horario {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100%;
            position: absolute;
            width: 100%;
            background: white;
            z-index: 800000;
        }
        .loader-horario {
            font-size: 18px;
            font-weight: 500;
            line-height: 1.2;
        }
        header .page-header a.px-3 {
            color: rgb(47,51,51);
            font-weight: 500;
        }
        .page-header {
            background: white;
        }
        header .page-header {
            box-shadow: 0 8px 24px 0 rgb(47 51 51 / 10%);
        }
        .fecha_inicio_evento {
            font-size: 28px;
            font-weight: 600;
            padding-bottom: 10px;
            border-bottom: solid 1px #eaeaea;
        }
        .num-dia-evento {
            color: #335fff;
        }
        .titulo-evento {
            text-transform: uppercase;
            font-weight: 600;
            font-size: 17px;
            margin-bottom: 4px;
        }
        .cierre-inscrp {
            font-size: 14px;
        }
        .item-evento {
            border: solid 1px #eaeaea;
            border-left-color: #6EDE29;
            margin-bottom: 3px;
        }
        .contenido-evento {
            padding: 10px 0;
        }
        .post-header {
            display: flex;
            justify-content: center;
            position: -webkit-sticky;
            position: sticky;
            top: 0;
            box-shadow: 0 8px 24px 0 rgb(47 51 51 / 10%);
            z-index: 10;
            background-color: #fff;
            border-top: 1px solid rgba(1,1,1,.05);
        }
        .menu-header {
            display: flex;
            align-items: center;
            font-size: 16px;
            flex-wrap: wrap;
            padding: 10px;
            gap: 20px;
            justify-content: center;
        }
        .menu-header a {
            display: block;
            padding: 1em 0;
            line-height: 1em;
            border-bottom: 2px solid transparent;
            text-decoration: none;
            opacity: .6;
            transition: all .15s;
            position: relative;
            color: black;
        }
        .menu-header a.active {
            opacity: 1;
            border-bottom-color: #335fff;
        }

        @media (max-width: 650px) {
            .fecha_inicio_evento{
                display: block !important;
            }
            .menu-main-header {
                position: absolute;
                top: 79px;
                background: white;
                z-index: 200;
                width: 100%;
                left: 0;
                z-index: 210;
            }
            .menu-main-header>div {
                flex-direction: column;
                width: 100%;
            }
            .menu-main-header .px-3 {
                padding-left: 2rem !important;
                padding-top: 1rem;
                padding-bottom: 1rem;
                border: 1px solid #dee2e6!important;
                opacity: 1;
            }
            .navbar-burger {
                display: block !important;
            }
        }
        .navbar-burger {
            color: black;
            font-size: 20px;
            display: none;
        }
        .div-hoy {
            cursor: pointer;
            background: white;
            font-weight: bold;
            position: absolute;
            z-index: 200;
            width: 112px;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            padding-left: 14px;
        }
        #alt-select-fecha {
            cursor: pointer;
            background: white;
            font-weight: bold;
            position: absolute;
            z-index: 199;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            padding-left: 14px;
        }
        .form-control:disabled, .form-control[readonly]{
            background: white !important;
        }
        .select2-container--default {
            min-width: 132px;
        }
        .info-instalacion {
            max-height: 200px;
            overflow: hidden;
            transition: max-height .55s;
            font-size: 14px;
        }
        .toggle-ver-mas {
            display: block;
            padding: 5px 16px;
            font-size: 16px;
            color: #335fff;
            cursor: pointer;
            -webkit-transform: translateY(-50%);
            transform: translateY(-50%);
            margin: 0 auto;
            background-color: #fff;
            border: 0;
            border-radius: 32px;
            border: 1px solid #335fff;
        }
        .info-instalacion:not(.no-max-height)::after {
            content: "";
            display: block;
            position: -webkit-sticky;
            position: sticky;
            bottom: 0;
            height: 40px;
            background: -webkit-gradient(linear,left bottom,left top,from(hsla(0,0%,100%,.8)),to(hsla(0,0%,100%,0)));
            background: -webkit-linear-gradient(bottom,hsla(0,0%,100%,.8),hsla(0,0%,100%,0));
            background: linear-gradient(0deg,hsla(0,0%,100%,.8) 0,hsla(0,0%,100%,0));
        }
        .no-max-height {
            max-height: 2000px;
            margin-bottom: 25px;
            transition: max-height .55s;
        }
        .galeria-intalacion input[type=radio] {
            display: none;
        }
        .galeria-intalacion .card {
        position: absolute;
        width: 60%;
        height: 100%;
        left: 0;
        right: 0;
        margin: auto;
        transition: transform 0.4s ease;
        cursor: pointer;
        }

        .galeria-intalacion .container {
            width: 100%;
            max-width: 800px;
            max-height: 600px;
            height: 100%;
            transform-style: preserve-3d;
            display: flex;
            justify-content: center;
            flex-direction: column;
            align-items: center;
        }

        .galeria-intalacion .cards {
            position: relative;
            width: 100%;
            height: 100%;
            margin-bottom: 20px;
        }

        .galeria-intalacion img {
            width: 100%;
            height: 100%;
            border-radius: 10px;
            -o-object-fit: cover;
                object-fit: cover;
        }

        #item-1:checked ~ .cards #song-3, #item-2:checked ~ .cards #song-1, #item-3:checked ~ .cards #song-2 {
            transform: translatex(-40%) scale(0.8);
            opacity: 0.4;
            z-index: 0;
        }

        #item-1:checked ~ .cards #song-2, #item-2:checked ~ .cards #song-3, #item-3:checked ~ .cards #song-1 {
            transform: translatex(40%) scale(0.8);
            opacity: 0.4;
            z-index: 0;
        }

        #item-1:checked ~ .cards #song-1, #item-2:checked ~ .cards #song-2, #item-3:checked ~ .cards #song-3 {
            transform: translatex(0) scale(1);
            opacity: 1;
            z-index: 1;
        }
        #item-1:checked ~ .cards #song-1 img, #item-2:checked ~ .cards #song-2 img, #item-3:checked ~ .cards #song-3 img {
            box-shadow: 0px 0px 5px 0px rgba(81, 81, 81, 0.47);
        }
        /* .galeria-intalacion{
            height: 350px;
        } */
        @media(max-width:1275px){
            .app-desc{
                display: none !important;
            }

            .menu-app{
                display: block !important;
            }
        }
    </style>
    @yield('style')
</head>
<body>
    <header>
        <div class="page-header">
            <a href="/{{ request()->slug_instalacion }}">
                @if (file_exists(public_path() . '/img/'.request()->slug_instalacion.'.png'))
                <img src="{{ asset('img/'.request()->slug_instalacion.'.png') }}" style="max-height: 50px">
                @else
                <img src="/img/tallerempresarial.png{{-- {{ asset('img/'.request()->slug_instalacion.'.png') }} --}}" style="max-height: 50px">
                @endif
            </a>
            <a role="button" class="navbar-burger menu-app" aria-label="menu" aria-expanded="false"
                data-target="navbarBasicExample">
                <i class="fas fa-bars"></i>
            </a>
            <div class="menu-main-header">
                <div class="d-flex">
                    <a href="/{{ request()->slug_instalacion }}" class="px-3"> Inicio </a>
                    {{-- <a href="" class="px-3"> Reservar </a> --}}
                    {{-- @if (App\Models\Instalacion::where('slug', request()->slug_instalacion)->first()->html_normas)
                        <a href="/{{ request()->slug_instalacion }}/normas" class="px-3"> Normas </a>
                    @endif --}}
                    @if (\Auth::check())
                    @php
                        $pendientePago = check_pendientePago(auth()->user()->id);
                    @endphp
                        @if (auth()->user()->rol != 'admin' and request()->slug_instalacion != 'feria-jamon-villanuevadecordoba')
                            <a href="/{{ request()->slug_instalacion }}/mis-reservas" class="px-3 {{request()->is(request()->slug_instalacion . '/mis-reservas') ? 'active' : '' }}"><i class="fas fa-book-open mr-2"></i>
                                @if ($instalacion->finalidad_eventos == FINALIDAD_ENTRADA)
                                Mis eventos
                                @else
                                Mis reservas    
                                @endif
                                
                                @if($pendientePago > 0)<span class="ml-2 badge rounded-pill bg-warning">{!! $pendientePago !!}</span>@endif</a>
                            <a href="/{{ request()->slug_instalacion }}/perfil" class="px-3"><i class="fas fa-user mr-2"></i> Mi perfil </a>
                        @elseif(auth()->user()->rol != 'admin' and request()->slug_instalacion == 'feria-jamon-villanuevadecordoba')
                        <a href="/{{ request()->slug_instalacion }}/new/mis-eventos" class="px-3 {{request()->is(request()->slug_instalacion . '/new/mis-eventos') ? 'active' : '' }}"><i class="fas fa-book-open mr-2"></i>
                            @if ($instalacion->finalidad_eventos == FINALIDAD_ENTRADA)
                            Mis eventos
                            @else
                            Mis reservas    
                            @endif
                            
                            @if($pendientePago > 0)<span class="ml-2 badge rounded-pill bg-warning">{!! $pendientePago !!}</span>@endif</a>
                        <a href="/{{ request()->slug_instalacion }}/perfil" class="px-3"><i class="fas fa-user mr-2"></i> Mi perfil </a>
                        @else
                            <a href="/{{ request()->slug_instalacion }}/perfil" class="px-3"><i class="fas fa-unlock-alt mr-2"></i> Administración </a>
                        @endif
                        <a href="/{{ request()->slug_instalacion }}/anuncios" class="px-3"><i class="fa-regular fa-newspaper mr-2"></i> Anuncios </a>
                        <a href="/{{ request()->slug_instalacion }}/contacto" class="px-3"><i class="fa-solid fa-phone mr-2"></i> Contacto</a>
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="px-3">
                            <form id="logout-form" action="{{ route('logout', ['slug_instalacion' => request()->slug_instalacion]) }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                            <i class="fas fa-power-off mr-2"></i> Cerrar sesión
                        </a>
                    @else
                    <a href="/{{ request()->slug_instalacion }}/noticias" class="px-3"><i class="fa-regular fa-newspaper mr-2"></i> Anuncios </a>
                    <a href="/{{ request()->slug_instalacion }}/contacto" class="px-3"><i class="fa-solid fa-phone mr-2"></i> Contacto</a>
                    <a href="{{ route('register_user_instalacion', ['slug_instalacion' => request()->slug_instalacion]) }}" class="px-3" style="display: block"><i class="fa-solid fa-user-plus mr-2"></i>Registrarse</a>
                    <a href="{{ route('login_instalacion', ['slug_instalacion' => request()->slug_instalacion]) }}" class="px-3"><i class="fas fa-sign-in-alt mr-2"></i> Acceder</a>
                    @endif
                </div>
            </div>
        </div>
        @yield('post-header')
    </header>
    <main>
        @if(session()->has('error'))
            <div class="row msg-div-error m-0">
                <div class="col-12 p-0">
                    <div class="card">
                        <div class="card-body bg-danger">
                            <p class="mb-0 text-center text-white">{{ session()->get('error') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if(session()->has('mensaje'))
            <div class="row msg-div-error m-0">
                <div class="col-12 p-0">
                    <div class="card">
                        <div class="card-body bg-primary">
                            <p class="mb-0 text-center text-white">{{ session()->get('mensaje') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if(session()->has('message'))
            <div class="row msg-div-error m-0">
                <div class="col-12 p-0">
                    <div class="card">
                        <div class="card-body bg-success">
                            <p class="mb-0 text-center text-white">{{ session()->get('message') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @yield('titulo')

        <div class="contenido-principal container">
            @yield('content')
                @if(request()->slug_instalacion == "la-guijarrosa" && auth()->user())
                    @php
                        $servicio_usuario = $servicios_contratados = \App\Models\Servicio_Usuario::where('id_usuario', auth()->user()->id)->get();
                    @endphp
                    <div class="mt-3" style="position: fixed;bottom: 20px;right: 20px; display: flex; flex-direction: column; gap: 10px; z-index: 999">
                        {{-- @foreach ($servicio_usuario as $item)
                            @if ($item->activo == 'si' && count($item->recibos_sin_pago)==0)
                                <a href="#" class="btn btn-danger btn-dar-baja" data-toggle="modal" data-target="#confirmacion-modal" data-id="{{ $item->id }}">Dar de baja {{ $item->servicio->nombre }}</a>
                            @endif
                        @endforeach --}}
                    </div>
                    <div class="modal fade" id="confirmacion-modal" tabindex="-1" role="dialog" aria-labelledby="confirmacionBajaModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="confirmacionBajaModalLabel">Confirmación de baja</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="cerrar-modal-btn">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    ¿Está seguro de que desea dar de baja este servicio?
                                </div>
                                <div class="modal-footer">
                                    <a href="" class="btn btn-danger" id="aceptar">Aceptar</a>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal" id="cancelar-baja-btn">Cancelar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
        </div>
        @php $msg_difusion = \App\Models\Mensajes_difusion::where([['id_instalacion', $instalacion->id], ['fecha_inicio', '<=', date('Y-m-d')], ['fecha_fin', '>=', date('Y-m-d')]])->orderByDesc('created_at')->first(); @endphp
        @if (auth()->user() && $msg_difusion && Cookie::get('modal_difusion_'.$msg_difusion->id) == null)
        @php Cookie::queue(Cookie::make('modal_difusion_'.$msg_difusion->id, 'true', 10000000)) @endphp
            <div class="modal fade in modal_auto_open p-0" id="modal-mensaje-difusion" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content m-0" style="max-height: calc(100vh - 17px);">
                        <div class="modal-header">
                        <h4 class="h4 mb-0">{{ $msg_difusion->titulo }}</h4>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>
                        <div class="modal-body p-4">
                            {!! $msg_difusion->contenido !!}
                        </div>
                        <div class="modal-footer">
                            <p class="text-center mt-2"><button type="button"  data-bs-dismiss="modal" class="btn btn-secondary">Entendido</button></p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </main>
    @if(request()->slug_instalacion == 'vvadecordoba')
    <div class="container app-desc">
        <h2 class="title text-center titulo-pagina" style="margin-top: 30px !important;">Descargar App</h2>
        <div class="divider mb-3" style="padding: 5px !important;">
            <div></div>
        </div>
        <div class="content has-text-centered">
            <p style="font-size: 20px !important;">1. Descarga la aplicación para el dispositivo deseado:</p>
            <div class="d-flex flex-row justify-content-center">
                <div>
                    <a href="https://play.google.com/store/apps/details?id=com.taller.gestincomunidad&hl=en_US&gl=ES"><img src="{{ asset('img/google-play.png') }}"></a>
                    <a href="https://apps.apple.com/es/app/gesti%C3%B3n-comunidades/id1519070429"><img src="{{ asset('img/app-store.png') }}"></a>
                </div>
            </div>
            <p style="font-size: 20px !important;" class="mt-3">2. Introduce el código y pulsa en Entrar: <strong style="color: red">310541</strong></p>
            <p style="font-size: 20px !important;">3. ¡Haz tu reserva!</p>
        </div>
    </div>
    @endif
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
    {{-- <footer class="footer mt-5" style="background:#52b5f7 !important; box-shadow:rgb(0 0 0 / 17%) 0px -10px 15px -3px, rgb(0 0 0 / 5%) 0px 4px 6px -2px; padding: 0rem 1.5rem 0rem;" >
        <div  class="d-flex justify-content-center"> <img src="/img/tarjetas.png" style="max-height: 50px" /></div>
        <div class="d-flex justify-content-center">
            <p style="color:white; font-size: 20px !important;"><a href="/{{ request()->slug_instalacion }}/condiciones-generales" style="color: white;">Condiciones generales</a> | <a href="/{{ request()->slug_instalacion }}/privacidad" style="color: white;">Política de privacidad</a> | <a href="/{{ request()->slug_instalacion }}/terminos-condiciones" style="color: white;">Términos y condiciones</a></p>
        </div>
    </footer> --}}
    <script>
        $.datepicker.regional['es'] = {
            closeText: 'Cerrar',
            prevText: '< Ant',
            nextText: 'Sig >',
            currentText: 'Hoy',
            monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
            dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
            dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
            dayNamesMin: ['D','L','M','X','J','V','S'],
            weekHeader: 'Sm',
            dateFormat: 'dd/mm/yy',
            firstDay: 1,
            isRTL: false,
            showMonthAfterYear: false,
            yearSuffix: ''
        };
        var old_goToToday = $.datepicker._gotoToday;
        $.datepicker._gotoToday = function(id) {
            old_goToToday.call(this,id);
            this._selectDate(id);
            $('input[type="date"]').blur();
        }
        $.datepicker.setDefaults($.datepicker.regional['es']);
        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
        })
        $(document).ready(function () {
            $('.select2').select2({
                minimumResultsForSearch: -1
            });

            $('#modal-mensaje-difusion').modal('show');

            $('.btn-dar-baja').click(function (e) {
                let slug= "la-guijarrosa";
                $('#aceptar').attr('href','/'+slug+'/new/mis-servicios/'+$(this).data('id')+'/baja')
                $('#confirmacion-modal').modal('show');
            });

            $('#cancelar-baja-btn, #cerrar-modal-btn').click(function() {
                $('#confirmacion-modal').modal('hide');
            });

            $('.navbar-burger').click(function (e) {
                e.preventDefault();
                $(this).next().toggle();
            });
        });
    </script>
    @yield('script')
</body>
</html>
