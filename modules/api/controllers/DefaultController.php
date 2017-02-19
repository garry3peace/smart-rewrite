<?php

namespace app\modules\api\controllers;

use Yii;
use app\modules\api\components\BaseActiveController;
use app\components\Config;
use app\components\SmartRewrite;

/**
 * Default controller for the `api` module
 */
class DefaultController extends BaseActiveController
{
	public $modelClass = '';
	
	public function actions() {
		$actions = parent::actions();

		/** hapus default action bawaan dari ActiveController */
		unset($actions['index']);
		unset($actions['update']);
		unset($actions['delete']);
		unset($actions['view']);

		return $actions;
	}
	
	 /**
     * @inheritdoc
     */
    protected function verbs()
    {
		$verbs = parent::verbs();
		$verbs['index'] = ['POST'];
        return $verbs;
    }

	/**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $post = Yii::$app->request->post();
		
		if(!isset($post['Spin'])){
			return '';
		}
		
		$content = $post['Spin']['content'];
		if (strlen($content) > Yii::$app->params['maxContentLength']) {
			return '';
		}
		
		//load config
		$postOptions = [];
		if(isset($post['Options'])){
			$postOptions = $post['Options'];
		}

		$config = new Config($postOptions);

		//spinning result
		$sentence = new SmartRewrite($content, $config);
		$sentence->rewrite();
		$spinTax = $sentence->getRewriteSentence();

		return $spinTax;
    }
}
