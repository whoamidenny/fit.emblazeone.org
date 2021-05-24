<?php

namespace backend\modules\mealPlans\models;

use backend\modules\exercises\models\Tags;
use common\models\WbpActiveRecord;

class MealPlans extends WbpActiveRecord
{

    public static $seoKey = 'MealPlans';
    public static $imageTypes = ['MealPlans'];
    public static $fileTypes = ['MealPlans'];

    public $tagTitlesArray=[];
    public $saveTags=false;

    public $exercises_ids;

    public static function tableName()
    {
        return '{{%meal_plans}}';
    }

    public function rules(){
        return [
            [['title','status','sort','category_id','tagsTitlesRow'], 'safe', 'on'=>['edit','add']]
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {

        if($this->saveTags){
            $tags=$this->tags;
            $foundTag=[];
            foreach ($this->tagTitlesArray as $tag){
                $found=false;
                foreach ($tags as $existTag){
                    if($existTag->title==$tag) $found=true;
                }
                if(!$found){
                    $dbTag=Tags::findOne(['title'=>$tag]);
                    if(!$dbTag){
                        $dbTag=new Tags();
                        $dbTag->title=$tag;
                        $dbTag->save();
                    }
                    $link=new MealTags();
                    $link->tag_id=$dbTag->id;
                    $link->meal_id=$this->id;
                    $link->save();
                }
                $foundTag[]=$tag;
            }
            foreach ($tags as $tag){
                if(!in_array($tag->title, $foundTag)){
                    $link=MealTags::findOne(['tag_id'=>$tag->id, 'meal_id'=>$this->id]);
                    if($link) $link->delete();
                }
            }
        }

        return parent::afterSave($insert, $changedAttributes);
    }


    public function getTags(){
        return $this->hasMany(Tags::className(), ['id' => 'tag_id'])
            ->viaTable(MealTags::tableName(), ['meal_id' => 'id']);
    }

    public function getTagsTitlesRow(){
        return implode(',', $this->tagsTitles);
    }

    public function getTagsTitles(){
        if($this->tagTitlesArray) return $this->tagTitlesArray;

        $this->tagTitlesArray=[];
        foreach ($this->tags as $tag){
            $this->tagTitlesArray[]=$tag->title;
        }
        return $this->tagTitlesArray;
    }

    public function setTagsTitlesRow($values){
        $this->setTagsTitles(explode(',', $values));
    }

    public function setTagsTitles($values){
        $this->tagTitlesArray=$values;
        $this->saveTags=true;
    }



    public function attributeLabels()
    {
        return [
            'title' => 'Title',
            'status' => 'Status',
            'category_id' => 'Category',
            'url' => 'Video url',
            'created_at' => 'Date',
            'exercises_ids' => 'Exercises'
        ];
    }

}