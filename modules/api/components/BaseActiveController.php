<?php

namespace app\modules\api\components;

use yii\filters\Cors;
use yii\rest\ActiveController;
use yii\filters\auth\QueryParamAuth;

/**
 * Description of BaseActiveController
 *
 * @author Hendri
 */
class BaseActiveController extends ActiveController {

	public function behaviors() {
		$behaviors = parent::behaviors();

		unset($behaviors['authenticator']);

		$behaviors['corsFilter'] = [
			'class' => Cors::className(),
			'cors' => [
				'Origin' => ['*'],
				'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
				'Access-Control-Request-Headers' => ['*'],
				'Access-Control-Allow-Credentials' => true,
			],
		];

//		$behaviors['authenticator'] = [
//			'class' =>  QueryParamAuth::className(),
//			'except' => ['options','login'],
//		];

		return $behaviors;
	
	}

}
