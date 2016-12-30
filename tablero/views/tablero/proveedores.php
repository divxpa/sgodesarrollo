<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\grid\GridView;
use kartik\select2\Select2;
use kartik\checkbox\CheckboxX;
use nirvana\showloading\ShowLoadingAsset;
use yii\web\JsExpression;
use yii\jui\AutoComplete;
use yii\jui\DatePicker;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

use app\models\Proveedor;
use app\models\Organismo;
use app\models\EstadoObra;

use app\classes\CustomGridColumn as Column;

/* @var $this yii\web\View */
$this->title = 'SGO - Tablero - Proveedores';
?>

<div class="container">
    <h1>Proveedores</h1>

    <?php //echo '<pre>'.print_r($_REQUEST,true).'</pre>'; ?>

    <?php $form = ActiveForm::begin([
        'id' => 'form', 
        'method' => 'get',
        'fieldConfig' => ['template' => "<div class='row'><div class='col-xs-2'>{label}</div><div class='col-xs-6'>{input}</div></div>"], 
        'validateOnSubmit' => false
    ]); ?>
        
        <?= Html::label('Proveedor', 'idProveedor', ['class' => '']) ?>
        <?= Select2::widget([
            'name' => 'idProveedor',
            'value' => $proveedor['IdProveedor'],
            'data' => ArrayHelper::map(Proveedor::fillProveedores(), 'IdProveedor', 'Descripcion'),
            'options' => ['placeholder' => 'Seleccionar ...', 'id' => 'idProveedor'],
            'pluginOptions' => [
                'allowClear' => true
            ]
        ]); ?>
        <br>

        <?= Html::label('Organismo', 'idOrganismo', ['class' => '']) ?>
        <?= Select2::widget([
            'name' => 'idOrganismo',
            'value' => $idOrganismo,
            'data' => ArrayHelper::map(Organismo::find()->orderBy(['Nombre' => SORT_ASC])->all(), 'IdOrganismo', 'Nombre'),
            'options' => ['placeholder' => 'Seleccionar ...', 'id' => 'idOrganismo'],
            'pluginOptions' => [
                'allowClear' => true
            ]
        ]); ?>

        <br>
        <?= Html::submitButton('Buscar', ['class' => 'btn btn-primary', 'name' => 'buscar']) ?>
        <a href=" <?= Url::to(['tablero/proveedores']) ?>" class='btn btn-primary'>Limpiar</a>


    <?php ActiveForm::end(); ?>

    <?php if($proveedor): ?>
        <div class="row">
            <div class="col-xs-6">
                <h3 class="titulo proveedor">Datos del Proveedor</h3>
                <table class="table table-striped">
                    <tbody>
                        <?php foreach($proveedor as $campo => $valor): ?>
                            <tr>
                                <td><strong><?= $campo ?></strong></td>
                                <td><?= $valor ?></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
            <div class="col-xs-6">
                <h3><?php echo $proveedor['Ute'] ? "Proveedores que conforman la UTE" : "UTEs que conforma el proveedor" ?></h3>

                <?php echo GridView::widget([
                    'dataProvider' => $proveedoresUte,
                    'columns' => [
                        'Cuit',
                        'RazonSocial',
                        'PorcentajeSocio',
                        /*[ 'attribute' => 'Monto',
                            'vAlign' => 'middle',

                        ],*/
                    ],
                ]); ?>
            </div>
        </div>

        <h3>Contratos del Proveedor</h3>

        <?php echo GridView::widget([
            'dataProvider' => $contratos,
            'columns' => [
                [
                    'header'=>'Ver Contrato',
                    'format'=>'raw',
                    'value'=>function($data) {  
                        $url = Url::to([
                            'tablero/contrato', 
                            'idContrato' => $data['IdContrato'],
                            'idOrganismo' => $data['IdOrganismo'],
                        ]);                              
                        return "<a href='$url'>ver...</a>";
                    },
                ],
                'Organismo',
                'Obra',
                'Proveedor',
                'NroContrato',
                Column::curr('MontoContrato'),
                Column::curr('AdicionalesContrato'),
                Column::curr('TotalContrato'),
                Column::curr('TotalEmpresa'),
                'FechaContrato',
                Column::prcnt('PorcentajeCertif'),
            ],
        ]); ?>
    <?php else: ?>
        <em class="text-muted">Seleccione un proveedor para consultar...</em>
    <?php endif; ?>
</div>