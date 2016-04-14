<?php
/* 
 * SpinFormat to manage spin text format
 */

namespace app\components;

use app\components\SpinFormat;
use app\components\Rewriter;
use app\components\WordHelper;
use Yii;

class PhraseRewriter extends Rewriter
{
	/**
	 * Parsing text and replace the text that contain phrase like
	 * "para tetangga" into "tetangga-tetangga"
	 * @param type $sentence
	 */
	private static function parseReduplicationPhrase($sentence)
	{
		$regexPattern = '%para ([\w]*)%i';
		
		$replacements = [];
		$counter = 0;
		if (preg_match_all($regexPattern, $sentence, $matches,  PREG_SET_ORDER)){
			foreach($matches as $match){
				$realSentence = $match[0];

				$alternateSentences[] = $realSentence;
				
				$alternateSentences[] = $match[1].'-'.$match[1];
				$spinSentence = SpinFormat::generate($alternateSentences);
				
				$counterLabel = ':st'.$counter;
				$replacements[$counterLabel] = $spinSentence;
				$sentence = str_replace($realSentence, $counterLabel, $sentence);
				
				$counter++;
			}
		}
		
		
		//only at the beginning of text, the "banyak" make sense
		$regexPattern = '%(?:^|\.)([\w]*)-([\w]*)%i';
		if (preg_match_all($regexPattern, $sentence, $matches,  PREG_SET_ORDER)){
			foreach($matches as $match){
				$realSentence = $match[0];

				$alternateSentences[] = $realSentence;
				
				if(strtolower($match[1])!=strtolower($match[2])){
					continue;
				}
				
				$listException = ['laba','kupu','apa','gorong','gado','cumi','onde',
					'foya','jari','kadang','kisi','lobi','ubur','agar','rica',
					'alang','rempah','beri','biri','laki','gara','betul','benar','abal',
					'abu','aba','alih','alun','amit','ancang','ari','baling','bapak','bayang',
					'buli','buru','cita','cuma','duduk','embel','guna','hati','hura','ibu','iming','imut',
					'jalan','jampi','jaring','jentik','kira','langit','liku','layang','leha','liuk',
					'manik','masing','mata','megap','mencak','mentang','moga','oleh','olok',
					'omong','ondel','ongkang','paling','panji','para','paru','poco','pori',
					'pura','rata','remah','rintik','rumbai','rupa','sabu','sama','sayup','samar',
					'sepoi','sia','siku','suam','tahu','tiba','ubun','umbul','undang','unek',
					'wanti','was','malu','lumba','alap','berang','kunang','kura','labi','layang'
					];
				if(in_array($match[1], $listException)){
					continue;
				}
				
				$alternateSentences[] = 'Banyak '. lcfirst($match[1]);
				$spinSentence = SpinFormat::generate($alternateSentences);
				
				$counterLabel = ':st'.$counter;
				$replacements[$counterLabel] = $spinSentence;
				
				$sentence = str_replace($realSentence, $counterLabel, $sentence);
				
				$counter++;
			}
		}
		
		$sentence = self::replaceText($sentence, $replacements);
		
		return $sentence;
	}
	
	
	
	/**
	 * Parsing text and replace registered word into spin text format
	 * @param string $sentence
	 * @return string sentence
	 */
	private static function parsePhrase($sentence)
	{
		//It is faster to check all the phrase in database
		//then check the words one by one in the text.
		$phrases = \app\models\Word::find()->allPhrase()->all();
		
		foreach($phrases as $phrase){
			if(strpos($sentence, $phrase->name)===false){
				continue;
			}
			$replace = WordHelper::getSpinWord($phrase->name);
			
			$sentence = str_replace($phrase->name,$replace, $sentence);
			
			//store to cache, so it won't processed further
			$sentence = Yii::$app->wordCache->store($replace, $sentence);
		}
		
		return $sentence;
	}
	
	public static function rewrite($sentence)
	{
		//processing a lot (banyak, para) and "buku-buku"
		$sentence = self::parseReduplicationPhrase($sentence);
		//processing phrases
		$sentence = self::parsePhrase($sentence);
		
		return $sentence;
	}
}
