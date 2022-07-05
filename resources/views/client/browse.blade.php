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
                            {{-- @if (auth()->user()->hasPermission('add_people')) --}}
                                <a href="#" data-toggle="modal" data-target="#registar_modal" class="btn btn-success">
                                    <i class="voyager-plus"></i> <span>Registrar</span>
                                </a>
                            {{-- @endif --}}
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
                                        <th style="text-align: center">Plan</th>
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
                                            </td>
                                            <td style="text-align: center">{{ $item->service->name }}</td>
                                            <td style="text-align: center">{{ $item->plan->name }} <br> 
                                                @if ($item->plan_id != 4)
                                                    <b>{{date('d/m/Y', strtotime($item->start))}} Hasta {{date('d/m/Y', strtotime($item->finish))}}</b>
                                                @else
                                                    <small><b>{{ $item->day->name }}</b></small>
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
                                                
                                            </td>
                                            
                                        </tr>
                                    @empty
                                        <tr class="odd">
                                            <td valign="top" colspan="6" class="dataTables_empty">No hay datos disponibles en la tabla</td>
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
         
        })

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
