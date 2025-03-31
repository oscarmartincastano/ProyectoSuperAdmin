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
                    <h3 class="text-primary no-margin">Participante #{{ $participante->id }}</h3>
                </div>
            </div>

            <div class="p-t-15 p-b-15 p-l-20 p-r-20">
                <div class="card ">
                    <div class="card-header">
                        <div class="card-title">Editar participante</div>
                    </div>
                    <div class="card-body">
                        <form id="form-mail" method="post" role="form" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id_instalacion" value="{{ auth()->user()->id_instalacion }}">
                            
                            @foreach ($participante->valores_campos_personalizados as $item)
                                <div class="form-group">
                                    <label for="#">{{ $item->campo->label }}</label>
                                    @if ($item->tipo == 'textarea')
                                        <textarea class="form-control" data-campo="{{ $item->campo->id }}" name="campo_adicional[{{ $item->id }}]" rows="3" {{ $item->campo->required ? 'required' : '' }}>{{ $item->valor }}</textarea>
                                    @elseif($item->tipo == 'select')
                                        <select class="form-control select_adicional" data-campo="{{ $item->id }}" name="campo_adicional[{{ $item->id }}]">
                                            @foreach (unserialize($item->campo->opciones) as $option)
                                                
                                                <option @if($option['texto'] == $item->valor) selected @endif data-value="{{ $option['texto'] }}"  data-precio_extra_opcion={{ $option['pextra'] }} value="{{ $option['texto'] }}">{{ $option['texto'] }}</option>
                                            @endforeach
                                        </select>
                                    @else
                                        <input value="{{ $item->valor }}" type="{{ $item->campo->tipo }}" data-campo="{{ $item->campo->id }}" name="campo_adicional[{{ $item->id }}]" class="form-control" placeholder="{{ $item->campo->label }}" {{ $item->campo->required ? 'required' : '' }}>
                                    @endif
                                </div>
                            @endforeach

                            <button class="btn btn-primary btn-lg m-b-10 mt-3 w-100" type="submit">
                                Editar participante
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
