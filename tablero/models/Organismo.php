<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "organismo".
 *
 * @property string $IdOrganismo
 * @property string $Nombre
 * @property string $PrefijoCodigo
 * @property string $Comitente
 * @property string $Color
 *
 * @property Obra[] $obras
 * @property Obra[] $obras0
 * @property Pago[] $pagos
 * @property Solicitudproyecto[] $solicitudproyectos
 */
class Organismo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'organismo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Nombre', 'PrefijoCodigo'], 'required'],
            [['Comitente'], 'integer'],
            [['Nombre'], 'string', 'max' => 50],
            [['PrefijoCodigo'], 'string', 'max' => 2],
            [['Color'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'IdOrganismo' => 'Id Organismo',
            'Nombre' => 'Nombre',
            'PrefijoCodigo' => 'Prefijo Codigo',
            'Comitente' => 'Comitente',
            'Color' => 'Color',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getObras()
    {
        //return $this->hasMany(Obra::className(), ['IdOrganismo' => 'IdOrganismo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getObras0()
    {
        //return $this->hasMany(Obra::className(), ['IdComitente' => 'IdOrganismo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPagos()
    {
        //return $this->hasMany(Pago::className(), ['IdOrganismo' => 'IdOrganismo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSolicitudproyectos()
    {
        //return $this->hasMany(Solicitudproyecto::className(), ['IdOrganismo' => 'IdOrganismo']);
    }
}
