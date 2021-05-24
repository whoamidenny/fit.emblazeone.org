<?php
namespace backend\modules\bnrs\controllers;

use backend\controllers\OneModelBaseController;
use backend\modules\bnrs\models\SearchModel;
use backend\modules\bnrs\models\Banners;
use yii;

class DefaultController extends OneModelBaseController
{
    public function init()
    {
        $this->ModelName = Banners::className();
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
