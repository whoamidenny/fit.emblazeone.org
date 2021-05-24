<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $sourcePath = '@frontend/web/theme';
    public $css = [
        'css/style.css',
    ];
    public $js = [
        'https://kit.fontawesome.com/fc70117f52.js',
        'https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js',
        'https://cdn.jwplayer.com/libraries/46zsTJPm.js',
        'js/scripts.js',
        'js/Chart.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset',
    ];
}
