<?php
namespace backend\modules\discount\controllers;

use backend\controllers\OneModelBaseController;
use backend\modules\discount\models\Discount;
use backend\modules\discount\models\SearchModel;
use yii;

class DefaultController extends OneModelBaseController
{
    public function init()
    {
        $this->ModelName = Discount::className();
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
