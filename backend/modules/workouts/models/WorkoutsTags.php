<?php

namespace backend\modules\workouts\models;

use yii\db\ActiveRecord;

class WorkoutsTags extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%workouts_tags}}';
    }
}