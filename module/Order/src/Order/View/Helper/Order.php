<?php
/**
 * Article\View\Helper\ProductCategory
 *
 * @category   	Shop99 library
 * @copyright  	http://shop99.vn
 * @license    	http://shop99.vn/license
 */

namespace Article\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class Product
 * @package Article\View\Helper
 */
class Article extends AbstractHelper
{
	/**
	 * @var ServiceLocatorInterface
	 */
	protected $serviceLocator;

	/**
	 * @var \Store\Service\Store
	 */
	protected $serviceStore;

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
     * @param \Store\Service\Store $serviceStore
     */
    public function setServiceStore($serviceStore)
    {
        $this->serviceStore = $serviceStore;
    }

    /**
     * @return \Store\Service\Store
     */
    public function getServiceStore()
    {
        return $this->serviceStore;
    }

	/**
	 * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
	 * @param \Store\Service\Store $serviceStore
	 */
	public function __construct($serviceLocator) {
		$this->setServiceLocator($serviceLocator);
	}
	public function getAllArticles(){
//		$model = new \Article\Model\Article();
		$mapper = $this->getServiceLocator()->get('\Article\Model\ArticleMapper');
		return $mapper->getAllArticle();
	}
	/**
	 * get most viewed products
	 * @param array $options | "limit"
	 */
	

}










