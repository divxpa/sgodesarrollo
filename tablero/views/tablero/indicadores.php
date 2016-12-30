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
use app\classes\CustomGridColumn as Column;

/*
use app\models\Usuariorh;
use yii\data\ArrayDataProvider;
*/

/* @var $this yii\web\View */
$this->title = 'SGO - Tablero - Indicadores';
?>
<!-- <h1>Contratos</h1> -->

<?php //echo '<pre>'.print_r($_REQUEST,true).'</pre>'; ?>

<div class="container fixed-x">
    <?php $form = ActiveForm::begin([
        'id' => 'form', 
        'method' => 'get',
        'fieldConfig' => ['template' => "<div class='row'><div class='col-xs-2'>{label}</div><div class='col-xs-6'>{input}</div></div>"], 
        'validateOnSubmit' => false
    ]); ?>


    <?php ActiveForm::end(); ?>
</div>

<?php if($indicadores): ?>

    <div class="container fixed-x">
       <h3 class="titulo">Indicadores</h3>
        <table class="table table-striped">
            <thead>
                <th>INDICADOR</th>
                <th>CANTIDAD</th>
                <th>DETALLE</th>
            </thead>

            <tbody>
                <?php foreach($indicadores as $indicador): ?>
                    <tr>
                        <td ><?= $indicador['Nombre'] ?></td>
                        <td tooltip="<?= $indicador['Tooltip']?>"><span class="numero1"  ><?= $indicador['Numero1'] ?></span> / &nbsp;&nbsp;<?= $indicador['Numero2'] ?></td>

                        <?php $url = Url::to([
                            'tablero/indicadores', 
                            'nombreIndicador' => $indicador['Nombre'],
                        ]); ?> 
                        <td><a href="<?= $url ?>">ver...</a></td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
        
        <hr>

        <h3><?= $nombreIndicador ? "$nombreIndicador - " : "" ?>Detalles</h3> <?php //>> Implementar con Ajax a futuro... ?>
    </div>


    <?php if($detalles): ?>

        <div class="table-container">
            
            <?php 

            $cols = array_map(function($i, $elem) use ($detalles) {
                $redColumn = in_array($i + 1, $detalles['redColumns']);
                $columnColor = $redColumn ? 'red' : 'black';

                if ( in_array($elem, $detalles['columnTypes']['colsCurr']) )
                    $column = Column::curr($elem);
                else if ( in_array($elem, $detalles['columnTypes']['colsPrcnt']) )
                    $column = Column::prcnt($elem);
                else if ( in_array($elem, $detalles['columnTypes']['colsLongText']) )
                    $column = [
                        'attribute' => $elem,
                        'headerOptions' => ['style' => ['min-width' => '400px'] ],
                    ];
                else
                    $column = [
                        'attribute' => $elem, 
                        'noWrap' => true,
                    ];

                return Column::append($column, [
                    'headerOptions' => ['style' => ['color' => $columnColor] ],
                    'contentOptions' => ['style' => ['color' => $columnColor] ],
                ]);

            }, array_keys($detalles['columns']), $detalles['columns']); 

            ?>

            <?php echo GridView::widget([
                'dataProvider' => $detalles['data'],
                'responsive' => false,
                'floatHeader' => true,
                'floatHeaderOptions' => ['scrollingTop' => 124],
                'columns' => $cols,
            ]); ?>

        </div>

    <?php else: ?>

        <div class="container fixed-x">
            <em class="text-muted">Seleccione un indicador para ver los detalles...</em>
        </div>

    <?php endif; ?>

    </div>
<?php else: ?>
    <em class="text-muted">Error...</em>
<?php endif; ?>

<p id="tooltip" style="
    display: none;
    position: absolute;
    top:300px;
    left:300px;
    font-family: 'Open-Sans',sans-serif;
    background-color: #333;
    color: #DDD;
    padding: 3px 5px;
">Mensaje</p>

<script>
    $("[tooltip]").each(function(i, elem) {
        $(elem).mouseover(function(){
            console.log(elem);
            var text = $(elem).attr("tooltip");
            $("#tooltip").html(text).css({
                "top": $(elem).offset().top + $(elem).height() + 5,
                "left": $(elem).offset().left + 5
            }).show();
            //console.log($(elem).attr("tooltip"));
        }).mouseout(function(){
            
            $("#tooltip").hide();
            //console.log($(elem).attr("tooltip"));
        });
        //console.log($(elem).attr("tooltip"));
    }) ;
</script>