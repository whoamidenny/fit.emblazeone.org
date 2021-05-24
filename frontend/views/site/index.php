<?php
    $bundle=\frontend\assets\AppAsset::register($this);
?>

<section class="dashboard">
    <div class="container">
        <?=$this->render('profile-image')?>

        <!-- -->
        <div class="row mb-3">
            <div class="col-md-8 col-12">
                <div class="bg_white d-flex flex-wrap align-items-center">
                    <div class="title_contest">
                        THE CONTEST HAS NOT YET STARTED!
                        <span>CHECK BACK ON OCTOBER 19, 2020 TO ACCESS THE CHECK-IN CARD.</span>
                    </div>
                    <div class="current">
                        CURRENT WEIGHT
                        <span><?=Yii::$app->user->identity->weight?Yii::$app->user->identity->weight.'LBS':'Start tracking your weight'?></span>
                    </div>
                    <div class="current">
                        YOUR GOAL
                        <span><?=Yii::$app->user->identity->goal?></span>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-12 pl_cust">
                <div class="bg_rouse">
                    <div class="update">
                        Update Your Progress
                        <span>Last Updated : A week ago!</span>
                    </div>
                    <a href="<?=\yii\helpers\Url::to(['profile/photo-tracker'])?>" class="btn">
                        UPDATE PROGRESS
                    </a>
                </div>
            </div>
        </div>
        <div class="row mb-5">
            <div class="col-md-6">
                <div class="bg_white radius d-flex flex-wrap justify-content-between align-items-center">
                    <div class="weight">Weight Tracker</div>
                    <a href="<?=\yii\helpers\Url::to(['profile/weight-tracker'])?>" class="btn-all">View All</a>
<!--                    <img src="--><?//=$bundle->baseUrl?><!--/images/img_2.png" class="d-block w-100 mt-3" alt="">-->
                    <canvas id="chart-weight"></canvas>

                    <?
                        $this->registerJs("
                            
                            var options = {
//                                        maintainAspectRatio: false,
//                                        spanGaps: false,
//                                        elements: {
//                                            line: {
//                                                tension: 0.000001
//                                            }
//                                        },
//                                        plugins: {
//                                            filler: {
//                                                propagate: false
//                                            }
//                                        },
//                                        scales: {
//                                            xAxes: [{
//                                                ticks: {
//                                                    autoSkip: false,
//                                                    maxRotation: 0
//                                                }
//                                            }]
//                                        }
                                    };


                            new Chart('chart-weight', {
                                type: 'line',
                                data: {
                                    labels: [".Yii::$app->user->identity->getWeightTrackersLabels()."],
                                    datasets: [{
                                        label: 'Weight Tracker',
                                        data: [".Yii::$app->user->identity->getWeightTrackersValues()."],
//                                        backgroundColor: [
//                                            'rgba(255, 99, 132, 0.2)',
//                                            'rgba(54, 162, 235, 0.2)',
//                                            'rgba(255, 206, 86, 0.2)',
//                                            'rgba(75, 192, 192, 0.2)',
//                                            'rgba(153, 102, 255, 0.2)',
//                                            'rgba(255, 159, 64, 0.2)'
//                                        ],
                                        borderColor: [
//                                            'rgba(255, 99, 132, 1)',
//                                            'rgba(54, 162, 235, 1)',
//                                            'rgba(255, 206, 86, 1)',
//                                            'rgba(75, 192, 192, 1)',
//                                            'rgba(153, 102, 255, 1)',
                                            'rgba(255, 159, 64, 1)'
                                        ],
                                        borderWidth: 3
                                    }]
                                },
                                options: Chart.helpers.merge(options, {
                                    title: {
//                                        text: 'fill: start',
                                        display: false
                                    }
                                })
                            });
                        ",\yii\web\View::POS_END);

                    ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="bg_white radius d-flex flex-wrap justify-content-between align-items-center">
                    <div class="weight">Progress Photos</div>
                    <a href="<?=\yii\helpers\Url::to(['profile/photo-tracker'])?>" class="btn-all">View All</a>
                    <? foreach (Yii::$app->user->identity->getPhotoTrackers()->limit(2)->all() as $photoTrack){ ?>
                        <div class="progresPhoto">
                            <img src="<?=$photoTrack->image->getUrl('160x100')?>" alt=""/>
                            <h4>Uploaded <?=date("m/d/Y | h:i a", strtotime($photoTrack->created_at))?></h4>
                        </div>
                    <? } ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="mb-4 d-flex flex-wrap justify-content-between align-items-center">
                    <div class="weight_2">Exercise Video Library</div>
                    <a href="<?=\yii\helpers\Url::to(['videos/exercises'])?>" class="btn-all">View All</a>
                </div>
            </div>
            <? foreach ($exercises as $exercise){  echo $this->render('../videos/exercises-video', ['video'=>$exercise]); } ?>

        </div>
        <div class="row">
            <!-- -->
            <div class="col-12 col-sm-4">
                <a href="<?=\yii\helpers\Url::to(['plans/meal'])?>" class="fo_img">
                    <span class="bottom_line">Meal Plans</span>
                    <img src="<?=$bundle->baseUrl?>/images/img_8.png" alt="">
                </a>
            </div>
            <!-- -->
            <div class="col-12 col-sm-4">
                <a href="<?=\yii\helpers\Url::to(['plans/workout'])?>" class="fo_img">
                    <span class="bottom_line">Workout Plans</span>
                    <img src="<?=$bundle->baseUrl?>/images/img_9.png" alt="">
                </a>
            </div>
            <!-- -->
            <div class="col-12 col-sm-4">
                <a href="<?=\yii\helpers\Url::to(['plans/supplement'])?>" class="fo_img">
                    <span class="bottom_line">Supplement Plans</span>
                    <img src="<?=$bundle->baseUrl?>/images/img_10.png" alt="">
                </a>
            </div>
        </div>
    </div>
</section>