<?php
use backend\assets\AppAsset;
use yii\helpers\Html;
use backend\widgets\Menu;


/* @var $this \yii\web\View */
/* @var $content string */
$bundle = AppAsset::register($this);
\iPaya\Yii2\FontAwesome\WebFontsWithCss\FontAwesomeAsset::register($this);

$this->registerJs('
//     $.post("",{logOnly:true});
//    setInterval(function(){$.post("",{logOnly:true})},20000); 
', yii\web\View::POS_END);
$this->registerJs('
    $("[href=\'/default/index\']").click(function(){
        $("#comming-soon").modal("show");
        return false;
    });
    $(\'.hasSubmenu [data-toggle="slide"]\').on(\'click\', function(e){
        $(\'.hasSubmenu.active\').not($(this).parent()).each(function(){
            $(this).find("ul").slideUp();
            $(this).removeClass("active");
            $(this).find("[data-toggle=\"slide\"]").removeClass("open");
        });
        if($(this).hasClass("open")){
            $(this).removeClass("open");
            $(this).parent().removeClass("active");
            $(this).parent().find("ul").stop(true, true).slideUp();
        }else{
            $(this).addClass("open");
            $(this).parent().addClass("active");
            $(this).parent().find("ul").stop(true, true).slideDown();
        }
    });
    $(\'.hasSubmenu.active\').each(function(){
        $(this).find("ul").slideDown(1);
        $(this).find("[data-toggle=\"slide\"]").addClass("open");
    });
', yii\web\View::POS_END);

$this->registerJs('    



    ', \yii\web\View::POS_READY);
$this->registerCss("
    .ch input{
        display: none;
    }
    .ch input + label{
        margin-left: 10px;
        content: \"\";
        display: inline-block;
        width: 30px;
        height: 15px;
        background-color: rgba(239, 111, 111, 0.9);
        border-radius: 15px;
        margin-right: 15px;
        -webkit-transition: background 0.3s ease;
        -o-transition: background 0.3s ease;
        transition: background 0.3s ease;
        vertical-align: middle;
    }
    .ch input + label:after {
        cursor: pointer;
        content: \"\";
        display: inline-block;
        width: 20px;
        height: 20px;
        background-color: #F1F1F1;
        border-radius: 20px;
        position: relative;
        -webkit-box-shadow: 0 1px 3px 1px rgba(0, 0, 0, 0.4);
        box-shadow: 0 1px 3px 1px rgba(0, 0, 0, 0.4);
        left: -5px;
        top: -3px;
        -webkit-transition: left 0.3s ease, background 0.3s ease, -webkit-box-shadow 0.1s ease;
        -o-transition: left 0.3s ease, background 0.3s ease, box-shadow 0.1s ease;
        transition: left 0.3s ease, background 0.3s ease, box-shadow 0.1s ease;
    }
    .ch input:checked + label{
        background-color: rgba(98, 196, 98, 0.9);
    
    
    }
    .ch input:checked + label:after {
        left: 15px;
    }

");

$this->beginPage();

?>
<!DOCTYPE html>
<!--[if lt IE 7]>
<html class="ie lt-ie9 lt-ie8 lt-ie7" lang="<?= Yii::$app->language ?>"> <![endif]-->
<!--[if IE 7]>
<html class="ie lt-ie9 lt-ie8" lang="<?= Yii::$app->language ?>"> <![endif]-->
<!--[if IE 8]>
<html class="ie lt-ie9" lang="<?= Yii::$app->language ?>"> <![endif]-->
<!--[if gt IE 8]>
<html class="ie gt-ie8" lang="<?= Yii::$app->language ?>"> <![endif]-->
<!--[if !IE]><!-->
<html lang="<?= Yii::$app->language ?>"><!-- <![endif]-->


<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <link rel="shortcut icon" href="<?=$bundle->baseUrl?>/images/fav.png" type="image/png">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
    <!--    <meta name="viewport" content="width=1280, initial-scale=0">-->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE"/>
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="content-menu">
<?php $this->beginBody() ?>
<div style="position: fixed; right: 0; top: 0; z-index: 1100;" id="alerts">

    <?
    $alert = '';
    $alertReady = '';
    $types=[
        'success'=>'success',
        'error'=>'danger'
    ];
    $flashes = Yii::$app->session->getAllFlashes();
    foreach ($flashes as $flashType => $fl) {
        if($flashType=='popup-success' || $flashType=='popup-error'){
            foreach ((array)$fl as $flash) {
                $uniqueId=uniqid();
                ?>
                <div id="<?=$uniqueId?>" class="modal fade">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel"><?=$flashType=='popup-success'?'Ура! Получилось.':'Какая-то странная ошибка.'?></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p><?=$flash?></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
                <?
                $alertReady .= <<<JS
                    $('#{$uniqueId}').modal('show');
JS;
            }
        }else{

            foreach ((array)$fl as $flash) {
                $uniqueId=uniqid();
                ?>
                <div id="<?=$uniqueId?>" class="alert alert-<?=$types[$flashType]?> alert-outline alert-dismissible fade show" role="alert">
                    <?=$flash?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true" class="la la-close"></span>
                    </button>
                </div>
                <?
                $alert .= <<<JS
                setTimeout(function(){
                    $('#{$uniqueId} .close').click();
                    
                },5000);
JS;
            }
        }

    }
    $this->registerJs($alert, yii\web\View::POS_END);
    $this->registerJs($alertReady, yii\web\View::POS_READY);
    //echo \wbp\PrettyAlert\Alert::widget(["autoSearchInSession"=>true]);
    ?>
</div>

<?=\wbp\uniqueOverlay\UniqueOverlay::body()?>

<!-- CONTENT WRAPPER -->
<div id="app">
    <!-- TOP TOOLBAR WRAPPER -->
    <nav class="top-toolbar navbar navbar-mobile navbar-tablet">
        <div class="flex">
            <ul class="navbar-nav nav-left">
                <li class="nav-item">
                    <a href="javascript:void(0)" data-toggle-state="aside-left-open">
                        <i class="icon dripicons-align-left"></i>
                    </a>
                </li>
            </ul>
            <ul class="navbar-nav nav-center site-logo">
                <li>
                    <a href="<?=\yii\helpers\Url::to(['/'])?>">
                        <?
                            $logo_header_url=\common\models\Config::getParameter('logo_header_url',false, false);
                        ?>
                        <? if($logo_header_url){ ?>
                            <div class="logo_mobile">
                                <img src="<?=$bundle->baseUrl.$logo_header_url?>" style="height:40px;" />
                            </div>
                        <? }else{ ?>
                            <h1 class="brand-text"><?=mb_strtoupper(\common\models\Config::getParameter('title'))?></h1>
                        <? } ?>

                    </a>
                </li>
            </ul>
            <ul class="navbar-nav nav-right">
                <li class="nav-item">
                    <a href="javascript:void(0)" data-toggle-state="mobile-topbar-toggle">
                        <i class="icon dripicons-dots-3 rotate-90"></i>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
    <nav class="top-toolbar navbar navbar-desktop">
        <div class="flex">
        <ul class="navbar-nav nav-left">
            <li class="nav-item">
                <a href="javascript:void(0)" data-toggle-state="content-menu-close">
                    <i class="icon dripicons-align-left"></i>
                </a>
            </li>
        </ul>
        <ul class="site-logo">
            <li>
                <!-- START LOGO -->
                <a href="<?=\yii\helpers\Url::to(['/'])?>">
                        <? if($logo_header_url){ ?>
                            <div class="logo">
                                <img src="<?=$bundle->baseUrl.$logo_header_url?>" style="height:40px;" />
                            </div>
                        <? }else{ ?>
                            <h1 class="brand-text"><?=mb_strtoupper(\common\models\Config::getParameter('title'))?></h1>
                        <? } ?>
                </a>
                <!-- END LOGO -->
            </li>
        </ul>
        <div class="navbar-nav nav-right">

        <ul class="navbar-nav nav-right">

            <li class="nav-item dropdown">
                <a class="nav-link nav-pill user-avatar" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                    <? if(Yii::$app->user->identity &&  Yii::$app->user->identity->getImage() && Yii::$app->user->identity->getImage()->id){ ?>
                        <img src="<?= Yii::$app->user->identity->getImage()->getUrl('200x200') ?>" class="w-35 rounded-circle" alt="<?= Yii::$app->user->identity->name?>" />
                    <? }else{ ?>
                        <span style="display: inline-block;width: 32px;" class="rounded-circle">
                            <?=\backend\widgets\SvgWidget::getSvgIcon('avatars/avatar')?>
                        </span>
                    <? } ?>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-accout">
                    <div class="dropdown-header pb-3">
                        <div class="media d-user">
                            <? if(Yii::$app->user->identity && Yii::$app->user->identity->getImage() && Yii::$app->user->identity->getImage()->id){ ?>
                                <img src="<?= Yii::$app->user->identity->getImage()->getUrl('200x200') ?>" class="align-self-center mr-3 w-40 rounded-circle" alt="<?= Yii::$app->user->identity->name?>" />
                            <? }else{ ?>
                                <span class="align-self-center mr-3 w-40 rounded-circle">
                                    <?=\backend\widgets\SvgWidget::getSvgIcon('avatars/avatar')?>
                                </span>
                            <? } ?>
                            <div class="media-body">
                                <h5 class="mt-0 mb-0"><?= Yii::$app->user->identity->name?></h5>
                                <span><?= Yii::$app->user->identity->email?></span>
                            </div>
                        </div>
                    </div>
<!--                    <a class="dropdown-item" href="--><?//=\yii\helpers\Url::to(['/account/add'])?><!--"><i class="icon dripicons-user"></i> Add account </a>-->
                    <a class="dropdown-item" href="<?=\yii\helpers\Url::to(['/accounts/default/profile'])?>"><i class="icon dripicons-gear"></i> <?=Yii::t('main','Account Settings')?> </a>
                    <div class="dropdown-divider"></div>
                    <? if(Yii::$app->session->get('old-__id',false)){ ?>
                        <a class="dropdown-item" data-method="post"  href="<?=\yii\helpers\Url::to(['/auth/return'])?>"><i class="icon dripicons-lock-open"></i> <?=Yii::t('main','Return as admin')?></a>
                    <? } ?>
                    <a class="dropdown-item" data-method="post" href="<?= \yii\helpers\Url::to(['/auth/logout'])?>"><i class="icon dripicons-lock-open"></i> <?=Yii::t('main','Sign Out')?></a>
                </div>
            </li>
            <? if(NULL){ ?>
                <li class="nav-item">
                    <a href="javascript:void(0)" data-toggle-state="aside-right-open">
                        <i class="icon dripicons-align-right"></i>
                    </a>
                </li>
            <? } ?>
        </ul>
        </div>
        <form role="search" action="" class="navbar-form">
            <div class="form-group">
                <input type="text" placeholder="Search and press enter..." class="form-control navbar-search" autocomplete="off">
                <i data-q-action="close-site-search" class="icon dripicons-cross close-search"></i>
            </div>
            <button type="submit" class="d-none">Submit</button>
        </form>
        </div>
    </nav>
    <!-- END TOP TOOLBAR WRAPPER -->

    <div class="content-wrapper">
        <!-- MENU SIDEBAR WRAPPER -->
        <aside class="sidebar sidebar-left">
            <div class="sidebar-content">
                <nav class="main-menu">

                    <?
                    echo Menu::widget([
                        'options'=>['class'=>"nav metismenu"],
                        'linkTemplate' => '<a href="{url}"><i class="{class}"></i><span>{label}</span></a>',
                        'activateParents' => true,
                        'items' => \backend\models\Menu::getMenuItems(),
                    ]);
                    ?>

            </div>
        </aside>
        <!-- END MENU SIDEBAR WRAPPER -->
        <div class="content container-fluid">

            <?=$content?>

        </div>

        <? if(NULL){ ?>

            <!-- SIDEBAR QUICK PANNEL WRAPPER -->
            <aside class="sidebar sidebar-right">
                <div class="sidebar-content">
                    <div class="tab-panel m-b-30" id="sidebar-tabs">
                        <ul class="nav nav-tabs primary-tabs">
                            <li class="nav-item" role="presentation"><a href="#sidebar-settings" class="nav-link active show" data-toggle="tab" aria-expanded="true">Settings</a></li>
                            <li class="nav-item" role="presentation"><a href="#sidebar-contact" class="nav-link" data-toggle="tab" aria-expanded="true">Contacts</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fadeIn active" id="sidebar-settings">
                                <div class="sidebar-settings-wrapper">
                                    <h5 class="m-t-30 m-b-20">Colors with dark sidebar</h5>
                                    <div class="row m-0">
                                        <div class="col-6 col-md-3 p-5 m-b-10">
                                            <div class="color-option-check">
                                                <h6 class="title text-center">theme-a.css</h6><label data-load-css="../assets/css/layouts/vertical/themes/theme-a.css">
                                                    <input type="radio" name="setting-theme" checked="checked">
                                                    <span class="icon-check dark"></span>
                                                    <span class="split">
                                                                                                                    <span class="color bg-menu-dark"></span>
                                                                                                                    <span class="color bg-theme-a"></span>
                                                                                                                </span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3 p-5 m-b-10">
                                            <div class="color-option-check">
                                                <h6 class="title text-center">theme-b.css</h6><label data-load-css="../assets/css/layouts/vertical/themes/theme-b.css">
                                                    <input type="radio" name="setting-theme">
                                                    <span class="icon-check"></span>
                                                    <span class="split">
                                                                                                                    <span class="color bg-menu-dark"></span>
                                                                                                                    <span class="color bg-theme-b"></span>
                                                                                                                </span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3 p-5 m-b-10">
                                            <div class="color-option-check">
                                                <h6 class="title text-center">theme-c.css</h6><label data-load-css="../assets/css/layouts/vertical/themes/theme-c.css">
                                                    <input type="radio" name="setting-theme">
                                                    <span class="icon-check"></span>
                                                    <span class="split">
                                                                                                                    <span class="color bg-menu-dark"></span>
                                                                                                                    <span class="color bg-theme-c"></span>
                                                                                                                </span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3 p-5 m-b-10">
                                            <div class="color-option-check">
                                                <h6 class="title text-center">theme-d.css</h6><label data-load-css="../assets/css/layouts/vertical/themes/theme-d.css">
                                                    <input type="radio" name="setting-theme">
                                                    <span class="icon-check"></span>
                                                    <span class="split">
                                                                                                                    <span class="color bg-menu-dark"></span>
                                                                                                                    <span class="color bg-theme-d"></span>
                                                                                                                </span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3 p-5 m-b-10">
                                            <div class="color-option-check">
                                                <h6 class="title text-center">theme-e.css</h6><label data-load-css="../assets/css/layouts/vertical/themes/theme-e.css">
                                                    <input type="radio" name="setting-theme">
                                                    <span class="icon-check"></span>
                                                    <span class="split">
                                                                                                                    <span class="color bg-menu-dark"></span>
                                                                                                                    <span class="color bg-theme-e"></span>
                                                                                                                </span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3 p-5 m-b-10">
                                            <div class="color-option-check">
                                                <h6 class="title text-center">theme-f.css</h6><label data-load-css="../assets/css/layouts/vertical/themes/theme-f.css">
                                                    <input type="radio" name="setting-theme">
                                                    <span class="icon-check"></span>
                                                    <span class="split">
                                                                                                                    <span class="color bg-menu-dark"></span>
                                                                                                                    <span class="color bg-theme-f"></span>
                                                                                                                </span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3 p-5 m-b-10">
                                            <div class="color-option-check">
                                                <h6 class="title text-center">theme-g.css</h6><label data-load-css="../assets/css/layouts/vertical/themes/theme-g.css">
                                                    <input type="radio" name="setting-theme">
                                                    <span class="icon-check"></span>
                                                    <span class="split">
                                                                                                                    <span class="color bg-menu-dark"></span>
                                                                                                                    <span class="color bg-theme-g"></span>
                                                                                                                </span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3 p-5 m-b-10">
                                            <div class="color-option-check">
                                                <h6 class="title text-center">theme-h.css</h6><label data-load-css="../assets/css/layouts/vertical/themes/theme-h.css">
                                                    <input type="radio" name="setting-theme">
                                                    <span class="icon-check"></span>
                                                    <span class="split">
                                                                                                                    <span class="color bg-menu-dark"></span>
                                                                                                                    <span class="color bg-theme-h"></span>
                                                                                                                </span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <h5 class="m-t-30 m-b-20">Colors with light sidebar</h5>
                                    <div class="row m-0">
                                        <div class="col-6 col-md-3 p-5 m-b-10">
                                            <div class="color-option-check">
                                                <h6 class="title text-center">theme-i.css</h6><label data-load-css="../assets/css/layouts/vertical/themes/theme-i.css">
                                                    <input type="radio" name="setting-theme">
                                                    <span class="icon-check"></span>
                                                    <span class="split">
                                                                                                                    <span class="color bg-menu-light"></span>
                                                                                                                    <span class="color bg-menu-dark"></span>
                                                                                                                </span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3 p-5 m-b-10">
                                            <div class="color-option-check">
                                                <h6 class="title text-center">theme-j.css</h6><label data-load-css="../assets/css/layouts/vertical/themes/theme-j.css">
                                                    <input type="radio" name="setting-theme">
                                                    <span class="icon-check"></span>
                                                    <span class="split">
                                                                                                                    <span class="color bg-menu-light"></span>
                                                                                                                    <span class="color bg-theme-j"></span>
                                                                                                                </span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3 p-5 m-b-10">
                                            <div class="color-option-check">
                                                <h6 class="title text-center">theme-k.css</h6><label data-load-css="../assets/css/layouts/vertical/themes/theme-k.css">
                                                    <input type="radio" name="setting-theme">
                                                    <span class="icon-check"></span>
                                                    <span class="split">
                                                                                                                    <span class="color bg-menu-light"></span>
                                                                                                                    <span class="color bg-theme-k"></span>
                                                                                                                </span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3 p-5 m-b-10">
                                            <div class="color-option-check">
                                                <h6 class="title text-center">theme-l.css</h6><label data-load-css="../assets/css/layouts/vertical/themes/theme-l.css">
                                                    <input type="radio" name="setting-theme">
                                                    <span class="icon-check"></span>
                                                    <span class="split">
                                                                                                                    <span class="color bg-menu-light"></span>
                                                                                                                    <span class="color bg-theme-l"></span>
                                                                                                                </span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3 p-5 m-b-10">
                                            <div class="color-option-check">
                                                <h6 class="title text-center">theme-m.css</h6><label data-load-css="../assets/css/layouts/vertical/themes/theme-m.css">
                                                    <input type="radio" name="setting-theme">
                                                    <span class="icon-check"></span>
                                                    <span class="split">
                                                                                                                    <span class="color bg-menu-light"></span>
                                                                                                                    <span class="color bg-theme-m"></span>
                                                                                                                </span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3 p-5 m-b-10">
                                            <div class="color-option-check">
                                                <h6 class="title text-center">theme-n.css</h6><label data-load-css="../assets/css/layouts/vertical/themes/theme-n.css">
                                                    <input type="radio" name="setting-theme">
                                                    <span class="icon-check"></span>
                                                    <span class="split">
                                                                                                                    <span class="color bg-menu-light"></span>
                                                                                                                    <span class="color bg-theme-n"></span>
                                                                                                                </span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3 p-5 m-b-10">
                                            <div class="color-option-check">
                                                <h6 class="title text-center">theme-o.css</h6><label data-load-css="../assets/css/layouts/vertical/themes/theme-o.css">
                                                    <input type="radio" name="setting-theme">
                                                    <span class="icon-check"></span>
                                                    <span class="split">
                                                                                                                    <span class="color bg-menu-light"></span>
                                                                                                                    <span class="color bg-theme-o"></span>
                                                                                                                </span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3 p-5 m-b-10">
                                            <div class="color-option-check">
                                                <h6 class="title text-center">theme-p.css</h6><label data-load-css="../assets/css/layouts/vertical/themes/theme-p.css">
                                                    <input type="radio" name="setting-theme" />
                                                    <span class="icon-check"></span>
                                                    <span class="split">
                                                                                                                    <span class="color bg-menu-light"></span>
                                                                                                                    <span class="color bg-theme-p"></span>
                                                                                                                </span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <h5 class="m-t-30 m-b-20">Layouts</h5>
                                    <ul class="list-reset">
                                        <li>
                                            <div class="custom-control custom-radio radio-primary form-check">
                                                <input type="radio" id="layoutStatic" name="layoutMode" class="custom-control-input" checked="checked" value="">
                                                <label class="custom-control-label" for="layoutStatic">Static Layout</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="custom-control custom-radio radio-primary form-check">
                                                <input type="radio" id="layoutFixed" name="layoutMode" class="custom-control-input" value="layout-fixed">
                                                <label class="custom-control-label" for="layoutFixed">Fixed Layout</label>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="tab-pane" id="sidebar-contact">
                                <!--START SEARCH WRAPPER -->
                                <div class="search-wrapper m-b-30">
                                    <button type="submit" class="search-button-submit"><i class="icon dripicons-search site-search-icon"></i></button>
                                    <input type="text" class="form-control search-input no-focus-border" placeholder="Search contacts...">
                                    <a href="javascript:void(0)" class="close-search-button" data-q-action="close-site-search">
                                        <i class="icon dripicons-cross site-search-close-icon"></i>
                                    </a>
                                </div>
                                <!--END START SEARCH WRAPPER -->
                                <!--START RIGHT SIDEBAR CONTACT LIST -->
                                <div class="qt-scroll" data-scroll="minimal-dark">
                                    <div class="list-view-group-header">a</div>
                                    <ul class="list-group p-0">
                                        <li class="list-group-item" data-chat="open" data-chat-name="John Smith">
                                            <span class="float-left"><img src="../assets/img/avatars/01.jpg" alt="" class="rounded-circle max-w-50 m-r-10"></span>
                                            <i class="badge mini success status"></i>
                                            <div class="list-group-item-body">
                                                <div class="list-group-item-heading">Abby Pugh</div>
                                                <div class="list-group-item-text">New York, NY</div>
                                            </div>
                                        </li>
                                        <li class="list-group-item" data-chat="open" data-chat-name="Allison Grayce">
                                            <span class="float-left"><img src="../assets/img/avatars/06.jpg" alt="" class="rounded-circle max-w-50 m-r-10"></span>
                                            <i class="badge mini success status"></i>
                                            <div class="list-group-item-body">
                                                <div class="list-group-item-heading">Allison Selleck</div>
                                                <div class="list-group-item-text">Seattle, WA</div>
                                            </div>
                                        </li>
                                    </ul>
                                    <div class="list-view-group-header">b</div>
                                    <ul class="list-group p-0">
                                        <li class="list-group-item" data-chat="open" data-chat-name="Ashley Ford">
                                            <span class="float-left"><img src="../assets/img/avatars/07.jpg" alt="" class="rounded-circle max-w-50 m-r-10"></span>
                                            <i class="badge mini success status"></i>
                                            <div class="list-group-item-body">
                                                <div class="list-group-item-heading">Bently Hinton</div>
                                                <div class="list-group-item-text">Denver, CO</div>
                                            </div>
                                        </li>
                                        <li class="list-group-item" data-chat="open" data-chat-name="Johanna Kollmann">
                                            <span class="float-left"><img src="../assets/img/avatars/11.jpg" alt="" class="rounded-circle max-w-50 m-r-10"></span>
                                            <i class="badge mini success status"></i>
                                            <div class="list-group-item-body">
                                                <div class="list-group-item-heading">Brad Friedman </div>
                                                <div class="list-group-item-text">Palo Alto, Ca</div>
                                            </div>
                                        </li>
                                        <li class="list-group-item" data-chat="open" data-chat-name="John Smith">
                                            <span class="float-left"><img src="../assets/img/avatars/02.jpg" alt="" class="rounded-circle max-w-50 m-r-10"></span>
                                            <i class="badge mini success status"></i>
                                            <div class="list-group-item-body">
                                                <div class="list-group-item-heading">Boston Nather</div>
                                                <div class="list-group-item-text">New York, NY</div>
                                            </div>
                                        </li>
                                        <li class="list-group-item" data-chat="open" data-chat-name="Allison Grayce">
                                            <span class="float-left"><img src="../assets/img/avatars/16.jpg" alt="" class="rounded-circle max-w-50 m-r-10"></span>
                                            <i class="badge mini success status"></i>
                                            <div class="list-group-item-body">
                                                <div class="list-group-item-heading">Brayan Bunnell</div>
                                                <div class="list-group-item-text">Seattle, WA</div>
                                            </div>
                                        </li>
                                    </ul>
                                    <div class="list-view-group-header">c</div>
                                    <ul class="list-group p-0">
                                        <li class="list-group-item" data-chat="open" data-chat-name="Ashley Ford">
                                            <span class="float-left"><img src="../assets/img/avatars/08.jpg" alt="" class="rounded-circle max-w-50 m-r-10"></span>
                                            <i class="badge mini success status"></i>
                                            <div class="list-group-item-body">
                                                <div class="list-group-item-heading">Carter Titchen</div>
                                                <div class="list-group-item-text">Denver, CO</div>
                                            </div>
                                        </li>
                                        <li class="list-group-item" data-chat="open" data-chat-name="Johanna Kollmann">
                                            <span class="float-left"><img src="../assets/img/avatars/13.jpg" alt="" class="rounded-circle max-w-50 m-r-10"></span>
                                            <i class="badge mini success status"></i>
                                            <div class="list-group-item-body">
                                                <div class="list-group-item-heading">Carla Fraser </div>
                                                <div class="list-group-item-text">Palo Alto, Ca</div>
                                            </div>
                                        </li>
                                    </ul>
                                    <div class="list-view-group-header">d</div>
                                    <ul class="list-group p-0">
                                        <li class="list-group-item" data-chat="open" data-chat-name="John Smith">
                                            <span class="float-left"><img src="../assets/img/avatars/03.jpg" alt="" class="rounded-circle max-w-50 m-r-10"></span>
                                            <i class="badge mini success status"></i>
                                            <div class="list-group-item-body">
                                                <div class="list-group-item-heading">David Petrie</div>
                                                <div class="list-group-item-text">New York, NY</div>
                                            </div>
                                        </li>
                                    </ul>
                                    <div class="list-view-group-header">e</div>
                                    <ul class="list-group p-0">
                                        <li class="list-group-item" data-chat="open" data-chat-name="Allison Grayce">
                                            <span class="float-left"><img src="../assets/img/avatars/12.jpg" alt="" class="rounded-circle max-w-50 m-r-10"></span>
                                            <i class="badge mini success status"></i>
                                            <div class="list-group-item-body">
                                                <div class="list-group-item-heading">Ellie Sweetser</div>
                                                <div class="list-group-item-text">Seattle, WA</div>
                                            </div>
                                        </li>
                                        <li class="list-group-item" data-chat="open" data-chat-name="Ashley Ford">
                                            <span class="float-left"><img src="../assets/img/avatars/09.jpg" alt="" class="rounded-circle max-w-50 m-r-10"></span>
                                            <i class="badge mini success status"></i>
                                            <div class="list-group-item-body">
                                                <div class="list-group-item-heading">Eric Eskridge</div>
                                                <div class="list-group-item-text">Denver, CO</div>
                                            </div>
                                        </li>
                                    </ul>
                                    <div class="list-view-group-header">f</div>
                                    <ul class="list-group p-0">
                                        <li class="list-group-item" data-chat="open" data-chat-name="John Smith">
                                            <span class="float-left"><img src="../assets/img/avatars/04.jpg" alt="" class="rounded-circle max-w-50 m-r-10"></span>
                                            <i class="badge mini success status"></i>
                                            <div class="list-group-item-body">
                                                <div class="list-group-item-heading">Farrah Yulikova</div>
                                                <div class="list-group-item-text">New York, NY</div>
                                            </div>
                                        </li>
                                        <li class="list-group-item" data-chat="open" data-chat-name="Allison Grayce">
                                            <span class="float-left"><img src="../assets/img/avatars/05.jpg" alt="" class="rounded-circle max-w-50 m-r-10"></span>
                                            <i class="badge mini success status"></i>
                                            <div class="list-group-item-body">
                                                <div class="list-group-item-heading">Florence Buren</div>
                                                <div class="list-group-item-text">Seattle, WA</div>
                                            </div>
                                        </li>
                                        <li class="list-group-item" data-chat="open" data-chat-name="Johanna Kollmann">
                                            <span class="float-left"><img src="../assets/img/avatars/14.jpg" alt="" class="rounded-circle max-w-50 m-r-10"></span>
                                            <i class="badge mini success status"></i>
                                            <div class="list-group-item-body">
                                                <div class="list-group-item-heading">Francesca Koehn </div>
                                                <div class="list-group-item-text">Palo Alto, Ca</div>
                                            </div>
                                        </li>
                                    </ul>
                                    <div class="list-view-group-header">g</div>
                                    <ul class="list-group p-0">
                                        <li class="list-group-item" data-chat="open" data-chat-name="Ashley Ford">
                                            <span class="float-left"><img src="../assets/img/avatars/10.jpg" alt="" class="rounded-circle max-w-50 m-r-10"></span>
                                            <i class="badge mini success status"></i>
                                            <div class="list-group-item-body">
                                                <div class="list-group-item-heading">Glynn Slade</div>
                                                <div class="list-group-item-text">Denver, CO</div>
                                            </div>
                                        </li>
                                    </ul>
                                    <div class="list-view-group-header">h</div>
                                    <ul class="list-group p-0">
                                        <li class="list-group-item" data-chat="open" data-chat-name="Johanna Kollmann">
                                            <span class="float-left"><img src="../assets/img/avatars/14.jpg" alt="" class="rounded-circle max-w-50 m-r-10"></span>
                                            <i class="badge mini success status"></i>
                                            <div class="list-group-item-body">
                                                <div class="list-group-item-heading">Haley Molaroni </div>
                                                <div class="list-group-item-text">Palo Alto, Ca</div>
                                            </div>
                                        </li>
                                    </ul>
                                    <div class="list-view-group-header">i</div>
                                    <ul class="list-group p-0">
                                        <li class="list-group-item" data-chat="open" data-chat-name="John Smith">
                                            <span class="float-left"><img src="../assets/img/avatars/07.jpg" alt="" class="rounded-circle max-w-50 m-r-10"></span>
                                            <i class="badge mini success status"></i>
                                            <div class="list-group-item-body">
                                                <div class="list-group-item-heading">Isaac Seldin</div>
                                                <div class="list-group-item-text">New York, NY</div>
                                            </div>
                                        </li>
                                        <li class="list-group-item" data-chat="open" data-chat-name="Allison Grayce">
                                            <span class="float-left"><img src="../assets/img/avatars/13.jpg" alt="" class="rounded-circle max-w-50 m-r-10"></span>
                                            <i class="badge mini success status"></i>
                                            <div class="list-group-item-body">
                                                <div class="list-group-item-heading">Ivy Dancelli</div>
                                                <div class="list-group-item-text">Seattle, WA</div>
                                            </div>
                                        </li>
                                    </ul>
                                    <div class="list-view-group-header">j</div>
                                    <ul class="list-group p-0">
                                        <li class="list-group-item" data-chat="open" data-chat-name="Ashley Ford">
                                            <span class="float-left"><img src="../assets/img/avatars/18.jpg" alt="" class="rounded-circle max-w-50 m-r-10"></span>
                                            <i class="badge mini success status"></i>
                                            <div class="list-group-item-body">
                                                <div class="list-group-item-heading">Jax Scharf</div>
                                                <div class="list-group-item-text">Denver, CO</div>
                                            </div>
                                        </li>
                                        <li class="list-group-item" data-chat="open" data-chat-name="Johanna Kollmann">
                                            <span class="float-left"><img src="../assets/img/avatars/17.jpg" alt="" class="rounded-circle max-w-50 m-r-10"></span>
                                            <i class="badge mini success status"></i>
                                            <div class="list-group-item-body">
                                                <div class="list-group-item-heading">Jen Pritsinas </div>
                                                <div class="list-group-item-text">Palo Alto, Ca</div>
                                            </div>
                                        </li>
                                    </ul>
                                    <div class="list-view-group-header">m</div>
                                    <ul class="list-group p-0">
                                        <li class="list-group-item" data-chat="open" data-chat-name="Ashley Ford">
                                            <span class="float-left"><img src="../assets/img/avatars/20.jpg" alt="" class="rounded-circle max-w-50 m-r-10"></span>
                                            <i class="badge mini success status"></i>
                                            <div class="list-group-item-body">
                                                <div class="list-group-item-heading">Marco Heginbotham</div>
                                                <div class="list-group-item-text">Denver, CO</div>
                                            </div>
                                        </li>
                                        <li class="list-group-item" data-chat="open" data-chat-name="Johanna Kollmann">
                                            <span class="float-left"><img src="../assets/img/avatars/21.jpg" alt="" class="rounded-circle max-w-50 m-r-10"></span>
                                            <i class="badge mini success status"></i>
                                            <div class="list-group-item-body">
                                                <div class="list-group-item-heading">Marisa Gelber </div>
                                                <div class="list-group-item-text">Palo Alto, Ca</div>
                                            </div>
                                        </li>
                                    </ul>
                                    <div class="list-view-group-header">p</div>
                                    <ul class="list-group p-0">
                                        <li class="list-group-item" data-chat="open" data-chat-name="Ashley Ford">
                                            <span class="float-left"><img src="../assets/img/avatars/22.jpg" alt="" class="rounded-circle max-w-50 m-r-10"></span>
                                            <i class="badge mini success status"></i>
                                            <div class="list-group-item-body">
                                                <div class="list-group-item-heading">Penny Withka</div>
                                                <div class="list-group-item-text">Denver, CO</div>
                                            </div>
                                        </li>
                                        <li class="list-group-item" data-chat="open" data-chat-name="Johanna Kollmann">
                                            <span class="float-left"><img src="../assets/img/avatars/23.jpg" alt="" class="rounded-circle max-w-50 m-r-10"></span>
                                            <i class="badge mini success status"></i>
                                            <div class="list-group-item-body">
                                                <div class="list-group-item-heading">Pixie Clayborne </div>
                                                <div class="list-group-item-text">Palo Alto, Ca</div>
                                            </div>
                                        </li>
                                    </ul>
                                    <div class="list-view-group-header">s</div>
                                    <ul class="list-group p-0">
                                        <li class="list-group-item" data-chat="open" data-chat-name="Ashley Ford">
                                            <span class="float-left"><img src="../assets/img/avatars/25.jpg" alt="" class="rounded-circle max-w-50 m-r-10"></span>
                                            <i class="badge mini success status"></i>
                                            <div class="list-group-item-body">
                                                <div class="list-group-item-heading">Sheldon Luntz</div>
                                                <div class="list-group-item-text">Denver, CO</div>
                                            </div>
                                        </li>
                                        <li class="list-group-item" data-chat="open" data-chat-name="Johanna Kollmann">
                                            <span class="float-left"><img src="../assets/img/avatars/26.jpg" alt="" class="rounded-circle max-w-50 m-r-10"></span>
                                            <i class="badge mini success status"></i>
                                            <div class="list-group-item-body">
                                                <div class="list-group-item-heading">Sam Kendall </div>
                                                <div class="list-group-item-text">Palo Alto, Ca</div>
                                            </div>
                                        </li>
                                    </ul>
                                    <div class="list-view-group-header">z</div>
                                    <ul class="list-group p-0">
                                        <li class="list-group-item" data-chat="open" data-chat-name="Ashley Ford">
                                            <span class="float-left"><img src="../assets/img/avatars/27.jpg" alt="" class="rounded-circle max-w-50 m-r-10"></span>
                                            <i class="badge mini success status"></i>
                                            <div class="list-group-item-body">
                                                <div class="list-group-item-heading">Zack Mohanram</div>
                                                <div class="list-group-item-text">Denver, CO</div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <!--END RIGHT SIDEBAR CONTACT LIST -->
                            </div>
                        </div>
                    </div>
                </div>
            </aside>
            <!-- END SIDEBAR QUICK PANNEL WRAPPER -->

        <? } ?>

    </div>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
