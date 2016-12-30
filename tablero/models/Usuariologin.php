<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "usuariologin".
 *
 * @property string $IdUsuarioLogin
 * @property string $IdUsuario
 * @property string $Ip
 * @property string $SessionId
 * @property string $FechaHoraLogin
 * @property string $FechaHoraLogout
 *
 * @property Usuario $idUsuario
 */
class Usuariologin extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'usuariologin';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['IdUsuario', 'Ip', 'SessionId', 'FechaHoraLogin'], 'required'],
            [['IdUsuario'], 'integer'],
            [['FechaHoraLogin', 'FechaHoraLogout'], 'safe'],
            [['Ip'], 'string', 'max' => 15],
            [['SessionId'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'IdUsuarioLogin' => 'Id Usuario Login',
            'IdUsuario' => 'Id Usuario',
            'Ip' => 'Ip',
            'SessionId' => 'Session ID',
            'FechaHoraLogin' => 'Fecha Hora Login',
            'FechaHoraLogout' => 'Fecha Hora Logout',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdUsuario()
    {
        return $this->hasOne(Usuario::className(), ['IdUsuario' => 'IdUsuario']);
    }
}
