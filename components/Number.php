<?php

namespace app\components;

use yii\base\Component;

class Number extends Component
{
	const AFTER_EXECUTE = 'after_execute';
	
	private static function power()
	{
		return [
			10=>'puluh',
			100=>'ratus',
			1000=>'ribu'
		];
	}
	
	private static function base()
	{
		return $basicNum = [
			"nol", 
			"satu", 
			"dua", 
			"tiga", 
			"empat", 
			"lima", 
			"enam", 
			"tujuh", 
			"delapan", 
			"sembilan", 
			"sepuluh",
			"sebelas",
			100=>"seratus",
			1000=>"seribu"];
	}
	
	/**
	 * Convert the text into number
	 * This function is very simple
	 * since its unlikely there is people write number in text format
	 * that more than two words
	 * @param string $x
	 */
	public static function toNumeric($x)
	{
		$x = strtolower(trim($x));
		
		//to simplified process, we will change seratus to satu ratus, seribu to satu ribu
		$x = str_replace('seratus', 'satu ratus', $x);
		$x = str_replace('seribu', 'satu ribu', $x);
		
		$base = self::base();
		
		//split all the words into tokens
		$words = explode(' ', $x);
		
		//If it is single word
		if(str_word_count($x)==1){
			$number = array_search($x, $base);
			return $number;
		}
		
		
		//if it is two word
		if(str_word_count($x)==2){
			$number = array_search($words[0], $base);
			
			if($words[1]=='belas'){
				return $number + 10;
			}else{
				$power = self::power();
				$multiplier = array_search($words[1], $power);
				return $number * $multiplier;
			}
		}
		
		//slicing the words
		if($words[0] != 'belas' && $words[0] != 'seratus' && $words[0]!= 'seribu'){
			$processWord = $words[0] .' '. $words[1];
		}else{
			$processWord = $words[0];
		}
		$remainingWord = trim(str_replace($processWord, '',$x));
		
		return self::toNumeric($processWord) + self::toNumeric($remainingWord);
	}
	
	/**
	 * Convert given number into phrase
	 * @param int $x
	 * @return string
	 */
	public static function toPhrase($x)
	{
		$basicNum = self::base();

		if ($x < 12){
			$number = $basicNum[$x];
			if($x==0) $number = '';
			return $number;
		}elseif ($x < 20)
			return self::toPhrase($x - 10) . " belas";
		elseif ($x < 100){
			return self::toPhrase($x / 10) . " puluh " . self::toPhrase($x % 10);
		}elseif ($x < 200)
			return "seratus " . self::toPhrase($x - 100);
		elseif ($x < 1000)
			return self::toPhrase($x / 100) . " ratus " . self::toPhrase($x % 100);
		elseif ($x < 2000)
			return "seribu " . self::toPhrase($x - 1000);
		elseif ($x < 1000000)
			return self::toPhrase($x / 1000) . " ribu " . self::toPhrase($x % 1000);
		elseif ($x < 1000000000)
			return self::toPhrase($x / 1000000) . " juta " . self::toPhrase($x % 1000000);
	}
	
	private static function toCommaPhrase($x){
		$number = self::base();
		$length = strlen($x);

		$result = ' koma ';
		for ($pos = 0; $pos < $length; $pos++) {
			$char = $x[$pos];
			$result.= ' ' . $number[$char];
		}
		return $result;
	}

	public static function toNumberPhrase($x) {
		$hasil = '';
		
		//Cek minus atau bukan
		if ($x < 0) {
			$hasil = "minus ";
		} 
		
		$decimal=explode('.', $x);
		
		//Main number
		$hasil .= trim(self::toPhrase($decimal[0]));
		
		//Decimal number
		if(isset($decimal[1]) && $decimal[1]!=''){
			$hasil .= self::toCommaPhrase($decimal[1]);
		}
		
		
		return $hasil;
	}
	
	public function execute()
	{
		echo 'jalani Number execute<br/>';
		$this->trigger(self::AFTER_EXECUTE);
	}

}