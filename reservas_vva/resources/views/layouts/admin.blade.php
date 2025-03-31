@extends('layouts.app')

@section('content-app')
    @include('inc.sidebar')

    <div class="page-container ">
        @include('inc.header')
        <div class="page-content-wrapper ">
            <div class="content sm-gutter">
                {{-- <div data-pages="parallax">
                    <div class="container-fluid p-l-25 p-r-25 sm-p-l-0 sm-p-r-0">
                        <div class="inner">
                            <!-- START BREADCRUMB -->
                            <ol class="breadcrumb sm-p-b-5">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Dashboard</li>
                            </ol>
                        </div>
                    </div>
                </div> --}}
                <div class="container-fluid p-l-25 p-r-25 p-t-0 p-b-25 sm-padding-10">
                    @yield('content')
                </div>
            </div>
            <!--<div class=" container-fluid  container-fixed-lg footer">
                <div class="copyright sm-text-center">
                    <td id="footer"><small>&copy; Desarrollado por <a href="http://tallerempresarial.es/">Taller Empresarial 2.0</a> </td>
                    <div class="clearfix"></div>
                </div>
            </div>-->
            <!-- END COPYRIGHT -->
        </div>
        <!-- END PAGE CONTENT WRAPPER -->
    </div>
    @if(Session::get('success'))
    <div class="alert alert-success" role="alert">
        <button aria-label="" class="close" data-dismiss="alert"></button>
        {{Session::get('success')}}
    </div>
	@endif
    @if(Session::get('error'))
    <div class="alert alert-danger" role="alert">
        <button aria-label="" class="close" data-dismiss="alert"></button>
        {{Session::get('error')}}
    </div>
	@endif
@endsection
