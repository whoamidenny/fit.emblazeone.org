<?php

namespace backend\modules\pages\models;

use common\models\WbpActiveRecord;

class Pages extends WbpActiveRecord
{

    public static $seoKey = 'pages';

    public static function tableName()
    {
        return '{{%pages}}';
    }

    public function rules(){
        return [
            [['title','description','href','status'], 'safe', 'on'=>['edit','add']]
        ];
    }


    public function attributeLabels()
    {
        return [
            'title' => 'Title',
            'description' => 'Content',
            'status' => 'Status',
            'created_at' => 'Date'
        ];
    }

}