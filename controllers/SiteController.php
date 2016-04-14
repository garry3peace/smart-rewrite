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

class SiteController extends Controller
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
		$spinTax = '';
		$content = '';
		$result = '';
		$options = Config::optionsWithEmptyValue();
		
		if (Yii::$app->request->post()) {
			$post = Yii::$app->request->post();
			$content = $post['Spin']['content'];
			
			if(strlen($content)<=Yii::$app->params['maxContentLength']){
				//load config
				$config = new Config($post['Spin']['options']);
				$options = $config->optionsWithValue();
				
				//spinning result
				$spinTax = Sentence::parse($content, $config);
				
				//randomly get one of the parsing spin result
				$result = SpinFormat::parse($spinTax);
				
			}
        }
        return $this->render('index',[
			'result'=>$result,
			'spinTax'=>$spinTax,
			'content'=>$content,
			'options'=>$options,
		]);
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    public function actionAbout()
    {
		$wordCount = \app\models\Word::count();
        return $this->render('about',[
			'wordCount'=>$wordCount,
		]);
    }
	
	public function actionImport()
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
        return $this->render('import');
	}
	
}
