<?php

namespace backend\modules\products\models;


use common\models\WbpActiveRecord;

class ProductsCategories extends WbpActiveRecord
{

    public static $seoKey = 'products_categories';
    public static $imageTypes = ['productsCategories'];

    public static function tableName()
    {
        return '{{%products_categories}}';
    }

    public function rules(){
        return [
            [['title','title_ua', 'status'], 'safe', 'on'=>['edit','add']]
        ];
    }

    public function getProducts(){
        return $this->hasMany(Products::className(),['category_id'=>'id']);
    }
    public function getActiveProducts(){
        return $this->getProducts()->andWhere(['status'=>Products::STATUS_ACTIVE]);
    }

    public function attributeLabels()
    {
        return [
            'title' => 'Название',
            'title_ua' => 'Название (укр)',
            'status' => 'Статус',
            'created_at' => 'Дата создания'
        ];
    }

}