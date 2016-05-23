<?php
namespace app\components\scrapper;
use app\components\scrapper\SimpleHtml;

class Scrapper
{
	//URL need to scrapped
	protected $url;
	//Domain of the URL, eg: mashable.com
	protected $hostname;
	//domain name without dot, eg: mashable in mashable.com
	protected $domainName; 
	//the content of the URL
	protected $html;
	
	public function __construct($url)
	{
		$this->url = $url;
		
		$domain = new \app\components\web\Domain($url);
		$this->hostname = $domain->getHostname();
		$this->domainName = $domain->getName();
		
		$content = $this->curlGet($url);
//		print_r($content);
		$html = SimpleHtml::str_get_html($content);
		
		$this->html = $html; 
//		var_dump($html);die('#');
	}
	
	protected function curlGet($url)
	{
		$maxRedirect = 5; //maximum time to follow redirection
		$curl = curl_init();
		
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HEADER, false);
		$agent= 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';
		curl_setopt($curl, CURLOPT_USERAGENT, $agent);
		curl_setopt($curl,CURLOPT_ENCODING , 'gzip');
		curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
		curl_setopt($curl, CURLOPT_TIMEOUT,30);
		curl_setopt($curl, CURLOPT_POST, false);
		
		if (ini_get('open_basedir') == '' && ini_get('safe_mode' == 'Off')){
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($curl, CURLOPT_MAXREDIRS, $maxRedirect);
			$data = curl_exec($curl);
		}else{
			$data = $this->curlFollow($curl, $maxRedirect);
		}

		curl_close($curl);
		
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
	protected function curlFollow($ch, $maxRedirect = 1) {
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

	protected function find($rule, $label='plaintext',$position=0)
	{
		$html = $this->html;
		$obj = $html->find($rule,$position);
		if ($obj){
			$text = $obj->$label;
			return $text;
		}	
	}
	
	protected function findAll($rule, $label='plaintext')
	{
		$result = '';
		$html = $this->html;
		
		foreach ($html->find($rule) as $element){
			$text = $element->$label;
			$result .= $text;
		}
		return $result;
	}
	
	private function toTag($raw)
	{
		$raw = trim($raw);
		$tag = nl2br($raw);
		return '<p>'.$tag.'</p>';
	}
}