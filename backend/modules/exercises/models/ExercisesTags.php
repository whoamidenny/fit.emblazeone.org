<?php

namespace backend\modules\exercises\models;

use yii\db\ActiveRecord;

class ExercisesTags extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%exercises_tags}}';
    }
}