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
<!--                <span>--><?//=\common\models\Config::getParameter('title')?><!--</span>-->
            </span>
            <h5 class="sign-in-heading text-center m-b-20"><?=Yii::t('login', 'Sign in to your account')?></h5>

            <?= $form->field($model, 'username')->textInput(['placeholder' => Yii::t('login', 'Your Username')])->label(Yii::t('login', 'Username'),["class"=>"sr-only"]); ?>
            <?= $form->field($model, 'password')->passwordInput(['placeholder' => Yii::t('login', 'Your Password')])->label(Yii::t('login', 'Password'),["class"=>"sr-only"]) ?>

            <a href="<?=\yii\helpers\Url::to(['auth/repass'])?>" class="float-right repass_link"><?=Yii::t('login', 'Forgot Password?')?></a>
            <button class="btn btn-primary btn-rounded btn-floating btn-block" style="margin-bottom: 15px;" type="submit"><?=Yii::t('login', 'Sign In')?></button>

            <?$form->field(
                    $model,
                    'agree',[
                        'options'=>[
                            'class'=>'checkbox',
                        ]
                    ])->checkbox([
                        'template' => "<div class=\"custom-control custom-checkbox checkbox-primary form-check\">\n{input}\n{beginLabel}\n{labelTitle}\n{endLabel}\n</div>\n{error}\n{hint}",
                        'class'=>'custom-control-input'
                    ])->label(
                        Yii::t('login','I agree to the processing of my personal data. Check out the <a href="#" data-toggle="modal" data-target="#policy"> privacy policy </a>'),
                        ['class'=>'custom-control-label']
                    ) ?>

            <div class="row">
                <div class="col-md-6">
                    <a href="<?=\yii\helpers\Url::to(['auth/auth','authclient'=>'google'])?>" id="google_button"  style="margin-top: 8px;" class="btn btn-rounded btn-primary btn-outline btn-block">
                        <?=$this->render('@backend/web/quantum/google_logo.svg')?>&nbsp;&nbsp;Google
                    </a>
                </div>
                <div class="col-md-6">
                    <a href="<?=\yii\helpers\Url::to(['auth/auth','authclient'=>'facebook'])?>" id="facebook_button" style="margin-top: 8px;" class="btn btn-rounded btn-secondary btn-outline btn-block">
                        <?=$this->render('@backend/web/quantum/facebook.svg')?>&nbsp;&nbsp;Facebook
                    </a>
                </div>
            </div>
        </div>

    </div>
<?php ActiveForm::end(); ?>

<div class="modal" tabindex="-1" id="policy" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Политика конфиденциальности</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?=$this->render('../site/privacy-policy')?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>
