<?php
/**
 * @category   	Shop99 library
 * @copyright  	http://shop99.vn
 * @license    	http://shop99.vn/license
 */
namespace Home\View\Helper;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class HomeFactory implements FactoryInterface
{
    /**
     * @author Mienlv
     * @param ServiceLocatorInterface $hpm
     * @return \News\View\Helper\News
     */
    public function createService(ServiceLocatorInterface $hpm)
    {
        /* @var $hpm \Zend\View\HelperPluginManager */
        $helper = new Home($hpm->getServiceLocator());
        return $helper;
    }
}