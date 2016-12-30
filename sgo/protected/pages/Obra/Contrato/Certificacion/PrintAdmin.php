<?php


function moneda($valor, $signo = false) {
    return ($signo ? $signo.' ' : '').number_format($valor, 2, ",", ".");
}

function porcent($valor, $signo = false) {
    return number_format($valor, 4, ".", "").($signo ? '%' : '');
}


$idObra = $_GET["ido"];
$idContrato = $_GET["idc"];
$idCertificacion = $_GET["id"];


$obra = ObraRecord::finder()->findByPk($idObra);
$organismo = OrganismoRecord::finder()->findByPk($obra->IdOrganismo);
$contrato = ContratoRecord::finder()->findByPk($idContrato);
$proveedor = ProveedorRecord::finder()->findByPk($contrato->IdProveedor);

$localidades = PageBaseSP::CreateDataSource("ObraPeer", "LocalidadesPorObra", $idObra)[0];

$tituloObra = $organismo->PrefijoCodigo . '-' . $obra->Codigo . ' ' . $obra->Denominacion; 
$municip_comisionFomento = $localidades["Localidades"]; //OK

$certificacion = PageBaseSP::CreateDataSource("CertificacionPeer", "getCertificacion", $idCertificacion)[0];
$certificacionAnterior = PageBaseSP::CreateDataSource("CertificacionPeer", "getCertificacionAnterior", $idContrato, $certificacion["Periodo"])[0];
$contratoItems = PageBaseSP::CreateDataSource("ContratoPeer", "ItemsByContratoCertificacion", $idContrato, $certificacion["Periodo"], $idCertificacion);

$periodo = substr($certificacion["Periodo"], 4, 2) . "/" . substr($certificacion["Periodo"], 0, 4);

$fechaInicio = $contrato->FechaInicio != null ? preg_replace('/(\d+)-(\d+)-(\d+)/','$3/$2/$1', $contrato->FechaInicio) : '-';

$fechaMedicion = $certificacion["FechaMedicion"] != null ? 
    preg_replace('/(\d+)-(\d+)-(\d+)/','$3/$2/$1', $certificacion["FechaMedicion"]) : '-';

$fechaPagoAnticipo = $certificacion["FechaPagoAnticipo"] != null ? 
    preg_replace('/(\d+)-(\d+)-(\d+)/','$3/$2/$1', $certificacion["FechaPagoAnticipo"]) : '-';

$contrato->PlazoEjecucion;

$certificacionAnterior["ImporteCertifAnterior"];
$porcentajeCertifAnterior = $certificacionAnterior["ImporteCertifAnterior"] / $contrato->Monto * 100;

$fechaUltimoPago = $certificacionAnterior["FechaUltimoPago"] != null ? 
    preg_replace('/(\d+)-(\d+)-(\d+)/','$3/$2/$1', $certificacionAnterior["FechaUltimoPago"]) : '-';



// CALCULO DE TOTALES
$sumaPrecioTotal = $sumaIncidencia = $sumaImporteAnterior = $sumaImporteActual = $sumaImporteAcum = 0;
foreach ($contratoItems as $i => $item) {
    if ($item['tipo'] == 2) { // Items con Sub-Items
        $padre = $i;
        $contratoItems[$padre]['PrecioTotal'] = 0; //1230;  
        $contratoItems[$padre]['Incidencia'] = 0; //1230;
        $contratoItems[$padre]['IncidenciaPorcentaje'] = 0; //1230;
        $contratoItems[$padre]['ImporteAnterior'] = 0; //1230;
        $contratoItems[$padre]['PorcentajeAnterior'] = 0; //1230;
        $contratoItems[$padre]['ImporteActual'] = 0; //1230;
        $contratoItems[$padre]['PorcentajeActual'] = 0; //1230;
        $contratoItems[$padre]['ImporteAcum'] = 0; //1230;
        $contratoItems[$padre]['PorcentajeAcum'] = 0; //1230;
    } else {
        if ($item['tipo'] == 3) { // Sub-Items
            $contratoItems[$padre]['PrecioTotal'] += $contratoItems[$i]['PrecioTotal'];
            $contratoItems[$padre]['Incidencia'] += $contratoItems[$i]['Incidencia'];
            $contratoItems[$padre]['IncidenciaPorcentaje'] += $contratoItems[$i]['IncidenciaPorcentaje'];
            $contratoItems[$padre]['ImporteAnterior'] += $contratoItems[$i]['ImporteAnterior'];
            $contratoItems[$padre]['PorcentajeAnterior'] += $contratoItems[$i]['PorcentajeAnterior'];
            $contratoItems[$padre]['ImporteActual'] += $contratoItems[$i]['ImporteActual'];
            $contratoItems[$padre]['PorcentajeActual'] += $contratoItems[$i]['PorcentajeActual'];
            $contratoItems[$padre]['ImporteAcum'] += $contratoItems[$i]['ImporteAcum'];
            $contratoItems[$padre]['PorcentajeAcum'] += $contratoItems[$i]['PorcentajeAcum'];
        }
        // No Sub-Items
        $sumaPrecioTotal += $contratoItems[$i]['PrecioTotal'];
        $sumaIncidencia += $contratoItems[$i]['Incidencia'];
        $sumaImporteAnterior += $contratoItems[$i]['ImporteAnterior'];
        $sumaImporteActual += $contratoItems[$i]['ImporteActual'];
        $sumaImporteAcum += $contratoItems[$i]['ImporteAcum'];
        
    }
}
$porcentajeTotal = $sumaIncidencia * 100;
$porcentajeAnterior = $sumaImporteAnterior / $contrato->Monto * 100;
$sumaPorcentajeActual = $sumaImporteActual / $contrato->Monto * 100;
$sumaPorcentajeAcum = $sumaImporteAcum / $contrato->Monto * 100;


// ULTIMOS CAMPOS
$anticipoAcumulado = $certificacionAnterior["AnticipoFinancieroAnterior"];
$anticipoFinancieroPorcentaje = $certificacion["AnticipoFinanciero"] / $contrato->Monto * 100;
$descuentoAnticipoActual = $sumaPorcentajeActual * ($anticipoAcumulado + $certificacion["AnticipoFinanciero"]);
$descuentoAnticipoAcumulado = $certificacionAnterior["DescuentoAnticipoAnterior"] + $descuentoAnticipoActual;
$fondoReparo = $certificacion["RetencionFondoReparo"];
$totalPagoProvincia = $sumaImporteActual - $descuentoAnticipoActual;
$totalPagoMunicipio = $totalPagoProvincia - $fondoReparo;

// FILTRADO DE ITEMS NO HIJOS
//$items = array_filter($contratoItems, function($elem){return $elem['tipo'] != 3;});

$items = [];
foreach ($contratoItems as $item) {
    if ($item['tipo'] != 3) $items[] = $item;
}


//==============================================================
//==============================================================
//==============================================================

// CARGA la vista a mostrar en PDF
ob_start();
eval(
    " ?>"
    .str_replace( '{{', '<?php echo ', 
     str_replace('}}', ' ?>', file_get_contents('protected/pages/Obra/Contrato/Certificacion/plantilla_certif.php'))) 
    ."<?php "
);
$html = ob_get_clean();


/*echo "<pre>";
print_r($contratoItems);
print_r($items);

echo "</pre>";
/**/

//echo str_replace('<!--style','<style', str_replace('style-->','style>', $html)); die();


//==============================================================
//==============================================================
//==============================================================

$mpdf=new mPDF('c','A4-L','','',10,10,10,10,16,13); 

$mpdf->SetDisplayMode('fullpage');

$mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list

// LOAD a stylesheet
//$stylesheet = file_get_contents('mpdfstyletables.css');
$mpdf->WriteHTML($stylesheet,1);    // The parameter 1 tells that this is css/style only and no body/html/text

$mpdf->WriteHTML($html,2);

$mpdf->Output('mpdf.pdf','I');
exit;
//==============================================================
//==============================================================
//==============================================================

?>