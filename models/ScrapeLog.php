<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "scrape_log".
 *
 * @property integer $id
 * @property string $url
 * @property string $domain
 * @property string $title
 * @property string $content
 * @property integer $posted
 * @property string $create_date
 * @property string $updated_date
 */
class ScrapeLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'scrape_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['url', 'domain', 'posted'], 'required'],
            [['content'], 'string'],
            [['posted'], 'integer'],
            [['create_date', 'updated_date'], 'safe'],
			['create_date', 'default', 'value' => date('Y-m-d H:i:s'),'on'=>'insert'],
			['updated_date', 'default', 'value' => date('Y-m-d H:i:s'),'on'=>'update'],
            [['url'], 'string', 'max' => 3000],
            [['domain'], 'string', 'max' => 300],
            [['title'], 'string', 'max' => 1000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url' => 'Url',
            'domain' => 'Domain',
            'title' => 'Title',
            'content' => 'Content',
            'posted' => 'Posted',
            'create_date' => 'Create Date',
            'updated_date' => 'Updated Date',
        ];
    }
	
	public static function findByUrl($url)
	{
		return self::findOne(['url'=>$url]);
	}
	
	public static function create()
	{
		$this->url;
		
	}
	
	public function isPosted()
	{
		if($this->posted==1){
			return true;
		}
		
		return false;
	}
	
	public function beforeValidate()
	{
		
		$domain = new \app\components\web\Domain($this->url);
		$this->domain = $domain->getHostname();
		
		return parent::beforeValidate();
	}
	
	/**
	 * Function to check whether the code is success
	 * @param type $code
	 * @return boolean
	 */
	private static function isSuccess($code)
	{
		if($code==200 ||$code==201){
			return true;
		}
		return false;
	}
	
	/**
	 * Insert log after posting the article
	 * @param type $data
	 */
	public static function logPost($data)
	{
		$scrapeLog = new self;
		$scrapeLog->scenario = 'insert';
		$scrapeLog->title = $data['title'];
		$scrapeLog->content = $data['content'];
		$scrapeLog->url = $data['url'];
		$scrapeLog->posted = self::isSuccess($data['code'])?1:0;
		return $scrapeLog->save();
	}
	
	/**
	 * Log the URL that doesn't containt article, hence it is empty
	 * @param type $data
	 * @return type
	 */
	public static function logExclusion($data)
	{
		$url = $data['url'];
		$scrapeLog = ScrapeLog::findByUrl($url);
		if(empty($scrapeLog)){
			$scrapeLog = new self;
			$scrapeLog->scenario = 'insert';
			$scrapeLog->title = null;
			$scrapeLog->content = null;
			$scrapeLog->url = $url;
			$scrapeLog->posted = 1;
		}else{
			$scrapeLog->posted = 1;
		}
		
		return $scrapeLog->save();
	}
	
	/**
	 * Update the log if system try repost.
	 * @param type $data
	 * @return type
	 */
	public static function logUpdate($data)
	{
		$url = $data['url'];
		$scrapeLog = ScrapeLog::findByUrl($url);
		if(empty($scrapeLog)){
			return false;
		}
		
		$scrapeLog->scenario = 'update';
		$scrapeLog->title = $data['title'];
		$scrapeLog->content = $data['content'];
		$scrapeLog->url = $data['url'];
		$scrapeLog->posted = self::isSuccess($data['code'])?1:0;
		return $scrapeLog->save();
	}
	
	public static function findAllNotPosted()
	{
		return self::findAll(['posted'=>0]);
	}
}
