<?php

/* @var $this \yii\web\View */
/* @var $content string */

use frontend\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;

$bundle = AppAsset::register($this);
?>
<?php $this->beginPage() ?>

<!DOCTYPE html>
<html lang="<?=Yii::$app->lang->getShortLang()?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?=\common\models\Config::getParameter('seo_title')?></title>
    <meta name="description" content="<?=\common\models\Config::getParameter('seo_description')?>">
    <meta name="keywords" content="<?=\common\models\Config::getParameter('seo_keywords')?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="shortcut icon" href="<?=$bundle->baseUrl?>/images/fav.png" type="image/png">

    <?php $this->head() ?>
</head>

<body data-spy="scroll" data-target="#navbar-menu" data-offset="0">
<?php $this->beginBody() ?>
<?= Alert::widget() ?>

<?=$this->render('header')?>
<?=$this->render('left_sidebar')?>

<?= $content ?>

<?=$this->render('footer')?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
