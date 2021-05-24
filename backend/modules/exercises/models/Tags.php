<?php

namespace backend\modules\exercises\models;

use common\models\WbpActiveRecord;

class Tags extends WbpActiveRecord
{
    public $selected=false;

    public function setSelectedFromQuery($requestQuery){
        if($requestQuery){
            foreach ($requestQuery as $tag_id){
                if($tag_id==$this->id) $this->selected=true;
            }
        }
    }

    public static function tableName()
    {
        return '{{%tags}}';
    }

    public function rules(){
        return [
            [['title'], 'safe', 'on'=>['edit','add']]
        ];
    }

    public static function getTypehead(){
        $list=self::getList('id','title', 'title');
        $list=array_values($list);
        return $list;
    }


}