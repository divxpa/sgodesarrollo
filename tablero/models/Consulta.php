<?php

namespace app\models;

use Yii;

use app\classes\SqlFormatter as SQLF;

class Consulta
{
    public function consultaObras($mes, $anio, $organismo = "", $estado = "", $idOrganismo = "", $desde_anio = "", $hasta_anio = "", $alarma = "") 
    {  
        //>>FUNCION zerofill() con str_pad
        $periodo = $anio . str_pad($mes, 2, "0", STR_PAD_LEFT);
        $desde = $desde_anio ? $desde_anio."01" : "";
        $hasta = $hasta_anio ? $hasta_anio."12" : "";

        $where = [];

        $where[] ='o.Activo = 1';

        if ($organismo != "")
            $where[] = "og.Nombre = '$organismo' OR og2.Nombre = '$organismo'";

        if ($estado != "")
            $where[] = "eo.IdEstadoObra = '$estado'";

        if ($idOrganismo > 0)
            $where[] = "o.IdOrganismo = $idOrganismo OR o.IdComitente = $idOrganismo";

        if($desde != "" && $hasta != "") //>>MEJORAR LAS 3 LINEAS CON BETWEEN
            $where[] = "exists(SELECT * FROM certificacion WHERE IdContrato = co.IdContrato AND Periodo >= '$desde' AND Periodo <= '$hasta')  
                OR (date_format(o.FechaPresupuestoOficial, '%Y%m') >= '$desde' AND date_format(o.FechaPresupuestoOficial, '%Y%m') <= '$hasta') 
                OR (date_format(co.Fecha, '%Y%m') >= '$desde' AND date_format(co.Fecha, '%Y%m') <= '$hasta')"; 

        if ($alarma == 'con')
            $where[] = "(SELECT count(*) FROM alarmatablero WHERE IdObra = o.IdObra) > 0";
        else if ($alarma == 'sin')
            $where[] = "(SELECT count(*) FROM alarmatablero WHERE IdObra = o.IdObra) = 0";

        $where = "WHERE ".implode(' AND ', array_map(function($elem){
            return "($elem)";
        }, $where));
        
        //>> usar el select least para :
        //      FROM certificacion WHERE IdContrato = co.IdContrato
        //      FROM certificacion WHERE IdContrato = co.IdContrato AND Periodo = '$periodo')
        //      FROM certificacion WHERE IdContrato = co.IdContrato AND Periodo <= '$period


        $sql = "SELECT
            #select_begin
            (SELECT LEAST(
                @maxManoObraOcupada := max(manoobraocupada),
                @sumMontoAvance := sum(montoavance),
                @sumAnticipoFinanciero := sum(anticipofinanciero),
                @sumRedeterminacionPrecios := sum(redeterminacionprecios)
             )
             FROM certificacion 
             WHERE 
                IdContrato = co.IdContrato 
                AND Periodo <= '$periodo'
            ) AS CalculosCertificacionHastaPeriodo,

            og.IdOrganismo AS IdOrganismo,
            og.Nombre AS Organismo,
            og2.Nombre AS Comitente, 
            fnCodigosFufixObra(o.IdObra) AS Fufi, 
            fnDescripcionesFufixObra(o.IdObra) AS Financiamiento, 
            o.Denominacion AS Obra, 
            concat(og.PrefijoCodigo,'-',o.Codigo) AS CodObra, 
            o.Expediente AS Expediente, 
            fnLocalidadesxObra(o.IdObra, '\n') AS Localidad, 
            ifnull(o.CantidadBeneficiarios, 0) AS CantidadBeneficiarios, 
            concat(ifnull((SELECT sum(manoobraocupada) FROM certificacion WHERE IdContrato = co.IdContrato AND Periodo = '$periodo'),0), '/', ifnull(@maxManoObraOcupada,0)) AS CantManoObra, 
            p.RazonSocial AS RazonSocial, 
            o.CreditoPresupuestarioAprobado AS CreditoPresupuestarioAprobado, 
            @refuerzoPartida := ifnull((SELECT sum(Importe) FROM refuerzopartida WHERE IdObra = o.IdObra) ,0) AS RefuerzoPartida, 
            o.PresupuestoOficial AS PresupuestoOficial, 
            date_format(o.FechaPresupuestoOficial,'%d/%m/%Y') AS FechaPresupuestoOficial, 
            @adicionalesEst := o.PresupuestoOficial * 0.2 AS AdicionalesEst, 
            @redPreciosEst := IF(o.IdTipoObra in (1,3,6,10), 0, o.PresupuestoOficial * 0.15) AS RedPreciosEst, 
            o.PresupuestoOficial + @adicionalesEst + @redPreciosEst AS CredPresupEst,
            co.IdContrato AS IdContrato, 
            co.Numero AS NumeroContrato, 
            co.Monto AS MontoContrato, 
            date_format(co.FechaBaseMonto,'%d/%m/%Y') AS FechaBaseMonto, 
            @adicionales := ifnull((SELECT sum(Importe * IF(AdicionalDeductivo = 0, 1, -1)) FROM alteracion WHERE IdContrato = co.IdContrato),0) AS Adicionales, 
            co.PlazoEjecucion AS PlazoEjecucion, 
            date_format(co.FechaInicio,'%d/%m/%Y') AS FechaInicio, 
            ifnull((SELECT sum(CantidadDias) FROM contratoplazo WHERE IdContrato = co.IdContrato),0) AS AmpliacionPlazo, 
            date_format(ifnull((SELECT max(NuevaFechaFinalizacion) FROM contratoplazo WHERE IdContrato = co.IdContrato),co.FechaFinalizacion), '%d/%m/%Y') AS FechaFinalizacion, 
            ifnull((SELECT sum(montoavance) FROM certificacion WHERE IdContrato = co.IdContrato AND Periodo = '$periodo'),0) / (co.Monto + @adicionales) * 100 AS PorcentajeAvance, 
            @mntoAvanceAcum := ifnull(@sumMontoAvance,0) AS MontoAvanceAcum, 
            @mntoAvanceAcum/(co.Monto + @adicionales) * 100 AS PorcentajeAvanceAcum, 
            ifnull(@sumAnticipoFinanciero, 0) AS AnticipoFinanciero, 
            ifnull((SELECT sum(pc.Importe) FROM pagocertificacion pc INNER JOIN certificacion ce on pc.IdCertificacion = ce.IdCertificacion WHERE ce.IdContrato = co.IdContrato),0) AS PagoAcumulado, 
            @redetPrecioAcum := ifnull(@sumRedeterminacionPrecios,0) AS RedetPrecioAcum,
            o.CreditoPresupuestarioAprobado + @refuerzoPartida - @mntoAvanceAcum - @redetPrecioAcum AS SaldoCreditoPresup, 
            (SELECT concat(substring(max(Periodo),5,2),'/',substring(max(Periodo),1,4)) FROM certificacion WHERE IdContrato = co.IdContrato) AS UltimoMesCertif, 
            eo.Descripcion AS Estado, 
            o.DetalleEstado AS DetalleEstado, 
            IFNULL(o.MemoriaDescriptiva, '') AS MemoriaDescriptiva, 
            (SELECT count(*) FROM alarmatablero WHERE IdObra = o.IdObra) AS Alarmas 
            #select_end
        FROM 
            obra o
            INNER JOIN  organismo og on o.IdOrganismo = og.IdOrganismo 
            LEFT JOIN tipoobra tio on o.IdTipoObra = tio.IdTipoObra 
            INNER JOIN estadoobra eo on o.IdEstadoObra = eo.IdEstadoObra
            LEFT JOIN contrato co on o.IdObra = co.IdObra
            LEFT JOIN  proveedor p on co.IdProveedor = p.IdProveedor
            INNER JOIN organismo og2 on o.IdComitente = og2.IdOrganismo  
        $where 
        --    og.PrefijoCodigo, 
        --    o.Codigo
        "; //>> Estudiar que campos deberían ser no nulos para mejorar velocidad y simplificar
            //>> Atento a ver si influye la cantidad de decimales de las @variables generadas
        
  
        /* VERSION VIEJA: 25% más lenta

        $sql2 = "select " .
        "og.Nombre as Organismo, " .
        "og2.Nombre as Comitente, " .
        "fnCodigosFufixObra(o.IdObra) as Fufi, " .
        "fnDescripcionesFufixObra(o.IdObra) as NombreFufi, " .
        "o.Denominacion as Obra, " .
        "concat(og.PrefijoCodigo,'-',o.Codigo) as CodigoObra, " .
        "o.Expediente as Expediente, " .
        "fnLocalidadesxObra(o.IdObra, '\n') as Localidad, " .
        "ifnull(o.CantidadBeneficiarios, 0) as CantidadBeneficiarios, " .
        "concat(ifnull((select sum(manoobraocupada) from certificacion where IdContrato=co.IdContrato and Periodo='" . $periodo . "'),0), '/', ifnull((select max(manoobraocupada) from certificacion where IdContrato=co.IdContrato and Periodo<='" . $periodo . "'),0)) as CantManoObra, " .
        "p.RazonSocial, " .
        "o.CreditoPresupuestarioAprobado, " .
        "ifnull((select sum(Importe) from refuerzopartida where IdObra=o.IdObra) ,0) as RefuerzoPartida, " .
        "o.PresupuestoOficial, " .
        "date_format(o.FechaPresupuestoOficial,'%d/%m/%Y') as FechaPresupuestoOficial, " .
        "o.PresupuestoOficial*0.2 as AdicionalesEst, " .
        "(case when o.IdTipoObra in (1,3,6,10) then 0 else o.PresupuestoOficial*0.15 end) as RedPreciosEst, " .
        "o.PresupuestoOficial + (o.PresupuestoOficial*0.2) + (case when o.IdTipoObra in (1,3,6,10) then 0 else o.PresupuestoOficial*0.15 end) as CredPresupEst, " .
        "co.Numero as NumeroContrato, " .
        "co.Monto as MontoContrato, " .
        "date_format(co.FechaBaseMonto,'%d/%m/%Y') as FechaBaseMonto, " .
        "ifnull((select sum(Importe*(case when AdicionalDeductivo=0 then 1 else -1 end)) from alteracion where IdContrato=co.IdContrato),0) as Adicionales, " .
        "co.PlazoEjecucion, " .
        "date_format(co.FechaInicio,'%d/%m/%Y') as FechaInicio, " .
        "ifnull((select sum(CantidadDias) from contratoplazo where IdContrato=co.IdContrato),0) as AmpliacionPlazo, " .
        "date_format(ifnull((select max(NuevaFechaFinalizacion) from contratoplazo where IdContrato=co.IdContrato),co.FechaFinalizacion), '%d/%m/%Y') as FechaFinalizacion, " .
        "ifnull((select sum(montoavance) from certificacion where IdContrato=co.IdContrato and Periodo='" . $periodo . "'),0)/(co.Monto+ifnull((select sum(Importe*(case when AdicionalDeductivo=0 then 1 else -1 end)) from alteracion where IdContrato=co.IdContrato),0))*100 as PorcentajeAvance, " .
        "ifnull((select sum(montoavance) from certificacion where IdContrato=co.IdContrato and Periodo<='" . $periodo . "'),0)/(co.Monto+ifnull((select sum(Importe*(case when AdicionalDeductivo=0 then 1 else -1 end)) from alteracion where IdContrato=co.IdContrato),0))*100 as PorcentajeAvanceAcum, " .
        "ifnull((select sum(anticipofinanciero) from certificacion where IdContrato=co.IdContrato and Periodo<='" . $periodo . "'), 0) as AnticipoFinanciero, " .  
        "ifnull((select sum(pc.Importe) from pagocertificacion pc inner join certificacion ce on pc.IdCertificacion = ce.IdCertificacion where ce.IdContrato=co.IdContrato),0) as PagoAcumulado, " .
        "ifnull((select sum(montoavance) from certificacion where IdContrato=co.IdContrato and Periodo<='" . $periodo . "'),0) as MontoAvanceAcum, " .
        "ifnull((select sum(redeterminacionprecios) from certificacion where IdContrato=co.IdContrato and Periodo<='" . $periodo . "'),0) as RedetPrecioAcum," .
        "o.CreditoPresupuestarioAprobado + ifnull((select sum(Importe) from refuerzopartida where IdObra=o.IdObra) ,0) - ifnull((select sum(montoavance) from certificacion where IdContrato=co.IdContrato and Periodo<='" . $periodo . "'),0) - ifnull((select sum(redeterminacionprecios) from certificacion where IdContrato=co.IdContrato and Periodo<='" . $periodo . "'),0) as SaldoCreditoPresup, " .
        "(select concat(substring(max(Periodo),5,2),'/',substring(max(Periodo),1,4)) from certificacion where IdContrato=co.IdContrato) as UltimoMesCertif, " .
        "eo.Descripcion as Estado, " .
        "o.DetalleEstado, " .
        "(case when o.MemoriaDescriptiva='' or isnull(o.MemoriaDescriptiva) then '' else 'VER' end) as MemoriaDescriptiva, " .
        "(select count(*) from alarmatablero where IdObra=o.IdObra) as Alarmas " .
        "from " .
        "obra o inner join " .
        "organismo og on o.IdOrganismo = og.IdOrganismo left join " .
        "tipoobra tio on o.IdTipoObra = tio.IdTipoObra inner join " .
        "estadoobra eo on o.IdEstadoObra=eo.IdEstadoObra left join " .
        "contrato co on o.IdObra = co.IdObra left join " .
        "proveedor p on co.IdProveedor = p.IdProveedor inner join " .
        "organismo og2 on o.IdComitente = og2.IdOrganismo " .  
        $where . " and o.Activo=1 " .
        "order by " .
        "og.PrefijoCodigo, " .
        "o.Codigo"; */

        return $sql;

    }

    public function consultaProveedores($idProveedor, $idOrganismo = "")
    {  
        $whereAux = $idOrganismo != "" ? "AND (o.IdOrganismo = $idOrganismo OR o.IdComitente = $idOrganismo) " : "";

        $sql = "SELECT 
            #select_begin
            co.IdContrato, o.IdOrganismo,
            og.Nombre AS Organismo, 
            o.Denominacion AS Obra, 
            p.RazonSocial AS Proveedor, 
            co.Numero AS NroContrato, 
            co.Monto AS MontoContrato, 
            @adicionales := ifnull((SELECT sum(Importe * IF(AdicionalDeductivo = 0, 1, -1)) FROM alteracion WHERE IdContrato = co.IdContrato),0) AS AdicionalesContrato, 
            @totalContrato := co.Monto + @adicionales AS TotalContrato, 
            @totalContrato * IF(co.IdProveedor <> $idProveedor, 
                (SELECT PorcentajeSocio FROM ute WHERE IdProveedor = p.idProveedor AND IdProveedorSocio = $idProveedor ) / 100, 
                1) AS TotalEmpresa, 
            date_format(co.Fecha,'%d/%m/%Y') AS FechaContrato, 
            ifnull((SELECT sum(montoavance) FROM certificacion WHERE IdContrato = co.IdContrato),0) / @totalContrato * 100 AS PorcentajeCertif 
            #select_end
        FROM 
            contrato co 
            INNER JOIN obra o on co.IdObra = o.IdObra 
            INNER JOIN organismo og on o.IdOrganismo = og.IdOrganismo 
            INNER JOIN proveedor p on co.IdProveedor = p.IdProveedor 
        WHERE 
            (co.IdProveedor = $idProveedor OR exists(SELECT * FROM ute WHERE IdProveedor = p.idProveedor AND IdProveedorSocio = $idProveedor )) 
            AND o.Activo = 1 
            $whereAux";

        return $sql;

    }


    public function consultaContratos($idContrato, $idOrganismo)
    { 
        $sql = "SELECT 
            #select_begin
            co.IdContrato, o.IdOrganismo,
            og.Nombre AS Organismo, 
            o.Denominacion AS Obra, 
            p.RazonSocial AS Proovedor, 
            co.Numero AS NroContrato, 
            co.Monto AS MontoContrato, 
            @adicionales := ifnull((SELECT sum(Importe * IF(AdicionalDeductivo = 0, 1, -1)) FROM alteracion WHERE IdContrato = co.IdContrato),0) AS AdicionalesContrato, 
            @totalContrato := co.Monto + @adicionales AS TotalContrato, 
            date_format(co.Fecha,'%d/%m/%Y') AS FechaContrato, 
            ifnull((SELECT sum(montoavance) FROM certificacion WHERE IdContrato = co.IdContrato),0) / @totalContrato * 100 AS PorcentajeCertif 
            #select_end
        FROM 
            contrato co
            INNER JOIN obra o on co.IdObra = o.IdObra
            INNER JOIN organismo og on o.IdOrganismo = og.IdOrganismo
            INNER JOIN proveedor p on co.IdProveedor = p.IdProveedor
        WHERE 
            co.IdContrato = '$idContrato' AND og.IdOrganismo = '$idOrganismo'";
            //>> Columna contrato.Numero transformar en índice?

            // ESTO ERA DE LA CONSULTA DE PROVEEDORES:

            # @totalContrato * IF(co.IdProveedor <> $idProveedor, 
            #     (SELECT PorcentajeSocio FROM ute WHERE IdProveedor = p.idProveedor AND IdProveedorSocio = $idProveedor ) / 100, 
            #     1) AS TotalEmpresa,
        
            # (co.IdProveedor = $idProveedor OR exists(SELECT * FROM ute WHERE IdProveedor = p.idProveedor AND IdProveedorSocio = $idProveedor )) 
            # AND o.Activo = 1
            //>>^^ UNIFICAR ESTAS DOS CONSULTAS

        return $sql;

    }


    public function consultaContratoCertificaciones($idContrato)
    { 
        $sql = "SELECT 
            #select_begin
            ce.Periodo, 
            IF(ce.IdOrdenTrabajo is null, ce.NroCertificado, concat(ce.NroCertificado,' - OT ', ot.Numero)) as NroCertificado, 
            ce.PorcentajeAvance, 
            ce.MontoAvance, 
            ce.AnticipoFinanciero, 
            ce.DescuentoAnticipo, 
            ce.RetencionMulta, 
            ce.RetencionFondoReparo, 
            ce.RedeterminacionPrecios, 
            ce.OtrosConceptos, 
            ce.ImporteNeto, 
            @pago := ifnull((SELECT sum(Importe) FROM pagocertificacion WHERE IdCertificacion = ce.IdCertificacion), 0) as Pago, 
            ce.ImporteNeto - @pago as Saldo, 
            (SELECT LEAST(
                @porcentajeAvanceAcum := sum(PorcentajeAvance), 
                @montoAvanceAcum := sum(MontoAvance)
             )
             FROM certificacion 
             WHERE 
                IdContrato = ce.IdContrato 
                AND Periodo <= ce.Periodo 
                AND IF(ce.IdOrdenTrabajo is not null, IdOrdenTrabajo = ce.IdOrdenTrabajo, true)
            ) AS CalculosCertificacion,
            @porcentajeAvanceAcum as PorcentajeAvanceAcum, 
            @montoAvanceAcum as MontoAvanceAcum 
            #select_end
        FROM 
            certificacion ce 
            INNER JOIN contrato co on ce.IdContrato = co.IdContrato 
            LEFT JOIN ordentrabajo ot on ce.IdOrdenTrabajo = ot.IdOrdenTrabajo 
        WHERE 
            ce.IdContrato = $idContrato
        ORDER BY 
            ce.Periodo, ce.NroCertificado";

        return $sql;

    }

}

?>