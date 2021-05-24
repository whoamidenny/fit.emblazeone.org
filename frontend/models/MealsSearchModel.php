<?php


namespace frontend\models;


use backend\modules\exercises\models\Exercises;
use backend\modules\exercisesCategories\models\ExercisesCategories;
use backend\modules\mealCategories\models\MealCategories;
use backend\modules\mealPlans\models\MealPlans;
use yii\base\Model;

class MealsSearchModel extends Model
{
    public $tags;
    public $search;

    public function rules()
    {
        return [
            [['tags','search'],'safe']
        ];
    }

    public function searchPlans(){
        $videos=MealPlans::find()->leftJoin("{{%meal_tags}}","{{%meal_tags}}.meal_id={{%meal_plans}}.id");
        if($this->tags) $videos=$videos->where(["{{%meal_tags}}.tag_id"=>$this->tags]);
        return $videos;
    }

    public function searchCategories(){
        $distinctIds=$this->searchPlans()->select("category_id")->distinct("category_id")->all();
        $categories_ids=[];
        foreach ($distinctIds as $distinct){
            $categories_ids[]=$distinct->category_id;
        }
        $categories=MealCategories::find()->where(['id'=>$categories_ids]);
        $categories=$categories->all();
        return $categories;
    }


}