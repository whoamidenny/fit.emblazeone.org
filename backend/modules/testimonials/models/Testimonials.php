<?php

namespace backend\modules\testimonials\models;


use backend\modules\products\models\Products;
use common\models\WbpActiveRecord;

class Testimonials extends WbpActiveRecord
{

    public static $seoKey = 'faq';
    public static $imageTypes=['testimonials_1','testimonials_2'];

    public static function tableName()
    {
        return '{{%testimonials}}';
    }

    public static function getFrontendQuery(){
        $query=self::find()
            ->where(['status'=>self::STATUS_ACTIVE]);

        return $query;
    }

    public function rules(){
        return [
            [['title','title_ua','status','product_id'], 'safe', 'on'=>['edit','add']]
        ];
    }

    public function getProduct(){
        return $this->hasOne(Products::className(),['id'=>'product_id']);
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
            'product_id' => 'Ссылка на Продукт',
            'created_at' => 'Дата создания'
        ];
    }

}