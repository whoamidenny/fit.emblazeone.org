<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'homeUrl' => '/',
    'components' => [
        'request' => [
            'class'=>'wbp\lang\LangRequest',
            'csrfParam' => '_csrf-frontend',
            'baseUrl' => '',
        ],
        'user' => [
            'class'=> 'common\models\User',
            'identityClass' => 'backend\modules\clients\models\Client',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
            'loginUrl' => ['auth/login'],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'class'=>'wbp\urlManager\UrlManager',
            'ruleConfig'=>['class'=>'\wbp\urlManager\UrlRule'],
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '' => 'site/index',
                'auth'=>'auth/auth',
            ],

        ],
        'lang' => [
            'class'=>'wbp\lang\Lang',
            'languages'=>[
                'en_US'=>'',
//                'en_US'=>'en',
//                'uk_UA'=>'ua',
            ],
            'languagesUrls'=>[
                'en_US'=>'',
//                'en_US'=>'en',
//                'uk_UA'=>'uk',
            ],
        ],
        'i18n'=>array(
            'translations' => array(
                '*'=>array(
                    'class' => 'wbp\lang\PhpMessageSource',
                    'develop'=>true,
                    'basePath' => "@app/messages",
                    'sourceLanguage' => 'en_US',
                    'fileMap' => array(
                    )
                ),
            )
        ),
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'facebook' => [
                    'class' => 'yii\authclient\clients\Facebook',
                    'clientId' => '382491966515122',
                    'clientSecret' => 'f7a97ce3dd275fde6cfb12e7417d9b01',
                    'scope' => 'email',
                ],
//                'twitter' => [
//                    'class' => 'yii\authclient\clients\Twitter',
//                    'consumerKey' => 'xxxxxxxxxx',
//                    'consumerSecret' => 'yyyyyyyyyy',
//                ],
                'google' => [
                    'class' => 'yii\authclient\clients\Google',
                    'clientId' => '340814934216-b7g8hifec635o6ln495fgl08rt19cbu9.apps.googleusercontent.com',
                    'clientSecret' => 'tKARSYDAi8CV4CGuAcOUYC_0',
                ],
            ]
        ],
		'formatter' => [
			'class' => 'yii\i18n\Formatter',
			'nullDisplay' => '-',
			'dateFormat' => 'd/M/Y',
			'datetimeFormat' => 'd-M-Y H:i:s',
			'timeFormat' => 'H:i:s',
		],
//        'view' => [
//            'class' => '\rmrevin\yii\minify\View',
//            'enableMinify' => false,//!YII_DEBUG,
//            'concatCss' => true, // concatenate css
//            'minifyCss' => true, // minificate css
//            'concatJs' => true, // concatenate js
//            'minifyJs' => true, // minificate js
//            'minifyOutput' => true, // minificate result html page
//            'webPath' => '@web', // path alias to web base
//            'basePath' => '@webroot', // path alias to web base
//            'minifyPath' => '@webroot/minify', // path alias to save minify result
//            'jsPosition' => [ \yii\web\View::POS_END ], // positions of js files to be minified
//            'forceCharset' => 'UTF-8', // charset forcibly assign, otherwise will use all of the files found charset
//            'expandImports' => true, // whether to change @import on content
//            'compressOptions' => ['extra' => true], // options for compress
////            'excludeFiles' => [
////                'jquery.js', // exclude this file from minification
////                'app-[^.].js', // you may use regexp
////            ],
//        ],
    ],
    'language' => 'en_US',
    'sourceLanguage' => 'en_US',
    'params' => $params,
	'aliases' => [
		'@yii/authclient/assets' => '@vendor/yiisoft/yii2-authclient/src/assets',
		'@yii/authclient/widgets' => '@vendor/yiisoft/yii2-authclient/src/widgets',
		//'@yii/httpclient/Client' => '@vendor/yiisoft/yii2-httpclient/src/Client',
		'@keygenqt/ImageAjax/ImageAjax' => '@vendor/keygenqt/yii2-image-ajax/ImageAjax',
		'@keygenqt/imageAjax/assets' => '@vendor/keygenqt/yii2-image-ajax/assets',
		'@keygenqt/imageAjax/views/view' => '@vendor/keygenqt/yii2-image-ajax/views/view',
	],
];
