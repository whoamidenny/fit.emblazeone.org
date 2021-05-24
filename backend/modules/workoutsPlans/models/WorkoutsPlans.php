<?php

namespace backend\modules\workoutsPlans\models;

use common\models\WbpActiveRecord;

class WorkoutsPlans extends WbpActiveRecord
{

    public static $seoKey = 'workoutsPlans';
    public static $imageTypes = ['workoutsPlans'];
    public static $fileTypes = ['workoutsPlans'];

    public static function tableName()
    {
        return '{{%workout_plans}}';
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

}