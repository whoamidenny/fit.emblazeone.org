<?php

namespace backend\controllers;

use common\models\Config;
use common\models\Identity;
use Exception;
use wbp\file\File;
use wbp\images\models\Image;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\UploadedFile;

class BaseController extends Controller
{
    public function beforeAction($action)
    {
        if(Yii::$app->user->isGuest){
            $this->layout='login';
        }

        $this->view->title=Config::getParameter('title');

        return parent::beforeAction($action);
    }

    public function behaviors()
    {
        $rules=[];
        if(count($this->userActions())){
            $rules[]=[
                'actions' => $this->userActions(),
                'allow' => true,
                'roles' => ['@'],
            ];
        }
        if(count($this->adminActions())){
            $rules[]=[
                'actions' => $this->adminActions(),
                'allow' => true,
                'roles' => [Identity::ROLE_ADMIN, Identity::ROLE_GOD],
            ];
        }
        if(count($this->allowedActions())){
            $rules[]=[
                'actions' => $this->allowedActions(),
                'allow' => true,
                'roles' => ['*'],
            ];
        }
        if(count($this->unloggedActions())){
            $rules[]=[
                'actions' => $this->unloggedActions(),
                'allow' => true,
                'roles' => ['?'],
            ];
        }
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

    public function userActions(){
        return [];
    }

    public function adminActions(){
        return [];
    }

    public function allowedActions(){
        return [];
    }

    public function unloggedActions(){
        return [];
    }

    public function actionSortImage()
    {
        $modelName = Image::className();
        $elements = Yii::$app->request->post('elements');
        $modelName::sort($elements);
//        $this->addToLog(UserLog::SORTED, $elements);
    }

    public function actions()
    {
        return [
//            'error' => [
//                'class' => 'yii\web\ErrorAction',
//            ],
            'getImage' => [
                'class' => \wbp\uploadifive\GetAction::className(),
            ],
//            'getVideo' => [
//                'class' => \wbp\uploadifive\GetVideoAction::className(),
//            ],
            'getFile' => [
                'class' => \wbp\uploadifive\GetFileAction::className(),
            ],
            'deleteImage' => [
                'class' => \wbp\uploadifive\DeleteAction::className(),
            ],
//            'deleteVideo' => [
//                'class' => \wbp\uploadifive\DeleteVideoAction::className(),
//            ],
            'deleteFile' => [
                'class' => \wbp\uploadifive\DeleteFileAction::className(),
            ],
            'uploadImage' => [
                'class' => \wbp\uploadifive\UploadAction::className(),
                'uploadBasePath' => '@serverDocumentRoot/images/tmp', //file system path
//                'uploadBaseUrl' => \common\helpers\Url::getWebUrlFrontend('upload'), //web path
                'csrf' => false,
                'format' => '{yyyy}-{mm}-{dd}-{time}-{rand:6}', //save format
                'validateOptions' => [
//                    'extensions' => ['jpg', 'png', 'svg'],
//                    'maxSize' => 10 * 1024 * 1024, //file size
                ],
                'beforeValidate' => function ($actionObject) {
                    $modelName = $this->ModelName;
                    if(!$modelName) return;
                    if(isset($modelName::$imageSizesRequired)){
                        foreach ($modelName::$imageSizesRequired as $k => $v) {
                            if($k == Yii::$app->request->post('type')){

                                $obj = UploadedFile::getInstanceByName('Filedata');
                                if(!$obj) {throw new Exception("UploadedFile doesn't exist");}
                                $imageInfo = getimagesize($obj->tempName);
                                if(!$imageInfo) {throw new Exception("GD2 extension exist");}
                                if(count($imageInfo) < 2){throw new Exception("Not enough values");}
                                //$imageInfo[0]- width, $imageInfo[1]-height
                                if($imageInfo[0] < $v[0] || $imageInfo[1] < $v[1]){
                                    $error = 'The image you are trying to upload has invalid dimensions ('.$imageInfo[0].'x'.$imageInfo[1].'). Please change your image to match the required upload dimensions ('.$v[0].'x' . $v[1].') and try again.';
                                    throw new HttpException(400, $error);
                                }
                            }
                        }
                    }

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
            ],
//            'uploadVideo' => [
//                'class' => \wbp\uploadifive\UploadAction::className(),
//                'uploadBasePath' => '@serverDocumentRoot/video/tmp', //file system path
//                'csrf' => false,
//                'format' => '{yyyy}-{mm}-{dd}-{time}-{rand:6}', //save format
//                'validateOptions' => [
//                    'extensions' => ['mp4', 'flv', 'ogg'],
//                    'maxSize' => 600 * 1024 * 1024, //file size
//                ],
//                'beforeValidate' => function ($actionObject) {
//                },
//                'afterValidate' => function ($actionObject) {
//                },
//                'beforeSave' => function ($actionObject) {
//                },
//                'afterSave' => function ($filename, $fullFilename, $actionObject) {
//
//                    $dir = Yii::getAlias(Yii::$app->getModule('video')->videoStorePath);
//                    $type = Yii::$app->getRequest()->post('type');
//                    $unique_id = Yii::$app->getRequest()->post('uniqueId');
//                    $ext = pathinfo($fullFilename, PATHINFO_EXTENSION);
//
//
//                    $video = new Video();
//                    $video->type = $type;
//                    $video->unique_id = $unique_id;
//                    $video->ext = $ext;
//                    $video->status = Image::STATUS_ACTIVE;
//                    $video->deleted = Image::NON_DELETED;
//                    $video->name = $actionObject->getUpladedFileName();
////                    file_put_contents(Yii::getAlias('@serverDocumentRoot/video/').'/1',$dir.'/'.$image->id.'.'.$image->ext);
//
//                    $video->save();
//
//                    rename($fullFilename, $dir . '/' . $video->id . '.' . $video->ext);
//
//                },
//            ],
            'uploadFile' => [
                'class' => \wbp\uploadifive\UploadAction::className(),
                'uploadBasePath' => '@serverDocumentRoot/files/tmp', //file system path
                'csrf' => false,
                'format' => '{yyyy}-{mm}-{dd}-{time}-{rand:6}', //save format
                'validateOptions' => [
//                    'extensions' => ['*'],
                    'maxSize' => 600 * 1024 * 1024, //file size
                ],
                'beforeValidate' => function ($actionObject) {
                },
                'afterValidate' => function ($actionObject) {
                },
                'beforeSave' => function ($actionObject) {
                },
                'afterSave' => function ($filename, $fullFilename, $actionObject) {

                    $dir = Yii::getAlias(Yii::$app->getModule('file')->fileStorePath);
                    $type = Yii::$app->getRequest()->post('type');
                    $save = Yii::$app->getRequest()->post('save');
                    $item_id = Yii::$app->getRequest()->post('item_id');
                    $unique_id = Yii::$app->getRequest()->post('uniqueId');
                    $ext = pathinfo($fullFilename, PATHINFO_EXTENSION);

                    $file = new File();
                    $file->type = $type;
                    if($save){
                        $file->item_id = $item_id;
                    }
                    $file->unique_id = $unique_id;
                    $file->ext = $ext;
                    $file->status = File::STATUS_ACTIVE;
                    $file->deleted = File::NON_DELETED;
                    $file->name = $actionObject->getUpladedFileName();
//                    file_put_contents(Yii::getAlias('@serverDocumentRoot/files/').'/1',$dir.'/'.$file->id.'.'.$file->ext);

                    $file->save();

                    rename($fullFilename, $dir . '/' . $file->id . '.' . $file->ext);

                },
            ],
        ];
    }

}