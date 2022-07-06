<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Entrega de fondos</title>
    <link rel="shortcut icon" href="{{ asset('images/icon.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body{
            margin: 0px auto;
            font-family: Arial, sans-serif;
            font-weight: 100;
            max-width: 740px;
        }
        #watermark {
            position: absolute;
            opacity: 0.1;
            z-index:  -1000;
        }
        #watermark img{
            position: relative;
            width: 300px;
            height: 300px;
            left: 205px;
        }
        .show-print{
            display: none;
            padding-top: 15px
        }
        .btn-print{
            padding: 5px 10px
        }
        @media print{
            .hide-print, .btn-print{
                display: none
            }
            .show-print, .border-bottom{
                display: block
            }
            .border-bottom{
                border-bottom: 1px solid rgb(90, 90, 90);
                padding: 20px 0px;
            }
        }
    </style>
</head>
<body>
    <div class="hide-print" style="text-align: right; padding: 10px 0px">
        <button class="btn-print" onclick="window.close()">Cancelar <i class="fa fa-close"></i></button>
        <button class="btn-print" onclick="window.print()"> Imprimir <i class="fa fa-print"></i></button>
    </div>
    @for ($i = 0; $i < 2; $i++)
    <div style="height: 45vh" @if ($i == 1) class="show-print" @else class="border-bottom" @endif>
        <table width="100%">
            <tr>
                <td><img src="{{ asset('storage/'.str_replace('.', '-cropped.',$busine->busine->image)) }}" alt="GYM" width="80px"></td>
                <td style="text-align: right">
                    <h3 style="margin-bottom: 0px; margin-top: 5px">CAJAS - {{$busine->busine->name}}<br> <small>ENTREGA DE FONDOS</small> </h3>
                </td>
            </tr>
        </table>
        <hr style="margin: 0px">
        <div id="watermark">
            <img src="{{ asset('images/icon.png') }}" height="100%" width="100%" /> 
        </div>
        <table width="100%" cellpadding="10" style="font-size: 12px">
            <tr>
                <td width="70%">
                    <table width="100%" cellpadding="5">
                        <tr>
                            <td width="100px"><b>ID</b></td>
                            <td style="border: 1px solid #ddd">{{ str_pad($cashier->id, 6, "0", STR_PAD_LEFT) }}</td>
                        </tr>
                        <tr>
                            <td><b>FECHA</b></td>
                            <td style="border: 1px solid #ddd">
                                @php
                                    $dias = ['', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
                                    $meses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                                @endphp
                                {{ $dias[date('N', strtotime($cashier->created_at))].', '.date('d', strtotime($cashier->created_at)).' de '.$meses[intval(date('m', strtotime($cashier->created_at)))].' de '.date('Y', strtotime($cashier->created_at)).' a las '.date('H:i:s', strtotime($cashier->created_at)) }}
                            </td>
                        </tr>
                        <tr>
                            <td><b>CAJERO(A)</b></td>
                            <td style="border: 1px solid #ddd">{{ $cashier->user->name }}</td>
                        </tr>
                        <tr>
                            <td><b>CONCEPTO</b></td>
                            <td style="border: 1px solid #ddd">Apertura de caja</td>
                        </tr>
                        <tr>
                            <td><b>MONTO</b></td>
                            @php
                                $amount = 0;
                                foreach($cashier->movements as $movement){
                                    if($movement->description == 'Monto de apertura de caja.'){
                                        $amount += $movement->amount;
                                    }
                                }
                            @endphp
                            <td style="border: 1px solid #ddd">{{ number_format($amount, 2, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td><b>NOTA</b></td>
                            <td style="border: 1px solid #ddd">{{ $cashier->observations ?? 'Ninguna' }}</td>
                        </tr>
                    </table>
                    <br>
                    <b>CORTES DE BILLETES</b>
                    <div style="display: flex; border: 1px solid #ddd; padding: 10px 0px; margin-top: 10px">
                        @foreach ($cashier->vault_details->cash as $cash)
                            <div style="width: 33%; text-aign: center">
                                <div style="text-align: center">{{ $cash->cash_value >= 1 ? intval($cash->cash_value) : number_format($cash->cash_value, 2, '.', '') }}</div>
                                <div style="text-align: center">&darr;</div>
                                <div style="text-align: center"><b>{{ intval($cash->quantity) }}</b></div>
                            </div>
                        @endforeach
                    </div>
                </td>
                <td width="30%" style="padding: 0px 10px">
                    <div>
                        <p style="text-align: center; margin-top: 0px"><b><small>RECIBIDO POR</small></b></p>
                        <br>
                        <p style="text-align: center">.............................................. <br> <small>{{ strtoupper($cashier->user->name) }}</small> <br> <small>{{ $cashier->user->ci }}</small> <br> <b>{{ strtoupper($cashier->user->role->display_name) }}</b> </p>
                    </div>
                    <div>
                        <p style="text-align: center; margin-top: 0px"><b><small>ENTREGADO POR</small></b></p>
                        <br>
                        <p style="text-align: center">.............................................. <br> <small>{{ strtoupper(Auth::user()->name) }}</small> <br> <small>{{ Auth::user()->ci }}</small> <br> <b>{{ strtoupper(Auth::user()->role->display_name) }}</b> </p>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    @endfor

    <script>
        document.body.addEventListener('keypress', function(e) {
            switch (e.key) {
                case 'Enter':
                    window.print();
                    break;
                case 'Escape':
                    window.close();
                default:
                    break;
            }
        });
    </script>
</body>
</html>