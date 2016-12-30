<?php

namespace app\models;

use Yii;
use app\components\PGActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "usuario".
 *
 * @property string $IdUsuario
 * @property string $Apellido
 * @property string $Nombre
 * @property string $IdRol
 * @property string $Username
 * @property string $Password
 * @property string $Activo
 *
 * @property Auditoria[] $auditorias
 * @property Rol $idRol
 * @property Usuariologin[] $usuariologins
 */
class Usuario extends PGActiveRecord implements IdentityInterface
{
    public $authKey;
    public $accessToken;
    public $currentPassword;
    public $newPassword;
    public $newPasswordConfirm;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'usuario';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Apellido', 'Nombre', 'IdRol', 'Username', 'Password', 'Activo'], 'required'],
            [['IdRol', 'Activo'], 'integer'],
            [['Apellido', 'Nombre', 'Password'], 'string', 'max' => 50],
            [['Username'], 'string', 'max' => 20],
            [['Username'], 'unique','message' => 'Ya existe otro registro con ese nombre de usuario.'],
            [['newPassword', 'currentPassword', 'newPasswordConfirm'], 'required','on'=>'changepassword'],
            [['currentPassword'], 'compareCurrentPassword'],
            [['newPassword', 'newPasswordConfirm'], 'string', 'min' => 6],
            [['newPassword', 'newPasswordConfirm'], 'filter', 'filter' => 'trim'],
            [['newPasswordConfirm'], 'compare', 'compareAttribute' => 'newPassword', 'message' => 'La confirmación de contraseña no coinciden']

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'IdUsuario' => 'Id Usuario',
            'Apellido' => 'Apellido',
            'Nombre' => 'Nombre',
            'IdRol' => 'Rol',
            'Username' => 'Nombre de Usuario',
            'Password' => 'Contraseña',
            'Activo' => 'Activo',
                'ActivoDesc' => 'Activo',
             'LastConnection' => 'Últ. acceso',
            'currentPassword' => 'Contraseña actual',
            'newPassword' => ' Nueva contraseña',
            'newPasswordConfirm' => 'Nueva contraseña (Repetir)'            
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuditorias()
    {
        return $this->hasMany(Auditoria::className(), ['IdUsuario' => 'IdUsuario']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdRol()
    {
        return $this->hasOne(Rol::className(), ['IdRol' => 'IdRol']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuariologins()
    {
        return $this->hasMany(Usuariologin::className(), ['IdUsuario' => 'IdUsuario']);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * Finds user by username
     *
     * @param  string      $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['Username' => $username]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->IdUsuario;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->Password === md5($password) and $this->Activo==1;
    }

    public function getusername(){
        return $this->Username;
    }
    public function getRol() {
        return $this->idRol->Nombre;
    }

    public function getActivoDesc() {

        if ($this->Activo == 1) {
            return "Si";
        } else {
            return "No";
        }
    }
    public function getLastConnection(){
        $login = Usuariologin::find()->where(['IdUsuario' => $this->IdUsuario])->orderBy('IdUsuarioLogin DESC')->limit(1)->all();

        if(count($login)){
            return $login[0]->FechaHoraLogin;
        }
        else{
            return null;
        }

    }
        public function compareCurrentPassword($attribute, $params) {
        if (md5($this->currentPassword) !== static::findOne(['Username' => Yii::$app->user->identity->Username])->Password) {
            $this->addError($attribute, 'La contraseña actual es incorrecta');
        }
    }
}
