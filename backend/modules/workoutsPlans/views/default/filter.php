<?php
use yii\widgets\ActiveForm;

return;

$form=ActiveForm::begin([
    'action'=>['index'],
    'id'=> 'search',
]); ?>
<div class="card">
    <div class="card-header">
        Filter
    </div>
    <div class="card-body">
        <?=\yii\helpers\Html::hiddenInput('page',1);?>

        <div class="row">
            <div class="col-md-6">
                <?=$form->field($searchModel,'from')->textInput(['autocomplete'=>"off",'autocorrect'=>"off",'autocapitalize'=>"none",'spellcheck'=>"false"])?>
            </div>
            <div class="col-md-4">
                <?=$form->field($searchModel,'to')->textInput(['autocomplete'=>"off",'autocorrect'=>"off",'autocapitalize'=>"none",'spellcheck'=>"false"])?>
            </div>
        </div>
    </div>
    <div class="card-footer text-right">
        <?=\yii\helpers\Html::submitButton('Search',['class'=>'btn btn-danger'])?>
    </div>
</div>
<? ActiveForm::end()?>