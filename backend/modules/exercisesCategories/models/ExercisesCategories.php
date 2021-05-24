<?php

namespace backend\modules\exercisesCategories\models;

use backend\modules\exercises\models\Exercises;
use common\models\WbpActiveRecord;

class ExercisesCategories extends WbpActiveRecord
{

    public static $seoKey = 'ExercisesCategories';
    public static $imageTypes = ['ExercisesCategories'];

    public static function tableName()
    {
        return '{{%exercises_categories}}';
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
        $videos=Exercises::find()->leftJoin("{{%exercises_tags}}","{{%exercises_tags}}.exercise_id={{%exercises}}.id");
        $videos=$videos->where(["category_id"=>$this->id]);
        if($tags) $videos=$videos->andWhere(["{{%exercises_tags}}.tag_id"=>$tags]);
        if($search) $videos=$videos->andWhere(['like', "title", $search]);
        return $videos;
    }

}