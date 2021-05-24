<?php

namespace backend\modules\products\models;


use common\models\WbpActiveRecord;

class ProductsDays extends WbpActiveRecord
{

    public static $seoKey = 'products_days';

    public $arrayPars=['ingredients','titles'];

    public static function getDays(){
        if(\Yii::$app->language=='ru_RU'){
            return [
                1=>'Пн',
                2=>'Вт',
                3=>'Ср',
                4=>'Чт',
                5=>'Пт',
                6=>'Сб',
                7=>'Вс',
            ];
        }else{
            return [
                1=>'Пн',
                2=>'Вт',
                3=>'Ср',
                4=>'Чт',
                5=>'Пт',
                6=>'Сб',
                7=>'Нд',
            ];
        }
    }

    public static function getFullDays(){
        if(\Yii::$app->language=='ru_RU'){
            return [
                1=>'Понедельник',
                2=>'Вторник',
                3=>'Среда',
                4=>'Четверг',
                5=>'Пятница',
                6=>'Суббота',
                7=>'Воскресенье',
            ];
        }else{
            return [
                1=>'Понеділок',
                2=>'Вівторок',
                3=>'Середа',
                4=>'Четверг',
                5=>'П\'ятниця',
                6=>'Субота',
                7=>'Неділя',
            ];
        }
    }

    public function getFullDayName(){
        return self::getFullDays()[$this->day];
    }

    public function getDay(){
        return self::getDays()[$this->day];
    }

    public static function tableName()
    {
        return '{{%products_per_day}}';
    }

    public function rules(){
        return [
            [['ingredients','titles','b', 'g','u','kal'], 'safe', 'on'=>['edit','add']]
        ];
    }

    public function getProductIngredients(){
        $ingredients=$this->ingredients;
        $result=[];
        foreach ($ingredients as $ingredient_id){
            $result[]=ProductsIngredients::findOne($ingredient_id);
        }
        return $result;
    }

    public function getLunchList(){
        $titles=$this->titles;
        $result=[];
        foreach ($titles as $title_id){
            $result[]=ProductsIngredients::getLunchList()[$title_id];
        }
        return $result;
    }

    public function attributeLabels()
    {
        return [
            'ingredients' => 'Состав',
            'b' => 'Белки',
            'g' => 'Жиры',
            'u' => 'Углеводы',
            'kal' => 'Калории'
        ];
    }
}