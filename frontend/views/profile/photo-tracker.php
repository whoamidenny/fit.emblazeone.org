<?php


?>

<section class="dashboard photo_tracker_bg">
    <div class="container">

        <div class="row">
            <div class="col-sm-12">
                <div class="mb-4 d-flex flex-wrap justify-content-between align-items-center">
                    <div class="weight_2">Photo Tracker</div>
                    <!--<a href="#" class="btn-all">View All</a>-->
                </div>
            </div>
            <div class="col-12 overflow-auto">
                <? $form=\yii\widgets\ActiveForm::begin(); ?>
                    <table class="table">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col">Date Entered</th>
                            <th scope="col"  class="text-center">Weight</th>
                            <th scope="col" class="text-center" >Front</th>
                            <th scope="col" class="text-center" >Side</th>
                            <th scope="col" class="text-center" >Back</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?
                            foreach (Yii::$app->user->identity->photoTrackers as $track){
                                echo $this->render('photo-tracker_item', ['model'=>$track]);
                            }
                        ?>
                        <tr>
                            <th scope="row"><?=date("m/d/Y")?></th>
                            <td  class="text-center">
                                <div class="form-inline justify-content-center">
                                    <?=$form->field($model, 'weight')->textInput()->label(false)?>
                                    <button type="submit" class="btn btn-primary">save</button>
                                </div>
                            </td>
                            <td class="text-center">
                                <?php
                                    echo \wbp\imageUploader\ImageUploader::widget([
                                        'style' => 'yellowUpload',
                                        'data' => ['size' => '100x100'],
                                        'type' => \backend\modules\clients\models\ClientPhotoTracker::$imageTypes[0],
                                        'item_id' => $model->id,
                                        'limit' => 1
                                    ]);
                                ?>
                            </td>
                            <td class="text-center">
                                <?php
                                    echo \wbp\imageUploader\ImageUploader::widget([
                                        'style' => 'yellowUpload',
                                        'data' => ['size' => '100x100'],
                                        'type' => \backend\modules\clients\models\ClientPhotoTracker::$imageTypes[1],
                                        'item_id' => $model->id,
                                        'limit' => 1
                                    ]);
                                ?>
                            </td>
                            <td class="text-center">
                                <?php
                                    echo \wbp\imageUploader\ImageUploader::widget([
                                        'style' => 'yellowUpload',
                                        'data' => ['size' => '100x100'],
                                        'type' => \backend\modules\clients\models\ClientPhotoTracker::$imageTypes[2],
                                        'item_id' => $model->id,
                                        'limit' => 1
                                    ]);
                                ?>
                            </td>
                        </tr>

                        </tbody>
                    </table>
                <?
                    \yii\widgets\ActiveForm::end();
                ?>
            </div>

        </div>
    </div>
</section>
