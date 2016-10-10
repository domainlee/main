<?php
/**
 * @category   	Shop99 library
 * @copyright  	http://shop99.vn
 * @license    	http://shop99.vn/license
 */

namespace Store\View\Helper;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class TemplateFactory implements FactoryInterface
{
	/**
	 * @author VanCK
	 * @param ServiceLocatorInterface $hpm
	 * @return \Store\View\Helper\Template
	 */
    public function createService(ServiceLocatorInterface $hpm)
    {
        /* @var $hpm \Zend\View\HelperPluginManager */
		$helper = new Template();
		$helper->setUitemplate($hpm->getServiceLocator()->get('Store\Model\Uitemplate'));
		return $helper;
    }
}
