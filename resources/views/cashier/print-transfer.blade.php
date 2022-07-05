<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Entrega de Fondos</title>
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
                <td><img src="{{ asset('images/icon.png') }}" alt="GADBENI" width="80px"></td>
                <td style="text-align: right">
                    <h3 style="margin-bottom: 0px; margin-top: 5px">CAJAS - GOBERNACIÓN<br> <small>ENTREGA DE FONDOS</small> </h3>
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
                            <td style="border: 1px solid #ddd">{{ str_pad($movement->id, 6, "0", STR_PAD_LEFT) }}</td>
                        </tr>
                        <tr>
                            <td><b>FECHA</b></td>
                            <td style="border: 1px solid #ddd">
                                @php
                                    $dias = ['', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
                                    $meses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                                @endphp
                                {{ $dias[date('N', strtotime($movement->created_at))].', '.date('d', strtotime($movement->created_at)).' de '.$meses[intval(date('m', strtotime($movement->created_at)))].' de '.date('Y', strtotime($movement->created_at)).' a las '.date('H:i:s', strtotime($movement->created_at)) }}
                            </td>
                        </tr>
                        <tr>
                            <td><b>CAJERO(A)</b></td>
                            <td style="border: 1px solid #ddd">{{ $movement->cashier->user->name }}</td>
                        </tr>
                        <tr>
                            <td><b>CONCEPTO</b></td>
                            <td style="border: 1px solid #ddd">{{ $movement->description }}</td>
                        </tr>
                        <tr>
                            <td><b>MONTO</b></td>
                            <td style="border: 1px solid #ddd">{{ number_format($movement->amount, 2, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td><b>NOTA</b></td>
                            <td style="border: 1px solid #ddd">{{ $movement->observations ?? 'Ninguna' }}</td>
                        </tr>
                    </table>
                </td>
                <td width="30%" style="padding: 0px 10px">
                    <br>
                    <div>
                        <p style="text-align: center; margin-top: 0px"><b><small>RECIBIDO POR</small></b></p>
                        <br>
                        <p style="text-align: center">.............................................. <br> <small>{{ strtoupper($movement->type == 'egreso' ? $movement->cashier_to->user->name : $movement->cashier->user->name) }}</small> <br> <small>{{ $movement->type == 'egreso' ? $movement->cashier_to->user->ci : $movement->cashier->user->ci }}</small> <br> <b>{{ strtoupper( $movement->type == 'egreso' ? $movement->cashier_to->user->role->display_name : $movement->cashier->user->role->display_name) }}</b> </p>
                    </div>
                    <div>
                        <p style="text-align: center; margin-top: 0px"><b><small>ENTREGADO POR</small></b></p>
                        <br>
                        <p style="text-align: center">.............................................. <br> <small>{{ strtoupper($movement->user->name) }}</small> <br> <small>{{ $movement->user->ci }}</small> <br> <b>{{ strtoupper($movement->user->role->display_name) }}</b> </p>
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