@extends('voyager::master')

@section('page_title', 'Viendo Servicios')

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
                            @if (auth()->user()->hasPermission('add_services') && !auth()->user()->hasRole('admin'))
                                <a href="{{ route('voyager.services.create') }}" class="btn btn-success">
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
                                        <th style="text-align: center">Servicio</th>
                                        @if (auth()->user()->hasRole('admin'))
                                            <th style="text-align: center">Gymnacio</th>
                                        @endif
                                        <th style="text-align: center">Estado</th>     
                                        @if (!auth()->user()->hasRole('admin') && auth()->user()->hasPermission('add_providers'))
                                            <th style="text-align: right">Acciones</th>
                                        @endif                                
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($service as $item)
                                        <tr>
                                            <td style="text-align: center">{{ $item->id }}</td>   
                                            <td style="text-align: center">{{ $item->name }}</td>   
                                            @if (auth()->user()->hasRole('admin'))
                                                <td style="text-align: center">{{ $item->busine->name }}</td>
                                            @endif
                                            <td style="text-align: center">
                                                @if ($item->status)
                                                    <label class="label label-success">Activo</label>
                                                @else
                                                    <label class="label label-warning">Inactivo</label>
                                                @endif
                                            </td>
                                            <td class="no-sort no-click bread-actions text-right">
                                                <a href="{{route('service-hour.index', ['service'=>$item->id])}}" title="Horarios" class="btn btn-sm btn-dark">
                                                    <i class="fa-solid fa-clock"></i> <span class="hidden-xs hidden-sm">Horas</span>
                                                </a>
                                                    @if (auth()->user()->hasPermission('browse_plans'))
                                                        <a href="{{ route('service-plans.index', ['service' => $item->id]) }}" title="Ver Planes" class="btn btn-sm btn-warning view">
                                                            <i class="fa-solid fa-list"></i> <span class="hidden-xs hidden-sm">Planes</span>
                                                        </a>
                                                    @endif
                                                    {{-- @if (auth()->user()->hasPermission('read_services'))
                                                        <a href="{{ route('voyager.services.show', ['id' => $item->id]) }}" title="Ver" class="btn btn-sm btn-warning view">
                                                            <i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">Ver</span>
                                                        </a>
                                                    @endif --}}
                                                    @if (auth()->user()->hasPermission('edit_services'))
                                                        <a href="{{ route('voyager.services.edit', ['id' => $item->id]) }}" title="Editar" class="btn btn-sm btn-primary edit">
                                                            <i class="voyager-edit"></i> <span class="hidden-xs hidden-sm">Editar</span>
                                                        </a>
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

        // @if(session('rotation_id'))
        //     let rotation_id = "{{ session('rotation_id') }}";
        //     window.open(`{{ url('admin/people/rotation') }}/${rotation_id}`, '_blank').focus();
        // @endif
    </script>
@stop
