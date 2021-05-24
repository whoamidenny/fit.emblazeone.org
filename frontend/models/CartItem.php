<?php

namespace frontend\models;

use backend\modules\products\models\Products;
use Yii;
use yii\base\Model;

class CartItem extends Model{
    public $product_id;
    public $size;
    public $date;

    public function rules()
    {
        return [
            [['product_id','size','date'], 'safe']
        ];
    }

    public function getProduct(){
        return Products::findOne($this->product_id);
    }

    public function getPrice(){
        return (int)$this->product->{'price_'.$this->size};
    }

    public function getDayPrice(){
        $days=Products::getLengths()[$this->size];
        return (int)($this->product->{'price_'.$this->size}/$days);
    }

    public function getDayTitle(){
        return Products::getLengthsDays()[$this->size];
    }
}
