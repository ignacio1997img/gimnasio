@extends('voyager::master')

@section('page_title', 'Viendo Datos Personales')

@section('page_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body" style="padding: 0px">
                        <div class="col-md-8" style="padding: 0px">
                            <h1 class="page-title">
                                <i class="fa-solid fa-dumbbell"></i> Servicios
                            </h1>
                            {{-- <div class="alert alert-info">
                                <strong>Información:</strong>
                                <p>Puede obtener el valor de cada parámetro en cualquier lugar de su sitio llamando <code>setting('group.key')</code></p>
                            </div> --}}
                        </div>
                        <div class="col-md-4 text-right" style="margin-top: 30px">
                            @if (auth()->user()->hasPermission('add_clients') && ! auth()->user()->hasRole('admin'))
                                <a href="#" data-toggle="modal" data-target="#producto_modal" class="btn btn-dark">
                                    <i class="fa-solid fa-tags"></i> <span>Vender Productos</span>
                                </a>
                                <a href="#" data-toggle="modal" data-target="#registar_modal" class="btn btn-success">
                                    <i class="fa-solid fa-clipboard-user"></i> <span>Atender Cliente</span>
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
                                        <th style="text-align: center">Estado</th>                                        
                                        <th style="text-align: center">Registrado</th>                                        
                                        <th style="text-align: right">Acciones</th>
                                    </tr>
                                </thead>
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
                                                    {{-- {{$item[0]->item}} --}}
                                                    @php
                                                        $si = $item->item;
                                                        $si->groupBy('itemEarnings')
                                                    @endphp
                                                    @foreach ($si as $data)
                                                        <small><b>{{$data}}</b></small>
                                                        <br>
                                                    @endforeach
                                                @endif
                                                                                     
                                            </td>
                                            <td style="text-align: center">{{ $item->amount }}</td>
                                            <td style="text-align: center">
                                                @if ($item->status)
                                                    <label class="label label-success">Vigente</label>
                                                @else
                                                    <label class="label label-warning">Finalizado</label>
                                                @endif
                                            </td>
                                            <td style="text-align: center">{{date('d/m/Y H:i:s', strtotime($item->created_at))}}<br><small>{{\Carbon\Carbon::parse($item->created_at)->diffForHumans()}}.</small></td>
                                            <td style="text-align: right">
                                                @if ( !auth()->user()->hasRole('admin') && !auth()->user()->hasRole('administrador'))                                                    
                                                    @if ($item->status && $item->cashier->status == "abierta")
                                                        <a href="" title="Editar" class="btn btn-sm btn-primary" data-item="{{ $item}}" data-toggle="modal" data-target="#edit_modal">
                                                            <i class="voyager-edit"></i> <span class="hidden-xs hidden-sm">Editar</span>
                                                        </a>
                                                    @endif
                                                    @if ($item->cashier->status == "abierta")
                                                        <button title="Borrar" class="btn btn-sm btn-danger delete" onclick="deleteItem('{{ route('clients.destroy', ['client' => $item->id]) }}')" data-toggle="modal" data-target="#delete-modal">
                                                            <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Borrar</span>
                                                        </button>
                                                    @endif
                                                @endif

                                                
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
        <div class="modal fade" tabindex="-1" id="producto_modal" role="dialog">
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
                                    <select name="people_id" class="form-control select2">
                                        <option value="">Seleccione una persona</option>
                                        @foreach ($people as $item)
                                            <option value="{{$item->id}}">{{$item->first_name}} {{$item->last_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> 
                            
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <small>Articulo.</small>
                                    <select id="article_id" class="form-control select2">
                                        <option value="">Seleccione una categoria..</option>
                                        @foreach ($article as $item)
                                            <option data-item='@json($item)'>{{$item->name}} - {{$item->presentation}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>  
                        <div id="dataTable" class="table-responsive">
                            <div class="col-md-12" style="margin-top: 20px" id="div-empty-list">
                                <h3 class="text-muted text-center">Lista vacía</h3>
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
        <div class="modal fade" tabindex="-1" id="registar_modal" role="dialog">
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
                            <div class="col-sm-4">
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
                                    <select name="people_id" class="form-control select2" required>
                                        <option value="">Seleccione una persona</option>
                                        @foreach ($people as $item)
                                            <option value="{{$item->id}}">{{$item->first_name}} {{$item->last_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>  
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <small>Monto Bs.</small>
                                    <input type="number" class="form-control" name="amount">
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


@stop

@section('css')
<style>
small{font-size: 12px;
        color: rgb(44, 44, 44);
        font-weight: bold;
    }
</style>

@stop

@section('javascript')
    <script>
        $(function()
        {
            // alert(3)
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
                    let producto = $('#article_id option:selected').data('item');
                    // alert(producto);
                    if(producto){
                        addTr(indexTable, producto);
                        indexTable += 1;
                    }
                });

         
        });
        var arrayarticle = [];
        var total=0;


            function addTr(indexTable, data){
                let cantidad_precios = "2";

                $('#table-detalle').append(`
                    <tr id="tr-${indexTable}" class="tr-item">
                        <td><input type="hidden" name="wherehouseDetail_id[]" class="form-control" value="${data.id}" required/>${data.name} - ${data.presentation} </td>
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


                // let precio_venta_contado = $(`#input-precio_venta_contado-${index}`).val() ? parseFloat($(`#input-precio_venta_contado-${index}`).val()) : 0;
                // // let precio_venta = $(`#input-precio_venta-${index}`).val() ? parseFloat($(`#input-precio_venta-${index}`).val()) : 0;
                // // let precio_venta_alt = $(`#input-precio_venta_alt-${index}`).val() ? parseFloat($(`#input-precio_venta_alt-${index}`).val()) : 0;
                
                // let ganancia_contado = precio_venta_contado - precio_mayoritario;
                // // let ganancia_credito = precio_venta - precio_mayoritario;
                // // let ganancia_credito_alt = precio_venta_alt - precio_mayoritario;
                
                

            }
            function calcular_total()
            {
                let total = 0;
                $(".input_t").each(function(){

                    total += parseFloat($(this).val());
                    // alert(parseFloat($(this).val()));
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
                        // alert(0)
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
                
                // if(item.checkcategoria_id == 2)
                // {
                //     var div =   '<div class="col-md-12">'
                //         div+=           '<div class="input-group-prepend">'
                //         div+=                '<span class="input-group-text"><b>Tipo:</b></span>'
                //         div+=            '</div>'
                //         div+=            '<select name="tipopagos" id="select-tipo" class="form-control select2" required>'
                //         div+=                '<option value="">Seleccione un tipo..</option>'
                //         div+=                '<option value="1">Personal Eventual.</option>'
                //         div+=                '<option value="2">Funcionamiento.</option>'
                //         div+=                '<option value="3">Consultoria.</option>'                                        
                //         div+=            '</select>'
                //         div+=        '</div>'
                //     $('#tips').html(div);
                // }
                // else
                // {
                //     div ='';
                //     $('#tips').html(div);
                // }
                // modal.find('.modal-body #select-tipo').val(item.tipopagos).trigger('change')   
                
                // if(item.checkcategoria_id == 4 || item.tipopagos == 3)
                // {
                //     var div =   '<div class="col-md-12">'
                //         div+=           '<div class="input-group-prepend">'
                //         div+=                '<span class="input-group-text"><b>Ci:</b></span>'
                //         div+=            '</div>'
                //         div+=            '<input type="text" id="ci" class="form-control" name="ci">'
                //         div+=        '</div>'
                //     $('#div_cis').html(div);
                // }
                // else
                // {
                //     div ='';
                //     $('#div_cis').html(div);
                // }
                // modal.find('.modal-body #ci').val(item.ci)   


                
            });
            
    </script>
@stop
