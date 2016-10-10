 <?php

// namespace Store\View\Helper;

// use Zend\View\Helper\AbstractHelper;

// class Store extends AbstractHelper
// {
// 	/**
// 	 * @var ServiceLocatorInterface
// 	 */
// 	protected $serviceLocator;

// 	/**
// 	 * @var \Store\Service\Store
// 	 */
// 	protected $serviceStore;

// 	/**
// 	 * @return the $serviceLocator
// 	 */
// 	public function getServiceLocator() {
// 		return $this->serviceLocator;
// 	}

// 	/**
// 	 * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
// 	 */
// 	public function setServiceLocator($serviceLocator) {
// 		$this->serviceLocator = $serviceLocator;
// 		return $this;
// 	}

// 	/**
// 	 * @return \Store\Service\Store
// 	 */
// 	public function getServiceStore() {
// 	    if(!$this->serviceStore) {
// 	        $this->serviceStore = $this->getServiceLocator()->get('Store\Service\Store');
// 	    }
// 		return $this->serviceStore;
// 	}

// 	/**
// 	 * @param \Store\Service\Store $serviceStore
// 	 */
// 	public function setServiceStore($serviceStore) {
// 		$this->serviceStore = $serviceStore;
// 		return $this;
// 	}
// 	public function getStoreName(){
// 		$model = new \Admin\Model\Store();
// 		$mapper = $this->getServiceLocator()->get('Admin\Model\StoreMapper');
// 		return $mapper->fetchAll($model);
// 	}
// }