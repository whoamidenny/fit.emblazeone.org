<?php
    $bundle=\frontend\assets\AppAsset::register($this);
?>

<!-- -->
<div class="left_sidebar">
    <div class="pointer">
        <i class="fas fa-bars"></i>
    </div>
    <ul>
        <li>
            <a href="<?=\yii\helpers\Url::to(['plans/meal'])?>">
                <img src="<?=$bundle->baseUrl?>/images/nav_1.svg" alt=""/>
                <span>Meal Plan</span>
            </a>
        </li>
        <li>
            <a href="<?=\yii\helpers\Url::to(['plans/workout'])?>">
                <img src="<?=$bundle->baseUrl?>/images/nav_2.svg" alt=""/>
                <span>Workout Plan</span>
            </a>
        </li>
        <li>
            <a href="<?=\yii\helpers\Url::to(['plans/supplement'])?>">
                <img src="<?=$bundle->baseUrl?>/images/nav_3.svg" alt=""/>
                <span>Supplement Plan</span>
            </a>
        </li>
        <li>
            <a href="<?=\yii\helpers\Url::to(['profile/weight-tracker'])?>">
                <img src="<?=$bundle->baseUrl?>/images/nav_4.svg" alt=""/>
                <span>Weight Tracker</span>
            </a>
        </li>
        <li>
            <a href="<?=\yii\helpers\Url::to(['profile/photo-tracker'])?>">
                <img src="<?=$bundle->baseUrl?>/images/nav_5.svg" alt=""/>
                <span>Photo Upload</span>
            </a>
        </li>
        <li>
            <a href="<?=\yii\helpers\Url::to(['site/check-in'])?>">
                <img src="<?=$bundle->baseUrl?>/images/nav_6.svg" alt=""/>
                <span>Check-In</span>
            </a>
        </li>
        <li>
            <a href="<?=\yii\helpers\Url::to(['site/rules'])?>">
                <img src="<?=$bundle->baseUrl?>/images/nav_7.svg" alt=""/>
                <span>Rules</span>
            </a>
        </li>
        <li>
            <a href="<?=\yii\helpers\Url::to(['videos/exercises'])?>">
                <img src="<?=$bundle->baseUrl?>/images/nav_8.svg" alt=""/>
                <span>Video Library</span>
            </a>
        </li>
        <li>
            <a href="<?=\yii\helpers\Url::to(['videos/workouts'])?>">
                <img src="<?=$bundle->baseUrl?>/images/nav_9.svg" alt=""/>
                <span>Workout Videos</span>
            </a>
        </li>
    </ul>
</div>
<!-- -->
