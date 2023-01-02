@extends('voyager::master')

@section('page_title', 'Viendo Registro de Servicios')

@section('page_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body" style="padding: 0px">
                        <div class="col-md-6" style="padding: 0px">
                            <h1 id="subtitle" class="page-title">
                                <i class="voyager-basket"></i> Servicios
                            </h1>
                        </div>
                        <div class="col-md-6 text-right" style="margin-top: 30px">
                            @if (auth()->user()->hasPermission('add_clients') && ! auth()->user()->hasRole('admin'))
                                <a href="#" data-toggle="modal" data-target="#producto_modal" class="btn btn-dark">
                                    <i class="fa-solid fa-tags"></i> <span>Vender Productos</span>
                                </a>
                                <a href="#" data-toggle="modal" data-target="#registar_modal" class="btn btn-success">
                                    <i class="fa-solid fa-clipboard-user"></i> <span>Atender Cliente</span>
                                </a>
                                <a href="#" title="Nuevo cliente" data-target="#modal-create-customer" data-toggle="modal" class="btn btn-primary">
                                    <i class="fa-solid fa-person-circle-plus"></i> <span>Persona</span>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="page-content browse container-fluid">
        @include('voyager::alerts')
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">   
                        <div class="table-responsive">
                            <table id="dataTable" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th style="text-align: center">Id</th>
                                        <th style="text-align: center">Cliente</th>
                                        <th style="text-align: center">Servicios</th>
                                        <th style="text-align: center">Detalles</th>
                                        <th style="text-align: center">Monto</th>
                                        <th style="text-align: center">Deuda</th>
                                        <th style="text-align: center">Estado</th>                                        
                                        <th style="text-align: center">Registrado</th>                                        
                                        <th style="text-align: right">Acciones</th>
                                    </tr>
                                </thead>
                                @php
                                    // dd($cashier);
                                @endphp
                                <tbody>
                                    @forelse ($client as $item)
                                        <tr>
                                            
                                            <td style="text-align: center">{{ $item->id }}</td>                                            
                                            <td>
                                                @if ($item->people_id)                                                    
                                                    <table>                                                    
                                                        @php
                                                            $image = asset('images/default.jpg');
                                                            if($item->people->photo){
                                                                $image = asset('storage/'.str_replace('.', '-cropped.', $item->people->photo));
                                                            }
                                                            $now = \Carbon\Carbon::now();
                                                            $birthdate = new \Carbon\Carbon($item->people->birthdate);
                                                            $age = $birthdate->diffInYears($now);
                                                        @endphp
                                                                <img src="{{ $image }}" alt="{{ $item->people->first_name }} {{ $item->people->last_name }}" style="width: 60px; height: 60px; border-radius: 30px; margin-right: 10px">
                                                        
                                                                {{ $item->people->first_name }} {{ $item->people->last_name }}
                                                    </table>
                                                @else
                                                    <img src="{{ asset('images/icono-anonimato.png') }}" alt="" style="width: 60px; height: 60px; border-radius: 30px; margin-right: 10px">
                                                @endif

                                            </td>
                                            <td style="text-align: center">{{ $item->service_id ? $item->service->name:'Productos' }}</td>
                                            <td style="text-align: center">
                                                @if ( $item->plan)
                                                    Plan: {{ $item->plan->name}}
                                                    <br> 
                                                    @if ($item->plan_id != 4)
                                                        <b>{{date('d/m/Y', strtotime($item->start))}} Hasta {{date('d/m/Y', strtotime($item->finish))}}</b>
                                                    @else
                                                        Dia: <small><b>{{ $item->day->name }}</b></small>
                                                    @endif        
                                                    <br>
                                                    <b>Turno: 
                                                        @if($item->hour == 1)
                                                            Mañana
                                                        @endif
                                                        @if($item->hour == 2)
                                                            Tarde
                                                        @endif
                                                        @if($item->hour == 3)
                                                            Noche
                                                        @endif
                                                    </b>  
                                                @else
                                                    @php
                                                        $articles = \DB::table('items as i')
                                                            ->join('wherehouse_details as w', 'w.id', 'i.wherehouseDetail_id')
                                                            ->join('articles as a', 'a.id', 'w.article_id')
                                                            ->where('i.client_id', $item->id)->where('i.deleted_at', null)
                                                            ->select('a.name','a.presentation', DB::raw("SUM(i.item) as cant"), DB::raw("SUM(i.amount) as money"))
                                                            ->groupBy('i.indice')->get();
                                                    @endphp
                                                    @foreach ($articles as $ar)
                                                        <small><b>{{$ar->name}}-{{$ar->presentation}}</b></small>
                                                        <br>
                                                        <small><b>Cantidad: {{$ar->cant}}    Venta:{{$ar->money}}</b></small>
                                                        <br><br><br>
                                                    @endforeach
                                                @endif
                                                                                     
                                            </td>
                                            <td style="text-align: center">{{ $item->amount }}</td> 
                                            <td style="text-align: center">
                                                @if ($item->amount == $item->subAmount)
                                                    <label class="label label-success">Pagado</label>
                                                @else
                                                    <label class="label label-warning">{{$item->amount - $item->subAmount}}</label>
                                                @endif
                                            </td>
                                            <td style="text-align: center">
                                                @if ($item->status)
                                                    <label class="label label-success">Vigente</label>
                                                @else
                                                    <label class="label label-warning">Finalizado</label>
                                                @endif
                                            </td>
                                            <td style="text-align: center">{{date('d/m/Y H:i:s', strtotime($item->created_at))}}<br><small>{{\Carbon\Carbon::parse($item->created_at)->diffForHumans()}}.</small></td>
                                            <td style="text-align: right">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                                        Más <span class="caret"></span>
                                                    </button>
                                                  
                                                    <ul class="dropdown-menu" role="menu">
                                                        @if ($item->amount != $item->subAmount && !auth()->user()->hasRole('admin'))
                                                            <li>
                                                                <a href="#" data-toggle="modal" data-target="#payment-modal" data-item='@json($item)' title="Pagar" class="btn-payment">
                                                                    <i class="voyager-dollar"></i> <span class="hidden-xs hidden-sm">Pagar</span>
                                                                </a>
                                                            </li>
                                                        @endif
                                                        <li>
                                                            <a href="" target="_blank" title="Imprimir">
                                                                <i class="glyphicon glyphicon-print"></i> <span class="hidden-xs hidden-sm">Imprimir</span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                                @if ( !auth()->user()->hasRole('admin'))                                                    
                                                    {{-- @if ($item->status && $item->cashier->status == "abierta" && $cashier->id == $item->cashier_id)
                                                        <a href="" title="Editar" class="btn btn-sm btn-primary" data-item="{{ $item}}" data-toggle="modal" data-target="#edit_modal">
                                                            <i class="voyager-edit"></i> <span class="hidden-xs hidden-sm">Editar</span>
                                                        </a>
                                                    @endif --}}
                                                    <br>
                                                    @if ($item->status && $item->cashier->status == "abierta" && $cashier->id == $item->cashier_id)
                                                        <button title="Borrar" class="btn btn-sm btn-danger delete" onclick="deleteItem('{{ route('clients.destroy', ['client' => $item->id]) }}')" data-toggle="modal" data-target="#delete-modal">
                                                            <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Borrar</span>
                                                        </button>
                                                    @endif
                                                @endif
                                                <a href="#" data-toggle="modal" data-target="#show-modal" data-item='@json($item)' data-user="{{$item->user->name}}" title="Ver" class="btn btn-sm btn-warning view">
                                                    <i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">Ver</span>
                                                </a>

                                                
                                            </td>
                                            
                                        </tr>
                                    @empty
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- vault add register producto modal --}}
    <form id="form-search" action="{{ route('clients-article.store') }}" method="post">
        @csrf
        <input type="hidden" name="cashier_id" value="{{ $cashier? $cashier->id:'' }}">
        <div class="modal fade" id="producto_modal" role="dialog">
            <div class="modal-dialog modal-primary modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="fa-solid fa-tags"></i> Registrar Servicios</h4>
                    </div>
                    @if (!$cashier)
                        <div class="alert alert-warning">
                            <strong>Advertencia:</strong>
                            <p>No puedes registrar un servicio debido a que no existe un registro de caja activo.</p>
                        </div>
                    @endif
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <small>Cliente.</small>
                                    <select name="people_id" id="select_people" class="form-control"></select>
                                    
                                </div>
                            </div>  
                            
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <small>Articulo.</small>
                                    <select id="article_id" class="form-control select2">
                                        <option value="">Seleccione una categoria..</option>
                                        @foreach ($article as $item)
                                            <option data-item='@json($item)'>{{$item->name}} - {{$item->presentation}} {{$item->cant}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>  
                        <div id="dataTable" class="table-responsive">
                            <div class="col-md-12" style="margin-top: 20px" id="div-empty-list">
                                <h4 class="text-center text-muted" style="margin-top: 50px">
                                    <i class="glyphicon glyphicon-shopping-cart" style="font-size: 50px"></i> <br><br>
                                    Lista de venta vacía
                                </h4>
                            </div>
                            <table class="table" style="display: none" id="table-list">
                                <thead>
                                    <th>Artículo</th>
                                    <th style="width: 110px">Stock Disponible</th>
                                    <th style="width: 110px">Precio Unitario</th>
                                    <th style="width: 110px">Cant a Vender</th>
                                    <th style="width: 110px">Ganancia Unitaria</th>
                                    {{-- <th style="width: 110px">Venta crédito</th> --}}
                                    {{-- <th @if (setting('ventas.precios_credito') != 2) style="display: none" @else style="width: 100px" @endif >Venta crédito</th> --}}
                                    <th style="width: 20px"></th>
                                </thead>
                                <tbody id="table-detalle"></tbody>
                                <tfoot>
                                    <td colspan="4" style="text-align: left">Total</td>
                                    <td colspan="1" style="text-align: right"><h4 id="total">Bs. 0.00</h4></td>
                                    <td colspan="1" style="text-align: left"></td>
                                </tfoot>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <small class="control-label">Pago al Credito</small>
                                    <span class="voyager-question text-info pull-left" data-toggle="tooltip" data-placement="left" title="Seleccione si el pago es al credito."></span>
                                    <input 
                                        type="checkbox" 
                                        name="credits"
                                        id="toggleswitchs" 
                                        onclick="myFunctions()"
                                        data-on="Si" 
                                        data-off="No" 
                                    >
                                </div>
                            </div>
                            <div class="col-sm-3"id="texts" style="display:none">
                                <div class="form-group">
                                    <small>Monto Recibido.</small>
                                    <input type="number" name="subAmount" id="input-subAmount" min="0" step="0.1" class="form-control" placeholder="Monto recibo Bs.">

                                </div>
                            </div>                               
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        @if ($cashier)
                            <button type="submit" class="btn btn-success" id="btn_vender">Vender</button>
                        @endif                        
                    </div>
                </div>
            </div>
        </div>
    </form>

    {{-- vault add register modal --}}
    <form id="form-search" action="{{ route('clients.store') }}" method="post">
        @csrf
        <input type="hidden" name="cashier_id" value="{{ $cashier? $cashier->id:'' }}">
        <div class="modal fade" id="registar_modal" role="dialog">
            <div class="modal-dialog modal-success modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="fa-solid fa-dumbbell"></i> Registrar Servicios</h4>
                    </div>
                    @if (!$cashier)
                        <div class="alert alert-warning">
                            <strong>Advertencia:</strong>
                            <p>No puedes registrar un servicio debido a que no existe un registro de caja activo.</p>
                        </div>
                    @endif
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <small>Tipo de Servicios.</small>
                                    <select name="service_id"  class="form-control select2" required>
                                        <option value="">Seleccione un servicio</option>
                                        @foreach ($service as $item)
                                            <option value="{{$item->id}}" >{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>  
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <small>plan.</small>
                                    <select name="plan_id" id="plan_id" class="form-control select2" required>
                                        <option value="">Seleccione un plan</option>
                                        @foreach ($plan as $item)
                                                <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>  
                            <div class="col-sm-3" id="div_1">
                               
                            </div>  
                            <div class="col-sm-3" id="div_2">
                             
                            </div> 
                        </div>
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <small>Turno.</small>
                                    <select name="hour" class="form-control select2" required>
                                        <option value="">Seleccione un turno</option>
                                        <option value="1">Mañana</option>
                                        <option value="2">Tarde</option>
                                        <option value="3">Noche</option>
                                    </select>
                                </div>
                            </div>  
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <small>Cliente.</small>
                                    <select name="people_id" id="select_people1" class="form-control"></select>
                                    
                                </div>
                            </div>   
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <small>Monto Total.</small>
                                    <input type="number" class="form-control" placeholder="Monto Total a Pagar" min="0" step="0.1" name="amount">
                                </div>
                            </div>                    
                        </div>                 
                        


                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <small class="control-label">Pago al Credito</small>
                                    <span class="voyager-question text-info pull-left" data-toggle="tooltip" data-placement="left" title="Seleccione si el pago es al credito."></span>
                                    <input 
                                        type="checkbox" 
                                        name="credit"
                                        id="toggleswitch" 
                                        onclick="myFunction()"
                                        data-on="Si" 
                                        data-off="No" 
                                    >
                                </div>
                            </div>
                            <div class="col-sm-3"id="text" style="display:none">
                                <div class="form-group">
                                    <small>Monto Recibido.</small>
                                    <input type="number" name="subAmount" id="input-subAmount" min="0" step="0.1" class="form-control" value="0" placeholder="Monto recibo Bs.">

                                </div>
                            </div>                               
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        @if ($cashier)
                            <button type="submit" class="btn btn-success">Registrar Servicio</button>
                        @endif                        
                    </div>
                </div>
            </div>
        </div>
    </form>


        {{-- Payment modal --}}
        <form action="{{ route('clients-adition.store') }}" id="form-payment" method="POST">
            @csrf
            <div class="modal modal-primary fade" tabindex="-1" id="payment-modal" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title"><i class="voyager-dollar"></i> Agregar pago</h4>
                        </div>
                        @if (!$cashier)
                            <div class="alert alert-warning">
                                <strong>Advertencia:</strong>
                                <p>No puedes registrar un servicio debido a que no existe un registro de caja activo.</p>
                            </div>
                        @endif
                        <div class="modal-body">
                            <input type="hidden" name="cashier_id" value="{{ $cashier? $cashier->id:'' }}">
                            <input type="hidden" name="client_id" id="client_id">
                            <div class="form-group">
                                <label for="subAmount">Monto</label>
                                <input type="number" class="form-control" name="subAmount" min="1" step="0.1" placeholder="Monto" required>
                            </div>
                            {{-- <div class="form-group">
                                <label for="next_payment">Fecha del siguiente pago</label>
                                <input type="date" class="form-control" name="next_payment" min="{{ date('Y-m-d') }}" >
                            </div> --}}
                            <div class="form-group">
                                <label for="observation">Observaciones</label>
                                <textarea class="form-control" name="observation" placeholder="Observaciones" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                            {{-- <input type="submit" class="btn btn-dark delete-confirm" value="Pagar"> --}}
                            @if ($cashier)
                                <button type="submit" class="btn btn-dark">Pagar</button>
                            @endif 
                        </div>
                    </div>
                </div>
            </div>
        </form>

    {{-- editar --}}
    <form id="form-search" action="{{ route('clients.update') }}" method="post">
        @csrf
        
        <div class="modal fade" tabindex="-1" id="edit_modal" role="dialog">
            <div class="modal-dialog modal-primary modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="fa-solid fa-dumbbell"></i> Registrar Servicios</h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="id">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <small>Tipo de Servicios.</small>
                                    <select name="service_id" id="service_id" class="form-control select2" required>
                                        <option value="">Seleccione un servicio</option>
                                        @foreach ($service as $item)
                                            <option value="{{$item->id}}" >{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>  
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <small>plan.</small>
                                    <select name="plan_id" id="plan_id1" class="form-control select2" required>
                                        <option value="">Seleccione un plan</option>
                                        @foreach ($plan as $item)
                                                <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>  
                            <div class="col-sm-3" id="divs_1">
                               
                            </div>  
                            <div class="col-sm-3" id="divs_2">
                             
                            </div> 
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <small>Turno.</small>
                                    <select name="hour" id="hour" class="form-control select2" required>
                                        <option value="">Seleccione un turno</option>
                                        <option value="1">Mañana</option>
                                        <option value="2">Tarde</option>
                                        <option value="3">Noche</option>
                                    </select>
                                </div>
                            </div>  
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <small>Cliente.</small>
                                    <select name="people_id" id="people_id" class="form-control select2" required>
                                        <option value="">Seleccione una ppersona</option>
                                        @foreach ($people as $item)
                                            <option value="{{$item->id}}">{{$item->first_name}} {{$item->last_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>  
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <small>Monto Bs.</small>
                                    <input type="number" class="form-control" name="amount" id="amount">
                                </div>
                            </div>  
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Registrar Servicio</button>                   
                    </div>
                </div>
            </div>
        </div>
    </form>


    <div class="modal modal-danger fade" tabindex="-1" id="delete-modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="voyager-trash"></i> Desea eliminar el siguiente registro?</h4>
                </div>
                <div class="modal-body">

                    <div class="text-center" style="text-transform:uppercase">
                        <i class="voyager-trash" style="color: red; font-size: 5em;"></i>
                        <br>                        
                        <p><b>Desea eliminar el siguiente registro?</b></p>
                    </div>
                </div>  
                <div class="modal-footer">
                    <form action="#" id="delete_form" method="POST">
                        {{ method_field('DELETE') }}
                        {{ csrf_field() }}
                        <input type="submit" class="btn btn-danger pull-right delete-confirm" value="Sí, eliminar">
                    </form>
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>




    {{-- para ver el detalle de cada servicio o venta de articulo --}}
    <div class="modal fade" tabindex="-1" id="show-modal" role="dialog">
        <div class="modal-dialog modal-warning modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="voyager-basket"></i> Detalles de la Venta</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6" style="margin-bottom:0;">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Cliente</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p id="label-customer">Value</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6" style="margin-bottom:0;">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Registrado por</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p id="label-user">Value</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6" style="margin-bottom:0;">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Fecha de venta</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p id="label-date">Value</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6" style="margin-bottom:0;">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Total</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p id="label-total">Value</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        {{-- <div class="col-md-6" style="margin-bottom:0;">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Creado el</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p id="label-created_at">Value</p>
                            </div>
                            <hr style="margin:0;">
                        </div> --}}
                        <div class="col-md-6" style="margin-bottom:0;">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Estado</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p id="label-status">Value</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        {{-- <div class="col-md-12">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Observaciones</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p id="label-observations">Value</p>
                            </div>
                            <hr style="margin:0;">
                        </div> --}}
                        <div class="col-md-12">
                            <table id="detalle" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th colspan="6" class="text-center">Detalles de venta</th>
                                    </tr>
                                    <tr>
                                        <th>N&deg;</th>
                                        <th>Servicio</th>
                                        <th>Detalles</th>
                                        <th>Cantidad</th>
                                        <th>Precio</th>
                                        <th class="text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                        <div class="col-md-12">
                            <table id="detallepago" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th colspan="4" class="text-center">Detalles de pagos</th>
                                    </tr>
                                    <tr>
                                        <th>N&deg;</th>
                                        {{-- <th>Registrado por</th> --}}
                                        <th>Fecha</th>
                                        <th>Observaciones</th>
                                        <th class="text-right">Monto</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-right"><b>PAGO TOTAL</b></td>
                                        <td class="text-right"><b style="font-size: 18px" id="label-total-payment">0,00 Bs.</b></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-right"><b>DEUDA TOTAL</b></td>
                                        <td class="text-right"><b style="font-size: 18px" id="label-debt">0,00 Bs.</b></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">cerrar</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal crear cliente --}}
    <form action="{{route('people.store')}}" id="form-create-customer" method="POST">
        <div class="modal fade" tabindex="-1" id="modal-create-customer" role="dialog">
            <div class="modal-dialog modal-primary">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="fa-solid fa-person-circle-plus"></i> Agregar Personas</h4>
                    </div>
                    <div class="modal-body">
                        @csrf
                        @php
                            $busine_id =\Auth::user()->busine_id;
                        @endphp
                        <input type="hidden" name="busine_id" value="{{$busine_id}}">

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <small>CI/NIT</small>
                                    <input type="text" name="ci" id="ci" class="form-control" required placeholder="78559644">
                                </div>
                            </div> 
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <small>Sexo</small>
                                    <select name="gender" id="gender" class="form-control select2">
                                        <option value="" disabled selected>Seleccione una opción</option>
                                        <option value="1">Masculino</option>
                                        <option value="0">Femenino</option>
                                    </select>
                                </div>
                            </div> 
                        </div>  

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <small>Nombre.</small>
                                    <input type="text" name="first_name" id="first_name" class="form-control" placeholder="Juan">
                                </div>
                            </div> 
                            
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <small>Apellido.</small>
                                    <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Ortiz Fernandez">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <small>Fecha de Nacimiento.</small>
                                    <input type="date" name="birthdate" id="birthdate" class="form-control">
                                </div>
                            </div> 
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <small>Telefono.</small>
                                    <input type="number" name="phone" id="phone" placeholder="67285512" class="form-control">
                                </div>
                            </div> 
                        </div>
                        
                        <div class="form-group">
                            <small>Dirección</small>
                            <textarea name="address" id="address" class="form-control" rows="3" placeholder="C/ 18 de nov. Nro 123 zona central"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <input type="submit" class="btn btn-primary btn-save-customer" value="Guardar">
                    </div>
                </div>
            </div>
        </div>
    </form>


@stop

@section('css')
    <style>
        small{font-size: 14px;
            color: rgb(44, 44, 44);
            font-weight: bold;
        }
        .select2{
            width: 100% !important;
        }
    </style>
@stop

@section('javascript')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- <script src="{{ asset('vendor/tippy/popper.min.js') }}"></script>
    <script src="{{ asset('vendor/tippy/tippy-bundle.umd.min.js') }}"></script> --}}
    <script>

        $(function()
        {
            $('#dataTable').DataTable({
                    language: {
                            // "order": [[ 0, "desc" ]],
                            sProcessing: "Procesando...",
                            sLengthMenu: "Mostrar _MENU_ registros",
                            sZeroRecords: "No se encontraron resultados",
                            sEmptyTable: "Ningún dato disponible en esta tabla",
                            sInfo: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                            sInfoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
                            sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
                            sSearch: "Buscar:",
                            sInfoThousands: ",",
                            sLoadingRecords: "Cargando...",
                            oPaginate: {
                                sFirst: "Primero",
                                sLast: "Último",
                                sNext: "Siguiente",
                                sPrevious: "Anterior"
                            },
                            oAria: {
                                "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                            },
                            buttons: {
                                copy: "Copiar",
                                colvis: "Visibilidad"
                            }
                        },
                        order: [[ 0, 'desc' ]],

            });
            // $(".select2").select2({theme: "classic"});
            
            $('#plan_id').on('change',functionDay);
            $('#plan_id1').on('change',functionDay1);
            $('#category').on('change', onselect_article);

            var indexTable = 0;
            var i=0;
            
                $('#article_id').change(function(){
                    // let producto = $('#article_id option:selected').val();
                    // let nombre = $('#article_id option:selected').text();
                    // alert(2)
                    let producto = $('#article_id option:selected').data('item');
                    var ok=true;
                    
                    if(indexTable>0)
                    {
                        $(".wherhouse_article").each(function(){
                            id = parseFloat($(this).val());
                            if(producto.id == id)
                            {
                                ok=false;
                                alert("El Articulo ya existe")
                            }

                        });
                    }
                
                    if(producto && ok){
                        addTr(indexTable, producto);
                        indexTable += 1;
                    }
                });


            var productSelected, customerSelected;

            $('#select_people').select2({
                // tags: true,
                placeholder: '<i class="fa fa-search"></i> Buscar...',
                escapeMarkup : function(markup) {
                    return markup;
                },
                language: {
                    inputTooShort: function (data) {
                        return `Por favor ingrese ${data.minimum - data.input.length} o más caracteres`;
                    },
                    noResults: function () {
                        return `<i class="far fa-frown"></i> No hay resultados encontrados`;
                    }
                },
                quietMillis: 250,
                minimumInputLength: 2,
                ajax: {
                    url: "{{ url('admin/people/list/ajax') }}",        
                    processResults: function (data) {
                        let results = [];
                        data.map(data =>{
                            results.push({
                                ...data,
                                disabled: false
                            });
                        });
                        return {
                            results
                        };
                    },
                    cache: true
                },
                templateResult: formatResultCustomers,
                templateSelection: (opt) => {
                    customerSelected = opt;
                    
                    return opt.first_name?opt.first_name+' '+opt.last_name:'<i class="fa fa-search"></i> Buscar... ';
                }
            });
            $('#select_people1').select2({
                // tags: true,
                placeholder: '<i class="fa fa-search"></i> Buscar...',
                escapeMarkup : function(markup) {
                    return markup;
                },
                language: {
                    inputTooShort: function (data) {
                        return `Por favor ingrese ${data.minimum - data.input.length} o más caracteres`;
                    },
                    noResults: function () {
                        return `<i class="far fa-frown"></i> No hay resultados encontrados`;
                    }
                },
                quietMillis: 250,
                minimumInputLength: 2,
                ajax: {
                    url: "{{ url('admin/people/list/ajax') }}",        
                    processResults: function (data) {
                        let results = [];
                        data.map(data =>{
                            results.push({
                                ...data,
                                disabled: false
                            });
                        });
                        return {
                            results
                        };
                    },
                    cache: true
                },
                templateResult: formatResultCustomers,
                templateSelection: (opt) => {
                    customerSelected = opt;
                    
                    return opt.first_name?opt.first_name+' '+opt.last_name:'<i class="fa fa-search"></i> Buscar... ';
                }
            });



            



            $('#form-create-customer').submit(function(e){
                e.preventDefault();
                $('.btn-save-customer').attr('disabled', true);
                $('.btn-save-customer').val('Guardando...');
                $.post($(this).attr('action'), $(this).serialize(), function(data){
                    if(data.people.id){
                        toastr.success('Persona registrada..', 'Éxitos');
                        $(this).trigger('reset');
                    }else{
                        toastr.error(data.error, 'Error');
                    }
                })
                .always(function(){
                    $('.btn-save-customer').attr('disabled', false);
                    // $('.btn-save-customer').text('Guardar');
                    $('.btn-save-customer').val('Guardar');
                    $('#ci').val('');
                    $('#first_name').val('');
                    $('#last_name').val('');
                    $('#phone').val('');
                    $('#address').val('');
                    $('#birthdate').val('');
                    $('#gender').val('').trigger('change');


                    $('#modal-create-customer').modal('hide');
                });
            });


         
        });



        function formatResultCustomers(option){
            // Si está cargando mostrar texto de carga
            if (option.loading) {
                return '<span class="text-center"><i class="fas fa-spinner fa-spin"></i> Buscando...</span>';
            }
            // Mostrar las opciones encontradas
            return $(`  <div>
                            <b style="font-size: 16px">${option.first_name} ${option.last_name}</b><br>
                            <spam>NIT/CI: ${option.ci ? option.ci : 'No definido'} - Cel: ${option.phone ? option.phone : 'No definido'}</spam>
                        </div>`);
        }




        var arrayarticle = [];
        var total=0;


            function addTr(indexTable, data){
                let cantidad_precios = "2";

                $('#table-detalle').append(`
                    <tr id="tr-${indexTable}" class="tr-item">
                        <td><input type="hidden" name="wherehouseDetail_id[]" class="form-control wherhouse_article" value="${data.id}" required/>${data.name} - ${data.presentation} </td>
                        <td>
                            <input type="hidden" step="1" min="1" class="form-control imput-sm" value="${data.cant}" id="input-stock-disponible-${indexTable}" required/>
                            <input type="number" step="1" min="1" class="form-control imput-sm" value="${data.cant}" disabled required/>
                        </td>
                        <td>
                            <input type="hidden" step="1" min="1" class="form-control imput-sm" value="${data.itemEarnings}" id="input-precio-unitario-${indexTable}" required/>
                            <input type="number" step="1" min="1" class="form-control imput-sm" value="${data.itemEarnings}" disabled required/>
                        </td>
                        <td>
                            <input type="number" step="1" min="1" class="form-control imput-sm" name="cant_stock[]" onchange="subTotal(${indexTable})" onkeyup="subTotal(${indexTable})" id="input_cant-stock-${indexTable}" required/>
                            <small style="font-size: 11px" id="cant_stock-${indexTable}"></small>
                        </td>
                        <td>
                            <input type="hidden" step="1" min="1" name="total_pagar[]" class="form-control input_t" onchange="subTotal(${indexTable})" onkeyup="subTotal(${indexTable})" id="input_pagar-${indexTable}" required/>
                            <input type="number" step="1" min="1" class="form-control" id="input_pagar_view-${indexTable}" required disabled/>
                            <small style="font-size: 11px" id="ganancia_contado-${indexTable}"></small>
                        </td>
                        <td><button type="button" style="padding: 0px" onclick="removeTr(${indexTable})" class="btn btn-link"><i class="voyager-trash text-danger"></i></button></td>
                    </tr>
                `);
                arrayarticle[indexTable]=0;
                showHelp();
                $('#article_id').val('').trigger('change');
            }
        
            function subTotal(index){

                let stock_disponible = $(`#input-stock-disponible-${index}`).val() ? parseFloat($(`#input-stock-disponible-${index}`).val()) : 0;
                let precio_unitario = $(`#input-precio-unitario-${index}`).val() ? parseFloat($(`#input-precio-unitario-${index}`).val()) : 0;
                let cant = $(`#input_cant-stock-${index}`).val() ? parseFloat($(`#input_cant-stock-${index}`).val()) : 0;
               
                $(`#cant_stock-${index}`).html(`${cant > stock_disponible ? '<i class="fa-solid fa-rectangle-xmark"></i>' : '<i class="fa-solid fa-circle-check"></i>'} `);
                cant > stock_disponible  ? $(`#cant_stock-${index}`).addClass('text-danger') : $(`#cant_stock-${index}`).removeClass('text-danger');
                cant <= stock_disponible  ? $(`#cant_stock-${index}`).addClass('text-success') : $(`#cant_stock-${index}`).removeClass('text-success');
                
                // cant > stock_disponible ? $('#btn_vender').attr('disabled', true) : $('#btn_vender').attr('disabled', false);

                let pagar = cant * precio_unitario;

                
                // total= total + pagar;

           
                $(`#input_pagar-${index}`).val(cant > stock_disponible ? '0':pagar.toFixed(2));
                $(`#input_pagar_view-${index}`).val(cant > stock_disponible ? '0':pagar.toFixed(2));

                if(cant > stock_disponible)
                {
                    arrayarticle[index]=0;
                }
                else
                {
                    arrayarticle[index]=1;
                }
                btn();
                $("#total").html("Bs. "+calcular_total().toFixed(2));               
            }
            function calcular_total()
            {
                let total = 0;
                $(".input_t").each(function(){
                    total += parseFloat($(this).val());
                });
                
                return total;
            }
            
            function removeTr(index){
                $(`#tr-${index}`).remove();
                arrayarticle[index]=1;
                // $(`#input_pagar-${index}`).val(cant > stock_disponible ? '0':pagar.toFixed(2));
                btn();
                $("#total").html("Bs. "+calcular_total().toFixed(2));

                showHelp();
            }

            function showHelp(){
                let show = document.getElementsByClassName("tr-item").length > 0 ? false : true;
                if(show){
                    $('#div-empty-list').fadeIn('fast');
                    $('#table-list').fadeOut();
                }else{
                    $('#div-empty-list').fadeOut('fast');
                    $('#table-list').fadeIn();
                }
            }

            function btn()
            {
                let i =0;
                $('#btn_vender').attr('disabled', false)
                for(i=0; i<arrayarticle.length; i++)
                {                    
                    if(arrayarticle[i] == 0)
                    {
                        $('#btn_vender').attr('disabled', true)
                    }
                }

            }
            


            function onselect_article()
            {
                var id =  $(this).val();    
                // alert(id)
                if(id >=1)
                {
                    // alert(2)
                    $.get('{{route('clients-ajax.article')}}/'+id, function(data){
                        // alert(1)
                        var html_article=    '<option value="">Seleccione un Articulo..</option>'
                            for(var i=0; i<data.length; ++i)
                            html_article += '<option value="'+data[i].id+'">'+data[i].name+' - '+data[i].presentation +'</option>'

                        $('#article_id').html(html_article);           
                    });
                }
                else
                {
                    var html_article=    ''       
                    $('#article_id').html(html_article);
                }
            }

        function deleteItem(url){
            $('#delete_form').attr('action', url);
        }

        function functionDay()
        {
            
            id= $(this).val();
            // alert(id)
            if(id>=1)
            {
                if(id==4)    
                {
                    var html_day ='<div class="form-group">'
                    html_day+='<small>Dia.</small>'
                    html_day+='<select name="day_id" class="form-control select2" required>'
                    html_day+='<option value="">Seleccione un plan</option>'
                    html_day+='@foreach ($day as $item)'
                    html_day+='<option value="{{$item->id}}">{{$item->name}}</option>'
                    html_day+='@endforeach'
                    html_day+='</select>'
                    html_day+='</div>'
                    $('#div_1').html(html_day);
                    $('#div_2').html('');
                }          
                else
                {
                    var html_start ='<div class="form-group">'
                        html_start+=    '<small>Inicio.</small>'
                        html_start+=    '<input type="date" class="form-control" name="start">'
                        html_start+='</div>'
                    $('#div_1').html(html_start);
                    var html_finish ='<div class="form-group">'
                        html_finish+=    '<small>Inicio.</small>'
                        html_finish+=    '<input type="date" class="form-control" name="finish">'
                        html_finish+='</div>'
                    $('#div_2').html(html_finish);
                }  
            }
            else
            {
                $('#div_1').html('');
                $('#div_2').html('');
            }
        }

        function functionDay1()
        {
            id= $(this).val();
            // alert(id)
            if(id>=1)
            {
                if(id==4)    
                {
                    var html_day ='<div class="form-group">'
                    html_day+='<small>Dia.</small>'
                    html_day+='<select name="day_id" class="form-control select2" required>'
                    html_day+='<option value="">Seleccione un plan</option>'
                    html_day+='@foreach ($day as $item)'
                    html_day+='<option value="{{$item->id}}">{{$item->name}}</option>'
                    html_day+='@endforeach'
                    html_day+='</select>'
                    html_day+='</div>'
                    $('#divs_1').html(html_day);
                    $('#divs_2').html('');
                }          
                else
                {
                    var html_start ='<div class="form-group">'
                        html_start+=    '<small>Inicio.</small>'
                        html_start+=    '<input type="date" class="form-control" name="start">'
                        html_start+='</div>'
                    $('#divs_1').html(html_start);
                    var html_finish ='<div class="form-group">'
                        html_finish+=    '<small>Inicio.</small>'
                        html_finish+=    '<input type="date" class="form-control" name="finish">'
                        html_finish+='</div>'
                    $('#divs_2').html(html_finish);
                }  
            }
            else
            {
                $('#divs_1').html('');
                $('#divs_2').html('');
            }
        }

            $('#edit_modal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget) //captura valor del data-empresa=""

                var id = button.data('id')
                var item = button.data('item')
                // alert(item.id);


                // // alert(item.nromemo)

                var modal = $(this)
                modal.find('.modal-body #id').val(item.id)
                modal.find('.modal-body #service_id').val(item.service_id).trigger('change')
                modal.find('.modal-body #plan_id1').val(item.plan_id).trigger('change')
                modal.find('.modal-body #people_id').val(item.people_id).trigger('change')
                modal.find('.modal-body #hour').val(item.hour).trigger('change')
                
                
                modal.find('.modal-body #amount').val(item.amount)


                if(item.plan_id==4)    
                {
                    var html_day ='<div class="form-group">'
                    html_day+='<small>Dia.</small>'
                    html_day+='<select name="day_id" id="day_id1" class="form-control select2" required>'
                    html_day+='<option value="">Seleccione un plan</option>'
                    html_day+='@foreach ($day as $item)'
                    html_day+='<option value="{{$item->id}}">{{$item->name}}</option>'
                    html_day+='@endforeach'
                    html_day+='</select>'
                    html_day+='</div>'
                    $('#divs_1').html(html_day);
                    $('#divs_2').html('');
                    // alert(item.day_id)
                    modal.find('.modal-body #day_id1').val(item.day_id).trigger('change')
                }
                else
                {
                    var html_start ='<div class="form-group">'
                        html_start+=    '<small>Inicio.</small>'
                        html_start+=    '<input type="date" class="form-control" id="start" name="start">'
                        html_start+='</div>'
                    $('#divs_1').html(html_start);
                    var html_finish ='<div class="form-group">'
                        html_finish+=    '<small>Inicio.</small>'
                        html_finish+=    '<input type="date" class="form-control" id="finish" name="finish">'
                        html_finish+='</div>'
                    $('#divs_2').html(html_finish);
                    modal.find('.modal-body #start').val(item.start)
                    modal.find('.modal-body #finish').val(item.finish)

                }                  
            });



            $('#payment-modal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget) //captura valor del data-empresa=""
                var item = button.data('item')

                var modal = $(this)
                modal.find('.modal-body #client_id').val(item.id)
                
            });
            
            $('#show-modal').on('show.bs.modal', function (event)
            {
                var button = $(event.relatedTarget);
                var item = button.data('item');
                var user = button.data('user');
                var modal = $(this);
                modal.find('.modal-body #label-customer').text(item.people ? item.people.first_name+' '+item.people.last_name: 'Sin nombre');
                modal.find('.modal-body #label-user').text(user);
                modal.find('.modal-body #label-date').text(item.created_at.toLocaleString());
                modal.find('.modal-body #label-total').text(item.amount);
                if(item.amount == item.subAmount)
                {
                    modal.find('.modal-body #label-status').text('Pagado');
                }
                else
                {
                    modal.find('.modal-body #label-status').text('Pendiente');
                }

                $('#detalle tbody').empty();
                $('#detallepago tbody').empty(); 
                if(item.service_id)
                {
                    var dia ='';
                    var date = '';
                    if (item.plan_id != 4)
                    {
                        dia =`<b>${item.start} Hasta ${item.finish}</b>`
                    }
                    else
                    {
                        dia = `Dia: <small><b>${item.day.name }</b></small>`
                    }


                    if(item.hour == 1)
                    {
                        date = 'Mañana';
                    }
                    if(item.hour == 2)
                    {
                        date = 'Tarde';
                    }
                    if(item.hour ==3)
                    {
                        date = 'Noche';
                    }


                    $('#detalle tbody').append(`
                        <tr>
                            <td style="width: 50px">1</td>
                            <td>${item.service.name}                            
                            </td>
                            <td>
                                Plan: ${item.plan.name}
                                <br> 
                                                        ${dia}     
                                                        <br>
                                                        <b>Turno: 
                                                            ${date}
                                                        </b>
                            </td>
                            <td>1</td>
                            <td>${item.amount}</td>
                            <td class="text-right"><b>${item.amount} Bs.</b></td>
                        </tr>
                    `);
                }
                else
                {

                    $.get('{{route('clients-ajax.item.modal')}}/'+item.id, function(data){

                        for (var i=0; i<data.length; i++) {
                            $('#detalle tbody').append(`
                                <tr>
                                    <td style="width: 50px">${i+1}</td>
                                    <td>Productos</td>
                                    <td>
                                        <small><b>${data[i].name}-${data[i].presentation}</b></small>
                                    </td>
                                    <td> ${data[i].cant} </td>
                                    <td>${data[i].price}</td>
                                    <td class="text-right"><b>${data[i].price * data[i].cant} Bs.</b></td>
                                    
                                </tr>
                            `);
                        }
                    });
                }




                var name='';
                $.get('{{route('clients-ajax.adition.modal')}}/'+item.id, function(data){
                    var pago= 0;
                    
                    for (var i=0; i<data.length; i++) {
                        // pago = pago+ data[i].cant;
                        pago = pago + parseInt(data[i].cant);
                        $('#detallepago tbody').append(`
                            <tr>
                                <td style="width: 50px">${i+1}</td>
                                <td style="width: 50px">${data[i].created_at}</td>
                                <td style="width: 50px">${data[i].observation? data[i].observation:''}</td>
                                <td style="width: 50px" class="text-right">${data[i].cant}</td>                               
                                
                            </tr>
                        `);
                    }
                    
                    $('#label-total-payment').text(Number(pago).toString());
                    $('#label-debt').text(item.amount - pago);
                });


            })
            
    </script>
@stop
