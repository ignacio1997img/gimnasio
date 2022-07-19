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
                                        <form action="{{route('wherehouses.store')}}" id="delete_form" method="POST">
                                        {{ method_field('DELETE') }}
                                        {{ csrf_field() }}
                                        <div class="card-body container">
                                            <h5 id="subtitle">Proveedor + Detalle de Factura:</h5>
                                          
                                            <div class="row">          
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <input type="number" id="nrofactura" name="nrofactura" placeholder="Introducir Numero" class="form-control text" title="Introducir Nro de Factura" required>
                                                        </div>
                                                        <small>Numero Factura (Opcional).</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <select id="provider" name="provider_id" class="form-control select2" required>
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
                                                            <input type="text" id="nit" class="form-control form-control-sm text" placeholder="Seleccione un Proveedor" disabled readonly>
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
                                            <div class="row">   
                                                                                             
                                                <div class="col-sm-6" id="radio">
                                                    <h5 id="subtitle">Compra:</h5>
                                                    <div class="col-sm-7">
                                                        <div class="form-group">
                                                            <div class="form-line">
                                                                <textarea class="form-control" id="details" rows="2"></textarea>
                                                            </div>
                                                            <small>Detalles.</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-5">
                                                        <div class="form-group">
                                                            <div class="form-line">
                                                                <input type="number" id="preciocompra" placeholder="Precio de Compra" class="form-control text" title="Cantidad">
                                                            </div>
                                                            <small>Precio Compra *(Bs).</small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6" id="radio">
                                                    <h5 id="subtitle">Venta:</h5>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <div class="form-line">
                                                                <input type="number" id="cantidad" placeholder="Cantidad de Items" class="form-control text" title="Cantidad">
                                                            </div>
                                                            <small>Cantidad Items (Unidad).</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <div class="form-line">
                                                                <input type="number" id="precio" placeholder="Precio Unitario" class="form-control text" title="Precio Unitario Bs">
                                                            </div>
                                                            <small>Precio Unitario del Item *(Bs).</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <button type="button" id="bt_add" class="btn btn-success"><i class="voyager-basket"></i> Agregar Artículo</button>
                                                </div>
                                            </div>
                                        
                                            <table id="dataTable" class="table table-bordered table-striped table-sm">
                                                <thead>
                                                    <tr>
                                                        <th colspan="2"></th>
                                                        <th colspan="2" style="text-align: center">Compra</th>
                                                        <th colspan="2" style="text-align: center">Venta</th>
                                                        <th></th>                    
                                                    </tr>
                                                    <tr>
                                                        <th>Opciones</th>
                                                        <th>Articulo</th>
                                                        <th>Detalle</th>
                                                        <th style="text-align: right">Precio Compra</th>
                                                        <th style="text-align: right">Cantidad Items (Unidad).</th>
                                                        <th style="text-align: right">Precio Unitario del Item *(Bs)</th>
                                                        <th style="text-align: right">SubTotal</th>                    
                                                    </tr>
                                                </thead>
                                                <tfoot>
                                                    <th colspan="2" style="text-align:right"><h5>TOTAL</h5></th>
                                                    <th colspan="2" style="text-align: right"><h4 id="total">Bs. 0.00</h4></th>
                                                    <th colspan="3" style="text-align: right"><h4 id="totalventa">Bs. 0.00</h4></th>
                                                    <input type="text" step="0.01" name="total" id="totals">
                                                </tfoot>
                                                
                                            </table>
                                            
                                        </div>   
                                        <div class="card-footer">
                                            <button id="btn_guardar" disabled type="submit"  class="btn btn-primary"><i class="fas fa-save"></i> Guardar</button>
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

                $('#bt_add').click(function() {
                    agregar();
                    // alert(3);
                });
                // alert(6);


            })
            var cont=0;
            var total=0;
            subtotal=[];

            function agregar()
            {
                article =$("#article_id option:selected").text();
                article_id=$("#article_id").val();

                montofactura=$("#montofactura").val();

                detalle = $('#details').val();
                preciocompra=$("#preciocompra").val();

                cantidad=$("#cantidad").val();
                precio=$("#precio").val();

                var arrayarticle = [];
                var i=0;
                var j=0;
                ok=false;

              
                if (article != 'Seleccione un Articulo..' && cantidad != "" && precio != "" && preciocompra != "") {                   
                  

                    var fila='<tr class="selected" id="fila'+article_id+'">'
                            fila+='<td><button type="button" class="btn btn-danger" onclick="eliminar('+article_id+')";><i class="voyager-trash"></i></button></td>'
                            fila+='<td><input type="hidden" class="input_article" name="article_id[]"value="'+article_id+'">'+article+'</td>'
                            fila+='<td><input type="hidden" name="details[]"value="'+detalle+'">'+detalle+'</td>'
                            fila+='<td style="text-align: right"><input type="hidden" name="preciocompra[]"value="'+preciocompra+'">'+preciocompra+'</td>'
                            fila+='<td style="text-align: right"><input type="hidden" name="cantidad[]" value="'+cantidad+'">'+cantidad+'</td>'
                            fila+='<td style="text-align: right"><input type="hidden" name="precio[]" value="'+precio+'">'+precio+'</td>'
                            fila+='<td style="text-align: right"><input type="hidden" class="input_subtotal" value="'+preciocompra+'">'+preciocompra+'</td>'
                        fila+='</tr>';
                    
                    let detalle_subtotal = parseFloat(calcular_total()+preciocompra ).toFixed(2);
                    alert(detalle_subtotal)
                    // let monto_factura = parseFloat($('#montofactura').val());
                    // $('#dataTable').append(fila);
                    
                    // if (detalle_subtotal <= monto_factura)
                    // {   
                        // alert('si')
                        $(".input_article").each(function(){
                            arrayarticle[i]= parseFloat($(this).val());
                            i++;

                        }); 
                        var ok=true;
                        for(j=0;j<arrayarticle.length; j++)
                        {
                            
                            if(arrayarticle[j] == article_id)
                            {
                                limpiar();
                                ok = false;
                                swal({
                                    title: "Error",
                                    text: "El Articulo ya Existe en la Lista",
                                    type: "error",
                                    showCancelButton: false,
                                    });
                                div = document.getElementById('flotante');
                                div.style.display = '';
                                return;                                
                            }
                        }
                        if(ok==true)
                        {
                            limpiar();
                            $('#dataTable').append(fila);
                            $("#total").html("Bs. "+calcular_total().toFixed(2));
                            $("#totals").val(calcular_total().toFixed(2));
                            if (calcular_total().toFixed(2)==monto_factura.toFixed(2)) {
                                $('#btn_guardar').removeAttr('disabled');
                            }
                            else
                            {
                                $('#btn_guardar').attr('disabled', true);
                            }
                        }
                    // }
                    // else
                    // {
                    //     swal({
                    //         title: "Error",
                    //         text: "El monto total supera al monto de la factura",
                    //         type: "error",
                    //         showCancelButton: false,
                    //         });
                    //     div = document.getElementById('flotante');
                    //     div.style.display = '';
                    //     return;
                    // }
                }
                else
                {
                    swal({
                            title: "Error",
                            text: "Rellene los Campos de las Seccion de Articulo",
                            type: "error",
                            showCancelButton: false,
                            });
                        div = document.getElementById('flotante');
                        div.style.display = '';
                        return;
                }

            }        
            function limpiar()
            {
                $("#precio").val("");
                $("#cantidad").val("");
            }


            function eliminar(index)
            {
                $("#fila" + index).remove();
                $("#total").html("Bs. "+calcular_total().toFixed(2));
                $("#totals").val(calcular_total().toFixed(2));
                $('#btn_guardar').attr('disabled', true);
            }

            function calcular_total()
            {
                let total = 0;
                $(".input_subtotal").each(function(){
                    total += parseFloat($(this).val());
                });
                // console.log(total);
                
                return total;
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