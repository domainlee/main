<?php
/**
 * News\View\Helper\News
 *
 * @category    Shop99 library
 * @copyright   http://shop99.vn
 * @license     http://shop99.vn/license
 */

namespace News\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class News
 * @package News\View\Helper
 */
class News extends AbstractHelper
{

    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator($serviceLocator)
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
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     */
    public function __construct($serviceLocator)
    {
        $this->setServiceLocator($serviceLocator);
    }

    /**
     * @uses get news categories
     */
    public function getArtCategory($options = null)
    {
        $CategoryMapper = $this->getServiceLocator()->get('News\Model\CategoryMapper');
        /* @var $CategoryMapper \News\Model\CategoryMapper */
        if (!isset($options['limit'])) {
            $options['limit'] = '';
        }
        $category = new \News\Model\Category();
        $category->setStoreId($this->getServiceLocator()->get('Store\Service\Store')->getStoreId());
        return $CategoryMapper->getCategory($category, $options);
    }

    public  function getNewsId($id){
        $mapper = $this->getServiceLocator()->get('News\Model\ArticleMapper');
        return $mapper->getNewsId($id);
    }
    /**
     * @param $options [limit,start,iccp,categoryId,page]
     * @return array|null|\Zend\Paginator\Paginator
     */
    public function getLastestNews($options = null)
    {
        $mapper = $this->getServiceLocator()->get('News\Model\ArticleMapper');
        /* @var $mapper \News\Model\ArticleMapper */
     	$news = new \News\Model\Article();
        $news->setStoreId($this->getServiceLocator()->get('Store\Service\Store')->getStoreId());
     	// get categoryIds
        if (isset($options['categoryId'])) {
        	$news->setCategoryId($options['categoryId']);
        }
        if (isset($options['categoryIds'])) {
        	$news->setCategoryIds($options['categoryIds']);
        }
        if (isset($options['domainId'])) {
            $news->setDomainId($options['domainId']);
        }
        if (isset($options['limit'])) {
            $news->addOption('limit', $options['limit']);
        }
    	if ($news->getCategoryId()) {
        	$category = new \News\Model\Category();
        	$category->setId($news->getCategoryId());
        	/* @var $categoryMapper \News\Model\CategoryMapper */
        	$categoryMapper = $this->getServiceLocator()->get('News\Model\CategoryMapper');
        	$childIds = $categoryMapper->getChildIds($category);
        	if (count($childIds)) {
        		$news->setCategoryIds($childIds);
        	}
    	}
        $news->setOptions($options);
        return $mapper->search($news);
    }

    /**
     * @param array $options
     * @return array|null|\Zend\Paginator\Paginator
     */
    public function searchArticle($options = null)
    {
        $articleMapper = $this->getServiceLocator()->get('News\Model\ArticleMapper');
        /* @var $articleMapper \News\Model\ArticleMapper */
        $article = new \News\Model\Article();
        $article->setStoreId($this->getServiceLocator()->get('Store\Service\Store')->getStoreId());
        if (isset($options['articleId'])) {
            $article->setId($options['articleId']);
        }
        if (isset($options['categoryId'])) {
            $article->setCategoryIds($options['categoryId']);
        }
        if (isset($options['domainId'])) {
            $article->setDomainId($options['domainId']);
        }
        if(isset($options['limit'])){
            $article->addOption('limit', $options['limit']);
        } else {
            $article->addOption('limit', 20);
        }
        if(isset($options['order'])){
            if($options['order'] == 'mostview'){
                $article->addOption('order', 'hits DESC');
            }
        }
        if(isset($options['type'])){
            $article->setType($options['type']);
        }
        return $articleMapper->search($article);
    }

    /**
     * @param $options [limit,start,iccp,categoryId,page]
     * @return array|null|\Zend\Paginator\Paginator
     */
    public function getMostViewedNews($options)
    {
        $mapper = $this->getServiceLocator()->get('News\Model\ArticleMapper');
        /* @var $mapper \News\Model\ArticleMapper */
        $news = new \News\Model\Article();
        $news->setStoreId($this->getServiceLocator()->get('Store\Service\Store')->getStoreId());
        if (isset($options['categoryId'])) {
            $news->setCategoryId($options['categoryId']);
        }
        if (isset($options['limit'])) {
            $news->addOption('limit', $options['limit']);
        }
        $options['order'] = 'hits desc';
        $news->setOptions($options);
        return $mapper->search($news);
    }

    /**
     * @param $options
     * @return array|null|\Zend\Paginator\Paginator
     */
    public function getHotNews($options)
    {
        /* @var $mapper \News\Model\ArticleMapper */
        $mapper = $this->getServiceLocator()->get('News\Model\ArticleMapper');
        $news = new \News\Model\Article();
        $news->setStoreId($this->getServiceLocator()->get('Store\Service\Store')->getStoreId());
        if (isset($options['categoryId'])) {
            $news->setCategoryId($options['categoryId']);
        }
        $options['order'] = 'id desc';
        $news->setOptions($options);
        return $mapper->search($news);
    }


    /**
     * @param $catId int
     * @return array|null|\Zend\Paginator\Paginator
     */
    public function searchNewsByCategory($catId, $options = null)
    {
        /* @var $mapper \News\Model\ArticleMapper */
        $mapper = $this->getServiceLocator()->get('News\Model\ArticleMapper');
        $news = new \News\Model\Article();
        if(isset($options['limit'])) {
            $news->addOption('limit', $options['limit']);
        }
        if(isset($options['excludedIds'])){
            $news->addOption('excludedIds', $options['excludedIds']);
        }
        $news->setStoreId($this->getServiceLocator()->get('Store\Service\Store')->getStoreId());
        $news->setCategoryId($catId);
        return $mapper->search($news);
    }

    /**
     * @param array $options
     * @return null|array<\News\Model\Article>
     */
    public function searchTin247($options)
    {
        /* @var $newsService \News\Service\News */
        $newsService = $this->getServiceLocator()->get('News\Service\News');
        return $newsService->searchTin247($options);
    }

    /**
     * @param int $id
     * @return \News\Model\Article
     */
    public function getTin247($id)
    {
        /* @var $newsService \News\Service\News */
        $newsService = $this->getServiceLocator()->get('News\Service\News');
        return $newsService->getTin247($id);
    }

    public function getTin247ByCategory($options)
    {
        /* @var $newsService \News\Service\News */
        $newsService = $this->getServiceLocator()->get('News\Service\News');
        return $newsService->getLastestNews($options);
    }
}