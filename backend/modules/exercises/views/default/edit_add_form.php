<?

use backend\assets\AppAsset;
use backend\modules\exercisesCategories\models\ExercisesCategories;
use wbraganca\tagsinput\TagsinputWidget;

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
            <div class="col-md-6">
                <?=$form->field($model,'title')->textInput()?>
            </div>
            <div class="col-md-6">
                <div class="form-group m-b-0">
                    <label class="control-label"><?=$model->attributeLabels()['status']?></label>
                </div>
                <?=$form->field($model,'status')->checkbox()->label('Active')?>
            </div>
            <div class="col-md-6">
                <?=$form->field($model,'category_id')->dropDownList(ExercisesCategories::getList())?>
            </div>
            <div class="col-md-6">
                <?=$form->field($model,'url')->textInput()?>
            </div>
            <div class="col-md-12">
                <?= $form->field($model, 'tagsTitlesRow')->widget(TagsinputWidget::classname(), [
                    'clientOptions' => [
                        'trimValue' => true,
                        'allowDuplicates' => false,
                        'typeahead'=>[
                            'source'=>\backend\modules\exercises\models\Tags::getTypehead()
                        ]
                    ]
                ]) ?>
            </div>
        </div>
    </div>
    <div class="card-footer text-right">
        <?=\yii\helpers\Html::submitButton('Save',['class'=>'btn btn-danger'])?>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Image</div>
            <div class="card-body">
                <?php
                    echo \wbp\imageUploader\ImageUploader::widget([
                        'style' => 'estoreMultiple',
                        'data' => ['size' => '123x123'],
                        'type' => \backend\modules\exercises\models\Exercises::$imageTypes[0],
                        'item_id' => $model->id,
                        'limit' => 1
                    ]);
                ?>
            </div>
            <div class="card-footer text-right">
                <?=\yii\helpers\Html::submitButton('Save',['class'=>'btn btn-danger'])?>
            </div>
        </div>
    </div>
</div>

<? \yii\bootstrap\ActiveForm::end(); ?>

<?
if(Yii::$app->request->isAjax){ echo \wbp\uniqueOverlay\UniqueOverlay::script(); }
?>
