@extends('voyager::master')

@section('page_title', 'Viendo Planes')

@section('page_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body" style="padding: 0px">
                        <div class="col-md-8" style="padding: 0px">
                            <h1 class="page-title">
                                <i class="fa-solid fa-list"></i>  Planes {{$service->name}}
                            </h1>
                            {{-- <div class="alert alert-info">
                                <strong>Información:</strong>
                                <p>Puede obtener el valor de cada parámetro en cualquier lugar de su sitio llamando <code>setting('group.key')</code></p>
                            </div> --}}
                        </div>
                        <div class="col-md-4 text-right" style="margin-top: 30px">
                            @if (auth()->user()->hasPermission('add_plans') && !auth()->user()->hasRole('admin'))
                                <a href="#" title="Nuevo Plan" data-target="#modal-create-plan" data-toggle="modal" class="btn btn-success">
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
                            <table id="dataTableStyle" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th style="text-align: center">Id</th>
                                        <th style="text-align: center">Plan</th>
                                        <th style="text-align: center">Total de Dias</th>
                                        <th style="text-align: center">Monto Total</th>
                                        <th style="text-align: center">Estado</th>     
                                        <th style="text-align: right">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($plans as $item)
                                        <tr>
                                            <td style="text-align: center">{{ $item->id }}</td>   
                                            <td style="text-align: center">{{ $item->name }}</td>   
                                            <td style="text-align: center">{{ $item->day?$item->day:'S/N' }}</td>
                                            <td style="text-align: center">{{ $item->amount?number_format($item->amount,2):'S/N' }}</td>
                                            <td style="text-align: center">
                                                @if ($item->status)
                                                    <label class="label label-success">Activo</label>
                                                @else
                                                    <label class="label label-warning">Inactivo</label>
                                                @endif
                                            </td>
                                            <td class="no-sort no-click bread-actions text-right">
                                                    
                                                    {{-- @if (auth()->user()->hasPermission('read_services'))
                                                        <a href="{{ route('voyager.services.show', ['id' => $item->id]) }}" title="Ver" class="btn btn-sm btn-warning view">
                                                            <i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">Ver</span>
                                                        </a>
                                                    @endif --}}
                                                @if (auth()->user()->hasPermission('edit_plans'))
                                                    <a data-toggle="modal" data-target="#modal-edit-plan" data-item="{{$item}}" title="Editar" class="btn btn-sm btn-primary edit">
                                                        <i class="voyager-edit"></i> <span class="hidden-xs hidden-sm">Editar</span>
                                                    </a>
                                                @endif
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





    <form action="{{route('service-plans.store')}}" id="form-create-plan" method="POST">
        <div class="modal fade" tabindex="-1" id="modal-create-plan" role="dialog">
            <div class="modal-dialog modal-success">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="fa-solid fa-list"></i> Agregar Plan</h4>
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
                                    <small>Nombre</small>
                                    <input type="text" name="name" class="form-control" required placeholder="Zumba">
                                </div>
                            </div> 

                            <div class="col-sm-3">
                                <div class="form-group">
                                    <small>Cant Días</small>
                                    <input type="number" name="day" class="form-control" style="text-align: right">
                                </div>
                            </div> 
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <small>Monto</small>
                                    <input type="number" name="amount" class="form-control" style="text-align: right">
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

    <form action="{{route('service-plans.update')}}" id="form-edit-plan" method="POST">
        <div class="modal fade" tabindex="-1" id="modal-edit-plan" role="dialog">
            <div class="modal-dialog modal-primary">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="fa-solid fa-list"></i> Editar Plan</h4>
                    </div>
                    <div class="modal-body">
                        @csrf
                        @php
                            $busine_id =\Auth::user()->busine_id;
                        @endphp
                        <input type="hidden" name="service_id" value="{{$service->id}}">
                        <input type="hidden" name="id" id="id">

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <small>Nombre</small>
                                    <input type="text" name="name" id="name" class="form-control" required placeholder="Zumba">
                                </div>
                            </div> 

                            <div class="col-sm-3">
                                <div class="form-group">
                                    <small>Cant Días</small>
                                    <input type="number" name="day" id="day" class="form-control" style="text-align: right">
                                </div>
                            </div> 
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <small>Monto</small>
                                    <input type="number" name="amount" id="amount" class="form-control" style="text-align: right">
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
                        <input type="checkbox" data-toggle="toggle" data-size="small" id="checkbox1">
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
        var countPage = 10, order = 'id', typeOrder = 'desc';
        $(document).ready(() => {
            list();
            
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
            // $('#div-results').loading({message: 'Cargando...'});
            var loader = '<div class="col-md-12 bg"><div class="loader" id="loader-3"></div></div>'
            $('#div-results').html(loader);


            let url = '{{ url("admin/people/ajax/list") }}';
            let search = $('#input-search').val() ? $('#input-search').val() : '';
            $.ajax({
                url: `${url}/${search}?paginate=${countPage}&page=${page}`,
                type: 'get',
                success: function(response){
                    $('#div-results').html(response);
                    // $('#div-results').loading('toggle');

                }
            });
        }

        $('#modal-edit-plan').on('show.bs.modal', function (event)
            {
                var button = $(event.relatedTarget);
                var item = button.data('item');
                var modal = $(this);
                modal.find('.modal-body #id').val(item.id);
                modal.find('.modal-body #name').val(item.name);
                modal.find('.modal-body #day').val(item.day);
                modal.find('.modal-body #amount').val(item.amount);
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
