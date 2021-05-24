<?php
namespace backend\modules\workoutsCategories\controllers;

use backend\controllers\OneModelBaseController;
use backend\modules\workoutsCategories\models\WorkoutsCategories;
use backend\modules\workoutsCategories\models\SearchModel;

class DefaultController extends OneModelBaseController
{
    public function init()
    {
        $this->ModelName = WorkoutsCategories::className();
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
