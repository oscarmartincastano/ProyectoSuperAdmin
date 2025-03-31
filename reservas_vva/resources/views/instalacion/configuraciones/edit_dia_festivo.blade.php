@extends('layouts.admin')

@section('style')
    <style>
        .col-form-label {
            font-weight: bold;
        }
        thead td{
            font-weight: bold;
        }
        td{
            padding: .75rem !important;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 m-b-10">

            <div class="p-l-20 p-r-20 p-b-10 pt-3">
                <div>
                    <h3 class="text-primary no-margin">Configuraciones</h3>
                </div>
            </div>

            <div class="p-t-15 p-b-15 p-l-20 p-r-20">
                <div class="card ">
                    <div class="card-header">
                        <div class="card-title">Dias Festivos</div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('edit_festivo', ['slug_instalacion' => request()->slug_instalacion , 'id' =>request()->id]) }}" method="post">
                            @csrf
                            
                            <div class="form-group mb-4">
                                <label for="">Edite el día festivo</label>
                                <div class="border p-3">
                                    <input type="date" name="dia_festivo" id="">
                                </div>
                            </div>
                            <input type="submit" value="Editar" class="btn btn-primary btn-lg m-b-10 mt-3 mt-2">
                        </form>
                    </div>
                </div>
                <p class="small no-margin">
                </p>
            </div>
        </div>
    </div>
@endsection
