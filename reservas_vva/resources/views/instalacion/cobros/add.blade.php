@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-lg-12 m-b-10">

            <div class="p-l-20 p-r-20 p-b-10 pt-3">
                <div>
                    <h3 class="text-primary no-margin">Añadir Cobro</h3>
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
                            <div class="form-group">
                                <label for="cliente">Cliente</label>
                                <select required class="full-width select-user" name="id_user" id="id_user">
                                    <option></option>
                                    @foreach (auth()->user()->instalacion->users as $item)
                                        @if ($item->id != auth()->user()->id)
                                            <option value="{{ $item->id }}">{{ $item->name }} ({{ $item->email }})</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group row">
                                <label>Concepto</label>
                                <input name="concepto" type="text" placeholder="Concepto..." class="form-control" required>
                            </div>
                            <div class="form-group row">
                                <label>Fecha</label>
                                <input name="fecha" type="date" placeholder="Fecha..." class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="form-group row">
                                <label>Forma</label>
                                <input name="forma" type="text" placeholder="Forma..." class="form-control">
                            </div>
                            <div class="form-group row">
                                <label>Cantidad (€)</label>
                                <input name="cantidad" type="number" placeholder="Cantidad..." class="form-control">
                            </div>
                            <div class="form-group row">
                                <label>Notas</label>
                                <textarea name="notas" id="notas" rows="4" class="form-control" placeholder="Notas..."></textarea>
                            </div>
                            <button class="btn btn-primary btn-lg m-b-10 mt-3" type="submit">Añadir</button>
                        </form>
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
            $(".select-user").select2({
                placeholder: "Selecciona un usuario"
            });
        });
    </script>
@endsection