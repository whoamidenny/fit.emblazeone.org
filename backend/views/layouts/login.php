<?php
    use backend\assets\AppAsset;
    use yii\helpers\Html;

    $bundle=AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<!--[if lt IE 7]> <html class="ie lt-ie9 lt-ie8 lt-ie7" lang="<?= Yii::$app->language ?>"> <![endif]-->
<!--[if IE 7]>    <html class="ie lt-ie9 lt-ie8" lang="<?= Yii::$app->language ?>"> <![endif]-->
<!--[if IE 8]>    <html class="ie lt-ie9" lang="<?= Yii::$app->language ?>"> <![endif]-->
<!--[if gt IE 8]> <html class="ie gt-ie8" lang="<?= Yii::$app->language ?>"> <![endif]-->
<!--[if !IE]><!--><html lang="<?= Yii::$app->language ?>"><!-- <![endif]-->

<head>
    <meta charset="<?= Yii::$app->charset ?>"/>

    <meta name="theme-color" content="#ffffff">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE" />

    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="login content-menu">
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

<!--    <div class="lang-login-box">-->
<!--        <a href="--><?//=Yii::$app->lang->getLanguageUrl('uk_UA')?><!--" class="flag-icon flag-icon-ua"></a>-->
<!--        <a href="--><?//=Yii::$app->lang->getLanguageUrl('ru_RU')?><!--" class="flag-icon flag-icon-ru"></a>-->
<!--        <a href="--><?//=Yii::$app->lang->getLanguageUrl('en_US')?><!--" class="flag-icon flag-icon-us"></a>-->
<!--    </div>-->
    <div class="container">
        <?=$content ?>
    </div>


    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
