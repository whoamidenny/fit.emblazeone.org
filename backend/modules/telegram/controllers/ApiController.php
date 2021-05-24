<?php

namespace backend\modules\telegram\controllers;

use backend\assets\AppAsset;
use backend\controllers\BaseController;
use backend\modules\telegram\models\TelegramBot;
use backend\modules\telegram\models\TelegramLog;
use common\models\Config;
use common\models\Identity;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use TelegramBot\Api\Types\Message;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;
use TelegramBot\Api\Types\ReplyKeyboardRemove;
use TelegramBot\Api\Types\Update;
use wbp\images\models\Image;
use wbp\telegramBot\Client;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class ApiController extends BaseController
{
//    const REGEXP = '/^(?:@\w+\s)?\/([^\s@]+)(@\S+)?\s?(.*)$/';
    const REGEXP = '/^\/(\w)?/';
    const INPUT_MESSAGE=0;
    const OUTPUT_MESSAGE=1;

    const MESSAGE_TYPE_COMMAND='command';
    const MESSAGE_TYPE_TEXT='text';
    const MESSAGE_TYPE_CALLBACK='callback';
    const MESSAGE_TYPE_PHOTO='photo';

    public $inLog;
    public $request_local;

    public $callback_query=false;
    public $message=false;
    public $prevCommands=false;

    public $chat_id;
    public $inContent;

    public $mediaCache=false;

    public function allowedActions(){
        return ['index'];
    }

    public function userActions(){
        return ['set-telegram-webhook'];
    }

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function getPreviousMessages($chat_id, $type){
        $logs=TelegramLog::find()->where(['deleted_from_chat'=>0, 'chat_id'=>$chat_id, 'message_type'=>$type])->orderBy('id desc')->all();
        return $logs;
    }

    public function deletePreviousMessages($chat_id, $bot, $type){
        $menus=$this->getPreviousMessages($chat_id,$type);
        foreach ($menus as $menu){
            if($menu->message_id){
                $menu->deleted_from_chat=1;
                $menu->save();
                $bot->deleteMessage($chat_id, $menu->message_id);
            }
        }
    }
    public function deletePreviousMenus($chat_id, $bot){
        $this->deletePreviousMessages($chat_id, $bot, 'menu');
    }

    public function addOutputLogMessage(Message $message, $type='text'){
        $outLog = new TelegramLog();
        $outLog->message_direction=self::OUTPUT_MESSAGE;
        $outLog->chat_id=$message->getChat()->getId();
        $outLog->message_id=$message->getMessageId();
        $outLog->value=$message->getText();
        $outLog->message_type=$type;
        $outLog->deleted_from_chat=0;
        $outLog->save();
    }

    public function addInputLogMessage(Update $request){
        $this->inLog = new TelegramLog();
        $this->inLog->content=$this->inContent;
        $this->inLog->get=json_encode($_GET);
        $this->inLog->post=json_encode($_POST);
        $this->inLog->message_direction=self::INPUT_MESSAGE;
        $this->inLog->message_id=$this->getMessageIdFromRequest($request);
        $this->inLog->chat_id=$this->getChatIdFromRequest($request);
        $this->inLog->value=$this->getValueFromRequest($request);

        $type=$this->getTypeFromRequest($request);
        $this->inLog->message_type=$type;

        if($type==self::MESSAGE_TYPE_PHOTO){
            $this->inLog->files=json_encode($this->getFilesListFromRequest($request));
        }

        $checkDuplicated=TelegramLog::find()->where([
            'message_direction'=>$this->inLog->message_direction,
            'message_id'=>$this->inLog->message_id,
            'chat_id'=>$this->inLog->chat_id,
            'value'=>$this->inLog->value,
            'message_type'=>$this->inLog->message_type,
        ])->one();

        if($checkDuplicated) $this->inLog->message_direction=2;

        $this->inLog->deleted_from_chat=0;
        $this->inLog->save();

        if($checkDuplicated) exit();
    }

    public function getPreviousCommand($chat_id){
        $logs = TelegramLog::find()
            ->where([
                'chat_id'=>$chat_id,
                'message_direction'=>self::INPUT_MESSAGE,
                'message_type'=>[self::MESSAGE_TYPE_CALLBACK, self::MESSAGE_TYPE_COMMAND]
            ])
            ->andWhere(['!=','id',$this->inLog->id])
            ->orderBy('id desc')
//            ->offset(1)
            ->limit(1);
        return $logs->one();
    }

    public function getFilesListFromRequest(Update $request){
        $result=[];
        if($message = $request->getMessage()){
            $photos=$message->getPhoto();
            if($photos && count($photos)){
                $width=0;
                foreach ($photos as $photo){
                    if($photo->getWidth()>$width){
                        $result=[$photo->getFileId()];
                    }
                }
            }
        }elseif ($message = $request->getEditedMessage()) {
            $photos=$message->getPhoto();
            if($photos && count($photos)){
                $width=0;
                foreach ($photos as $photo){
                    if($photo->getWidth()>$width) {
                        $result = [$photo->getFileId()];
                    }
                }
            }
        }
        return $result;
    }

    public function getMessageIdFromRequest(Update $request){
        if($message = $request->getMessage()){
            return $message->getMessageId();
        }elseif ($message = $request->getEditedMessage()) {
            return $message->getMessageId();
        }elseif ($callback = $request->getCallbackQuery()){
            return $callback->getId();
        }
    }


    public function getValueFromRequest(Update $request){
        if($message = $request->getMessage()){
            $photos=$message->getPhoto();
            if($photos && count($photos)){
                return $message->getCaption();
            }

            return $message->getText();
        }elseif ($message = $request->getEditedMessage()) {
            $photos=$message->getPhoto();
            if($photos && count($photos)){
                return $message->getCaption();
            }

            return $message->getText();
        }elseif ($callback = $request->getCallbackQuery()){
            return $callback->getData();
        }

    }

    public function getTypeFromRequest(Update $request){
        if($message = $request->getMessage()){
            $text=$message->getText();
            $photos=$message->getPhoto();
            if($photos && count($photos)){
                return self::MESSAGE_TYPE_PHOTO;
            }

            preg_match(self::REGEXP, $text, $matches);
            if(!empty($matches) && isset($matches[1])){
                return self::MESSAGE_TYPE_COMMAND;
            }
            return self::MESSAGE_TYPE_TEXT;
        }elseif ($message = $request->getEditedMessage()) {
            $text=$message->getText();
            $photos=$message->getPhoto();
            if($photos && count($photos)){
                return self::MESSAGE_TYPE_PHOTO;
            }
            preg_match(self::REGEXP, $text, $matches);
            if(!empty($matches) && $matches[1]){
                return self::MESSAGE_TYPE_COMMAND;
            }
            return self::MESSAGE_TYPE_TEXT;
        }elseif ($callback = $request->getCallbackQuery()){
            return self::MESSAGE_TYPE_CALLBACK;
        }

    }
    public function getChatIdFromRequest(Update $request){
        if($this->chat_id) return $this->chat_id;

        $chat_id=false;
        if($message = $request->getMessage()){
            $chat_id=$message->getChat()->getId();
        }elseif ($message = $request->getEditedMessage()) {
            $chat_id = $message->getChat()->getId();
        }elseif ($callback = $request->getCallbackQuery()){
            $chat_id = $callback->getMessage()->getChat()->getId();
        }

        $this->chat_id=$chat_id;
        return $chat_id;
    }

    public function getInputMessageCommand($content=false){
        if (!$content) $content = $this->inContent;
        $json = json_decode($content, true);
        if (isset($json) && isset($json["message"]) && isset($json["message"]["text"])) {
            preg_match(self::REGEXP, $json["message"]["text"], $matches);
            if(!empty($matches) && $matches[1]) {
                return $matches[1];
            }
        }
        return false;
    }

    public function actionIndex($tk='')
    {
        \Yii::$app->response->format=Response::FORMAT_JSON;

        $this->inContent = file_get_contents("php://input");
//        file_put_contents($_SERVER['DOCUMENT_ROOT'].'/telegram',$this->inContent);
//        $this->inContent='{"update_id":619239413,
//"message":{"message_id":23,"from":{"id":153170246,"is_bot":false,"first_name":"Pavel","username":"pavel_char","language_code":"en"},"chat":{"id":153170246,"first_name":"Pavel","username":"pavel_char","type":"private"},"date":1598797742,"text":"/tutorial","entities":[{"offset":0,"length":9,"type":"bot_command"}]}}';

        $json=json_decode($this->inContent, true);

        $token=Config::getParameter('telegram_token', false);
//        if($tk!=$token) throw new NotFoundHttpException('Page not found');

        $this->request_local=Update::fromResponse($json);

        $this->addInputLogMessage($this->request_local);

//        if($this->inLog->chat_id<0) return '';


        try {
            $bot = new Client($token);

            $controller=&$this;

            $chat_id=$this->inLog->chat_id;
            $type=$this->inLog->message_type;

            $prevCommand=$this->getPreviousCommand($chat_id);
            if($type==self::MESSAGE_TYPE_CALLBACK){
                $bot->answerCallbackQuery($this->inLog->message_id);

                $callbackAllreadyExists=TelegramLog::find()->where(['chat_id'=>$chat_id,'message_type'=>'callback','message_id'=>$this->inLog->message_id])->count();
                if($callbackAllreadyExists>1) return;

                $this->callback_query=explode('/', $this->inLog->value);
                $methodName="callback".ucfirst($this->callback_query[0]);
                if(method_exists($controller,$methodName)){
                    call_user_func_array(array($controller, $methodName), [
                        'chat_id'=>$chat_id,
                        'bot'=>&$bot
                    ]);
                }else{
                    $controller->callbackError($chat_id, $bot);
                }

            }elseif($type==self::MESSAGE_TYPE_COMMAND){
                $command=substr($this->inLog->value,1);
                $methodName="command".ucfirst($command);
                if(method_exists($controller,$methodName)){
                    call_user_func_array(array($controller, $methodName), [
                        'chat_id'=>$chat_id,
                        'bot'=>&$bot
                    ]);
                }else{
                    $controller->commandError($chat_id, $bot);
                }

            }elseif($prevCommand){
//                if($prevCommand->value=='room'){
//                    $this->prevCommandRoom($chat_id, $bot);
//                }
//                if($prevCommand->value=='car'){
//                    $this->prevCommandCar($chat_id, $bot);
//                }
//                if($prevCommand->value=='car_notify'){
//                    $this->prevCommandCar_notify($chat_id, $bot);
//                }
//                if(strpos($prevCommand->value,'task')===0){
//                    $this->callback_query=explode('/',$prevCommand->value);
//                    $this->callback_query[5]='createWithDescription';
//                    $this->callbackTask($chat_id, $bot);
//                }
            }

            $bot->currentContactCommand(function ($message) use ($chat_id,$bot,$controller) {
                $controller->commandContact($chat_id, $bot,$message);
            });

            $bot->run();

        } catch (\TelegramBot\Api\Exception $e) {
            $e->getMessage();
        }

        exit();

    }

//    public function prevCommandRoom($chat_id, $bot){
//        $room_num=$this->inLog->value;
////                if(strpos($room_num,'-')) $room_num=((int)$room_num).'-а';
//
//        $rooms=Rooms::find()->where("`appart_num` like '".$room_num."'");
//        $bot->sendMessage($chat_id,'Найдены такие квартиры:');
//        foreach ($rooms->all() as $room){
//            $bot->sendMessage($chat_id,$room->appart_num);
//            $bot->sendMessage($chat_id,"Баланс: ".$room->getBalance().'грн.');
//            $bot->sendMessage($chat_id,"Пользователи: ");
//            foreach ($room->users as $user){
//                if($user->role==10)
//                    $bot->sendMessage($chat_id,$user->username." / ".$user->generated_password);
//            }
//            $bot->sendMessage($chat_id,"Собственники: ");
//            foreach ($room->owners as $owner){
//                $bot->sendMessage($chat_id,$owner->fullName." тел.".$owner->phone);
//            }
//            $bot->sendMessage($chat_id,"-----------------------------------");
//        }
//
//    }
//
//    public function prevCommandCar($chat_id, $bot){
//        $room_num=CarsNumbers::prepare($this->inLog->value);
//        if($room_num){
//            $carNumbers=CarsNumbers::findRest()->where("`number` like '%".$room_num."%'");
//            $bot->sendMessage($chat_id,'Найдены такие машины: ('.$room_num.')');
//            foreach ($carNumbers->all() as $number){
//                $room=$number->room;
//                $bot->sendMessage($chat_id,$number->number.' '.$number->getModelName().' '.$number->color);
//                $bot->sendMessage($chat_id,'Квартира: '.$room->appart_num);
//                $first=true;
//                foreach ($room->users as $user){
//                    if($user->phone){
//                        if($first){
//                            $bot->sendMessage($chat_id,"Пользователи: ");
//                            $first=false;
//                        }
//                        $bot->sendMessage($chat_id,$user->getFullName()." тел.".$user->phone);
//                    }
//                }
//                $bot->sendMessage($chat_id,"Собственники: ");
//                foreach ($room->owners as $owner){
//                    $bot->sendMessage($chat_id,$owner->fullName." тел.".$owner->phone);
//                }
//                $bot->sendMessage($chat_id,"-----------------------------------");
//            }
//        }
//
//    }
//    public function prevCommandCar_notify($chat_id, $bot){
//        $parts=explode('-',$this->inLog->value);
//        $room_num=CarsNumbers::prepare(trim($parts[0]));
//
//        if(strlen($room_num)<3 && count($parts)>=2){
//            $bot->sendMessage($chat_id,"Введите номер авто и сообщение в формате: (номер авто - ваше сообщение)");
//            return false;
//        }
//
//        if($room_num){
//            $carNumbers=CarsNumbers::findRest()->where("`number` like '%".$room_num."%'")->all();
//            if(count($carNumbers)==1){
//                $not_send=0;
//                foreach ($carNumbers as $number){
//                    $telegramAccounts=$number->room->getTelegramAccounts();
//                    if(!$telegramAccounts) $not_send++;
//                    else{
//                        foreach ($telegramAccounts as $telegramAccount){
//                            $telegramAccount->sendMessage('Пользователь telegram бота хочет сообщить вам о проблеме с вашим авто.');
//                            $telegramAccount->sendMessage($this->inLog->value);
//                        }
//
//                    }
//                }
//            }else{
//
//            }
//        }
//
//        $additional='';
//        if(count($carNumbers)) $additional=' и отправили всем ваше сообщение';
//        if($not_send) $additional=', но, к сожалению, '.$not_send.' человек не получил ваше сообщение, т.к. не использует telegram бота';
//        $bot->sendMessage($chat_id, "Мы нашли ".count($carNumbers).' авто в нашей базе с таким номером'.$additional);
//    }

    public function commandEmpty($chat_id,$bot){
        $bot->sendMessage($chat_id, "⠀\n⠀\n⠀\n⠀");
    }
    public function callbackError($chat_id,$bot){
        $bot->sendMessage($chat_id, "Ошибка...");
    }

    public function commandError($chat_id,$bot){
        $bot->sendMessage($chat_id, "Такая команда не найдена.");
        $this->commandMenu($chat_id, $bot);
    }

//    public function callbackMenu($chat_id,$bot){
//        $keyboard = new \TelegramBot\Api\Types\Inline\InlineKeyboardMarkup([
//            [
//                ['text' => 'Контакты ОСМД', 'callback_data' => 'contacts'],
//                ['text' => 'Состояние счета', 'callback_data' => 'balance'],
//            ],
//            [
//                ['text' => 'Вход в личный кабинет', 'callback_data' => 'login']
//            ]
//        ]);
//
//        $this->deletePreviousMenus($chat_id, $bot);
//
//        $bot->sendMessage($chat_id, "Выберите действие:", null, false, null, $keyboard);
//    }

//    public function callbackTask($chat_id,$bot){
//        $telegram=$this->getAccount($chat_id);
//
//        if(!$telegram) {
//            $this->commandAuth($chat_id, $bot);
//            return false;
//        }
//
//        $this->deletePreviousMenus($chat_id,$bot);
//
//        $selectedType=false;
//        if(isset($this->callback_query[1])){
//            $selectedType=IssuesTypes::find()->where(['status'=>1,'id'=>$this->callback_query[1]])->one();
//        }
//        $selectedEntr=false;
//        if(isset($this->callback_query[2])){
//            $selectedEntr=(int)$this->callback_query[2];
//        }
//        if($selectedType && !$selectedType->is_entrance){
//            $selectedEntr=0;
//        }
//
//        $selectedFl=false;
//        if(isset($this->callback_query[3])){
//            $selectedFl=(int)$this->callback_query[3];
//        }
//        if($selectedType && !$selectedType->is_floor){
//            $selectedFl=0;
//        }
//
//        $selectedRoom=false;
//
//
//
//        if(isset($this->callback_query[4])){
//            $selectedRoom=$this->callback_query[4];
//        }
//
//        if($selectedType && !$selectedType->is_room){
//            $selectedRoom=0;
//        }else{
//            $rooms=$telegram->getRooms();
//            if(count($rooms)==1){
//                $selectedRoom=$rooms[0]->id;
//            }
//        }
//
//        $lastCommand=false;
//        if(isset($this->callback_query[5])){
//            $lastCommand=$this->callback_query[5];
//        }
//
//
//        if($selectedType!==false){
//            if($selectedEntr!==false){
//                if($selectedFl!==false){
//                    if($selectedRoom!==false) {
//                        if($lastCommand!==false){
//                            if($lastCommand=='create'){
//                                $message="Добавить заявку: ".$selectedType->title;
//                                if($selectedEntr){
//                                    $ent=Labels::findOne(['id'=>$selectedEntr]);
//                                    $message.=" ".$ent->name;
//                                }
//                                if($selectedFl){
//                                    $flr=Labels::findOne(['id'=>$selectedFl]);
//                                    $message.=" ".$flr->name;
//                                }
//                                if($selectedRoom) {
//                                    $rm=Rooms::findOne(['id'=>$selectedRoom]);
//                                    $message.=" ".$rm->appart_num;
//                                }
//
//                                $items=[[
//                                    ['text' => 'Подтверждаю', 'callback_data' => 'task/'.$selectedType->id.'/'.$selectedEntr.'/'.$selectedFl.'/'.$selectedRoom.'/save'],
//                                    ['text' => 'Отмена', 'callback_data' => 'menumain'],
//                                ]];
//                                $keyboard = new \TelegramBot\Api\Types\Inline\InlineKeyboardMarkup($items);
//
//
//                                $this->deletePreviousMessages($chat_id, $bot, 'task_menu');
//                                $message = $bot->sendMessage($chat_id, $message, null, false, null, $keyboard);
//                                $this->addOutputLogMessage($message, 'task_message');
//                            }elseif($lastCommand=='createWithDescription'){
//                                $message="Добавить заявку: ".$selectedType->title;
//                                if($selectedEntr){
//                                    $ent=Labels::findOne(['id'=>$selectedEntr]);
//                                    $message.=" ".$ent->name;
//                                }
//                                if($selectedFl){
//                                    $flr=Labels::findOne(['id'=>$selectedFl]);
//                                    $message.=" ".$flr->name;
//                                }
//                                if($selectedRoom) {
//                                    $rm=Rooms::findOne(['id'=>$selectedRoom]);
//                                    $message.=" ".$rm->appart_num;
//                                }
//
//
//                                $lastTaskId=TelegramLog::find()->where([
//                                    'chat_id'=>$chat_id,
//                                    'message_type'=>self::MESSAGE_TYPE_CALLBACK,
//                                    'value'=>'task'
//                                ])->orderBy('id desc')->one();
//                                $lastTaskId=$lastTaskId->id;
//
//                                $messages=TelegramLog::find()->where([
//                                    'chat_id'=>$chat_id,
//                                    'message_type'=>[self::MESSAGE_TYPE_TEXT, self::MESSAGE_TYPE_PHOTO]
//                                ])->andWhere(['>','id',$lastTaskId])->orderBy('id')->all();
//
//                                $msgs=[];
//                                foreach ($messages as $mess){
//                                    if($mess->value) $msgs[]=$mess->value;
//                                }
//
//                                $message.=" ".implode(', ',$msgs);
//
//                                $items=[[
//                                    ['text' => 'Подтверждаю', 'callback_data' => 'task/'.$selectedType->id.'/'.$selectedEntr.'/'.$selectedFl.'/'.$selectedRoom.'/saveWithDescription'],
//                                    ['text' => 'Отмена', 'callback_data' => 'menumain'],
//                                ]];
//                                $keyboard = new \TelegramBot\Api\Types\Inline\InlineKeyboardMarkup($items);
//
//
//                                $this->deletePreviousMessages($chat_id, $bot, 'task_menu');
//                                $this->deletePreviousMessages($chat_id, $bot, 'task_confirmation');
//                                $message = $bot->sendMessage($chat_id, $message, null, false, null, $keyboard);
//                                $this->addOutputLogMessage($message, 'task_confirmation');
//                            }elseif($lastCommand=='save'){
//                                $issue=new Issues();
//
//                                $rooms=$telegram->getRooms();
//                                if($rooms){
//                                    $issue->from_user=$rooms[0]->id;
//                                }
//
//                                $issue->type=$selectedType->id;
//                                $issue->entrance=$selectedEntr;
//                                $issue->floor=$selectedFl;
//                                $issue->room=$selectedRoom;
//                                $issue->phone=$telegram->phone;
//                                $issue->name=$telegram->name;
//                                $issue->save();
//
//                                $issue->sendToAdmins();
//
//                                $this->deletePreviousMessages($chat_id, $bot, 'task_menu');
//                                $message = $bot->sendMessage($chat_id, "Ваша заявка создана");
//                                $this->addOutputLogMessage($message, 'task_message');
//                                $this->commandMenu($chat_id, $bot);
//                            }elseif($lastCommand=='saveWithDescription'){
//                                $issue=new Issues();
//
//                                $rooms=$telegram->getRooms();
//                                if($rooms){
//                                    $issue->from_user=$rooms[0]->id;
//                                }
//
//                                $issue->type=$selectedType->id;
//                                $issue->entrance=$selectedEntr;
//                                $issue->floor=$selectedFl;
//                                $issue->room=$selectedRoom;
//                                $issue->phone=$telegram->phone;
//                                $issue->name=$telegram->name;
//
//                                $lastTaskId=TelegramLog::find()->where([
//                                    'chat_id'=>$chat_id,
//                                    'message_type'=>self::MESSAGE_TYPE_CALLBACK,
//                                    'value'=>'task'
//                                ])->orderBy('id desc')->one();
//                                $lastTaskId=$lastTaskId->id;
//
//                                $messages=TelegramLog::find()->where([
//                                    'chat_id'=>$chat_id,
//                                    'message_type'=>[self::MESSAGE_TYPE_TEXT, self::MESSAGE_TYPE_PHOTO],
//                                ])->andWhere(['>','id',$lastTaskId])->orderBy('id')->all();
//
//                                $msgs=[];
//                                foreach ($messages as $mess){
//                                    if($mess->value) $msgs[]=$mess->value;
//                                }
//
//                                $issue->description=implode(', ',$msgs);//$this->prevCommands[count($this->prevCommands)-1];
//                                $issue->save();
//
//                                $messages=TelegramLog::find()->where([
//                                    'chat_id'=>$chat_id,
//                                    'message_type'=>self::MESSAGE_TYPE_PHOTO,
//                                ])->andWhere(['>','id',$lastTaskId])->orderBy('id')->all();
//
//                                $this->deletePreviousMessages($chat_id, $bot, 'task_menu');
//                                $this->deletePreviousMessages($chat_id, $bot, 'task_confirmation');
//                                $message = $bot->sendMessage($chat_id, "Ваша заявка создана: ".$issue->getTelegramDescription());
//                                $this->addOutputLogMessage($message, 'task_message');
//                                $this->commandMenu($chat_id, $bot);
//
//                                if(count($messages)){
//                                    foreach ($messages as $photos){
//                                        $files=json_decode($photos->files, true);
//                                        foreach ($files as $file_id){
//                                            $fileInfo=file_get_contents("https://api.telegram.org/bot".Config::getParameter('telegram_token', false)."/getFile?file_id=".$file_id);
//                                            $fileInfo=json_decode($fileInfo, true);
//                                            if(isset($fileInfo["result"]) && isset($fileInfo["result"]["file_path"])){
//                                                $filePath=$fileInfo["result"]["file_path"];
//                                                $image=file_get_contents("https://api.telegram.org/file/bot".Config::getParameter('telegram_token', false)."/".$filePath);
//                                                $dbImage=new Image();
//                                                $dbImage->type=Issues::$imageTypes[0];
//                                                $dbImage->item_id=$issue->id;
//
//                                                $name=explode('/',$filePath);
//                                                $name=$name[count($name)-1];
//
//                                                $ext=explode('.',$name);
//                                                $ext=$ext[count($ext)-1];
//
//                                                $dbImage->name=$name;
//                                                $dbImage->ext=$ext;
//                                                $dbImage->save();
//                                                file_put_contents($_SERVER['DOCUMENT_ROOT'].'/images/source/'.$dbImage->id.'.'.$dbImage->ext, $image);
//                                            }
//                                        }
//                                    }
//                                }
//
//                                $issue->sendToAdmins();
//
//
//
//                            }elseif($lastCommand=='addDescription'){
//                                $this->deletePreviousMessages($chat_id, $bot, 'task_menu');
//                                $message = $bot->sendMessage($chat_id, "Добавьте ваше описание");
//                                $this->addOutputLogMessage($message, 'task_menu');
//                            }
//                        }else{
//
//                            $items=[[
//                                ['text' => 'Да', 'callback_data' => 'task/'.$selectedType->id.'/'.$selectedEntr.'/'.$selectedFl.'/'.$selectedRoom.'/addDescription'],
//                                ['text' => 'Нет, создать заявку', 'callback_data' => 'task/'.$selectedType->id.'/'.$selectedEntr.'/'.$selectedFl.'/'.$selectedRoom.'/create']
//                            ]];
//                            $keyboard = new \TelegramBot\Api\Types\Inline\InlineKeyboardMarkup($items);
//
//                            $this->deletePreviousMessages($chat_id, $bot, 'task_menu');
//                            $message = $bot->sendMessage($chat_id, "Добавить описание?", null, false, null, $keyboard);
//                            $this->addOutputLogMessage($message, 'task_menu');
//                        }
//                    }else{
//                        $rooms=$telegram->getRooms();
//
//                        $items=[];
//                        foreach ($rooms as $room){
//                            $items[]=[
//                                ['text' => $room->appart_num, 'callback_data' => 'task/'.$selectedType->id.'/'.$selectedEntr.'/'.$selectedFl.'/'.$room->id]
//                            ];
//                        }
//                        $keyboard = new \TelegramBot\Api\Types\Inline\InlineKeyboardMarkup($items);
//
//                        $this->deletePreviousMessages($chat_id, $bot, 'task_menu');
//                        $message = $bot->sendMessage($chat_id, "Какая квартира?", null, false, null, $keyboard);
//                        $this->addOutputLogMessage($message, 'task_menu');
//                    }
//                }else{
//                    $items=[];
//                    $labels=Labels::find()->where(['in', 'id', explode(',', Config::getParameter('floor_labels', false))])->orderBy('sort, name');
//                    foreach ($labels->all() as $label){
//                        $items[]=[
//                            ['text' => $label->name, 'callback_data' => 'task/'.$selectedType->id.'/'.$selectedEntr.'/'.$label->id]
//                        ];
//                    }
//                    $keyboard = new \TelegramBot\Api\Types\Inline\InlineKeyboardMarkup($items);
//
//                    $this->deletePreviousMessages($chat_id, $bot, 'task_menu');
//                    $message = $bot->sendMessage($chat_id, "Какой этаж?", null, false, null, $keyboard);
//                    $this->addOutputLogMessage($message, 'task_menu');
//                }
//            }else{
//                $items=[];
//                $labels=Labels::find()->where(['in', 'id', explode(',', Config::getParameter('split_labels', false))])->orderBy('name');
//                foreach ($labels->all() as $label){
//                    $items[]=[
//                        ['text' => $label->name, 'callback_data' => 'task/'.$selectedType->id.'/'.$label->id]
//                    ];
//                }
//                $keyboard = new \TelegramBot\Api\Types\Inline\InlineKeyboardMarkup($items);
//
//                $this->deletePreviousMessages($chat_id, $bot, 'task_menu');
//                $message = $bot->sendMessage($chat_id, "Какой ".mb_strtolower(Config::getParameter('entrance_title', true, "подъезд"))."?", null, false, null, $keyboard);
//                $this->addOutputLogMessage($message, 'task_menu');
//            }
//        }else{
////            $this->mediaCache=\Yii::$app->cache->get('telegramMedia');
////            $this->mediaCache[$chat_id]=[];
////            \Yii::$app->cache->set('telegramMedia',$this->mediaCache);
//
//
//
//            $items=[];
//            $types=IssuesTypes::find()->where(['status'=>1]);
//            foreach ($types->all() as $type){
//                $items[]=[
//                    ['text' => $type->title, 'callback_data' => 'task/'.$type->id]
//                ];
//            }
//            $keyboard = new \TelegramBot\Api\Types\Inline\InlineKeyboardMarkup($items);
//
//            $this->deletePreviousMessages($chat_id, $bot, 'task_menu');
//            $message = $bot->sendMessage($chat_id, 'Создание заявки', null, false, null);
//            $this->addOutputLogMessage($message, 'task_message');
//            $message = $bot->sendMessage($chat_id, "Что у вас случилось?", null, false, null, $keyboard);
//            $this->addOutputLogMessage($message, 'task_menu');
//        }
//    }



//    public function callbackContacts($chat_id,$bot){
//        $keyboard = new \TelegramBot\Api\Types\Inline\InlineKeyboardMarkup([
//            [
//                ['text' => 'Председатель', 'callback_data' => 'ceo'],
////                ['text' => 'Слесарь', 'callback_data' => 'ceo']
//            ]
////            ,[
////                ['text' => 'Электрик', 'callback_data' => 'ceo']
////            ]
//        ]);
//
//        $bot->sendMessage($chat_id, "Кому звонить?", null, false, null, $keyboard);
//    }

//    public function callbackLogin($chat_id,$bot){
//        $this->commandLogin($chat_id, $bot);
//    }


//    public function callbackBalance($chat_id,$bot){
//        $keyboard = new \TelegramBot\Api\Types\Inline\InlineKeyboardMarkup([
//            [
//                ['text' => 'Сводная инфо', 'callback_data' => 'room_info'],
//                ['text' => 'Выписка', 'callback_data' => 'detailed_info']
//            ]
//        ]);
//
//        $bot->sendMessage($chat_id, "Подробно или нет?", null, false, null, $keyboard);
//    }
//    public function callbackRoom_info($chat_id,$bot){
//        $this->commandInfo($chat_id, $bot);
//    }

//    public function callbackCar_notify($chat_id,$bot){
//        $bot->sendMessage($chat_id, "Введите номер авто и описание (AX0000AX - ваше описание), мы отправим его собственнику авто и предупредим о проблеме");
//    }
//    public function callbackDetailed_info($chat_id,$bot){
//        $this->commandDetail($chat_id, $bot);
//    }

//    public function callbackPay($chat_id,$bot){
//        $this->commandPay($chat_id, $bot);
//    }
//    public function callbackCeo($chat_id,$bot){
//        $this->commandCeo($chat_id, $bot);
//    }
//
//    public function callbackRoom($chat_id,$bot){
//        $this->commandRoom($chat_id, $bot);
//    }
//
//    public function callbackCar($chat_id,$bot){
//        $this->commandCar($chat_id, $bot);
//    }


    public function callbackMenumain($chat_id,$bot)
    {
        $this->commandMenu($chat_id, $bot);
    }

//    public function callbackIssues_list($chat_id,$bot)
//    {
//        $telegramBot=$this->getAccount($chat_id);
//
//        if(!$telegramBot) {
//            $this->commandAuth($chat_id, $bot);
//            return false;
//        }
//
//        if($telegramBot->is_admin()){
//            $issues=Issues::find()->where('(status!=2 AND status!=3) OR status IS NULL')->all();
//            if($issues){
//                foreach ($issues as $issue){
//                    $bot->sendMessage($chat_id, $issue->getTelegramDescription());
//                }
//            }
//        }else{
//            $bot->sendMessage($chat_id, "У вас нет доступа для этого.");
//            $this->commandMenu($chat_id, $bot);
//        }
//    }

    public function commandMenu($chat_id,$bot){
//        $telegram=$this->getAccount($chat_id);
//        $items=[];
//
//        $row=[];
//        $row[]=['text' => 'Меню', 'callback_data' => 'menu'];
//        if(Config::getParameter('liqpay_public_key',false) || Config::getParameter('wayforpay_merchantAccount', false)) $row[]=['text' => 'Оплатить', 'callback_data' => 'pay'];
//        $row[]=['text' => 'Заявка', 'callback_data' => 'task'];
//
//        $items[]=$row;
//        $items[]=[
//            ['text' => 'Предупредить собственника авто', 'callback_data' => 'car_notify']
//        ];
//        if($telegram && $telegram->is_admin()){
//            $items[]=[
//                ['text' => 'Проверка квартиры', 'callback_data' => 'room'],
//                ['text' => 'Найти Авто', 'callback_data' => 'car'],
//                ['text' => 'Список заявок', 'callback_data' => 'issues_list'],
//            ];
//        }
//        $keyboard = new \TelegramBot\Api\Types\Inline\InlineKeyboardMarkup($items);
//
//        $this->deletePreviousMenus($chat_id, $bot);
//        $this->deletePreviousMessages($chat_id, $bot, 'task_confirmation');
//
//        $message = $bot->sendMessage($chat_id, "Что то еще?", null, false, null, $keyboard);
//        $this->addOutputLogMessage($message, 'menu');
    }

//    public function commandRoom($chat_id, $bot){
//        $telegramBot=$this->getAccount($chat_id);
//
//        if(!$telegramBot) {
//            $this->commandAuth($chat_id, $bot);
//            return false;
//        }
//
//        if($telegramBot->is_admin()){
//            $msg="Введите номер квартиры:";
//            $bot->sendMessage($chat_id, $msg);
//        }else{
//            $bot->sendMessage($chat_id, "У вас нет доступа для этого.");
//            $this->commandMenu($chat_id, $bot);
//        }
//    }
//
//    public function commandCar($chat_id, $bot){
//        $telegramBot=$this->getAccount($chat_id);
//
//        if(!$telegramBot) {
//            $this->commandAuth($chat_id, $bot);
//            return false;
//        }
//
//        if($telegramBot->is_admin()){
//            $msg="Введите номер авто:";
//            $bot->sendMessage($chat_id, $msg);
//        }else{
//            $bot->sendMessage($chat_id, "У вас нет доступа для этого.");
//            $this->commandMenu($chat_id, $bot);
//        }
//    }



    public function commandContact($chat_id, $bot, $message)
    {
        $phoneUtil = PhoneNumberUtil::getInstance();
        $phone=$message->getContact()->getPhoneNumber();
        $numberProto = $phoneUtil->parse('+'.$phone, "UA");
        $phone=$phoneUtil->format($numberProto, PhoneNumberFormat::INTERNATIONAL);

        $telegramBot=false;
        $user=Identity::findOne(['phone'=>$phone]);
        $telegramBot=TelegramBot::findOne(['user_id'=>$user->id]);
        if(!$telegramBot && $user){
            $telegramBot=new TelegramBot();
            $telegramBot->chat_id=$chat_id;
            $telegramBot->user_id=$user->id;
            $telegramBot->phone=$numberProto;
            $telegramBot->save();
        }

        if($telegramBot){
            $this->allreadyAuth($telegramBot, $chat_id, $bot);
            $this->commandMenu($chat_id, $bot);
        }else{
            $bot->sendMessage($chat_id, "Ваш номер телефона не найден в системе. Для того чтоб исправить это, войдите на сайт ".Url::to(['/auth/login'], true)." и добавьте номер телефона в свой профиль.");
            $this->commandTutorial($chat_id, $bot);
            $this->commandMenu($chat_id, $bot);
        }
    }

    public function allreadyAuth($telegramBot,$chat_id,$bot){
        $msg="Теперь вы в системе.";

        $keyboard = new ReplyKeyboardRemove(true, false);

        $bot->sendMessage($chat_id, $msg, null, false, null, $keyboard);
    }


    public function getAccount($chat_id){
        if($chat_id)
            return TelegramBot::findOne(['chat_id'=>$chat_id]);
        else
            return null;
    }

//    public function commandInfo($chat_id, $bot){
//        $telegramBot=$this->getAccount($chat_id);
//
//        if(!$telegramBot) {
//            $this->commandAuth($chat_id, $bot);
//            return false;
//        }
//
//        $msg="Ваши помещения:";
//        foreach ($telegramBot->rooms as $room){
//            $msg.="\n".$room->appart_num." (".$room->square."кв.м.) Баланс: ".$room->balance." грн.";
//        }
//        $bot->sendMessage($chat_id, $msg);
//        $this->commandMenu($chat_id, $bot);
//    }
//
//    public function commandLogin($chat_id, $bot){
//        $telegramBot=$this->getAccount($chat_id);
//
//        if(!$telegramBot) {
//            $this->commandAuth($chat_id, $bot);
//            return false;
//        }
//
//        $keyboard = new \TelegramBot\Api\Types\Inline\InlineKeyboardMarkup(
//            [
//                [
//                    ['text' => 'Вход', 'url' => Url::to(['site/autologin','token'=>$telegramBot->getLoginToken()],true)]
//                ]
//            ]
//        );
//
//        $msg="Ссылка для входа в личный кабинет:";
//
//        $bot->sendMessage($chat_id, $msg, null, false, null, $keyboard);
//        $this->commandMenu($chat_id, $bot);
//    }

//    public function commandPay($chat_id, $bot){
//        $telegramBot=$this->getAccount($chat_id);
//
//        if(!$telegramBot) {
//            $this->commandAuth($chat_id, $bot);
//            return false;
//        }
//
//        $keyboard = new \TelegramBot\Api\Types\Inline\InlineKeyboardMarkup(
//            [
//                [
//                    ['text' => 'Оплатить', 'url' => Url::to(['site/autopay','token'=>$telegramBot->getLoginToken()],true)]
//                ]
//            ]
//        );
//
//
//        $msg="Ссылка для оплаты из личного кабинета";
//
//        $bot->sendMessage($chat_id, $msg, null, false, null, $keyboard);
//        $this->commandMenu($chat_id, $bot);
//    }


//    public function commandDetail($chat_id, $bot){
//        $telegramBot=$this->getAccount($chat_id);
//
//        if(!$telegramBot) {
//            $this->commandAuth($chat_id, $bot);
//            return false;
//        }
//
//        $roomIds=$telegramBot->roomIds;
//        $bal = RoomBalance::find()->orderBy('id desc')->where([
//            'room_id'=>$roomIds
//        ])->orderBy("id desc")->limit(20)->all();
//
//
//        $msg="Информация о платежах и начислениях:\n";
//        $bot->sendMessage($chat_id, $msg);
//        foreach ($bal as $b){
//            $msg="\n".date("Y-m-d",strtotime($b->created_at)).' Квартира: '.$b->room->appart_num.', Сумма: '.number_format($b->value,2,'.',' ').'грн. ';//.$b->comment."\n";
//            $bot->sendMessage($chat_id, $msg);
//        }
//        $bot->sendMessage($chat_id, "Более детальная информация в личном кабинете");
//        $this->commandLogin($chat_id, $bot);
//
//    }



    public function commandStart($chat_id, $bot){
        $auth=$this->commandAuth($chat_id, $bot);
        if($auth===true) $this->commandMenu($chat_id, $bot);
    }

    public function commandAuth($chat_id, $bot){
        $telegramBot=$this->getAccount($chat_id);

        if($telegramBot) {
            $this->allreadyAuth($telegramBot,$chat_id, $bot);
            return true;
        }

        $keyboard = new ReplyKeyboardMarkup([[
            ['text'=>'Отправить номер телефона','request_contact'=>true,'row_width'=>1],
        ]],true, true);

        $bot->sendMessage($chat_id, 'Нажмите кнопку ниже и отправьте свой номер телефона', null, false, null, $keyboard);
    }
    public function commandPing($chat_id, $bot){
        $bot->sendMessage($chat_id, 'pong!');
        $this->commandMenu($chat_id, $bot);
    }

//    public function commandCeo($chat_id, $bot){
//        $name=Config::getParameter('ceo_name', false);
//        $name=explode(' ', $name);
//        $phone=Config::getParameter('ceo_phone', false);
//        $phone=str_replace(['(',')',' '],['','',''], $phone);
//        $bot->sendContact($chat_id, $phone, $name[1]." ".$name[2], $name[0]);
//        $this->commandMenu($chat_id, $bot);
//    }

    public function commandTutorial($chat_id, $bot){
        $bundle=AppAsset::register(\Yii::$app->view);

        $bot->sendPhoto($chat_id,Url::to($bundle->baseUrl.'/telegram_5.png', true));
//        $this->commandMenu($chat_id, $bot);
    }

//    public function commandHowto($chat_id, $bot){
//        $bot->sendPhoto($chat_id,'https://bid.molodizhne-mistechko.kh.ua/tutorialImages/3.png');
//        $bot->sendPhoto($chat_id,'https://bid.molodizhne-mistechko.kh.ua/tutorialImages/4.png');
//        $this->commandMenu($chat_id, $bot);
//    }

    public function actionSetTelegramWebhook(){
        $token=Config::getParameter('telegram_token', false);
        $url='https://api.telegram.org/bot'.$token.'/setWebhook?url='.urlencode(Url::to(['/telegram/api/index','tk'=>$token], true));
        file_get_contents($url);
        Yii::$app->session->setFlash('success','Webhook установлен');
        if(Yii::$app->request->get('return')) return $this->redirect(Yii::$app->request->get('return'));
        return $this->redirect('index');
    }

}
