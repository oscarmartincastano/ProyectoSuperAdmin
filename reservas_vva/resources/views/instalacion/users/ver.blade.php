@extends('layouts.admin')

@section('style')
    <style>
        .list-group-item:not(:first-child) {
            border-top: 1px solid rgba(0,0,0,.125);
        }
        .card-title{
            font-size: 16px !important;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 m-b-10">

            <div class="p-l-20 p-r-20 p-b-10 pt-3">
                <div>
                    <h3 class="text-primary no-margin">{{ $user->name }}</h3>
                </div>
            </div>

            <div class="p-t-15 p-b-15 p-l-20 p-r-20">
                <div class="row">
                    <div class="col-lg-5">
                        <div class="card card-default">
                            <div class="card-header  separator">
                                <div class="card-title">Información</div>
                            </div>
                            <div class="card-header border-bottom text-center">
                                <div class="mb-3 mx-auto">
                                    <a href="/{{ request()->slug_instalacion }}/admin/users/{{ $user->id }}/cambiar-foto" style="opacity: 1">
                                        <img class="rounded-circle" src="{{ asset('img/assets/user-default.png') }}" alt="User Avatar" width="110">
                                    </a>
                                </div>
                                <h4 class="mb-0">{{ $user->name }}</a></h4>
                            </div>
                            <div class="card-body p-0">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item px-4"><strong>Email:</strong> {{ $user->email }}</li>
                                    @if ($user->date_birth) <li class="list-group-item px-4"><strong>Fecha de nacimiento:</strong> {{ $user->date_birth }}</li> @endif
                                    @if ($user->tlfno) <li class="list-group-item px-4"><strong>Teléfono:</strong> {{ $user->tlfno }}</li> @endif
                                    @if ($user->cuota) <li class="list-group-item px-4"><strong>Cuota:</strong> {{ $user->cuota }}</li> @endif
                                    <li class="list-group-item px-4"><strong>Fecha de alta:</strong> {{ date('d/m/Y', strtotime($user->aprobado)) }}</li>
                                    <li class="list-group-item px-4"><strong>Baja:</strong> {{ $user->deleted_at ?? 'Cliente activo' }}</li>
                                    @if (request()->slug_instalacion == 'la-guijarrosa' || request()->slug_instalacion == 'santaella')
                                        <li class="list-group-item px-4"><strong>Número de tarjeta:</strong> {{ $user->codigo_tarjeta ?? 'Sin asignar' }}
                                            <button class="btn btn-primary btn-sm buttonModalTarjeta" data-toggle="modal" data-target="#modal-codigo-tarjeta"><i class="fas fa-edit"></i></button>
                                        </li>
                                        <div class="modal fade" id="modal-codigo-tarjeta" tabindex="-1" role="dialog" aria-labelledby="modal-codigo-tarjeta" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <form action="/{{ request()->slug_instalacion }}/admin/users/{{ $user->id }}/update-codigo-tarjeta" method="post" id="updateCodigoTarjeta">
                                                        @csrf
                                                        <input type="hidden" id="slugCodigo" value="{{ request()->slug_instalacion }}">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title mb-2" id="exampleModalLabel">Cambiar número de tarjeta</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <input type="text" class="form-control" name="codigo_tarjeta" placeholder="{{ $user->codigo_tarjeta ?? '' }}" >
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                            <button type="submit" class="btn btn-primary">Guardar</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="card card-default">
                            <div class="card-header  separator">
                                <div class="card-title">Reservas realizadas</div>
                            </div>
                            <div class="card-body">
                                <table class="table table-hover table-condensed table-reservas">
                                    <thead>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Horas</th>
                                            <th>Espacio</th>
                                            <th>Estado de la reserva</th>
                                            {{-- <th>#</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($user->reservas as $item)
                                        {{-- {{dd($item)}} --}}
                                            <tr>
                                                <td>{{ date('d/m/Y', $item->timestamp) }}</td>
                                                <td>{{ \Carbon\Carbon::createFromTimestamp($item->timestamp)->format('H:i') }} - {{ \Carbon\Carbon::createFromTimestamp($item->timestamp)->addMinutes($item->minutos_totales)->format('H:i') }}</td>
                                                @if($item->pista)
                                                    <td>{{ count(auth()->user()->instalacion->deportes) > 1 ? $item->pista->tipo . '.' : '' }} {{ $item->pista->nombre }}</td>
                                                @else
                                                    <td>Pista no existente.</td>
                                                @endif
                                                    <td>
                                                    @if ($item->estado  == 'active')
                                                        @if (strtotime(date('Y-m-d H:i', $item->timestamp) . ' +' . $item->minutos_totales . ' minutes') > strtotime(date('Y-m-d H:i')))
                                                            Pendiente
                                                        @else
                                                            Pasado
                                                        @endif
                                                    @endif
                                                    @if($item->estado == 'canceled')
                                                        <span class="text-danger">Cancelada</span>
                                                    @endif
                                                    @if($item->estado == 'pasado')
                                                        Pasado
                                                    @endif
                                                </td>
                                                {{-- <td>
                                                    @if ($item->estado == 'active' && strtotime(date('Y-m-d H:i', $item->timestamp) . ' +' . $item->minutos_totales . ' minutes') > strtotime(date('Y-m-d H:i')))
                                                        <a class="cancel btn btn-primary text-white" title="Cancelar reserva">
                                                            Acción
                                                        </a>
                                                    @endif
                                                </td> --}}
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card card-default">
                            <div class="card-header  separator">
                                <div class="card-title">Reservas máximas permitidas para este cliente</div>
                            </div>
                            <div class="card-body">
                                <form action="/{{ request()->slug_instalacion }}/admin/users/{{ $user->id }}/update-maximas-reservas" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <div class="border p-3">
                                            @foreach (auth()->user()->instalacion->deportes as $tipo_espacio)
                                                <label for="max_reservas_tipo_espacio[{{ $tipo_espacio }}]">{{ $tipo_espacio }}</label>
                                                <input type="number" class="form-control" name="max_reservas_tipo_espacio[{{ $tipo_espacio }}]" id="max_reservas_tipo_espacio[{{ $tipo_espacio }}]"
                                                    value="{{ unserialize($user->max_reservas_tipo_espacio)[$tipo_espacio] ?? (unserialize(auth()->user()->instalacion->configuracion->max_reservas_tipo_espacio)[$tipo_espacio] ?? '') }}">
                                            @endforeach
                                        </div>
                                    </div>
                                    <input type="submit" value="Editar" class="btn btn-primary">
                                </form>
                            </div>
                        </div>
                    </div>
                    @if(request()->slug_instalacion != 'la-guijarrosa')
                        <div class="col-lg-12">
                            <div class="card card-default">
                                <div class="card-header  separator">
                                    <div class="card-title">Cobros</div>
                                </div>
                                <div class="card-body">
                                    <a href="/{{ request()->slug_instalacion }}/admin/users/{{ request()->id }}/cobro/add" class="btn btn-primary">Añadir cobro</a>
                                    <table class="table table-hover table-condensed">
                                        <thead>
                                            <tr>
                                                <th>Fecha</th>
                                                <th>Concepto</th>
                                                <th>Forma de cobro</th>
                                                <th>Cantidad</th>
                                                <th>#</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($user->cobros as $item)
                                                <tr>
                                                    <td>{{ $item->fecha }}</td>
                                                    <td>{{ $item->concepto }}</td>
                                                    <td>{{ $item->forma }}</td>
                                                    <td>{{ $item->cantidad }} €</td>
                                                    <td><a href="/{{ request()->slug_instalacion }}/admin/cobro/{{ $item->id }}" class="btn btn-primary"><i class="fas fa-edit"></i></a> <a href="/{{ request()->slug_instalacion }}/admin/cobro/{{ $item->id }}/delete" class="btn btn-danger"><i class="fas fa-trash"></i></a></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @else
                            <div class="col-lg-6">
                                <div class="card card-default">
                                    <div class="card-header  separator">
                                        <div class="card-title">Cobros</div>
                                    </div>
                                    <div class="card-body">
                                        <a href="/{{ request()->slug_instalacion }}/admin/users/{{ request()->id }}/cobro/add" class="btn btn-primary">Añadir cobro</a>
                                        <table class="table table-hover table-condensed">
                                            <thead>
                                                <tr>
                                                    <th>Fecha</th>
                                                    <th>Concepto</th>
                                                    <th>Forma de cobro</th>
                                                    <th>Cantidad</th>
                                                    <th>#</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($user->cobros as $item)
                                                    <tr>
                                                        <td>{{ $item->fecha }}</td>
                                                        <td>{{ $item->concepto }}</td>
                                                        <td>{{ $item->forma }}</td>
                                                        <td>{{ $item->cantidad }} €</td>
                                                        <td><a href="/{{ request()->slug_instalacion }}/admin/cobro/{{ $item->id }}" class="btn btn-primary"><i class="fas fa-edit"></i></a> <a href="/{{ request()->slug_instalacion }}/admin/cobro/{{ $item->id }}/delete" class="btn btn-danger"><i class="fas fa-trash"></i></a></td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card card-default">
                                    <div class="card-header  separator">
                                        <div class="card-title">Bonos</div>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-hover table-condensed">
                                            <thead>
                                                <tr>
                                                    <th>Nombre</th>
                                                    <th>Espacio</th>
                                                    <th>Usos</th>
                                                    <th>Precio</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($user->bonosUsuarios as $item)
                                                    <tr>
                                                        <td>{{ $item->bono->nombre }}</td>
                                                        <td>{{ $item->bono->deporte->nombre }}</td>
                                                        <td>{{ $item->num_usos }}</td>
                                                        <td>{{ $item->precio }} €</td>
{{--                                                         <td><a href="/{{ request()->slug_instalacion }}/admin/cobro/{{ $item->id }}" class="btn btn-primary"><i class="fas fa-edit"></i></a> <a href="/{{ request()->slug_instalacion }}/admin/cobro/{{ $item->id }}/delete" class="btn btn-danger"><i class="fas fa-trash"></i></a></td>
 --}}                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endif
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card card-default">
                            <div class="card-header separator">
                                <div class="card-title">Servicios</div>
                            </div>
                            <div class="card-body">
                                <a href="/{{ request()->slug_instalacion }}/admin/users/{{ request()->id }}/servicios/add" class="btn btn-primary">Añadir servicio</a>
                                <table class="table table-hover table-condensed">
                                    <thead>
                                        <tr>
                                            <th>Servicio contratado</th>
                                            <th>Fecha de expiración</th>
                                            <th>Activo</th>
                                            <th>#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($user->servicio_usuario as $item)
                                            <tr>
                                                <td>{!! $item->servicio->nombre !!}</td>
                                                <td>{!! \Carbon\Carbon::parse($item->fecha_expiracion)->format('d-m-Y') !!}</td>
                                                <td>
                                                    @if($item->activo == 'si')
                                                        SI
                                                    @else
                                                        NO
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($item->activo == 'si')
                                                        <!-- Formulario con un botón para actualizar el estado a 'no' -->
                                                        <form action="{{ route('updateActivo', ['slug_instalacion' => request()->slug_instalacion ,'id' => $item->id_usuario, 'servicio_id'=>$item->id_servicio]) }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="btn btn-danger btn-sm">Desactivar</button>
                                                        </form>

                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card card-default">
                            <div class="card-header separator">
                                <div class="card-title">Recibos</div>
                            </div>
                            <div class="card-body">
                                <div> <a href="/{{ request()->slug_instalacion }}/admin/users/{{ request()->id }}/recibo/add" class="btn btn-primary">Crear recibo</a>
                                    <a href="/{{ request()->slug_instalacion }}/admin/users/{{ request()->id }}/borrar-recibos" class="btn btn-danger" onclick="return confirm('¿Estás seguro de que quieres eliminar todos los recibos sin pedido?')">
                                        Eliminar recibos sin pagar
                                    </a></div>
                                <table class="table table-hover table-condensed">
                                    <thead>
                                        <tr>
                                            <th>Servicio contratado</th>
                                            <th>Mes</th>
                                            <th>Año</th>
                                            <th>Precio</th>
                                            <th>Pedido Asociado</th>
                                            <th>Estado</th>
                                            <th>#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $recibos = $user->recibo()->orderBy('created_at', 'desc')->paginate(10);
                                        @endphp
                                        @foreach ($recibos as $item)
                                            <tr>
                                                <td>{!! $item->servicio->nombre !!}</td>
                                                <td>{!! ucfirst(\Carbon\Carbon::parse($item->created_at)->formatLocalized('%B')) !!}</td>
                                                <td>{!! \Carbon\Carbon::parse($item->created_at)->formatLocalized('%Y') !!}</td>
                                                <td>{!! $item->servicio->precio !!}€</td>
                                                <td>{!! $item->pedido_id !!}</td>
                                                <td>{!! $item->estado == 'pagado' || $item->pedido_id ? '<i class="fas fa-check text-success"></i>' : '<i class="fas fa-times text-danger"></i>' !!}</td>
                                                <td style="overflow: visible !important">
                                                    @if($item->pedido_id == "")
                                                        <!-- Botón de Borrar -->
                                                        <a href="/{{ request()->slug_instalacion }}/admin/users/{{ $user->id }}/recibo/{{ $item->id }}/delete" class="btn btn-danger" onclick="return confirm('¿Estás seguro que quieres borrar este recibo?')">
                                                            <i class="fas fa-trash"></i>
                                                        </a>

                                                        <!-- Botón de Editar -->
                                                        <a href="/{{ request()->slug_instalacion }}/admin/users/{{ $user->id }}/recibo/{{ $item->id }}/edit" class="btn btn-primary">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-center mt-3">
                                    {{ $recibos->links() }}
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <p class="small no-margin">
                </p>
            </div>

        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function () {
            $('.buttonModalTarjeta').click(function() {
                $('#modal-codigo-tarjeta').on('shown.bs.modal', function () {
                    $('input[name="codigo_tarjeta"]').trigger('focus');
                });
            });
            $('input[name="codigo_tarjeta"]').on('input', function() {
                $(this).val($(this).val().toUpperCase());
                $(this).val($(this).val().replace(' ', ''));
            });
            // $('.table-reservas').DataTable({
            //     "info": false,
            //     "pageLength": 4,
            //     "lengthChange": false,
            //     language: {
            //         url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            //     },
            //     "order": [[ 4, "desc"], [ 0, "asc"], [ 1, "desc"]]
            // });

            $('#updateCodigoTarjeta').submit(function (e) {
                let codigo_tarjeta = $('input[name="codigo_tarjeta"]').val();

                // Si es santaella no se le da la vuelta
                if ($("#slugCodigo").val() == 'santaella') {
                    return;
                }

                //Este codigo es el correcto 556c7841  y me llega en el val 41786c55, quiero que se muestre como la izquierda van por grupo de dos
                let codigo_tarjeta_correcto = '';
                for (let i = codigo_tarjeta.length - 2; i >= 0; i -= 2) {
                    codigo_tarjeta_correcto += codigo_tarjeta[i] + codigo_tarjeta[i + 1];
                }
                $('input[name="codigo_tarjeta"]').val(codigo_tarjeta_correcto);
            });

        });
    </script>
@endsection
