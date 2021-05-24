<?php

namespace backend\modules\supplementPlans\models;

use common\models\WbpActiveRecord;

class SupplementPlans extends WbpActiveRecord
{

    public static $seoKey = 'supplementPlans';
    public static $imageTypes = ['supplementPlans'];
    public static $fileTypes = ['supplementPlans'];

    public static function tableName()
    {
        return '{{%supplement_plans}}';
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