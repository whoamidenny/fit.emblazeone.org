<?php
namespace backend\modules\pages\controllers;

use backend\controllers\OneModelBaseController;
use backend\modules\accounts\models\SearchModel;
use backend\modules\faq\models\Faq;
use backend\modules\pages\models\Pages;
use common\models\Identity;
use yii;

class DefaultController extends OneModelBaseController
{
    public function init()
    {
        $this->ModelName = Pages::className();
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
