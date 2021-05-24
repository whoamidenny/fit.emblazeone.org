<?

use backend\assets\AppAsset;
use common\models\Identity;

$bundle=AppAsset::register($this);

if(Yii::$app->request->isAjax  && !Yii::$app->request->get('_pjax')) $this->registerJs('
        $("#owners-form").on("beforeSubmit",function(){
            var form = $(this);
            if (form.find(".has-error").length) return false;
            
            $.ajax({
                url    : form.attr(\'action\'),
                type   : \'post\',
                data   : form.serialize(),
                success: function (response) {
                    $("body").append(response);
                },
                error  : function () {
                    console.log(\'internal server error\');
                }
            });
            return false;
        });
    ',\yii\web\View::POS_END);

$form=\yii\bootstrap\ActiveForm::begin(['id'=>'owners-form']);

?>
<div class="card">
    <div class="card-header">
        Редактирование аккаунта
        <? if(Yii::$app->request->isAjax){ ?>
            <a href="#" onclick="uniqueOverlay.close();return false;" class="pull-right m-l-20" style="margin-top: 1px;"><i class="la la-close" ></i></a>
        <? } ?>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <?=$form->field($model,'username')->textInput()?>
            </div>
            <div class="col-md-4">
                <?=$form->field($model,'newPassword')->passwordInput()?>
            </div>
            <div class="col-md-4">
                <?=$form->field($model,'passwordConfirmation')->passwordInput()?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <?=$form->field($model,'phone')->textInput()?>
            </div>
            <div class="col-md-4">
                <?=$form->field($model,'email')->textInput()?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <?=$form->field($model,'first_name')->textInput()?>
            </div>
            <div class="col-md-4">
                <?=$form->field($model,'last_name')->textInput()?>
            </div>
            <div class="col-md-4">
                <?=$form->field($model,'middle_name')->textInput()?>
            </div>
        </div>
    </div>
    <div class="card-footer text-right">
        <? if($model->scenario=='editProfile'){ ?>

            <a href="<?=\yii\helpers\Url::to(['/auth/auth','authclient'=>'google'])?>" id="google_button" class="btn btn-primary btn-outline">
                <?=$this->render('@app/web/quantum/google_logo.svg')?>&nbsp;&nbsp;Привязать аккаунт Google
            </a>

            <a href="<?=\yii\helpers\Url::to(['/auth/auth','authclient'=>'facebook'])?>" id="facebook_button" class="btn btn-secondary btn-outline">
                <?=$this->render('@app/web/quantum/facebook.svg')?>&nbsp;&nbsp;Привязать аккаунт Facebook
            </a>

        <? } ?>

        <?=\yii\helpers\Html::submitButton('Применить',['class'=>'btn btn-danger'])?>
    </div>
</div>

<? if($model->scenario=='edit'){ ?>

    <? if(!Yii::$app->request->isAjax){ ?>
        <div class="card">
            <div class="card-header">
                Блокировка аккаунта
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <?=$form->field($model,'status')->dropDownList(Identity::$statuses)->label('Активный аккаунт')?>
                    </div>
                    <div class="col-md-4">
                        <?=$form->field($model,'wrong_pass_entered')->textInput()->label('Неверно введено пароль')?>
                    </div>
                </div>

            </div>
            <div class="card-footer text-right">
                <?=\yii\helpers\Html::submitButton('Применить',['class'=>'btn btn-danger'])?>
            </div>
        </div>
    <? } ?>
<? } ?>
<? \yii\bootstrap\ActiveForm::end(); ?>

<?
if(Yii::$app->request->isAjax){ echo \wbp\uniqueOverlay\UniqueOverlay::script(); }
?>
