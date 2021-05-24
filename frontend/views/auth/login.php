<?php

use yii\widgets\ActiveForm;

$bundle=\frontend\assets\AppAsset::register($this);
?>

<div class="login_screen">
    <div class="d-flex justify-content-center align-items-center">
        <div class="login_block">
            <a href="<?=\yii\helpers\Url::to(['site/index'])?>>">
                <img src="<?=$bundle->baseUrl?>/images/logo_login.png" alt="logo">
            </a>
            <?php $form = ActiveForm::begin(['options'=>['class' => 'block_white shadow']]); ?>
                <h2>Sign In</h2>

                <?= $form->field($model, 'email')->textInput(['placeholder' => 'Email'])->label(false); ?>
                <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Password'])->label(false) ?>

                <button type="submit" class="btn btn-primary">LOGIN</button>
                <div class="or">or Login with</div>
                <div class="d-flex justify-content-between">
                    <a href="<?=\yii\helpers\Url::to(['auth/auth','authclient'=>'facebook'])?>" class="log_face">
                        <i class="fab fa-facebook-f"></i>
                        facebook
                    </a>
                    <a href="<?=\yii\helpers\Url::to(['auth/auth','authclient'=>'google'])?>" class="log_googl">
                        <i class="fab fa-google-plus-g"></i>
                        Google
                    </a>
                </div>
                <div class="forgot">
                    <a href="<?=\yii\helpers\Url::to(['auth/forgot'])?>">Forgot Password?</a>
                </div>
            <? ActiveForm::end(); ?>
        </div>
    </div>
</div>
