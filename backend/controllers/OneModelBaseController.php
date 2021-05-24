<?php
namespace backend\controllers;

use backend\models\UserLog;
use frontend\models\Category;
use wbp\images\models\Image;
use Yii;

/**
 * Site controller
 */
class OneModelBaseController extends BaseController
{
    const ADD_SCENARIO_NAME = 'admin-add';
    const UPDATE_SCENARIO_NAME = 'admin-update';

    const BEFORE_ADD = 'beforeAdd';
    const BEFORE_EDIT = 'beforeAdd';
    const BEFORE_ADD_EDIT = 'beforeAddEdit';

    public $FormModel = '';
    public $ModelName = '';
    public $editView = 'edit';
    public $addView = 'add';
    public $formView = 'edit_add_form';
    public $successAddMessage = 'Added to database';
    public $successEditMessage = 'Information saved';
    public $errorMessage = 'Error';

    public $searchModel;

    public function init()
    {
        $this->on(self::BEFORE_ADD, [$this, 'beforeAdd']);
        $this->on(self::BEFORE_EDIT, [$this, 'beforeEdit']);
        $this->on(self::BEFORE_ADD_EDIT, [$this, 'beforeAddEdit']);

        return parent::init();
    }

    public function beforeAdd(){
    }

    public function beforeEdit(){
    }

    public function beforeAddEdit(){
    }

    public function sortEnable()
    {
        $modelName = $this->ModelName;
        $modelName = $modelName::className();
        $columns = $modelName::getTableSchema()->columns;
        if (isset($columns['sort'])) return true;
        return false;
    }

    public function actionAdd()
    {
        $this->trigger(self::BEFORE_ADD);
        $this->trigger(self::BEFORE_ADD_EDIT);

        $modelName = $this->ModelName;

        $formModel = new $modelName(['scenario' => 'add']);

        //$model=new $modelName;
        if ($formModel->load(Yii::$app->request->post())) {
            $saved = $formModel->save();
            if ($saved) {
//                $this->addToLog(UserLog::ADDED, $formModel->id);
                Yii::$app->getSession()->setFlash('success', Yii::t('index',$this->successAddMessage));
                return $this->redirect(['edit', 'id' => $formModel->id]);
            } else {
                Yii::$app->getSession()->setFlash('error',  Yii::t('index',$this->errorMessage));
            }
        }
        $form = $this->renderPartial('edit_add_form', ['model' => $formModel]);

        return $this->render($this->addView, ['form' => $form, 'model' => $formModel]);
    }

    public function actionEdit($id, $scenario='edit')
    {
        $this->trigger(self::BEFORE_EDIT);
        $this->trigger(self::BEFORE_ADD_EDIT);

        $modelName = $this->ModelName;

        $model = $modelName::findOne(['id' => (int)$id]);
        $model->scenario = $scenario;

        if ($model->load(Yii::$app->request->post())) {
            $saved = $model->save();
            if ($saved) {
//                $this->addToLog(UserLog::SAVED, $formModel->id);
                Yii::$app->getSession()->setFlash('success',  Yii::t('index',$this->successEditMessage));
            } else {
                Yii::$app->getSession()->setFlash('error',  Yii::t('index',$this->errorMessage));
            }
        }

        $form = $this->renderPartial($this->formView, ['model' => $model]);

        return $this->render($this->editView, ['model' => $model, 'form' => $form]);

    }

    public function actionSort()
    {
        $modelName = $this->ModelName;
        $elements = Yii::$app->request->post('elements');
        $modelName::sort($elements);
//        $this->addToLog(UserLog::SORTED, $elements);
    }

    public function actionDelete($id){
        return $this->actionRemove($id);
    }

    public function actionRemove($id)
    {
        $modelName = $this->ModelName;
        $model = $modelName::findOne(['id' => (int)$id]);
        if ($model) {
//            $this->addToLog(UserLog::REMOVED, $model->id);
            $model->delete();
        }

        if (!Yii::$app->request->isPost) $this->redirect('index');
    }

    public function userActions(){
        return ['index','add','edit','delete','sort','remove','uploadImage','getImage','deleteImage','uploadFile','getFile','deleteFile'];
    }

    public function clearTmpImages(){
        // Clear Tmp Images

        $tmpImages=Image::find()->where('item_id=:item_id AND added_date < :added_date ', ['item_id'=>'0', 'added_date'=>time()-172800]);
        foreach($tmpImages->each() as $img){
            $img->delete();
        }
    }
}
