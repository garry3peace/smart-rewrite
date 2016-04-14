<?php

namespace app\models;

use app\components\WordHelper;


use Yii;

/**
 * This is the model class for table "synonym".
 *
 * @property integer $id
 * @property integer $word_from_id
 * @property integer $word_to_id
 */
class Synonym extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'synonym';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['word_from_id','word_to_id'], 'required'],
            [['word_from_id','word_to_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'word_from_id' => 'Word From',
            'word_to_id' => 'Word To',
        ];
    }
	
	/**
	 * create the antonym relation between two words
	 * to make them as a synonym
	 * @param  string $source
	 * @param  string $target
	 */
	public static function create($source, $target)
	{
		$source= trim($source);
		$target = trim($target);
		
		if($source == $target){
			return false;
		}
		
		$sourceObj = Word::findByName($source, $createNew = true);
		$targetObj = Word::findByName($target, $createNew = true);
		
		//create new Synonym
		$synonym = new self;
		$synonym->word_from_id = $sourceObj->id;
		$synonym->word_to_id = $targetObj->id;
		$synonym->save();		
	}
	
	/**
	 * Finding the correspondent words 
	 * Return in spin format text
	 * @param type $sourceWord
	 * @return type
	 */
	public static function get($sourceWord){
		$word = Word::findByName($sourceWord);
		if(!$word){
			return [];
		}
		
		$synonyms = self::findAll(['word_from_id'=>$word->id]);
		if(!$synonyms){
			return [];
		}
		
		$arrText = [];
		foreach($synonyms as $text){
			$result = Word::findOne($text->word_to_id)->name;
			$arrText[] = WordHelper::process($sourceWord, $result);
		}

		return $arrText;
	}
}
