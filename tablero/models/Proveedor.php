<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "proveedor".
 *
 * @property string $IdProveedor
 * @property string $Cuit
 * @property string $RazonSocial
 * @property string $Domicilio
 * @property string $IdLocalidad
 * @property string $RepresentanteTecnico
 * @property string $Telefono
 * @property string $Email
 *
 * @property Contrato[] $contratos
 * @property Pago[] $pagos
 * @property Localidad $idLocalidad
 * @property Ute[] $utes
 * @property Ute[] $utes0
 * @property Proveedor[] $idProveedorSocios
 * @property Proveedor[] $idProveedors
 */
class Proveedor extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'proveedor';
    }
//>>Chequear todas las reglas de los modelos creados
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Cuit', 'RazonSocial'], 'required'],
            [['IdLocalidad'], 'integer'],
            [['Cuit'], 'string', 'max' => 13],
            [['RazonSocial', 'Domicilio', 'RepresentanteTecnico'], 'string', 'max' => 255],
            [['Telefono', 'Email'], 'string', 'max' => 50],
            [['IdLocalidad'], 'exist', 'skipOnError' => true, 'targetClass' => Localidad::className(), 'targetAttribute' => ['IdLocalidad' => 'IdLocalidad']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'IdProveedor' => 'Id Proveedor',
            'Cuit' => 'Cuit',
            'RazonSocial' => 'Razon Social',
            'Domicilio' => 'Domicilio',
            'IdLocalidad' => 'Id Localidad',
            'RepresentanteTecnico' => 'Representante Tecnico',
            'Telefono' => 'Telefono',
            'Email' => 'Email',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContratos()
    {
        return $this->hasMany(Contrato::className(), ['IdProveedor' => 'IdProveedor']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPagos()
    {
        return $this->hasMany(Pago::className(), ['IdProveedor' => 'IdProveedor']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdLocalidad()
    {
        return $this->hasOne(Localidad::className(), ['IdLocalidad' => 'IdLocalidad']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUtes()
    {
        return $this->hasMany(Ute::className(), ['IdProveedor' => 'IdProveedor']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUtes0()
    {
        return $this->hasMany(Ute::className(), ['IdProveedorSocio' => 'IdProveedor']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdProveedorSocios()
    {
        return $this->hasMany(Proveedor::className(), ['IdProveedor' => 'IdProveedorSocio'])->viaTable('ute', ['IdProveedor' => 'IdProveedor']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdProveedors()
    {
        return $this->hasMany(Proveedor::className(), ['IdProveedor' => 'IdProveedor'])->viaTable('ute', ['IdProveedorSocio' => 'IdProveedor']);
    }

    //>> Hacer más limpia la consulta buscando por separado únicamente la localidad en consulta separada y 
    //   verificar si es ute directamente en findProveedoresUte()
    public function findProveedorParaTablero($idProveedor, $sqlOnly = false)
    {
        $sql = "SELECT
            p.Cuit,
            p.RazonSocial,
            p.Domicilio,
            l.Nombre AS Localidad,
            p.RepresentanteTecnico,
            p.Telefono,
            p.Email,
            IF(exists(SELECT * FROM ute WHERE IdProveedor = p.IdProveedor), 1, 0) AS Ute,
            p.IdProveedor
        FROM
            proveedor AS p 
            LEFT JOIN localidad AS l ON p.IdLocalidad = l.IdLocalidad
        WHERE
            p.IdProveedor = $idProveedor";

        if ($sqlOnly) return $sql;
        return Yii::$app->db->createCommand($sql)->queryOne();
    }

    // Recibe un $proveedor de findProveedorParaTablero()
    // (porque necesita evaluar si es Ute o no)
    public function findProveedoresUte($proveedor, $sqlOnly = false)
    {
        $idProveedor = $proveedor['IdProveedor'];

        // Segunda Parte
        if ($proveedor['Ute']) {
            $campo = (object)[
                'join' => 'u.IdProveedorSocio', 
                'where' => 'u.IdProveedor'
            ];
        }
        else {
            $campo = (object)[
                'join' => 'u.IdProveedor', 
                'where' => 'u.IdProveedorSocio'
            ];
        }

        $sql = "SELECT 
            p.Cuit, 
            p.RazonSocial, 
            u.PorcentajeSocio 
        FROM 
            proveedor p 
            INNER JOIN ute u ON p.Idproveedor = $campo->join
        WHERE $campo->where = $idProveedor";

        if ($sqlOnly) return $sql;
        return Yii::$app->db->createCommand($sql)->queryAll();
    }


    public function fillProveedores($sqlOnly = false)
    {
        $sql = "SELECT 
            IdProveedor, 
            CONCAT(RazonSocial,' (',Cuit, ')') AS Descripcion 
        FROM proveedor ORDER BY RazonSocial";

        if ($sqlOnly) return $sql;
        return Yii::$app->db->createCommand($sql)->queryAll();
    }

}
