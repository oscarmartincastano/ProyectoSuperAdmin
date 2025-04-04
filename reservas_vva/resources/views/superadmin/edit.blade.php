@extends('layouts.superadmin')
@section('header', 'Editar Instalación')

@section('content')
    @if (!Auth::check())
        <script>
            window.location.href = "{{ route('superadmin.login') }}";
        </script>
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

                            <!-- Campo: HTML Normas -->
                            <div class="col-md-12 mb-3">
                                <label for="html_normas_{{ $instalacion->id }}" class="form-label">HTML
                                    Normas</label>
                                <textarea class="form-control @error('html_normas_' . $instalacion->id) is-invalid @enderror"
                                    name="html_normas_{{ $instalacion->id }}" id="html_normas_{{ $instalacion->id }}" rows="6"
                                    placeholder="HTML Normas">{{ old('html_normas_' . $instalacion->id, $instalacion->html_normas) }}</textarea>
                                @error('html_normas_' . $instalacion->id)
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Campo: Servicios -->
                            <div class="col-md-12 mb-3">
                                <label for="servicios_{{ $instalacion->id }}" class="form-label">Servicios</label>
                                <div class="border p-3 rounded bg-light">
                                    <h6 class="text-primary">Selecciona los servicios disponibles:</h6>
                                    <div class="row">
                                        @if ($servicios->isEmpty() && $serviciosAdicionales->isEmpty())
                                            <div class="col-12">
                                                <p class="text-danger">No hay servicios disponibles para
                                                    seleccionar.</p>
                                            </div>
                                        @else
                                            @foreach ($servicios as $servicio)
                                                <div class="col-md-4">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            id="servicio_{{ $instalacion->id }}_{{ $servicio->id }}"
                                                            name="servicios_{{ $instalacion->id }}[]"
                                                            value="{{ $servicio->id }}"
                                                            @if (in_array($servicio->id, $instalacion->servicios ?? [])) checked @endif>
                                                        <label class="form-check-label"
                                                            for="servicio_{{ $instalacion->id }}_{{ $servicio->id }}">
                                                            {{ $servicio->nombre }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach

                                            @foreach ($serviciosAdicionales as $servicioAdicional)
                                                <div class="col-md-4">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            id="servicio_adicional_{{ $instalacion->id }}_{{ $servicioAdicional->id }}"
                                                            name="servicios_{{ $instalacion->id }}[]"
                                                            value="{{ $servicioAdicional->id }}"
                                                            @if (in_array($servicioAdicional->id, $instalacion->servicios ?? [])) checked @endif>
                                                        <label class="form-check-label"
                                                            for="servicio_adicional_{{ $instalacion->id }}_{{ $servicioAdicional->id }}">
                                                            {{ $servicioAdicional->nombre }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Campo: Horario -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Horario</label>
                                <div class="border p-3 rounded bg-light">
                                    @foreach (['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'] as $dia)
                                        <div class="mb-3">
                                            <h6 class="text-primary">Día: {{ $dia }}</h6>
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Hora Inicio</th>
                                                        <th>Hora Fin</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @for ($i = 0; $i < 2; $i++)
                                                        {{-- Dos intervalos por día --}}
                                                        <tr>
                                                            <td>
                                                                <input type="time" class="form-control"
                                                                    name="horario_{{ $instalacion->id }}[{{ strtolower($dia) }}][intervalo][{{ $i }}][hinicio]"
                                                                    value="{{ old('horario_' . $instalacion->id . '.' . strtolower($dia) . ".intervalo.$i.hinicio", $instalacion->horario[strtolower($dia)]['intervalo'][$i]['hinicio'] ?? '') }}"
                                                                    placeholder="Hora Inicio">
                                                            </td>
                                                            <td>
                                                                <input type="time" class="form-control"
                                                                    name="horario_{{ $instalacion->id }}[{{ strtolower($dia) }}][intervalo][{{ $i }}][hfin]"
                                                                    value="{{ old('horario_' . $instalacion->id . '.' . strtolower($dia) . ".intervalo.$i.hfin", $instalacion->horario[strtolower($dia)]['intervalo'][$i]['hfin'] ?? '') }}"
                                                                    placeholder="Hora Fin">
                                                            </td>
                                                        </tr>
                                                    @endfor
                                                </tbody>
                                            </table>
                                        </div>
                                    @endforeach
                                </div>
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

                            <!-- Campo: Condiciones -->
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
                            </div>

                            <!-- Botones de radio para opciones de visualización -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Opciones de visualización</label>

                                <!-- Botones de radio para Normas -->
                                <div class="mb-2">
                                    <label class="form-label">Mostrar Normas</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio"
                                            name="ver_normas_{{ $instalacion->id }}"
                                            id="ver_normas_si_{{ $instalacion->id }}" value="1"
                                            {{ old('ver_normas_' . $instalacion->id, $instalacion->ver_normas) == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label"
                                            for="ver_normas_si_{{ $instalacion->id }}">Sí</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio"
                                            name="ver_normas_{{ $instalacion->id }}"
                                            id="ver_normas_no_{{ $instalacion->id }}" value="0"
                                            {{ old('ver_normas_' . $instalacion->id, $instalacion->ver_normas) == 0 ? 'checked' : '' }}>
                                        <label class="form-check-label"
                                            for="ver_normas_no_{{ $instalacion->id }}">No</label>
                                    </div>
                                </div>

                                <!-- Botones de radio para Servicios -->
                                <div class="mb-2">
                                    <label class="form-label">Mostrar Servicios</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio"
                                            name="ver_servicios_{{ $instalacion->id }}"
                                            id="ver_servicios_si_{{ $instalacion->id }}" value="1"
                                            {{ old('ver_servicios_' . $instalacion->id, $instalacion->ver_servicios) == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label"
                                            for="ver_servicios_si_{{ $instalacion->id }}">Sí</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio"
                                            name="ver_servicios_{{ $instalacion->id }}"
                                            id="ver_servicios_no_{{ $instalacion->id }}" value="0"
                                            {{ old('ver_servicios_' . $instalacion->id, $instalacion->ver_servicios) == 0 ? 'checked' : '' }}>
                                        <label class="form-check-label"
                                            for="ver_servicios_no_{{ $instalacion->id }}">No</label>
                                    </div>
                                </div>

                                <!-- Botones de radio para Horario -->
                                <div class="mb-2">
                                    <label class="form-label">Mostrar Horario</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio"
                                            name="ver_horario_{{ $instalacion->id }}"
                                            id="ver_horario_si_{{ $instalacion->id }}" value="1"
                                            {{ old('ver_horario_' . $instalacion->id, $instalacion->ver_horario) == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label"
                                            for="ver_horario_si_{{ $instalacion->id }}">Sí</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio"
                                            name="ver_horario_{{ $instalacion->id }}"
                                            id="ver_horario_no_{{ $instalacion->id }}" value="0"
                                            {{ old('ver_horario_' . $instalacion->id, $instalacion->ver_horario) == 0 ? 'checked' : '' }}>
                                        <label class="form-check-label"
                                            for="ver_horario_no_{{ $instalacion->id }}">No</label>
                                    </div>
                                </div>

                                <!-- Botones de radio para Política -->
                                <div class="mb-2">
                                    <label class="form-label">Mostrar Política</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio"
                                            name="ver_politica_{{ $instalacion->id }}"
                                            id="ver_politica_si_{{ $instalacion->id }}" value="1"
                                            {{ old('ver_politica_' . $instalacion->id, $instalacion->ver_politica) == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label"
                                            for="ver_politica_si_{{ $instalacion->id }}">Sí</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio"
                                            name="ver_politica_{{ $instalacion->id }}"
                                            id="ver_politica_no_{{ $instalacion->id }}" value="0"
                                            {{ old('ver_politica_' . $instalacion->id, $instalacion->ver_politica) == 0 ? 'checked' : '' }}>
                                        <label class="form-check-label"
                                            for="ver_politica_no_{{ $instalacion->id }}">No</label>
                                    </div>
                                </div>

                                <!-- Botones de radio para Condiciones -->
                                <div class="mb-2">
                                    <label class="form-label">Mostrar Condiciones</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio"
                                            name="ver_condiciones_{{ $instalacion->id }}"
                                            id="ver_condiciones_si_{{ $instalacion->id }}" value="1"
                                            {{ old('ver_condiciones_' . $instalacion->id, $instalacion->ver_condiciones) == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label"
                                            for="ver_condiciones_si_{{ $instalacion->id }}">Sí</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio"
                                            name="ver_condiciones_{{ $instalacion->id }}"
                                            id="ver_condiciones_no_{{ $instalacion->id }}" value="0"
                                            {{ old('ver_condiciones_' . $instalacion->id, $instalacion->ver_condiciones) == 0 ? 'checked' : '' }}>
                                        <label class="form-check-label"
                                            for="ver_condiciones_no_{{ $instalacion->id }}">No</label>
                                    </div>
                                </div>
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

                        <!-- Campo: HTML Normas -->
                        <div class="col-md-6 mb-3">
                            <label for="html_normas" class="form-label">HTML Normas</label>
                            <textarea class="form-control @error('html_normas') is-invalid @enderror" name="html_normas" id="html_normas"
                                rows="6" placeholder="HTML Normas">{{ old('html_normas') }}</textarea>
                            @error('html_normas')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Campo: Servicios -->
                        <div class="col-md-12 mb-3">
                            <label for="servicios" class="form-label">Servicios</label>
                            <div class="border p-3 rounded bg-light">
                                <h6 class="text-primary">Selecciona los servicios disponibles:</h6>
                                <div class="row">
                                    @if ($servicios->isEmpty() && $serviciosAdicionales->isEmpty())
                                        <div class="col-12">
                                            <p class="text-danger">No hay servicios disponibles para seleccionar.
                                            </p>
                                        </div>
                                    @else
                                        @foreach ($servicios as $servicio)
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"
                                                        id="servicio_{{ $servicio->id }}" name="servicios[]"
                                                        value="{{ $servicio->id }}"
                                                        @if (is_array(old('servicios')) && in_array($servicio->id, old('servicios'))) checked @endif>
                                                    <label class="form-check-label" for="servicio_{{ $servicio->id }}">
                                                        {{ $servicio->nombre }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach

                                        @foreach ($serviciosAdicionales as $servicioAdicional)
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"
                                                        id="servicio_adicional_{{ $servicioAdicional->id }}"
                                                        name="servicios[]" value="{{ $servicioAdicional->id }}"
                                                        @if (is_array(old('servicios')) && in_array($servicioAdicional->id, old('servicios'))) checked @endif>
                                                    <label class="form-check-label"
                                                        for="servicio_adicional_{{ $servicioAdicional->id }}">
                                                        {{ $servicioAdicional->nombre }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Campo: Horario -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Horario</label>
                            <div class="border p-3 rounded bg-light">
                                @foreach (['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'] as $dia)
                                    <div class="mb-3">
                                        <h6 class="text-primary">Día: {{ $dia }}</h6>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Hora Inicio</th>
                                                    <th>Hora Fin</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @for ($i = 0; $i < 2; $i++)
                                                    {{-- Dos intervalos por día --}}
                                                    <tr>
                                                        <td>
                                                            <input type="time" class="form-control"
                                                                name="horario[{{ strtolower($dia) }}][intervalo][{{ $i }}][hinicio]"
                                                                value="{{ old('horario.' . strtolower($dia) . ".intervalo.$i.hinicio") }}"
                                                                placeholder="Hora Inicio">
                                                        </td>
                                                        <td>
                                                            <input type="time" class="form-control"
                                                                name="horario[{{ strtolower($dia) }}][intervalo][{{ $i }}][hfin]"
                                                                value="{{ old('horario.' . strtolower($dia) . ".intervalo.$i.hfin") }}"
                                                                placeholder="Hora Fin">
                                                        </td>
                                                    </tr>
                                                @endfor
                                            </tbody>
                                        </table>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Campo: Política -->
                        <div class="col-md-6 mb-3">
                            <label for="politica" class="form-label">Política</label>
                            <textarea class="form-control" name="politica" id="politica" rows="6" placeholder="Política">{{ old('politica') }}</textarea>
                        </div>

                        <!-- Campo: Condiciones -->
                        <div class="col-md-6 mb-3">
                            <label for="condiciones" class="form-label">Condiciones</label>
                            <textarea class="form-control" name="condiciones" id="condiciones" rows="6" placeholder="Condiciones">{{ old('condiciones') }}</textarea>
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
