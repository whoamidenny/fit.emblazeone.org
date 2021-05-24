<?php

use backend\assets\AppAsset;
use yii\bootstrap\ActiveForm;

//$this->title = 'Login';
//$this->params['breadcrumbs'][] = $this->title;

$bundle = AppAsset::register($this);

?>
<?php $form = ActiveForm::begin(['options'=>['class' => 'sign-in-form']]); ?>
    <div class="card">
        <div class="card-body">
            <span class="brand text-center d-block m-b-20 text-success" style="font-size: 15px;">
                <? if($logo_url=\common\models\Config::getParameter('login_logo_url',false)){ ?>
                    <img src="<?=$bundle->baseUrl.$logo_url?>" style="max-height: 80px;" alt="" />
                <? } ?>
                <div class="clearfix"></div>
<!--                --><?//=\common\models\Config::getParameter('title')?>
            </span>
            <h5 class="sign-in-heading text-center m-b-20"><?=Yii::t('login', 'Forgot your password?')?></h5>

            <?= $form->field($model, 'username')->textInput(['placeholder' => Yii::t('login', 'Your Username or Email')])->label(Yii::t('login', 'Username or Email'),["class"=>"sr-only"]); ?>

            <button class="btn btn-primary btn-rounded btn-floating btn-lg btn-block" type="submit"><?=Yii::t('login', 'Reset password')?></button>
            <p class="text-muted m-t-25 m-b-0 p-0"><a href="<?=\yii\helpers\Url::to(['auth/login'])?>"> <?=Yii::t('login', 'Back to login')?></a></p>
        </div>

    </div>
<?php ActiveForm::end(); ?>
