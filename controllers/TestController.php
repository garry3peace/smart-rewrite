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

class TestController extends Controller
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
	
	private function getString()
	{
		$text = "Penyihir, kurcaci, elf, dan orc bukan makhluk-makhluk asing bagi penggemar permainan Warcraft. Legendary Pictures dan Blizzard Entertainment \"menghidupkan\" makhluk-makhluk yang hanya ada dalam dunia fantasi itu. Kali ini melewati film Warcraft yang diangkat dari permainan populer bertajuk sama.

Perkara rebutan tempat tinggal tidak ayal membuat 2 kelompok makhluk itu wajib berperang.

Dari segi bunyi, scoring film ini juga terdengar baik. Tidak hanya membuat tegang saat peperangan terjadi, Saat adegan murung ditampilkan, akibat bunyi juga membuat haru.

Drama dan peperangan yang tak titik puncak, ditambah karakter yang penempatannya kurang pas, membuat kecewa penonton yang berharap lebih dari Warcraft. Terlebih bagi mereka yang gemar dan paham permainannya.

Sejak Sebab Dunianya rusak awal dikisahkan, orc tengah mencari tempat lain untuk hidup. Pemimpin orc yang juga penyihir, Gul'dan membawa kaumnya ke dunia manusia yang disebut Azeroth, dengan ilmu sihir gelap.

Cerita manusia dan makhluk fantasi yang berebut tempat tinggal memang klasik. Namun perang mereka jadi sesuatu yang layak tunggu dan tonton dalam film Warcraft. Sutradara bisa membuat adegan perang menarik dan berimbas dahsyat.

Tapi tidak bisa dibantah, pengambilan gambar film ini terlihat bagus. Sudut gambarnya rapi dan perpindahan gambarnya membuat membuat penonton nyaman. Komposisi gambar pun terasa pas.

Penonton yang awam bakal dibingungkan oleh aneka karakter yang ahistori.

Kedatangan orc ke Azeroth tidak membuat Kerajan Stormwind tinggal diam. Tawanan manusia itu digunakan sebagai energi untuk memanggil seluruh orc ke Azeroth.

Duncan Jones sebagai direktur juga terlalu tidak sedikit menaruh adegan drama. Terkadang itu terasa tak masuk akal dan sungguh gampang ditebak.

Sayangnya, peperangan dalam film ini terasa tak titik puncak. Ada sejumlah adegan yang dimulai dengan baik tapi tidak jelas alhasil. Ada juga karakter yang ditempatkan mendadak tanpa uraian asal-muasalnya. Cerita tentang karakter itu juga tak dibuat logis dan terkesan terlalu mengada-ada. Mungkin direktur lupa, tak seluruh penonton Warcraft adalah penggemar berat permainannya.

Cerita Warcraft fokus pada kehidupan orc dan manusia yang selalu berseberangan. Kedua kaum itu bahkan rela saling menghabisi untuk melindungi anggota masing-masing maupun menguasai apa yang mau dimiliki.

Secara keseluruhan film ini layak dinilai enam dari sepuluh. Keapikan visualnya menolong sejumlah drama klasik dan adegan yang tidak masuk akal.

Warcraft sudah bisa ditonton di bioskop-bioskop Indonesia semenjak Rabu (dua puluh lima/lima).";
		
		return $text;
	}


	public function actionSummary()
	{
		
		$text = $this->getString();
		$summarizer = new \app\components\summarizer\Summarizer($text);
		var_dump($summarizer->getKeywords());
	}
	
	public function actionArrangeParagraph()
	{
		$text = $this->getString();
		$paragraph = new \app\components\rewriter\ParagraphRewriter($text);
		var_dump($paragraph->rearrange());
	}
	
	public function actionPost()
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
	
	public function actionScrape()
	{
		$url = 'http://www.cnnindonesia.com/hiburan/20160526042220-220-133502/suguhan-perang-tanpa-klimaks-dalam-warcraft/';
		$scrapePage = \app\components\scrapper\ScrapperPageFactory::get($url);
		$data = $scrapePage->get();
		print_r($data['content']);
	}
	
	public function actionNumber()
	{
		$string = 'Lalu 4.';
		$configValue = [
			'unique'=>true,
		];
		$config = new Config($configValue);
		
		$spinTax = Sentence::parse($string, $config);
		var_dump($spinTax);
	}
	
	public function actionSentence()
	{
		$content = $this->getString();//'Itulah. Kemudian kamu menemukan buah apel.';
		$configValue = [
			'unique'=>true,
		];
		$config = new Config($configValue);
		
		$smartRewrite = new \app\components\SmartRewrite($content, $config) ;
		$smartRewrite->rewrite();
		$spinTax = $smartRewrite->getRewriteSentence();
		print_r($spinTax);
	}
	
	public function actionStrtok()
	{
		$text = $this->getString();
		$count = 0;
		$token = strtok($text, ' ');
		while($token !==false){
			echo $token.'<br/>';
			
			$token = strtok(' ');
		}
	}
	
	public function actionTest3()
	{
		
	}
}
