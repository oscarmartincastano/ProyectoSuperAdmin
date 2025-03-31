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
                        <div class="card-title">Campos adicionales reservas</div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('edit_config', ['slug_instalacion' => $instalacion->slug]) }}" method="post">
                            @csrf
                            <div class="form-group mb-4">
                                <label>Campos personalizados en la reserva</label>
                                <div class="border p-3">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <td>Tipo</td>
                                                <td>Nombre</td>
                                                <td>Pistas</td>
                                                <td style="width: 15%">#</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($instalacion->campos_personalizados as $campo)
                                                <tr>
                                                    <td style="text-transform: capitalize">{{ $campo->tipo }}</td>
                                                    <td>{{ $campo->label }}</td>
                                                    <td>
                                                        @if ($campo->all_pistas)
                                                            Todos los espacios
                                                        @else
                                                            @foreach ($campo->pistas as $index => $value)
                                                                {{ $value->nombre }}{{ $index != count($campo->pistas)-1 ? ',' : '' }}
                                                            @endforeach
                                                        @endif
                                                    </td>
                                                    <td><a href="/{{ auth()->user()->instalacion->slug }}/admin/campos-adicionales/campos-personalizados/{{ $campo->id }}" class="btn btn-primary"><i class="fas fa-edit"></i></a> <a href="/{{ auth()->user()->instalacion->slug }}/admin/campos-adicionales/campos-personalizados/{{ $campo->id }}/delete" onclick="return confirm('¿Estás seguro que quieres borrar este campo?');" class="btn btn-danger"><i class="fas fa-trash"></i></a></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="3"><a href="/{{ auth()->user()->instalacion->slug }}/admin/campos-adicionales/campos-personalizados" class="btn btn-primary"><i class="fas fa-plus mr-2"></i> Añadir campo</a></td>
                                            </tr>
                                        </tfoot>
                                    </table>
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
