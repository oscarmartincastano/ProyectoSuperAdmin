<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from pages.revox.io/dashboard/latest/html/simply_white/index.html /3.x [XR&CO'2014], Tue, 19 Jan 2021 11:45:32 GMT -->

<head>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    <meta charset="utf-8" />
    <title>{{ auth()->user()->instalacion->nombre ?? 'Panel de administración' }}</title>
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no" />
    <link rel="apple-touch-icon" href="/simply_white/pages/ico/60.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/simply_white/pages/ico/76.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/simply_white/pages/ico/120.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/simply_white/pages/ico/152.png">
    <link rel="icon" type="image/x-icon" href="favicon.ico" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="Meet pages - The simplest and fastest way to build web UI for your dashboard or app."
        name="description" />
    <meta content="Ace" name="author" />
    <link href="/simply_white/assets/plugins/pace/pace-theme-flash.css" rel="stylesheet" type="text/css" />
    <link href="/simply_white/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="/simply_white/assets/plugins/jquery-scrollbar/jquery.scrollbar.css" rel="stylesheet" type="text/css"
        media="screen" />
    <link href="/simply_white/assets/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css"
        media="screen" />
    <link href="/simply_white/assets/plugins/nvd3/nv.d3.min.css" rel="stylesheet" type="text/css" media="screen" />
    <link href="/simply_white/assets/plugins/rickshaw/rickshaw.min.css" rel="stylesheet" type="text/css" />
    <link href="/simply_white/assets/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet" type="text/css"
        media="screen">
    <link href="/simply_white/assets/plugins/mapplic/css/mapplic.css" rel="stylesheet" type="text/css" />
    <link href="/simply_white/assets/css/dashboard.widgets.css" rel="stylesheet" type="text/css" media="screen" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
        integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="/simply_white/assets/plugins/jquery-datatable/media/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="/simply_white/assets/plugins/jquery-datatable/extensions/FixedColumns/css/dataTables.fixedColumns.min.css" rel="stylesheet" type="text/css" />
    <link href="/simply_white/assets/plugins/datatables-responsive/css/datatables.responsive.css" rel="stylesheet" type="text/css" media="screen" />
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.0/css/responsive.dataTables.min.css">

    <link class="main-stylesheet" href="/simply_white/pages/css/themes/light.css?v=1.0.1" rel="stylesheet" type="text/css" />
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <style>
        .mark {
            border-radius: 20px;
            border: 2px solid #FFF;
            width: 24px;
            height: 22px;
            background-color: #FF6B6B;
            position: absolute;
            top: 4px;
            left: 82px;
            font-size: 10px;
            line-height: 15px;
            font-family: 'Roboto', sans-serif;
            /* font-weight: 400; */
            color: #FFF;
            font-weight: 700;
            text-align: center;
        }

        .span-users{
            position: relative;
        }
        .alert {
			position: absolute;
    		top: 20px;
    		z-index: 2000;
    		right: 0;
		}
		.alert-success {
			background-color: #a1e991;
    		color: #013700;
		}
        .links span{
            display: none;
        }
        .btn-outline-warning, .btn-outline-warning:focus{
            color: #cf9c12;
            border: 1px solid #cf9c12;
            background: transparent;
        }
    </style>
    @yield('style')
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

<body class="fixed-header dashboard menu-pin">
    <!-- BEGIN SIDEBPANEL-->
    @yield('content-app')
    <!-- END OVERLAY -->
    <!-- BEGIN VENDOR JS -->
    <script src="/simply_white/assets/plugins/feather-icons/feather.min.js" type="text/javascript"></script>
    <script src="/simply_white/assets/plugins/pace/pace.min.js" type="text/javascript"></script>
    <!--  A polyfill for browsers that don't support ligatures: remove liga.js if not needed-->
    <script src="/simply_white/assets/plugins/liga.js" type="text/javascript"></script>
    <script src="/simply_white/assets/plugins/jquery/jquery-3.2.1.min.js" type="text/javascript"></script>
    <script src="/simply_white/assets/plugins/modernizr.custom.js" type="text/javascript"></script>
    <script src="/simply_white/assets/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
    <script src="/simply_white/assets/plugins/popper/umd/popper.min.js" type="text/javascript"></script>
    <script src="/simply_white/assets/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="/simply_white/assets/plugins/jquery/jquery-easy.js" type="text/javascript"></script>
    <script src="/simply_white/assets/plugins/jquery-unveil/jquery.unveil.min.js" type="text/javascript"></script>
    <script src="/simply_white/assets/plugins/jquery-ios-list/jquery.ioslist.min.js" type="text/javascript"></script>
    <script src="/simply_white/assets/plugins/jquery-actual/jquery.actual.min.js"></script>
    <script src="/simply_white/assets/plugins/jquery-scrollbar/jquery.scrollbar.min.js"></script>
    <script type="text/javascript" src="/simply_white/assets/plugins/select2/js/select2.full.min.js"></script>
    <script type="text/javascript" src="/simply_white/assets/plugins/classie/classie.js"></script>
    <script src="/simply_white/assets/plugins/nvd3/lib/d3.v3.js" type="text/javascript"></script>
    <script src="/simply_white/assets/plugins/nvd3/nv.d3.min.js" type="text/javascript"></script>
    <script src="/simply_white/assets/plugins/nvd3/src/utils.js" type="text/javascript"></script>
    <script src="/simply_white/assets/plugins/nvd3/src/tooltip.js" type="text/javascript"></script>
    <script src="/simply_white/assets/plugins/nvd3/src/interactiveLayer.js" type="text/javascript"></script>
    <script src="/simply_white/assets/plugins/nvd3/src/models/axis.js" type="text/javascript"></script>
    <script src="/simply_white/assets/plugins/nvd3/src/models/line.js" type="text/javascript"></script>
    <script src="/simply_white/assets/plugins/nvd3/src/models/lineWithFocusChart.js" type="text/javascript"></script>
    <script src="/simply_white/assets/plugins/rickshaw/rickshaw.min.js"></script>
    <script src="/simply_white/assets/plugins/mapplic/js/hammer.min.js"></script>
    <script src="/simply_white/assets/plugins/mapplic/js/jquery.mousewheel.js"></script>
    <script src="/simply_white/assets/plugins/mapplic/js/mapplic.js"></script>
    <script src="/simply_white/assets/js/dashboard.js" type="text/javascript"></script>
    <!-- END VENDOR JS -->
    <!-- BEGIN CORE TEMPLATE JS -->
    <!-- BEGIN CORE TEMPLATE JS -->
    <script src="/simply_white/pages/js/pages.js"></script>
    <!-- END CORE TEMPLATE JS -->
    <!-- BEGIN PAGE LEVEL JS -->
    <script src="/simply_white/assets/js/scripts.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL JS -->
    <!-- END CORE TEMPLATE JS -->
    <!-- BEGIN PAGE LEVEL JS -->
    <script src="/simply_white/assets/js/dashboard.js" type="text/javascript"></script>
    <script src="/simply_white/assets/js/scripts.js" type="text/javascript"></script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="/simply_white/assets/plugins/jquery-datatable/media/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js"></script>
    <script src="/simply_white/assets/plugins/jquery-datatable/extensions/TableTools/js/dataTables.tableTools.min.js" type="text/javascript"></script>
    <script src="/simply_white/assets/plugins/jquery-datatable/media/js/dataTables.bootstrap.js" type="text/javascript"></script>
    <script src="/simply_white/assets/plugins/jquery-datatable/extensions/Bootstrap/jquery-datatable-bootstrap.js" type="text/javascript"></script>
    <script type="text/javascript" src="/simply_white/assets/plugins/datatables-responsive/js/lodash.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

    <!-- END PAGE LEVEL JS -->
    @yield('script')

</body>

</html>
