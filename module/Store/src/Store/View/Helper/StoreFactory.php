<?php
/**
 * @category   	Shop99 library
 * @copyright  	http://shop99.vn
 * @license    	http://shop99.vn/license
 */

namespace Store\View\Helper;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class StoreFactory implements FactoryInterface
{
	/**
	 * @author VanCK
	 * @param ServiceLocatorInterface $hpm
	 * @return \Store\View\Helper\Store
	 */
    public function createService(ServiceLocatorInterface $hpm)
    {
        /* @var $hpm \Zend\View\HelperPluginManager */
		$helper = new \Store\View\Helper\Store();
		$helper->setServiceLocator($hpm->getServiceLocator());
		return $helper;
    }
}