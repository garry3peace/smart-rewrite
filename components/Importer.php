<?php

/* 
 * Class handling importing Word 
 */
namespace app\components;

use Yii;
use app\models\Synonym;
use app\models\Antonym;

class Importer
{
	private static function emptyTable()
	{
		$connection = Yii::$app->db;
		$connection->createCommand()->truncateTable('antonym')->execute();
		$connection->createCommand()->truncateTable('synonym')->execute();
		$connection->createCommand()->truncateTable('word')->execute();
	}
	
	public static function import($data)
	{
		//truncate all tables first
		self::emptyTable();
		
		$data = explode(PHP_EOL, $data);
		
		foreach($data as $row){		
			$row = trim($row);
			
			if(strpos($row,'*')>0){
				self::multiwayAntonym($row);
			}else if(strpos($row,'=')>0){
				self::onewaySynonym($row);
			}else{
				self::multiwaySynonym($row);
			}
		}
	}
	
	/**
	 * This function processing the line looks like this 
	 * about,approximately,around (where every word can change each others)
	 */
	private function multiwaySynonym($row)
	{
		$list = explode(',', $row);
		foreach($list as $source){
			foreach($list as $target){
				Synonym::create($source, $target);
			}
		}
	}
	
	/**
	 * This function to process one way synonym. The format looks like this
	 * dead=>passed away,fallen,gone
	 */
	private function onewaySynonym($row)
	{
		$segments = explode('=', $row);
		$sources = explode(',', $segments[0]);
		$targets = explode(',',$segments[1]);
		foreach($sources as $source){
			foreach($targets as $target){
				Synonym::create($source, $target);
			}
		}
	}
	
	/**
	 * This function to process one way antonym. The format looks like this
	 * fast*slow,sluggish
	 */
	private function multiwayAntonym($row)
	{
		$segments = explode('*', $row);
		$sources = explode(',',$segments[0]);
		$targets = explode(',',$segments[1]);
		foreach($sources as $source){
			foreach($targets as $target){
				Antonym::create($source, $target);
				Antonym::create($target, $source);
			}
		}
	}
}