<?php
namespace app\components;

/**
 * Kelas kecil untuk membuat tampilan form yang ditampilkan sesuai dengan Angular
 *
 * @author garry
 */
class Html extends \yii\bootstrap\Html {
	
	/**
	 * Mengubah format string "model[attribute]" menjadi "model.attribute"
	 */
	private static function toDotFormat($string)
	{
		if(preg_match('%(.+)\[(.+)\]%', $string, $matches)){
			$model = $matches[1];
			$attribute = $matches[2];
			return $model.'.'.$attribute;
		}
		return $string;
	}
	
	public static function checkbox($name, $value='', $options=[])
	{
		$options['ng-model'] = self::toDotFormat($name);
		return parent::checkbox($name, $value, $options);
	}
	
	public static function inputText($name, $value='', $options=[])
	{
		$options['ng-model'] = self::toDotFormat($name);
		return parent::textInput($name, $value, $options);
	}
	
	public static function textarea($name, $value='', $options=[])
	{
		$options['ng-model'] = self::toDotFormat($name);
		return parent::textarea($name, $value, $options);
	}
	
		
}
