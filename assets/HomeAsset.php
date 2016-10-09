<?php

namespace app\assets;

use yii\web\AssetBundle;

class HomeAsset extends AssetBundle
{
	public $sourcePath = '@bower/'; 
	public $js = [
		'angular/angular.js',
		'clipboard/dist/clipboard.min.js',
		'/js/mel-spintax.js',
		'/js/app.js',
		'/js/services.js',
	];
    
}
