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
                    <h3 class="text-primary no-margin">@if(request()->id) Editar @else Nuevo @endif evento</h3>
                </div>
            </div>

            <div class="p-t-15 p-b-15 p-l-20 p-r-20">
                <div class="card ">
                    <div class="card-header">
                        <div class="card-title">Datos evento</div>
                    </div>
                    <div class="card-body">
                        <form id="form-mail" method="post" role="form" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id_instalacion" value="{{ auth()->user()->id_instalacion }}">
                            <h5 class="border-bottom">Datos evento</h5>
                            <div class="parte-form">

                                {{-- <div class="form-group">
                                    <label class="control-label">Deporte</label>
                                    <select name="id_deporte" class="full-width select2" data-init-plugin="select2">
                                        <option></option>
                                        @foreach ($deportes as $item)
                                            <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div> --}}
                                <div class="form-group">
                                    <label>Cartel</label>
                                    @if(request()->id)
                                    <div class="border p-2">
                                        <img src="/img/eventos/{{ request()->slug_instalacion }}/{{ $evento->id }}.jpg" height="120">
                                    </div>
                                    @endif
                                    <input name="cartel" type="file" placeholder="Selecciona imagen cartel..." class="form-control" accept="image/*" @if(!request()->id) required @endif>
                                </div>
                                <div class="form-group">
                                    <label for="" class="control-label">Titulo del evento</label>
                                    <input @if(request()->id) value="{{ $evento->nombre }}" @endif type="text" name="nombre" id="" class="form-control" placeholder="Nombre del evento">
                                </div>
                                <div class="form-group">
                                    <label for="" class="control-label">Descripción</label>
                                    <div class="quill-wrapper">
                                        <div id="quill">
                                            @if(request()->id) {!! $evento->descripcion !!} @endif
                                        </div>
                                    </div>
                                    <input type="hidden" name="descripcion" class="descripcion">
                                    {{-- <textarea class="form-control" name="descripcion" rows="5" placeholder="Descripción del evento"></textarea> --}}
                                </div>
                                <div class="form-group">
                                    <label for="" class="control-label">Localización</label>
                                    <input @if(request()->id) value="{{ $evento->localizacion }}" @endif type="text" name="localizacion" id="" class="form-control" placeholder="Localización">
                                </div>
                                <div class="form-group">
                                    <label for="" class="control-label">Precio (€)</label>
                                    <input @if(request()->id) value="{{ $evento->precio_participante }}" @else value="0" @endif type="number" step=".01" min="0" name="precio_participante" id="" class="form-control" placeholder="Precio">
                                </div>
                                <div class="d-flex justify-content-between">
                                    <div class="form-group w-100">
                                        <label for="" class="control-label">Fecha inicio</label>
                                        @if (request()->slug_instalacion == 'villafranca-actividades' || request()->slug_instalacion == 'ciprea24' || request()->slug_instalacion == "eventos-bodega" || request()->slug_instalacion == "feria-jamon-villanuevadecordoba")
                                            <input @if(request()->id) value="{{ $evento->fecha_inicio }}" @endif type="datetime-local" name="fecha_inicio" id="" class="form-control" value="{{ date('Y-m-d') }}">
                                        @else
                                        <input @if(request()->id) value="{{ $evento->fecha_inicio }}" @endif type="date" name="fecha_inicio" id="" class="form-control" value="{{ date('Y-m-d') }}">

                                        @endif
                                    </div>
                                    <div class="form-group w-100">
                                        <label for="" class="control-label">Fecha fin</label>
                                        @if (request()->slug_instalacion == 'villafranca-actividades' || request()->slug_instalacion == 'ciprea24' || request()->slug_instalacion == "eventos-bodega" || request()->slug_instalacion == "feria-jamon-villanuevadecordoba")
                                            <input @if(request()->id) value="{{ $evento->fecha_fin }}" @endif type="datetime-local" name="fecha_fin" id="" class="form-control" value="{{ date('Y-m-d') }}">
                                        @else
                                        <input @if(request()->id) value="{{ $evento->fecha_fin }}" @endif type="date" name="fecha_fin" id="" class="form-control" value="{{ date('Y-m-d') }}">
                                        @endif
                                    </div>
                                </div>
                                {{-- <div class="form-group">
                                    <label for="" class="control-label">Tipo de participantes</label>
                                    <select name="tipo_participantes" id="" class="full-width select2-no_search">
                                        <option value="individual">Individual</option>
                                        <option value="multiple">Múltiple</option>
                                    </select>
                                </div> --}}
                                <div class="form-group">
                                    <label class="control-label">Número de participantes</label>
                                    <input @if(request()->id) value="{{ $evento->num_participantes }}" @endif type="number" name="num_participantes" class="form-control" placeholder="Número de participantes">
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Tipo de participante</label>
                                    <select class="full-width select2"  data-init-plugin="select2" name="id_tipo_participante" required>
                                        @foreach (auth()->user()->instalacion->tipos_participante as $item)
                                            <option @if(request()->id && $evento->id_tipo_participante == $item->id ) selected @endif value="{{ $item->id }}">{{ $item->nombre }}</option>
                                        @endforeach
                                    <select>
                                </div>

                                @if (request()->slug_instalacion == 'eventos-bodega' || request()->slug_instalacion == 'villafranca-de-cordoba' || request()->slug_instalacion == 'feria-jamon-villanuevadecordoba')

                                <div class="form-group">
                                    <label class="control-label">¿Entradas agotadas?</label>
                                    <select class="full-width select2-no_search" name="entradas_agotadas" required>
                                        <option @if(request()->id && $evento->entradas_agotadas == 0) selected @endif value="0">No</option>
                                        <option @if(request()->id && $evento->entradas_agotadas == 1) selected @endif value="1">Sí</option>
                                    <select>
                                </div>
                                    
                                @endif
                            </div>
                            <h5 class="border-bottom">Inscripción</h5>
                            <div class="parte-form">
                                <div class="form-group">
                                    <label for="" class="control-label">Tipo de inscripción</label>
                                    <select class="full-width select2-no_search" name="renovacion_mes" required>
                                        <option @if(request()->id && $evento->renovacion_mes == 0) selected @endif value="0">En un período de tiempo una sola vez.</option>
                                        <option @if(request()->id && $evento->renovacion_mes == 1) selected @endif value="1">Inscripción/renovación cada mes (10 primeros días del mes).</option>
                                    <select>
                                </div>
                                <div class="form-group">
                                    <label for="" class="control-label">Fecha inicio</label>
                                    <input required @if(request()->id) value="{{ $evento->insc_fecha_inicio }}" @endif name="insc_fecha_inicio" type="datetime-local" name="nombre" id="" class="form-control" value="{{ date('Y-m-d') }}">
                                </div>
                                <div class="form-group">
                                    <label for="" class="control-label">Fecha fin</label>
                                    <input required @if(request()->id) value="{{ $evento->insc_fecha_fin }}" @endif name="insc_fecha_fin" type="datetime-local" name="nombre" id="" class="form-control" value="{{ date('Y-m-d') }}">
                                </div>
                            </div>
                            <button class="btn btn-primary btn-lg m-b-10 mt-3 w-100" type="submit">
                                @if(request()->id) Editar @else Crear @endif evento
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

@section('script')
    <script>
        $(document).ready(function() {
            $(".select2").select2({
                placeholder: "Selecciona deporte"
            });
            $(".select2-no_search").select2({
                minimumResultsForSearch: -1
            });

            var toolbarOptions = [
                ['bold', 'italic', 'underline', 'strike'],
                ['blockquote', 'code-block'],

                [{ 'header': 1 }, { 'header': 2 }],
                [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                [{ 'script': 'sub' }, { 'script': 'super' }],
                [{ 'indent': '-1' }, { 'indent': '+1' }],
                [{ 'direction': 'rtl' }],

                [{ 'size': ['small', false, 'large', 'huge'] }]
                [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                [ 'link', 'image', 'video', 'formula' ], 

                [{ 'color': [] }, { 'background': [] }],
                [{ 'font': [] }],
                [{ 'align': [] }],

                ['clean']
            ];

            var quill = new Quill('#quill', {
                modules: {
                    toolbar: toolbarOptions
                },
                placeholder: 'Descripción del evento...',
                theme: 'snow'
            });

            $('form').submit(function (e) {
                $('.descripcion').val(quill.root.innerHTML);
            });
        });
    </script>
@endsection
