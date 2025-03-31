@extends('layouts.mobile')

@section('content')
    <div class="row" style="padding: 3%;">

        <h1 class="title text-center  titulo-pagina" style="color:#203a74; margin-top:20% !important">Reservas de instalaciones deportivas</h1>

    </div>
    <ul class="nav nav-tabs nav-fill mb-3" id="ex1" role="tablist" style="position: fixed; bottom:0; width:100%">
        <li class="nav-item" style="list-style:none">
          <a class="nav-link active" href="#instalaciones-tab" role="tab" data-toggle="tab" style='text-decoration: none;color: black;font-weight: bold;'>Instalaciones</a>
        </li>
        <li class="nav-item" style="list-style:none">
          <a class="nav-link" href="#noticias-tab" role="tab" data-toggle="tab" style='text-decoration: none;color: black;font-weight: bold;'>Noticias</a>
        </li>
      </ul>
    
    <div class="container tab-content">
        <div class="tab-pane fade show active" id="instalaciones-tab" role="tabpanel">
            <div class="search mt-5 mb-5 d-flex ml-3 mr-3">
                <input type="search" class="form-control" name="search" id="search" placeholder="Buscar">
            </div>
            <div class="todosDatos" >
                <div class="d-flex col-sm-12 flex-div">
                    @foreach($instalaciones as $instalacion)
                        <div class="col-sm-6">
                            <div class="card mb-5">
                                <a href="/{{$instalacion->slug}}/" style="text-decoration:none; color: black">
                                    <div class="row justify-content-center">
                                        <div class="col-3 col-md-4" style="text-align: center; margin: auto">
                                            <img src="/img/{{$instalacion->slug}}.png" style="max-height: 50px" />
                                        </div>
                                        <div class="col-9 col-md-8">
                                            <div class="card-body">
                                                <p class="card-text">{!! $instalacion->nombre !!}</p>
                                                <p class="card-text" style="font-size: 12px"><small class="text-muted">({!! $instalacion->direccion !!})</small></p>
                                            </div>
                                        </div>                  
                                    </div>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div id='content' class="searchDatos">
                <div class="d-flex col-sm-12 flex-div">
                </div>
            </div>
        </div>

    <div class="tab-pane fade" id="noticias-tab" role="tabpanel"> 
        <h2 style="margin-top: 20px !important; text-align:center;">Noticias</h2>
            <div class="search mt-5 mb-5 d-flex ml-3 mr-3">
                <div class="d-flex col-sm-12 flex-div">
                    <div class="col-sm-6">
                        <div class="card mb-5">
                            <div class="row justify-content-center">
                                <div class="col-9 col-md-8">
                                    <div class="card-body">
                                        <p class="card-text">Media Maratón</p>
                                        <p class="card-text" style="font-size: 12px"><small class="text-muted">Fecha: 27-09-2022</small></p>
                                    </div>
                                </div>                  
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="card mb-5">
                            <div class="row justify-content-center">
                                <div class="col-9 col-md-8">
                                    <div class="card-body">
                                        <p class="card-text">Torneo de padel</p>
                                        <p class="card-text" style="font-size: 12px"><small class="text-muted">Fecha: 30-09-2022</small></p>
                                    </div>
                                </div>                  
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>

$(document).ready(function () {
    $('#search').on('keyup',function(){
        
        $value=$(this).val();

        if($value){
            $('.todosDatos').hide();
            $('.searchDatos').show();
        }else{
            $('.todosDatos').show();
            $('.searchDatos').hide();
        }

        $.ajax({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "GET",
            url: "{{route('search')}}",
            data: {'search':$value},
           
            success: function (data) {
                if(data == ""){
                    $('.flex-div').html(`<p style="color:red; text-align:center;font-size:18px;">No se han encontrado resultados.</p>`);
                }else{
                    $('.flex-div').html(data);
                }
            }
        });

    })
});


</script>



@endsection