<?php
namespace backend\modules\mealPlans\controllers;

use backend\controllers\OneModelBaseController;
use backend\modules\mealPlans\models\MealPlans;
use backend\modules\mealPlans\models\SearchModel;

class DefaultController extends OneModelBaseController
{
    public function init()
    {
        $this->ModelName = MealPlans::className();
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
