<?php
use app\components\Debug;

$params = require(__DIR__ . '/params.php');

require_once(__DIR__ . '/theme.php');
$theme = new ThemeConfig('lumino');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
		'assetManager' => [
            'class' => 'yii\web\AssetManager',
            'bundles' => [
                        'yii\web\JqueryAsset' => [
                            'js' => [
                                YII_ENV_DEV ? 'jquery.js' : 'jquery.min.js'
                            ]
                        ],
                        'yii\bootstrap\BootstrapAsset' => [
                            'css' => [
                                YII_ENV_DEV ? 'css/bootstrap.css' : 'css/bootstrap.min.css',
                            ]
                        ],
                        'yii\bootstrap\BootstrapPluginAsset' => [
                            'js' => [
                                YII_ENV_DEV ? 'js/bootstrap.js' : 'js/bootstrap.min.js',
                            ]
                        ]
            ],
        ],
		'request' => [
            'cookieValidationKey' => 'IVMLLeG2LiF02R9xioXAowX5TiDGqJ-p',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
           // 'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,
			'transport' => [
				'class' => 'Swift_SmtpTransport',
				'host' => 'belphegor.in-hell.com',
				'username' => 'no-reply@smartrewrite.com',
				'password' => 'appl4b00k',
				'port' => '465',
				'encryption' => 'ssl',
			],
        ],
        'log' => [
			'logger' => Yii::createObject('yii\log\Logger'),
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
		'wordCache' => [
		   'class' => 'app\components\WordCache',
		],
		
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
		'view' => [
            'theme' => $theme->getConfig(),
		],
		'authManager' => [
			'class' => 'yii\rbac\DbManager',
			'defaultRoles' => ['guest'], //role biasa
		],
	],
	'modules'=>[
		'user' => [
            'class' => 'dektrium\user\Module',
            'admins'=>['bisto890'],
			'modelMap' => [
				'User' => 'app\models\User',
			],
		],
        'admin' => [
			'class' => 'mdm\admin\Module',
            'layout' => '@app/themes/layouts/main',
		],
        'api' => [
            'class' => 'app\modules\api\Module',
        ],
	],
	'as access' => [
		'class' => 'mdm\admin\components\AccessControl',
		'allowActions' => [
			'api/*',
			'site/*',
			'user/*'
		]
	],
	'params' => $params,
];

require_once(__DIR__.'/../components/Debug.php');
$config['components']['db'] = require(__DIR__ .'/'. (Debug::isLocal()?'dev-db.php':'prod-db.php'));

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
