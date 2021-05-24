<?php
namespace backend\modules\telegram\models;

use common\models\Config;
use common\models\Identity;
use common\models\WbpActiveRecord;

/**
 * Owners model
 */

class TelegramBot extends WbpActiveRecord
{
    public static $token="";

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%telegram_bot}}';
    }

    public function getPhone(){
        return $this->phone;
    }

    public function getName(){
        if($this->user) return $this->user->getName();
    }

    public function getUser(){
        return $this->hasOne(Identity::className(),['id'=>'user_id']);
    }

    public function is_admin(){
        return true;
//        if($this->user && $this->user->role>10){
//            return true;
//        }elseif($this->owner && $this->owner->room){
//            $user = $this->owner->room->getUsers()->one();
//            if($user && $user->role>10) return true;
//            return false;
//        }
//        return false;
    }

    public function getLoginToken(){
        $user=$this->user;
        $user->generateLoginToken();
        return $user->login_token;
    }

    public function sendMessage($text, $keys=false){
        $token=Config::getParameter('telegram_token', false);
        $bot = new \TelegramBot\Api\Client($token);

        if($keys){
            $keyboard = new \TelegramBot\Api\Types\Inline\InlineKeyboardMarkup($keys);
        }else{
            $keyboard=null;
        }

        $bot->sendMessage($this->chat_id, $text,  null, false, null, $keyboard);
    }

    public function attributeLabels()
    {
        return [
            'phone'=>'Телефон',
            'name'=>'Имя',
            'id'=>'#',
            'chat_id'=>'# чата',
            'created_at'=>'Дата',
        ];
    }
}
