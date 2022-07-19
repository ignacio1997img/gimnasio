{{-- <link href="https://cdn.jsdelivr.net/npm/alertifyjs@1.11.0/build/css/alertify.min.css" rel="stylesheet"/>
<script src="https://cdn.jsdelivr.net/npm/alertifyjs@1.11.0/build/alertify.min.js"></script> --}}


@extends('voyager::master')

@section('page_title', 'Viendo Ingresos')


{{-- @if(auth()->user()->hasPermission('add_income')) --}}

    @section('page_header')
        
        <div class="container-fluid">
            <div class="row">
                <h1 id="subtitle" class="page-title">
                    <i class="fa-solid fa-store"></i> Añadir Items
                </h1>
                <a href="{{ route('wherehouses.index') }}" class="btn btn-warning btn-add-new">
                    <i class="fa-solid fa-arrow-rotate-left"></i> <span>Volver</span>
                </a>
            </div>
        </div>
    @stop

    @section('content')    
        <div id="app">
            <div class="page-content browse container-fluid" >
                @include('voyager::alerts')
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-bordered">
                            <div class="panel-body">                            
                                <div class="table-responsive">
                                    <div >        
                                        <form role="form" action="{{ route('wherehouses.store') }}" method="post">
                                        @csrf
                                        <div class="card-body">
                                            <h5 id="subtitle">Proveedor + Detalle de Factura:</h5>
                                          
                                            <div class="row">          
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <input type="number" id="nrofactura" name="nrofactura" placeholder="Introducir Numero" class="form-control text" title="Introducir Nro de Factura">
                                                        </div>
                                                        <small>Numero Factura (Opcional).</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <select id="provider" name="provider_id" class="form-control select2">
                                                                <option value="">Seleccione un Proveedor</option>
                                                                @foreach ($provider as $data)
                                                                    <option value="{{$data->id}}_{{$data->nit}}_{{$data->responsible}}">{{$data->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <small>Seleccionar Proveedor (Opcional).</small>
                                                    </div>
                                                </div>
                                                <input type="hidden" id="provider_id" name="provider_id">
                                            
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <input type="text" id="nit" class="form-control form-control-sm text" placeholder="Seleccione un Proveedor" readonly>
                                                        </div>
                                                        <small>NIT.</small>
                                                    </div>
                                                </div>
                                                <!-- === -->
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <input type="text" id="responsible" class="form-control form-control-sm text" placeholder="Seleccione un Proveedor" disabled >
                                                        </div>
                                                        <small>Responsable.</small>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <hr>
                                            <h5 id="subtitle">Categoria + Articulo:</h5>
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <select id="category"class="form-control select2">
                                                                <option value="">Seleccione una categoria..</option>
                                                                @foreach ($category as $item)
                                                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <small>Categoria.</small>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <select id="article_id" class="form-control select2">
                                                            
                                                        </select>
                                                        <small>Articulo.</small>
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
                                                        <th style="width: 110px">Precio compra</th>
                                                        <th style="width: 110px">Cantidad item</th>
                                                        <th style="width: 110px">Precio Mayoritario (Unitario)</th>
                                                        <th style="width: 110px">Ganancia Unitaria</th>
                                                        {{-- <th style="width: 110px">Venta crédito</th> --}}
                                                        {{-- <th @if (setting('ventas.precios_credito') != 2) style="display: none" @else style="width: 100px" @endif >Venta crédito</th> --}}
                                                        <th style="width: 20px"></th>
                                                    </thead>
                                                    <tbody id="table-detalle"></tbody>
                                                </table>
                                            </div>
                                            
                                        </div>   
                                        <div class="card-footer">
                                            <button type="submit"  class="btn btn-primary"><i class="fas fa-save"></i> Guardar</button>
                                        </div>   
                                        </form>               
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>          
    @stop


    @section('css')
    <script src="{{ asset('js/app.js') }}" defer></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.8.0/sweetalert2.min.css" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.8.0/sweetalert2.min.js"></script>
    <style>
        input:focus {
            background: rgb(197, 252, 215);
        }

        input:focus{        
            background: rgb(255, 245, 229);
            border-color: rgb(255, 161, 10);
            /* border-radius: 50px; */
        }
        input.text, select.text, textarea.text{ 
            border-radius: 5px 5px 5px 5px;
            color: #000000;
            border-color: rgb(63, 63, 63);
        }
    
       
        small{font-size: 12px;
            color: rgb(12, 12, 12);
            font-weight: bold;
        }
        #subtitle{
            font-size: 18px;
            color: rgb(12, 12, 12);
            font-weight: bold;
        }

        #radio{
            border: 1px solid #353434;
            background-color: #dbeefb;
            height: 190px;
        }
    </style>
    @stop

    @section('javascript')
        
        <script>

            $(function()
            {    

                $(".select2").select2({theme: "classic"});


                $('#provider').on('change', onselect_proveedor_llenar);



                $('#category').on('change', onselect_article);
                var indexTable = 0;
                $('#article_id').change(function(){
                    let producto = $('#article_id option:selected').val();
                    let nombre = $('#article_id option:selected').text();
                    // alert(producto)
                    if(producto){
                        addTr(indexTable, producto, nombre);
                        indexTable += 1;
                    }
                });

            })

            function addTr(indexTable, data, name){
            let cantidad_precios = "2";

            $('#table-detalle').append(`
                <tr id="tr-${indexTable}" class="tr-item">
                    <td><input type="hidden" name="article_id[]" class="form-control" value="${data}" required/>${name}</td>
                    <td><input type="number" step="1" min="1" name="precio_compra[]" class="form-control imput-sm" onchange="subTotal(${indexTable})" onkeyup="subTotal(${indexTable})" value="" id="input-precio_compra-${indexTable}" required/></td>
                    <td><input type="number" step="1" min="1" name="cantidad_item[]" class="form-control imput-sm" onchange="subTotal(${indexTable})" onkeyup="subTotal(${indexTable})" value="" id="input-cantidad-item-${indexTable}" required/></td>
                    <td>
                        <input type="hidden" step="1" min="1" name="precio_mayoritario[]" id="precio_mayoritario-${indexTable}" class="form-control imput-sm" required/>
                        <input type="text" step="1" min="1" id="vista-${indexTable}" class="form-control imput-sm" disabled required/>
                    </td>
                    <td>
                        <input type="number" step="1" min="1" name="ganacia_unitaria[]" class="form-control" onchange="subTotal(${indexTable})" onkeyup="subTotal(${indexTable})" id="input-precio_venta_contado-${indexTable}" required/>
                        <small style="font-size: 11px" id="ganancia_contado-${indexTable}"></small>
                    </td>
                    <td><button type="button" style="padding: 0px" onclick="removeTr(${indexTable})" class="btn btn-link"><i class="voyager-trash text-danger"></i></button></td>
                </tr>
            `);
            showHelp();
                $('#article_id').val('').trigger('change');
            }
        
            function subTotal(index){
                let precio_compra = $(`#input-precio_compra-${index}`).val() ? parseFloat($(`#input-precio_compra-${index}`).val()) : 0;
                let contidad_item = $(`#input-cantidad-item-${index}`).val() ? parseFloat($(`#input-cantidad-item-${index}`).val()) : 0;
                // alert(contidad_item)
                let precio_mayoritario =0;
                if(precio_compra !=0 && contidad_item != 0)
                {
                    precio_mayoritario = precio_compra / contidad_item
                }
                $(`#precio_mayoritario-${index}`).val(precio_mayoritario.toFixed(2));
                $(`#vista-${index}`).val(precio_mayoritario.toFixed(2));

                let precio_venta_contado = $(`#input-precio_venta_contado-${index}`).val() ? parseFloat($(`#input-precio_venta_contado-${index}`).val()) : 0;
                // let precio_venta = $(`#input-precio_venta-${index}`).val() ? parseFloat($(`#input-precio_venta-${index}`).val()) : 0;
                // let precio_venta_alt = $(`#input-precio_venta_alt-${index}`).val() ? parseFloat($(`#input-precio_venta_alt-${index}`).val()) : 0;
                
                let ganancia_contado = precio_venta_contado - precio_mayoritario;
                // let ganancia_credito = precio_venta - precio_mayoritario;
                // let ganancia_credito_alt = precio_venta_alt - precio_mayoritario;
                
                $(`#ganancia_contado-${index}`).html(`${ganancia_contado <= 0 ? 'Pérdida' : 'Ganancia'} ${ganancia_contado.toFixed(2)} Bs.`);
                ganancia_contado <= 0 ? $(`#ganancia_contado-${index}`).addClass('text-danger') : $(`#ganancia_contado-${index}`).removeClass('text-danger');

            }
            
            function removeTr(index){
                $(`#tr-${index}`).remove();
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
            
            
        
            function onselect_proveedor_llenar()
            {
                datoProveedor = document.getElementById('provider').value.split('_');
                // alert(datoProveedor[2]);
                $("#provider_id").val(datoProveedor[0]);
                $("#nit").val(datoProveedor[1]);
                $("#responsible").val(datoProveedor[2]);
            }

            function onselect_article()
            {
                // alert(2)
                var id =  $(this).val();    
                if(id >=1)
                {
                    $.get('{{route('wherehouses-ajax.article')}}/'+id, function(data){
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
            
         

        </script> 
    @stop
{{-- 
    @else
    @section('content')
        <h1>No tienes permiso</h1>
    @stop
@endif --}}