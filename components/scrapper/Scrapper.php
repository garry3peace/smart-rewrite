<?php
namespace app\components\scrapper;
use app\components\scrapper\SimpleHtml;

class Scrapper
{

	/**
	 * Function to retrieve content from onlineshop content
	 * automatically
	 */
	private static function onlineShop($domain)
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
			case 'maccosmetics.com':
				$longtext= self::findAll($html,'.product__description-group-body');
				$shorttext= self::find($html,'.product__description-short');
				break;
			case 'maybelline.com':
				$longtext= ucfirst(trim(strtolower(self::find($html,'.details')))).self::find($html,'.details','plaintext',1);
				$shorttext= ucfirst(trim(strtolower(self::find($html,'.details'))));
				break;
			case 'thayers.com':
				$longtext= self::find($html,'.summary div p').self::find($html,'.summary div p','plaintext',1);
				$shorttext= self::find($html,'#tab-description p','plaintext').self::findAll($html,'#tab-description .greentext3');
				break;
			case 'benefitcosmetics.com':
				$longtext= self::find($html,'.details-product-module-body .field-item','innertext').
					'<p>Ingredients</p>'.self::find($html,'.field-name-field-ingredients .field-item','innertext').
					'<p>How to use</p>'.self::find($html,'.field-name-field-how-to-use .field-item','innertext');
				$shorttext= self::find($html,'.field-name-field-statement-of-id .field-item','outertext');
				break;
			case 'mariobadescu.com':
				$longtext= self::find($html,'td.product_details_text','plaintext',0).
					'<p>How to Use</p>'.self::find($html,'td.product_details_text','plaintext',10).
					'<p>Ingredients</p>'.self::find($html,'td.product_details_text','plaintext',11);
				$shorttext= self::find($html,'td.product_details_text');
				break;
			case 'ulta.com':
				$longtext = self::find($html, '.current-longDescription','innertext');
				$shorttext = self::find($html, '.current-longDescription p');
				break;
			case 'shuuemura-usa.com':
				$longtext = self::findAll($html, '.product-description p');
				$shorttext = self::find($html, '.product-description p');
				break;
			case 'tatcha.com':
				$longtext = self::findAll($html, '#description p','outertext');
				$shorttext = self::find($html, '#description p');
				break;
			case 'toofaced.com':
				$longtext = self::find($html, '.what-it-is').
					'<p>More to love: </p>'.self::find($html, '.more-to-love ul','outertext');
				$shorttext = self::find($html, '.description');
				break;
		}
		return ['longtext'=>$longtext, 'shorttext'=>$shorttext];
	}
	
	
	private static function cnnindonesia($html)
	{
		$title = self::find($html, '.content_detail h1');
		
		//remove the content "side see more"
		$nodes = $html->find('.text_detail .topiksisip');
		foreach($nodes as $node){
			$node->outertext='';
		}
		
		//remove link sisip
		$nodes = $html->find('.text_detail .linksisip');
		foreach($nodes as $node){
			$node->outertext='';
		}
		
		//remove the first "cnn-jakarta"
		$node = $html->find('.text_detail strong', 0);
		$node->outertext='';
		
		$node = $html->find('.text_detail b', -1);
		$node->outertext='';
		
		$content = self::find($html, '.text_detail','innertext');
		$content = str_replace('<!–// –>','',$content);
		
		return [$title, $content];
	}
	
	private static function wowkeren($html)
	{
		$title =self::find($html, '#JudulHalaman h1');
		
		//remove link
		$nodes = $html->find('#IsiBerita .content p a');
		foreach($nodes as $node){
			$node->innertext=$node->plaintext;
		}
		
		$content = self::find($html, '#IsiBerita .content p','innertext');
		
		//remove brand
		$content = str_replace('<strong>WowKeren.com</strong> - ','',$content);
		
		
		return [$title, $content];
	}
	
	private static function kapanlagi($html)
	{
		$title =self::find($html, '.entertainment-newsdetail-title-new');
		$content = self::find($html, '.entertainment-detail-news');
		$content = str_replace('Kapanlagi.com - ','',$content);
		return [$title, $content];
	}
	
	/**
	 * execute how to get content based on domain
	 * @param string $domain
	 * @param SimpleHtml $html
	 * @return type
	 */
	private static function retrieve($domain, $html)
	{
		$domainMeta = explode('.',$domain);
		$domainName = $domainMeta[0];
		
		$title = $content = '';
		
		if(method_exists(new self,$domainName)){
			list($title, $content) = self::$domainName($html);
		}		
		
		return ['title'=>$title, 'content'=>$content];
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
			return $text;
		}	
	}
	
	private static function findAll($html, $rule, $label='plaintext')
	{
		$result = '';
		
		foreach ($html->find($rule) as $element){
			$text = $element->$label;
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
		curl_setopt($curl,CURLOPT_ENCODING , 'gzip');
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
		
		$content = self::curlGet($url);
//		print_r($content);
		$html = SimpleHtml::str_get_html($content);
//		var_dump($html);die('#');
		return self::retrieve($domain, $html);
	}
}