@extends('voyager::master')

@section('page_title', 'Viendo Articulos')
@if (auth()->user()->hasPermission('browse_articles'))
@section('page_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body" style="padding: 0px">
                        <div class="col-md-8" style="padding: 0px">
                            <h1 class="page-title">
                                <i class="fa-solid fa-prescription-bottle"></i> Articulos
                            </h1>
                        </div>
                        <div class="col-md-4 text-right" style="margin-top: 30px">
                            @if (!auth()->user()->hasRole('admin') && auth()->user()->hasPermission('add_articles'))
                                <a href="#" data-toggle="modal" data-target="#registar_modal" class="btn btn-success">
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
                                        <th style="text-align: center">Id</th>
                                        <th style="text-align: center">Categoria</th>
                                        <th style="text-align: center">Articulo</th>
                                        <th style="text-align: center">Persentación</th>
                                        @if (auth()->user()->hasRole('admin'))
                                            <th style="text-align: center">Gymnacio</th>
                                        @endif
                                        <th style="text-align: center">Estado</th>     
                                        @if (!auth()->user()->hasRole('admin'))
                                            <th style="text-align: right">Acciones</th>
                                        @endif                                
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($article as $item)
                                        <tr>
                                            <td style="text-align: center">{{ $item->id }}</td>   
                                            <td style="text-align: center">{{ $item->category->name }}</td>
                                            <td>
                                                <table>
                                                    @php
                                                        $image = asset('images/default.jpg');
                                                        if($item->image){
                                                            $image = asset('storage/'.$item->image);

                                                        }
                                                        // dd($image);
                                                        // dd($item->image);
                                                        // $now = \Carbon\Carbon::now();
                                                        // $birthdate = new \Carbon\Carbon($item->birthdate);
                                                        // $age = $birthdate->diffInYears($now);
                                                    @endphp
                                                    {{-- <tr>
                                                        <td> --}}
                                                            <img src="{{ $image }}" alt="" style="width: 60px; height: 60px; border-radius: 30px; margin-right: 10px">
                                                        {{-- </td>
                                                        <td> --}}
                                                            {{ $item->name }}
                                                        {{-- </td>
                                                    </tr> --}}
                                                </table>
                                            </td>
                                            {{-- <td style="text-align: center">{{ $item->name }}</td> --}}
                                            <td style="text-align: center">{{ $item->presentation }}</td>
                                            @if (auth()->user()->hasRole('admin'))
                                                <td style="text-align: center">{{ $item->category->busine->name }}</td>
                                            @endif
                                            <td style="text-align: center">
                                                @if ($item->status)
                                                    <label class="label label-success">Activo</label>
                                                @else
                                                    <label class="label label-warning">Inactivo</label>
                                                @endif
                                            </td>
                                            @if (!auth()->user()->hasRole('admin'))
                                                <td style="text-align: right">
                                                        <a href="" title="Editar" class="btn btn-sm btn-primary" data-item="{{ $item}}" data-toggle="modal" data-target="#edit_modal">
                                                            <i class="voyager-edit"></i> <span class="hidden-xs hidden-sm">Editar</span>
                                                        </a>
                                                    
                                                </td>
                                            @endif
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
    


        {{-- vault add register modal --}}
        <form id="form-search" action="{{ route('articles.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            {{-- <input type="hidden" name="cashier_id" value="{{ $cashier? $cashier->id:'' }}"> --}}
            <div class="modal fade" tabindex="-1" id="registar_modal" role="dialog">
                <div class="modal-dialog modal-success modal-md">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title"><i class="fa-solid fa-prescription-bottle"></i> Registrar Articulo</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <small>Categorias.</small>
                                        <select name="category_id" class="form-control select2" required>
                                            <option value="">Seleccione una categoria.</option>
                                            @foreach ($category as $item)
                                                <option value="{{$item->id}}">{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>   
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <small>Articulo.</small>
                                        <input type="text" name="name" class="form-control" required>
                                    </div>
                                </div>   
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <small>Persentacion.</small>
                                        <input type="text" name="presentation" class="form-control" required>
                                    </div>
                                </div>   
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <small>Imagen.</small>
                                        <input accept="image/png,image/jpeg" type="file" name="image" class="form-control" required>
                                    </div>
                                </div>   
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-success">Registrar</button>
                                  
                        </div>
                    </div>
                </div>
            </div>
        </form>

            {{-- vault edit register modal --}}
            <form id="form-search" action="{{ route('articles.update') }}" method="post" enctype="multipart/form-data">
                @csrf
                {{-- <input type="hidden" name="cashier_id" value="{{ $cashier? $cashier->id:'' }}"> --}}
                <div class="modal fade" tabindex="-1" id="edit_modal" role="dialog">
                    <div class="modal-dialog modal-primary modal-md">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title"><i class="fa-solid fa-prescription-bottle"></i> Editar Articulo</h4>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="id" id="id">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <small>Categorias.</small>
                                            <select name="category_id" id="category_id" class="form-control select2">
                                                <option value="">Seleccione una categoria.</option>
                                                @foreach ($category as $item)
                                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>   
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <small>Articulo.</small>
                                            <input type="text" name="name" id="name" class="form-control">
                                        </div>
                                    </div>   
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <small>Persentacion.</small>
                                            <input type="text" name="presentation" id="presentation" class="form-control">
                                        </div>
                                    </div>   
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <small>Imagen.</small>
                                            <input accept="image/png,image/jpeg" type="file" name="image" class="form-control">
                                        </div>
                                    </div>   
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <input 
                                                type="checkbox" 
                                                
                                                id="status" 
                                                data-toggle="toggle" 
                                                data-on="Activo" 
                                                data-off="Inactivo"
                                                checked name="status">
                                        </div>
                                    </div>   
                                </div>
                                
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Actualizar</button>
                                      
                            </div>
                        </div>
                    </div>
                </div>
            </form>


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
                modal.find('.modal-body #name').val(item.name)
                modal.find('.modal-body #presentation').val(item.presentation)
                modal.find('.modal-body #category_id').val(item.category_id).trigger('change')
                modal.find('.modal-body #status').val(item.status).trigger('cheked')

            });
            
    </script>
@stop
@else
    @section('content')
        <h1>No tienes permiso</h1>
    @stop
@endif