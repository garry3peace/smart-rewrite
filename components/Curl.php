<?php

namespace app\components;

/**
 * Special curl for handling cURL more.
 */
class Curl{
	const MAX_REDIRECT = 5;
	
	public $maxRedirect; 
	private $code;
	
	private $error;
	
	public function __construct()
	{
		$this->maxRedirect = self::MAX_REDIRECT;
	}
	
	public function exec($ch)
	{
		$maxRedirect = $this->maxRedirect;

		if (ini_get('open_basedir') == '' && ini_get('safe_mode')== false){
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_MAXREDIRS, $maxRedirect);
			$data = curl_exec($ch);
		}else{
			$data = $this->follow($ch, $maxRedirect);
		}

		$this->error = curl_error($ch);
		$this->code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		
		curl_close($ch);
		
		return $data;
	}
	
	/**
	 * Special function to follow location
	 * We must use this when the hosting doesn't support CURLOPT_FOLLOWLOCATION
	 * because of safe_mode or open_basedir is opened
	 * @param type $ch
	 * @param int $maxRedirect
	 * @return boolean
	 */
	private function follow($ch, $maxRedirect = 1) {
		$mr = $maxRedirect === null ? 5 : intval($maxRedirect);
		
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
		
		if ($mr > 0) {
			$newurl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);

			$rch = curl_copy_handle($ch);
			curl_setopt($rch, CURLOPT_HEADER, true);
			curl_setopt($rch, CURLOPT_NOBODY, true);
			curl_setopt($rch, CURLOPT_FORBID_REUSE, false);
			curl_setopt($rch, CURLOPT_RETURNTRANSFER, true);
			do {
				curl_setopt($rch, CURLOPT_URL, $newurl);
				$header = curl_exec($rch);
				if (curl_errno($rch)) {
					$code = 0;
				} else {
					$code = curl_getinfo($rch, CURLINFO_HTTP_CODE);
					if ($code == 301 || $code == 302) {
						preg_match('/Location:(.*?)\n/', $header, $matches);
						$newurl = trim(array_pop($matches));
					} else {
						$code = 0;
					}
				}
			} while ($code && --$mr);
			curl_close($rch);
			
			if (!$mr) {
				if ($maxRedirect === null) {
					trigger_error('Too many redirects. When following redirects, libcurl hit the maximum amount.', E_USER_WARNING);
				} else {
					$maxRedirect = 0;
				}
				return false;
			}
			curl_setopt($ch, CURLOPT_URL, $newurl);
		}
		
		return curl_exec($ch);
	}
	
	/**
	 * Get HTTP code from the result
	 */
	public function getCode()
	{
		return $this->code;
	}
	
	public function getError()
	{
		return $this->error;
	}
}