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
$this->title = 'SGO - Tablero - Contratos';
?>
<!-- <h1>Contratos</h1> -->

<?php //echo '<pre>'.print_r($_REQUEST,true).'</pre>'; ?>

<div class="container">
    <?php $form = ActiveForm::begin([
        'id' => 'form', 
        'method' => 'get',
        'fieldConfig' => ['template' => "<div class='row'><div class='col-xs-2'>{label}</div><div class='col-xs-6'>{input}</div></div>"], 
        'validateOnSubmit' => false
    ]); ?>


    <?php ActiveForm::end(); ?>

    <?php if($contrato): ?>

        <h3 class="titulo proveedor">Detalles del Contrato</h3>
        <table class="table table-striped">
            <tbody>
                <?php foreach($contrato as $campo => $valor): ?>
                    <tr>
                        <td><strong><?= $campo ?></strong></td>
                        <td><?= $valor ?></td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>

        <h3>Certificaciones del Contrato</h3>

        <?php echo GridView::widget([
            'dataProvider' => $certificaciones,
            'columns' => [
                'Periodo', 
                'NroCertificado', 
                Column::prcnt('PorcentajeAvance'), 
                Column::curr('MontoAvance'), 
                Column::curr('AnticipoFinanciero'), 
                Column::curr('DescuentoAnticipo'), 
                Column::curr('RetencionMulta'), 
                Column::curr('RetencionFondoReparo'), 
                Column::curr('RedeterminacionPrecios'), 
                Column::curr('OtrosConceptos'), 
                Column::curr('ImporteNeto'), 
                Column::curr('Pago'), 
                Column::curr('Saldo'), 
                Column::prcnt('PorcentajeAvanceAcum'), 
                Column::curr('MontoAvanceAcum'),
            ],
        ]); ?>


    <?php else: ?>
        <em class="text-muted">Seleccione un certificado para consultar...</em>
    <?php endif; ?>
</div>





