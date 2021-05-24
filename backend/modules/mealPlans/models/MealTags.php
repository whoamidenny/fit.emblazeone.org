<?php

namespace backend\modules\mealPlans\models;

use yii\db\ActiveRecord;

class MealTags extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%meal_tags}}';
    }
}