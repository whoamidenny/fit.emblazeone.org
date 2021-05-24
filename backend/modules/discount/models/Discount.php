<?php

namespace backend\modules\discount\models;


use common\models\WbpActiveRecord;

class Discount extends WbpActiveRecord
{
    public static $seoKey = 'faq';
    public static $types=[1=>'грн.',2=>'%'];

    public static function tableName()
    {
        return '{{%discount}}';
    }

    public static function getFrontendQuery(){
        $query=self::find()
            ->where(['status'=>self::STATUS_ACTIVE]);

        return $query;
    }

    public function rules(){
        return [
            [['code','value', 'type', 'status', 'start_date', 'stop_date' ,'status'], 'safe', 'on'=>['edit','add']]
        ];
    }

    public function getTypeTitle(){
        return self::$types[$this->type];
    }

    public function attributeLabels()
    {
        return [
            'code' => 'Код',
            'title' => 'Название',
            'title_ua' => 'Название (укр)',
            'value' => 'Скидка',
            'type' => 'Тип скидки',
            'category_id' => 'Категория',
            'start_date' => 'Дата начала',
            'stop_date' => 'Дата окончания',
            'description' => 'Содержимое',
            'description_ua' => 'Содержимое (укр)',
            'status' => 'Статус',
            'created_at' => 'Дата создания'
        ];
    }

    public function calculate($value){
        if($this->type==2){
            return $value*$this->value/100;
        }
        if($this->type==1){
            return $this->value;
        }
        return 0;
    }

}