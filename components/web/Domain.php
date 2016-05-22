<?php

namespace app\components\web;

/**
 * Regarding the domain function
 */
class Domain{
	
	//Can be url, can be domain
	private $url;
	
	//the name in the hostname format: eg: www.abc.com
	private $hostname;
	
	//the name without tld, like "abc".
	private $name;
	
	public function __construct($url)
	{
		$this->url = $url;
		$this->hostname = $this->getHostname();
		$this->name = $this->getName();
	}
	
	public function getHostname()
	{
		$url = $this->url;
		$parseUrl = parse_url(trim($url)); 
		$domain =  trim($parseUrl['host'] ? $parseUrl['host'] : array_shift(explode('/', $parseUrl['path'], 2))); 
		$nonwww = str_replace('www.','',$domain);
		return $nonwww;
	}
	
	/**
	 * Domain name without tld
	 */
	public function getName()
	{
		$domainMeta = explode('.',$this->hostname);
		return $domainMeta[0];
	}
	
	/**
	 * Create class name based on the domain name
	 */
	public function getClassName()
	{
		$domainName = str_replace('-','',$this->name);
		return ucfirst($domainName);
	}
}