<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "lemma".
 *
 * @property integer $id
 * @property string $name
 * @property string $type
 * @property string $note
 */
class Lemma extends \yii\db\ActiveRecord
{
	const ADJECTIVE = 'adj';
	const PRONOUN = 'pro';
	const VERB = 'v';
	const ADVERB = 'adv';
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lemma';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'type', 'note'], 'required'],
            [['note'], 'string'],
            [['name', 'type'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'type' => 'Type',
            'note' => 'Note',
        ];
    }
	
	/**
	 * Function to know certain word is certain type
	 * @param string $name
	 * @param string $type
	 * @return boolean
	 */
	public static function is($name, $type)
	{
		$count = self::find()->where(['name'=>$name,'type'=>$type])->count();
		if($count>0){
			return true;
		}else{
			return false;
		}
	}
	
	public static function isAdjective($name)
	{
		return self::is($name, self::ADJECTIVE);
	}
	
	public static function isVerb($name)
	{
		return self::is($name, self::VERB);
	}

}