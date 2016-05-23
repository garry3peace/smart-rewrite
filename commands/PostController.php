<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use \app\components\WordpressPoster;
use \app\models\ScrapeLog;

/**
 * This command is for processing simple scrapping and post it into wordpress
 */
class PostController extends Controller
{
    /**
     * Scrape the site with the given list of website
	 * and save the list of url into the scrapelog
     * 
     */
    public function actionScrapeList()
    {
        $list = [
			//'http://www.cnnindonesia.com/',
			'http://www.muvila.com',
			];
		
		foreach($list as $web){
			$scrapeList = new \app\components\scrapper\ScrapperList($web);
			$result = $scrapeList->get();
			
			foreach($result as $url){
				//Skip if already exists in log
				$scrapeLog = ScrapeLog::findByUrl($url);
				if(!empty($scrapeLog) && $scrapeLog->isPosted()){
					continue;
				}
				
				//Save into log
				$item = [
					'title'=>null,
					'content'=>null,
					'url'=>$url,
					'code'=>0,
				];
				ScrapeLog::logPost($item);
			}
		}
    }
	
	/**
	 * Get list of urls from the scrapelog that hasn't posted to wordpress yet
	 * and post it.
	 * @param int $limit of the post for each execution
	 */
	public function actionPost($limit=1)
	{
		$list = ScrapeLog::findAllNotPosted();
		
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
			$wp = new WordpressPoster($data['title'], $data['content']);
			$result = $wp->post();
			
			print_r($result);
			
			//Save into log
			$item = [
				'title'=>$data['title'],
				'content'=>$data['content'],
				'url'=>$url,
				'code'=>$result['code'],
			];
			ScrapeLog::logUpdate($item);
			$count++;
			
			//Stop when have reached the limit of post
			if($count>=$limit){
				break;
			}
		}
	}
}
