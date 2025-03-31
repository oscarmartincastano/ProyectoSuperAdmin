@extends('layouts.admin')

@section('pagename', (request()->id ? 'Editar' : 'Enviar') . ' mensaje informativo')

@section('style')
    <style>
        h5 {
            margin: 0;
            padding-bottom: 20px;
            font-size: 1.3125rem;
        }

        .parte-form {
            padding: 20px;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 m-b-10">

            <div class="p-l-20 p-r-20 p-b-10 pt-3">
                <div>
                    <h3 class="text-primary no-margin">Nuevo tipo participante</h3>
                </div>
            </div>

            <div class="p-t-15 p-b-15 p-l-20 p-r-20">
                <div class="card ">
                    <div class="card-header">
                        <div class="card-title">Formulario creación tipo participante</div>
                    </div>
                    <div class="card-body">
                        <form id="form-mail" method="post" role="form" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id_instalacion" value="{{ auth()->user()->id_instalacion }}">
                            <div class="form-group">
                                 <label>Nombre</label>
                                 <input name="nombre" type="text" placeholder="Nombre tipo participante (Ej: Niños, deportista, etc.)" class="form-control" required>
                             </div>
                             <div class="form-group">
                                <label for="tipo">Campos</label>
                                <select required class="full-width select2 select-desactivacion" data-placeholder="Selecciona los campos que va a tener" data-init-plugin="select2" name="campos[]" id="campos" multiple>
                                    <option></option>
                                    @foreach ($campos as $item)
                                        <option value="{{ $item->id }}">{{ $item->label }} ({{ $item->tipo }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <button class="btn btn-primary btn-lg m-b-10 mt-3 w-100" type="submit">
                                Crear tipo participante
                            </button>
                        </form>
                    </div>
                </div>
                <p class="small no-margin">
                </p>
            </div>

        </div>
    </div>
@endsection