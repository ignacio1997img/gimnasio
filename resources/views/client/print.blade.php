<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Factura:GYM</title>
    <link rel="shortcut icon" href="{{ asset('images/icon.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body{
            margin: 0px auto;
            font-family: Arial, sans-serif;
            font-weight: 100;
            max-width: 300px;
        }
        #watermark {
            position: absolute;
            opacity: 0.1;
            z-index:  -1000;
        }
        #watermark-stamp {
            position: absolute;
            /* opacity: 0.9; */
            z-index:  -1000;
        }
        #watermark img{
            position: relative;
            width: 300px;
            height: 300px;
            left: 205px;
        }
        #watermark-stamp img{
            position: relative;
            width: 4cm;
            height: 4cm;
            left: 50px;
            top: 70px;
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
    {{-- <div class="hide-print" style="text-align: right; padding: 10px 0px">
        <button class="btn-print" onclick="window.close()">Cancelar <i class="fa fa-close"></i></button>
        <button class="btn-print" onclick="window.print()"> Imprimir <i class="fa fa-print"></i></button>
    </div> --}}
    
    <div >
        <table width="100%">
            <tr>
                <td colspan="2" style="text-align: center">
                    <h3 style="margin-bottom: 0px; margin-top: 50px; font-size: 15px"><small>COMPROBANTE <br>GYM</small> </h3>
                </td>
            </tr>
        </table>
        
        <hr>
        {{-- <div id="watermark">
            <img src="{{ asset('images/icon.png') }}" height="100%" width="100%" /> 
        </div> --}}
        <table width="100%" cellpadding="5" style="font-size: 10px">
            <tr>
                <th style="text-align: right; width: 10%">
                    CODIGO:
                </th>
                <td>
                    {{$data->id}}
                </td>
            </tr>
            <tr>
                <th style="text-align: right; width: 10%">
                    FECHA:
                </th>
                <td>
                    {{Carbon\Carbon::parse($data->created_at)->format('d/m/Y H-m-s')}}
                </td>
            </tr>
            <tr>
                <th style="text-align: right; width: 10%">
                    BENEFICIARIO:
                </th>
                <td>
                    {{$data->people->last_name}} {{$data->people->first_name}}
                </td>
            </tr>
            <tr>
                <th style="text-align: right; width: 10%">
                    CI:
                </th>
                <td>
                    {{$data->people->ci}}
                </td>
            </tr>
        </table>
        {{-- <hr> --}}
        <table width="100%">
            <tr>
                <td colspan="2" style="text-align: center">
                    <h3 style="margin-bottom: 0px; margin-top: 0px; font-size: 12px"><small>DETALLE DEL SERVICIO O VENTA DE PRODUCTO</small> </h3>
                </td>
            </tr>
        </table>
        @if ($data->type == 'servicio')
            <table width="100%" cellpadding="2" cellspacing="0" border="0" style="font-size: 12px">
                <tr style="text-align: center">
                    <td class="border" style="text-align: right; width: 30%">
                        SERVICIO:
                    </td>
                    <td class="border" style="width: 70%">
                        {{$data->service->name}}
                    </td>  
                </tr>
                <tr style="text-align: center">
                    <td class="border" style="text-align: right; width: 30%">
                        PLAN:
                    </td>
                    <td class="border" style="width: 70%">
                        {{$data->plan->name}}
                    </td>  
                </tr>
                <tr style="text-align: center">
                    <td class="border" style="text-align: right; width: 30%">
                        HORARIO:
                    </td>
                    <td class="border" style="width: 70%">
                        {{$data->hour->name}}
                    </td>  
                </tr>
                <tr style="text-align: center">
                    <td class="border" style="text-align: right; width: 30%">
                        FECHA INICIO:
                    </td>
                    <td class="border" style="width: 70%">
                        {{Carbon\Carbon::parse($data->start)->format('d/m/Y')}}
                    </td>  
                </tr>
                <tr style="text-align: center">
                    <td class="border" style="text-align: right; width: 30%">
                        FECHA FIN:
                    </td>
                    <td class="border" style="width: 70%">
                        {{Carbon\Carbon::parse($data->finish)->format('d/m/Y')}}
                    </td>  
                </tr>
            </table>
            <br>
            <table width="100%" cellpadding="2" cellspacing="0" border="0" style="font-size: 12px">
                <tr>
                    <th colspan="1" class="border" style="text-align: center; width: 70%">
                        TOTAL A PAGAR
                    </th>
                    <th class="border" style="text-align: right; width: 30%">
                        Bs.{{number_format($data->amount)}}
                    </th>
                </tr>
                <tr>
                    <th colspan="1" class="border" style="text-align: center; width: 70%">
                        DEUDA
                    </th>
                    <th class="border" style="text-align: right; width: 30%">
                        Bs.{{number_format($data->subAmount)}}
                    </th>
                </tr>
            </table>
        @else
            @php
                $total =0;
            @endphp
            <table width="100%" cellpadding="2" cellspacing="0" border="0" style="font-size: 12px">
                <tr style="text-align: center">
                    <th class="border" style="width: 60%">
                        DETALLE
                    </th>   
                    <th class="border" style="width: 5%">
                        CANTIDAD
                    </th>

                    <th class="border" style="width: 5%">
                        PRECIO
                    </th>
                                 
                    <th class="border" style="width: 25%">
                        TOTAL
                    </th>
                </tr>
                @php
                    $total=0;
                @endphp
                @foreach ($data->item as $item)
                    <tr>
                        <td style="text-align: left">
                            {{$item->wherehouseDetail->article->name}}
                        </td> 
                        <td style="text-align: right">
                            {{$item->item}}
                        </td>                 
                        <td style="text-align: right">
                            {{$item->itemEarnings}}
                        </td>
                        <td style="text-align: right">
                            {{$item->amount}}
                        </td>
                        @php
                            $total+=$item->amount;
                        @endphp
                    </tr>
                @endforeach
                
                <tr>
                    <th colspan="3"  class="border" style="text-align: center; width: 75%">
                        TOTAL (BS)
                    </th>
                    <th class="border" style="text-align: right; width: 25%">
                        {{ number_format($total, 2, ',','.') }}
                    </th>
                </tr>
                <tr>
                    <th colspan="3"  class="border" style="text-align: center; width: 75%">
                        DEUDA (BS)
                    </th>
                    <th class="border" style="text-align: right; width: 25%">
                        {{ number_format($data->subAmount, 2, ',','.') }}
                    </th>
                </tr>
            </table>
        @endif
        {{-- <hr> --}}
        <table width="100%">
            <tr>
                <td colspan="2" style="text-align: center">
                    <h3 style="margin-bottom: 0px; margin-top: 5px; font-size: 12px"><small>ATENDIDO POR</small> </h3>
                </td>
            </tr>
        </table>
        <table width="100%" cellpadding="2" cellspacing="0" border="0" style="font-size: 12px">
            <tr>
                <td colspan="2" style="text-align: center;">
                    {{$data->user->name}}
                </td>
            </tr>
        </table>
        <br>
        <div class="visible-print text-center" style="text-align: center">
            {!!
            QrCode::size(100)->generate('
                CODIGO: '.$data->id.'
                CLIENTE: '.$data->people->first_name.' '.$data->people->last_name.'
                CI.: '.$data->people->ci.'
                FECHA: '.Carbon\Carbon::parse($data->created_at)->format('d/m/Y H-m-s').'
                
                TOTAL: Bs.'.$data->amount.'
                DEUDA: Bs.'.$data->subAmount
            );
            // QrCode::format('png')->merge('/public/images/icon.png')->generate('Make me into a QrCode!');

            !!}
        </div>
        <hr>
        <table width="100%" cellpadding="5" style="font-size: 10px">
            <tr>
                <th style="text-align: right; width: 10%">
                    FIRMA:
                </th>
                <td>
                    _____________________________________
                </td>
            </tr>
            <tr>
                <th style="text-align: right; width: 10%">
                    NOMBRE:
                </th>
                <td>
                    _____________________________________
                </td>
            </tr>
            <tr>
                <th style="text-align: right; width: 10%">
                    CI:
                </th>
                <td>
                    _____________________________________
                </td>
            </tr>
        </table>
        <br><br>
        <table width="100%" style="font-size: 8px">
            <tr style="text-align: center">
                <td>
                    <small><b>GYM V1</b></small>
                </td>
            </tr>
        </table>

    </div>
    <style>
        .border{
            border: solid 1px black;
        }
    </style>
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