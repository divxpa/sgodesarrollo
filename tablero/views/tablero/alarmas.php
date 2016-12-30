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

use app\classes\F;


/* @var $this yii\web\View */
$this->title = 'SGO - Tablero - Alarmas';
?>
<div class="container">
    <h1>Memoria Descriptiva</h1>

    <?php //echo '<pre>'.print_r($_REQUEST,true).'</pre>'; ?>

    <?php $form = ActiveForm::begin([
        'id' => 'form', 
        'method' => 'get',
        'fieldConfig' => ['template' => "<div class='row'><div class='col-xs-2'>{label}</div><div class='col-xs-6'>{input}</div></div>"], 
        'validateOnSubmit' => false
    ]); ?>


    <?php ActiveForm::end(); ?>

    <?php if($alarmas): ?>


        <?php foreach($alarmas as $alarma): ?>

            <h4 class="titulo"><?= $alarma['categoria'] ?></h4>

            <?php 

            $cols = array_map(function($i, $elem) use ($alarma) {

                if (in_array($elem, ['Icono','Comentario'])) return ['visible' => false];

                return [
                    'attribute' => $elem,
                    'format' => 'raw',
                    'value' => function($data) use ($elem) {
                        return "<span class='".$data['Icono']."'> $data[$elem]</span>";
                    },
                ];

            }, array_keys($alarma['columns']), $alarma['columns']); 

            ?>

            <?php echo GridView::widget([
                'dataProvider' => $alarma['data'],
                'columns' => $cols,
            ]); ?>
        <?php endforeach ?>
        
    <?php else: ?>
        <em class="text-muted">Seleccione una Obra...</em>
    <?php endif; ?>
</div>