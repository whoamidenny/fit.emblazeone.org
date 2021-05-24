<?php

namespace backend\modules\workouts\models;

use backend\modules\exercises\models\Exercises;
use backend\modules\exercises\models\Tags;
use common\models\WbpActiveRecord;

class Workouts extends WbpActiveRecord
{

    public static $seoKey = 'Workouts';
    public static $imageTypes = ['Workouts'];

    public $tagTitlesArray=[];
    public $saveTags=false;

    public $exercises_ids;

    public static function tableName()
    {
        return '{{%workouts}}';
    }

    public function rules(){
        return [
            [['title','status','sort','category_id','exercises_ids','tagsTitlesRow'], 'safe', 'on'=>['edit','add']]
        ];
    }

    public function afterFind()
    {
        $this->exercises_ids=[];
        foreach ($this->workoutExercises as $link){
            $this->exercises_ids[]=$link->exercise_id;
        }
        return parent::afterFind();
    }

    public function afterSave($insert, $changedAttributes)
    {
        foreach ($this->workoutExercises as $link){
            if(!in_array($link->exercise_id, $this->exercises_ids)) $link->delete();
        }

        foreach ($this->exercises_ids as $id){
            $link=WorkoutsExercises::findOne(['workout_id'=>$this->id, 'exercise_id'=>$id]);
            if(!$link){
                $link=new WorkoutsExercises();
                $link->workout_id=$this->id;
                $link->exercise_id=$id;
                $link->save();
            }
        }

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
                    $link=new WorkoutsTags();
                    $link->tag_id=$dbTag->id;
                    $link->workout_id=$this->id;
                    $link->save();
                }
                $foundTag[]=$tag;
            }
            foreach ($tags as $tag){
                if(!in_array($tag->title, $foundTag)){
                    $link=WorkoutsTags::findOne(['tag_id'=>$tag->id, 'workout_id'=>$this->id]);
                    if($link) $link->delete();
                }
            }
        }

        return parent::afterSave($insert, $changedAttributes);
    }

    public function getExercises(){
        return $this->hasMany(Exercises::className(), ['id' => 'exercise_id'])
            ->viaTable(WorkoutsExercises::tableName(), ['workout_id' => 'id']);
    }

    public function getWorkoutExercises(){
        return $this->hasMany(WorkoutsExercises::className(), ['workout_id' => 'id']);
    }
    public function getTags(){
        return $this->hasMany(Tags::className(), ['id' => 'tag_id'])
            ->viaTable(WorkoutsTags::tableName(), ['workout_id' => 'id']);
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