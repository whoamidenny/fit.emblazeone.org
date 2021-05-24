<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;

/**
 * Site controller
 */
class SiteController extends BaseController
{
    public function unloggedActions(){
        return ['login'];
    }
    public function userActions(){
        return ['index', 'logout'];
    }

    public function allowedActions(){
        return ['error'];
    }

    public function actionLogin(){
        return $this->redirect(['auth/login']);
    }

    public function actionLogout(){
        return $this->redirect(['auth/login']);
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $this->redirect(['/pages/default/index']);
//        return $this->render('index');
    }
}
