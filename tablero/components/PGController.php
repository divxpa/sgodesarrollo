<?php
namespace app\components;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use PDO;
use app\models\Usuario;

use app\classes\PGMenu as Menu;
use app\classes\F;

use app\classes\SessionSGO;

class PGController extends Controller
{
    public $session;
    public $GlobalPath;

    public function __construct($id, $module, $config = []) 
    {
        
        $this->session = new SessionSGO();
        $session = $this->session;
        $srv = $_SERVER["SERVER_NAME"];
        $this->GlobalPath = "http://" . ($srv == "localhost" ? $srv . preg_replace('/^\/([\w\d]+)(\/[\w\W]*)/', "/$1", $_SERVER["REQUEST_URI"]) : $srv);

//F::p($_SESSION);
        if ($session->get('usr_tablero')) {
            $idRol = $session->get('usr_sgo_idRol');
            if (!$session->get('Menu')) $session->set('Menu', Menu::make($idRol)); 
        } else {
            //die( __DIR__);
            header("Location: $this->GlobalPath");
            exit;
        }

        parent::__construct($id, $module, $config);
        
        //F::p($_SESSION);
    }
    

    public function actions()
    {
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }


    public function InitializeAcciones()
    {
        $actionsDeny = [];

        if (!Yii::$app->user->isGuest)
        {
            $idUsuario = Yii::$app->user->getId();
            $sql = "SELECT * from acciontablero where Activo = 1 and not exists(select * from usuario u inner join rol r on u.IdRol = r.IdRol inner join rolacciontablero ra on r.IdRol = ra.IdRol where ra.IdAccion=acciontablero.IdAccion and u.IdUsuario=:IdUsuario)";
            $command =  Yii::$app->db->createCommand($sql);
            $command->bindParam(':IdUsuario', $idUsuario);
        }
        else
        {
            $sql = "SELECT * from acciontablero where Activo = 1";
            $command =  Yii::$app->db->createCommand($sql);
        }        
        
        $data = $command->queryAll();

        foreach ($data as $d) {
            $actionsDeny[$d['Url']] = 1;
        }

        $this->session->set('Acciones', $actionsDeny);
    }

    public function getMenu(){
        $menu = $this->session->get('Menu');
        return $menu ? $menu : [];
    } //>> verificación que creo es innecesaria, comprobar...

    public function getAcciones(){
        $acciones = $this->session->get('Acciones');
        return $acciones ? $acciones : [];
    }

    public function LogError($exception){
        $username = 'Guest';

        if(!Yii::$app->user->isGuest){
            $idUsuario = Yii::$app->user->getId();
            $usuario = Usuario::findOne($idUsuario);
            $username = $usuario->username;
        }

        $fileName = "../log/error.log";
        $content = date('YmdHis ') . "\t" . $username . "\t" . serialize(['code' => $exception->getCode(), 'name' => $exception->getName(), 'file' => $exception->getFile(), 'line' => $exception->getLine(), 'message' => $exception->getMessage()]) . "\n";
        $this->Log($fileName, $content, 1024 * 1024);
    }

    public function Log($fileName, $content, $maxSize) {

        try{
            
            if (file_exists($fileName)) {

                if (filesize($fileName) > $maxSize) {
                    rename($fileName, $fileName . "_" . date('YmdHis'));
                }
            }

            file_put_contents($fileName, $content, FILE_APPEND | LOCK_EX);
        }
        catch(exception $e){

        }

    }

}

?>