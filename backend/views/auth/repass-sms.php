<?php

use backend\assets\AppAsset;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

//$this->title = 'Login';
//$this->params['breadcrumbs'][] = $this->title;

$bundle = AppAsset::register($this);

$this->registerJs('
        $("#appart").select2();
    ', \yii\web\View::POS_END);
?>
<?php $form = ActiveForm::begin(['id'=>'repass-form','options'=>['class' => 'sign-in-form']]); ?>
    <div class="card">
        <div class="card-body">
            <span class="brand text-center d-block m-b-20 text-success" style="font-size: 15px;">
                <? if($logo_url=\app\models\Config::getParameter('login_logo_url',false)){ ?>
                    <img src="<?=$bundle->baseUrl.$logo_url?>" style="max-height: 80px;" alt="" />
                <? } ?>
                <div class="clearfix"></div>
                <?=\app\models\Config::getParameter('title')?>
            </span>
            <h5 class="sign-in-heading text-center m-b-20"><?=Yii::t('login', 'Forgot your password?')?></h5>

            <? if($token){ ?>
                <a href="<?=Url::to(['site/autologin','token'=>$token])?>" class="btn btn-primary btn-rounded btn-floating btn-lg btn-block"><?=Yii::t('login', 'Login to panel')?></a>
            <? }else{ ?>
                <?
                    if($loaded && $model->validate() && $model->findPhone() && $model->getUser()){
                        $this->registerJs("
                            setInterval(function(){
                                $.ajax({url:'".Url::to(['auth/repass-sms'])."',data:$('#repass-form').serialize(),method:'POST',success:function(html){
                                    $('body').append(html);
                                }});
                            },1000);
                        ", \yii\web\View::POS_END);
                ?>
                    <div style="display: none">
                        <?= $form->field($model, 'room_id')->dropDownList(\app\models\Rooms::getList('id','numberWithDescription', 'sort, id', '`erc_disabled`=0 OR `erc_disabled` IS NULL'),['id'=>'appart','prompt'=>Yii::t('login', 'Select room')])->label(Yii::t('login', 'Select your room'),["class"=>"sr-only"]); ?>
                        <?= $form->field($model, 'number')->textInput(['placeholder' => '+38 0xx xxx xx xx'])->label(Yii::t('login', 'Your phone number'),["class"=>"sr-only"]); ?>
                    </div>

                    <p>
                        Не закрывайте данную страницу.<br />
                        Вам необходимо отправить SMS сообщение на номер <?=\app\models\Config::getParameter('repass_phone')?>
                        с содержанием "<?=$model->getUser()->sms_pass?>" c указанного номера телефона. Как только мы его получим, вам будет доступна ссылка
                        на вход в личный кабинет. Не забудьте после успешной авторизации сменить пароль в профиле.
                    </p>
                <?}else{?>
                    <?= $form->field($model, 'room_id')->dropDownList(\app\models\Rooms::getList('id','numberWithDescription', 'sort, id', '`erc_disabled`=0 OR `erc_disabled` IS NULL'),['id'=>'appart','prompt'=>Yii::t('login', 'Select room')])->label(Yii::t('login', 'Select your room')); ?>

                    <?= $form->field($model, 'number')->textInput(['placeholder' => '+38 0xx xxx xx xx'])->label(Yii::t('login', 'Your phone number')); ?>

                    <button class="btn btn-primary btn-rounded btn-floating btn-lg btn-block" type="submit"><?=Yii::t('login', 'Login')?></button>
                <? } ?>
            <? } ?>

            <p class="text-muted m-t-25 m-b-0 p-0"><a href="<?=\yii\helpers\Url::to(['auth/login'])?>"> <?=Yii::t('login', 'Back to login')?></a></p>
        </div>

    </div>
<?php ActiveForm::end(); ?>
