<?php
namespace backend\modules\testimonials\controllers;

use backend\controllers\OneModelBaseController;
use backend\modules\testimonials\models\SearchModel;
use backend\modules\testimonials\models\Testimonials;
use yii;

class DefaultController extends OneModelBaseController
{
    public function init()
    {
        $this->ModelName = Testimonials::className();
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
