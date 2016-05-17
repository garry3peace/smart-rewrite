<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "log".
 *
 * @property integer $id
 * @property string $content
 * @property string $config
 */
class Log extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content', 'config'], 'required'],
            [['content', 'config'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'content' => 'Content',
            'config' => 'Config',
        ];
    }
	
	/**
	 * Adding the rewrite into the log
	 * @param string $content
	 * @param array $config
	 * @return boolean success or failed
	 */
	public function add($content, $config)
	{
		$this->content = $content;
		$this->config = json_encode($config);
		return $this->save();
	}
}
