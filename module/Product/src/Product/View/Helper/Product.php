<?php
namespace Product\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Product extends AbstractHelper{
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
		if(!$this->serviceStore) {
			$this->serviceStore = $this->getServiceLocator()->get('Store\Service\Store');
		}
		return $this->serviceStore;
	}
	public function getIds()
	{
		if(!$this->serviceStore) {
			$this->serviceStore = $this->getServiceLocator()->get('Product\Model\Product');
		}
		return $this->serviceStore;
	}
	
	/**
	 * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
	 */
	public function __construct($serviceLocator) {
		$this->setServiceLocator($serviceLocator);
	}
	public function getMostView($options = null){
		$product = new \Product\Model\Product();
		$product->setStoreId($this->getServiceStore()->getStoreId());
		$product->setOptions($options);
		$mapper = $this->getServiceLocator()->get('Product\Model\ProductMapper');
		return $mapper->getMostView($product);
	}
	public function getProchild($options=null){
		$product = new \Product\Model\Product();
		$product->setStoreId($this->getServiceStore()->getStoreId());
		$product->setOptions($options);
		$mapper = $this->getServiceLocator()->get('Product\Model\ProductMapper');
		return $mapper->getProchild($product);
	}
	public function getSize($options=null){
		$product = new \Product\Model\Product();
		$product->setStoreId($this->getServiceStore()->getStoreId());
		$product->setOptions($options);
		$mapper = $this->getServiceLocator()->get('Product\Model\ProductMapper');
		return $mapper->getSize($product);
	}

    public function search($option = null)
    {
//        print_r($option);die;

        $sl = $this->getServiceLocator();

        $product = new \Product\Model\Product();
        $product->setStoreId($sl->get('Store\Service\Store')->getStoreId());
        $product->setStatus(\Product\Model\Product::STATUS_ACTIVE);
        if(isset($option['sale'])){
            $product->addOption('sale', true);
        }
        if(isset($option['limit'])){
            $product->addOption('limit', $option['limit']);
        }
        if(isset($option['categoryId'])){
            $product->setCategoryId($option['categoryId']);
        }
        $mapper = $this->getServiceLocator()->get('Product\Model\ProductMapper');
        $a = $mapper->fetchAll($product);
        if(count($a)){
            return $a;
        }else{
            return false;
        }
    }

    /**
     * @param $param    | string
     * @param $value    | string
     * @param int $mode |1= add new value|2= repalce value
     * @return string
     */
    public function addFilter($param, $value, $mode = 1)
    {
        /* @var $request \Zend\Http\Request */
        $request = $this->getServiceLocator()->get('Request');

        $params = $queryArr = $request->getUri()->getQueryAsArray();
        if (isset($params[$param]) && $params[$param] && $mode == 1) {
            // mode 1: append new value
            $values = explode(',', $params[$param]);
            if (!in_array($value, $values)) {
                $values[] = $value;
                $params[$param] = implode(',', $values);
            }
        } else if (count_chars($value)) {
            $params[$param] = $value;
        }

        foreach ($params as $pKey => $pVal) {
            if($pKey == 'q') {
                $pVal = strip_tags($pVal);
            }
            $params[$pKey] = $pKey . '=' . $pVal;
        }
        return $request->getUri()->getPath() . '?' . implode('&', $params);
    }

    /**
     * @param $param | string
     * @param null $value
     * @return string
     */
    public function removeFilter($param, $value = null)
    {
        /* @var $request \Zend\Http\Request */
        $request = $this->getServiceLocator()->get('Request');
        $params = $queryArr = $request->getUri()->getQueryAsArray();
        if (isset($params[$param]) && $params[$param]) {
            $values = explode(',', $params[$param]);
            if (isset($value)) {
                if (in_array($value, $values)) {
                    $params[$param] = implode(',', array_diff($values, [$value]));
                }
            } else {
                unset($params[$param]);
            }
        } else if ($value) {
            $params[$param] = $value;
        }

        foreach ($params as $pKey => $pVal) {
            if ($pVal) {
                if($pKey == 'q') {
                    $pVal = strip_tags($pVal);
                }
                $params[$pKey] = $pKey . '=' . $pVal;
            } else {
                unset($params[$pKey]);
            }
        }
        return $request->getUri()->getPath() . '?' . implode('&', $params);
    }
}














