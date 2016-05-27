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
	public function actionSummarize()
	{
		$content = '';
		$summary = '';
		$line = 5;
		
		if(isset($_POST['Summarize'])){
			$content = $_POST['Summarize']['content'];
			$line = intval($_POST['Summarize']['line']);
			$summarizer = new \app\components\summarizer\Summarizer($content);
			$summarizer->setNumOfResult($line);
			$summary = $summarizer->summarize();
			
		}
		return $this->render('summarize',[
			'content'=>$content,
			'summary'=>$summary,
			'line'=>$line]
			);
	}
	
	private function testSummary()
	{
		$text = "Penyihir, kurcaci, elf, dan orc bukan makhluk-makhluk asing bagi penggemar permainan Warcraft. Legendary Pictures dan Blizzard Entertainment \"menghidupkan\" makhluk-makhluk yang hanya ada dalam dunia fantasi itu. Kali ini melewati film Warcraft yang diangkat dari permainan populer bertajuk sama.

Perkara rebutan tempat tinggal tidak ayal membuat 2 kelompok makhluk itu wajib berperang.

Dari segi bunyi, scoring film ini juga terdengar baik. Tidak hanya membuat tegang saat peperangan terjadi, Saat adegan murung ditampilkan, akibat bunyi juga membuat haru.

Drama dan peperangan yang tak titik puncak, ditambah karakter yang penempatannya kurang pas, membuat kecewa penonton yang berharap lebih dari Warcraft. Terlebih bagi mereka yang gemar dan paham permainannya.

Sejak Sebab Dunianya rusak awal dikisahkan, orc tengah mencari tempat lain untuk hidup. Pemimpin orc yang juga penyihir, Gul'dan membawa kaumnya ke dunia manusia yang disebut Azeroth, dengan ilmu sihir gelap.

Cerita manusia dan makhluk fantasi yang berebut tempat tinggal memang klasik. Namun perang mereka jadi sesuatu yang layak tunggu dan tonton dalam film Warcraft. Sutradara bisa membuat adegan perang menarik dan berimbas dahsyat.

Tapi tidak bisa dibantah, pengambilan gambar film ini terlihat bagus. Sudut gambarnya rapi dan perpindahan gambarnya membuat membuat penonton nyaman. Komposisi gambar pun terasa pas.

Penonton yang awam bakal dibingungkan oleh aneka karakter yang ahistori.

Kedatangan orc ke Azeroth tidak membuat Kerajan Stormwind tinggal diam. Mereka mau membasmi Horde Manusia sebagai tawanan dijadikan yang menghancurkan kampung 1 per 1 dan. Tawanan manusia itu digunakan sebagai energi untuk memanggil seluruh orc ke Azeroth.

Duncan Jones sebagai direktur juga terlalu tidak sedikit menaruh adegan drama. Terkadang itu terasa tak masuk akal dan sungguh gampang ditebak.

Sayangnya, peperangan dalam film ini terasa tak titik puncak. Ada sejumlah adegan yang dimulai dengan baik tapi tidak jelas alhasil. Ada juga karakter yang ditempatkan mendadak tanpa uraian asal-muasalnya. Cerita tentang karakter itu juga tak dibuat logis dan terkesan terlalu mengada-ada. Mungkin direktur lupa, tak seluruh penonton Warcraft adalah penggemar berat permainannya.

Cerita Warcraft fokus pada kehidupan orc dan manusia yang selalu berseberangan. Kedua kaum itu bahkan rela saling menghabisi untuk melindungi anggota masing-masing maupun menguasai apa yang mau dimiliki.

Secara keseluruhan film ini layak dinilai enam dari sepuluh. Keapikan visualnya menolong sejumlah drama klasik dan adegan yang tidak masuk akal.

Warcraft sudah bisa ditonton di bioskop-bioskop Indonesia semenjak Rabu (dua puluh lima/lima).";
		
		$summarizer = new \app\components\summarizer\Summarizer($text);
		var_dump($summarizer->getKeywords());
	}
	
	private function testArrangeParagraph()
	{
		$text = "Penyihir, kurcaci, elf, dan orc bukan makhluk-makhluk asing bagi penggemar permainan Warcraft. Legendary Pictures dan Blizzard Entertainment \"menghidupkan\" makhluk-makhluk yang hanya ada dalam dunia fantasi itu. Kali ini melewati film Warcraft yang diangkat dari permainan populer bertajuk sama.<br/><br/>

Perkara rebutan tempat tinggal tidak ayal membuat 2 kelompok makhluk itu wajib berperang.<br/><br/>

Dari segi bunyi, scoring film ini juga terdengar baik. Tidak hanya membuat tegang saat peperangan terjadi, Saat adegan murung ditampilkan, akibat bunyi juga membuat haru.<br/><br/>

Drama dan peperangan yang tak titik puncak, ditambah karakter yang penempatannya kurang pas, membuat kecewa penonton yang berharap lebih dari Warcraft. Terlebih bagi mereka yang gemar dan paham permainannya.<br/><br/>

Sejak Sebab Dunianya rusak awal dikisahkan, orc tengah mencari tempat lain untuk hidup. Pemimpin orc yang juga penyihir, Gul'dan membawa kaumnya ke dunia manusia yang disebut Azeroth, dengan ilmu sihir gelap.<br/><br/>

Cerita manusia dan makhluk fantasi yang berebut tempat tinggal memang klasik. Namun perang mereka jadi sesuatu yang layak tunggu dan tonton dalam film Warcraft. Sutradara bisa membuat adegan perang menarik dan berimbas dahsyat.<br/><br/>

Tapi tidak bisa dibantah, pengambilan gambar film ini terlihat bagus. Sudut gambarnya rapi dan perpindahan gambarnya membuat membuat penonton nyaman. Komposisi gambar pun terasa pas.<br/><br/>

Penonton yang awam bakal dibingungkan oleh aneka karakter yang ahistori.<br/><br/>

Kedatangan orc ke Azeroth tidak membuat Kerajan Stormwind tinggal diam. Mereka mau membasmi Horde Manusia sebagai tawanan dijadikan yang menghancurkan kampung 1 per 1 dan. Tawanan manusia itu digunakan sebagai energi untuk memanggil seluruh orc ke Azeroth.<br/><br/>

Duncan Jones sebagai direktur juga terlalu tidak sedikit menaruh adegan drama. Terkadang itu terasa tak masuk akal dan sungguh gampang ditebak.<br/><br/>

Sayangnya, peperangan dalam film ini terasa tak titik puncak. Ada sejumlah adegan yang dimulai dengan baik tapi tidak jelas alhasil. Ada juga karakter yang ditempatkan mendadak tanpa uraian asal-muasalnya. Cerita tentang karakter itu juga tak dibuat logis dan terkesan terlalu mengada-ada. Mungkin direktur lupa, tak seluruh penonton Warcraft adalah penggemar berat permainannya.<br/><br/>

Cerita Warcraft fokus pada kehidupan orc dan manusia yang selalu berseberangan. Kedua kaum itu bahkan rela saling menghabisi untuk melindungi anggota masing-masing maupun menguasai apa yang mau dimiliki.<br/><br/>

Secara keseluruhan film ini layak dinilai enam dari sepuluh. Keapikan visualnya menolong sejumlah drama klasik dan adegan yang tidak masuk akal.<br/><br/>

Warcraft sudah bisa ditonton di bioskop-bioskop Indonesia semenjak Rabu (dua puluh lima/lima).";
		
		$paragraph = new \app\components\rewriter\ParagraphRewriter($text);
		var_dump($paragraph->rearrange());
	}
	
	private function testPost()
	{
		$limit = 1;
		$list = \app\models\ScrapeLog::findAllNotPosted();
		
		//scrape the page
		$count = 0;
		foreach($list as $log){
			$url = $log->url;
			
			$scrapePage = \app\components\scrapper\ScrapperPageFactory::get($url);
			$data = $scrapePage->get();

			//Skip if no content
			if(empty($data['content'])){
				$item['url']= $url;
				ScrapeLog::logExclusion($item);
				continue;
			}

			//post to wordpress
			$wp = new \app\components\WordpressPoster($data['title'], $data['content']);
			$result = $wp->post();
			
			var_dump($result);
			
			//Save into log
			$item = [
				'title'=>$data['title'],
				'content'=>$data['content'],
				'url'=>$url,
				'code'=>$result['code'],
			];
			\app\models\ScrapeLogScrapeLog::logUpdate($item);
			
			$count++;
			//Stop when have reached the limit of post
			if($count >= $limit){
				break;
			}
		}
	}
	
	public function actionTest()
	{
		$this->testArrangeParagraph();
	}
}
