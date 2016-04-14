<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "word".
 *
 * @property integer $id
 * @property string $name
 * @property string $type
 * @property string $note
 * @property integer $base_id
 */
class Word extends \yii\db\ActiveRecord
{
	const VERB = 'v';
	const ADJECTIVE = 'a';
	const NOUN = 'n';
	
	const DEFULT_TYPE = 'a';
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'word';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'type'], 'required'],
            [['note'], 'string'],
            [['phrase_count'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['type'], 'string', 'max' => 1],
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
            'base_id' => 'Base ID',
        ];
    }
	
	public static function find()
    {
		return new WordQuery(get_called_class());
    }
	
	/**
	 * Find the object Word that match the given string
	 * @param $name the name of the word
	 * @param $createNew if the word is not existing in the database
	 */
	public static function findByName($name, $createNew=false)
	{
		$model = self::findOne(['name'=>$name]);
		
		//if the word not exists and createNew is true
		if(!$model && $createNew){
			$model = new self;
			$model->name = $name;
			$model->phrase_count = count(explode(' ',$name));
			$model->type = self::DEFULT_TYPE; //default. We don't want to make the system too complex yet
			$model->save();
		}
		
		return $model;
	}
	
	/**
	 * In Indonesia "Not" can be represnt "Tidak" or "Bukan" 
	 * depend on the word after it whether noun or verb 
	 */
	public function getNegativeAdverb()
	{
		switch($this->type){
			case self::VERB : 
			case self::ADJECTIVE : return 'tidak';
			case self::NOUN: return 'bukan';
		}
	}
	
	public static function count()
	{
		$connection = Yii::$app->getDb();
		$command = $connection->createCommand('
			SELECT COUNT(`id`) AS total
			FROM `word`');
		$result = $command->queryScalar();
		return $result;
	}
	
}

class WordQuery extends ActiveQuery
{
	public function allPhrase()
    {
        return $this->andWhere('phrase_count > 1');
    }
}