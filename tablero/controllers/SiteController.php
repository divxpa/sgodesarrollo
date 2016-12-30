<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
#use yii\web\Controller;
use app\components\PGController;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Usuario;
use app\models\Usuariologin;

use app\classes\F;
use app\classes\PGMenu as Menu;


class SiteController extends PGController
{
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {   
        return $this->redirect(['tablero/obras']);
        return $this->render('index');
    }


    public function actionLogin()
    {
        
        if ($this->session->get('usr_tablero')) {
            return $this->goHome();
        }
        else die("sin permisos");

        exit;

        //IMPLEMENTACION ORIGINAL
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        //>>!! CAMBIAR LAS VARIABLES DE SESION CON IDENTIFICADOR UNICO PARA EVITAR SUPERPOSICION DE SESIONES
        if ($model->load(Yii::$app->request->post()) && $model->login()) {

            // idUsuario
            $idUsuario = Yii::$app->user->getId();

            // Guarda registro del logueo en la Base
            $usuarioLogin = new Usuariologin();
            $usuarioLogin->IdUsuario = $idUsuario;
            $usuarioLogin->Ip = Yii::$app->request->getUserIp();
            $usuarioLogin->SessionId = $this->session->getId();
            $usuarioLogin->FechaHoraLogin = date('Y-m-d H:i:s');
            $usuarioLogin->FechaHoraLogout = null;
            $usuarioLogin->save();

            // Carga los datos de Usuario en la Sesion
            $usuario = Usuario::findOne($idUsuario);
            $session = $this->session;
            $session->set('IdRol', $usuario->IdRol);
            $session->set('NombreApellido', $usuario->ApellidoNombre);
            $session->set('Menu', Menu::make($usuario->IdRol));

            
            $this->InitializeAcciones();

            return $this->goBack();
        } 
        else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        header("location: $this->GlobalPath?logout=true"); exit;



        $logins = Usuariologin::find()->where(['IdUsuario' => Yii::$app->user->getId(), 'FechaHoraLogout' => null])->orderBy(['IdUsuarioLogin' => SORT_DESC])->all();

        foreach ($logins as $login) {
            $login->FechaHoraLogout = date('Y-m-d H:i:s');
            $login->save();
        }

        $session = $this->session;
        $session->remove('IdRol');
        $session->remove('NombreApellido');
        $session->remove('Menu');
        $session->remove('Acciones');
        Yii::$app->user->logout();        
        return $this->goHome();
    }

    public function actionError()
    {
        $exception = Yii::$app->errorHandler->exception;

        if ($exception !== null) {
            $this->LogError($exception);

            if (Yii::$app->request->isAjax){
                echo $exception->getMessage();
            }
            else{
                return $this->render('error', ['exception' => $exception, 'name' => $exception->getName(), 'message' => $exception->getMessage()]);
            }                
            
        }

    }


    public function actionRoot()
    {
        $this->redirect("$this->GlobalPath");
    }

}
