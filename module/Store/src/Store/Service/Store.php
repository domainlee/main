<?php
/**
 * @category   	Shop99 library
 * @copyright  	http://shop99.vn
 * @license    	http://shop99.vn/license
 */

namespace Store\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class Store implements ServiceLocatorAwareInterface {

	/**
	 * @var ServiceLocatorInterface
	 */
	protected $serviceLocator;

	/**
	 * @var \Store\Model\Domain
	 */
	protected $domain;

	/**
	 * @var \Uitemplate\Model\Uitemplate
	 */
	protected $uitemplate;

	/**
	 * @return \Zend\ServiceManager\ServiceLocatorInterface
	 */
	public function getServiceLocator() {
		return $this->serviceLocator;
	}

	/**
	 * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
	 */
	public function setServiceLocator(ServiceLocatorInterface $serviceLocator) {
		$this->serviceLocator = $serviceLocator;
		return $this;
	}

	public function getStoreId() {
		return (int)$this->getDomain()->getStoreId();
	}

	/**
	 * @return \Store\Model\Domain
	 */
	public function getDomain() {
		return $this->domain;
	}

	/**
	 * @param \Store\Model\Domain $domain
	 */
	public function setDomain($domain) {
		$this->domain = $domain;
		return $this;
	}

	/**
	 * @return \Uitemplate\Model\Uitemplate
	 */
	public function getUitemplate() {
		return $this->uitemplate;
	}

	/**
	 * @return array(lat,lnt);
	 */
	public function getStoreCoordinate()
	{
		$storeId = $this->getStoreId();
		/*@var $storeMapper \Store\Model\StoreMapper */
		$storeMapper = $this->getServiceLocator()->get('Store\Model\StoreMapper');
		$store = $storeMapper->get($storeId);
		if ($store && $store->getLatitude() && $store->getLongitude())
		{
			return array('lat' => $store->getLatitude(), 'lnt' => $store->getLongitude());
		}
		return null;
	}

	/**
	 * @param \Uitemplate\Model\Uitemplate $uitemplate
	 */
	public function setUitemplate($uitemplate) {
		$this->uitemplate = $uitemplate;
		return $this;
	}

	/**
	 * @return array @config
	 */
	public function getStoreUIConfig(){
		/*@var $domainMapper \Store\ModeL\DomainMapper */
		$domainMapper = $this->getServiceLocator()->get('Store\Model\DomainMapper');
		$domain = $domainMapper->get($this->getDomain());
		$config = json_decode($domain->getUitemplateOptions());
		return $config;
	}

	/**
	 * @return array $smtpConfig
	 */
	public function getStoreSmtpOptions() {
		$sl= $this->getServiceLocator();
		$storeMailMapper = $sl->get('Store\Model\StoreEmailMapper');
		/*@var $storeMail \Store\Model\StoreEmail */
		$storeMail = $storeMailMapper->getByStoreId($this->getStoreId());
		$smtpCfgs = $sl->get('Config')['smtpOptions'];
		if($storeMail && $storeMail->getEmail()) {
			$smtpCfgs['connection_config']['username'] = $storeMail->getEmail();
			$smtpCfgs['connection_config']['password'] = $storeMail->getPassword();
		}
		return $smtpCfgs;
	}

    /**
     * @param null $options
     * @return null
     */
    public function getStoreSetting($options = null)
    {
        $sl = $this->getServiceLocator();
        /** @var \Store\Model\StoreSettingMapper $storeSettingMapper */
        $storeSettingMapper = $sl->get('Store\Model\StoreSettingMapper');
        $storeSetting = new \Store\Model\StoreSetting();
        $storeSetting->setStoreId($this->getStoreId());
        if (isset($options['paginator'])) {
            $storeSetting->setType($storeSetting::TYPE_PAGINATOR);
            if ($storeSettingMapper->get($storeSetting)) {
                if (is_null($storeSetting->getContent())) {
                    return null;
                }
                $setting = json_decode($storeSetting->getContent());
                if(isset($setting->$options['paginator']) && isset($setting->$options['paginator']->itemCountPerPage)){
                    return $setting->$options['paginator']->itemCountPerPage;
                }
            }
        }
        return null;
    }

}