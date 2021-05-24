<?php
namespace backend\modules\faq\controllers;

use backend\controllers\OneModelBaseController;
use backend\modules\faq\models\FaqCategories;
use backend\modules\faq\models\SearchModel;
use common\models\Identity;
use yii;

class CategoriesController extends OneModelBaseController
{

    public function init()
    {
        $this->ModelName = FaqCategories::className();
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

    public function actionProfile(){
        $model=Yii::$app->user->identity;

        Yii::$app->controller->module->actions['enable_add']=false;
        Yii::$app->controller->module->actions['enable_delete']=false;

        return $this->actionEdit($model->id, 'editProfile');
    }


}
