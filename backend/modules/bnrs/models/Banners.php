<?php

namespace backend\modules\bnrs\models;


use common\models\WbpActiveRecord;

class Banners extends WbpActiveRecord
{

    public static $seoKey = 'faq';
    public static $imageTypes=['banner','banner_ua'];

    public static function tableName()
    {
        return '{{%banners}}';
    }

    public static function getFrontendQuery(){
        $query=self::find()
            ->where(['status'=>self::STATUS_ACTIVE])
            ->andWhere('start_date<=NOW() OR start_date IS NULL')
            ->andWhere('stop_date>=NOW() OR stop_date IS NULL');

        return $query;
    }

    public function rules(){
        return [
            [['title','title_ua','start_date', 'stop_date', 'status', 'description','description_ua'], 'safe', 'on'=>['edit','add']]
        ];
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
            'created_at' => 'Дата создания'
        ];
    }

}