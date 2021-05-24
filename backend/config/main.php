<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

$config = [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'homeUrl' => '/admin',
    'components' => [
        'request' => [
            'baseUrl' => '/admin',
            'enableCsrfValidation' => false
        ],
        'urlManager' => [
            'class'=>'wbp\urlManager\UrlManager',
            'ruleConfig'=>['class'=>'\wbp\urlManager\UrlRule'],
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '' => 'site/index',
                'login' => 'auth/login',
                'logout' => 'auth/logout',
                'auth' => 'auth/auth',
                '/elfinder/<action:[\w\-]+>' => 'elfinder/<action>',        // FOR CKEDITOR UPLOADER
                '<module:[\w\-]+>' => '<module>/default/index',

                '<module:[\w\-]+>/<action:(edit|add|view)>' => '<module>/default/<action>',
                '<module:[\w\-]+>/<action:(get-shopping-cart|get-regions)>' => '<module>/default/<action>',     //for orders
                '<module:[\w\-]+>/<controller:[\w\-]+>' => '<module>/<controller>/index',
                '<module:[\w\-]+>/<controller:[\w\-]+>/<action:[\w\-]+>' => '<module>/<controller>/<action>',
                '<module:[\w\-]+>/<action>' => '<module>/default/<action>'

            ],
        ],
        'user' => [
            'class'=> 'common\models\User',
            'identityClass' => 'common\models\Identity',
            'enableAutoLogin' => true,
            'loginUrl' => ['auth/login'],
            'identityCookie' => [
                'name' => '_backendUser', // unique for backend
                'path'=>'/backend/web'  // correct path for the backend app.
            ]
        ],
        'session' => [
            'name' => '_backendSessionId', // unique for backend
            'savePath' => __DIR__ . '/../runtime', // a temporary folder on backend
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'i18n'=>array(
            'translations' => array(
                '*'=>array(
                    'class' => 'wbp\lang\PhpMessageSource',
                    'basePath' => "@backend/messages",
                    'sourceLanguage' => 'en_US',
                    'fileMap' => array(
                    )
                ),
            )
        ),
        'lang' => [
            'class'=>'wbp\lang\Lang',
            'languages'=>[
                'en_US'=>'',
//                'ru_RU'=>'',
//                'en_US'=>'en',
//                'uk_UA'=>'ua',
            ],
            'languagesUrls'=>[
                'en_US'=>'',
//                'ru_RU'=>'',
//                'en_US'=>'en',
//                'uk_UA'=>'ua',
            ],
        ],
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
        ]

    ],
    'params' => $params,

    'language' => 'en_US',
    'sourceLanguage' => 'en_US',
    'aliases' => [
        '@iPaya/Yii2/FontAwesome/WebFontsWithCss/assets' => '@vendor/yiizh/yii2-fontawesome/src/WebFontsWithCss/assets',
        '@mihaildev/ckeditor/editor' => '@vendor/mihaildev/yii2-ckeditor/editor',
    ],
    'controllerMap' => [
        'elfinder' => [
            'class' => 'mihaildev\elfinder\Controller',
            'access' => ['@'],
            'disabledCommands' => ['netmount'],
            'roots' => [
                [
                    'baseUrl'=>'',
                    'basePath'=>'@serverDocumentRoot',
                    'path' => 'uploads',
                    'name' => 'Uploads',
                ],
            ],
        ]
    ],
    'modules'=>[],
];
$scan = scandir($_SERVER['DOCUMENT_ROOT'].'/backend/modules');
foreach($scan as $file) {
    if (is_dir($_SERVER['DOCUMENT_ROOT']."/backend/modules/".$file)) {
        if($file!='..' && $file!='.'){
            $config['modules'][$file]=['class' => 'backend\\modules\\'.$file.'\\Module'];
        }
    }
}

return $config;