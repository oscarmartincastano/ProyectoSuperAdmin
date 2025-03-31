@extends('layouts.admin')

@section('pagename',  (request()->id ? 'Editar' : 'Enviar') . ' mensaje informativo')

@section('content')
    <div class="row">
        <div class="col-lg-12 m-b-10">

            <div class="p-l-20 p-r-20 p-b-10 pt-3">
                <div>
                    <h3 class="text-primary no-margin">@if(request()->id)Editar @else Enviar  @endif mensaje informativo</h3>
                </div>
            </div>

            <div class="p-t-15 p-b-15 p-l-20 p-r-20">
                <div class="card ">
                    <div class="card-header">
                        <div class="card-title">Mensaje</div>
                    </div>
                    <div class="card-body">
                        <form id="form-mail" method="post" role="form">
                            @csrf
                            <input type="hidden" name="id_instalacion" value="{{ auth()->user()->instalacion->id }}">

							{{-- <div class="form-group"> --}}
                                {{-- <label class="control-label">Destinatario</label>
                                <input name="destinatario" type="email" class="form-control" placeholder="Email destinatario..." @if(isset(request()->user)) value="{{ \App\Models\User::find(request()->user)->email }}" @endif required> --}}
								
                                {{-- <div class="p-0">
                                    <select required class="full-width select2 select-cliente" data-init-plugin="select2" name="destinatario" id="destinatario">
                                        <option></option>
                                        @foreach (auth()->user()->instalacion->users as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }} ({{ $item->email }})</option>
                                        @endforeach
                                    </select>
                                </div>--}}
                            {{-- </div> --}}
                            <div class="form-group">
                                <label class="control-label">Título</label>
                                <input @if(request()->id) value="{{ $mensaje->titulo }}" @endif name="titulo" type="text" class="form-control" placeholder="Título..." value="Nuevo mensaje" required>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Fecha inicio</label>
                                <input @if(request()->id) value="{{ $mensaje->fecha_inicio }}" @endif name="fecha_inicio" type="date" class="form-control"  value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Fecha fin</label>
                                <input @if(request()->id) value="{{ $mensaje->fecha_fin }}" @endif name="fecha_fin" type="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Tipo de mensaje:</label>
                                    <select @if(request()->id) value="{{ $mensaje->tipo_mensaje }}" @endif class="form-control full-width" name="tipo_mensaje" id="tipo_mensaje">
                                        <option value="publico">Público</option>
                                        <option value="privado">Privado para los usuarios registrados</option>
                                    </select>
                            </div>
                            <div class="form-group">
                                <label>Contenido</label>
                                <div class="quill-wrapper">
                                    <div id="quill">
                                        @if(request()->id)
                                        {!! $mensaje->contenido !!}
                                        @else
                                        <p>Ejemplo de mensaje de difusión para los usuarios.</p>
                                        <p><br></p>
                                        <p>Gracias.</p>
                                        <p><br></p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="contenido" class="contenido">
                            <button class="btn btn-primary btn-lg m-b-10 mt-3" type="submit">@if(request()->id) Editar @else Enviar @endif</button>
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

                [{ 'color': [] }, { 'background': [] }],
                [{ 'font': [] }],
                [{ 'align': [] }],

                ['clean']
            ];

            var quill = new Quill('#quill', {
                modules: {
                    toolbar: toolbarOptions
                },
                placeholder: 'Type here...',
                theme: 'snow'
            });
            
            $(".select2").select2({
                placeholder: "Selecciona el usuario..."
            });

            $('#form-mail').submit(function (e) { 
                $('.contenido').val(quill.root.innerHTML);
            });
        });
    </script>
@endsection