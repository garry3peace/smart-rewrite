<?php

namespace app\components;

class Number
{
	
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
			"sebelas"];
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
		$base = self::base();
		
		if(str_word_count($x)==1){
			$number = array_search($x, $base);
			return $number;
		}else{
			$words = explode(' ',$x);
			
			if($words[1]=='belas'){
				return '1'.self::toNumeric($words[0]);
			}else if(in_array($words[1],['puluh','ratus','ribu'])){
				$num = [];
				
				//filling the zeroes as the result
				switch($words[1]){
					case 'puluh': $num[1]=0;break;
					case 'ratus': $num[2]=$num[1]=0;break;
					case 'ribu': $num[3]=$num[2]=$num[1]=0;break;
				}
				
				//filling the number according to the text
				$count=0;
				foreach($words as $key=>$word){
					if($key%2==1){
						continue;
					}
					$num[$count] = self::toNumeric($word);
					$count++;
				}
				//because the filling to array cause the order of element change
				ksort($num);
				return implode('', $num);
			}
				
		}
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
			return " seratus" . self::toPhrase($x - 100);
		elseif ($x < 1000)
			return self::toPhrase($x / 100) . " ratus " . self::toPhrase($x % 100);
		elseif ($x < 2000)
			return " seribu" . self::toPhrase($x - 1000);
		elseif ($x < 1000000)
			return self::toPhrase($x / 1000) . " ribu " . self::toPhrase($x % 1000);
		elseif ($x < 1000000000)
			return self::toPhrase($x / 1000000) . " juta " . self::toPhrase($x % 1000000);
	}

}