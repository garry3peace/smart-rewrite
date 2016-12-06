<?php

namespace app\themes\lumino\assets;

use yii\web\AssetBundle;
use Yii;

/**
 * Default asset bundle.
 */
class IEAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [];
    public $js = [];
    public $depends = [
        'yii\web\YiiAsset',
    ];

    public function init()
    {
        parent::init();

		$themeUrl = 'themes/lumino';

        $this->js = [
            $themeUrl.'/js/html5shiv.js',
            $themeUrl.'/js/respond.min.js',	
        ];
		
		$this->jsOptions=[
			'condition' => 'lt IE9'
		];
    }
}
