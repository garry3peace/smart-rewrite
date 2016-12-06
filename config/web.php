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
        'request' => [
            'cookieValidationKey' => 'y1kB87_prXcWGfjAW5IlB2u1vMWYZfxB',
			'hostInfo'=>'http://localhost:8080',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser', // required for POST input via `php://input`
            ]
        ],
		'response' => [
			'formatters' => [
				\yii\web\Response::FORMAT_JSON => [
					'class' => 'yii\web\JsonResponseFormatter',
					'prettyPrint' => YII_DEBUG, // use "pretty" output in debug mode
					'encodeOptions' => JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE,
				],
			],
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
