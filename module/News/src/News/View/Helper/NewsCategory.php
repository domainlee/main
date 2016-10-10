<?php
/**
 * News\View\Helper\News
 *
 * @category   	Shop99 library
 * @copyright  	http://shop99.vn
 * @license    	http://shop99.vn/license
 */

namespace News\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class NewsCategory
 * @package News\View\Helper
 */
class NewsCategory extends AbstractHelper
{
    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * @var array
     */
    protected $categories;

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
        if(!$this->serviceStore) {
            $this->serviceStore = $this->getServiceLocator()->get('Store\Service\Store');
        }
        return $this->serviceStore;
    }

    /**
     * @param array $categories
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;
    }

    /**
     * @return array
     */
    public function getCategories($options = null)
    {
        $category = new \News\Model\Category();
        $category->setStatus($category::STATUS_ACTIVE);
        $category->setStoreId($this->getServiceStore()->getStoreId());
        if(isset($options['domainId']) && $options['domainId']){
            $category->setDomainId($options['domainId']);
        }
        if(isset($options['excludedIds']) && $options['excludedIds']){
            $category->setOptions(['excludedIds' => $options['excludedIds']]);
        }
        /* @var $categoryMapper \News\Model\CategoryMapper */
        $categoryMapper = $this->getServiceLocator()->get('News\Model\CategoryMapper');
        if (count($cates = $categoryMapper->fetchTree($category))) {
            $this->setCategories($cates);
        }

        return $this->categories;
    }

    /**
     * @param $serviceLocator \Zend\ServiceManager\ServiceLocatorInterface
     */
    public function __construct($serviceLocator)
    {
        $this->setServiceLocator($serviceLocator);
    }

    public function featchTreeCategory($categoryId = null)
    {
        $category = new \News\Model\Category();
        $category->setStatus($category::STATUS_ACTIVE);
        $category->setStoreId($this->getServiceStore()->getStoreId());
        $category->setId($categoryId);

        /* @var $categoryMapper \News\Model\CategoryMapper */
        $categoryMapper = $this->getServiceLocator()->get('News\Model\CategoryMapper');
        if (count($categories = $categoryMapper->fetchTree($category))) {
            return $categories;
        }
        return null;
    }

    /**
     * @param $id
     * @return \News\Model\Category|null
     */
    public function getCategoryById($id,$options = null)
    {
        $mapper = $this->getServiceLocator()->get('News\Model\CategoryMapper');
        /* @var $mapper \News\Model\CategoryMapper */
        $category = new \News\Model\Category();
        $category->setId($id);

        /* @var  \Store\Service\Store */
        $storeId = $this->getServiceLocator()->get('Store\Service\Store')->getStoreId();
        if(isset($options['storeId']) && $options['storeId']){
            $category->setStoreId($options['storeId']);
        }else{
            $category->setStoreId($storeId);
        }

        return $mapper->get($category);
    }

    /**
     * @param null $root
     * @param null|array $categories
     * @return null|\Zend\Navigation\Page\Mvc
     */
    public function buildNav($root = null, $categories = null)
    {
        if (!$root) {
            $root = new \Zend\Navigation\Page\Mvc();
        }
        if (!$categories) {
            $categories = $this->getCategories();
        }
        if (count($categories)) {
            foreach ($categories as $category) {
                /* @var $category \News\Model\Category */
                $page = new \Zend\Navigation\Page\Uri();
                $page->setLabel($category->getName());
                $page->setUri($category->getViewLink());
                $page->setClass($category->getId());

                $root->addPage($page);

                if ($category->getChilds()) {
                    $page->setClass("category hasChild");
                    $this->buildNav($page, $category->getChilds());
                }
            }
        }
        return $root;
    }

    /**
     * build breadcrumb
     * @param \News\Model\Category $category
     * @return array
     */
    public function buildBreadcrumbs($category)
    {
        /* @var $category \Product\Model\BaseCategory */
        $categories = array_reverse($category->flattenParents());
        $root = array();
        if (count($categories)) {
            foreach ($categories as $category) {
                $root[] = array(
                    'label' => $category->getName(),
                    'uri'   => $category->getViewLink(),
                    'class' => (String)$category->getId()
                );
            }
        }
        return $root;
    }

    /**
     * @param \News\Model\Category $category
     * @return array|null
     */
    public function getCategory(\News\Model\Category $category)
    {
        /* @var $mapper \News\Model\CategoryMapper */
        $mapper = $this->getServiceLocator()->get('News\Model\CategoryMapper');
        if (!$category->getStoreId()) {
            $category->setStoreId($this->getServiceStore()->getStoreId());
        }
        if ($category->getStatus()) {
            $category->setStatus(1);
        }
        return $mapper->getCategory($category);
    }

    public function getCategoryId($id)
    {
        $mapper = $this->getServiceLocator()->get('News\Model\CategoryMapper');
        /* @var $mapper \News\Model\CategoryMapper */
        $category = new \News\Model\Category();
        $category->setStoreId($this->getServiceStore()->getStoreId());
        $category->setId($id);
        return $mapper->getChildIds($category);
    }

    public function getCategoryIds($options)
    {
        $mapper = $this->getServiceLocator()->get('News\Model\CategoryMapper');
        $category = new \News\Model\Category();
        $category->setStoreId($this->getServiceStore()->getStoreId());
        return $mapper->getId($category, $options);
    }
}