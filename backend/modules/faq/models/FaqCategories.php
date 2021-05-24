<?php

namespace backend\modules\faq\models;


use common\models\WbpActiveRecord;

class FaqCategories extends WbpActiveRecord
{

    public static $seoKey = 'faq_categories';
    public static $imageTypes = ['faqCategories'];

    public static function tableName()
    {
        return '{{%faq_categories}}';
    }

    public function rules(){
        return [
            [['title','title_ua', 'status'], 'safe', 'on'=>['edit','add']]
        ];
    }

    public function getFrontendQuery(){
        return self::find()->where(['status'=>self::STATUS_ACTIVE]);
    }

    public function getQuestions(){
        return $this->hasMany(Faq::className(),['category_id'=>'id']);
    }

    public function getActiveQuestions(){
        return $this->getQuestions()->andWhere(['status'=>Faq::STATUS_ACTIVE]);
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