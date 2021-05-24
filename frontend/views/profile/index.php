<section class="dashboard">
    <div class="container">
        <?php
            echo $this->render('../site/profile-image');
        ?>
        <div class="row mb-3">
            <div class="col-sm-12 col-12">
                <div class="bg_white radius d-flex flex-wrap align-items-center">
                    <?
                        $form=\yii\widgets\ActiveForm::begin([
                            'options'=>[
                                    'style'=>'width:100%;'
                            ]
                        ]);
                    ?>
                        <div class="row">
                            <div class="col-md-6">
                                <?=$form->field($model,'email')->textInput()?>
                                <?=$form->field($model,'first_name')->textInput()?>
                                <?=$form->field($model,'last_name')->textInput()?>
                                <?=$form->field($model,'goal_id')->dropDownList(\backend\modules\clients\models\Client::getGoals())?>
                            </div>
                            <div class="col-md-6">
                                <?=$form->field($model,'newPassword')->passwordInput()?>
                                <?=$form->field($model,'passwordConfirmation')->passwordInput()?>
                                <?=$form->field($model,'address')->textInput()?>
                                <?=$form->field($model,'country')->textInput()?>
                            </div>
                            <div class="col-md-12 text-right">
                                <button class="btn btn-success">Save</button>
                            </div>
                        </div>
                    <? \yii\widgets\ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</section>

