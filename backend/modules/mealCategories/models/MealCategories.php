<?php

namespace backend\modules\mealCategories\models;

use backend\modules\exercises\models\Exercises;
use backend\modules\mealPlans\models\MealPlans;
use backend\modules\workouts\models\Workouts;
use common\models\WbpActiveRecord;

class MealCategories extends WbpActiveRecord
{

    public static $seoKey = 'MealCategories';
    public static $imageTypes = ['MealCategories'];

    public static function tableName()
    {
        return '{{%meal_categories}}';
    }

    public function rules(){
        return [
            [['title','status','sort'], 'safe', 'on'=>['edit','add']]
        ];
    }


    public function attributeLabels()
    {
        return [
            'title' => 'Title',
            'status' => 'Status',
            'created_at' => 'Date'
        ];
    }

    public function getPlans($tags='', $search=''){
        $videos=MealPlans::find()->leftJoin("{{%meal_tags}}","{{%meal_tags}}.meal_id={{%meal_plans}}.id");
        $videos=$videos->where(["category_id"=>$this->id]);
        if($tags) $videos=$videos->andWhere(["{{%meal_tags}}.tag_id"=>$tags]);
        if($search) $videos=$videos->andWhere(['like', "title", $search]);
        return $videos;
    }

}