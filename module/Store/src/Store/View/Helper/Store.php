<?php

namespace Store\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Store extends AbstractHelper
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
	 * @return the $serviceLocator
	 */
	public function getServiceLocator() {
		return $this->serviceLocator;
	}

	/**
	 * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
	 */
	public function setServiceLocator($serviceLocator) {
		$this->serviceLocator = $serviceLocator;
		return $this;
	}

	/**
	 * @return \Store\Service\Store
	 */
	public function getServiceStore() {
	    if(!$this->serviceStore) {
	        $this->serviceStore = $this->getServiceLocator()->get('Store\Service\Store');
	    }
		return $this->serviceStore;
	}

	/**
	 * @param \Store\Service\Store $serviceStore
	 */
	public function setServiceStore($serviceStore) {
		$this->serviceStore = $serviceStore;
		return $this;
	}

	/**
	 * get all banners by position code
	 *
	 * @author VanCK
	 * @param string $positionCode
	 * @return array
	 */
	public function getBannerByPositionCode($positionCode,$options = null) {
		$banner = new \Store\Model\Banner();
		$banner->setPositionCode($positionCode);
		$banner->setStoreId($this->getServiceStore()->getStoreId());
		if(!isset($options['limit'])){
			$options['limit'] = 1;
		}
		/* @var $bannerMapper \Store\Model\BannerMapper */
		$bannerMapper = $this->getServiceLocator()->get('Store\Model\BannerMapper');
		return $bannerMapper->getByPositionCode($banner,$options);
	}


	/**
	 * @author VanCK
	 * @return \Store\Model\Domain
	 */
	public function getDomain()
	{
		/* @var $domain \Store\Model\Domain */
		$domain = $this->getServiceLocator()->get('Store\Model\Domain');
		return $domain;
	}

	/**
	 * @author HungNVB
	 * @return array
	 */
	public function getDomainInRoller()
	{
		/* @var $domainMapper \Store\Model\DomainMapper */
		$domainMapper = $this->getServiceLocator()->get('Store\Model\DomainMapper');
		return $domainMapper->fetchInRoller();
	}

	/**
	 * @param string $options
	 * @return \Store\Model\DepotStore|null
	 */
    public function getDepotStore($options = null)
    {
        $depotStore = new \Store\Model\DepotStore();
//        if (isset($options['id'])) {
//            $depotStore->setId($options['id']);
//        }
//        if (isset($options['depotId'])) {
//            $depotStore->setDepotId($options['depotId']);
//        }
//        if (isset($options['storeId'])) {
//            $depotStore->setStoreId($options['storeId']);
//        } else {
            $depotStore->setStoreId($this->getServiceStore()->getStoreId());
//        }
//        if (isset($options['cityId'])) {
//            $depotStore->setCityId($options['cityId']);
//        }
        return $depotStore;
//        print_r($depotStore);die;
        /* @var $depotMapper \Store\Model\DepotMapper */
//        $depotMapper = $this->getServiceLocator()->get('Store\Model\DepotMapper');
//        return $depotMapper->get($depotStore);
    }
}