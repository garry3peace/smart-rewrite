<?php

namespace app\components;

use app\components\Curl;

class WordpressPoster
{
	private $title;
	private $content;
	
	const API_URL = 'http://www.ligafilm.com/wp-json';
	const CONSUMER_KEY = 'fuqA355h80Tf';
	const CONSUMER_SECRET = 'ZdpDG2XhtB2gFkyTaSJQJ2xhSBTEFNOWSvz5eYubjV1iHq5Q';
	const OAUTH_TOKEN = 'LBPGXZnq095gXnS6suFD4fuC';
	const OAUTH_TOKEN_SECRET = 'zhby4W1PcFGcijm706AfkLoqmVavzjhXwnyOAEL3zAYtZZ8Z'; 
	
	public function __construct($title, $content)
	{
		$this->title = $title;
		$this->content = $content; 
	}
	
	/**
	 * Rewriting the content 
	 * return the unique content back
	 */
	private function rewriteContent()
	{
		$content = $this->content;
		
		//for title, we use the summary of content
//		$summarizer = new \app\components\summarizer\Summarizer($content, 1);
//		$this->title = $summarizer->summarize();
		
		$configValue = [
			'unique'=>true,
			'paragraph'=>true,
			'paragraph_exclude'=>'1,2,3,-2,-1',
		];
		$config = new Config($configValue);
		
		$sentence = new Sentence($content, $config);
		$sentence->rewrite();
		$spinTax = $sentence->getRewriteSentence();
		$this->content = SpinFormat::parse($spinTax);
	}
	
	
	/**
	 * Fungsi untuk menyusun data yang akan di-post ke cURL
	 * @param array $fields pasangan key value yang akan disubmit
	 * @return string
	 */
	private function query($fields)
	{
		
		$fieldString = '';
		foreach($fields as $key=>$value) { 
			$fieldString .= $key.'='.rawurlencode($value).'&'; 
		}

		$fieldString = rtrim($fieldString, '&');
		return $fieldString;
	}
	
	private function url($url)
	{
		return self::API_URL.'/'.$url;
	}
	
  

	/**
	 * komunikasi dengan server wordpress
	 * @return type
	 */
	private function curl($url, $data)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
		
		//DEBUG MODE
		/*
		curl_setopt($ch, CURLOPT_VERBOSE, TRUE);
		$fp =  fopen("curl.error","w");
		curl_setopt($ch, CURLOPT_STDERR, $fp);		 
		 */
		
		curl_setopt($ch, CURLOPT_URL, $this->url($url));
		$headers = array('Content-Type: application/x-www-form-urlencoded');
		curl_setopt($ch, CURLOPT_HEADER, $headers);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
		$curl = new Curl;
		$curl->maxRedirect =0;
		$result = $curl->exec($ch);
		$httpcode = $curl->getCode();
		$error = $curl->getError();
//		var_dump($error,$result);die();
				
		return ['result'=>$result, 'code'=>$httpcode];
		
	}

	private function getSignature($url, $query)
	{
		$key = rawurlencode(self::CONSUMER_SECRET). '&' . rawurlencode(self::OAUTH_TOKEN_SECRET);
		$string = strtoupper('post') . '&' . rawurlencode($url) . '&' . rawurlencode($query);
//		var_dump($key,$string);
		$result= base64_encode(hash_hmac('sha1', $string, $key, true));
//		var_dump($result);
		return $result;
	}

	private function getData()
	{
		$nonce = md5(mt_rand());
		$timestamp = time();
		
		$postData = [
			'content'=>$this->content,
			'title'=>$this->title,
			'status'=>'publish',
			//'categories'=>14,


			'oauth_consumer_key'=>self::CONSUMER_KEY,
			'oauth_nonce'=>$nonce,
			'oauth_signature_method'=>"HMAC-SHA1",
			'oauth_timestamp'=>$timestamp,
			'oauth_token'=> self::OAUTH_TOKEN,
			'oauth_version'=>"1.0",
		];
		uksort( $postData, 'strcmp' );
		return $postData;
	}
	
	public function post()
	{
		$this->rewriteContent();

//		print_r($this->title);
//		print_r($this->content);

		$postData = $this->getData();
//		print_r($postData);
		$data = $this->query($postData);
//		print_r($data);die();
		$signature = $this->getSignature($this->url('wp/v2/posts'), $data);
		$data .= '&oauth_signature='.$signature;

		return $this->curl('wp/v2/posts',$data);
	}
}
