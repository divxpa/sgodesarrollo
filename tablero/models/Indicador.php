<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "indicador".
 *
 * @property string $IdIndicador
 * @property string $Nombre
 * @property string $Query
 * @property string $Activo
 * @property string $QueryCount
 * @property string $Tooltip
 */
class Indicador extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'indicador';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Nombre', 'Query', 'Activo', 'QueryCount'], 'required'],
            [['Query', 'QueryCount','Tooltip'], 'string'],
            [['Activo'], 'integer'],
            [['Nombre'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'IdIndicador' => 'Id Indicador',
            'Nombre' => 'Nombre',
            'Query' => 'Query',
            'Activo' => 'Activo',
            'QueryCount' => 'Query Count',
            'Tooltip'=>'Detalle'
        ];
    }
}
