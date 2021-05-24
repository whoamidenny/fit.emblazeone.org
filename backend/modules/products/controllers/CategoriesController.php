<?php
namespace backend\modules\products\controllers;

use backend\controllers\OneModelBaseController;
use backend\modules\products\models\SearchModel;
use backend\modules\products\models\ProductsCategories;
use yii;

class CategoriesController extends OneModelBaseController
{

    public function init()
    {
        $this->ModelName = ProductsCategories::className();
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
