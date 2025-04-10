@extends('layouts.admin')

@section('style')
    <style>
        .group-horario li {
            display: flex;
            justify-content: space-between;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 m-b-10">

            <div class="p-l-20 p-r-20 p-b-10 pt-3">
                <div>
                    <h3 class="text-primary no-margin">Editar información</h3>
                </div>
            </div>

            <div class="p-t-15 p-b-15 p-l-20 p-r-20">
                <div class="card ">
                    <div class="card-header">
                        <div class="card-title">{{ request()->tipo == 'tlfno' ? 'Teléfono' : request()->tipo }}</div>
                    </div>
                    <div class="card-body">
                        <form action="#" method="post" id="form-normas" role="form" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group row">
                                <label>{{ request()->tipo == 'tlfno' ? 'Teléfono' : ucfirst(request()->tipo) }}</label>
                                @if (request()->tipo == 'logo' || request()->tipo == 'cover')
                                    <input name="{{ request()->tipo }}" type="file" placeholder="Logo..."
                                        class="form-control" required>
                                @elseif(request()->tipo == 'galeria')
                                    <div class="ml-2">(Máximo 3 imágenes)</div>
                                    <div class="border col-12 p-2 d-flex">
                                        @if (file_exists(public_path() . '/img/galerias/' . $instalacion['slug']))
                                            @foreach (\File::files(public_path() . '/img/galerias/' . $instalacion['slug']) as $item)
                                                <div class="position-relative border p-2 mr-2">
                                                    <img src="/img/galerias/{{ $instalacion['slug'] }}/{{ pathinfo($item)['basename'] }}"
                                                        style="width: 75px">
                                                    <a href="/{{ request()->slug_instalacion }}/admin/configuracion/instalacion/edit/galeria/delete/{{ pathinfo($item)['filename'] }}"
                                                        class="close" style="position: absolute;right: 3px;top: 0;"
                                                        onclick="return confirm('¿Quieres eliminar esta imagen de tu galería?')">
                                                        <span class="button_cross"><i class="fas fa-times"></i></span>
                                                    </a>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>

                                    @if (file_exists(public_path() . '/img/galerias/' . $instalacion['slug']) &&
                                            count(\File::files(public_path() . '/img/galerias/' . $instalacion['slug'])) == 3)
                                        <div class="w-100 border p-2">No puedes añadir más imágenes porque tienes ya 3.
                                            Borra alguna para añadir una nueva.</div>
                                    @else
                                        <input name="{{ request()->tipo }}" type="file" placeholder="Galería..."
                                            class="form-control mt-1" accept="image/*" required>
                                        <small>Añade una imagen</small>
                                    @endif
                                @elseif(request()->tipo == 'html_normas')
                                    <div class="form-group">
                                        <div class="quill-wrapper">
                                            <div id="quill">
                                                {!! $instalacion[request()->tipo] ?? '' !!}
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="html_normas" class="html_normas">
                                @elseif(request()->tipo == 'politica')
                                    <div class="form-group">
                                        <div class="quill-wrapper">
                                            <div id="quill">
                                                {!! $instalacion[request()->tipo] ?? '' !!}
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="politica" class="html_normas">
                                    @elseif(request()->tipo == 'condiciones')
                                    <div class="form-group">
                                        <div class="quill-wrapper">
                                            <div id="quill">
                                                {!! $instalacion[request()->tipo] ?? '' !!}
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="condiciones" class="html_normas">
                                @elseif(request()->tipo == 'horario')
                                    <div class="col-md-12">
                                        <ul class="list-group group-horario">
                                            @php
                                                // Deserializar el campo horario
                                                $horario = @unserialize($instalacion['horario']) ?: [];
                                            @endphp

                                            @foreach (['lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado', 'domingo'] as $dia)
                                                <li class="list-group-item">
                                                    <div>{{ ucfirst($dia) }}</div>
                                                    <div>
                                                        @if (isset($horario[$dia]['intervalo']))
                                                            @foreach ($horario[$dia]['intervalo'] as $index => $intervalo)
                                                                <div class="d-flex align-items-center"
                                                                    data-index="{{ $index }}">
                                                                    <div class="form-group form-group-default m-0">
                                                                        <label>Hora inicio</label>
                                                                        <input type="time" style="width: 180px"
                                                                            name="horario[{{ $dia }}][intervalo][{{ $index }}][hinicio]"
                                                                            value="{{ $intervalo['hinicio'] ?? '' }}"
                                                                            class="form-control">
                                                                    </div>
                                                                    <div class="form-group form-group-default m-0">
                                                                        <label>Hora fin</label>
                                                                        <input type="time" style="width: 180px"
                                                                            name="horario[{{ $dia }}][intervalo][{{ $index }}][hfin]"
                                                                            value="{{ $intervalo['hfin'] ?? '' }}"
                                                                            class="form-control">
                                                                    </div>
                                                                    <div class="ml-2">
                                                                        <a href="#"
                                                                            class="btn btn-rounded btn-drop-horario"><i
                                                                                class="fas fa-times"></i></a>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                        <div class="mt-2">
                                                            <a href="#" class="btn btn-add-horario"
                                                                data-dia="{{ $dia }}">Añadir horario</a>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @elseif(request()->tipo == 'servicios')
                                @php
                                $servicios_adicionales = \App\Models\Servicios_adicionales::all()->toArray();
                            @endphp
                            <div class="border p-2 w-100">
                                @foreach ($servicios_adicionales as $item)
                                    <div class="w-100">
                                        <div class="form-check form-check-inline switch mb-4">
                                            <input class="form-check-input" type="checkbox"
                                                value="{{ $item['id'] }}" id="servicio{{ $item['id'] }}"
                                                name="servicios[]">
                                            <label class="form-check-label" for="servicio{{ $item['id'] }}">
                                                {{ $item['nombre'] }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                                @elseif(request()->tipo == 'prefijo_pedido')
                                    <input value="{{ $instalacion[request()->tipo] }}" name="{{ request()->tipo }}"
                                        type="text"
                                        placeholder="{{ request()->tipo == 'tlfno' ? 'Teléfono' : ucfirst(request()->tipo) }}..."
                                        class="form-control" minlength="4" maxlength="4" required>
                                @elseif(request()->tipo == 'tipo_reservas')
                                    <div class="border p-2 w-100">
                                        @foreach (\App\Models\Tipo_reservas::all() as $item)
                                            <div class="w-100">
                                                <div class="form-check form-check-inline switch mb-4">
                                                    <input class="form-check-input" type="radio"
                                                        value="{{ $item->id }}" id="tipo_reservas{{ $item->id }}"
                                                        name="tipo_reservas_id"
                                                        {{ $item->id == $instalacion['tipo_reservas_id'] ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="tipo_reservas{{ $item->id }}">
                                                        {{ $item->nombre }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @elseif(request()->tipo == 'tipo_calendario')
                                    <div class="form-group">
                                        <br>
                                        <label for="tipo_calendario">Seleccionar </label>
                                        <select name="tipo_calendario" id="tipo_calendario" class="form-control">
                                            <option value="0" {{ $tipoCalendario == 0 ? 'selected' : '' }}>Sin calendario
                                            </option>
                                            <option value="1" {{ $tipoCalendario == 1 ? 'selected' : '' }}>Calendario
                                                1
                                            </option>
                                            <option value="2" {{ $tipoCalendario == 2 ? 'selected' : '' }}>Calendario
                                                2
                                            </option>
                                        </select>
                                    </div>

                                    @elseif(request()->tipo == 'normas_visualizacion')
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Opciones de visualización para Admin</label>
                                    
                                        @php
                                            $permiso = $permisos->first(); // Acceder al primer elemento de la colección
                                        @endphp
                                    
                                    @foreach ($permiso as $key => $value)
                                    @if (Str::startsWith($key, 'ver_') && Str::endsWith($key, '_admin'))
                                        @php
                                            // Obtener el nombre del "superior" eliminando "_admin" del final
                                            $superiorKey = Str::replaceLast('_admin', '', $key);
                                        @endphp
                                
                                        @if (isset($permiso->$superiorKey) && $permiso->$superiorKey == 1)
                                            <div class="mb-2">
                                                <label class="form-label">
                                                    {{ $key == 'ver_serviciosadicionales_admin' ? 'Servicios adicionales' : ucfirst(str_replace('_', ' ', str_replace(['ver_', '_admin'], '', $key))) }}
                                                </label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="permisos[{{ $key }}]"
                                                        id="{{ $key }}_si" value="1"
                                                        {{ old("permisos.$key", $value) == 1 ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="{{ $key }}_si">Sí</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="permisos[{{ $key }}]"
                                                        id="{{ $key }}_no" value="0"
                                                        {{ old("permisos.$key", $value) == 0 ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="{{ $key }}_no">No</label>
                                                </div>
                                            </div>
                                        @else
                                            <div class="mb-2">
                                                <label class="form-label">
                                                    {{ $key == 'ver_serviciosadicionales_admin' ? 'Servicios adicionales' : ucfirst(str_replace('_', ' ', str_replace(['ver_', '_admin'], '', $key))) }}
                                                </label>
                                                <p class="text-danger">
                                                    La visualización de {{ str_replace('_', ' ', str_replace(['ver_', '_admin'], '', $key)) }} está desactivada. Pide al superadmin que lo active.
                                                </p>
                                            </div>
                                        @endif
                                    @endif
                                @endforeach
                                    </div>
                                @else
                                    <input value="{{ $instalacion[request()->tipo] }}" name="{{ request()->tipo }}"
                                        type="text"
                                        placeholder="{{ request()->tipo == 'tlfno' ? 'Teléfono' : ucfirst(request()->tipo) }}..."
                                        class="form-control" required>
                                @endif
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

@section('script')
    <script>
        $(document).ready(function() {
            var toolbarOptions = [
                ['bold', 'italic', 'underline', 'strike'],
                ['blockquote', 'code-block'],

                [{
                    'header': 1
                }, {
                    'header': 2
                }],
                [{
                    'list': 'ordered'
                }, {
                    'list': 'bullet'
                }],
                [{
                    'script': 'sub'
                }, {
                    'script': 'super'
                }],
                [{
                    'indent': '-1'
                }, {
                    'indent': '+1'
                }],
                [{
                    'direction': 'rtl'
                }],

                [{
                    'size': ['small', false, 'large', 'huge']
                }]
                [{
                    'header': [1, 2, 3, 4, 5, 6, false]
                }],

                [{
                    'color': []
                }, {
                    'background': []
                }],
                [{
                    'font': []
                }],
                [{
                    'align': []
                }],

                ['clean']
            ];

            var quill = new Quill('#quill', {
                modules: {
                    toolbar: toolbarOptions
                },
                placeholder: 'Html de normas...',
                theme: 'snow'
            });

            if ($('.html_normas').length) {
                $('#form-normas').submit(function(e) {
                    $('.html_normas').val(quill.root.innerHTML);
                });
            }

            $('form').on('click', '.btn-add-horario', function(e) {
                e.preventDefault();
                let index = !isNaN($(this).parent().prev().data('index')) ? $(this).parent().prev().data(
                    'index') + 1 : 0;

                $(this).parent().before(`<div class="d-flex align-items-center"  data-index="${index}">
                                                        <div class="form-group form-group-default m-0">
                                                            <label>Hora inicio</label>
                                                            <input type="time" style="width: 180px" name="horario[${$(this).data('dia')}][intervalo][${index}][hinicio]" placeholder="Default input" class="form-control">
                                                        </div>
                                                        <div class="form-group form-group-default m-0">
                                                            <label>Hora fin</label>
                                                            <input type="time" style="width: 180px" name="horario[${$(this).data('dia')}][intervalo][${index}][hfin]" placeholder="Default input" class="form-control">
                                                        </div>
                                                        <div class="ml-2"><a href="#" class="btn btn-rounded btn-drop-horario"><i class="fas fa-times"></i></a></div>
                                                    </div>`);
            });

            $('form').on('click', '.btn-drop-horario', function(e) {
                e.preventDefault();
                $(this).parent().parent().remove();
            });
        });
    </script>
@endsection
