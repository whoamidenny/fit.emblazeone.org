<?php
namespace backend\modules\clients\models;

use common\models\WbpActiveRecord;

/**
 * Owners model
 */

class ClientAuth extends WbpActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%client_auth}}';
    }

    public function getUser()
    {
        return $this->hasOne(Client::className(), ['id' => 'client_id']);
    }
}
