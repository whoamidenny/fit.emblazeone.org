<?php

namespace backend\modules\products\models;


use common\models\WbpActiveRecord;

class ProductsIngredients extends WbpActiveRecord
{

    public static $seoKey = 'products_ingredients';
    public static $imageTypes = ['productsIngredients'];

    public static function tableName()
    {
        return '{{%products_ingredients}}';
    }

    public function rules(){
        return [
            [['title','title_ua', 'status'], 'safe', 'on'=>['edit','add']]
        ];
    }

    public static function getLunchList(){
        if(\Yii::$app->language=='ru_RU'){
            return [
                1=>'Завтрак',
                6=>'Завтрак салат',
                7=>'Завтрак десерт',
                8=>'Завтрак фрукты',
                9=>'Завтрак напиток',
                2=>'2й Завтрак',
                3=>'Обед',
                10=>'Обед салат',
                11=>'Обед десерт',
                12=>'Обед фрукты',
                4=>'Полдник',
                5=>'Ужин',
                13=>'Ужин салат',
            ];
        }else{
            return [
                1=>'Сніданок',
                6=>'Сніданок салат',
                7=>'Сніданок десерт',
                8=>'Сніданок фрукти',
                9=>'Сніданок напій',
                2=>'2й сніданок',
                3=>'Обід',
                10=>'Обід салат',
                11=>'Обід десерт',
                12=>'Обід фрукти',
                4=>'Полуденик',
                5=>'Вечеря',
                13=>'Вечеря салат',
            ];
        }
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