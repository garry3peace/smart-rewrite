<?php

namespace app\components\sentence;

class Passive {
	private static function isVerbStartWith($base,$character)
	{
		$verbA = [
			'acak',
			'aduk',
			'ajar',
			'akhir',
			'antar',
			'ambil',
			'ampun',
			'anulir',
			'angkat',
			'ancam',
			'arak',
			'atur'
		];
		
		$verbK = [
			'kabar','kerja','kunci',
		];
		
		$verbM = [
			'maaf','mabuk','macam','macet','madu','makan','makam','makar',
			'maklum','makmur','makna','maksimal','maksud','makzul','malam',
			'malas','mampir','mampu','mandat','mandi','mandul','manfaat','mangsa',
			'manis','manja','manjur','mantap','manuver','marah','mekar','mati','malu','maling',
			'menang','mengerti','mentah','milik','minat','minggat','minta','minum','minus','mirip',
			'miskin','modal','model','modern','modernisasi','modifikasi','mogok','molor',
			'moralisasi','motivasi','muara','mula','mulai','mulia','muncul','muntah','mutilasi','mutasi',
			'mutlak'
		];
		
		$verbNy= ['nyata','nyanyi'];
		
		$verbN = [
			'nafkah','nafas','naik', 'nama', 'nampak', 'nahkoda',
			'nanti', 'napas', 'narasi', 'nasehat',
			'natural', 'navigasi', 'nazar', 'negosiasi',
			'nekat', 'niaga', 'niat', 'nihil',
			'nikah', 'nikmat', 'nilai', 'nisbi', 'nista',
			'noda','nominasi','nomor','nonaktif','normal','nubuat'
		];
		
		$verb = [
			'a'=>$verbA,
			'k'=>$verbK,
			'm'=>$verbM,
			'n'=>$verbN,
			'ny'=>$verbNy,
		];
		
		
		
		if(in_array($base,$verb[$character])){
			return true;
		}
			
		return false;
	}
	

	/**
	 * Remove the suffix (ending word)
	 * @param string $word
	 * @param string $suffix
	 */
	private static function removeSuffix($word, $suffix)
	{
		$prefixLength= strlen($suffix);
		if(substr($word,-$prefixLength)==$suffix){
			return substr($word,0, -$prefixLength);
		}
		return $word;
	}
	
	/**
	 * Remove the beginning (first word)
	 * @param string $word
	 * @param string $prefix
	 */
	private static function removePrefix($word, $prefix)
	{
		$prefixLength= strlen($prefix);
		if(substr($word,0,$prefixLength)==$prefix){
			return substr($word,$prefixLength);
		}
		return $word;
	}
	

	/**
	 * Function to remove any fix given
	 *
	*/
	private static function removeAffix($word, $prefix='', $suffix='')
	{
		//replace prefix
		$word = self::removePrefix($word, $prefix);

		//replace suffix
		$word = self::removeSuffix($word, $suffix);
		
		return $word;
	}	
	
	private function exclusionMekan()
	{
		return [
			'menyakitkan'
		];
	}

	/**
	 * Passive me-kan form
	 * @param type $affix
	 */
	public static function toPassiveMekan($affix)
	{
		//Certain mekan musn't passified
		if(in_array($affix, $this->exclusionMekan())){
			return $affix;
		}
		
		//finding "meny-kan", eg: menyertakan, menyatakan
		if(strpos($affix,'meny')===0){
			$base = self::removeAffix($affix,'me','kan');
			if(self::isVerbStartWith($affix,'ny')){
				$passive = 'di'.$affix.'kan';
			}else{
				$base = self::removeAffix($affix,'meny','kan');
				$passive = 'dis'.$base.'kan';
			}
		//find "meng-kan, eg: mengabarkan, menganulirkan, mengaturkan
		}else if(strpos($affix,'meng')===0){
			$base = 'k'.self::removeAffix($affix,'meng','kan');
			if(self::isVerbStartWith($base,'k')){
				$passive = 'di'.$base.'kan';
			}else{
				$base = self::removeAffix($affix,'meng','kan');
				$passive = 'di'.$base.'kan';
			}
		//find "men-kan", eg: menudingkan, menuliskan
		}else if (strpos($affix,'men')===0){
			$base = self::removeAffix($affix,'me','kan');
			if(self::isVerbStartWith($base,'n')){
				$passive = 'di'.$base.'kan';
			}else if(in_array($affix[3], ['a','i','u','e','o'])){
				$base = self::removeAffix($affix,'men','kan');
				$passive = 'dit'.$base.'kan';
			}else{
				$base = self::removeAffix($affix,'men','kan');
				$passive = 'di'.$base.'kan';
			}
			
		//find mem-kan
		}else if (strpos($affix,'mem')===0){
			if(in_array($affix[3], ['a','i','u','e','o'])){
				$base = self::removeAffix($affix,'me','kan');
				if(self::isVerbStartWith($base,'m')){
					$passive = 'di'.$base.'kan';
				}else{
					$base = self::removeAffix($affix,'mem','kan');
					$passive = 'dip'.$base.'kan';
				}
			}else{
				$base = self::removeAffix($affix,'mem','kan');
				$passive = 'di'.$base.'kan';
			}
		}else{
			$base = self::removeAffix($affix,'me','kan');
			$passive = 'di'.$base.'kan';
		}
		
		return $passive;
	}
}
