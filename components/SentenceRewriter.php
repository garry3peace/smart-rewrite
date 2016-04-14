<?php
/* 
 * SpinFormat to manage spin text format
 */

namespace app\components;

use app\components\SpinFormat;
use app\components\Rewriter;

class SentenceRewriter extends Rewriter
{
	const SENTENCE_OPENING = '%(?:^|\.)';//symbols for opening sentence
	const SENTENCE_CLOSING = '(?:$|\.)%i'; //symbols for closing sentence
	
	/**
	 * Parsing the $process string by replacing the variable
	 * with the given $match array
	 * @param string $process
	 * @param array $param
	 * @return string result text
	 */
	private static function processingPregMatch($process, $param)
	{
		$regexPattern = '%:[\w\d|]+%i';
		preg_match_all($regexPattern, $process, $matches);
		
		foreach($matches[0] as $match){
			$pieces = explode('|',$match);
			
			$variable = str_replace(':match','', $pieces[0]);
			unset($pieces[0]);
			$value = trim($param[$variable]);
			
			//if there is no extra param for variable, then continue
			if(count($pieces)>0){
				foreach($pieces as $function){
					$value = call_user_func($function,$value);
				}
			}
			
			$process = str_replace($match, $value, $process);
		}
		
		return $process;
	}

	private static function rules()
	{
		
		$ifWord = "(seandainya|andaikan|jika|kalau|jikalau|asal|asalkan|manakala)";
		
		$timeWord = '(sejak|semenjak|sedari|sewaktu|tatkala|ketika|sementara|seraya|
			selagi|selama|sambil|demi|setelah|sesudah|sebelum|sehabis|selesai|seusai|
			hingga|sampai)';
		
		return [
			'1'=>[
				'rule'=>self::SENTENCE_OPENING.'([\w\s`-]*)'.$ifWord .'([\w\s`-]*)maka([\w\s`-]*)'.self::SENTENCE_CLOSING,
				'process'=>':match1|ucfirst,:match4 :match2 :match3|trim|lcfirst.',
			],
			'2'=>[
				'rule'=>self::SENTENCE_OPENING.'([\w\s`-]*)'.$ifWord .'([\w\s`-]*)'.self::SENTENCE_CLOSING,
				'process'=>':match2|trim|ucfirst :match3, :match1|lcfirst|trim.',
			],
			'3'=>[
				'rule'=>self::SENTENCE_OPENING.$ifWord.'([\w\s`-]*), ([\w\s`-]*)'.self::SENTENCE_CLOSING,
				'process'=>':match3|ucfirst|trim :match1|lcfirst :match2.',
			],
			'4'=>[
				'rule'=>self::SENTENCE_OPENING.'([\w\s-`,]+) (dikarenakan|karena|sebab|supaya|agar) ([\w\s`-]*)'.self::SENTENCE_CLOSING,
				'process'=>':match2 :match3|ucfirst|trim :match1|lcfirst.',
			],
			'5'=>[
				'rule'=>self::SENTENCE_OPENING.'([\w\s-`]+) '.$timeWord.' ([\w\s-`]*)'.self::SENTENCE_CLOSING,
				'process'=>':match2 :match3|trim, :match1|trim|lcfirst.',
			],
		];
	}
	
	private static function process($sentence)
	{
		$counter = 0;
		$replacements = [];
		
		$listRules = self::rules();
		
		foreach($listRules as $rule){
			$regexPattern = $rule['rule'];
			if (preg_match_all($regexPattern, $sentence, $matches,  PREG_SET_ORDER)){
				foreach($matches as $match){
					$realSentence = $match[0];

					$alternateSentences[] = $realSentence;
					

					$alternateSentences[] = self::processingPregMatch($rule['process'], $match); 
					$spinSentence = SpinFormat::generate($alternateSentences);

					$counterLabel = ':st'.$counter;
					$replacements[$counterLabel] = $spinSentence;
					$sentence = str_replace($realSentence, $counterLabel, $sentence);

					$counter++;
				}
			}
		}
		$sentence = self::replaceText($sentence, $replacements);
		return $sentence;
	}
	
	private static function parseBecauseSentence($sentence)
	{
		$regexPattern = self::SENTENCE_OPENING.'([\w\s-`,]+) (dikarenakan|karena|sebab|supaya|agar) ([\w\s`-]*)'.self::SENTENCE_CLOSING;
		if(preg_match_all($regexPattern, $sentence, $matches,  PREG_SET_ORDER)){
			foreach($matches as $match){				
				$realSentence = $match[0];
				
				if(strpos(strtolower($realSentence), 'oleh sebab itu')||
					strpos(strtolower($realSentence), 'oleh karena itu')){
					continue;
				}

				$alternateSentences[] = $realSentence;
				
				
				$alternateSentences[] = ucfirst($match[2]).' '.trim(lcfirst($match[3])).', '.trim(lcfirst($match[1])).'.';
				$spinSentence = SpinFormat::generate($alternateSentences);
				$sentence = str_replace($realSentence, $spinSentence, $sentence);
			}
		}

		return $sentence;
	}
	
	private static function parseWhenSentence($sentence)
	{
		$timeWord = '(sejak|semenjak|sedari|sewaktu|tatkala|ketika|sementara|begitu|seraya|
			selagi|selama|serta|sambil|demi|setelah|sesudah|sebelum|sehabis|selesai|seusai|
			hingga|sampai)';
		$regexPattern = self::SENTENCE_OPENING.'([\w\s-`]+) '.$timeWord.' ([\w\s-`]*)'.self::SENTENCE_CLOSING;
		if(preg_match_all($regexPattern, $sentence, $matches,  PREG_SET_ORDER)){
			foreach($matches as $match){
				$realSentence = $match[0];

				$alternateSentences[] = $realSentence;
				
				$alternateSentences[] = 'Ketika '.trim($match[2]).', '.trim(lcfirst($match[1])).'.';
				$spinSentence = SpinFormat::generate($alternateSentences);
				$sentence = str_replace($realSentence, $spinSentence, $sentence);
			}
		}

		return $sentence;
	}
	
	public static function rewrite($sentence)
	{
		$sentence = self::process($sentence);
		
		return $sentence;
	}
}
