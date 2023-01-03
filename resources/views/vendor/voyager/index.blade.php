@extends('voyager::master')

@section('content')
    <div class="page-content">
        @include('voyager::alerts')
        {{-- Vista de cajero(a) --}}
        @if (Auth::user()->role_id == 3 || Auth::user()->role_id == 4)
            @php
                $cashier = \App\Models\Cashier::with(['movements' => function($q){
                    $q->where('deleted_at', NULL);
                }, 'vault_details.cash' => function($q){
                    $q->where('deleted_at', NULL);
                }, 'client'])
                ->where('user_id', Auth::user()->id)
                ->where('status', '<>', 'cerrada')
                ->where('deleted_at', NULL)->first();
                // dd($cashier);
            @endphp
            @if ($cashier)
            
                @if ($cashier->status == 'abierta' || $cashier->status == 'apertura pendiente')
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-bordered">
                                <div class="panel-body">
                                    @php
                                        $cashier_in = $cashier->movements->where('type', 'ingreso')->where('deleted_at', NULL)->sum('amount');
                                        // dd($cashier_in);
                                        // $cashier_out = $cashier->movements->where('type', 'egreso')->where('deleted_at', NULL)->sum('amount');
                                        // $payments = $cashier->payments->where('deleted_at', NULL)->sum('amount');
                                        

                                        // $amount = $cashier->client->where('deleted_at', null)->sum('amount');
                                        $amount = \App\Models\Adition::where('deleted_at', null)->where('cashier_id', $cashier->id)->sum('cant');

                                        // dd($amount);
                                        // $amount =2;
                                        $movements = $cashier_in + $amount;
                                        // $total = $movements - $payments;
                                        $total = $movements;


                                    @endphp
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h2>{{ $cashier->title }}</h2>
                                        </div>
                                        @if ($cashier->status == 'abierta')
                                            <div class="col-md-6 text-right">
                                                {{-- <button type="button" data-toggle="modal" data-target="#transfer-modal" class="btn btn-success">Transferir <i class="voyager-forward"></i></button> --}}
                                                <a href="{{ route('cashiers.close', ['cashier' => $cashier->id]) }}" class="btn btn-danger">Cerrar <i class="voyager-lock"></i></a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if ($cashier->status == 'abierta')
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-bordered">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-6" style="margin-top: 50px">
                                                <table width="100%" cellpadding="20">
                                                    <tr>
                                                        <td><small>Dinero Asignado a Caja</small></td>
                                                        <td class="text-right"><h3>{{ number_format($cashier_in, 2, ',', '.') }} <small>Bs.</small></h3></td>
                                                    </tr>
                                                    <tr>
                                                        <td><small>Servicios Atendido</small></td>
                                                        <td class="text-right"><h3>{{ number_format($amount, 2, ',', '.') }} <small>Bs.</small></h3></td>
                                                    </tr>
                                                    {{-- <tr>
                                                        <td><small>Pagos realizados ({{ $cashier->payments->where('deleted_at', NULL)->count() }})</small></td>
                                                        <td class="text-right"><h3>{{ number_format($payments, 2, ',', '.') }} <small>Bs.</small></h3></td>
                                                    </tr> --}}
                                                    <tr>
                                                        <td><small>TOTAL EN CAJA</small></td>
                                                        <td class="text-right"><h3>{{ number_format($total, 2, ',', '.') }} <small>Bs.</small></h3></td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="col-md-6">
                                                <canvas id="myChart"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-bordered">
                                    <div class="panel-body">
                                        <h3>Detalle de servicios realizados</h3>
                                        <table id="dataTable" class="table table-bordered table-bordered">
                                            <thead>
                                                <tr>
                                                    <th style="text-align: center">Id</th>
                                                    <th style="text-align: center">Cliente</th>
                                                    <th style="text-align: center">Servicios</th>
                                                    <th style="text-align: center">Plan</th>
                                                    <th style="text-align: right">Monto Cobrado</th>
                                                    <th style="text-align: center">Monto Tatal</th>
                                                    <th style="text-align: right">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $cont = 0;
                                                    $total = 0;
                                                    $i = 1;
                                                @endphp

                                                @foreach ($cashier->client as $item)
                                                    <tr>
                                                        <td style="text-align: center">{{ $i }}</td>                                            
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
                                                                            <img src="{{ $image }}" alt="{{ $item->people->first_name }} {{ $item->people->last_name }}" style="width: 60px; height: 60px; border-radius: 30px; margin-right: 10px">
                                                                    
                                                                            {{ $item->people->first_name }} {{ $item->people->last_name }}
                                                                </table>
                                                            @endif
            
                                                        </td>
                                                        <td style="text-align: center">{{ $item->service_id ? $item->service->name:'Productos' }}</td>
                                                        <td style="text-align: center">
                                                            @if ( $item->plan)
                                                                Plan: {{ $item->plan->name}}
                                                                <br> 
                                                                @if ($item->plan_id != 4)
                                                                    <b>{{date('d/m/Y', strtotime($item->start))}} Hasta {{date('d/m/Y', strtotime($item->finish))}}</b>
                                                                @else
                                                                    Dia: <small><b>{{ $item->day->name }}</b></small>
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
                                                            @else
                                                                {{-- {{$item[0]->item}} --}}
                                                                @php
                                                                    $article = \DB::table('items as i')
                                                                        ->join('wherehouse_details as w', 'w.id', 'i.wherehouseDetail_id')
                                                                        ->join('articles as a', 'a.id', 'w.article_id')
                                                                        ->where('i.client_id', $item->id)->where('i.deleted_at', null)
                                                                        ->select('a.name','a.presentation', DB::raw("SUM(i.item) as cant"), DB::raw("SUM(i.amount) as money"))
                                                                        ->groupBy('i.indice')->get();
                                                                @endphp
            
                                                                
            
                                                                @foreach ($article as $ar)
                                                                    <small><b>{{$ar->name}}-{{$ar->presentation}}</b></small>
                                                                    <br>
                                                                    <small><b>Cantidad: {{$ar->cant}}    Venta:{{$ar->money}}</b></small>
                                                                    <br><br><br>
                                                                @endforeach
                                                            @endif
                                                                                                 
                                                        </td>
                                                        <td style="text-align: right">
                                                            @php
                                                                $sub = \App\Models\Adition::where('client_id', $item->id)
                                                                                          ->where('cashier_id', $cashier->id)
                                                                                          ->where('deleted_at', null)
                                                                                          ->sum('cant')   ;
                                                            @endphp
                                                            {{ $sub }}
                                                        </td>

                                                        <td style="text-align: right">{{ $item->amount }}</td>
                                                        {{-- <td style="text-align: center">{{date('d/m/Y H:i:s', strtotime($item->created_at))}}<br><small>{{\Carbon\Carbon::parse($item->created_at)->diffForHumans()}}.</small></td> --}}
                                                                    
                                                        <td class="text-right">
                                                            {{-- @if ($data) --}}
                                                                @if (!$item->deleted_at)
                                                                    <button type="button" onclick="print_recipe({{ $item->id }})" title="Imprimir" class="btn btn-default btn-print"><i class="glyphicon glyphicon-print"></i> Imprimir</button>
                                                                @else
                                                                    <button type="button" onclick="print_recipe_delete({{ $item->id }})" title="Imprimir" class="btn btn-default btn-print"><i class="glyphicon glyphicon-print"></i> Informe de anulación</button>
                                                                @endif
                                                            {{-- @endif --}}
                                                        </td>

                                                    </tr>
                                                    @php
                                                        $i = $i+1;
                                                        $total+=$item->amount;
                                                    @endphp
                                                @endforeach
                                                <tr>
                                                    <td colspan="4" style="text-align: right"><b>TOTAL</b></td>
                                                    <td style="text-align: right"><b>{{ number_format($total, 2, ',', '.') }}</b></td>
                                                    <td colspan="3" style="text-align: right"></td>

                                                </tr>
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-bordered">
                                    <div class="panel-body">
                                        <h3>Detalle de movimientos de caja</h3>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>N&deg;</th>
                                                    <th>Detalle</th>
                                                    <th>Tipo</th>
                                                    <th class="text-right">Monto</th>
                                                    {{-- <th class="text-right">Acciones</th> --}}
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $cont = 1;
                                                    $total_movements = 0;
                                                @endphp
                                                @foreach ($cashier->movements->where('deleted_at', NULL) as $item)
                                                    <tr>
                                                        <td>{{ $cont }}</td>
                                                        <td>{{ $item->description }}</td>
                                                        <td><label class="label label-{{ $item->type == 'ingreso' ? 'success' : 'danger' }}">{{ $item->type }}</label></td>
                                                        <td class="text-right">{{ $item->amount }}</td>
                                                        {{-- <td class="text-right">
                                                            <button type="button" onclick="print_transfer({{ $item->id }})" title="Imprimir" class="btn btn-default"><i class="glyphicon glyphicon-print"></i> Imprimir</button>
                                                        </td> --}}
                                                    </tr>
                                                    @php
                                                        $cont++;
                                                        if($item->type == 'ingreso'){
                                                            $total_movements += $item->amount;
                                                        }else{
                                                            $total_movements -= $item->amount;
                                                        }
                                                    @endphp
                                                @endforeach
                                                <tr>
                                                    <td colspan="3"><h5>TOTAL</h5></td>
                                                    <td  colspan="1" class="text-right"><h4><small>Bs.</small> {{ number_format($total_movements, 2, ',', '.') }}</h4></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>                    
                    @else
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-bordered">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-6" style="margin-top: 50px">
                                                <table class="table table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Corte</th>
                                                            <th>Cantidad</th>
                                                            <th>Sub Total</th>
                                                        </tr>
                                                    </thead>
                                                    @php
                                                        $cash = ['200', '100', '50', '20', '10', '5', '2', '1', '0.5', '0.2', '0.1'];
                                                        $total = 0;
                                                    @endphp
                                                    <tbody>
                                                        @foreach ($cash as $item)
                                                        <tr>
                                                            <td><h4 style="margin: 0px"><img src=" {{ url('images/cash/'.$item.'.jpg') }} " alt="{{ $item }} Bs." width="70px"> {{ $item }} Bs. </h4></td>
                                                            <td>
                                                                @php
                                                                    $details = $cashier->vault_details->cash->where('cash_value', $item)->first();
                                                                @endphp
                                                                {{ $details ? $details->quantity : 0 }}
                                                            </td>
                                                            <td>
                                                                {{ $details ? number_format($details->quantity * $item, 2, ',', '.') : 0 }}
                                                                <input type="hidden" name="cash_value[]" value="{{ $item }}">
                                                                <input type="hidden" name="quantity[]" value="{{ $details ? $details->quantity : 0 }}">
                                                            </td>
                                                            @php
                                                            if($details){
                                                                $total += $details->quantity * $item;
                                                            }
                                                            @endphp
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="col-md-6">
                                                <br>
                                                <div class="alert alert-info">
                                                    <strong>Información:</strong>
                                                    <p>Si la cantidad de de cortes de billetes coincide con la cantidad entregada por parte del administrador(a) de vóbeda, acepta la apertura de caja, caso contrario puedes rechazar la apertura.</p>
                                                </div>
                                                <br>
                                                <h2 class="text-right"><small>Total en caja: Bs.</small> {{ number_format($total, 2, ',', '.') }} </h2>
                                                <br>
                                                <div class="text-right">
                                                    <button type="button" data-toggle="modal" data-target="#refuse_cashier-modal" class="btn btn-danger">Rechazar apertura <i class="voyager-x"></i></button>
                                                    <button type="button" data-toggle="modal" data-target="#open_cashier-modal" class="btn btn-success">Aceptar apertura <i class="voyager-key"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Aceptar apertura de caja --}}
                        <form action="{{ route('cashiers.change.status', ['cashier' => $cashier->id]) }}" method="post">
                            @csrf
                            <input type="hidden" name="status" value="abierta">
                            <div class="modal fade" tabindex="-1" id="open_cashier-modal" role="dialog">
                                <div class="modal-dialog modal-success">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title"><i class="voyager-key"></i> Aceptar apertura de caja</h4>
                                        </div>
                                        <div class="modal-body">
                                            <p class="text-muted">Esta a punto de aceptar que posee todos los cortes de billetes descritos en la lista, ¿Desea continuar?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-success">Si, aceptar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                        {{-- Rechazar apertura de caja --}}
                        <form action="{{ route('cashiers.change.status', ['cashier' => $cashier->id]) }}" method="post">
                            @csrf
                            <input type="hidden" name="status" value="cerrada">
                            <div class="modal modal-danger fade" tabindex="-1" id="refuse_cashier-modal" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title"><i class="voyager-x"></i> Rechazar apertura de caja</h4>
                                        </div>
                                        <div class="modal-body">
                                            <p class="text-muted">Esta a punto de rechazar la apertura de caja, ¿Desea continuar?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-danger">Si, rechazar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    @endif
                @else
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-bordered">
                                <div class="panel-body text-center">
                                    <h2>Tienes una caja esperando por confimación de cierre</h2>
                                    <button type="button" data-toggle="modal" data-target="#cashier-revert-modal" class="btn btn-success"><i class="voyager-key"></i> Reabrir caja</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('cashiers.close.revert', ['cashier' => $cashier->id]) }}" method="post">
                        @csrf
                        <div class="modal fade" tabindex="-1" id="cashier-revert-modal" role="dialog">
                            <div class="modal-dialog modal-success">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title"><i class="voyager-key"></i> Reabrir Caja</h4>
                                    </div>
                                    <div class="modal-body">
                                        <p class="text-muted">Si reabre la caja deberá realizar el arqueo nuevamente, ¿Desea continuar?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-success">Si, reabrir</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                @endif
            @else
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-bordered">
                            <div class="panel-body">
                                <h1 class="text-center">No tienes caja abierta</h1>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endif
    </div>
@stop

@section('javascript')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.4/Chart.bundle.min.js"></script>
    @if ((Auth::user()->role_id == 3 || Auth::user()->role_id == 4) && $cashier)
        @if ($cashier->status == 'abierta')
            <script>
                $(document).ready(function(){
                    const data = {
                        labels: [
                            'Dinero Asignado a Caja',
                            'Servicios Atendido'
                        ],
                        datasets: [{
                            label: 'My First Dataset',
                            data: ["{{ $cashier_in }}", "{{ $amount }}"],
                            backgroundColor: [
                            'rgb(54, 162, 257)',
                            'rgb(54, 205, 1)'
                            ],
                            hoverOffset: 4
                        }]
                    };
                    const config = {
                        type: 'pie',
                        data: data,
                    };
                    var myChart = new Chart(
                        document.getElementById('myChart'),
                        config
                    );

                    // Si retorna las opciones para generar recibo de traspaso a caja
                   
                });


            </script>
        @endif
    @endif

@stop
