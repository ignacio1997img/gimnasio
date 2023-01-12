@extends('voyager::master')

@section('page_title', 'Viendo Planes')
@if (auth()->user()->hasPermission('browse_hour'))
@section('page_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body" style="padding: 0px">
                        <div class="col-md-8" style="padding: 0px">
                            <h1 class="page-title">
                                <i class="fa-solid fa-clock"></i> Horarios [{{$service->name}}]
                            </h1>
                        </div>

                        <div class="col-md-4 text-right" style="margin-top: 30px">
                            <a href="{{route('voyager.services.index')}}" title="Volver"  data-toggle="modal" class="btn btn-warning">
                                <i class="fa-solid fa-arrow-rotate-left"></i><span> Volver</span>
                            </a>
                            @if (auth()->user()->hasPermission('add_hour') && !auth()->user()->hasRole('admin'))
                                <a href="#" title="Nuevo turnos" data-target="#modal-create-shift" data-toggle="modal" class="btn btn-success">
                                    <i class="voyager-plus"></i> <span>Crear</span>
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
                        <div class="table-responsive">
                            <table id="dataTable" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th style="text-align: center">Id</th>
                                        <th style="text-align: center">Horarios</th>
                                        <th style="text-align: center">Descripción</th>
                                        <th style="text-align: center">Estado</th>     
                                        <th style="text-align: right">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($hour as $item)
                                        <tr>
                                            <td style="text-align: center">{{ $item->id }}</td>   
                                            <td style="text-align: center">{{ $item->name }}</td>   
                                            <td style="text-align: center">{{ $item->description }}</td>
                                            <td style="text-align: center">
                                                @if ($item->status)
                                                    <label class="label label-success">Activo</label>
                                                @else
                                                    <label class="label label-warning">Inactivo</label>
                                                @endif
                                            </td>
                                            <td class="no-sort no-click bread-actions text-right">
                                                <a href="{{route('service-hour-instructor.index', ['service'=>$service->id, 'hour'=>$item->id])}}" title="instructor" class="btn btn-sm btn-dark">
                                                    <i class="fa-solid fa-person-chalkboard"></i><span class="hidden-xs hidden-sm"> Instructor</span>
                                                </a>
                                                {{-- @if (auth()->user()->hasPermission('edit_shifts')) --}}
                                                    <a data-toggle="modal" data-target="#modal-edit-shift" data-item="{{$item}}" title="Editar" class="btn btn-sm btn-primary edit">
                                                        <i class="voyager-edit"></i> <span class="hidden-xs hidden-sm">Editar</span>
                                                    </a>
                                                {{-- @endif --}}


                                            </td>
                                            
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" style="text-align: center">Sin Datos</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>





    <form action="{{route('service-hour.store')}}" id="form-create-plan" method="POST">
        <div class="modal fade" tabindex="-1" id="modal-create-shift" role="dialog">
            <div class="modal-dialog modal-success">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="fa-solid fa-clock"></i> Agregar Horarios</h4>
                    </div>
                    <div class="modal-body">
                        @csrf
                        @php
                            $busine_id =\Auth::user()->busine_id;
                        @endphp
                        <input type="hidden" name="service_id" value="{{$service->id}}">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <small>Horarios</small>
                                    <input type="text" name="name" class="form-control" required placeholder="Horarios">
                                </div>
                            </div> 
                        </div>  
                        <div class="form-group">
                            <small>Descripcion</small>
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <input type="submit" class="btn btn-success btn-save-customer" value="Guardar">
                    </div>
                </div>
            </div>
        </div>
    </form>

    <form action="{{route('service-hour.update')}}" id="form-edit-plan" method="POST">
        <div class="modal fade" tabindex="-1" id="modal-edit-shift" role="dialog">
            <div class="modal-dialog modal-primary">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="fa-solid fa-clock"></i> Editar Horarios</h4>
                    </div>
                    <div class="modal-body">
                        @csrf
                        @php
                            $busine_id =\Auth::user()->busine_id;
                        @endphp
                        <input type="hidden" name="id" id="id">
                        <input type="hidden" name="service_id" value="{{$service->id}}">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <small>Turno</small>
                                    <input type="text" name="name" id="turno" class="form-control" required placeholder="Turno">
                                </div>
                            </div> 
                        </div>  
                        <div class="form-group">
                            <small>Descripcion</small>
                            <textarea name="description" id="description" class="form-control" rows="3"></textarea>
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
                                        checked
                                        name="status">
                                </div>
                            </div>   
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
  background-color: #5eaf4a;
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


</style>
@stop

@section('javascript')
    <script src="{{ url('js/main.js') }}"></script>
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
            
         
        })

        $('#modal-edit-shift').on('show.bs.modal', function (event)
            {
                var button = $(event.relatedTarget);
                var item = button.data('item');
                var modal = $(this);
                modal.find('.modal-body #id').val(item.id);
                modal.find('.modal-body #turno').val(item.name);
                modal.find('.modal-body #description').val(item.description);
                // alert(item.status)
                if(item.status==1)
                {
                    $('#status').prop('checked', true).change()

                }
                else
                {
                    $("#status").prop('checked', false).change();
                }
            })
    </script>
@stop
@endif
