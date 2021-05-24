<?php

namespace backend\modules\orders\models;


use backend\modules\products\models\Products;
use common\models\WbpActiveRecord;

class OrdersItems extends WbpActiveRecord
{
    public static function tableName()
    {
        return '{{%orders_items}}';
    }

    public function getProduct(){
        return $this->hasOne(Products::className(),['id'=>'product_id']);
    }
    public function getProductPrice(){
        return $this->product->{'price_'.$this->size};
    }

    public function attributeLabels()
    {
        return [
            'product_id'=>'Продукт',
            'size'=>'Длительность',
        ];
    }

}