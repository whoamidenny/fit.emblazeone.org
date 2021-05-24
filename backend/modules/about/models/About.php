<?php

namespace backend\modules\about\models;


use common\models\WbpActiveRecord;

class About extends WbpActiveRecord
{

    public static $seoKey = 'faq';
    public static $imageTypes=['about'];

    public static function tableName()
    {
        return '{{%about}}';
    }

    public static function getFrontendQuery(){
        $query=self::find()
            ->where(['status'=>self::STATUS_ACTIVE]);

        return $query;
    }

    public function rules(){
        return [
            [['title','title_ua','status','description','description_ua'], 'safe', 'on'=>['edit','add']]
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => 'Название',
            'title_ua' => 'Название (укр)',
            'category_id' => 'Категория',
            'start_date' => 'Дата начала',
            'stop_date' => 'Дата окончания',
            'description' => 'Содержимое',
            'description_ua' => 'Содержимое (укр)',
            'status' => 'Статус',
            'created_at' => 'Дата создания'
        ];
    }

}