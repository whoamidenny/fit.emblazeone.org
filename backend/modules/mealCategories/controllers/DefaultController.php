<?php
namespace backend\modules\mealCategories\controllers;

use backend\controllers\OneModelBaseController;
use backend\modules\mealCategories\models\MealCategories;
use backend\modules\mealCategories\models\SearchModel;

class DefaultController extends OneModelBaseController
{
    public function init()
    {
        $this->ModelName = MealCategories::className();
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
