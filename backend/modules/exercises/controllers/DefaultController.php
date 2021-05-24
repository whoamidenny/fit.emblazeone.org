<?php
namespace backend\modules\exercises\controllers;

use backend\controllers\OneModelBaseController;
use backend\modules\exercises\models\Exercises;
use backend\modules\exercises\models\SearchModel;

class DefaultController extends OneModelBaseController
{
    public function init()
    {
        $this->ModelName = Exercises::className();
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
