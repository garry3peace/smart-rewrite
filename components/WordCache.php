<?php

namespace app\components;

use yii\base\Component;

/**
 * Class to dealing cache the word so it won't being synonymized
 */
class WordCache extends Component
{
	private $cache;
	private $counter;
	
	public function init()
	{
		$this->counter=0;
		return parent::init();
	}
	
	/**
	 * Storing the cache value and replace the real word into placeholder
	 * @param string $data the word that is going to replace
	 * @param string $sentence the whole sentences
	 * @return type
	 */
	public function store($data, $sentence)
	{
		$counter = $this->counter;
		$label = "%$counter%";
		$this->cache[$label] = $data;
		$this->counter++;
		
		$sentence = str_replace($data, $label, $sentence);
		return $sentence;
	}
	
	public function getAll()
	{
		return $this->cache;
	}
	
}
