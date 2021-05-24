<?php

namespace backend\modules\workoutsCategories\models;

use backend\modules\exercises\models\Exercises;
use backend\modules\workouts\models\Workouts;
use common\models\WbpActiveRecord;

class WorkoutsCategories extends WbpActiveRecord
{

    public static $seoKey = 'WorkoutsCategories';
    public static $imageTypes = ['WorkoutsCategories'];

    public static function tableName()
    {
        return '{{%workouts_categories}}';
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

    public function getVideos($tags='', $search=''){
        $videos=Workouts::find()->leftJoin("{{%workouts_tags}}","{{%workouts_tags}}.workout_id={{%workouts}}.id");
        $videos=$videos->where(["category_id"=>$this->id]);
        if($tags) $videos=$videos->andWhere(["{{%workouts_tags}}.tag_id"=>$tags]);
        if($search) $videos=$videos->andWhere(['like', "title", $search]);
        return $videos;
    }

}