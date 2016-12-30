<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "alarma".
 *
 * @property string $IdAlarma
 * @property string $Nombre
 * @property string $Query
 * @property string $Activo
 * @property string $Alcance
 * @property string $Tipo
 *
 * @property Alarmarol[] $alarmarols
 * @property Rol[] $idRols
 * @property Alarmatablero[] $alarmatableros
 * @property Alarmausuario[] $alarmausuarios
 */
class Alarma extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'alarma';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Nombre', 'Query', 'Tipo'], 'required'],
            [['Query'], 'string'],
            [['Activo', 'Alcance'], 'integer'],
            [['Nombre'], 'string', 'max' => 100],
            [['Tipo'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'IdAlarma' => 'Id Alarma',
            'Nombre' => 'Nombre',
            'Query' => 'Query',
            'Activo' => 'Activo',
            'Alcance' => '1. Sgo 2. Tablero 3. Todos',
            'Tipo' => '(D)atos (G)estion',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlarmarols()
    {
        return $this->hasMany(Alarmarol::className(), ['IdAlarma' => 'IdAlarma']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdRols()
    {
        return $this->hasMany(Rol::className(), ['IdRol' => 'IdRol'])->viaTable('alarmarol', ['IdAlarma' => 'IdAlarma']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlarmatableros()
    {
        return $this->hasMany(Alarmatablero::className(), ['IdAlarma' => 'IdAlarma']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlarmausuarios()
    {
        return $this->hasMany(Alarmausuario::className(), ['IdAlarma' => 'IdAlarma']);
    }

    public function categorias($codigoObra, $sqlOnly = false)
    {
        $sql = "SELECT DISTINCT 
            a.IdAlarma, 
            a.Nombre, 
            a.Query, 
            at.IdObra 
        FROM 
            alarmatablero at 
            INNER JOIN obra o on at.IdObra = o.IdObra 
            INNER JOIN organismo og on o.IdOrganismo = og.IdOrganismo 
            INNER JOIN alarma a on at.IdAlarma = a.IdAlarma 
        WHERE 
            concat(og.PrefijoCodigo,'-',o.Codigo) = '$codigoObra'";

        if ($sqlOnly) return $sql;
        return Yii::$app->db->createCommand($sql)->queryAll();
    }


    public function listadoSegunCategoria($alarmaCategoria, $sqlOnly = false)
    {
        $idAlarma = $alarmaCategoria["IdAlarma"];
        $idObra = $alarmaCategoria["IdObra"];
        $sqlAlarmas = $alarmaCategoria["Query"];

        //Estaba en el script de Excel pero sin usar...
        //idCertificacion = rs.getString("IdCertificacion");
        //query = "select v.*, (select max(Comentario) from alarmausuario au inner join alarmausuariodetalle aud on au.IdAlarmaUsuario = aud.IdAlarmaUsuario where au.IdAlarma = " + idAlarma + " and aud.IdObra = v.IdObra and (IdCertificacion = v.IdCertificacion or (IdCertificacion is null and v.IdCertificacion is null))) as Comentario from (" + query + ") v where v.IdObra = " + idObra + " and exists(select * from alarmatablero where IdObra = v.IdObra and (IdCertificacion = v.IdCertificacion or (IdCertificacion is null and v.IdCertificacion is null)) and IdAlarma = " + idAlarma + ")";
       
        $sql = "SELECT 
            v.*, '' as Comentario 
        FROM ($sqlAlarmas) v
        WHERE 
            v.IdObra = $idObra 
            AND exists(SELECT * FROM alarmatablero WHERE 
                IdObra = v.IdObra 
                AND (IdCertificacion = v.IdCertificacion OR (IdCertificacion is null AND v.IdCertificacion is null)) 
                AND IdAlarma = $idAlarma)";
        
        if ($sqlOnly) return $sql;
        return Yii::$app->db->createCommand($sql)->queryAll();
    }

}
