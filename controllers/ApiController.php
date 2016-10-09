<?php

namespace app\controllers;
use yii\rest\Controller;
use app\components\Config;
use app\components\SmartRewrite;
use app\components\SpinFormat;
use Yii;

/**
 * ApiController untuk melakukan proses bagian belakang layar
 *
 * @author garry
 */
class ApiController extends Controller {

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
		$options = $config->optionsWithValue();


		//spinning result
		$sentence = new SmartRewrite($content, $config);
		$sentence->rewrite();
		$spinTax = $sentence->getRewriteSentence();

		return $spinTax;
		
	}

}
