<?php

use kartik\date\DatePicker;
use yii\widgets\ActiveForm;

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
            <div class="col-md-3">
                <?=$form->field($searchModel,'phone')->textInput(['autocomplete'=>"off",'autocorrect'=>"off",'autocapitalize'=>"none",'spellcheck'=>"false"])?>
            </div>
            <div class="col-md-3">
                <?=$form->field($searchModel,'name')->textInput(['autocomplete'=>"off",'autocorrect'=>"off",'autocapitalize'=>"none",'spellcheck'=>"false"])?>
            </div>
            <div class="col-md-3">
                <?=$form->field($searchModel,'from')->widget(DatePicker::className(),[
                    'options'=>['class'=>'form-control', 'autocomplete'=>"off"],
//                    'language' => 'ru',
                    'pluginOptions' => [
                        'format' => 'yyyy-mm-dd',
                    ]
                ])?>
            </div>
            <div class="col-md-3">
                <?=$form->field($searchModel,'to')->widget(DatePicker::className(),[
                    'options'=>['class'=>'form-control', 'autocomplete'=>"off"],
//                    'language' => 'ru',
                    'pluginOptions' => [
                        'format' => 'yyyy-mm-dd',
                    ]
                ])?>
            </div>
        </div>
    </div>
    <div class="card-footer text-right">
        <?=\yii\helpers\Html::submitButton('Search',['class'=>'btn btn-danger'])?>
    </div>
</div>
<? ActiveForm::end()?>