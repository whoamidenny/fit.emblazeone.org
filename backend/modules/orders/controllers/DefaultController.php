<?php
namespace backend\modules\orders\controllers;

use backend\controllers\OneModelBaseController;
use backend\modules\discount\models\Discount;
use backend\modules\orders\models\Orders;
use backend\modules\orders\models\OrdersItems;
use backend\modules\orders\models\SearchModel;
use yii;

class DefaultController extends OneModelBaseController
{

    public function init()
    {
        $this->ModelName = Orders::className();
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
                $items=Yii::$app->request->post('OrdersItems');
                $total=0;
                foreach ($items['id'] as $num=>$id){
                    $orderItem=OrdersItems::findOne($id);
                    $orderItem->product_id=$items['product_id'][$num];
                    $orderItem->size=$items['size'][$num];
                    $total+=$orderItem->productPrice;
                    $orderItem->save();
                }
                $discount=Discount::findOne(['code'=>$model->discount_code]);
                $dp=0;
                if($discount){
                    $dp=$discount->calculate($total);
                }
                $model->amount=$total-$dp;
                $model->discount=$dp;
                $model->save();

//                $this->addToLog(UserLog::SAVED, $formModel->id);
                Yii::$app->getSession()->setFlash('success', $this->successEditMessage);
            } else {
                Yii::$app->getSession()->setFlash('error', $this->errorMessage);
            }
        }

        $form = $this->renderPartial($this->formView, ['model' => $model]);

        return $this->render($this->editView, ['model' => $model, 'form' => $form]);

    }

}
