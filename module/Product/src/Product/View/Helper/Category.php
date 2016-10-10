<?php
namespace Product\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Category extends AbstractHelper{
	/**
	 * @var ServiceLocatorInterface
	 */
	protected $serviceLocator;

	/**
	 * @var \Store\Service\Store
	 */
	protected $serviceStore;

    protected $categories;
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
	 * @return array
	 */
	public function getCategories() {
		return $this->categories;
	}
	
	/**
	 * @param array $categories
	 * @return $this
	 */
	public function setCategories($categories) {
		$this->categories = $categories;
		return $this;
	}

    public function buildBreadcrumbs($category)
    {
        /* @var $category \Product\Model\Category */
        $categories = array_reverse($category->flattenParents());
        $root = [];
        if (count($categories)) {
            foreach ($categories as $category) {
                $root[] = [
                    'label' => str_replace('&amp;', '&', $category->getName()),
                    'uri'   => $category->getViewLink(),
                    'class' => (String)$category->getId()
                ];
            }
        }
        return $root;
    }

    public function getId($c){

        /* @var $category \Product\Model\Category */
        $category = new \Product\Model\Category();
        $category->setId($c->getParentId());
        $storeId = $this->getServiceLocator()->get('Store\Service\Store')->getStoreId();
        $category->setStoreId($storeId);
        $categoryMapper = $this->getServiceLocator()->get('Product\Model\CategoryMapper');
        return $categoryMapper->get($category);
    }
	/**
	 * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
	 */

	public function __construct($serviceLocator) {
		$this->setServiceLocator($serviceLocator);
		$category = new \Product\Model\Category();
		$category->setStoreId($this->getServiceStore()->getStoreId());
		$categoryMapper = $this->getServiceLocator()->get('Product\Model\CategoryMapper');
		if (is_array($cates = $categoryMapper->fetchTree($category)) && count($cates)) {
			$this->setCategories($cates);
		}
	}


}














