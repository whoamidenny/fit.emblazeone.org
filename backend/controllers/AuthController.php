<?php

namespace backend\controllers;

use common\models\Identity;
use backend\models\LoginForm;
use backend\models\RegisterForm;
use backend\models\RepassForm;
use backend\models\UserAuth;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

class AuthController extends BaseController
{
    public $ModelName='app\models\Identity';

    public function unloggedActions(){
        return ['login','register','repass','repass-sms'];
    }

    public function userActions(){
        return ['logout','login','return'];
    }

    public function adminActions(){
        return ['return', 'test-connection'];
    }

    public function allowedActions()
    {
        return ['auth','uploadImage','getImage','deleteImage'];
    }

    public function beforeAction($action)
    {
        if ($action->id == 'auth') {
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }

    public function actions()
    {
        return ArrayHelper::merge(parent::actions(), [
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
        ]);
    }

    public function onAuthSuccess($client)
    {
        $attributes = $client->getUserAttributes();

        /* @var $auth Auth */
        $auth = UserAuth::find()->where([
            'source' => $client->getId(),
            'source_id' => $attributes['id'],
        ])->one();

        if (Yii::$app->user->isGuest) {
            if ($auth) { // авторизация
                $user = $auth->user;
                Yii::$app->user->login($user, 31536000);
            } else { // регистрация
                Yii::$app->session->setFlash('error','Пользователь не привязан к данному аккаунту, вам необходимо зайти в личный кабинет с помощью логина и пароля, в настройках профиля привязать аккаунт к системе авторизации');
//                if (isset($attributes['email']) && User::find()->where(['email' => $attributes['email']])->exists()) {
//                    Yii::$app->getSession()->setFlash('error', [
//                        Yii::t('app', "Пользователь с такой электронной почтой как в {client} уже существует, но с ним не связан. Для начала войдите на сайт использую электронную почту, для того, что бы связать её.", ['client' => $client->getTitle()]),
//                    ]);
//                } else {
//                    $password = Yii::$app->security->generateRandomString(6);
//                    $user = new User([
//                        'username' => $attributes['login'],
//                        'email' => $attributes['email'],
//                        'password' => $password,
//                    ]);
//                    $user->generateAuthKey();
//                    $user->generatePasswordResetToken();
//                    $transaction = $user->getDb()->beginTransaction();
//                    if ($user->save()) {
//                        $auth = new Auth([
//                            'user_id' => $user->id,
//                            'source' => $client->getId(),
//                            'source_id' => (string)$attributes['id'],
//                        ]);
//                        if ($auth->save()) {
//                            $transaction->commit();
//                            Yii::$app->user->login($user);
//                        } else {
//                            print_r($auth->getErrors());
//                        }
//                    } else {
//                        print_r($user->getErrors());
//                    }
//                }
            }
        } else { // Пользователь уже зарегистрирован
            if (!$auth) { // добавляем внешний сервис аутентификации
                $auth = new UserAuth([
                    'user_id' => Yii::$app->user->identity->id,
                    'source' => $client->getId(),
                    'source_id' => $attributes['id'],
                ]);
                $auth->save();
            }

            if (isset($attributes['email'])) {
                if(!Yii::$app->user->identity->email){
                    Yii::$app->user->identity->email=$attributes['email'];
                    Yii::$app->user->identity->save();
                }
            }

        }
    }

    public function actionLogin(){
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['site/index']);
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(['site/index']);
        }

        $this->layout='login';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionRegister(){
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['site/index']);
        }

        $model = new RegisterForm();

        if(Yii::$app->request->isAjax) {
            if ($model->load(Yii::$app->request->post())) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        } else {
            if ($model->load(Yii::$app->request->post()) && $model->register()) {
                Yii::$app->session->setFlash('success',Yii::t('login','Your account is created, it will reviewed asap, when it\'s was done you will receive email confirmation'));
                return $this->redirect(['site/index']);
            }
        }

        $this->layout='login';

        return $this->render('register', [
            'model' => $model,
        ]);


    }

    public function actionLogout(){
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionReturn(){
        if(!$_SESSION['old-__id']) throw new NotFoundHttpException(Yii::t('main','Page not found'));

        $_SESSION['__id']=$_SESSION['old-__id'];
        $_SESSION['old-__id']='';

        $this->redirect(['site/index']);
    }

    public function actionTestConnection($userId){
        $user=Identity::findOne(['id'=>$userId]);
        if($user->role==Identity::ROLE_GOD){
            throw new NotFoundHttpException(Yii::t('main','You are not authorized to use this user\'s login'));
        }

        Yii::$app->session->set('old-__id',$_SESSION["__id"]);

        Yii::$app->user->login($user, 31536000);
        $this->redirect(['site/index']);
    }

    public function actionRepass()
    {
        $this->layout='login';

        if(Yii::$app->request->get('key')){
            RepassForm::resetPassword(Yii::$app->request->get('key'));
            return $this->redirect(['auth/login']);
        }
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }


        $model = new RepassForm();
        if ($model->load(Yii::$app->request->post())) {
            if($model->repass()){
                return $this->goBack();
            }
        }
        return $this->render('repass', [
            'model' => $model,
        ]);
    }

//    public function actionRepassSms()
//    {
//        $this->layout='login';
//
////        if(Yii::$app->request->get('key')){
////            RepassForm::resetPassword(Yii::$app->request->get('key'));
////            return $this->redirect(['auth/login']);
////        }
//        if (!Yii::$app->user->isGuest) {
//            return $this->goHome();
//        }
//
//        $token=Yii::$app->request->get('token', false);
//
//        $model = new RepassSmsForm();
//        if ($loaded=$model->load(Yii::$app->request->post())) {
//            if($model->findPhone() && !Yii::$app->request->isAjax){
//                if($model->getUser()){
//                    $model->getUser()->generateSmsPass();
//                }
//            }
//
//
//            if($model->findPhone() && $sms=$model->checkSmsReceived()){
//                $user=$model->getUser();
//                $validationKey=$user->getLoginToken();
//                foreach ($sms as $mess){
//                    $mess->is_read=1;
//                    $mess->save();
//                }
//                echo "<script>document.location.href='".Url::to(['auth/repass-sms','token'=>$validationKey])."';</script>";
//                Yii::$app->end();
//            }
//
//            if($model->repass()){
////                return $this->goBack();
//            }
//        }
//        if(!Yii::$app->request->isAjax)
//            return $this->render('repass-sms', [
//                'model' => $model,
//                'loaded'=>$loaded,
//                'token'=>$token
//            ]);
//        else return '';
//    }
}