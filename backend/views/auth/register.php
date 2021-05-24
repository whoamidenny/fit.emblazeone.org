<?php

use backend\assets\AppAsset;
use yii\bootstrap\ActiveForm;

//$this->title = 'Login';
//$this->params['breadcrumbs'][] = $this->title;

$this->registerJs('
    $("#sign-in-form").on("beforeValidate", function(){
        if(!$(".uploadifive-files .image").length){
            $("#documents").show();
            return false;
        }else{
            $("#documents").hide();
        }
        return true;
    });
',\yii\web\View::POS_READY);

$bundle = AppAsset::register($this);
?>
<?php $form = ActiveForm::begin([
        'options'=>['id'=>'sign-in-form','class' => 'sign-in-form'],
        'enableAjaxValidation' => true,
]); ?>
    <div class="card">
        <div class="card-body">
            <span class="brand text-center d-block m-b-20 text-success" style="font-size: 15px;">
                <? if($logo_url=\app\models\Config::getParameter('login_logo_url',false)){ ?>
                    <img src="<?=$bundle->baseUrl.$logo_url?>" style="max-height: 80px;" alt="" />
                <? } ?>
                <div class="clearfix"></div>
                <?=\app\models\Config::getParameter('title')?>
            </span>
            <h5 class="sign-in-heading text-center m-b-20"><?=Yii::t('login', 'Create new account')?></h5>

            <?= $form->field($model, 'username')->textInput(['placeholder' => Yii::t('login', 'Your Username')])->label(Yii::t('login', 'Username'),["class"=>"sr-only"]); ?>
            <?= $form->field($model, 'phone')->textInput(['placeholder' => '+380 XX XXX XXXX'])->label(Yii::t('login', 'Phone'),["class"=>"sr-only"]); ?>
            <?= $form->field($model, 'email')->textInput(['placeholder' => Yii::t('login', 'Your Email')])->label(Yii::t('login', 'Email'),["class"=>"sr-only"]); ?>
            <?= $form->field($model, 'password')->passwordInput(['placeholder' => Yii::t('login', 'Your Password')])->label(Yii::t('login', 'Password'),["class"=>"sr-only"]) ?>
            <?= $form->field($model, 'password_confirmation')->passwordInput(['placeholder' => Yii::t('login', 'Password Confirmation')])->label(Yii::t('login', 'Password Confirmation'),["class"=>"sr-only"]) ?>

            <?=$form->field($model,'rooms')->dropDownList(\app\models\Rooms::getList('id','numberWithDescription', 'sort, id', '`erc_disabled`=0 OR `erc_disabled` IS NULL'), ['value'=>$model->rooms,'id'=>'rooms_ids','multiple'=>'multiple','style'=>'width:100%;']);?>
            <? $this->registerJs('$("#rooms_ids").select2();', \yii\web\View::POS_END)?>

            <?= $form->field($model, 'first_name')->textInput(['placeholder' => Yii::t('login', 'Your First Name')])->label(Yii::t('login', 'First Name'),["class"=>"sr-only"]); ?>
            <?= $form->field($model, 'last_name')->textInput(['placeholder' => Yii::t('login', 'Your Last Name')])->label(Yii::t('login', 'Last Name'),["class"=>"sr-only"]); ?>
            <?= $form->field($model, 'middle_name')->textInput(['placeholder' => Yii::t('login', 'Your Middle Name')])->label(Yii::t('login', 'Middle Name'),["class"=>"sr-only"]); ?>

            <div class="row">
                <div class="col-md-12 font-size-12">
                    Загрузите документы подтверждающие право собственности (документ купли-продажи, свидетельство о регистрации, договор дарования), выписку из реестра
                </div>
                <div class="col-md-12">
                    <?=\wbp\imageUploader\ImageUploader::widget([
                        'style' => 'estoreMultiple_110',
                        'data' => [
                            'size' => '110x110',
                        ],
                        'type' => \app\models\Identity::$imageTypes[1],
                        'item_id' => null,
                        'limit' => 999
                    ])?>
                </div>
                <div class="col-md-12 has-error">
                    <p class="help-block help-block-error" id="documents" style="display: none;">Необходимо загрузить документы.</p>
                </div>
            </div>
            <div class="h-25"></div>

            <?= $form->field(
                $model,
                'agree',
                [
                    'options'=>[
                        'class'=>'checkbox',
                    ]
                ])->checkbox([
                'template' => "<div class=\"custom-control custom-checkbox checkbox-primary form-check\">\n{input}\n{beginLabel}\n{labelTitle}\n{endLabel}\n</div>\n{error}\n{hint}",
                'class'=>'custom-control-input'
            ])->label(
                'Я соглашаюсь на обработку моих персональных данных. Ознакомьтесь с <a href="#"  data-toggle="modal" data-target="#policy">политикой конфиденциальности</a>',//Yii::t('login', ''),
                ['class'=>'custom-control-label']
            ) ?>

            <button class="btn btn-primary btn-rounded btn-floating btn-lg btn-block" type="submit"><?=Yii::t('login', 'Signup')?></button>
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
