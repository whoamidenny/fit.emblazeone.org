<?php


namespace frontend\models;


use backend\modules\exercises\models\Exercises;
use backend\modules\exercisesCategories\models\ExercisesCategories;
use backend\modules\workouts\models\Workouts;
use backend\modules\workoutsCategories\models\WorkoutsCategories;
use yii\base\Model;

class WorkoutsSearchModel extends Model
{
    public $tags;
    public $search;

    public function rules()
    {
        return [
            [['tags','search'],'safe']
        ];
    }

    public function searchVideos(){
        $videos=Workouts::find()->leftJoin("{{%workouts_tags}}","{{%workouts_tags}}.workout_id={{%workouts}}.id");
        if($this->tags) $videos=$videos->where(["{{%workouts_tags}}.tag_id"=>$this->tags]);
        return $videos;
    }

    public function searchCategories(){
        $distinctIds=$this->searchVideos()->select("category_id")->distinct("category_id")->all();
        $categories_ids=[];
        foreach ($distinctIds as $distinct){
            $categories_ids[]=$distinct->category_id;
        }
        $categories=WorkoutsCategories::find()->where(['id'=>$categories_ids]);
        $categories=$categories->all();
        return $categories;
    }


}