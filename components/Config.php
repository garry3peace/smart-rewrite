<?php

/* 
 * Class to configure how the spin should do
 */

namespace app\components;

class Config {
	
	public function __construct($data)
	{
		foreach($data as $field=>$value){
			if(!$this->exist($field)){
				continue;
			}
			$this->$field = $value;
		}
		
	}
	
	private function exist($field)
	{
		if(in_array($field, $this->options())){
			return true;
		}
		
		return false;
	}
	
	/**
	 * Return list of available fields (options)
	 * @return type
	 */
	private static function options()
	{
		return [
			'unique', //Ensuring the initial word won't show 
			'exception', //Don't process synonym/antonym on these words
			'paragraph',//to reorder paragraph or not
			'paragraph_exclude',
			'sentenceRewrite',
		];
	}
	
	private function validate($field)
	{
		if(!$this->exist($field)){
			return false;
		}
		
		if(!isset($this->$field)){
			return false;
		}
		
		return true;
	}
	
	public function is($field)
	{
		if(!$this->validate($field)){
			return null;
		}
		
		return $this->$field;
	}
	
	public function get($field)
	{
		if(!$this->validate($field)){
			return null;
		}
		
		return $this->$field;
	}
	
	/**
	 * Return list of config options with empty value
	 */
	public static function optionsWithEmptyValue()
	{
		$options = self::options();
		$result = [];
		foreach($options as $option){
			$result[$option] = '';
		}
		return $result;
	}
	
	/**
	 * Return list of config options with correspondent value
	 */
	public function optionswithValue()
	{
		$options = self::options();
		$result = [];
		foreach($options as $option){
			$result[$option] = $this->get($option);
		}
		return $result;
	}
	
	/**
	 * Get text value based on semicolon to convert it into array
	 * @param string $field name
	 * @return array
	 */
	public function getArray($field)
	{
		$data = $this->get($field);
		return explode(';', $data);
		
	}
	
}