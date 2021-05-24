<?php

use yii\helpers\Html;

$bundle=\frontend\assets\AppAsset::register($this);
?>

<? \yii\widgets\Pjax::begin(['id'=>'header'])?>

<header>
    <div class="container-fluid">
        <div class="row h-72 align-items-center justify-content-between">
            <div class="col-sm-3 col-8">
                <a data-pjax="0" href="<?=\yii\helpers\Url::to(['site/index'])?>" class="logo">
                    <img src="<?=$bundle->baseUrl?>/images/logo.png" alt="logo"/>
                    LIMITLESS VIP <span>FIT CLUB</span>
                </a>
            </div>
            <div class="col-sm-9 col-4">
                <nav class="navbar navbar-expand-lg navbar-dark">
                    <button class="navbar-toggler" type="button" data-toggle="collapse"
                            data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                            aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav w-100 justify-content-end align-items-start">
                            <li class="nav-item">
                                <a data-pjax="0" class="nav-link" href="<?=\yii\helpers\Url::to(['profile/weight-tracker'])?>">
                                    CURRENT WEIGHT
                                    <span><?=Yii::$app->user->identity->weight?Yii::$app->user->identity->weight.'LBS':'Start tracking your weight'?></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a data-pjax="0" class="nav-link" href="<?=\yii\helpers\Url::to(['profile/index'])?>">
                                    YOUR GOAL
                                    <span><?=Yii::$app->user->identity->goal?></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a data-pjax="0" class="nav-link last" href="<?=\yii\helpers\Url::to(['profile/index'])?>">
                                    <?=Yii::$app->user->identity->name?>
                                    <i class="fas fa-plus-circle"></i>
                                </a>
                                <div class="bg_avtar">
                                    <a data-pjax="0" href="<?=\yii\helpers\Url::to(['profile/index'])?>">
                                        <div class="pink" style="background-color: #fff;">
                                            <? if(Yii::$app->user->identity && Yii::$app->user->identity->image){ ?>
                                                <img src="<?=Yii::$app->user->identity->image->getUrl('118x118')?>" alt="">
                                            <? } ?>
                                        </div>
                                    </a>
                                </div>
                            </li>
                            <li class="nav-item">
                                <a data-pjax="0" class="nav-link logout" href="<?=\yii\helpers\Url::to(['auth/logout'])?>">
                                    Logout
                                </a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</header>

<? \yii\widgets\Pjax::end(); ?>
