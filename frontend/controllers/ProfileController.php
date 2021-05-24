<?php
namespace frontend\controllers;

use backend\modules\clients\models\ClientParams;
use backend\modules\clients\models\ClientPhotoTracker;
use backend\modules\clients\models\ClientWeightTracker;
use common\models\Identity;
use wbp\file\File;
use wbp\images\models\Image;
use Yii;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\UploadedFile;

/**
 * Site controller
 */
class ProfileController extends Controller
{

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'getImage' => [
                'class' => \wbp\uploadifive\GetAction::className(),
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
                'beforeValidate' => function ($actionObject) {
//                    $modelName = $this->ModelName;
//                    if(!$modelName) return;
//                    if(isset($modelName::$imageSizesRequired)){
//                        foreach ($modelName::$imageSizesRequired as $k => $v) {
//                            if($k == Yii::$app->request->post('type')){
//
//                                $obj = UploadedFile::getInstanceByName('Filedata');
//                                if(!$obj) {throw new Exception("UploadedFile doesn't exist");}
//                                $imageInfo = getimagesize($obj->tempName);
//                                if(!$imageInfo) {throw new Exception("GD2 extension exist");}
//                                if(count($imageInfo) < 2){throw new Exception("Not enough values");}
//                                //$imageInfo[0]- width, $imageInfo[1]-height
//                                if($imageInfo[0] < $v[0] || $imageInfo[1] < $v[1]){
//                                    $error = 'The image you are trying to upload has invalid dimensions ('.$imageInfo[0].'x'.$imageInfo[1].'). Please change your image to match the required upload dimensions ('.$v[0].'x' . $v[1].') and try again.';
//                                    throw new HttpException(400, $error);
//                                }
//                            }
//                        }
//                    }

                },
                'afterValidate' => function ($actionObject) {
                },
                'beforeSave' => function ($actionObject) {
                },
                'afterSave' => function ($filename, $fullFilename, $actionObject) {

                    //$filename; // image/yyyymmddtimerand.jpg
                    //$fullFilename; // /var/www/htdocs/image/yyyymmddtimerand.jpg
                    //$actionObject; // \wbp\uploadifive\UploadAction instance

                    $dir = Yii::getAlias(Yii::$app->getModule('im')->imagesStorePath);


//                    $itemId=Yii::$app->getRequest()->post('item_id');
                    $type = Yii::$app->getRequest()->post('type');
                    $unique_id = Yii::$app->getRequest()->post('uniqueId');
                    $ext = pathinfo($fullFilename, PATHINFO_EXTENSION);


                    $image = new Image();
                    $image->type = $type;
                    $image->unique_id = $unique_id;
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


    public function behaviors()
    {
        $rules=[];
        $rules[]=[
            'actions' => ['index','photo-tracker','uploadImage','deleteImage','getImage','weight-tracker','download-weight-tracker'],
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

    public function actionIndex(){
        $model=Yii::$app->user->identity;
        $model->scenario='edit';

        if($model->load(Yii::$app->request->post()) && $model->validate()){
            $model->save();
            Yii::$app->session->setFlash('success','Your data saved.');
        }

        return $this->render('index',['model'=>$model]);
    }

    public function actionDownloadWeightTracker(){
        $csv=[];
        $csv[]=implode(",", ['Date','Week','Weight']);
        foreach(Yii::$app->user->identity->weightTrackers as $track){
            $csv[]=implode(",", [date("m/d/Y", strtotime($track->created_at)),$track->week,$track->weight]);
        }
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename=export.csv');
        header('Pragma: no-cache');
        echo implode("\n", $csv);
        exit();
    }

    public function actionWeightTracker(){
        $model=new ClientWeightTracker();
        if($model->load(Yii::$app->request->post()) && $model->validate()){
            $model->client_id=Yii::$app->user->identity->id;
            $model->save();
            Yii::$app->session->setFlash('success', 'Your progress uploaded');
            return $this->redirect(['weight-tracker']);
        }
        return $this->render('weight-tracker',['model'=>$model]);
    }
    public function actionPhotoTracker(){
        $model=new ClientPhotoTracker();

        if($model->load(Yii::$app->request->post()) && $model->validate()){
            $model->client_id=Yii::$app->user->identity->id;
            $model->save();
            Yii::$app->session->setFlash('success', 'Your progress uploaded');
            return $this->redirect(['photo-tracker']);
        }

        return $this->render('photo-tracker',['model'=>$model]);
    }

}
