<?php

namespace backend\modules\products\models;


use common\models\WbpActiveRecord;

class Products extends WbpActiveRecord
{

    public static $seoKey = 'faq';
    public $productDayArray=[];

    public static function tableName()
    {
        return '{{%products}}';
    }

    public function rules(){
        return [
            [['title','kal','price_1','price_2','price_3','price_4','price_5','price_6','title_ua','category_id','description','description_ua', 'status', 'productDayArray'], 'safe', 'on'=>['edit','add']]
        ];
    }

    public static function getLengths(){
        return [
            1=>1,
            2=>2,
            3=>7,
            4=>14,
            5=>21,
            6=>30
        ];
    }

    public static function getLengthsDays(){
        $result=[];
        foreach (self::getLengths() as $num=>$days){
            if($days==1 || $days==21) $result[$num]=$days.' '.\Yii::t('index', 'день');
            else if($days==2) $result[$num]=$days.' '.\Yii::t('index', 'дня');
            else $result[$num]=$days.' '.\Yii::t('index', 'дней');
        }
        return $result;
    }

    public function getCategory(){
        return $this->hasOne(ProductsCategories::className(),['id'=>'category_id']);
    }

    public function getProductsDays(){
        return $this->hasMany(ProductsDays::className(),['product_id'=>'id']);
    }

    public function getProductDay($day){
        $productDay=$this->getProductsDays()->andWhere(['day'=>$day])->one();
        if(!$productDay){
            $productDay=new ProductsDays();
            $productDay->product_id=$this->id;
            $productDay->day=$day;
        }
        return $productDay;
    }

    public function afterSave($insert, $changedAttributes)
    {
//        var_dump($this->productDayArray);
//        exit();
        for ($i=1;$i<=7;$i++){
            $productDay=$this->getProductDay($i);
            $productDay->scenario='edit';
            if(isset($this->productDayArray[$i])){
                $productDay->load($this->productDayArray[$i],'');
            }
            $productDay->save();
        }


        return parent::afterSave($insert, $changedAttributes);
    }

    public function attributeLabels()
    {
        return [
            'title' => 'Название',
            'title_ua' => 'Название (укр)',
            'kal' => 'Калорийность',
            'category_id' => 'Категория',
            'description' => 'Описание',
            'description_ua' => 'Описание (укр)',
            'status' => 'Статус',
            'created_at' => 'Дата создания',
            'price_1'=>'Цена (1 день)',
            'price_2'=>'Цена (2 дня)',
            'price_3'=>'Цена (7 дней)',
            'price_4'=>'Цена (14 дней)',
            'price_5'=>'Цена (21 день)',
            'price_6'=>'Цена (30 дней)',
        ];
    }

}