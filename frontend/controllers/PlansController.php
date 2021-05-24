<?php
namespace frontend\controllers;

use backend\modules\exercises\models\Exercises;
use backend\modules\exercises\models\ExercisesTags;
use backend\modules\exercises\models\Tags;
use backend\modules\mealPlans\models\MealTags;
use backend\modules\supplementPlans\models\SupplementPlans;
use backend\modules\workouts\models\WorkoutsTags;
use backend\modules\workoutsPlans\models\WorkoutsPlans;
use common\models\Identity;
use frontend\models\ExercisesSearchModel;
use frontend\models\MealsSearchModel;
use frontend\models\WorkoutsSearchModel;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

/**
 * Site controller
 */
class PlansController extends Controller
{


    public function behaviors()
    {
        $rules=[];
        $rules[]=[
            'actions' => ['supplement','workout','meal'],
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

    public function actionSupplement(){
        $plans=SupplementPlans::find()->where(['status'=>SupplementPlans::STATUS_ACTIVE])->all();

        return $this->render('supplement',['plans'=>$plans]);
    }
    public function actionWorkout(){
        $plans=WorkoutsPlans::find()->where(['status'=>SupplementPlans::STATUS_ACTIVE])->all();
        return $this->render('workout',['plans'=>$plans]);
    }
    public function actionMeal(){
        $tids=MealTags::find()->select("tag_id")->distinct("tag_id")->all();
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

        $searchModel=new MealsSearchModel();
        $searchModel->load($get,'');
        $searchModel->tags=$requestedTags;

        $categories = $searchModel->searchCategories();

        return $this->render('meal',['searchModel'=>$searchModel, 'tags'=>$tags,'categories'=>$categories]);

    }

}
