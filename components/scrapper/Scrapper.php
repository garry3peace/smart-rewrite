<?php
namespace app\components\scrapper;
use app\components\scrapper\SimpleHtml;
use app\components\Curl;

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
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, false);
		$agent= 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';
		curl_setopt($ch, CURLOPT_USERAGENT, $agent);
		curl_setopt($ch,CURLOPT_ENCODING , 'gzip');
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
		curl_setopt($ch, CURLOPT_TIMEOUT,30);
		curl_setopt($ch, CURLOPT_POST, false);
		
		$curl = new Curl;
		$data = $curl->exec($ch);

		return $data;
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
	
	public function getHtml()
	{
		return $this->html;
	}
}