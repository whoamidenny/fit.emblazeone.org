<?php
namespace frontend\controllers;

use backend\modules\exercises\models\Exercises;
use backend\modules\pages\models\Pages;
use common\models\Identity;
use frontend\actions\GetProfileImageAction;
use wbp\images\models\Image;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

/**
 * Site controller
 */
class SiteController extends Controller
{


    public function behaviors()
    {
        $rules=[];
        $rules[]=[
            'actions' => ['index', 'rules', 'check-in','uploadImage','deleteImage','getImage'],
            'allow' => true,
            'roles' => ['@'],
        ];

        $rules[]=[
            'actions' => ['error'],
            'allow' => true,
            'roles' => ['*'],
        ];


        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => $rules,
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actionError(){
        if(Yii::$app->user->isGuest) return $this->redirect(['auth/login']);
        $exception = Yii::$app->errorHandler->exception;
        return $this->render('error',[
            'name'=>$exception->statusCode,
            'message'=>$exception->getMessage(),
        ]);
    }

    public function actionCheckIn(){
        return $this->render('check-in');
    }
    public function actionRules(){
        $page=Pages::findOne(['href'=>'rules']);
        return $this->render('generic-page',['page'=>$page]);
    }
    public function actionIndex(){
        $exercises=Exercises::find()->where(['status'=>Exercises::STATUS_ACTIVE])->limit(3)->all();

        return $this->render('index', ['exercises'=>$exercises]);
    }

    public function actions()
    {
        return [
//            'error' => [
//                'class' => 'yii\web\ErrorAction',
//            ],
            'getImage' => [
                'class' => GetProfileImageAction::className(),
            ],
            'deleteImage' => [
                'class' => \wbp\uploadifive\DeleteAction::className(),
            ],
            'uploadImage' => [
                'class' => \wbp\uploadifive\UploadAction::className(),
                'uploadBasePath' => '@serverDocumentRoot/images/tmp', //file system path
//                'uploadBaseUrl' => \common\helpers\Url::getWebUrlFrontend('upload'), //web path
                'csrf' => false,
                'format' => '{yyyy}-{mm}-{dd}-{time}-{rand:6}', //save format
                'validateOptions' => [
                    'extensions' => ['jpg', 'png', 'svg'],
//                    'maxSize' => 10 * 1024 * 1024, //file size
                ],
                'afterValidate' => function ($actionObject) {
                },
                'beforeSave' => function ($actionObject) {
                },
                'afterSave' => function ($filename, $fullFilename, $actionObject) {
                    $dir = Yii::getAlias(Yii::$app->getModule('im')->imagesStorePath);


//                    $itemId=Yii::$app->getRequest()->post('item_id');
                    $type = Yii::$app->getRequest()->post('type');
                    $unique_id = Yii::$app->getRequest()->post('uniqueId');
                    $ext = pathinfo($fullFilename, PATHINFO_EXTENSION);


                    $image = new Image();
                    $image->type = $type;
                    $image->unique_id = $unique_id;
                    $image->item_id = Yii::$app->user->identity->id;
                    $image->ext = $ext;
                    $image->status = Image::STATUS_ACTIVE;
                    $image->deleted = Image::NON_DELETED;
                    $image->name = $actionObject->getUpladedFileName();
//                    file_put_contents(Yii::getAlias('@serverDocumentRoot/images/') . '/1', $dir . '/' . $image->id . '.' . $image->ext);

                    $image->save();

                    rename($fullFilename, $dir . '/' . $image->id . '.' . $image->ext);

                },
            ]
        ];
    }


}
