<?php
namespace backend\modules\clients\models;

use common\models\WbpActiveRecord;

/**
 * Owners model
 */

class ClientPhotoTracker extends WbpActiveRecord
{
    public static $imageTypes=['PhotoFront','PhotoSide','PhotoBack'];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%clients_photo_tracker}}';
    }

    public function rules()
    {
        return [
            ['weight','safe']
        ];
    }

    public function getUser(){
        return $this->hasOne(Client::className(),['id'=>'client_id']);
    }
}
