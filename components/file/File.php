<?php
namespace app\components\file;

/**
 * Class about system and path
 *
 * @author garry
 */
class File {
	/**
	 * Read file of certain folder
	 * @param string $filenameOnly if true will return the name file without extension
	 * @return type
	 */
	static function loadDir($path, $filenameOnly=false){
		$files = scandir($path);
		
		//we don't need folder . and ..
		unset($files[0]); 
		unset($files[1]);
		
		//Return array with new indexes
		$result = array_values($files);
		
		
		//Run this code if 
		$temp = [];
		if($filenameOnly){
			foreach($result as $item)
			{
				$temp[] = pathinfo($item, PATHINFO_FILENAME);
			}
			$result = $temp;
		}
		
		return $result;
	}
}
