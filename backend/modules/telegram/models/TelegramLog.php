<?php
namespace backend\modules\telegram\models;

use common\models\WbpActiveRecord;

class TelegramLog extends WbpActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%telegram_log}}';
    }

    public static function getDb()
    {
        $db = parent::getDb();
//        $db->charset='utf8mb4';
        return $db;
    }
}
