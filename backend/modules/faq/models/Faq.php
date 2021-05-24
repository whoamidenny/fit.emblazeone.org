<?php

namespace backend\modules\faq\models;


use common\models\WbpActiveRecord;

class Faq extends WbpActiveRecord
{

    public static $seoKey = 'faq';

    public static function tableName()
    {
        return '{{%faq}}';
    }

    public function rules(){
        return [
            [['title','description','status'], 'safe', 'on'=>['edit','add']]
        ];
    }

    public function getCategory(){
        return $this->hasOne(FaqCategories::className(),['id'=>'category_id']);
    }


    public function attributeLabels()
    {
//        return [
//            'title' => 'Вопрос',
//            'title_ua' => 'Вопрос (укр)',
//            'category_id' => 'Категория',
//            'description' => 'Ответ',
//            'description_ua' => 'Ответ (укр)',
//            'status' => 'Статус',
//            'created_at' => 'Дата создания'
//        ];
    }

}