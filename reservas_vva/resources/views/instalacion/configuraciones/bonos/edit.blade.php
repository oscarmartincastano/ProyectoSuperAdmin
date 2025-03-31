@extends('layouts.admin')

@section('pagename', 'Añadir servicio')

@section('content')
    <div class="row">
        <div class="col-lg-12 m-b-10">

            <div class="p-l-20 p-r-20 p-b-10 pt-3">
                <div>
                    <h3 class="text-primary no-margin">Editar  bono</h3>
                </div>
            </div>

            <div class="p-t-15 p-b-15 p-l-20 p-r-20">
                <div class="card ">
                    <div class="card-header">
                        <div class="card-title">Editar bono</div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('update_bono', [auth()->user()->instalacion->slug, $bono->id]) }}" method="post" role="form" id="formulario">
                            @csrf
                            <div class="form-group row">
                                <label>Nombre</label>
                                <input value="{{ $bono->nombre }}" name="nombre" type="text" placeholder="Nombre..." class="form-control" required>
                            </div>
                            <div class="form-group row">
                                <label>Espacio</label>
                                <select name="espacio" id="espacio" class="form-control">
                                    @foreach (auth()->user()->instalacion->deportes_clases as $item)
                                        <option value="{{ $item->id }}" @if($bono->deporte_id == $item->id) selected @endif>{{ $item->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group row">
                                <label>Precio (€)</label>
                                <input name="precio" value="{{ $bono->precio }}" type="number" placeholder="Precio..." class="form-control" step=".01" required>
                            </div>
                            <div class="form-group row">
                                <label>Usos</label>
                                <input name="usos" value="{{ $bono->num_usos }}" type="number" placeholder="Usos..." class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label>Descripción</label>
                                <div class="quill-wrapper">
                                    <div id="quill">
                                        {!! $bono->descripcion !!}
                                    </div>
                                </div>
                                <input type="hidden" name="contenido" class="contenido">
                            </div>
                            <div class="form-group">
                                <label for="activo">Activo</label>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="activo" name="activo" value="1" {{ $bono->activo == 1 ? 'checked' : '' }}>

                                    <label class="form-check-label" for="activo">Activado</label>
                                </div>
                            </div>

                            <button class="btn btn-primary btn-lg m-b-10 mt-3" type="submit">@if(isset(request()->id)) Editar @else Añadir @endif</button>
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

        $.ajax({
            url:`/@php echo auth()->user()->instalacion->slug; @endphp/admin/configuracion/bonos/deportes/${$('#espacio').val()}`,
            success: data =>{
                $('.select3.select-promo').empty();
                $('#div_promo').show();
                $('.select3.select-promo').append(`<option value="0">Todos</option>`);
                $.each(data, function (i, val) {
                    $('.select3.select-promo').append(`<option value="${val.id}">${val.nombre}</option>`);
                });

            },
            error: er => {
                console.log(er);
            }
        });

        $('form').on('change', '#espacio', function () {
            $.ajax({
                url:`/@php echo auth()->user()->instalacion->slug; @endphp/admin/configuracion/bonos/deportes/${$('#espacio').val()}`,
                success: data =>{
                    $('.select3.select-promo').empty();
                    $('#div_promo').show();
                    $('.select3.select-promo').append(`<option value="0">Todos</option>`);
                    $.each(data, function (i, val) {
                        $('.select3.select-promo').append(`<option value="${val.id}">${val.nombre}</option>`);
                    });

                },
                error: er => {
                    console.log(er);
                }
            });
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

            [{ 'color': [] }, { 'background': [] }],
            [{ 'font': [] }],
            [{ 'align': [] }],

            ['clean']
        ];

        var quill = new Quill('#quill', {
            modules: {
                toolbar: toolbarOptions
            },
            placeholder: 'Describe el servicio',
            theme: 'snow'
        });



        $('#formulario').submit(function (e) {
            $('.contenido').val(quill.root.innerHTML);
        });
    });
</script>
    @endsection

