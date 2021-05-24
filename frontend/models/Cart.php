<?php

namespace frontend\models;

use backend\modules\discount\models\Discount;
use backend\modules\products\models\Products;
use Yii;
use yii\base\Model;

class Cart{
    public static $inst;
    protected $cart;

    public static function getInstance(){
        if(!self::$inst){
            self::$inst=new self;
        }
        return self::$inst;
    }

    protected function __construct()
    {
        $this->cart=Yii::$app->session->get('cart', []);
    }

    public function addItem($product_id, $size){
        $product=Products::find()->where(['id'=>$product_id,'status'=>Products::STATUS_ACTIVE])->one();

        if($product && isset(Products::getLengths()[$size])){
            $item=new CartItem();
            $item->load([
                'product_id'=>$product_id,
                'size'=>$size,
                'date'=>NULL
            ],'');
            $this->cart[]=$item;
            Yii::$app->session->set('cart', $this->cart);
        }
    }

    public function addDiscount($code){
        $discount=Discount::find()
            ->where(['code'=>$code])
            ->andWhere(['status'=>Discount::STATUS_ACTIVE])
            ->andWhere('start_date<=NOW() OR start_date IS NULL')
            ->andWhere('stop_date>=NOW() OR stop_date IS NULL')->one();

        if($discount){
            Yii::$app->session->set('discount', $discount->id);
        }
    }

    public function getDiscountId(){
        return Yii::$app->session->get('discount', false);
    }

    public function getDiscountCode(){
        if($this->getDiscountId() && $this->getDiscount()) return $this->getDiscount()->code;
        return false;
    }

    public function getDiscount(){
        $discount_id=$this->getDiscountId();
        if($discount_id){
            $discount=Discount::find()
                ->where(['id'=>$discount_id])
                ->andWhere(['status'=>Discount::STATUS_ACTIVE])
                ->andWhere('start_date<=NOW() OR start_date IS NULL')
                ->andWhere('stop_date>=NOW() OR stop_date IS NULL')
                ->one();

            if($discount){
                return $discount;
            }else{
                Yii::$app->session->set('discount', false);
            }
        }
    }

    public function removeItem($position){
        $this->cart=Yii::$app->session->get('cart', []);
        unset($this->cart[$position]);
        Yii::$app->session->set('cart', $this->cart);
    }

    public function getCount(){
        return count($this->cart);
    }

    /* @return array of CartItem */

    public function getItems(){
        return $this->cart;
    }

    /* @return float */

    public function getPrice(){
        $total=0;
        foreach ($this->getItems() as $item){
            $total+=$item->price;
        }

        return $total;
    }

    public function getDiscountPrice(){
        $discount=$this->getDiscount();
        if($discount){
            return $discount->calculate($this->getPrice());
        }
        return 0;
    }

    public function getTotal(){
        $total=$this->getPrice();
        $total-=$this->getDiscountPrice();
        return $total;
    }

    public function clean(){
        Yii::$app->session->set('discount', false);
        Yii::$app->session->set('cart', []);
    }
}
