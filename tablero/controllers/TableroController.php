<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
#use yii\web\Controller;
use app\components\PGController;
use yii\web\Request;
use yii\filters\VerbFilter;

use app\models\Consulta;
use app\models\Organismo;
use app\models\Proveedor;
use app\models\Alarma;
use app\models\Indicador;

use app\classes\DataProviderFromSQL;

use app\classes\F;

class TableroController extends PGController
{

    public function actionObras()
    {
        $rangoAnios = array_map(function($elem){
            return date("Y") + $elem;
        }, [
            "desde" => -10,
            "hasta" => 3
        ]);
        for ($anio = $rangoAnios["hasta"]; $anio >= $rangoAnios["desde"]; $anio-- )
            $aniosPosibles[$anio] = $anio;


        $request = Yii::$app->request;
        $requestQueryString = $_SERVER['QUERY_STRING'];

        $mes = $request->get('mes');
        $anio = $request->get('anio');
        $estado = $request->get('estado');
        $idOrganismo = $request->get('idOrganismo') ? $request->get('idOrganismo') : $this->session->get('usr_sgo_idOrganismo');
        $desde_anio = $request->get('desde_anio');
        $hasta_anio = $request->get('hasta_anio');
        $alarma = $request->get('alarma') ? $request->get('alarma') : 'todos';


        $order = $request->get('order');
        


        $organismo = Organismo::findOne($idOrganismo)['Nombre'];

        $sql = Consulta::consultaObras($mes, $anio, $organismo, $estado, $idOrganismo, $desde_anio, $hasta_anio, $alarma);
        $estadosObra = DataProviderFromSQL::create($sql, $order, 150);

        $columns = DataProviderFromSQL::getColumnNames($sql); //>> incluir las columnas en un único objeto que contenga también al data provider
    
        return $this->render('obras', [
            'mes' => $mes,
            'anio' => $anio,
            'organismo' => $organismo,
            'estado' => $estado,
            'idOrganismo' => $idOrganismo,
            'desde_anio' => $desde_anio,
            'hasta_anio' => $hasta_anio,
            'aniosPosibles' => $aniosPosibles,
            'estadosObra' => $estadosObra,
            'alarma' => $alarma,
            'queryString' => $requestQueryString,
        ]);
    }

    public function actionProveedores()
    {
        $request = Yii::$app->request;

        $idProveedor = $request->get('idProveedor');
        $idOrganismo = $request->get('idOrganismo');

        //>> "Procesando la consulta... Esto puede demorar algunos segundos..." (en todas las consultas)

        // Primer Parte
        if ($idProveedor) {

            $proveedor = Proveedor::findProveedorParaTablero($idProveedor);

            $proveedoresUte = DataProviderFromSQL::create( Proveedor::findProveedoresUte($proveedor, true) );

            $contratos = DataProviderFromSQL::create( Consulta::consultaProveedores($idProveedor, $idOrganismo) );

        } else {
            $proveedor = $proveedoresUte = $contratos = false;
        }

        return $this->render('proveedores', [
            'idOrganismo' => $idOrganismo,
            'proveedor' => $proveedor,
            'proveedoresUte' => $proveedoresUte,
            'contratos' => $contratos,
        ]);
    }

    public function actionContrato()
    {
        // desde Estado de Obras
        $request = Yii::$app->request;

        $idContrato = $request->get('idContrato');
        $idOrganismo = $request->get('idOrganismo');

        $contrato = Yii::$app->db->createCommand( Consulta::consultaContratos($idContrato, $idOrganismo) )->queryOne();
        unset($contrato['IdContrato'], $contrato['IdOrganismo']);
  
        $certificaciones = DataProviderFromSQL::create( Consulta::consultaContratoCertificaciones($idContrato) );

        return $this->render('contratos', [
            'idOrganismo' => $idOrganismo,
            'certificaciones' => $certificaciones,
            'contrato' => $contrato,
        ]);
    }

    public function actionAlarmas()
    {
        // desde Estado de Obras
        $request = Yii::$app->request;

        $codigoObra = $request->get('codigoObra');

        $alarmasCategorias = Alarma::categorias($codigoObra);
        $alarmas = [];
     
        foreach ($alarmasCategorias as $categoria)
        {
            $sqlListado = Alarma::listadoSegunCategoria($categoria, true);
            $alarma = [
                'categoria' => $categoria["Nombre"],
                'data' => DataProviderFromSQL::create($sqlListado, false, 0 ),
                'columns' => DataProviderFromSQL::getColumnNames($sqlListado),
            ];

            $alarmas[] = $alarma;
        }

        return $this->render('alarmas', [
            'alarmas' => $alarmas,
        ]);
     
    }


    public function actionIndicadores()
    {
        $request = Yii::$app->request;

        $nombreIndicador = $request->get('nombreIndicador');


        // Primera Parte: ACTUALIZACION DE INDICADORES
        $sql = "SELECT Nombre, Query, QueryCount,Tooltip FROM indicador WHERE Activo = 1 ORDER BY Nombre";
        $indicadores = Yii::$app->db->createCommand($sql)->queryAll();

        foreach ($indicadores as $i => $indicador)
        {
            $indicadores[$i]['Numero1'] = DataProviderFromSQL::countRows($indicador['Query']);
            //F::sql($indicador['Query']);
            $indicadores[$i]['Numero2'] = $indicador['QueryCount'] != '' ? DataProviderFromSQL::countRows($indicador['QueryCount']) : 0;
            //F::sql($indicador['QueryCount']);
            //die();
         } //>> cambiar nombre Numero por otro mejor

        // Segunda Parte: DETALLE DEL INDICADOR
        if ($nombreIndicador) 
        {
            $query = Indicador::find()->where(['Nombre' => $nombreIndicador])->one()->Query;

            // Devuelve la lista de Alias del Select en el array [1]
            preg_match_all("/AS ([^\,\=]*)\,/i", $query, $detallesColumnsMatch); // "/AS ([^\,\=]*)(\,|\sFROM)/i" es la exp. completa, pero como el de FROM es RedColumns, no se incluye

            $detalles = [
                'data' => DataProviderFromSQL::create($query, false, 0),
                'columns' => array_map(function($elem) {
                    return trim( str_replace("'", '', str_replace('"', '', $elem)) );
                }, $detallesColumnsMatch[1]),
                'redColumns' => explode(',', Yii::$app->db->createCommand("$query LIMIT 1")->queryOne()['RedColumns']),
                'columnTypes' => [
                    'colsCurr' => [
                        'CREDITO PRESUP. ASIGNADO',
                        'PRESUP. OFICIAL',
                        'DIFERENCIA',
                        'MONTO CONTRATO',
                        'ADICIONALES APROB.',
                        'RED. PRECIOS APROB.',
                        'TOTAL CONTRATO',
                        'REFUERZO PARTIDA',
                        '$ CERTIF.',
                    ],

                    'colsPrcnt' => [
                        '% AVANCE CERTIF.',
                        '% UTILIZ. CREDITO PRESUP.',
                    ],

                    'colsLongText' => [
                        'FINANCIAMIENTO',
                        'OBRA',
                        'LOCALIDAD',
                        'EMPRESA',
                    ],
                ]
            ];

            
        } else $detalles = false;


        return $this->render('indicadores', [
            'indicadores' => $indicadores,
            'nombreIndicador' => $nombreIndicador,
            'detalles' => $detalles,
        ]);

    }

    public function actionCambios() 
    {
        exit;
        //$sql = "UPDATE usuario AS u1 INNER JOIN usuario AS u2 ON u1.Username = u2.Username SET u1.sgo = 1;";

        //Yii::$app->db->createCommand($sql)->queryOne();
    }


    public function actionPruebas()
    {
        F::p(DataProviderFromSQL::getColumnNames(Consulta::consultaObras(2,2013)));
    }

    public function actionIndex() {
        return $this->render('index');
    }

}
