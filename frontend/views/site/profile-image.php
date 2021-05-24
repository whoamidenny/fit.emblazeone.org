<?php

?>


<div class="row align-items-center mb-5">
    <div class="col-12 col-sm-4 col-md-3 col-lg-2">
        <? \yii\widgets\Pjax::begin(['id'=>'profile'])?>
        <div class="by_photo">
            <img src="<?=Yii::$app->user->identity->image->getUrl('320x320')?>" alt="">
        </div>
        <? \yii\widgets\Pjax::end(); ?>
    </div>
    <div class="col-12 col-sm-8 col-md-9 col-lg-10 text-center text-sm-left">
        <h3 class="name">
            Hi <?=Yii::$app->user->identity->name?>!
            <span><?=Yii::$app->user->identity->gender?>  |  <?=Yii::$app->user->identity->location?>.</span>
        </h3>
        <div class="upl" style="background-color: transparent;">
            <?php
                echo \wbp\imageUploader\ImageUploader::widget([
                    'style' => 'yellowUploadProfile',
                    'data' => ['size' => '100x100'],
                    'getUrl'=>\yii\helpers\Url::to(['site/getImage']),
                    'uploadUrl'=>\yii\helpers\Url::to(['site/uploadImage']),
                    'type' => \backend\modules\clients\models\Client::$imageTypes[0],
                    'item_id' => Yii::$app->user->identity->id,
                    'limit' => 1
                ]);
            ?>
        </div>
    </div>
</div>
