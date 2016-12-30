<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title></title>

    <style type='text/css'>

    </style>
    
</head>
<body>



    <table cellpadding='0' cellspacing='0' width='100%' style="font-family: sans-serif; font-size: 9px; border-collapse: collapse;">
        <thead style='height: 0;'>
            <tr style='margin-bottom: 51px;'>
                <th width='3.87%'></th>
                <th width='4.66%'></th>
                <th width='13.90%'></th>
                <th width='8.02%'></th>
                <th width='6.72%'></th>
                <th width='8.86%'></th>
                <th width='7.70%'></th>
                <th width='7.42%'></th>
                <th width='7.84%'></th>
                <th width='7.88%'></th>
                <th width='8.82%'></th>
                <th width='7.14%'></th>
                <th width='7.18%'></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td height="18" style="text-align:center; border:.3mm solid black;">OBRA:</td>
                <td style="text-align:center; border:.3mm solid black;" colspan='7' class='celeste' bgcolor='#8BC5F8'>{{ $tituloObra }}</td>
                <td style="text-align:center; border:.3mm solid black;" colspan='2'>FUENTE DE FINANCIAMIENTO</td>
                <td style="text-align:center; border:.3mm solid black;" colspan='2' class='celeste' bgcolor='#8BC5F8'></td>
                <td rowspan='46' class='gris' bgcolor='#CFCACC'></td>
            </tr>
            <tr><td height="5" colspan='12' class='gris' bgcolor='#CFCACC'></td></tr>
            <tr>
                <td height="18" style="text-align:center; border:.3mm solid black;" colspan='2'>CERTIFICADO No:</td>
                <td style="text-align:center; border:.3mm solid black;" class='celeste' bgcolor='#8BC5F8'>{{ $_GET['idc'] }}</td>
                <td style="text-align:center; border:.3mm solid black;" colspan='2'>PERÍODO CERT.</td>
                <td style="text-align:center; border:.3mm solid black;" class='celeste' bgcolor='#8BC5F8'>{{ $periodo }}</td>
                <td style="text-align:center; border:.3mm solid black;" colspan='2'>FECHA MEDICION:</td>
                <td style="text-align:center; border:.3mm solid black;" class='celeste' bgcolor='#8BC5F8'>{{ $fechaMedicion }}</td>
                <td style="text-align:center; border:.3mm solid black;" colspan='2'>% AVANCE REAL OBRA</td>
                <td style="text-align:center; border:.3mm solid black;" class='celeste' bgcolor='#8BC5F8'>{{ porcent($certificacion["PorcentajeAvanceReal"],true) }}</td>
            </tr>
            <tr><td height="7" colspan='12' class='gris' bgcolor='#CFCACC'></td></tr>
            <tr>
                <td style="text-align:center; border-left:.3mm solid black; border-top:.3mm solid black;" colspan='3'>MUNICIPALIDAD/COMISION DE FOMENTO DE</td>
                <td style="text-align:center; border:.3mm solid black;" colspan='8' class='celeste' bgcolor='#8BC5F8'>{{ $municip_comisionFomento }} </td>
                <td style="border-right:.3mm solid black; border-top:.3mm solid black;"></td>
                
            </tr>
            <tr>
                <td height="3" style="border-left:.3mm solid black; border-right:.3mm solid black;" colspan='12'></td>
            </tr>
            <tr>
                <td style="text-align:right; border-left:.3mm solid black;" colspan='4'>MONTO DE OBRA FINANCIADO POR PROVINCIA:</td>
                <td style="text-align:center; border:.3mm solid black;" colspan='2' class='celeste' bgcolor='#8BC5F8'>{{ moneda($contrato->MontoProvincia, '$') }}</td>
                <td colspan='3'></td>
                <td style="text-align:center;">DECRETO No</td>
                <td style="text-align:center; border:.3mm solid black;" colspan='2' class='celeste' bgcolor='#8BC5F8'>{{ $contrato->Decreto ? $contrato->Decreto : '0000/0000' }}</td>
                
            </tr>
            <tr>
                <td height="3" style="border-left:.3mm solid black; border-right:.3mm solid black;" colspan='12'></td>
            </tr>
            <tr>
                <td style="text-align:right; border-left:.3mm solid black;" colspan='3'>CONTRATISTA</td>
                <td style="text-align:center; border:.3mm solid black;" colspan='5' class='celeste' bgcolor='#8BC5F8'>{{ $proveedor->RazonSocial }}</td>
                <td style="text-align:right;" colspan='2'>MONTO DE CONTRATO:</td>
                <td style="text-align:center; border:.3mm solid black;" colspan='2' class='celeste' bgcolor='#8BC5F8'>{{ moneda($contrato->Monto, '$') }}</td>
                
            </tr>
            <tr>
                <td height="3" style="border-left:.3mm solid black; border-right:.3mm solid black;" colspan='12'></td>
            </tr>
            <tr>
                <td style="text-align:right; border-left:.3mm solid black; border-bottom:.3mm solid black;" colspan='3'>FECHA INICIO DE OBRA:</td>
                <td style="text-align:center; border:.3mm solid black;" colspan='3' class='celeste' bgcolor='#8BC5F8'>{{ $fechaInicio }}</td>
                <td style="text-align:right; border-bottom:.3mm solid black;" colspan='4'>PLAZO DE EJECUCIÓN:</td>
                <td style="text-align:center; border:.3mm solid black;" class='celeste' bgcolor='#8BC5F8'>{{ $contrato->PlazoEjecucion }}</td>
                <td style="border-right:.3mm solid black; border-bottom:.3mm solid black;" >DIAS</td>
            </tr>
            <tr><td height="5" colspan='12' class='gris' bgcolor='#CFCACC'></td></tr>
            <tr>
                <td height="18" style="text-align:center; border:.3mm solid black;" colspan='3'>ANTICIPO FINANCIERO</td>
                <td style="text-align:center; border:.3mm solid black;" class='celeste' bgcolor='#8BC5F8'>{{ moneda($certificacion["AnticipoFinanciero"], '$') }}</td>
                <td style="border:.3mm solid black;" ></td>
                <td style="text-align:center; border:.3mm solid black;" class='celeste' bgcolor='#8BC5F8'>{{ porcent($anticipoFinancieroPorcentaje, true) }}</td>
                <td class='gris' bgcolor='#CFCACC'></td>
                <td style="text-align:center; border:.3mm solid black;" colspan='4'>FECHA DE PAGO DEL ANTICIPO FINANCIERO</td>
                <td style="text-align:center; border:.3mm solid black;" class='celeste' bgcolor='#8BC5F8'>{{ $fechaPagoAnticipo }}</td>
            </tr>
            <tr><td height="5" colspan='12' class='gris' bgcolor='#CFCACC'></td></tr>
            <tr>
                <td height="18" style="text-align:center; border:.3mm solid black;" colspan='3'>IMPORTE CERTIFICADO ANTERIORMENTE</td>
                <td style="text-align:center; border:.3mm solid black;" class='celeste' bgcolor='#8BC5F8'>{{ moneda($certificacionAnterior["ImporteCertifAnterior"], '$') }}</td>
                <td style="border:.3mm solid black;" ></td>
                <td style="text-align:center; border:.3mm solid black;" class='celeste' bgcolor='#8BC5F8'>{{ porcent($porcentajeCertifAnterior) }}</td>
                <td class='gris' bgcolor='#CFCACC'></td>
                <td style="text-align:center; border:.3mm solid black;" colspan='4'>FECHA DE PAGO DEL CERTIFICADO ANTERIOR</td>
                <td style="text-align:center; border:.3mm solid black;" class='celeste' bgcolor='#8BC5F8'>{{ $fechaUltimoPago }}</td>
            </tr>
            <tr><td height="12" colspan='12' class='gris' bgcolor='#CFCACC'></td></tr>
            <tr class='azul'>
                <td height="50" bgcolor='#037BC6' style='text-align:center; color:#FFF; border:.15mm solid black;'>ORDEN</td>
                <td bgcolor='#037BC6' style='text-align:center; color:#FFF; border:.15mm solid black;' colspan='2'>ITEM</td>
                <td bgcolor='#037BC6' style='text-align:center; color:#FFF; border:.15mm solid black;'>MONTO TOTAL</td>
                <td bgcolor='#037BC6' style='text-align:center; color:#FFF; border:.15mm solid black;'>INCIDENCIA</td>
                <td bgcolor='#037BC6' style='text-align:center; color:#FFF; border:.15mm solid black;'>INCIDENCIA %</td>
                <td bgcolor='#037BC6' style='text-align:center; color:#FFF; border:.15mm solid black;'>CERTIFICADO ACUMULARO ANTERIOR %</td>
                <td bgcolor='#037BC6' style='text-align:center; color:#FFF; border:.15mm solid black;'>CERTIFICADO ACUMULARO ANTERIOR $</td>
                <td bgcolor='#037BC6' style='text-align:center; color:#FFF; border:.15mm solid black;'>CERTIFICADO ACTUAL %</td>
                <td bgcolor='#037BC6' style='text-align:center; color:#FFF; border:.15mm solid black;'>CERTIFICADO ACTUAL $</td>
                <td bgcolor='#037BC6' style='text-align:center; color:#FFF; border:.15mm solid black;'>CERTIFICADO ACUMULARO TOTAL EN %</td>
                <td bgcolor='#037BC6' style='text-align:center; color:#FFF; border:.15mm solid black;'>CERTIFICADO ACUMULARO TOTAL EN $</td>
            </tr>
            <?php for ($i = 0; $i < 15; $i++) { 
                ?>
                <tr>
                    <td style='text-align:center; border:.15mm solid black;' >{{ isset($items[$i]) ? $items[$i]['Orden'] : '-'; }}</td>
                    <td style='text-align:center; border:.15mm solid black;' colspan='2'>{{ isset($items[$i]) ? $items[$i]['Item'] : '-'; }}</td>
                    <td style='text-align:center; border:.15mm solid black;'>{{ isset($items[$i]) ? moneda($items[$i]['PrecioTotal'],'$') : '-'; }}</td>
                    <td style='text-align:center; border:.15mm solid black;'>{{ isset($items[$i]) ? porcent($items[$i]['Incidencia'],false) : '-'; }}</td>
                    <td style='text-align:center; border:.15mm solid black;'>{{ isset($items[$i]) ? porcent($items[$i]['IncidenciaPorcentaje'],true) : '-'; }}</td>
                    <td style='text-align:center; border:.15mm solid black;'>{{ isset($items[$i]) ? porcent($items[$i]['PorcentajeAnterior'],true) : '-'; }}</td>
                    <td style='text-align:center; border:.15mm solid black;'>{{ isset($items[$i]) ? moneda($items[$i]['ImporteAnterior'],'$') : '-'; }}</td>
                    <td style='text-align:center; border:.15mm solid black;'>{{ isset($items[$i]) ? porcent($items[$i]['PorcentajeActual'],true) : '-'; }}</td>
                    <td style='text-align:center; border:.15mm solid black;'>{{ isset($items[$i]) ? moneda($items[$i]['ImporteActual'],'$') : '-'; }}</td>
                    <td style='text-align:center; border:.15mm solid black;'>{{ isset($items[$i]) ? porcent($items[$i]['PorcentajeAcum'],true) : '-'; }}</td>
                    <td style='text-align:center; border:.15mm solid black;'>{{ isset($items[$i]) ? moneda($items[$i]['ImporteAcum'],'$') : '-'; }}</td>

                </tr>
            <?php } ?>
            <tr class='azul'>
                <td bgcolor='#037BC6' style='text-align:center; color:#FFF; border:.15mm solid black;' colspan='3'>TOTALES</td>
                <td bgcolor='#037BC6' style='text-align:center; color:#FFF; border:.15mm solid black;'>{{ moneda($sumaPrecioTotal, '$') }}</td>
                <td bgcolor='#037BC6' style='text-align:center; color:#FFF; border:.15mm solid black;'>{{ porcent($sumaIncidencia, false) }}</td>
                <td bgcolor='#037BC6' style='text-align:center; color:#FFF; border:.15mm solid black;'>{{ porcent($porcentajeTotal, true) }}</td>
                <td bgcolor='#037BC6' style='text-align:center; color:#FFF; border:.15mm solid black;'>{{ porcent($porcentajeAnterior, true) }}</td>
                <td bgcolor='#037BC6' style='text-align:center; color:#FFF; border:.15mm solid black;'>{{ moneda($sumaImporteAnterior, '$') }}</td>
                <td bgcolor='#037BC6' style='text-align:center; color:#FFF; border:.15mm solid black;'>{{ porcent($sumaPorcentajeActual, true) }}</td>
                <td bgcolor='#037BC6' style='text-align:center; color:#FFF; border:.15mm solid black;'>{{ moneda($sumaImporteActual, '$') }}</td>
                <td bgcolor='#037BC6' style='text-align:center; color:#FFF; border:.15mm solid black;'>{{ porcent($sumaPorcentajeAcum, true) }}</td>
                <td bgcolor='#037BC6' style='text-align:center; color:#FFF; border:.15mm solid black;'>{{ moneda($sumaImporteAcum, '$') }}</td>
            </tr>

            <tr>
                <td height="7" colspan='12'></td>
            </tr>
            <tr>
                <td style='text-align:center;' colspan='4'>ANTICIPO FINANCIERO OTORGADO POR EL GOB PCIAL</td>
                <td></td>
                <td style="text-align:center; border:.15mm solid black;">{{ moneda($certificacion["AnticipoFinanciero"], '$') }}</td>
                <td colspan='6'></td>
            </tr>
            <tr>
                <td style='text-align:center;' colspan='4'>Anticipo Financiero Acumulado Anterior</td>
                <td></td>
                <td style="text-align:center; border:.15mm solid black;">{{ moneda($anticipoAcumulado, '$') }}</td>
                <td colspan='6'></td>
            </tr>
            <tr>
                <td style='text-align:center;' colspan='4'>Descuento Anticipo - Certificado Actual</td>
                <td></td>
                <td style="text-align:center; border:.15mm solid black;">{{ moneda($descuentoAnticipoActual, '$') }}</td>
                <td colspan='6'></td>
            </tr>
            <tr>
                <td height="5" colspan='12'></td>
            </tr>
            <tr>
                <td style='text-align:center;' colspan='4'>Descuento Anticipo Financiero Acumulado</td>
                <td></td>
                <td style="text-align:center; border:.15mm solid black;">{{ moneda($descuentoAnticipoAcumulado, '$') }}</td>
                <td colspan='6'></td>
            </tr>
            <tr>
                <td height="5" colspan='12'></td>
            </tr>
            <tr>
                <td style='text-align:center;' colspan='4'>Fondo de Reparo 5% -Del Certificado Bruto-</td>
                <td></td>
                <td style="text-align:center; border:.15mm solid black;" class='celeste' bgcolor='#8BC5F8'>{{ moneda($fondoReparo, '$') }}</td>
                <td colspan='6'></td>
            </tr>
            <tr>
                <td height="7" colspan='12'></td>
            </tr>
            <tr>
                <td style="text-align:center; border:.3mm solid black;" colspan='4'>TOTAL A PAGAR POR EL MUNICIPIO CORRESP. AL ACTUAL<br>(Para Fondo de Reparo)</td>
                <td style="text-align:center; border:.3mm solid black;">{{ moneda($totalPagoMunicipio, '$') }}</td>
                <td colspan='7'></td>
            </tr>
            <tr>
                <td height="7" colspan='12'></td>
            </tr>
            <tr>
                <td style="text-align:center; border:.3mm solid black; color:#FFF;" colspan='4' class='azul' bgcolor='#037BC6'>TOTAL A PAGAR POR EL GOBIERNO PROVINCIAL CORRESP. AL ACTUAL</td>
                <td style="text-align:center; border:.3mm solid black;">{{ moneda($totalPagoProvincia, '$') }}</td>
                <td colspan='2'></td>
                <td style="text-align:center; border-top:.15mm solid black;"colspan='2'>Firma Técnico/Contratista</td>
                <td></td>
                <td style="text-align:center; border-top:.15mm solid black;"colspan='2'>Firma Intendente</td>
                
            </tr>
            <tr><td colspan='12' class='gris' bgcolor='#CFCACC'>&nbsp;</td></tr>
        </tbody>
    </table>    
</body>
</html>