<?php
namespace backend\modules\contact\controllers;

use backend\controllers\OneModelBaseController;
use backend\modules\contact\models\Contact;
use backend\modules\contact\models\SearchModel;
use yii;

class DefaultController extends OneModelBaseController
{

//    public function userActions(){
//        return yii\helpers\ArrayHelper::merge(parent::userActions(),['profile']);
//    }

    public function init()
    {
        $this->ModelName = Contact::className();
        return parent::init();
    }

    public function actionIndex()
    {
        $modelName = $this->ModelName;
        $searchModel = new SearchModel();
        $params = \Yii::$app->request->post();
        $dataProvider = $searchModel->search($modelName, $params);

        return $this->render('index', ['dataProvider' => $dataProvider, 'searchModel' => $searchModel]);
    }
}
