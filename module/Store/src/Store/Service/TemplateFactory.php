<?php
/**
 * @category   	Shop99 library
 * @copyright  	http://shop99.vn
 * @license    	http://shop99.vn/license
 */

namespace Store\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class TemplateFactory implements FactoryInterface
{
	/**
	 * @author VanCK
	 * @param ServiceLocatorInterface $sl
	 * @return \Store\Service\Template
	 */
    public function createService(ServiceLocatorInterface $sl)
    {
		$templateService = new \Store\Service\Template();
		$templateService->setServiceStore($sl->get('Store\Service\Store'));
		return $templateService;
    }
}