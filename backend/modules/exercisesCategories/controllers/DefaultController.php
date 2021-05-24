<?php
namespace backend\modules\exercisesCategories\controllers;

use backend\controllers\OneModelBaseController;
use backend\modules\exercisesCategories\models\ExercisesCategories;
use backend\modules\exercisesCategories\models\SearchModel;

class DefaultController extends OneModelBaseController
{
    public function init()
    {
        $this->ModelName = ExercisesCategories::className();
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
