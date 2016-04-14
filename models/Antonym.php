<?php

namespace app\models;

use app\components\WordHelper;


use Yii;

/**
 * This is the model class for table "antonym".
 *
 * @property integer $id
 * @property integer $word_from_id
 * @property integer $word_to_id
 */
class Antonym extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'antonym';
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
	 * to make them as a antonym
	 * @param string $source
	 * @param string $target
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
		
		//create new Antonym
		$antonym = new self;
		$antonym->word_from_id = $sourceObj->id;
		$antonym->word_to_id = $targetObj->id;
		$antonym->save();		
	}
	
	/**
	 * Finding the correspondent words 
	 * Return in spin format text
	 * @param type $sourceWord
	 * @return type
	 */
	public static function get($sourceWord, $showNegation=true){
		$negationWord = '';
		
		$word = Word::findByName($sourceWord);
		if(!$word){
			return [];
		}
		
		$antonyms = self::findAll(['word_from_id'=>$word->id]);
		if(!$antonyms){
			return [];
		}
		
		$arrText = [];
		foreach($antonyms as $text){
			$wordObj = Word::findOne($text->word_to_id);
			
			if($showNegation){
				$negationWord = $wordObj->getNegativeAdverb().' ';
			}
			$result = $negationWord.$wordObj->name;
			$arrText[] = WordHelper::process($sourceWord, $result);
		}

		return $arrText;
	}
}
