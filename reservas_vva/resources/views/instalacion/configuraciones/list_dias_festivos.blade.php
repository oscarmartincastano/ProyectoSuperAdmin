@extends('layouts.admin')

@section('pagename', 'Días festivos')

@section('style')
    <style>
        .clickable {
            cursor: pointer;
        }
        .nav-tabs a.active, .nav-tabs a.active:focus {
            color: white !important;
            background: #0057bc !important;
        }
    </style>    
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12 m-b-10">
        
            <div class="p-l-20 p-r-20 p-b-10 pt-3">
                <div>
                    <h3 class="text-primary no-margin">Días Festivos</h3>
                </div>
            </div>
            
            <div class="p-t-15 p-b-15 p-l-20 p-r-20">
                <div class="card ">
                    <div class="card-header">
                        <div class="card-title">Festivos creados</div>
                    </div>
                    <div class="card-body">
                        <a href="/{{ request()->slug_instalacion }}/admin/configuracion/add-dias-festivos" class="text-white btn btn-primary">Añadir nuevo</a>
                        <div class="tab-content">
                            <div>
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Día festivo</th>
                                            <th>#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($dias as $dia)
                                            <tr>
                                                <td>{!! $dia->id ?? '' !!}</td>
                                                <td>{!! \Carbon\Carbon::parse($dia->dia_festivo)->format('d-m-Y') ?? '' !!}</td>
                                                <td>
                                                    <div class="d-flex" style="gap: 3px">
                                                        <a href="/{{ request()->slug_instalacion }}/admin/configuracion/dias-festivos/{{$dia->id}}/edit" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                                                        <a href="/{{ request()->slug_instalacion }}/admin/configuracion/dias-festivos/{{ $dia->id }}/delete" class="btn btn-danger" onclick="return confirm('¿Estás seguro que quieres borrar este día festivo?')"><i class="fas fa-trash"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <p class="small no-margin">
                </p>
            </div>
        
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function () {
        $(".nav-tabs a").click(function(){
            $('.tab-content>div').removeClass('in active show');
            $('.nav-tabs li, .nav-tabs a').removeClass('active show');
            $(this).tab('show');
        });
    });
</script>
@endsection