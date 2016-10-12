<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\components\word;

use app\components\word\Vocabulary;

/**
 * Description of Pronoun
 *
 * @author garry
 */
class Pronoun {
	
	const FIRST_PERSON = 1;
	const SECOND_PERSON = 2;
	const THIRD_PERSON = 3;
	const FIRST_PERSON_PLURAL = 4;
	const SECOND_PERSON_PLURAL = 5;
	const THIRD_PERSON_PLURAL = 6;

	/**
	 * The list of pronouns
	 * @return type
	 */
	public static function getList()
	{
		return [
			self::FIRST_PERSON => ['saya','aku','gw','gua','gue','ane','diriku'],
			self::SECOND_PERSON => ['kamu','anda','dikau','dirimu','engkau','kau'],
			self::THIRD_PERSON => ['dia','ia'],
			self::FIRST_PERSON_PLURAL=>['mereka','mereka semua'],
			self::SECOND_PERSON_PLURAL=>['kalian','kalian semua'],
			self::THIRD_PERSON_PLURAL=>['kami','kita','kami semua','kita semua']
		];
	}
	
	public static function isPronoun($text)
	{
		$pronouns = Vocabulary::pronoun();
		return in_array(strtolower($text), $pronouns);
	}
	
	
	
	public static function getPerspective($pronoun){
		$list = self::getList();
		
		foreach($list as $pointOfView => $pronouns){
			if(in_array(strtolower($pronoun), $pronouns)){
				return $pointOfView;
			}
		}
		return false;
	}
}
