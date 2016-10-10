<?php
/**
 * @author 		VanCK
 * @category   	Shop99 library
 * @copyright  	http://shop99.vn
 * @license    	http://shop99.vn/license
 */

namespace Store\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class StoreServiceFactory implements FactoryInterface
{

	/**
	 * @param ServiceLocatorInterface $sl
	 * @return \Store\Service\Store
	 */
    public function createService(ServiceLocatorInterface $sl)
    {
        $store = new \Store\Service\Store();
        $store->setServiceLocator($sl);
        /*@var $domain \Store\Model\Domain */
        $store->setDomain($domain = $sl->get('Store\Model\Domain'));
        /* @var $uitemplateMapper \Uitemplate\Model\UitemplateMapper */
        $uitemplateMapper = $sl->get('Uitemplate\Model\UitemplateMapper');
        $store->setUitemplate($uitemplateMapper->get($domain->getUitemplateId()));
        return $store;
    }
}
