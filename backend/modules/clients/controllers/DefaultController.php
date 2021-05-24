<?php
namespace backend\modules\clients\controllers;

use backend\controllers\OneModelBaseController;
use backend\modules\clients\models\Client;
use backend\modules\clients\models\SearchModel;
use yii;

class DefaultController extends OneModelBaseController
{
    public function init(){
        $this->ModelName = Client::className();
        return parent::init();
    }

    public function actionIndex(){
        $modelName = $this->ModelName;
        $searchModel = new SearchModel();
        $params = \Yii::$app->request->post();
        $dataProvider = $searchModel->search($modelName, $params);

        return $this->render('index', ['dataProvider' => $dataProvider, 'searchModel' => $searchModel]);
    }


}
