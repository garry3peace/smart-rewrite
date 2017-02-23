<?php

namespace app\modules\api;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\api\controllers';

    public function init()
    {
        parent::init();

		\Yii::$app->set('user', [
			'class'=> '\yii\web\User',
			'enableSession' => FALSE,
			'identityClass' => 'app\modules\api\components\UserIdentity',
			'enableAutoLogin' => FALSE,
			'loginUrl'=> null, //muncul error 401 alih-alih redirect ke login
		]);
		
		\Yii::$app->set('request',[
			'class'=> '\yii\web\Request',
			'cookieValidationKey' => 'wKlapzDu3wK_uOWmHSvouyNkydiTEf8r',
			'parsers' => [
				'application/json' => 'yii\web\JsonParser',
			],
		]);
		
		//memaksa API selalu return dalam format JSON
		\Yii::$app->set('response',[
			'class'=> '\yii\web\Response',
			'format'=>\yii\web\Response::FORMAT_JSON,
		]);
    }
}
