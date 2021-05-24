<?php
namespace backend\modules\workoutsPlans\controllers;

use backend\controllers\OneModelBaseController;
use backend\modules\workoutsPlans\models\SearchModel;
use backend\modules\workoutsPlans\models\WorkoutsPlans;

class DefaultController extends OneModelBaseController
{
    public function init()
    {
        $this->ModelName = WorkoutsPlans::className();
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
