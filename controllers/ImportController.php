<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\components\Sentence;
use app\components\Importer;
use app\components\SpinFormat;
use app\components\Config;

class ImportController extends Controller
{
	public $metaKeyword;
	public $metaDescription;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
	{
		if($_SERVER['HTTP_HOST']!='localhost:8080'){
			die();
		}
		
        if (Yii::$app->request->post()) {
			$post = Yii::$app->request->post();
			$data = $post['import']['data'];
			Importer::import($data);
            return $this->refresh();
        }
        return $this->render('index');
	}
	
	public function actionExclusion()
	{
		if($_SERVER['HTTP_HOST']!='localhost:8080'){
			die();
		}
		
        if (Yii::$app->request->post()) {
			$post = Yii::$app->request->post();
			$data = $post['import']['exclusion'];
			Importer::importException($data);
            return $this->refresh();
        }
        return $this->render('exclusion');
	}
}
