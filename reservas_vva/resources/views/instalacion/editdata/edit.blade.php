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
                                    <input name="{{ request()->tipo }}" type="file" placeholder="Logo..." class="form-control" required>
                                @elseif(request()->tipo == 'galeria')
                                    <div class="ml-2">(Máximo 3 imágenes)</div>
                                    <div class="border col-12 p-2 d-flex">
                                        @if (file_exists(public_path() . '/img/galerias/'. $instalacion["slug"]))

                                            @foreach (\File::files(public_path() . '/img/galerias/'. $instalacion["slug"]) as $item)
                                                <div class="position-relative border p-2 mr-2">
                                                    <img src="/img/galerias/{{ $instalacion["slug"] }}/{{pathinfo($item)['basename']}}"
                                                        style="width: 75px">
                                                    <a href="/{{ request()->slug_instalacion }}/admin/configuracion/instalacion/edit/galeria/delete/{{ pathinfo($item)['filename'] }}"  class="close" style="position: absolute;right: 3px;top: 0;" onclick="return confirm('¿Quieres eliminar esta imagen de tu galería?')">
                                                        <span class="button_cross"><i class="fas fa-times"></i></span>
                                                    </a>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>

                                    @if(file_exists(public_path() . '/img/galerias/'.$instalacion["slug"]) && count(\File::files(public_path() . '/img/galerias/'. $instalacion["slug"]))==3)
                                    <div class="w-100 border p-2">No puedes añadir más imágenes porque tienes ya 3. Borra alguna para añadir una nueva.</div>
                                    @else
                                    <input name="{{ request()->tipo }}" type="file" placeholder="Galería..." class="form-control mt-1" accept="image/*"  required>
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
                                @elseif(request()->tipo == 'horario')
                                    <div class="col-md-12">
                                        <ul class="list-group group-horario">
                                            <li class="list-group-item">
                                                <div>Lunes</div>
                                                <div>
                                                    <div class="d-flex align-items-center" data-index="0">
                                                        <div class="form-group form-group-default m-0">
                                                            <label>Hora inicio</label>
                                                            <input type="time" style="width: 180px" name="horario[Lunes][intervalo][0][hinicio]" placeholder="Default input" class="form-control">
                                                        </div>
                                                        <div class="form-group form-group-default m-0">
                                                            <label>Hora fin</label>
                                                            <input type="time" style="width: 180px" name="horario[Lunes][intervalo][0][hfin]" placeholder="Default input" class="form-control">
                                                        </div>
                                                        <div class="ml-2"><a href="#" class="btn btn-rounded btn-drop-horario"><i class="fas fa-times"></i></a></div>
                                                    </div>
                                                    <div class="mt-2"><a href="#" class="btn btn-add-horario" data-dia="Lunes" >Añadir horario</a></div>
                                                </div>
                                            </li>
                                            <li class="list-group-item">
                                                <div>Martes</div>
                                                <div>
                                                    <div class="d-flex align-items-center" data-index="0">
                                                        <div class="form-group form-group-default m-0">
                                                            <label>Hora inicio</label>
                                                            <input type="time" style="width: 180px" name="horario[Martes][intervalo][0][hinicio]" placeholder="Default input" class="form-control">
                                                        </div>
                                                        <div class="form-group form-group-default m-0">
                                                            <label>Hora fin</label>
                                                            <input type="time" style="width: 180px" name="horario[Martes][intervalo][0][hfin]" placeholder="Default input" class="form-control">
                                                        </div>
                                                        <div class="ml-2"><a href="#" class="btn btn-rounded btn-drop-horario"><i class="fas fa-times"></i></a></div>
                                                    </div>
                                                    <div class="mt-2"><a href="#" class="btn btn-add-horario" data-dia="Martes" >Añadir horario</a></div>
                                                </div>
                                            </li>
                                            <li class="list-group-item">
                                                <div>Miércoles</div>
                                                <div>
                                                    <div class="d-flex align-items-center" data-index="0">
                                                        <div class="form-group form-group-default m-0">
                                                            <label>Hora inicio</label>
                                                            <input type="time" style="width: 180px" name="horario[Miércoles][intervalo][0][hinicio]" placeholder="Default input" class="form-control">
                                                        </div>
                                                        <div class="form-group form-group-default m-0">
                                                            <label>Hora fin</label>
                                                            <input type="time" style="width: 180px" name="horario[Miércoles][intervalo][0][hfin]" placeholder="Default input" class="form-control">
                                                        </div>
                                                        <div class="ml-2"><a href="#" class="btn btn-rounded btn-drop-horario"><i class="fas fa-times"></i></a></div>
                                                    </div>
                                                    <div class="mt-2"><a href="#" class="btn btn-add-horario" data-dia="Miércoles" >Añadir horario</a></div>
                                                </div>
                                            </li>
                                            <li class="list-group-item">
                                                <div>Jueves</div>
                                                <div>
                                                    <div class="d-flex align-items-center" data-index="0">
                                                        <div class="form-group form-group-default m-0">
                                                            <label>Hora inicio</label>
                                                            <input type="time" style="width: 180px" name="horario[Jueves][intervalo][0][hinicio]" placeholder="Default input" class="form-control">
                                                        </div>
                                                        <div class="form-group form-group-default m-0">
                                                            <label>Hora fin</label>
                                                            <input type="time" style="width: 180px" name="horario[Jueves][intervalo][0][hfin]" placeholder="Default input" class="form-control">
                                                        </div>
                                                        <div class="ml-2"><a href="#" class="btn btn-rounded btn-drop-horario"><i class="fas fa-times"></i></a></div>
                                                    </div>
                                                    <div class="mt-2"><a href="#" class="btn btn-add-horario" data-dia="Jueves" >Añadir horario</a></div>
                                                </div>
                                            </li>
                                            <li class="list-group-item">
                                                <div>Viernes</div>
                                                <div>
                                                    <div class="d-flex align-items-center" data-index="0">
                                                        <div class="form-group form-group-default m-0">
                                                            <label>Hora inicio</label>
                                                            <input type="time" style="width: 180px" name="horario[Viernes][intervalo][0][hinicio]" placeholder="Default input" class="form-control">
                                                        </div>
                                                        <div class="form-group form-group-default m-0">
                                                            <label>Hora fin</label>
                                                            <input type="time" style="width: 180px" name="horario[Viernes][intervalo][0][hfin]" placeholder="Default input" class="form-control">
                                                        </div>
                                                        <div class="ml-2"><a href="#" class="btn btn-rounded btn-drop-horario"><i class="fas fa-times"></i></a></div>
                                                    </div>
                                                    <div class="mt-2"><a href="#" class="btn btn-add-horario" data-dia="Viernes" >Añadir horario</a></div>
                                                </div>
                                            </li>
                                            <li class="list-group-item">
                                                <div>Sábado</div>
                                                <div>
                                                    <div class="d-flex align-items-center" data-index="0">
                                                        <div class="form-group form-group-default m-0">
                                                            <label>Hora inicio</label>
                                                            <input type="time" style="width: 180px" name="horario[Sábado][intervalo][0][hinicio]" placeholder="Default input" class="form-control">
                                                        </div>
                                                        <div class="form-group form-group-default m-0">
                                                            <label>Hora fin</label>
                                                            <input type="time" style="width: 180px" name="horario[Sábado][intervalo][0][hfin]" placeholder="Default input" class="form-control">
                                                        </div>
                                                        <div class="ml-2"><a href="#" class="btn btn-rounded btn-drop-horario"><i class="fas fa-times"></i></a></div>
                                                    </div>
                                                    <div class="mt-2"><a href="#" class="btn btn-add-horario" data-dia="Sábado" >Añadir horario</a></div>
                                                </div>
                                            </li>
                                            <li class="list-group-item">
                                                <div>Domingo</div>
                                                <div>
                                                    <div class="d-flex align-items-center" data-index="0">
                                                        <div class="form-group form-group-default m-0">
                                                            <label>Hora inicio</label>
                                                            <input type="time" style="width: 180px" name="horario[Domingo][intervalo][0][hinicio]" placeholder="Default input" class="form-control">
                                                        </div>
                                                        <div class="form-group form-group-default m-0">
                                                            <label>Hora fin</label>
                                                            <input type="time" style="width: 180px" name="horario[Domingo][intervalo][0][hfin]" placeholder="Default input" class="form-control">
                                                        </div>
                                                        <div class="ml-2"><a href="#" class="btn btn-rounded btn-drop-horario"><i class="fas fa-times"></i></a></div>
                                                    </div>
                                                    <div class="mt-2"><a href="#" class="btn btn-add-horario" data-dia="Domingo" >Añadir horario</a></div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>

                                @elseif(request()->tipo == 'servicios')
                                    <div class="border p-2 w-100">
                                        @foreach (\App\Models\Servicios_adicionales::all() as $item)
                                        <div class="w-100">
                                            <div class="form-check form-check-inline switch mb-4">
                                                <input class="form-check-input" type="checkbox" value="{{ $item->id }}" id="servicio{{ $item->id }}" name="servicios[]" >
                                                <label class="form-check-label" for="servicio{{ $item->id }}">
                                                    {{ $item->nombre }}
                                                </label>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                @elseif(request()->tipo == 'prefijo_pedido')
                                    <input value="{{ $instalacion[request()->tipo] }}" name="{{ request()->tipo }}" type="text" placeholder="{{ request()->tipo == 'tlfno' ? 'Teléfono' : ucfirst(request()->tipo) }}..." class="form-control" minlength="4" maxlength="4" required>
                                @elseif(request()->tipo == "tipo_reservas")
                                <div class="border p-2 w-100">
                                    @foreach (\App\Models\Tipo_reservas::all() as $item)
                                    <div class="w-100">
                                        <div class="form-check form-check-inline switch mb-4">
                                            <input class="form-check-input" type="radio" value="{{ $item->id }}" id="tipo_reservas{{ $item->id }}" name="tipo_reservas_id" {{$item->id == $instalacion["tipo_reservas_id"] ? "checked" : ""}} >
                                            <label class="form-check-label" for="tipo_reservas{{ $item->id }}">
                                                {{ $item->nombre }}
                                            </label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @else
                                    <input value="{{ $instalacion[request()->tipo] }}" name="{{ request()->tipo }}" type="text" placeholder="{{ request()->tipo == 'tlfno' ? 'Teléfono' : ucfirst(request()->tipo) }}..." class="form-control" required>
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
                placeholder: 'Html de normas...',
                theme: 'snow'
            });

            if ($('.html_normas').length) {
                $('#form-normas').submit(function (e) {
                    $('.html_normas').val(quill.root.innerHTML);
                });
            }

            $('form').on('click', '.btn-add-horario', function(e) {
                e.preventDefault();
                let index = !isNaN($(this).parent().prev().data('index')) ? $(this).parent().prev().data('index') + 1 : 0;

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
