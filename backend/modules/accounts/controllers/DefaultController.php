<?php
namespace backend\modules\accounts\controllers;

use backend\controllers\OneModelBaseController;
use backend\modules\accounts\models\SearchModel;
use common\models\Identity;
use yii;

class DefaultController extends OneModelBaseController
{

    public function userActions(){
        return yii\helpers\ArrayHelper::merge(parent::userActions(),['profile']);
    }

    public function init()
    {
        $this->ModelName = Identity::className();
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
