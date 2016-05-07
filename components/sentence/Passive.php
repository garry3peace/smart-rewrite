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
			'mengerti','mentah','milik','minat','minggat','minta','minum','minus','mirip',
			'miskin','modal','model','modern','modernisasi','modifikasi','mogok','molor',
			'moralisasi','motivasi','muara','mula','mulai','mulia','muntah','mutilasi','mutasi',
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
	 * Passive me-kan form
	 * @param type $affix
	 */
	public static function toPassiveMekan($affix)
	{
		//finding "meny-kan", eg: menyertakan, menyatakan
		if(strpos($affix,'meny')===0){
			$base = str_replace('kan','',str_replace('me','',$affix));
			if(self::isVerbStartWith($affix,'ny')){
				$passive = 'di'.$affix.'kan';
			}else{
				$base = str_replace('kan','',str_replace('meny','',$affix));
				$passive = 'dis'.$base.'kan';
			}
		//find "meng-kan, eg: mengabarkan, menganulirkan, mengaturkan
		}else if(strpos($affix,'meng')===0){
			$base = str_replace('kan','',str_replace('meng','k',$affix));
			if(self::isVerbStartWith($base,'k')){
				$passive = 'di'.$base.'kan';
			}else{
				$base = str_replace('kan','',str_replace('meng','',$affix));
				$passive = 'di'.$base.'kan';
			}
		//find "men-kan", eg: menudingkan, menuliskan
		}else if (strpos($affix,'men')===0){
			$base = str_replace('kan','',str_replace('me','',$affix));
			if(self::isVerbStartWith($base,'n')){
				$passive = 'di'.$base.'kan';
			}else if(in_array($affix[3], ['a','i','u','e','o'])){
				$base = str_replace('kan','',str_replace('men','',$affix));
				$passive = 'dit'.$base.'kan';
			}else{
				$base = str_replace('kan','',str_replace('men','',$affix));
				$passive = 'di'.$base.'kan';
			}
			
		//find mem-kan
		}else if (strpos($affix,'mem')===0){
			if(in_array($affix[3], ['a','i','u','e','o'])){
				$base = str_replace('kan','',str_replace('mem','',$affix));
				$passive = 'dip'.$base.'kan';
			}else{
				$base = str_replace('kan','',str_replace('mem','',$affix));
				$passive = 'di'.$base.'kan';
			}
		}else{
			$base = $base = str_replace('kan','',str_replace('me','',$affix));
			$passive = 'di'.$base.'kan';
		}
		
		return $passive;
	}
}