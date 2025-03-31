@extends('layouts.admin')

@section('content')

<div class="row">
    <div class="col-lg-12 m-b-10">
        
            <div class="p-l-20 p-r-20 p-b-10 pt-3">
                <div>
                    <h3 class="text-primary no-margin">Devoluciones</h3>
                </div>
            </div>
            
            <div class="p-t-15 p-b-15 p-l-20 p-r-20">
                <div class="card ">
                    <div class="card-header">
                        <div class="card-title">Listado de devoluciones</div>
                    </div>
                    <div class="card-body">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Instalación</th>
                                    <th>Pedido</th>
                                    <th>Fecha pago</th>
                                    <th>Coste</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pedidos as $item)
                                    <tr>
                                        @if(substr($item->id, 0,4) == "vvac")
                                            <td>Villanueva de Córdoba</td>
                                        @elseif(substr($item->id, 0,4) == "vfdc")
                                            <td>Villafranca de Córdoba</td>
                                        @endif
                                        <td data-sort="{{ strtotime($item->created_at) }}">{{$item->id}}</td>
                                        <td>{{ strftime('%d %B', strtotime($item->created_at)) }}</td>
                                        <td>{{$item->amount}}€</td>
                                        <td>
                                            @if($item->estado == 'Devolucion pendiente')
                                                    <a href="#" data-id="{{ $item->id }}" class="btn btn-outline-warning btn-devolver">DEVOLVER</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <p class="small no-margin">
                </p>
            </div>
    </div>
</div>
<div class="modal fade slide-up disable-scroll" id="modal-devolucion" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content-wrapper">
        <div class="modal-content">
          <div class="modal-header clearfix text-left">
            <button aria-label="" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-icon">close</i>
            </button>
          </div>
          <div class="modal-body">
            <form role="form" method="POST" action="#">
                @csrf
                <div id="contenido_importe">
                    <div class="form-group mb-2 mt-2">
                        <p style="font-size: 16px">Importe a devolver</p>
                    </div>
                    <div class="form-group mb-2 mt-2" >
                        <input type="number" step=0.01 name="importe_devolucion" id="importe_devolucion" placeholder="Importe" style="width:55%;">
                    </div>
                </div>
            </div>
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-success" id="btn-devol">Devolución</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function () {
        $('.btn-devolver').click(function (e) { 
            e.preventDefault();
            $('#modal-devolucion').modal('show').find('form').attr('action', `/manager/devoluciones/${$(this).data('id')}/devolver`);
        });
    });
</script>
@endsection