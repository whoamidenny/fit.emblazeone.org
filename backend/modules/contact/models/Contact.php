<?php
namespace backend\modules\contact\models;

use backend\modules\telegram\models\TelegramBot;
use common\models\Config;
use common\models\WbpActiveRecord;
use wbp\file\File;
use Yii;

class Contact extends WbpActiveRecord
{
    public static $fileTypes = ['Contact'];

    const SCENARIO_SUBSCRIBE = 'subscribe';
    public $upload_file;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%contact}}';
    }

    public function rules()
    {
        return [
            [['email'], 'required','on'=>self::SCENARIO_SUBSCRIBE],
            [['phone'], 'required','on'=>self::SCENARIO_DEFAULT],
            [['name','email','message','subject','upload_file'], 'safe','on'=>self::SCENARIO_DEFAULT],
            [['upload_file'], 'file', 'skipOnEmpty' => true/*, 'extensions' => 'png, jpg'*/],
        ];

    }

    public function upload()
    {
        if ($this->validate()) {
            $file = new File();
            $file->type = self::$fileTypes[0];
            $file->item_id = $this->id;
            $file->unique_id = uniqid('file_');
            $file->ext = $this->upload_file->extension;
            $file->status = File::STATUS_ACTIVE;
            $file->deleted = File::NON_DELETED;
            $file->name = $this->upload_file->baseName. '.' . $this->upload_file->extension;
            $file->save();
            $this->upload_file->saveAs($_SERVER['DOCUMENT_ROOT'].'/files/source/' . $file->id . '.' . $this->upload_file->extension);

            return true;
        } else {
            return false;
        }
    }

    public function afterSave($insert, $changedAttributes)
    {
        $result = parent::afterSave($insert, $changedAttributes);
        if($insert){
            $telegramUsers=TelegramBot::find()->all();
            foreach ($telegramUsers as $telegramUser){
                $telegramUser->sendMessage('New Message: '.$this->name.' '.$this->phone."\n".$this->getConsultationValues());
            }
            $this->sendEmail();
        }
        return $result;
    }

    public function sendEmail()
    {
        return Yii::$app->mailer->compose()
            ->setTo(Config::getParameter('email', false))
            ->setFrom([Config::getParameter('email', false) => Config::getParameter('title', false)])
            ->setSubject("New contact message")
            ->setHtmlBody("
                <h3>New Contact Message</h3>
                <p>".$this->name.' '.$this->phone.' '.$this->email."</p>
                ".$this->message."        
            ")
            ->send();
    }

//    public function attributeLabels()
//    {
//        return [
//            'phone'=>'Телефон',
//            'name'=>'Имя',
//            'id'=>'#',
//            'created_at'=>'Дата',
//            'sex'=>'Пол',
//            'height'=>'Рост',
//            'weight'=>'Вес',
//            'age'=>'Возраст',
//            'activity'=>'Активность',
//            'goal'=>'Цель'
//        ];
//    }
}
