@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-lg-12 m-b-10">

            <div class="p-l-20 p-r-20 p-b-10 pt-3">
                <div>
                    <h3 class="text-primary no-margin">Editar cobro del usuario "{{ $cobro->user->name }}"</h3>
                </div>
            </div>

            <div class="p-t-15 p-b-15 p-l-20 p-r-20">
                <div class="card ">
                    <div class="card-header">
                        <div class="card-title">Información</div>
                    </div>
                    <div class="card-body">
                        <form method="post" role="form">
                            @csrf
                            <div class="form-group ">
                                <label>Concepto</label>
                                <input value="{{ $cobro->concepto }}" name="concepto" type="text" placeholder="Concepto..." class="form-control" required>
                            </div>
                            <div class="form-group ">
                                <label>Fecha</label>
                                <input value="{{ $cobro->fecha }}" name="fecha" type="date" placeholder="Fecha..." class="form-control" required>
                            </div>
                            <div class="form-group ">
                                <label>Forma</label>
                                <input value="{{ $cobro->forma }}" name="forma" type="text" placeholder="Forma..." class="form-control">
                            </div>
                            <div class="form-group ">
                                <label>Cantidad (€)</label>
                                <input value="{{ $cobro->cantidad }}" name="cantidad" type="number" placeholder="Cantidad..." class="form-control">
                            </div>
                            <div class="form-group ">
                                <label>Notas</label>
                                <textarea name="notas" id="notas" rows="4" class="form-control" placeholder="Notas...">{{ $cobro->notas }}</textarea>
                            </div>
                            <button class="btn btn-primary btn-lg m-b-10 mt-3" type="submit">Editar</button>
                        </form>
                    </div>
                </div>
                <p class="small no-margin">
                </p>
            </div>

        </div>
    </div>
@endsection
