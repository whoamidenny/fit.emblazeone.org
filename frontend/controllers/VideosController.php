<?php
namespace frontend\controllers;

use backend\modules\exercises\models\Exercises;
use backend\modules\exercises\models\ExercisesTags;
use backend\modules\exercises\models\Tags;
use backend\modules\workouts\models\WorkoutsTags;
use common\models\Identity;
use frontend\models\ExercisesSearchModel;
use frontend\models\WorkoutsSearchModel;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

/**
 * Site controller
 */
class VideosController extends Controller
{


    public function behaviors()
    {
        $rules=[];
        $rules[]=[
            'actions' => ['workouts','exercises'],
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

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }

    public function actionExercises(){
        $tids=ExercisesTags::find()->select("tag_id")->distinct("tag_id")->all();
        $tmp=[];
        foreach ($tids as $id){
            $tmp[]=$id->tag_id;
        }
        $tags=Tags::find()->where(['id'=>$tmp])->all();
        $get=\Yii::$app->request->get();
        if(isset($get['tags'])) $requestedTags=$get['tags'];
        else $requestedTags=false;

        foreach ($tags as $tag) {
            $tag->setSelectedFromQuery($requestedTags);
        }

        $searchModel=new ExercisesSearchModel();
        $searchModel->load($get,'');
        $searchModel->tags=$requestedTags;

        $categories = $searchModel->searchCategories();

        return $this->render('exercises',['searchModel'=>$searchModel, 'tags'=>$tags,'categories'=>$categories]);
    }

    public function actionWorkouts(){
        $tids=WorkoutsTags::find()->select("tag_id")->distinct("tag_id")->all();
        $tmp=[];
        foreach ($tids as $id){
            $tmp[]=$id->tag_id;
        }
        $tags=Tags::find()->where(['id'=>$tmp])->all();
        $get=\Yii::$app->request->get();
        if(isset($get['tags'])) $requestedTags=$get['tags'];
        else $requestedTags=false;

        foreach ($tags as $tag) {
            $tag->setSelectedFromQuery($requestedTags);
        }

        $searchModel=new WorkoutsSearchModel();
        $searchModel->load($get,'');
        $searchModel->tags=$requestedTags;

        $categories = $searchModel->searchCategories();

        return $this->render('workouts',['searchModel'=>$searchModel, 'tags'=>$tags,'categories'=>$categories]);
    }

}
