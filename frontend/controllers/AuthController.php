<?php

namespace frontend\controllers;

use backend\modules\clients\models\Client;
use backend\modules\clients\models\ClientAuth;
use frontend\models\LoginForm;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

class AuthController extends Controller
{
    public $ModelName='backend\modules\clients\models\Client';

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

        /* @var $auth ClientAuth */
        $auth = ClientAuth::find()->where([
            'source' => $client->getId(),
            'source_id' => $attributes['id'],
        ])->one();

        if (Yii::$app->user->isGuest) {
            if ($auth) { // авторизация
                $user = $auth->user;
                Yii::$app->user->login($user, 31536000);
            } else {
//                Yii::$app->session->setFlash('error','Пользователь не привязан к данному аккаунту, вам необходимо зайти в личный кабинет с помощью логина и пароля, в настройках профиля привязать аккаунт к системе авторизации');
                $client=Client::find()->where(['email' => $attributes['email']])->one();
                if (isset($attributes['email']) && !$client) {
                    Yii::$app->getSession()->setFlash('error', [
                        "User with current email is not found.",
                    ]);
                }else{
                    Yii::$app->user->login($client, 31536000);
                }
//                else {
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
                $auth = new ClientAuth([
                    'client_id' => Yii::$app->user->identity->id,
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

}