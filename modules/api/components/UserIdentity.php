<?php

namespace app\modules\api\components;

use app\models\User;

/**
 * @author Garry
 */
class UserIdentity {

	public static function findIdentityByAccessToken($token, $type = null) {
		//Sesuaikan rule login yang berlaku.
		return User::findOne(['access_token' => $token]);
	}

}
