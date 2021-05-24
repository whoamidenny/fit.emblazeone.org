<?php
namespace backend\modules\telegram\controllers;

use backend\controllers\OneModelBaseController;
use backend\modules\accounts\models\SearchModel;
use backend\modules\telegram\models\TelegramBot;
use common\models\Identity;
use yii;

class DefaultController extends OneModelBaseController
{

    public function userActions(){
        return yii\helpers\ArrayHelper::merge(parent::userActions(),['profile']);
    }

    public function init()
    {
        $this->ModelName = TelegramBot::className();
        return parent::init();
    }

    public function actionIndex()
    {
        $modelName = $this->ModelName;
        $searchModel = new SearchModel();
        $params = \Yii::$app->request->get();
        $dataProvider = $searchModel->search($modelName, $params);

        return $this->render('index', ['dataProvider' => $dataProvider, 'searchModel' => $searchModel]);
    }

}
