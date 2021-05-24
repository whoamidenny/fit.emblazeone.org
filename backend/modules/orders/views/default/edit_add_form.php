<?

use backend\assets\AppAsset;
use backend\modules\orders\models\Orders;
use common\models\Identity;
use kartik\date\DatePicker;

\kartik\date\DatePickerAsset::register($this);
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
        Статусы
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <?=$form->field($model,'status')->dropDownList(Orders::$statuses)?>
            </div>
            <div class="col-md-4">
                <?=$form->field($model,'payment_type')->dropDownList(Orders::$payment_types)?>
            </div>
            <div class="col-md-4">
                <?=$form->field($model,'payment_status')->dropDownList(Orders::$payment_statuses)?>
            </div>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-header">
        Клиент
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <?=$form->field($model,'name')->textInput()?>
            </div>
            <div class="col-md-4">
                <?=$form->field($model,'phone')->textInput()?>
            </div>
            <div class="col-md-4">
                <?=$form->field($model,'email')->textInput()?>
            </div>
        </div>

    </div>
    <div class="card-footer text-right">
        <?=\yii\helpers\Html::submitButton('Применить',['class'=>'btn btn-danger'])?>
    </div>
</div>
<div class="card">
    <div class="card-header">
        Доставка
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <?=$form->field($model,'street')->textInput()?>
            </div>
            <div class="col-md-4">
                <?=$form->field($model,'house')->textInput()?>
            </div>
            <div class="col-md-4">
                <?=$form->field($model,'appart')->textInput()?>
            </div>
            <div class="col-md-4">
                <?=$form->field($model,'entrance')->textInput()?>
            </div>
            <div class="col-md-4">
                <?=$form->field($model,'key_code')->textInput()?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <?=$form->field($model,'notes')->textarea()?>
            </div>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-header">
        Заказ
    </div>
    <div class="card-body">
        <? foreach ($model->orderItems as $item){ ?>
        <div style="display: none;">
            <?=$form->field($item,'id[]')->hiddenInput()->label(false)?>
        </div>
        <div class="row">
            <div class="col-md-6">
                <?=$form->field($item,'product_id[]')->dropDownList(\backend\modules\products\models\Products::getList())?>
            </div>
            <div class="col-md-6">
                <?=$form->field($item,'size[]')->dropDownList(\backend\modules\products\models\Products::getLengthsDays())?>
            </div>
        </div>
        <? } ?>
    </div>
</div>
<? \yii\bootstrap\ActiveForm::end(); ?>

<?
if(Yii::$app->request->isAjax){ echo \wbp\uniqueOverlay\UniqueOverlay::script(); }
?>
