<?php
namespace app\components\scrapper;
use app\components\scrapper\SimpleHtml;

class Scrapper
{

	/**
	 * execute how to get content based on domain
	 * @param string $domain
	 * @param SimpleHtml $html
	 * @return type
	 */
	private static function retrieve($domain, $html)
	{
		switch($domain){
			case 'sephora.com':
				$longtext = self::findAll($html,'.long-description, #pdp-tab__use');
				$shorttext = self::findAll($html,'.short-description');
				break;
			case 'anastasiabeverlyhills.com':
				case 'sephora.com':
				$longtext = self::findAll($html,'.product-tabs-content-inner .std');
				$shorttext = $longtext;
				break;
			case 'amazon.com':
				$longtext = self::findAll($html,'.showHiddenFeatureBullets .a-list-item');
				$shorttext = self::findAll($html,'.showHiddenFeatureBullets .a-list-item');
				break;
			case 'ardelllashes.com':
				$longtext = self::findAll($html,'#ui-accordion-description-panel-0');
				$shorttext = $longtext;
				break;
			case 'bobbibrowncosmetics.com':
				$longtext = self::findAll($html,'.how-to-use__content');
				$shorttext = self::find($html,'.how-to-use__content');
				break;
			case 'manentail.com':
				$longtext = self::find($html,'.intro p').self::find($html,'.first-section','innertext');
				$shorttext = self::find($html,'.intro p');
				break;
			case 'mecca.com':
				$longtext = self::findAll($html,'.hidden-phone .tab-pane');
				$shorttext = self::find($html,'.description');
				break;
			case 'beautyblender.com':
				$longtext = self::findAll($html,'.tab-container .std','innertext').
					self::findAll($html,'.tab-container .product_shortdescription','innertext');
				$shorttext = self::find($html,'.short-desc h3');
				break;
			case 'bhcosmetics.com':
				$longtext= self::findAll($html,'.ProductText','innertext');
				$shorttext= self::find($html,'.ProductText div','plaintext',1);
				break;
			case 'clarisonic.com':
				$longtext= self::find($html,'.product_detail_description div','innertext',0).
					self::find($html,'.product_detail_description div','innertext',1).
					self::find($html,'.detailFeatures ul','outertext');
				$shorttext= self::find($html,'.product_detail_description div','innertext',0);
				break;
			case 'drugstore.com':
				$longtext= self::findAll($html,'#TblProdForkPromo .contenttd p');
				$shorttext= self::find($html,'#TblProdForkPromo .contenttd p','plaintext',0).
					self::find($html,'#TblProdForkPromo .contenttd p','plaintext',1);
				break;
			case 'paulaschoice.com':
				$longtext= self::find($html,'.basic-description').
					self::find($html,'.briefdescription ul','outertext');
				$shorttext= self::find($html,'.basic-description');
				break;
			case 'urbandecay.com':
				$longtext= self::findAll($html,'#tab_details span');
				$shorttext= self::find($html,'.b-pdp-video-text');
				break;
			default:
				$longtext = $shorttext = '';
				break;
		}
		
		return ['longtext'=>$longtext, 'shorttext'=>$shorttext];
	}
	
	private static function getDomain($url)
	{
		$parseUrl = parse_url(trim($url)); 
		$domain =  trim($parseUrl['host'] ? $parseUrl['host'] : array_shift(explode('/', $parseUrl['path'], 2))); 
		$nonwww = str_replace('www.','',$domain);
		return $nonwww;
	}
	
	private static function toTag($raw)
	{
		$raw = trim($raw);
		$tag = nl2br($raw);
		return '<p>'.$tag.'</p>';
	}
	
	
	private static function find($html, $rule, $label='plaintext',$position=0)
	{
		$obj = $html->find($rule,$position);
		if ($obj){
			$text = $obj->$label;
			if($label=='plaintext'){
				$text = self::toTag($text);
			}
			return $text;
		}	
	}
	
	private static function findAll($html, $rule, $label='plaintext')
	{
		$result = '';
		
		foreach ($html->find($rule) as $element){
			$text = $element->$label;
			if($label=='plaintext'){
				$text = self::toTag($text);
			}
			$result .= $text;
		}
		return $result;
	}
	
	
	private static function curlGet($url)
	{
		$curl = curl_init();
		
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HEADER, false);
		$agent= 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';
		curl_setopt($curl, CURLOPT_USERAGENT, $agent);
		
		curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
		curl_setopt($curl, CURLOPT_POST, false);
		$data = curl_exec($curl);
		
		curl_close($curl);
		
		return $data;
	}
	
	/**
	 * Get the content of the URL
	 * @param type $url
	 */
	public static function get($url)
	{
		$domain = self::getDomain($url);
		if($domain=='sephora.com' || $domain=='bobbibrowncosmetics.com'){
			$content = self::curlGet($url);
			$html = SimpleHtml::str_get_html($content);
		}else{
			$context = stream_context_create();
			stream_context_set_params($context, array('user_agent' => 'msnbot/1.1 (+http://search.msn.com/msnbot.htm)'));
			$html = SimpleHtml::file_get_html($url, 0, $context);
		}
		
		return self::retrieve($domain, $html);
	}
}