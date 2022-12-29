@extends('voyager::master')

@section('page_title', 'Viendo Gym')
@if (auth()->user()->hasPermission('browse_busines'))

@section('page_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body" style="padding: 0px">
                        <div class="col-md-8" style="padding: 0px">
                            <h1 class="page-title">
                                <i class="fa-solid fa-briefcase"></i> Gimnasios
                            </h1>
                            {{-- <div class="alert alert-info">
                                <strong>Información:</strong>
                                <p>Puede obtener el valor de cada parámetro en cualquier lugar de su sitio llamando <code>setting('group.key')</code></p>
                            </div> --}}
                        </div>
                        <div class="col-md-4 text-right" style="margin-top: 30px">
                            @if (auth()->user()->hasRole('admin'))
                                <a href="{{ route('voyager.busines.create') }}" class="btn btn-success">
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
                                        <th style="text-align: center">Gimnasio</th>
                                        <th style="text-align: center">Responsable</th>
                                        <th style="text-align: center">Telefono</th>
                                        <th style="text-align: center">Email</th>
                                        <th style="text-align: center">Direccion</th>
                                        <th style="text-align: center">Estado</th>
                                        <th style="text-align: right">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($busine as $item)
                                        <tr>
                                            <td>{{$item->id}}</td>
                                            <td>
                                                <table>
                                                    @php
                                                        $image = asset('images/default.jpg');
                                                        if($item->image){
                                                            $image = asset('storage/'.str_replace('.', '-cropped.', $item->image));
                                                        }
                                                    @endphp
                                                    <tr>
                                                        <td>
                                                            <img src="{{ $image }}" alt="{{ $item->name }}" style="width: 60px; height: 60px; border-radius: 30px; margin-right: 10px">
                                                        </td>
                                                        <td>
                                                            {{ $item->name }}
                                                            <br>
                                                            <small><b>Nit: {{$item->nit }}</b></small>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                            <td style="text-align: center">{{$item->responsible}}</td>
                                            <td style="text-align: center">{{$item->phone}}</td>
                                            <td style="text-align: center">{{$item->email}}</td>
                                            <td style="text-align: center">{{$item->address}}</td>
                                            <td style="text-align: center">
                                                @if ($item->status == 1)
                                                    <label class="label label-success">Activo</label>
                                                @else
                                                    <label class="label label-warning">Inactivo</label>
                                                @endif
                                            </td>
                                            <td class="no-sort no-click bread-actions text-right">
                                                @if (auth()->user()->hasPermission('user_busines'))
                                                    <a href="{{ route('busines-user.index', ['id' => $item->id]) }}" title="user" class="btn btn-sm btn-success view">
                                                        <i class="fa-solid fa-users"></i> <span class="hidden-xs hidden-sm">User</span>
                                                    </a>
                                                @endif
                                                @if (auth()->user()->hasPermission('read_busines'))
                                                    <a href="{{ route('voyager.busines.show', ['id' => $item->id]) }}" title="Ver" class="btn btn-sm btn-warning view">
                                                        <i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">Ver</span>
                                                    </a>
                                                @endif
                                                @if (auth()->user()->hasPermission('edit_busines'))
                                                    <a href="{{ route('voyager.busines.edit', ['id' => $item->id]) }}" title="Editar" class="btn btn-sm btn-primary edit">
                                                        <i class="voyager-edit"></i> <span class="hidden-xs hidden-sm">Editar</span>
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" style="text-align: center">Sin Datos</td>
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
@endif
