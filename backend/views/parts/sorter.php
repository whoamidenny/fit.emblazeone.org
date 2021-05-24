<?php
    if(\Yii::$app->controller->sortEnable()) {
        \yii\jui\Sortable::widget([
            'options' => ['id' => 'sortable tbody'],
            'clientOptions' => ['cursor' => 'move', 'items' => ' > tr'],
            'clientEvents' => [
                'update' => "function(event, ui){
                    $.post(
                        '" . \yii\helpers\Url::to(['sort']) . "',
                        {elements:$(this).sortable('toArray',{attribute:'data-key'})}
                    );
                  }"
                ]
        ]);
    }