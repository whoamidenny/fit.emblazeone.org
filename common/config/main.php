<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],

        'assetManager' => [
            'bundles' => [
            ],
            'linkAssets' => true,
        ],
        'urlManager' => [
            'rules' => [
                'images/image-by-item' => 'im/images/image-by-item',
            ]
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
                [
                    'class' => 'yii\log\EmailTarget',
                    'mailer' => 'mailer',
                    'levels' => ['error', 'warning'],
                    'except' => [
                        'yii\web\HttpException:404',
                        'yii\web\HttpException:403',
                    ],
                    'message' => [
                        'from' => ['fit@emblazeone.org'],
                        'to' => ['pavel.chernetsky@gmail.com'],
                        'subject' => 'Limitless Fit Error',
                    ],
                ],
            ],
        ],
        'formatter' => [
            'class' => 'wbp\formatter\Formatter',
            'currencyCode' => 'USD',
        ],
        'encrypter' => [
            'class' => '\wbp\cipher\Cipher',
        ],
    ],
    'timeZone' => 'Europe/Kiev',

    'language' => 'ru-RU',
    'sourceLanguage' => 'ru-RU',
    'aliases' => [
        '@wbp' => '@vendor/wbp',
        '@serverDocumentRoot' => $_SERVER['DOCUMENT_ROOT'],
        '@mobile' => '@serverDocumentRoot/mobile',
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'modules' => [
        'im' => [
            'class' => 'wbp\images\Module',
            //be sure, that permissions ok
            //if you cant avoid permission errors you have to create "images" folder in web root manually and set 777 permissions
            'imagesStorePath' => '@serverDocumentRoot/images/source', //path to origin images
            'imagesCachePath' => '@serverDocumentRoot/images/cache', //path to resized copies
            'graphicsLibrary' => 'GD', //but really its better to use 'Imagick'
            'placeHolderPath' => '@serverDocumentRoot/images/noimage.png', // if you want to get placeholder when image not exists, string will be processed by Yii::getAlias
        ],
        'video' => [
            'class' => 'wbp\video\Module',
            'videoStorePath' => '@serverDocumentRoot/video/source', //path to origin images
        ],
        'file' => [
            'class' => 'wbp\file\Module',
            'fileStorePath' => '@serverDocumentRoot/files/source', //path to origin images
        ],
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
//                'URL' => '/'
                ],
            ],
        ]
    ],
];
