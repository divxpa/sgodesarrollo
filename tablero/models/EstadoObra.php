<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "estadoobra".
 *
 * @property string $IdEstadoObra
 * @property string $Descripcion
 *
 * @property Obra[] $obras
 * @property Obraestado[] $obraestados
 */
class EstadoObra extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'estadoobra';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Descripcion'], 'required'],
            [['Descripcion'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'IdEstadoObra' => 'Id Estado Obra',
            'Descripcion' => 'Descripción',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getObras()
    {
        //return $this->hasMany(Obra::className(), ['IdEstadoObra' => 'IdEstadoObra']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getObraestados()
    {
        //return $this->hasMany(Obraestado::className(), ['IdEstadoObra' => 'IdEstadoObra']);
    }
}
