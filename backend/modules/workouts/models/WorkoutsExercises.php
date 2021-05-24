<?php

namespace backend\modules\workouts\models;

use yii\db\ActiveRecord;

class WorkoutsExercises extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%workouts_exercises}}';
    }
}