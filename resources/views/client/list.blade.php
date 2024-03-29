<div class="col-md-12">
    <div class="table-responsive">
        <table id="dataTableStyle" class="table table-hover">
            <thead>
                <tr>
                    <th style="text-align: center">Id</th>
                    <th style="text-align: center">Cliente</th>
                    <th style="text-align: center">Servicios</th>
                    <th style="text-align: center">Detalles</th>
                    <th style="text-align: center">Monto</th>
                    <th style="text-align: center">Estado</th>                                        
                    <th style="text-align: center">Registrado</th>  
                    @if ($type == 'eliminados')
                        <th style="text-align: center">Eliminado</th>                                     
                    @endif                                      
                    <th style="text-align: right">Acciones</th>
                </tr>
            </thead>
            @php
                // dd($cashier);
            @endphp
            <tbody>
                @forelse ($data as $item)
                    <tr>
                        
                        <td style="text-align: center">{{ $item->id }}</td>                                            
                        <td>
                            @if ($item->people_id)                                                    
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
                                    <tr>
                                        <td>
                                            <img src="{{ $image }}" alt="{{ $item->people->first_name }} {{ $item->people->last_name }}" style="width: 60px; height: 60px; border-radius: 30px; margin-right: 10px">
                                        </td>
                                        <td>
                                            CI: <small>{{ $item->people->ci }} <br>
                                            NOMBRE: {{ $item->people->first_name }} {{ $item->people->last_name }}
                                        </td>
                                    </tr>
                                    
                                </table>


                               
                                
                            @else
                            <table>    
                                <tr>
                                    <td>
                                        <img src="{{ asset('images/icono-anonimato.png') }}" alt="" style="width: 60px; height: 60px; border-radius: 30px; margin-right: 10px"><br>
                                        ANONIMO
                                    </td>
                                </tr>
                            </table>                            

                            @endif

                        </td>
                        <td style="text-align: center">{{ $item->service_id ? $item->service->name:'Productos' }}</td>
                        <td style="text-align: center">
                            @if ($item->plan)
                                Plan: {{ $item->plan->name}}
                                <br> 
                                    <b>{{date('d/m/Y', strtotime($item->start))}} <br> Hasta <br>{{date('d/m/Y', strtotime($item->finish))}}</b>
                                 
                            @else

                                @foreach ($item->item as $ar)
                                    <p>
                                        <b>{{$ar->wherehouseDetail->article->name}}-{{$ar->wherehouseDetail->article->presentation}}</b>
                                        <br>
                                        <b>Cantidad: {{$ar->item}}    Venta:{{$ar->amount}}</b>
                                    </p>
                                @endforeach
                            @endif
                                                                 
                        </td>
                        <td style="text-align: center">
                            <p>Total: Bs.{{ $item->amount }}</p>
                            <p>Deuda: Bs.{{number_format($item->subAmount,2)}}</p>
                        </td> 
                        <td style="text-align: center">
                            @if ($item->status == 'pendiente')
                                <label class="label label-warning">Pendiente</label>
                            @endif
                            @if ($item->status == 'pagado')
                                <label class="label label-success">pagado</label>
                            @endif
                            @if ($item->status == 'atrazado')
                                <label class="label label-danger">Atrazado</label>
                            @endif
                        </td>
                        <td style="text-align: center">
                            <p>Atendido Por: {{$item->user->name}}</p>
                            {{date('d/m/Y H:i:s', strtotime($item->created_at))}}<br><small>{{\Carbon\Carbon::parse($item->created_at)->diffForHumans()}}.</small>
                        </td>
                        @if ($type == 'eliminados')
                            <td style="text-align: center">
                                <p>Eliminado Por: {{$item->userDelete->name}}</p>
                                {{date('d/m/Y H:i:s', strtotime($item->deleted_at))}}<br><small>{{\Carbon\Carbon::parse($item->deleted_at)->diffForHumans()}}.</small>
                            </td>
                        @endif
                        <td style="text-align: right">
                            <div class="btn-group">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                    Más <span class="caret"></span>
                                </button>
                              
                                <ul class="dropdown-menu" role="menu">
                                    {{-- @if ($item->amount != $item->subAmount && !auth()->user()->hasRole('admin'))
                                        <li>
                                            <a href="#" data-toggle="modal" data-target="#payment-modal" data-item='@json($item)' title="Pagar" class="btn-payment">
                                                <i class="voyager-dollar"></i> <span class="hidden-xs hidden-sm">Pagar</span>
                                            </a>
                                        </li>
                                    @endif --}}
                                    <li><a href="" class="btn-transaction"  data-toggle="modal" title="Imprimir Calendario" >Lista de Pagos</a></li> 

                                    {{-- <li>
                                        <a href="" target="_blank" title="Imprimir">
                                            <i class="glyphicon glyphicon-print"></i> <span class="hidden-xs hidden-sm">Imprimir</span>
                                        </a>
                                    </li> --}}
                                    <li>
                                        <a onclick="printClient({{$item->id}})"  title="Imprimir"  class="btn btn-danger">
                                            <i class="glyphicon glyphicon-print"></i> <span class="hidden-xs hidden-sm">Imprimir</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                                                                           
                                {{-- @if ($item->status && $item->cashier->status == "abierta" && $cashier->id == $item->cashier_id)
                                    <a href="" title="Editar" class="btn btn-sm btn-primary" data-item="{{ $item}}" data-toggle="modal" data-target="#edit_modal">
                                        <i class="voyager-edit"></i> <span class="hidden-xs hidden-sm">Editar</span>
                                    </a>
                                @endif --}}
                            {{-- @if ($cashier && $item->deleted_at == NULL) --}}

                            @if ($item->subAmount !=0 && $item->deleted_at == NULL && $cashier)
                                <a href="#" data-toggle="modal" data-target="#payment-modal" data-item='@json($item)' title="Abonar Pago"  class="btn btn-sm btn-success">
                                    <i class="voyager-dollar"></i><span class="hidden-xs hidden-sm"> Abonar Pago</span>
                                </a>
                            @endif

                                
                            
                            <a href="#" data-toggle="modal" data-target="#show-modal" data-item='@json($item)' data-user="{{$item->user->name}}" title="Ver" class="btn btn-sm btn-warning view">
                                <i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">Ver</span>
                            </a>
                            @if ($cashier && $item->deleted_at == NULL)
                                @if ($item->status && $item->cashier->status == "abierta" )
                                    <button title="Borrar" class="btn btn-sm btn-danger delete" onclick="deleteItem('{{ route('clients.destroy', ['client' => $item->id]) }}')" data-toggle="modal" data-target="#delete-modal">
                                        <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Borrar</span>
                                    </button>
                                @endif
                            @endif
                            

                            
                        </td>
                        
                    </tr>
                @empty
                    @if ($type == 'eliminados')
                    <tr>
                        <td colspan="10" style="text-align: center">Lista Vacia</td>
                    </tr>
                    @else
                    <tr>
                        <td colspan="9" style="text-align: center">Lista Vacia</td>
                    </tr>
                    @endif

                    
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="col-md-12">
    <div class="col-md-4" style="overflow-x:auto">
        @if(count($data)>0)
            <p class="text-muted">Mostrando del {{$data->firstItem()}} al {{$data->lastItem()}} de {{$data->total()}} registros.</p>
        @endif
    </div>
    <div class="col-md-8" style="overflow-x:auto">
        <nav class="text-right">
            {{ $data->links() }}
        </nav>
    </div>
</div>

<script>
   
   var page = "{{ request('page') }}";
    $(document).ready(function(){
        $('.page-link').click(function(e){
            e.preventDefault();
            let link = $(this).attr('href');
            if(link){
                page = link.split('=')[1];
                list(page);
            }
        });
    });
</script>