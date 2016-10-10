<?php
/**
 * @category   	Shop99 library
 * @copyright  	http://shop99.vn
 * @license    	http://shop99.vn/license
 */

namespace News\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Session\Container;

/**
 * Class News
 * @package News\Service
 */
class News implements ServiceLocatorAwareInterface
{
	const TIN247_CATEGORY 		= 'http://tin247.com/tin247_services_out/category.php';
	const TIN247_LATEST_NEWS 	= 'http://tin247.com/tin247_services_out/type.php?iCat=';
	const TIN247_SEARCH 		= 'http://tin247.com/tin247_services_out/search_product_news.php?';

	const TIN247_DETAIL			= 'http://tin247.com/tin247_services_out/detail.php?iNew=22601132&json=1';
	const TIN247_GET			= 'http://tin247.com/tin247_services_out/get_news_detail.php?iNew=';

	const NEWS_VIEWED 	= "ARTICLE_VIEW";

	/**
	 * @var ServiceLocatorInterface
	 */
	protected $serviceLocator;

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

	/**
	 * @param \News\Model\Article $article
	 */
	public function increaseArticleViewed(\News\Model\Article $article)
	{
		$sl = $this->getServiceLocator();
		/* @var \Website\Service\CrawlerDetect $crawlerDetect */
		$crawlerDetect = $sl->get('Website\Service\CrawlerDetect');
		if ($crawlerDetect->isCrawler() && ($newsId = $article->getId())) {
			$checkNewsViewed = new  Container(self::NEWS_VIEWED);
			if(!$checkNewsViewed->offsetExists($newsId)) {
				$newsMapper = $sl->get('News\Model\ArticleMapper');
				$newsMapper->increaseArticleView($article);
				$checkNewsViewed->offsetSet($newsId,true);
			}
		}
	}

	/**
	 * crawl tin247
	 * @param string $url
	 * @return mixed|boolean
	 */
	private function loadTin247($url)
	{
		// send result
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 1);
		curl_setopt($curl, CURLOPT_TIMEOUT, 1);
		$curlResult = curl_exec($curl);
		if(curl_error($curl)) {
			curl_close($curl);
			return false;
		}
		curl_close($curl);
		return $curlResult;
	}

	/**
	 * @param string $keyword
	 * @return array
	 */
	public function searchTin247($options)
	{
		$keyword = $options['keyword'];
		$limit = isset($options['limit']) ? $options['limit'] : 5;
		$cateId = isset($options['iCat']) ? $options['iCat'] : '';
		if(!$keyword || !$articles = $this->loadTin247(self::TIN247_SEARCH .'iCat=' .$cateId. '&keyword=' . $keyword)) {
			return null;
		}
		$articles = json_decode($articles, true);
		if(!is_array($articles) || !count($articles)) {
			return null;
		}
		$results = [];
		foreach($articles as $a) {
			$article = new \News\Model\Article();
			$article->setSource(\News\Model\Article::NEWS_SOURCE_TIN247);
			$article->setId($a['new_id']);
			$article->setCategoryId($a['new_category_id']);
			$article->setTitle($a['new_title']);
			$article->setPublishedDate(date('Y-m-d', $a['new_date']));
			$results[] = $article;
			if(count($results) >= $limit) {
				return $results;
			}
		}
		return $results;
	}

	/**
	 * @param int $id
	 * @return \News\Model\Article
	 */
	public function getTin247($id)
	{
		if(!$id || !$result = $this->loadTin247(self::TIN247_GET .$id)) {
			return null;
		}
		$result = json_decode($result, true);

		$article = new \News\Model\Article();
		$article->setSource(\News\Model\Article::NEWS_SOURCE_TIN247);
		$article->setId($result['new_id']);
		$article->setCategoryId($result['new_category_id']);
		$article->setPicture($result['path_img']);
		$article->setTitle($result['new_title']);
		$article->setIntro($result['new_teaser']);
		$article->setContent($result['new_description']);
		$article->setPublishedDate(date('Y-m-d', $result['new_date']));
		$article->setSourceLink($result['link']);
		return $result;
	}

	public function getLastestNews($options){
		$id = $options['id'];
		$limit = isset($options['limit']) ? $options['limit'] : 5;

		if(!$id || !$result = $this->loadTin247(self::TIN247_LATEST_NEWS .$id)) {
			return null;
		}
		return $result;
	}
}