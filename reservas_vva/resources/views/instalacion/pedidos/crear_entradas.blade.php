@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-lg-12 m-b-10">

            <div class="p-l-20 p-r-20 p-b-10 pt-3">
                <div>
                    <h3 class="text-primary no-margin">Crear entradas</h3>
                </div>
            </div>

            <table class="table table-hover" id="table-reservas" style="width: 100% !important;">
                <thead>
                    <tr>
                        <th>Usuario Id</th>
                        <th>Nombre Participante</th>
                        <th>Tipo entrada</th>
                        <th>Precio</th>
                        <th>Pedido Id</th>
                        <th>#</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pedidoLogNoCorrespondidos as $item)
                        <tr>
                           <td>{{$item->user_id}}</td>
                           <td>{{$item->nombre_participante}}</td>
                           <td>{{$item->tipo_entrada}}</td>
                           <td>{{$item->precio}}</td>
                           <td>{{$item->pedido_id}}</td>
                           <td></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="p-t-15 p-b-15 p-l-20 p-r-20">
                <div class="card ">
                    <div class="card-header">
                        <div class="card-title"></div>
                    </div>
                    <div class="card-body">
                        <form method="post" role="form">
                            @csrf
                            <div class="form-group row">
                                <label>Id Usuario</label>
                                <input name="id_usuario" type="text" placeholder="Id usuario" class="form-control" >
                            </div>
                            <div class="form-group row">
                                <label>Nombre participante</label>
                                <input name="nombre_participante" type="text" placeholder="Nombre participante" class="form-control" >
                            </div>
                            <div class="form-group row">
                                <label>Tipo entrada</label>
                                <input name="tipo_entrada" type="text" placeholder="Tipo Entrada" class="form-control" >
                            </div>
                            {{-- <div class="form-group row">
                                <label>Fecha entrada</label>
                                <select name="tipo_entrada" class="form-control">
                                    <option value="30-12-2023">30-12-2023</option>
                                    <option value="31-12-2023">31-12-2023</option>
                                    <option value="06-01-2024">06-01-2024</option>
                                    <option value="Bono">Bono</option>
                                </select>
                            </div> --}}
                            {{-- <div class="form-group row">
                                <label>Tipo entrada</label>
                                <input name="tipo_entrada" type="text" placeholder="Tipo entrada" class="form-control">
                            </div> --}}
                            {{-- <div class="form-group row">
                                <label>Precio</label>
                                <input name="precio" type="number" placeholder="Precio" class="form-control">
                            </div> --}}
                            <div class="form-group row">
                                <label>Pedido id</label>
                                <input name="id_predido" type="text" placeholder="Id pedido" class="form-control">
                            </div>
                            <button class="btn btn-primary btn-lg m-b-10 mt-3" type="submit">Añadir</button>
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
            $(".select-user").select2({
                placeholder: "Selecciona un usuario"
            });
        });
    </script>
@endsection