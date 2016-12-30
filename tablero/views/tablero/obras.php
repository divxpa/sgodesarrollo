<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\grid\GridView;
use kartik\select2\Select2;
use kartik\checkbox\CheckboxX;
use nirvana\showloading\ShowLoadingAsset;
use yii\web\JsExpression;
use yii\jui\AutoComplete;
use yii\jui\DatePicker;
use yii\helpers\ArrayHelper;

use app\models\Organismo;
use app\models\EstadoObra;

use app\helpers\Format;
use app\classes\F;
use app\classes\CustomGridColumn as Column;

/* @var $this yii\web\View */
$this->title = 'SGO - Tablero - Obras';
?>

<div class="form-container fixed-x container">
    <h1>Estado de Obras</h1>

    <?php //echo '<pre>'.print_r($_REQUEST,true).'</pre>'; ?>

    <?php $form = ActiveForm::begin([
        'id' => 'form', 
        'method' => 'get',
        'fieldConfig' => ['template' => "<div class='row'><div class='col-xs-2'>{label}</div><div class='col-xs-6'>{input}</div></div>"], 
        'validateOnSubmit' => false
    ]); ?>
        <div class="row">
            <div class="form-group col-xs-2">
                <?= Html::label('Año', 'anio', ['class' => '']) ?>
                <?= Select2::widget([
                    'name' => 'anio',
                    'value' => $anio ? $anio : date("Y"),
                    'data' => $aniosPosibles,
                    'options' => ['placeholder' => 'Año ...', 'id' => 'anio'],
                    /*'pluginOptions' => [
                        'allowClear' => true
                    ]*/
                ]); ?>
            </div>
            <div class="form-group col-xs-2">
                <?= Html::label('Mes', 'mes', ['class' => '']) ?>
                <?= Select2::widget([
                    'name' => 'mes',
                    'value' => $mes ? $mes : date("m"),
                    'data' => [1 => "Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"], //>> arreglo meses en otro lado
                    'options' => ['placeholder' => 'Mes ...', 'id' => 'mes'],
                    /*'pluginOptions' => [
                        'allowClear' => true
                    ]*/
                ]); ?>
            </div>

            <div class="form-group col-xs-4">
                <?= Html::label('Organismo', 'idOrganismo', ['class' => '']) ?>
                <?= Select2::widget([
                    'name' => 'idOrganismo',
                    'value' => $idOrganismo,
                    'data' => (['-1' => "Todos"] + ArrayHelper::map(Organismo::find()->orderBy(['Nombre' => SORT_ASC])->all(), 'IdOrganismo', 'Nombre')),
                    'options' => ['placeholder' => 'Seleccionar ...', 'id' => 'idOrganismo'],
                    /*'pluginOptions' => [
                        'allowClear' => true
                    ]*/
                ]); ?>
            </div>

            <div class="form-group col-xs-4">
                <?= Html::label('Estado&nbsp;de&nbsp;Obra', 'estado', ['class' => '']) ?>
                <?= Select2::widget([
                    'name' => 'estado',
                    'value' => $estado,
                    'data' => ArrayHelper::map(EstadoObra::find()->orderBy(['Descripcion' => SORT_ASC])->all(), 'IdEstadoObra', 'Descripcion'),
                    'options' => ['placeholder' => 'Seleccionar ...', 'id' => 'estado'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ]
                ]); ?>
            </div>
        </div>

        <div class="row">
            <div class="form-group col-xs-2">
                <?= Html::label('Desde', 'desde_anio', ['class' => '']) ?>
                <?= Select2::widget([
                    'name' => 'desde_anio',
                    'value' => $desde_anio,
                    'data' => $aniosPosibles,
                    'options' => ['placeholder' => 'Desde ...', 'id' => 'desde_anio'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ]
                ]); ?>
            </div>

            <div class="form-group col-xs-2">
                <?= Html::label('Hasta', 'hasta_anio', ['class' => '']) ?>
                <?= Select2::widget([
                    'name' => 'hasta_anio',
                    'value' => $hasta_anio,
                    'data' => $aniosPosibles,
                    'options' => ['placeholder' => 'Hasta ...', 'id' => 'hasta_anio'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ]
                ]); ?>
            </div>

            <div class="form-group col-xs-4">
                <?= Html::label('Mostrar', '', ['class' => '']) ?>
                <div class="btn-group" data-toggle="buttons">
                    <label class="btn btn-default <?= $alarma=='todos'  ? 'active' : '' ?>">
                        <input type="radio" name="alarma" id="optAlarmaTodos" autocomplete="off" value="todos" <?= $alarma=='todos' ? 'checked' : '' ?>> Todos
                    </label>
                    <label class="btn btn-default <?= $alarma=='con' ? 'active' : '' ?>">
                        <input type="radio" name="alarma" id="optAlarmaConAlarma" autocomplete="off" value="con" <?= $alarma=='con' ? 'checked' : '' ?>> Con Alarma
                    </label>
                    <label class="btn btn-default <?= $alarma=='sin' ? 'active' : '' ?>">
                        <input type="radio" name="alarma" id="optAlarmaSinAlarma" autocomplete="off" value="sin" <?= $alarma=='sin' ? 'checked' : '' ?>> Sin Alarma
                    </label>
                </div>      
            </div>

            <div class="col-xs-4 form-buttons">
                <?= Html::submitButton('Buscar', ['class' => 'btn btn-primary', 'name' => 'buscar']) ?>
                <a href=" <?= Url::to(['tablero/obras']) ?>" class='btn btn-primary'>Limpiar</a>
            </div>
        </div>


    <?php ActiveForm::end(); ?>
</div>

<hr class="fixed-x">

<?php //>> poner este script en clase de CustomColumns!!
    
    function sortableHeader($attribute, $title = '')
    {
        $queryString = preg_replace('/&?order=[^&]+/', '', $_SERVER['QUERY_STRING']);
        return ($title ? $title : $attribute)." <a href='?".$queryString."&order=$attribute%20ASC'>˅</a> <a href='?".$queryString."&order=$attribute%20DESC'>˄</a>";
    }

?>

<div class="table-container">
    <?php echo GridView::widget([
        'dataProvider' => $estadosObra,
        'floatHeader' => true,
        'floatHeaderOptions' => ['scrollingTop' => 124],
        'responsive' => false,
        'pager' => [
            'options' => ['class'=>'pagination fixed-x'], 
        ],
        'columns' => [
            [
                'header'=>sortableHeader('NumeroContrato', 'Contrato'),
                'format'=>'raw',
                'value'=>function($data) {  
                    if ($data['NumeroContrato']) {
                        $url = Url::to([
                            'tablero/contrato', 
                            'idContrato' => $data['IdContrato'],
                            'idOrganismo' => $data['IdOrganismo'],
                        ]);                              
                        return "<a href='$url'>ver...</a>";
                    } else return '';                            
                },
            ],
            [
                'header'=>sortableHeader('Organismo'),
                'attribute' => 'Organismo',
            ],
            [
                'header'=>sortableHeader('Comitente'),
                'attribute' => 'Comitente',
            ],
            [
                'header'=>sortableHeader('Fufi'),
                'attribute' => 'Fufi',
            ],
            [
                'header'=>sortableHeader('Financiamiento'),
                'attribute' => 'Financiamiento',
                'headerOptions' => ['style' => 'min-width:400px;'],
            ],
            [
                'header'=>sortableHeader('Obra'),
                'headerOptions' => ['style' => 'min-width:400px;'],
                'attribute' => 'Obra',
            ],
            [
                'header'=>'(!)', //sortableHeader('Alarma','<span class="btn-alarmas"></span>'),
                'format'=>'raw',
                'value'=>function($data) {  
                    $url = Url::to([
                        'tablero/alarmas', 
                        'codigoObra' => $data['CodObra'],
                    ]);                              
                    return ($data['Alarmas'] ? "<a class='btn-alarmas' title='ver alarmas...' href='$url'></a>" : "");

                },
            ],
            [
                'header'=>sortableHeader('CodObra'),
                'attribute' => 'CodObra',
            ],
            [
                'header'=>sortableHeader('Expediente'),
                'attribute' => 'Expediente',
                'noWrap' => true,
            ],
            [
                'header'=>sortableHeader('Localidad'),
                'headerOptions' => ['style' => 'min-width:400px;'],
                'attribute' => 'Localidad',
            ],
            [
                'header'=>sortableHeader('CantidadBeneficiarios'),
                'attribute' => 'CantidadBeneficiarios',
            ],
            [
                'header'=>sortableHeader('CantManoObra'),
                'attribute' => 'CantManoObra',
            ],
            [
                'header'=>sortableHeader('RazonSocial'),
                'attribute' => 'RazonSocial',
            ],
            Column::curr('CreditoPresupuestarioAprobado', [
                'header'=>sortableHeader('CreditoPresupuestarioAprobado'),
            ]),
            Column::curr('RefuerzoPartida', [
                'header'=>sortableHeader('RefuerzoPartida'),
            ]),
            Column::curr('PresupuestoOficial', [
                'header'=>sortableHeader('PresupuestoOficial'),
            ]),
            [
                'header'=>sortableHeader('CONCAT(SUBSTR(FechaPresupuestoOficial,7,4),SUBSTR(FechaPresupuestoOficial,4,2),SUBSTR(FechaPresupuestoOficial,1,2))', 'FechaPresupuestoOficial'),
                'attribute' => 'FechaPresupuestoOficial',
            ],
            Column::curr('AdicionalesEst', [
                'header'=>sortableHeader('AdicionalesEst'),
            ]),
            Column::curr('RedPreciosEst', [
                'header'=>sortableHeader('RedPreciosEst'),
            ]),
            Column::curr('CredPresupEst', [
                'header'=>sortableHeader('CredPresupEst'),
            ]),
            'NumeroContrato',
            Column::curr('MontoContrato', [
                'header'=>sortableHeader('MontoContrato'),
            ]),
            [
                'header'=>sortableHeader('CONCAT(SUBSTR(FechaBaseMonto,7,4),SUBSTR(FechaBaseMonto,4,2),SUBSTR(FechaBaseMonto,1,2))', 'FechaBaseMonto'),
                'attribute' => 'FechaBaseMonto',
            ],
            Column::curr('Adicionales', [
                'header'=>sortableHeader('Adicionales'),
            ]),
            [
                'header'=>sortableHeader('PlazoEjecucion'),
                'attribute' => 'PlazoEjecucion',
            ],
            [
                'header'=>sortableHeader('CONCAT(SUBSTR(FechaInicio,7,4),SUBSTR(FechaInicio,4,2),SUBSTR(FechaInicio,1,2))', 'FechaInicio'),
                'attribute' => 'FechaInicio',
            ],            
            [
                'header'=>sortableHeader('AmpliacionPlazo'),
                'attribute' => 'AmpliacionPlazo',
            ],            
            [
                'header'=>sortableHeader('CONCAT(SUBSTR(FechaFinalizacion,7,4),SUBSTR(FechaFinalizacion,4,2),SUBSTR(FechaFinalizacion,1,2))', 'FechaFinalizacion'),
                'attribute' => 'FechaFinalizacion',
            ],
            Column::prcnt('PorcentajeAvance', [
                'header'=>sortableHeader('PorcentajeAvance'),
            ]),
            Column::curr('MontoAvanceAcum', [
                'header'=>sortableHeader('MontoAvanceAcum'),
            ]),
            Column::prcnt('PorcentajeAvanceAcum', [
                'header'=>sortableHeader('PorcentajeAvanceAcum'),
            ]),
            Column::curr('AnticipoFinanciero', [
                'header'=>sortableHeader('AnticipoFinanciero'),
            ]),
            Column::curr('PagoAcumulado', [
                'header'=>sortableHeader('PagoAcumulado'),
            ]),
            Column::curr('RedetPrecioAcum', [
                'header'=>sortableHeader('RedetPrecioAcum'),
            ]),
            Column::curr('SaldoCreditoPresup', [
                'header'=>sortableHeader('SaldoCreditoPresup'),
            ]),
            [
                'header'=>sortableHeader('CONCAT(SUBSTR(UltimoMesCertif,4,4),SUBSTR(UltimoMesCertif,1,2))','UltimoMesCertif'),
                'attribute' => 'UltimoMesCertif',
            ],            
            [
                'header'=>sortableHeader('Estado'),
                'attribute' => 'Estado',
            ],            
            [
                'attribute' => 'DetalleEstado',
                'header'=>sortableHeader('DetalleEstado','Detalle Estado'),
                'headerOptions' => ['style' => 'min-width:400px;'],
            ],
            [
                'header'=>sortableHeader('MemoriaDescriptiva','Memoria Descriptiva'),
                'format'=>'raw',
                'value'=>function($data) {
                    if ($data['MemoriaDescriptiva']) {
                        return "
                            <button type='button' class='btn btn-info btn-sm' data-toggle='modal' data-target='#cuadroModal' onclick='javascript:

                                $(\"#cuadroModal .modal-title\").html(\"".$data['Organismo']." - ".$data['Obra']."\");
                                $(\"#cuadroModal .modal-body\").html(\"".$data['MemoriaDescriptiva']."\");
                                console.log(\"".$data['MemoriaDescriptiva']."\");

                            '>Ver</button>
                        ";
                    } else return '';                
                },
            ],
        ],
    ]); ?>
</div>

<!-- Modal -->
<div id="cuadroModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modal Header</h4>
      </div>
      <div class="modal-body">
        <p>Some text in the modal.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>

  </div>
</div>


