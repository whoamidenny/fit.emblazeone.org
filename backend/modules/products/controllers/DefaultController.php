<?php
namespace backend\modules\products\controllers;

use backend\controllers\OneModelBaseController;
use backend\modules\products\models\SearchModel;
use backend\modules\products\models\Products;
use yii;

class DefaultController extends OneModelBaseController
{
    public function init()
    {
        $this->ModelName = Products::className();
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
