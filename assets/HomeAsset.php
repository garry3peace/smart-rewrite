<?php

namespace app\assets;

use yii\web\AssetBundle;

class HomeAsset extends AssetBundle
{
	public $sourcePath = '@bower/'; 
	public $js = [
		'clipboard/dist/clipboard.min.js',
	];
    
}
