<?php
namespace backend\models;

use common\models\Identity;
use common\models\WbpActiveRecord;


/**
 * Owners model
 */

class UserAuth extends WbpActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_auth}}';
    }

    public function getUser(){
        return $this->hasOne(Identity::className(),['id'=>'user_id']);
    }
}
