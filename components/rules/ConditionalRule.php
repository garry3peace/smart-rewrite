<?php

namespace app\components;

/**
 * Class Rule is for defining rewrite rule. 
 */
class ConditionalRule extends Rule{
	
	const SENTENCE_OPENING = '%\b';//symbols for opening sentence
	const SENTENCE_CLOSING = '\b%i'; //symbols for closing sentence
	
	public function rewrite()
	{
		
	}
	
	private function ifWords()
	{
		return "(seandainya|andaikan|jika|kalau|jikalau|asal|asalkan|manakala)";
	}
	
	public function rule()
	{
		$ifWord = $this->ifWords();
		
		return [
			[
				'rule'=>$this->SENTENCE_OPENING.'([\w\s`-]*) '.$ifWord .' ([\w\s`-]*)maka([\w\s`-]*)'.$this->SENTENCE_CLOSING,
				'process'=>':match1|ucfirst,:match4 :match2 :match3|trim|lcfirst',
			],
			[
				'rule'=>$this->SENTENCE_OPENING.'([\w\s`-]*) '.$ifWord .' ([\w\s`-]*)'.$this->SENTENCE_CLOSING,
				'process'=>':match2|trim|ucfirst :match3, :match1|lcfirst|trim',
			],
			[
				'rule'=>$this->SENTENCE_OPENING.$ifWord.' ([\w\s`-]*), ([\w\s`-]*)'.$this->SENTENCE_CLOSING,
				'process'=>':match3|trim|ucfirst :match1|lcfirst :match2',
			]
		];
	}
}