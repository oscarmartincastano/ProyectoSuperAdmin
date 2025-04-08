@extends('layouts.superadmin')
@section('header', 'Editar Instalación')

@section('content')
    @if (!Auth::check())
        <script>
            window.location.href = "{{ route('superadmin.login') }}";
        </script>
    @endif
    {{-- si hay cualuier error que lo muestre aqui --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    {{-- Formulario para editar un ayuntamiento --}}
    <form action="{{ route('superadmin.update', $ayuntamiento->id) }}" method="POST" class="bg-light p-4 rounded shadow-sm">
        @csrf
        @method('PUT')

        <!-- Información del Ayuntamiento -->
        <div class="mb-4">
            <h3 class="text-primary">Información del Ayuntamiento</h3>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="name" name="name"
                        value="{{ old('name', $ayuntamiento->name) }}" placeholder="Nombre del ayuntamiento">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="url" class="form-label">Ruta</label>
                    <input type="text" class="form-control" id="url" name="url_visible"
                        value="{{ old('url', $ayuntamiento->url) }}" placeholder="URL del ayuntamiento" disabled>
                    <input type="hidden" name="url" value="{{ old('url', $ayuntamiento->url) }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="bd_nombre" class="form-label">Nombre de la base de datos</label>
                    <input type="text" class="form-control" id="bd_nombre" name="bd_nombre"
                        value="{{ old('bd_nombre', $ayuntamiento->bd_nombre) }}" placeholder="Nombre de la base de datos">
                </div>
                {{-- Radio button para seleccionar si ver los sponsor o no --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">Mostrar Sponsors</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="ver_sponsor" id="ver_sponsor_si" value="1"
                            {{ old('ver_sponsor', $ayuntamiento->ver_sponsor ?? 0) == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="ver_sponsor_si">Sí</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="ver_sponsor" id="ver_sponsor_no" value="0"
                            {{ old('ver_sponsor', $ayuntamiento->ver_sponsor ?? 0) == 0 ? 'checked' : '' }}>
                        <label class="form-check-label" for="ver_sponsor_no">No</label>
                    </div>
                </div>
                
                {{-- Seleccionar calendario 1 con el valor 0 o calendario 2 con el valor 1 --}}
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">Seleccionar Calendario</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="calendario" id="calendario_1" value="0"
                            {{ old('calendario', $ayuntamiento->tipo_calendario ?? 0) == 0 ? 'checked' : '' }}>
                        <label class="form-check-label" for="calendario_1">Calendario 1</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="calendario" id="calendario_2" value="1"
                            {{ old('calendario', $ayuntamiento->tipo_calendario ?? 0) == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="calendario_2">Calendario 2</label>
                    </div>
                </div>
            </div>
        </div>

        <!--Información de las Instalaciones teniendo en cuenta que tienen los campos nombre, dirección, teléfono, html_normas, servicios,horario,slug,política,condiciones y luego vpoy a necesitar 5 radio button de ver_normas, ver_servicios, ver_horario,ver_politica,ver_condiciones los cuales pondran en la base de datos 1 si se selecciona la opción mostrar y 0 si se selecciona la opcion no mostrar-->
        <div class="mb-4">
            <h3 class="text-primary">Instalaciones</h3>
            @if (!empty($aDatos['instalaciones']) && count($aDatos['instalaciones']) > 0)
                @foreach ($aDatos['instalaciones'] as $instalacion)
                    <div class="instalacion mb-4 p-3 border rounded">
                        <h5 class="text-secondary">Instalación: {{ $instalacion->nombre }}</h5>
                        <div class="row">
                            <!-- Campo: Nombre -->
                            <div class="col-md-6 mb-3">
                                <label for="nombre_{{ $instalacion->id }}" class="form-label">Nombre</label>
                                <input type="text"
                                    class="form-control @error('nombre_' . $instalacion->id) is-invalid @enderror"
                                    name="nombre_{{ $instalacion->id }}" id="nombre_{{ $instalacion->id }}"
                                    value="{{ old('nombre_' . $instalacion->id, $instalacion->nombre) }}"
                                    placeholder="Nombre">
                                @error('nombre_' . $instalacion->id)
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Campo: Dirección -->
                            <div class="col-md-6 mb-3">
                                <label for="direccion_{{ $instalacion->id }}" class="form-label">Dirección</label>
                                <input type="text"
                                    class="form-control @error('direccion_' . $instalacion->id) is-invalid @enderror"
                                    name="direccion_{{ $instalacion->id }}" id="direccion_{{ $instalacion->id }}"
                                    value="{{ old('direccion_' . $instalacion->id, $instalacion->direccion) }}"
                                    placeholder="Dirección">
                                @error('direccion_' . $instalacion->id)
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Campo: Teléfono -->
                            <div class="col-md-6 mb-3">
                                <label for="tlfno_{{ $instalacion->id }}" class="form-label">Teléfono</label>
                                <input type="text"
                                    class="form-control @error('tlfno_' . $instalacion->id) is-invalid @enderror"
                                    name="tlfno_{{ $instalacion->id }}" id="tlfno_{{ $instalacion->id }}"
                                    value="{{ old('tlfno_' . $instalacion->id, $instalacion->tlfno) }}"
                                    placeholder="Teléfono">
                                @error('tlfno_' . $instalacion->id)
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Campo: Slug -->
                            <div class="col-md-6 mb-3">
                                <label for="slug_{{ $instalacion->id }}" class="form-label">Slug</label>
                                <input type="text"
                                    class="form-control @error('slug_' . $instalacion->id) is-invalid @enderror"
                                    name="slug_{{ $instalacion->id }}" id="slug_{{ $instalacion->id }}"
                                    value="{{ old('slug_' . $instalacion->id, $instalacion->slug) }}" placeholder="Slug">
                                @error('slug_' . $instalacion->id)
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Campo: Política -->
                            <div class="col-md-12 mb-3">
                                <label for="politica_{{ $instalacion->id }}" class="form-label">Política</label>
                                <textarea class="form-control @error('politica_' . $instalacion->id) is-invalid @enderror"
                                    name="politica_{{ $instalacion->id }}" id="politica_{{ $instalacion->id }}" rows="6"
                                    placeholder="Política">{{ old('politica_' . $instalacion->id, $instalacion->politica) }}</textarea>
                                @error('politica_' . $instalacion->id)
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- <!-- Campo: Condiciones -->
                            <div class="col-md-12 mb-3">
                                <label for="condiciones_{{ $instalacion->id }}" class="form-label">Condiciones</label>
                                <textarea class="form-control @error('condiciones_' . $instalacion->id) is-invalid @enderror"
                                    name="condiciones_{{ $instalacion->id }}" id="condiciones_{{ $instalacion->id }}" rows="6"
                                    placeholder="Condiciones">{{ old('condiciones_' . $instalacion->id, $instalacion->condiciones) }}</textarea>
                                @error('condiciones_' . $instalacion->id)
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div> --}}

                            <!-- Botones de radio para opciones de visualización -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Opciones de visualización</label>

                                @foreach ($aDatos['instalaciones_visualizacion'] as $instalacion)
                                    @foreach ($instalacion as $key => $value)
                                        @if (Str::startsWith($key, 'ver_'))
                                            <div class="mb-2">
                                                <label class="form-label">{{ ucfirst(str_replace('_', ' ', str_replace('ver_', '', $key))) }}</label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="{{ $key }}_{{ $instalacion['id'] }}"
                                                        id="{{ $key }}_si_{{ $instalacion['id'] }}" value="1"
                                                        {{ old($key . '_' . $instalacion['id'], $value) == 1 ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="{{ $key }}_si_{{ $instalacion['id'] }}">Sí</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="{{ $key }}_{{ $instalacion['id'] }}"
                                                        id="{{ $key }}_no_{{ $instalacion['id'] }}" value="0"
                                                        {{ old($key . '_' . $instalacion['id'], $value) == 0 ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="{{ $key }}_no_{{ $instalacion['id'] }}">No</label>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                {{-- Creación de formulario con los campos vacíos --}}
                <div class="instalacion mb-4 p-3 border rounded">
                    <div class="row">
                        <!-- Campo: Nombre -->
                        <div class="col-md-6 mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                                id="nombre" name="nombre" value="{{ old('nombre') }}" placeholder="Nombre">
                            @error('nombre')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Campo: Dirección -->
                        <div class="col-md-6 mb-3">
                            <label for="direccion" class="form-label">Dirección</label>
                            <input type="text" class="form-control @error('direccion') is-invalid @enderror"
                                id="direccion" name="direccion" value="{{ old('direccion') }}"
                                placeholder="Dirección">
                            @error('direccion')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Campo: Teléfono -->
                        <div class="col-md-6 mb-3">
                            <label for="tlfno" class="form-label">Teléfono</label>
                            <input type="text" class="form-control @error('tlfno') is-invalid @enderror"
                                id="tlfno" name="tlfno" value="{{ old('tlfno') }}" placeholder="Teléfono">
                            @error('tlfno')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Campo: Slug -->
                        <div class="col-md-6 mb-3">
                            <label for="slug" class="form-label">Slug</label>
                            <input type="text" class="form-control" id="slug" name="slug"
                                value="{{ old('slug') }}" placeholder="Slug">
                        </div>

                        <!-- Campo: Política -->
                        <div class="col-md-6 mb-3">
                            <label for="politica" class="form-label">Política</label>
                            <textarea class="form-control" name="politica" id="politica" rows="6" placeholder="Política">{{ old('politica') }}</textarea>
                        </div>

                    </div>
                </div>
            @endif
        </div>

        <!-- Botón de envío -->
        <div class="text-center">
            <button type="submit" class="btn btn-primary px-5">Guardar</button>
        </div>
    </form>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
@endsection
