<?php
namespace backend\modules\supplementPlans\controllers;

use backend\controllers\OneModelBaseController;
use backend\modules\supplementPlans\models\SearchModel;
use backend\modules\supplementPlans\models\SupplementPlans;

class DefaultController extends OneModelBaseController
{
    public function init()
    {
        $this->ModelName = SupplementPlans::className();
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
