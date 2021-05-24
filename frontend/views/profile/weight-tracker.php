<?php


?>

<section class="dashboard photo_tracker_bg">
    <div class="container">

        <div class="row">
            <div class="col-sm-12">
                <div class="mb-4 d-flex flex-wrap justify-content-between align-items-center">
                    <div class="weight_2">Weight Tracker</div>
                </div>
            </div>
            <div class="col-12 overflow-auto">
                <? $form=\yii\widgets\ActiveForm::begin(); ?>
                    <table class="table">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col">Date Entered</th>
                            <th scope="col">Week Number</th>
                            <th scope="col">Your Weight</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?
                            foreach (Yii::$app->user->identity->weightTrackers as $track){
                                echo $this->render('weight-tracker_item', ['model'=>$track]);
                            }
                        ?>
                        <tr>
                            <th scope="row"><?=date("m/d/Y")?></th>
                            <td>
                                <div class="form-inline ">
                                    <?=$form->field($model, 'week')->textInput()->label(false)?>
                                    <button type="submit" class="btn btn-primary">save</button>
                                </div>
                            </td>
                            <td>
                                <div class="form-inline">
                                    <?=$form->field($model, 'weight')->textInput()->label(false)?>
                                    <button type="submit" class="btn btn-primary">save</button>
                                </div>
                            </td>
                        </tr>

                        </tbody>
                    </table>
                <?
                    \yii\widgets\ActiveForm::end();
                ?>
                <div class="text-center">
                    <a href="<?=\yii\helpers\Url::to(['site/index'])?>" class="btn btn-primary">View Graph</a>
                    <a href="<?=\yii\helpers\Url::to(['profile/download-weight-tracker'])?>" target="_blank" class="btn btn-primary">Download </a>
                </div>
            </div>

        </div>
    </div>
</section>
