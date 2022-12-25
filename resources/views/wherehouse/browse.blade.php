@extends('voyager::master')

@section('page_title', 'Viendo Gimnasio')
@if (auth()->user()->hasPermission('browse_wherehouses'))

@section('page_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body" style="padding: 0px">
                        <div class="col-md-8" style="padding: 0px">
                            <h1 class="page-title">
                                <i class="fa-solid fa-store"></i> Almacen
                            </h1>
                            {{-- <div class="alert alert-info">
                                <strong>Información:</strong>
                                <p>Puede obtener el valor de cada parámetro en cualquier lugar de su sitio llamando <code>setting('group.key')</code></p>
                            </div> --}}
                        </div>
                        <div class="col-md-4 text-right" style="margin-top: 30px">
                            @if (auth()->user()->hasPermission('add_wherehouses'))
                         
                                <a href="{{route('wherehouses.create')}}"  class="btn btn-success">
                                    <i class="voyager-plus"></i> <span>Registrar</span>
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
                                        <th colspan="2"></th>
                                        <th colspan="3" style="text-align: center">Compra</th>        
                                        <th colspan="2" style="text-align: center">Venta</th>                                              
                                        <th style="text-align: right"></th>
                                    </tr>
                                    <tr>
                                        <th style="text-align: center">Id</th>
                                        <th style="text-align: center">Artículo</th>
                                        <th style="text-align: right">Precio de Compra</th>
                                        <th style="text-align: right">Item Comprado</th>                        
                                        <th style="text-align: right">Precio Mayoritario</th>           
                                        <th style="text-align: right">Item Disponible</th>                             
                                        <th style="text-align: right">Ganancia Unitaria</th>                                             
                                        <th style="text-align: right">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($detail as $item)
                                        <tr>
                                            <td style="text-align: center">{{$item->id}}</td>
                                            <td>
                                                <table>
                                                    @php
                                                        $image = asset('images/default.jpg');
                                                        if($item->article->image){
                                                            // dd($item->article->image);
                                                            $image = asset('storage/'.str_replace('.', '_cropped.', $item->article->image));
                                                        }

                                                        // $image = asset('images/default.jpg');

                                                        // if($item->image){
                                                        //     $image = asset('storage/'.$item->image);

                                                        // }
                                                    @endphp


                                                            <img src="{{ $image }}" style="width: 60px; height: 60px; border-radius: 30px; margin-right: 10px">
                                            
                                                            {{$item->article->name}}
                                                </table>
                                            </td>


                                            
                                            <td style="text-align: right">{{$item->amount}}</td>
                                            <td style="text-align: right">{{$item->items}}</td>
                                            <td style="text-align: right">{{$item->unitPrice}}</td>
                                            <td style="text-align: right">
                                                <label @if($item->item > 0) class="label label-success" @else class="label label-danger" @endif> <small>{{$item->item}}</small></label>
                                            </td>
                                            <td style="text-align: right"> <small>Bs. {{$item->itemEarnings}}</small> </td>
                                            <td style="text-align: right">
                                                @if (auth()->user()->hasPermission('delete_wherehouses'))
                                                    <button title="Borrar" class="btn btn-sm btn-danger delete" onclick="deleteItem('{{ route('wherehouses.destroy', ['wherehouse' => $item->id]) }}')" data-toggle="modal" data-target="#delete-modal">
                                                        <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Borrar</span>
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    


  

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
            
            $('#plan_id').on('change',functionDay);
            $('#plan_id1').on('change',functionDay1);
         
        });

        function deleteItem(url){
            $('#delete_form').attr('action', url);
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
@else
    @section('content')
        <h1>No tienes permiso</h1>
    @stop
@endif
