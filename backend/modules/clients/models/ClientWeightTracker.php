<?php
namespace backend\modules\clients\models;

use common\models\WbpActiveRecord;

/**
 * Owners model
 */

class ClientWeightTracker extends WbpActiveRecord
{

    public function rules()
    {
        return [
            [['week','weight'],'safe']
        ];
    }

    public static function tableName()
    {
        return '{{%clients_weight_tracker}}';
    }

    public function getUser(){
        return $this->hasOne(Client::className(),['id'=>'client_id']);
    }
}
