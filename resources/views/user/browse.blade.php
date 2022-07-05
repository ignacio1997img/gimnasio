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
                                <i class="fa-solid fa-users"></i> Users
                            </h1>
                        </div>
                        <div class="col-md-4 text-right" style="margin-top: 30px">
                            {{-- @if (auth()->user()->hasPermission('add_people')) --}}
                                <a href="#" data-toggle="modal" data-target="#registar_modal" class="btn btn-success">
                                    <i class="voyager-plus"></i> <span>Crear</span>
                                </a>
                            {{-- @endif --}}

                            <a href="{{URL::previous()}}" class="btn btn-warning">
                                <i class="fa-solid fa-circle-left"></i>
                                  Volver
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
                                        <th style="text-align: center">Nombre</th>
                                        <th style="text-align: center">Email</th>
                                        <th style="text-align: center">Avatar</th>
                                        <th style="text-align: center">Role</th>
                                        <th style="text-align: center">Estado</th>
                                        <th style="text-align: right">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($user as $item)
                                        <tr>
                                            <td>{{$item->id}}</td>
                                            <td style="text-align: center">{{$item->name}}</td>
                                            <td style="text-align: center">{{$item->email}}</td>

                                            <td style="text-align: center">
                                                <table>
                                                    @php
                                                        // $image = asset('images/default.jpg');
                                                        // if($item->image){
                                                        //     $image = asset('storage/'.str_replace('.', '-cropped.', $item->image));
                                                        // }
                                                        $image = asset('storage/'. $item->avatar);
                                                    @endphp
                                                    
                                                            <img src="{{ $image }}" alt="{{ $item->name }}" style="width: 60px; height: 60px; border-radius: 30px; margin-right: 10px">
                                                        
                                                </table>
                                            </td>
                                            <td style="text-align: center">
                                                @php
                                                    $name = \DB::table('roles')->where('id', $item->role_id)->get();
                                                    // dd($role);
                                                @endphp
                                                {{$name[0]->name}}
                                            </td>
                                           
                                            <td style="text-align: center">
                                                @if ($item->status == 1)
                                                    <label class="label label-success">Activo</label>
                                                @else
                                                    <label class="label label-warning">Inactivo</label>
                                                @endif
                                            </td>
                                            <td class="no-sort no-click bread-actions text-right">
                                                    {{-- <a href="{{ route('voyager.busines.show', ['id' => $item->id]) }}" title="Ver" class="btn btn-sm btn-success view">
                                                        <i class="fa-solid fa-users"></i> <span class="hidden-xs hidden-sm">User</span>
                                                    </a> --}}
                                                {{-- @if (auth()->user()->hasPermission('read_busines'))
                                                    <a href="{{ route('voyager.busines.show', ['id' => $item->id]) }}" title="Ver" class="btn btn-sm btn-warning view">
                                                        <i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">Ver</span>
                                                    </a>
                                                @endif --}}
                                                <a href="#" data-toggle="modal" data-target="#update_modal" data-item="{{$item}}" title="Editar" class="btn btn-sm btn-primary edit">
                                                        <i class="voyager-edit"></i> <span class="hidden-xs hidden-sm">Editar</span>
                                                </a>
                                             
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



       {{-- vault add register modal --}}
    <form id="form-search" action="{{ route('busines-user.store') }}" method="post">
    @csrf        
        <div class="modal fade" tabindex="-1" id="registar_modal" role="dialog">
            <div class="modal-dialog modal-success modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="fa-solid fa-users"></i> Registrar Usuarios</h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="busine_id" value="{{ $busine }}">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <small>Nombre.</small>
                                    <input type="text" name="name" class="form-control" required>
                                </div>
                            </div>  
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <small>Email.</small>
                                    <input type="email" name="email" class="form-control" required>
                                </div>
                            </div>                             
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <small>Contraseña.</small>
                                    <input type="password" name="password" class="form-control" required>
                                </div>
                            </div> 
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <small>Roles.</small>
                                    <select name="role_id" class="form-control select2" required>
                                        <option value="">Seleccione un rol</option>
                                        @foreach ($role as $item)
                                                <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>  
                            
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">Registrar Usuario</button>
                                              
                    </div>
                </div>
            </div>
        </div>
    </form>
    
       {{-- vault add register modal --}}
       <form id="form-search" action="{{ route('busines-user.update') }}" method="post">
        @csrf        
            <div class="modal fade" tabindex="-1" id="update_modal" role="dialog">
                <div class="modal-dialog modal-primary modal-md">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title"><i class="fa-solid fa-users"></i> Actualizar Usuarios</h4>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id" id="id">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <small>Nombre.</small>
                                        <input type="text" name="name" id="name" class="form-control" required>
                                    </div>
                                </div>  
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <small>Email.</small>
                                        <input type="email" name="email" id="email" class="form-control" required>
                                    </div>
                                </div>                             
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <small>Contraseña.</small>
                                        <input type="password" name="password" class="form-control">
                                        <p>Dejar vacío para mantener el mismo</p>
                                    </div>
                                </div> 
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <small>Roles.</small>
                                        <select name="role_id" id="role_id" class="form-control select2" required>
                                            <option value="">Seleccione un rol</option>
                                            @foreach ($role as $item)
                                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>  
                                
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>
                                            <input type="radio" name="status" value="1" checked/>
                                            <span> Activo</span>
                                        </label>
                                        <label>
                                            <input type="radio" name="status" value="0"/>
                                            <span> Inactivo</span>
                                        </label>
                                    </div>
                                </div> 
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Actualizar Usuario</button>
                                                  
                        </div>
                    </div>
                </div>
            </div>
        </form>
        


@stop

@section('css')
<style>

*,
*:after,
*:before {
	box-sizing: border-box;
}

$primary-color: #00005c; // Change color here. C'mon, try it! 
$text-color: mix(#000, $primary-color, 64%);

body {
	font-family: "Inter", sans-serif;
	color: $text-color;
	font-size: calc(1em + 1.25vw);
	background-color: mix(#fff, $primary-color, 90%);
}

form {
	display: flex;
	flex-wrap: wrap;
	flex-direction: column;
}

label {
	display: flex;
	cursor: pointer;
	font-weight: 500;
	position: relative;
	overflow: hidden;
	margin-bottom: 0.375em;
	/* Accessible outline */
	/* Remove comment to use */ 
	/*
		&:focus-within {
				outline: .125em solid $primary-color;
		}
	*/
	input {
		position: absolute;
		left: -9999px;
		&:checked + span {
			background-color: mix(#fff, $primary-color, 84%);
			&:before {
				box-shadow: inset 0 0 0 0.4375em $primary-color;
			}
		}
	}
	span {
		display: flex;
		align-items: center;
		padding: 0.375em 0.75em 0.375em 0.375em;
		border-radius: 99em; // or something higher...
		transition: 0.25s ease;
		&:hover {
			background-color: mix(#fff, $primary-color, 84%);
		}
		&:before {
			display: flex;
			flex-shrink: 0;
			content: "";
			background-color: #fff;
			width: 1.5em;
			height: 1.5em;
			border-radius: 50%;
			margin-right: 0.375em;
			transition: 0.25s ease;
			box-shadow: inset 0 0 0 0.125em $primary-color;
		}
	}
}

// Codepen spesific styling - only to center the elements in the pen preview and viewport
.container {
	position: absolute;
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;
	width: 100%;
	display: flex;
	justify-content: center;
	align-items: center;
	padding: 20px;
}
// End Codepen spesific styling


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

        $('#update_modal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget) //captura valor del data-empresa=""

                var id = button.data('id')
                var item = button.data('item')
           

                var modal = $(this)
                modal.find('.modal-body #id').val(item.id)
                modal.find('.modal-body #name').val(item.name)
                modal.find('.modal-body #email').val(item.email)
                modal.find('.modal-body #role_id').val(item.role_id).trigger('change')                
            });
    </script>
@stop
