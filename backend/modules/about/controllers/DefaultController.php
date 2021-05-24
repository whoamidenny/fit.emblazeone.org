<?php
namespace backend\modules\about\controllers;

use backend\controllers\OneModelBaseController;
use backend\modules\about\models\SearchModel;
use backend\modules\about\models\About;
use yii;

class DefaultController extends OneModelBaseController
{
    public function init()
    {
        $this->ModelName = About::className();
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
