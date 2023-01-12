@extends('voyager::master')

@section('page_title', 'Viendo Datos Personales')

@section('page_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body" style="padding: 0px">
                        <div class="col-md-6" style="padding: 0px">
                            <h1 id="subtitle" class="page-title">
                                <i class="voyager-basket"></i> Venta y Servicios Atendidos
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
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-10">
                                <div class="dataTables_length" id="dataTable_length">
                                    <label>Mostrar <select id="select-paginate" class="form-control input-sm">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select> registros</label>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <input type="text" id="input-search" class="form-control">
                            </div>

                            <div class="col-sm-12 text-right">
                                <label class="radio-inline"><input type="radio" class="radio-type" name="optradio" value="todo">Todos</label>
                                
                                <label class="radio-inline"><input type="radio" class="radio-type" name="optradio" value="enpago" checked>En Pagos</label>
                                                            
                                <label class="radio-inline"><input type="radio" class="radio-type" name="optradio" value="enpagoS">En Pagos "Servicio"</label>
                                <label class="radio-inline"><input type="radio" class="radio-type" name="optradio" value="enpagoP">En Pagos "Productos"</label>
                             
                                <label class="radio-inline"><input type="radio" class="radio-type" name="optradio" value="pagado">Pagados</label>
                                <label class="radio-inline"><input type="radio" class="radio-type" name="optradio" value="eliminados">Eliminados</label>

                            </div>
                        </div>
                        <div class="row" id="div-results" style="min-height: 120px"></div>
                    </div>
                </div>
            </div>
        </div>
    </div> 


    {{-- vault add register producto modal --}}
    <form id="form-search" action="{{ route('clients-article.store') }}" method="post">
        @csrf
        <input type="hidden" name="cashier_id" value="{{ $cashier? $cashier->id:'' }}">
        <div class="modal fade modal-primary" id="producto_modal" role="dialog">
            <div class="modal-dialog modal-primary modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="fa-solid fa-tags"></i> Registrar Venta de Productos</h4>
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
                                    {{-- <small class="control-label" for="toggleswitchs">Pago al Credito</small> --}}
                                    <label for="toggleswitchs">Pago al Credito</label>
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
                                    {{-- <input type="number" name="subAmount" id="input-subAmount" min="0" step="0.1" class="form-control" placeholder="Monto recibo Bs."> --}}
                                    <input type="number" style="text-align: right" min="1" class="form-control" onkeypress="return filterFloat(event,this);" name="subAmount" id="input-subAmount" placeholder="Monto recibido Bs." required>

                                </div>
                            </div>                               
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        @if ($cashier)
                            <button type="submit" class="btn btn-dark" id="btn_vender">Vender</button>
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
        <input type="hidden" name="type" value="servicio">
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
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <small>Tipo de Servicios.</small>
                                    <select name="service_id" id="service_id" class="form-control select2" required>
                                        <option selected disabled value="">--Seleccione un servicio--</option>
                                        @foreach ($service as $item)
                                            <option value="{{$item->id}}" >{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>  
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <small>plan.</small>
                                    <select name="plan_id" id="plan_id" class="form-control select2" required>
                                        
                                    </select>
                                </div>
                            </div>  

                            <div id="div_1" class="col-sm-3">
                                
                            </div>  
                            <div id="div_2" class="col-sm-3">
                                
                            </div>  
                            
                        </div>
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <small>Horarios.</small>
                                    <select name="hour_id" id="hour_id" class="form-control select2" required>
                                    </select>
                                </div>
                            </div>  
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <small>Instructores.</small>
                                    <select name="instructor_id" id="instructor_id" class="form-control select2" required>
                                    </select>
                                </div>
                            </div>  
                                            
                        </div>          
                        
                        <hr>
                        <div class="row">
                            
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <small>Cliente.</small>
                                    <select name="people_id" id="select_people1" class="form-control" required></select>
                                    
                                </div>
                            </div>   
                            <div id="div_amount" class="col-sm-3">
                                <div class="form-group">
                                    <small>Monto Total.</small>
                                    <input type="number" style="text-align: right" min="1" class="form-control" onkeypress="return filterFloat(event,this);" placeholder="Monto Total a Pagar" min="0" step="0.1" name="amount">
                                    {{-- <input type="number"  style="text-align: right" onkeypress="return filterFloat(event,this);" id="cantidad" placeholder="Cantidad Articulo" class="form-control text" title="Cantidad"> --}}
                                </div>
                            </div>                    
                        </div>      
                        


                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    {{-- <small class="control-label">Pago al Credito</small> --}}
                                    <label for="toggleswitch">Pago al Credito</label>

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
                                    <input type="number" style="text-align: right" min="1" class="form-control" onkeypress="return filterFloat(event,this);" class="form-control" step="0.1" name="subAmount" id="input-subAmount" value="0" placeholder="Monto recibido Bs.">

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
        <div class="modal modal-success fade" tabindex="-1" id="payment-modal" role="dialog">
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
                            <input type="number" style="text-align: right" min="1" class="form-control" onkeypress="return filterFloat(event,this);" name="subAmount" placeholder="Monto" required>
                        </div>
                        <div class="form-group">
                            <label for="observation">Observaciones</label>
                            <textarea class="form-control" name="observation" placeholder="Observaciones" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        @if ($cashier)
                            <button type="submit" class="btn btn-success">Pagar</button>
                        @endif 
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
                                <h3 class="panel-title">Atendido por</h3>
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
                        <div class="col-md-6" style="margin-bottom:0;">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Estado</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p id="label-status">Value</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
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

    /* LOADER 3 */
    
    #loader-3:before, #loader-3:after{
      content: "";
      width: 20px;
      height: 20px;
      position: absolute;
      top: 0;
      left: calc(50% - 10px);
      background-color: #22a7f0;
      animation: squaremove 1s ease-in-out infinite;
    }
    
    #loader-3:after{
      bottom: 0;
      animation-delay: 0.5s;
    }
    
    @keyframes squaremove{
      0%, 100%{
        -webkit-transform: translate(0,0) rotate(0);
        -ms-transform: translate(0,0) rotate(0);
        -o-transform: translate(0,0) rotate(0);
        transform: translate(0,0) rotate(0);
      }
    
      25%{
        -webkit-transform: translate(40px,40px) rotate(45deg);
        -ms-transform: translate(40px,40px) rotate(45deg);
        -o-transform: translate(40px,40px) rotate(45deg);
        transform: translate(40px,40px) rotate(45deg);
      }
    
      50%{
        -webkit-transform: translate(0px,80px) rotate(0deg);
        -ms-transform: translate(0px,80px) rotate(0deg);
        -o-transform: translate(0px,80px) rotate(0deg);
        transform: translate(0px,80px) rotate(0deg);
      }
    
      75%{
        -webkit-transform: translate(-40px,40px) rotate(45deg);
        -ms-transform: translate(-40px,40px) rotate(45deg);
        -o-transform: translate(-40px,40px) rotate(45deg);
        transform: translate(-40px,40px) rotate(45deg);
      }
    }
    

    .select2{
            width: 100% !important;
        }
    
    </style>
@stop

@section('javascript')
    <script src="{{ url('js/main.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.27.0/moment.min.js"></script>
    
    <script>
        var countPage = 10, order = 'id', typeOrder = 'desc';
        $(document).ready(() => {


            list();
            $('.radio-type').click(function(){
                list();
            });
            
            $('#input-search').on('keyup', function(e){
                if(e.keyCode == 13) {
                    list();
                }
            });

            $('#select-paginate').change(function(){
                countPage = $(this).val();
               
                list();
            });
        });

        function list(page = 1){

            let type = $(".radio-type:checked").val();

            // $('#div-results').loading({message: 'Cargando...'});
            var loader = '<div class="col-md-12 bg"><div class="loader" id="loader-3"></div></div>'
            $('#div-results').html(loader);

            let url = '{{ url("admin/clients/ajax/list") }}';
            let search = $('#input-search').val() ? $('#input-search').val() : '';

            $.ajax({
                // url: `${url}/${search}?paginate=${countPage}&page=${page}`,
                url: `${url}/${type}/${search}?paginate=${countPage}&page=${page}`,

                type: 'get',
                
                success: function(result){
                $("#div-results").html(result);
            }});

        }
        // $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
        $(function()
        {
                    
            $('#service_id').on('change',functionPlan);
            $('#service_id').on('change',functionHour);
            $('#plan_id').on('change',funtionInfPlan);
            $('#hour_id').on('change',funtionInstructor);




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
                            <spam>CI: ${option.ci ? option.ci : 'No definido'} - Cel: ${option.phone ? option.phone : 'No definido'}</spam>
                        </div>`);
        }

        // funcion para mostrar los dias de un plan diario
        function functionPlan()
        {
                var id =  $(this).val();   
                $('#instructor_id').html(''); 
                $('#plan_id').html(''); 
                $('#div_1').html('')
                $('#div_2').html('')
                var html_amount = `<div class="form-group">
                                    <small>Monto Total.</small>
                                    <input type="number" style="text-align: right" min="1" class="form-control" onkeypress="return filterFloat(event,this);" class="form-control" placeholder="Monto Total a Pagar" min="0" step="0.1" name="amount">
                                </div>`
                $('#div_amount').html(html_amount)

                if(id >=1)
                {
                    $.get('{{route('clients-ajax-list.plan')}}/'+id, function(data){
                        var html_plan=    '<option selected disabled value="">--Seleccione un plan--</option>'
                            for(var i=0; i<data.length; ++i)
                            html_plan += '<option value="'+data[i].id+'">'+data[i].name+'</option>'

                        $('#plan_id').html(html_plan);           
                    });
                }
                else
                {    
                    $('#plan_id').html('');
                }
        }

        //para que despliquede los horarios de cada servicios que ofrese
        function functionHour()
        {
                var id =  $(this).val();  
                $('#instructor_id').html(''); 
                $('#plan_id').html(''); 
                $('#div_1').html('')
                $('#div_2').html('')
                var html_amount = `<div class="form-group">
                                    <small>Monto Total.</small>
                                    <input type="number" style="text-align: right" min="1" class="form-control" onkeypress="return filterFloat(event,this);" class="form-control" placeholder="Monto Total a Pagar" min="0" step="0.1" name="amount">
                                </div>`
                $('#div_amount').html(html_amount)

                if(id >=1)
                {
                    $.get('{{route('clients-ajax-list.hour')}}/'+id, function(data){
                        var html_hour=    '<option selected disabled value="">--Seleccione un horario--</option>'
                            for(var i=0; i<data.length; ++i)
                            html_hour += '<option value="'+data[i].id+'">'+data[i].name+'</option>'

                        $('#hour_id').html(html_hour);           
                    });
                }
                else
                {
                    var html_hour=    ''       
                    $('#hour_id').html(html_hour);
                }
        }
        //para obtener la informacion de cada plan
        function funtionInfPlan()
        {
            var id =  $(this).val();    
            if(id >=1)
            {
                $.get('{{route('clients-ajax-plan.inf')}}/'+id, function(data){
                    // alert(data.day)         
                    if(data.day == null || data.amount == null)
                    {
                        var html_start = `<div class="form-group">
                                    <small>Inicio.</small>
                                    <input type="date" name="start" class="form-control" required>
                                </div>`
                        $('#div_1').html(html_start)

                        var html_finish = `<div class="form-group">
                                    <small>Fin.</small>
                                    <input type="date" name="finish" class="form-control" required>
                                </div>`
                        $('#div_2').html(html_finish)


                        var html_amount = `<div class="form-group">
                                    <small>Monto Total.</small>
                                    <input type="number" style="text-align: right" min="1" class="form-control" onkeypress="return filterFloat(event,this);" class="form-control" placeholder="Monto Total a Pagar" min="0" step="0.1" name="amount">
                                </div>`

                        $('#div_amount').html(html_amount)
                    }
                    else
                    {
                        var html_day = `<div class="form-group">
                                    <small>Dias.</small>
                                    <input type="text" id="dayPlan" value="${data.day}" disabled class="form-control">
                                    <input type="hidden" name="day" id="dayPlans" value="${data.day}" class="form-control">
                                </div>`

                        $('#div_1').html(html_day)

                        var html_start = `<div class="form-group">
                                    <small>Inicio.</small>
                                    <input type="date" name="start" class="form-control" required>
                                </div>`
                        $('#div_2').html(html_start)

                        // $('#div_2').html('')


                        var html_amount = `<div class="form-group">
                                    <small>Monto Total.</small>
                                    <input type="text" style="text-align: right" value="${data.amount}" disabled class="form-control">
                                    <input type="hidden" name="amount"  value="${data.amount}" class="form-control">
                                </div>`

                        $('#div_amount').html(html_amount)
                    }
                });
            }
        }

        //para poder ver los instructores de cada horarios
        function funtionInstructor()
        {
            var id =  $(this).val();    
            if(id >=1)
            {
                $.get('{{route('clients-ajax-list.instructor')}}/'+id, function(data){
                    var html_instructor=    '<option selected disabled value="">--Seleccione un Instructor--</option>'
                        for(var i=0; i<data.length; ++i)
                        html_instructor += '<option value="'+data[i].id+'">'+data[i].instructor.people.first_name+' '+data[i].instructor.people.last_name +'</option>'

                    $('#instructor_id').html(html_instructor);           
                });
            }
            else
            { 
                $('#instructor_id').html('');
            }
        }



        // para mostrar el input de al credito de servicio
        function myFunction() {
            var checkBox = document.getElementById("toggleswitch");
            var text = document.getElementById("text");
            if (checkBox.checked == true){
                text.style.display = "block";
                $('#input-subAmount').attr('required', 'required');
            } else {
                text.style.display = "none";
                $('#input-subAmount').removeAttr('required');
            }
        }
        // para mostrar el input de al credito de producto
        function myFunctions() {
            var checkBox = document.getElementById("toggleswitchs");
            var text = document.getElementById("texts");
            if (checkBox.checked == true){
                text.style.display = "block";
                $('#input-subAmounts').attr('required', 'required');
            } else {
                text.style.display = "none";
                $('#input-subAmounts').removeAttr('required');
            }
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



// ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
        function deleteItem(url){
            $('#delete_form').attr('action', url);
        }


        $('#show-modal').on('show.bs.modal', function (event)
            {
                var button = $(event.relatedTarget);
                var item = button.data('item');
                var user = button.data('user');
                var modal = $(this);
                modal.find('.modal-body #label-customer').text(item.people ? item.people.first_name+' '+item.people.last_name: 'Sin nombre');
                modal.find('.modal-body #label-user').text(user);



                modal.find('.modal-body #label-date').text(moment(item.created_at).format('DD-MMMM-YYYY h:mm:ss a'));
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
                        pago = pago + parseInt(data[i].cant?data[i].cant:0);
                        $('#detallepago tbody').append(`
                            <tr>
                                <td style="width: 50px">${i+1}</td>
                                <td style="width: 50px">${moment(data[i].created_at).format('DD-MMMM-YYYY h:mm:ss a')}</td>
                                <td style="width: 50px">${data[i].observation? data[i].observation:''}</td>
                                <td style="width: 50px" class="text-right">${data[i].cant?data[i].cant:0}</td>                               
                                
                            </tr>
                        `);
                    }
                    
                    $('#label-total-payment').text('Bs. '+Number(pago).toString());
                    $('#label-debt').text('Bs. '+(item.amount - pago));
                });


            })


            $('#payment-modal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget) //captura valor del data-empresa=""
                var item = button.data('item')

                var modal = $(this)
                modal.find('.modal-body #client_id').val(item.id)
                
            });
        
       
    </script>

    <script type="text/javascript">
        function filterFloat(evt,input){
            // Backspace = 8, Enter = 13, ‘0′ = 48, ‘9′ = 57, ‘.’ = 46, ‘-’ = 43
            var key = window.Event ? evt.which : evt.keyCode;    
            var chark = String.fromCharCode(key);
            var tempValue = input.value+chark;
            if(key >= 48 && key <= 57){
                if(filter(tempValue)=== false){
                    return false;
                }else{       
                    return true;
                }
            }else{
                if(key == 8 || key == 13 || key == 0) {     
                    return true;              
                }else if(key == 46){
                        if(filter(tempValue)=== false){
                            return false;
                        }else{       
                            return true;
                        }
                }else{
                    return false;
                }
            }
        }
        function filter(__val__){
            var preg = /^([0-9]+\.?[0-9]{0,2})$/; 
            if(preg.test(__val__) === true){
                return true;
            }else{
            return false;
            }
            
        }
    </script>
@stop