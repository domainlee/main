<?php
/**
 * @category   	Shop99 library
 * @copyright  	http://shop99.vn
 * @license    	http://shop99.vn/license
 */
namespace News\View\Helper;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class NewsFactory implements FactoryInterface
{
    /**
     * @author VanCK
     * @param ServiceLocatorInterface $hpm
     * @return \News\View\Helper\News
     */
    public function createService(ServiceLocatorInterface $hpm)
    {
        /* @var $hpm \Zend\View\HelperPluginManager */
        $helper = new News($hpm->getServiceLocator());
        return $helper;
    }
}