<?php

namespace app\components\rules;

use app\components\Rule;
use app\components\RegexElement;
use app\components\word\Pronoun;

/**
 * Rule to translate direct sentence to indirect sentence
 * Example:
 * Saya berkata, "Mereka sudah pergi."
 * Saya berkata bahwa mereka sudah pergi
 * 
 * Dia berkata, "Saya hari ini sudah kuliah."
 * Dia berkata bahwa dia hari ini sudah kuliah
 * 
 */
class DirectRule extends Rule
{
	
	private static function tokenizeWord($words)
	{
		$result = explode(' ', $words);
		return $result;
	}
	
	/**
	 * Function to find out who is the speaker. Whether is it first person, second or third
	 * return in integer of the value of person perspective
	 * @param string $text
	 * @return int
	 */
	private static function findSpeaker($text)
	{
		$tokens = self::tokenizeWord($text);
		foreach($tokens as $token){
			if (Pronoun::isPronoun($token)){
				return Pronoun::getPerspective($token);
			}
		};
		//by default if speaker not found, consider third person
		return Pronoun::THIRD_PERSON;
	}
	
	
	/**
	 * Show the mapping of the person speaker, and to what form after changing 
	 * to indirect speech
	 * @return array
	 */
	private static function mapping()
	{
		return 
		[
			Pronoun::FIRST_PERSON => 
			[
				Pronoun::FIRST_PERSON => Pronoun::FIRST_PERSON ,
				Pronoun::SECOND_PERSON => Pronoun::THIRD_PERSON,
				Pronoun::THIRD_PERSON=> Pronoun::THIRD_PERSON,
				Pronoun::FIRST_PERSON_PLURAL => Pronoun::FIRST_PERSON_PLURAL,
				Pronoun::SECOND_PERSON_PLURAL => Pronoun::THIRD_PERSON_PLURAL,
				Pronoun::THIRD_PERSON_PLURAL => Pronoun::THIRD_PERSON_PLURAL,
			],
			Pronoun::SECOND_PERSON => 
			[
				Pronoun::FIRST_PERSON => Pronoun::SECOND_PERSON ,
				Pronoun::SECOND_PERSON => Pronoun::THIRD_PERSON,
				Pronoun::THIRD_PERSON=> Pronoun::THIRD_PERSON,
				Pronoun::FIRST_PERSON_PLURAL => Pronoun::SECOND_PERSON_PLURAL,
				Pronoun::SECOND_PERSON_PLURAL => Pronoun::THIRD_PERSON_PLURAL,
				Pronoun::THIRD_PERSON_PLURAL => Pronoun::THIRD_PERSON_PLURAL,
			],
			Pronoun::THIRD_PERSON => 
			[
				Pronoun::FIRST_PERSON => Pronoun::THIRD_PERSON ,
				Pronoun::SECOND_PERSON => Pronoun::THIRD_PERSON,
				Pronoun::THIRD_PERSON=> Pronoun::THIRD_PERSON,
				Pronoun::FIRST_PERSON_PLURAL => Pronoun::THIRD_PERSON_PLURAL,
				Pronoun::SECOND_PERSON_PLURAL => Pronoun::THIRD_PERSON_PLURAL,
				Pronoun::THIRD_PERSON_PLURAL => Pronoun::THIRD_PERSON_PLURAL,
			],
			Pronoun::FIRST_PERSON_PLURAL => 
			[
				Pronoun::FIRST_PERSON => Pronoun::FIRST_PERSON_PLURAL ,
				Pronoun::SECOND_PERSON => Pronoun::THIRD_PERSON,
				Pronoun::THIRD_PERSON=> Pronoun::THIRD_PERSON,
				Pronoun::FIRST_PERSON_PLURAL => Pronoun::FIRST_PERSON_PLURAL,
				Pronoun::SECOND_PERSON_PLURAL => Pronoun::THIRD_PERSON_PLURAL,
				Pronoun::THIRD_PERSON_PLURAL => Pronoun::THIRD_PERSON_PLURAL,
			],
			Pronoun::SECOND_PERSON_PLURAL => 
			[
				Pronoun::FIRST_PERSON => Pronoun::SECOND_PERSON_PLURAL ,
				Pronoun::SECOND_PERSON => Pronoun::THIRD_PERSON,
				Pronoun::THIRD_PERSON=> Pronoun::THIRD_PERSON,
				Pronoun::FIRST_PERSON_PLURAL => Pronoun::SECOND_PERSON_PLURAL,
				Pronoun::SECOND_PERSON_PLURAL => Pronoun::THIRD_PERSON_PLURAL,
				Pronoun::THIRD_PERSON_PLURAL => Pronoun::THIRD_PERSON_PLURAL,
			],
			Pronoun::THIRD_PERSON_PLURAL => 
			[
				Pronoun::FIRST_PERSON => Pronoun::THIRD_PERSON_PLURAL ,
				Pronoun::SECOND_PERSON => Pronoun::THIRD_PERSON,
				Pronoun::THIRD_PERSON=> Pronoun::THIRD_PERSON,
				Pronoun::FIRST_PERSON_PLURAL => Pronoun::THIRD_PERSON_PLURAL,
				Pronoun::SECOND_PERSON_PLURAL => Pronoun::THIRD_PERSON_PLURAL,
				Pronoun::THIRD_PERSON_PLURAL => Pronoun::THIRD_PERSON_PLURAL,
			],
		];
	}
	
	private static function replacePointOfView($speaker, $text)
	{
		$list = self::mapping();
		$selectedList = $list[$speaker];
		
		$tokens = self::tokenizeWord($text);
		$result = [];
		foreach($tokens as $token){
			if (Pronoun::isPronoun($token)){
				//get the source perspective
				$sourcePerspective = Pronoun::getPerspective($token);
				//find the mapping and save it to target perspective
				$targetPerspective = $selectedList[$sourcePerspective];
				$word = Pronoun::getPronounList()[$targetPerspective];
			}else{
				$word = $token;
			}
			$result[] = $word;
		}
		
		return implode(' ', $result);
	}
	
	private static function processSpeech($speaker, $text)
	{
		return self::replacePointOfView($speaker, $text);
	}
	
	
	public static function rule()
	{
		$phrase = RegexElement::phrase();
		$sentence = RegexElement::sentence();
		$opening = RegexElement::opening();
		$closing = RegexElement::closing();
		return $opening."($phrase+),\s*\"($sentence+).?\"".$closing;
	}
	
	public static function rewrite($match){
		
		//Sentence part
		$parts = [];
		
		$parts[0] = $match[1];
		$parts[1] = 'bahwa';
		$directSpeech = $match[2];
		
		//Find speaker from the first part of sentence
		$speaker = self::findSpeaker($parts[0]);
		//convert the direct speech to indirect speech based on speaker
		$indirectSpeech = self::processSpeech($speaker, $directSpeech);
		$parts[2]= $indirectSpeech;
		
		$sentence = ucfirst(implode('', $parts));
		
		return $sentence; 
	}
}