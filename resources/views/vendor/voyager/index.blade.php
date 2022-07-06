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
                                        

                                        $amount = $cashier->client->where('deleted_at', null)->sum('amount');
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
                                        <h3>Detalle de pagos realizados</h3>
                                        <table class="table table-bordered table-bordered">
                                            <thead>
                                                <tr>
                                                    <th style="text-align: center">Id</th>
                                                    <th style="text-align: center">Cliente</th>
                                                    <th style="text-align: center">Servicios</th>
                                                    <th style="text-align: center">Plan</th>
                                                    <th style="text-align: right">Monto</th>
                                                    <th style="text-align: center">Registrado</th>
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
                                                    @endphp
                                                @endforeach
                                                <tr>
                                                    <td colspan="4" style="text-align: right"><b>TOTAL</b></td>
                                                    <td style="text-align: right"><b>{{ number_format($total, 2, ',', '.') }}</b></td>
                                                    <td></td>
                                                </tr>
                                                {{-- @foreach ($cashier->payments as $payment)
                                                    @php
                                                        $data = DB::connection('mysqlgobe')->table('planillahaberes')->where('id', $payment->planilla_haber_id)->first();

                                                        $cont++;
                                                        if(!$payment->deleted_at){
                                                            $total += $payment->amount;
                                                        }
                                                        $months = [
                                                            '01' => 'Enero',
                                                            '02' => 'Febrero',
                                                            '03' => 'Marzo',
                                                            '04' => 'Abril',
                                                            '05' => 'Mayo',
                                                            '06' => 'Junio',
                                                            '07' => 'Julio',
                                                            '08' => 'Agosto',
                                                            '09' => 'Septiembre',
                                                            '10' => 'Octubre',
                                                            '11' => 'Noviembre',
                                                            '12' => 'Diciembre'
                                                        ];
                                                    @endphp
                                                    <tr>
                                                        
                                                        <td>{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                                                        <td style="text-align: right">{{ number_format($payment->amount, 2, ',', '.') }}</td>
                                                        
                                                    </tr>
                                                @endforeach
                                                <tr>
                                                    <td colspan="5" style="text-align: right"><b>TOTAL</b></td>
                                                    <td style="text-align: right"><b>{{ number_format($total, 2, ',', '.') }}</b></td>
                                                    <td></td>
                                                </tr> --}}
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
                                                    <th class="text-right">Acciones</th>
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
                                                        <td class="text-right">
                                                            <button type="button" onclick="print_transfer({{ $item->id }})" title="Imprimir" class="btn btn-default"><i class="glyphicon glyphicon-print"></i> Imprimir</button>
                                                        </td>
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
                                                    <td  colspan="2" class="text-right"><h4><small>Bs.</small> {{ number_format($total_movements, 2, ',', '.') }}</h4></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- <form id="form-pagar" action="{{ route('cashiers.amount.store') }}" method="post">
                            @csrf
                            @if ($cashier)
                            <input type="hidden" name="id" value="{{ $cashier->id }}">
                            <input type="hidden" name="redirect" value="voyager.dashboard">
                            @endif
                            <div class="modal fade" tabindex="-1" id="transfer-modal" role="dialog">
                                <div class="modal-dialog modal-success">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title"><i class="voyager-forward"></i> Realizar transferencia</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="cashier_id">Caja de destino</label>
                                                <select name="cashier_id" class="form-control select2" required>
                                                    <option value="" disabled selected>-- Seleccione la caja --</option>
                                                    @if ($cashier)
                                                    @foreach (\App\Models\Cashier::with('user')->where('closed_at', NULL)->where('deleted_at', NULL)->get() as $item)
                                                    <option value="{{ $item->id }}" @if($item->id == $cashier->id) disabled @endif >{{ $item->title }} - {{ $item->user->name }}</option>
                                                    @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="amount">Monto</label>
                                                <input type="number" step="0.1" min="0.1" name="amount" class="form-control" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="description">descripción</label>
                                                <textarea name="description" class="form-control" rows="3" required></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-success">Transferir</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form> --}}
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

                    {{-- <form action="{{ route('cashiers.close.revert', ['cashier' => $cashier->id]) }}" method="post">
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
                    </form> --}}
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
                    @if (session('id_transfer'))
                        let id_transfer = "{{ session('id_transfer') }}";
                        print_transfer(id_transfer);
                    @endif
                });

                function print_transfer(id){
                    window.open("{{ url('admin/cashiers/print/transfer') }}/"+id, "Entrega de fondos", `width=700, height=400`);
                }
                function print_recipe(id){
                    window.open("{{ url('admin/planillas/pagos/print') }}/"+id, "Recibo", `width=700, height=500`)
                }
                function print_recipe_delete(id){
                    window.open("{{ url('admin/planillas/pagos/delete/print') }}/"+id, "Recibo", `width=700, height=500`)
                }
            </script>
        @endif
    @endif

    {{-- @if(isset($google_analytics_client_id) && !empty($google_analytics_client_id))
        <script>
            (function (w, d, s, g, js, fs) {
                g = w.gapi || (w.gapi = {});
                g.analytics = {
                    q: [], ready: function (f) {
                        this.q.push(f);
                    }
                };
                js = d.createElement(s);
                fs = d.getElementsByTagName(s)[0];
                js.src = 'https://apis.google.com/js/platform.js';
                fs.parentNode.insertBefore(js, fs);
                js.onload = function () {
                    g.load('analytics');
                };
            }(window, document, 'script'));
        </script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/1.1.1/Chart.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>
        <script>
            // View Selector 2 JS
            !function(e){function t(r){if(i[r])return i[r].exports;var o=i[r]={exports:{},id:r,loaded:!1};return e[r].call(o.exports,o,o.exports,t),o.loaded=!0,o.exports}var i={};return t.m=e,t.c=i,t.p="",t(0)}([function(e,t,i){"use strict";function r(e){return e&&e.__esModule?e:{"default":e}}var o=i(1),s=r(o);gapi.analytics.ready(function(){function e(e,t,i){e.innerHTML=t.map(function(e){var t=e.id==i?"selected ":" ";return"<option "+t+'value="'+e.id+'">'+e.name+"</option>"}).join("")}function t(e){return e.ids||e.viewId?{prop:"viewId",value:e.viewId||e.ids&&e.ids.replace(/^ga:/,"")}:e.propertyId?{prop:"propertyId",value:e.propertyId}:e.accountId?{prop:"accountId",value:e.accountId}:void 0}gapi.analytics.createComponent("ViewSelector2",{execute:function(){return this.setup_(function(){this.updateAccounts_(),this.changed_&&(this.render_(),this.onChange_())}.bind(this)),this},set:function(e){if(!!e.ids+!!e.viewId+!!e.propertyId+!!e.accountId>1)throw new Error('You cannot specify more than one of the following options: "ids", "viewId", "accountId", "propertyId"');if(e.container&&this.container)throw new Error("You cannot change containers once a view selector has been rendered on the page.");var t=this.get();return(t.ids!=e.ids||t.viewId!=e.viewId||t.propertyId!=e.propertyId||t.accountId!=e.accountId)&&(t.ids=null,t.viewId=null,t.propertyId=null,t.accountId=null),gapi.analytics.Component.prototype.set.call(this,e)},setup_:function(e){function t(){s["default"].get().then(function(t){i.summaries=t,i.accounts=i.summaries.all(),e()},function(e){i.emit("error",e)})}var i=this;gapi.analytics.auth.isAuthorized()?t():gapi.analytics.auth.on("signIn",t)},updateAccounts_:function(){var e=this.get(),i=t(e),r=void 0,o=void 0,s=void 0;if(!this.summaries.all().length)return this.emit("error",new Error('This user does not have any Google Analytics accounts. You can sign up at "www.google.com/analytics".'));if(i)switch(i.prop){case"viewId":r=this.summaries.getProfile(i.value),o=this.summaries.getAccountByProfileId(i.value),s=this.summaries.getWebPropertyByProfileId(i.value);break;case"propertyId":s=this.summaries.getWebProperty(i.value),o=this.summaries.getAccountByWebPropertyId(i.value),r=s&&s.views&&s.views[0];break;case"accountId":o=this.summaries.getAccount(i.value),s=o&&o.properties&&o.properties[0],r=s&&s.views&&s.views[0]}else o=this.accounts[0],s=o&&o.properties&&o.properties[0],r=s&&s.views&&s.views[0];o||s||r?(o!=this.account||s!=this.property||r!=this.view)&&(this.changed_={account:o&&o!=this.account,property:s&&s!=this.property,view:r&&r!=this.view},this.account=o,this.properties=o.properties,this.property=s,this.views=s&&s.views,this.view=r,this.ids=r&&"ga:"+r.id):this.emit("error",new Error("This user does not have access to "+i.prop.slice(0,-2)+" : "+i.value))},render_:function(){var t=this.get();this.container="string"==typeof t.container?document.getElementById(t.container):t.container,this.container.innerHTML=t.template||this.template;var i=this.container.querySelectorAll("select"),r=this.accounts,o=this.properties||[{name:"(Empty)",id:""}],s=this.views||[{name:"(Empty)",id:""}];e(i[0],r,this.account.id),e(i[1],o,this.property&&this.property.id),e(i[2],s,this.view&&this.view.id),i[0].onchange=this.onUserSelect_.bind(this,i[0],"accountId"),i[1].onchange=this.onUserSelect_.bind(this,i[1],"propertyId"),i[2].onchange=this.onUserSelect_.bind(this,i[2],"viewId")},onChange_:function(){var e={account:this.account,property:this.property,view:this.view,ids:this.view&&"ga:"+this.view.id};this.changed_&&(this.changed_.account&&this.emit("accountChange",e),this.changed_.property&&this.emit("propertyChange",e),this.changed_.view&&(this.emit("viewChange",e),this.emit("idsChange",e),this.emit("change",e.ids))),this.changed_=null},onUserSelect_:function(e,t){var i={};i[t]=e.value,this.set(i),this.execute()},template:'<div class="ViewSelector2">  <div class="ViewSelector2-item">    <label>Account</label>    <select class="FormField"></select>  </div>  <div class="ViewSelector2-item">    <label>Property</label>    <select class="FormField"></select>  </div>  <div class="ViewSelector2-item">    <label>View</label>    <select class="FormField"></select>  </div></div>'})})},function(e,t,i){function r(){var e=gapi.client.request({path:n}).then(function(e){return e});return new e.constructor(function(t,i){var r=[];e.then(function o(e){var c=e.result;c.items?r=r.concat(c.items):i(new Error("You do not have any Google Analytics accounts. Go to http://google.com/analytics to sign up.")),c.startIndex+c.itemsPerPage<=c.totalResults?gapi.client.request({path:n,params:{"start-index":c.startIndex+c.itemsPerPage}}).then(o):t(new s(r))}).then(null,i)})}var o,s=i(2),n="/analytics/v3/management/accountSummaries";e.exports={get:function(e){return e&&(o=null),o||(o=r())}}},function(e,t){function i(e){this.accounts_=e,this.webProperties_=[],this.profiles_=[],this.accountsById_={},this.webPropertiesById_=this.propertiesById_={},this.profilesById_=this.viewsById_={};for(var t,i=0;t=this.accounts_[i];i++)if(this.accountsById_[t.id]={self:t},t.webProperties){r(t,"webProperties","properties");for(var o,s=0;o=t.webProperties[s];s++)if(this.webProperties_.push(o),this.webPropertiesById_[o.id]={self:o,parent:t},o.profiles){r(o,"profiles","views");for(var n,c=0;n=o.profiles[c];c++)this.profiles_.push(n),this.profilesById_[n.id]={self:n,parent:o,grandParent:t}}}}function r(e,t,i){Object.defineProperty?Object.defineProperty(e,i,{get:function(){return e[t]}}):e[i]=e[t]}i.prototype.all=function(){return this.accounts_},r(i.prototype,"all","allAccounts"),i.prototype.allWebProperties=function(){return this.webProperties_},r(i.prototype,"allWebProperties","allProperties"),i.prototype.allProfiles=function(){return this.profiles_},r(i.prototype,"allProfiles","allViews"),i.prototype.get=function(e){if(!!e.accountId+!!e.webPropertyId+!!e.propertyId+!!e.profileId+!!e.viewId>1)throw new Error('get() only accepts an object with a single property: either "accountId", "webPropertyId", "propertyId", "profileId" or "viewId"');return this.getProfile(e.profileId||e.viewId)||this.getWebProperty(e.webPropertyId||e.propertyId)||this.getAccount(e.accountId)},i.prototype.getAccount=function(e){return this.accountsById_[e]&&this.accountsById_[e].self},i.prototype.getWebProperty=function(e){return this.webPropertiesById_[e]&&this.webPropertiesById_[e].self},r(i.prototype,"getWebProperty","getProperty"),i.prototype.getProfile=function(e){return this.profilesById_[e]&&this.profilesById_[e].self},r(i.prototype,"getProfile","getView"),i.prototype.getAccountByProfileId=function(e){return this.profilesById_[e]&&this.profilesById_[e].grandParent},r(i.prototype,"getAccountByProfileId","getAccountByViewId"),i.prototype.getWebPropertyByProfileId=function(e){return this.profilesById_[e]&&this.profilesById_[e].parent},r(i.prototype,"getWebPropertyByProfileId","getPropertyByViewId"),i.prototype.getAccountByWebPropertyId=function(e){return this.webPropertiesById_[e]&&this.webPropertiesById_[e].parent},r(i.prototype,"getAccountByWebPropertyId","getAccountByPropertyId"),e.exports=i}]);
            // DateRange Selector JS
            !function(t){function e(n){if(a[n])return a[n].exports;var i=a[n]={exports:{},id:n,loaded:!1};return t[n].call(i.exports,i,i.exports,e),i.loaded=!0,i.exports}var a={};return e.m=t,e.c=a,e.p="",e(0)}([function(t,e){"use strict";gapi.analytics.ready(function(){function t(t){if(n.test(t))return t;var i=a.exec(t);if(i)return e(+i[1]);if("today"==t)return e(0);if("yesterday"==t)return e(1);throw new Error("Cannot convert date "+t)}function e(t){var e=new Date;e.setDate(e.getDate()-t);var a=String(e.getMonth()+1);a=1==a.length?"0"+a:a;var n=String(e.getDate());return n=1==n.length?"0"+n:n,e.getFullYear()+"-"+a+"-"+n}var a=/(\d+)daysAgo/,n=/\d{4}\-\d{2}\-\d{2}/;gapi.analytics.createComponent("DateRangeSelector",{execute:function(){var e=this.get();e["start-date"]=e["start-date"]||"7daysAgo",e["end-date"]=e["end-date"]||"yesterday",this.container="string"==typeof e.container?document.getElementById(e.container):e.container,e.template&&(this.template=e.template),this.container.innerHTML=this.template;var a=this.container.querySelectorAll("input");return this.startDateInput=a[0],this.startDateInput.value=t(e["start-date"]),this.endDateInput=a[1],this.endDateInput.value=t(e["end-date"]),this.setValues(),this.setMinMax(),this.container.onchange=this.onChange.bind(this),this},onChange:function(){this.setValues(),this.setMinMax(),this.emit("change",{"start-date":this["start-date"],"end-date":this["end-date"]})},setValues:function(){this["start-date"]=this.startDateInput.value,this["end-date"]=this.endDateInput.value},setMinMax:function(){this.startDateInput.max=this.endDateInput.value,this.endDateInput.min=this.startDateInput.value},template:'<div class="DateRangeSelector">  <div class="DateRangeSelector-item">    <label>Start Date</label>     <input type="date">  </div>  <div class="DateRangeSelector-item">    <label>End Date</label>     <input type="date">  </div></div>'})})}]);
            // Active Users JS
            !function(t){function i(s){if(e[s])return e[s].exports;var n=e[s]={exports:{},id:s,loaded:!1};return t[s].call(n.exports,n,n.exports,i),n.loaded=!0,n.exports}var e={};return i.m=t,i.c=e,i.p="",i(0)}([function(t,i){"use strict";gapi.analytics.ready(function(){gapi.analytics.createComponent("ActiveUsers",{initialize:function(){this.activeUsers=0,gapi.analytics.auth.once("signOut",this.handleSignOut_.bind(this))},execute:function(){this.polling_&&this.stop(),this.render_(),gapi.analytics.auth.isAuthorized()?this.pollActiveUsers_():gapi.analytics.auth.once("signIn",this.pollActiveUsers_.bind(this))},stop:function(){clearTimeout(this.timeout_),this.polling_=!1,this.emit("stop",{activeUsers:this.activeUsers})},render_:function(){var t=this.get();this.container="string"==typeof t.container?document.getElementById(t.container):t.container,this.container.innerHTML=t.template||this.template,this.container.querySelector("b").innerHTML=this.activeUsers},pollActiveUsers_:function(){var t=this.get(),i=1e3*(t.pollingInterval||5);if(isNaN(i)||5e3>i)throw new Error("Frequency must be 5 seconds or more.");this.polling_=!0,gapi.client.analytics.data.realtime.get({ids:t.ids,metrics:"rt:activeUsers"}).then(function(t){var e=t.result,s=e.totalResults?+e.rows[0][0]:0,n=this.activeUsers;this.emit("success",{activeUsers:this.activeUsers}),s!=n&&(this.activeUsers=s,this.onChange_(s-n)),1==this.polling_&&(this.timeout_=setTimeout(this.pollActiveUsers_.bind(this),i))}.bind(this))},onChange_:function(t){var i=this.container.querySelector("b");i&&(i.innerHTML=this.activeUsers),this.emit("change",{activeUsers:this.activeUsers,delta:t}),t>0?this.emit("increase",{activeUsers:this.activeUsers,delta:t}):this.emit("decrease",{activeUsers:this.activeUsers,delta:t})},handleSignOut_:function(){this.stop(),gapi.analytics.auth.once("signIn",this.handleSignIn_.bind(this))},handleSignIn_:function(){this.pollActiveUsers_(),gapi.analytics.auth.once("signOut",this.handleSignOut_.bind(this))},template:'<div class="ActiveUsers">Active Users: <b class="ActiveUsers-value"></b></div>'})})}]);
        </script>

        <script>
            // == NOTE ==
            // This code uses ES6 promises. If you want to use this code in a browser
            // that doesn't supporting promises natively, you'll have to include a polyfill.

            gapi.analytics.ready(function () {

                /**
                 * Authorize the user immediately if the user has already granted access.
                 * If no access has been created, render an authorize button inside the
                 * element with the ID "embed-api-auth-container".
                 */
                gapi.analytics.auth.authorize({
                    container: 'embed-api-auth-container',
                    clientid: '{{ $google_analytics_client_id }}'
                });


                /**
                 * Create a new ActiveUsers instance to be rendered inside of an
                 * element with the id "active-users-container" and poll for changes every
                 * five seconds.
                 */
                var activeUsers = new gapi.analytics.ext.ActiveUsers({
                    container: 'active-users-container',
                    pollingInterval: 5
                });


                /**
                 * Add CSS animation to visually show the when users come and go.
                 */
                activeUsers.once('success', function () {
                    var element = this.container.firstChild;
                    var timeout;

                    document.getElementById('embed-api-auth-container').style.display = 'none';
                    document.getElementById('analytics-dashboard').style.display = 'block';

                    this.on('change', function (data) {
                        var element = this.container.firstChild;
                        var animationClass = data.delta > 0 ? 'is-increasing' : 'is-decreasing';
                        element.className += (' ' + animationClass);

                        clearTimeout(timeout);
                        timeout = setTimeout(function () {
                            element.className =
                                    element.className.replace(/ is-(increasing|decreasing)/g, '');
                        }, 3000);
                    });
                });


                /**
                 * Create a new ViewSelector2 instance to be rendered inside of an
                 * element with the id "view-selector-container".
                 */
                var viewSelector = new gapi.analytics.ext.ViewSelector2({
                    container: 'view-selector-container',
                    propertyId: '{{ Voyager::setting("site.google_analytics_tracking_id")  }}'
                })
                        .execute();


                /**
                 * Update the activeUsers component, the Chartjs charts, and the dashboard
                 * title whenever the user changes the view.
                 */
                viewSelector.on('viewChange', function (data) {
                    var title = document.getElementById('view-name');
                    if (title) {
                        title.innerHTML = data.property.name + ' (' + data.view.name + ')';
                    }

                    // Start tracking active users for this view.
                    activeUsers.set(data).execute();

                    // Render all the of charts for this view.
                    renderWeekOverWeekChart(data.ids);
                    renderYearOverYearChart(data.ids);
                    renderTopBrowsersChart(data.ids);
                    renderTopCountriesChart(data.ids);
                });


                /**
                 * Draw the a chart.js line chart with data from the specified view that
                 * overlays session data for the current week over session data for the
                 * previous week.
                 */
                function renderWeekOverWeekChart(ids) {

                    // Adjust `now` to experiment with different days, for testing only...
                    var now = moment(); // .subtract(3, 'day');

                    var thisWeek = query({
                        'ids': ids,
                        'dimensions': 'ga:date,ga:nthDay',
                        'metrics': 'ga:users',
                        'start-date': moment(now).subtract(1, 'day').day(0).format('YYYY-MM-DD'),
                        'end-date': moment(now).format('YYYY-MM-DD')
                    });

                    var lastWeek = query({
                        'ids': ids,
                        'dimensions': 'ga:date,ga:nthDay',
                        'metrics': 'ga:users',
                        'start-date': moment(now).subtract(1, 'day').day(0).subtract(1, 'week')
                                .format('YYYY-MM-DD'),
                        'end-date': moment(now).subtract(1, 'day').day(6).subtract(1, 'week')
                                .format('YYYY-MM-DD')
                    });

                    Promise.all([thisWeek, lastWeek]).then(function (results) {

                        var data1 = results[0].rows.map(function (row) {
                            return +row[2];
                        });
                        var data2 = results[1].rows.map(function (row) {
                            return +row[2];
                        });
                        var labels = results[1].rows.map(function (row) {
                            return +row[0];
                        });

                        labels = labels.map(function (label) {
                            return moment(label, 'YYYYMMDD').format('ddd');
                        });

                        var data = {
                            labels: labels,
                            datasets: [
                                {
                                    label: '{{ __('voyager::date.last_week') }}',
                                    fillColor: 'rgba(220,220,220,0.5)',
                                    strokeColor: 'rgba(220,220,220,1)',
                                    pointColor: 'rgba(220,220,220,1)',
                                    pointStrokeColor: '#fff',
                                    data: data2
                                },
                                {
                                    label: '{{ __('voyager::date.this_week') }}',
                                    fillColor: 'rgba(151,187,205,0.5)',
                                    strokeColor: 'rgba(151,187,205,1)',
                                    pointColor: 'rgba(151,187,205,1)',
                                    pointStrokeColor: '#fff',
                                    data: data1
                                }
                            ]
                        };

                        new Chart(makeCanvas('chart-1-container')).Line(data);
                        generateLegend('legend-1-container', data.datasets);
                    });
                }


                /**
                 * Draw the a chart.js bar chart with data from the specified view that
                 * overlays session data for the current year over session data for the
                 * previous year, grouped by month.
                 */
                function renderYearOverYearChart(ids) {

                    // Adjust `now` to experiment with different days, for testing only...
                    var now = moment(); // .subtract(3, 'day');

                    var thisYear = query({
                        'ids': ids,
                        'dimensions': 'ga:month,ga:nthMonth',
                        'metrics': 'ga:users',
                        'start-date': moment(now).date(1).month(0).format('YYYY-MM-DD'),
                        'end-date': moment(now).format('YYYY-MM-DD')
                    });

                    var lastYear = query({
                        'ids': ids,
                        'dimensions': 'ga:month,ga:nthMonth',
                        'metrics': 'ga:users',
                        'start-date': moment(now).subtract(1, 'year').date(1).month(0)
                                .format('YYYY-MM-DD'),
                        'end-date': moment(now).date(1).month(0).subtract(1, 'day')
                                .format('YYYY-MM-DD')
                    });

                    Promise.all([thisYear, lastYear]).then(function (results) {
                        var data1 = results[0].rows.map(function (row) {
                            return +row[2];
                        });
                        var data2 = results[1].rows.map(function (row) {
                            return +row[2];
                        });
                        var labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                            'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

                        // Ensure the data arrays are at least as long as the labels array.
                        // Chart.js bar charts don't (yet) accept sparse datasets.
                        for (var i = 0, len = labels.length; i < len; i++) {
                            if (data1[i] === undefined) data1[i] = null;
                            if (data2[i] === undefined) data2[i] = null;
                        }

                        var data = {
                            labels: labels,
                            datasets: [
                                {
                                    label: '{{ __('voyager::date.last_year') }}',
                                    fillColor: 'rgba(220,220,220,0.5)',
                                    strokeColor: 'rgba(220,220,220,1)',
                                    data: data2
                                },
                                {
                                    label: '{{ __('voyager::date.this_year') }}',
                                    fillColor: 'rgba(151,187,205,0.5)',
                                    strokeColor: 'rgba(151,187,205,1)',
                                    data: data1
                                }
                            ]
                        };

                        new Chart(makeCanvas('chart-2-container')).Bar(data);
                        generateLegend('legend-2-container', data.datasets);
                    })
                            .catch(function (err) {
                                console.error(err.stack);
                            });
                }


                /**
                 * Draw the a chart.js doughnut chart with data from the specified view that
                 * show the top 5 browsers over the past seven days.
                 */
                function renderTopBrowsersChart(ids) {

                    query({
                        'ids': ids,
                        'dimensions': 'ga:browser',
                        'metrics': 'ga:pageviews',
                        'sort': '-ga:pageviews',
                        'max-results': 5
                    })
                            .then(function (response) {

                                var data = [];
                                var colors = ['#4D5360', '#949FB1', '#D4CCC5', '#E2EAE9', '#F7464A'];

                                response.rows.forEach(function (row, i) {
                                    data.push({value: +row[1], color: colors[i], label: row[0]});
                                });

                                new Chart(makeCanvas('chart-3-container')).Doughnut(data);
                                generateLegend('legend-3-container', data);
                            });
                }


                /**
                 * Draw the a chart.js doughnut chart with data from the specified view that
                 * compares sessions from mobile, desktop, and tablet over the past seven
                 * days.
                 */
                function renderTopCountriesChart(ids) {
                    query({
                        'ids': ids,
                        'dimensions': 'ga:country',
                        'metrics': 'ga:sessions',
                        'sort': '-ga:sessions',
                        'max-results': 5
                    })
                            .then(function (response) {

                                var data = [];
                                var colors = ['#4D5360', '#949FB1', '#D4CCC5', '#E2EAE9', '#F7464A'];

                                response.rows.forEach(function (row, i) {
                                    data.push({
                                        label: row[0],
                                        value: +row[1],
                                        color: colors[i]
                                    });
                                });

                                new Chart(makeCanvas('chart-4-container')).Doughnut(data);
                                generateLegend('legend-4-container', data);
                            });
                }


                /**
                 * Extend the Embed APIs `gapi.analytics.report.Data` component to
                 * return a promise the is fulfilled with the value returned by the API.
                 * @param {Object} params The request parameters.
                 * @return {Promise} A promise.
                 */
                function query(params) {
                    return new Promise(function (resolve, reject) {
                        var data = new gapi.analytics.report.Data({query: params});
                        data.once('success', function (response) {
                            resolve(response);
                        })
                                .once('error', function (response) {
                                    reject(response);
                                })
                                .execute();
                    });
                }


                /**
                 * Create a new canvas inside the specified element. Set it to be the width
                 * and height of its container.
                 * @param {string} id The id attribute of the element to host the canvas.
                 * @return {RenderingContext} The 2D canvas context.
                 */
                function makeCanvas(id) {
                    var container = document.getElementById(id);
                    var canvas = document.createElement('canvas');
                    var ctx = canvas.getContext('2d');

                    container.innerHTML = '';
                    canvas.width = container.offsetWidth;
                    canvas.height = container.offsetHeight;
                    container.appendChild(canvas);

                    return ctx;
                }


                /**
                 * Create a visual legend inside the specified element based off of a
                 * Chart.js dataset.
                 * @param {string} id The id attribute of the element to host the legend.
                 * @param {Array.<Object>} items A list of labels and colors for the legend.
                 */
                function generateLegend(id, items) {
                    var legend = document.getElementById(id);
                    legend.innerHTML = items.map(function (item) {
                        var color = item.color || item.fillColor;
                        var label = item.label;
                        return '<li><i style="background:' + color + '"></i>' + label + '</li>';
                    }).join('');
                }


                // Set some global Chart.js defaults.
                Chart.defaults.global.animationSteps = 60;
                Chart.defaults.global.animationEasing = 'easeInOutQuart';
                Chart.defaults.global.responsive = true;
                Chart.defaults.global.maintainAspectRatio = false;

                // resize to redraw charts
                window.dispatchEvent(new Event('resize'));

            });

        </script>

    @endif --}}

@stop
