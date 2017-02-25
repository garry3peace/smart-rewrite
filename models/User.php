<?php

namespace app\models;

use app\components\UserMap;
use dektrium\user\models\User as BaseUser;
use mdm\admin\models\Assignment;
use Symfony\Component\CssSelector\Parser\Token;
use Yii;
use yii\db\ActiveQuery;
use yii\debug\models\search\Profile;
use yii\web\IdentityInterface;
use yii\web\UploadedFile;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $email
 * @property string $password_hash
 * @property string $auth_key
 * @property integer $confirmed_at
 * @property string $unconfirmed_email
 * @property integer $blocked_at
 * @property string $registration_ip
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $flags
 *
 * @property Profile $profile
 * @property SocialAccount[] $socialAccounts
 * @property Token[] $tokens
 */
class User extends BaseUser implements IdentityInterface
{
	const ROLE_USER = 'user';
    
    public $file;

	public function init()
	{
		$this->on(self::AFTER_CREATE, [$this, 'afterCreate']);
	}
		
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'email' => 'Email',
            'password_hash' => 'Password Hash',
            'auth_key' => 'Auth Key',
            'confirmed_at' => 'Confirmed At',
            'unconfirmed_email' => 'Unconfirmed Email',
            'blocked_at' => 'Blocked At',
            'registration_ip' => 'Registration Ip',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'flags' => 'Flags',
        ];
    }

    
    
    public function getUser() 
    {
        if(!empty(Yii::$app->user->id))
        {
            return User::findOne(Yii::$app->user->id);
        }
    }
    
	
	public function afterCreate()
	{
		$this->assignAccess([self::ROLE_USER]);
		return true;
	}
	
	/**
	 * assign role to access user
	 * 
	 * @param array $roles
	 * @return boolean
	 */
	private function assignAccess($roles)
	{
		$items = [$roles];
		$model = new Assignment($this->id);
		$model->assign($items);
		
		return true;
	}

}
