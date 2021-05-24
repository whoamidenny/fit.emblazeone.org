<?

use backend\assets\AppAsset;
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
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <?=$form->field($model,'title')->textInput()?>
            </div>
            <div class="col-md-4">
                <?=$form->field($model,'title_ua')->textInput()?>
            </div>
            <div class="col-md-4">
                <div class="form-group m-b-0">
                    <label class="control-label"><?=$model->attributeLabels()['status']?></label>
                </div>
                <?=$form->field($model,'status')->checkbox()->label('Активная')?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <?=$form->field($model,'description')->textarea()?>
            </div>
            <div class="col-md-4">
                <?=$form->field($model,'description_ua')->textarea()?>
            </div>
        </div>
    </div>
    <div class="card-footer text-right">
        <?=\yii\helpers\Html::submitButton('Применить',['class'=>'btn btn-danger'])?>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                Картинки
            </div>
            <div class="card-body">
                <?=\wbp\imageUploader\ImageUploader::widget([
                    'style' => 'estoreMultiple',
                    'data' => [
                        'size' => '123x123',
                    ],
                    'type' => \backend\modules\about\models\About::$imageTypes[0],
                    'item_id' => $model->id,
                    'limit' => 2
                ])?>

            </div>
            <div class="card-footer text-right">

                <?=\yii\helpers\Html::submitButton('Применить',['class'=>'btn btn-danger'])?>
            </div>
        </div>
    </div>
</div>

<? \yii\bootstrap\ActiveForm::end(); ?>

<?
if(Yii::$app->request->isAjax){ echo \wbp\uniqueOverlay\UniqueOverlay::script(); }
?>
