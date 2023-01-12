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
                                <i class="fa-solid fa-person-chalkboard"></i> Instructores [{{$service->name}}-{{$hour->name}}]
                            </h1>
                        </div>

                        <div class="col-md-4 text-right" style="margin-top: 30px">
                            <a href="#" title="Nuevo cliente" data-target="#registar_modal" data-toggle="modal" class="btn btn-success">
                                <i class="voyager-plus"></i> <span>Crear</span>
                            </a>
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
                                        <th style="text-align: center">Instructor</th>
                                        <th style="text-align: center">Descripción</th>
                                        <th style="text-align: center">Estado</th>     
                                        <th style="text-align: right">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($hourInst as $item)
                                        <tr>
                                            <td style="text-align: center">{{ $item->id }}</td>   
                                            <td style="text-align: center">{{ $item->instructor->people->first_name }} {{ $item->instructor->people->first_name }}</td>   
                                            <td style="text-align: center">{{ $item->description }}</td>
                                            <td style="text-align: center">
                                                @if ($item->status)
                                                    <label class="label label-success">Activo</label>
                                                @else
                                                    <label class="label label-warning">Inactivo</label>
                                                @endif
                                            </td>
                                            <td class="no-sort no-click bread-actions text-right">
                                                @if ($item->status == 0)
                                                    <button title="habilitar" class="btn btn-sm btn-success delete" onclick="habilitarItem('{{ route('service-hour-instructor.activo', ['service' => $service->id, 'hour' => $hour->id, 'id' => $item->id]) }}')" data-toggle="modal" data-target="#habilitar-modal">
                                                        <i class="fa-solid fa-thumbs-up"></i> <span class="hidden-xs hidden-sm">Habilitar</span>
                                                    </button>
                                                @endif
                                                @if ($item->status == 1)
                                                    <button title="inabilitar" class="btn btn-sm btn-warning delete" onclick="inhabilitarItem('{{ route('service-hour-instructor.inactivo', ['service' => $service->id, 'hour' => $hour->id, 'id' => $item->id]) }}')" data-toggle="modal" data-target="#inhabilitar-modal">
                                                        <i class="fa-solid fa-thumbs-down"></i> <span class="hidden-xs hidden-sm">Desabilitar</span>
                                                    </button>
                                                @endif

                                            </td>
                                            
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" style="text-align: center">Sin Datos</td>
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





    <form id="form-search" action="{{ route('service-hour-instructor.store') }}" method="post">
        @csrf
        <input type="hidden" name="service" value="{{$service->id}}">
        <input type="hidden" name="hour" value="{{$hour->id}}">
        <div class="modal fade" id="registar_modal" role="dialog">
            <div class="modal-dialog modal-success">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="fa-solid fa-person-chalkboard"></i>  Registrar Instructores</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <small>Personas.</small>
                                    <select name="instructor_id" class="form-control select2" required>
                                        <option selected disabled value="">--Seleccione una persona--</option>
                                        @foreach ($instructor as $item)
                                            <option value="{{$item->id}}">{{$item->people->first_name}} {{$item->people->last_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>                              
                        </div>
                        <div class="form-group">
                            <small>Descripcion</small>
                            <textarea name="description" id="description" class="form-control" rows="3"></textarea>
                        </div>
                         
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">Registrar Instructor</button>
                    </div>
                </div>
            </div>
        </div>
    </form>


    <div class="modal modal-success fade" data-backdrop="static" tabindex="-1" id="habilitar-modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa-solid fa-user"></i> Desea habilitar el siguiente registro?</h4>
                </div>
                <div class="modal-footer">
                    <form action="#" id="habilitar_form" method="GET">
                            <div class="text-center" style="text-transform:uppercase">
                                <i class="fa-solid fa-thumbs-up" style="color: #1abc9c; font-size: 5em;"></i>
                                <br>
                                
                                <p><b>Desea habilitar el siguiente registro?</b></p>
                            </div>
                        <input type="submit" class="btn btn-success pull-right delete-confirm" value="Sí, habilitar">
                    </form>
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-warning fade" data-backdrop="static" tabindex="-1" id="inhabilitar-modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa-solid fa-user"></i> Desea Inhabilitar el siguiente registro?</h4>
                </div>
                <div class="modal-footer">
                    <form action="#" id="inhabilitar_form" method="GET">

                            <div class="text-center" style="text-transform:uppercase">
                                <i class="fa-solid fa-thumbs-down" style="color: #fabe28; font-size: 5em;"></i>
                                <br>
                                
                                <p><b>Desea inhabilitar el siguiente registro?</b></p>
                            </div>
                        <input type="submit" class="btn btn-warning pull-right delete-confirm" value="Sí, inhabilitar">
                    </form>
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>




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


        function inhabilitarItem(url){
            $('#inhabilitar_form').attr('action', url);
        }

        function habilitarItem(url){
            $('#habilitar_form').attr('action', url);
        }

    </script>
@stop
@endif
