<?php


namespace frontend\models;


use backend\modules\exercises\models\Exercises;
use backend\modules\exercisesCategories\models\ExercisesCategories;
use yii\base\Model;

class ExercisesSearchModel extends Model
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
        $videos=Exercises::find()->leftJoin("{{%exercises_tags}}","{{%exercises_tags}}.exercise_id={{%exercises}}.id");
        if($this->tags) $videos=$videos->where(["{{%exercises_tags}}.tag_id"=>$this->tags]);
        return $videos;
    }

    public function searchCategories(){
        $distinctIds=$this->searchVideos()->select("category_id")->distinct("category_id")->all();
        $categories_ids=[];
        foreach ($distinctIds as $distinct){
            $categories_ids[]=$distinct->category_id;
        }
        $categories=ExercisesCategories::find()->where(['id'=>$categories_ids]);
        $categories=$categories->all();
        return $categories;
    }


}