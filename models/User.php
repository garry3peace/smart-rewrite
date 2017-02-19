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
	const ROLE_TENANT = 'tenant';
    
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

    public function rules()
	{	
		$rules = parent::rules();
		unset ($rules['usernameMatch']);
        unset ($rules['emailUnique']);
		unset ($rules['emailPattern']);
		
		$rules['emailPattern']=['email', 'match', 'pattern'=> '/^[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-_]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-_]*[a-zA-Z0-9])?$/'];
		$rules['usernameMatch'] = ['username', 'match', 'pattern' => '/^[-a-zA-Z0-9_\.@\/]+$/'];
		return $rules;
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

    /**
     * @return ActiveQuery
     */
    public function getProfile()
    {
//        return $this->hasOne(Profile::className(), ['user_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getSocialAccounts()
    {
        return $this->hasMany(SocialAccount::className(), ['user_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTokens()
    {
        return $this->hasMany(Token::className(), ['user_id' => 'id']);
    }
    
    public function getUser() 
    {
        if(!empty(Yii::$app->user->id))
        {
            return User::findOne(Yii::$app->user->id);
        }
    }
    
    public function getProfileName()
	{
		$profile = $this->profile;
		if(!$profile) {
			return null;
		}
		
		if($profile->name==null || $profile=='') {
			return null;
		}
		
		return $profile->name;
	}
	
	public function afterCreate()
	{
		$this->assignAccess([self::ROLE_TENANT]);
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
    
    public function beforeValidate() 
    {
        if(preg_match('/\s/', $this->username))
        {
            $this->username = str_replace(' ', '/', $this->username);
        }
        
//        $model = User::find()->column('email');
//        var_dump($model); die;
//        foreach($model as $data)
//        {
//            if($data->email == $this->email)
//            {
//                return $this->email = 'xxx'.strtolower($this->username).'@ltc-glodok.com';
//            }
//        }
        
        if(empty($this->email))
        {
            $this->email = 'xxx'.uniqid().'@ltc-glodok.com';
        }
        $this->password_hash = '$2y$10$Vsaa3i65tmk3YBXPcJO2oe3TzQ0YS8dq44eGWkerAjf2we2RD54sy';
        $this->auth_key = 'L566e1eTfo--gZBLKXHnd09kKiH8aI8l';
        $this->confirmed_at = '1486967246';
        $this->unconfirmed_email = '';
        $this->blocked_at = '';
        $this->registration_ip = '::1';
        $this->created_at = '1486967245';
        $this->updated_at = '1486967245';
        $this->flags = '0';
//        var_dump($this); die;
        return parent::beforeValidate();
    }
        
    public function processFile(){
		
		$this->file = UploadedFile::getInstance($this, 'file');
        
		return UserMap::getData();
	}
    
}
