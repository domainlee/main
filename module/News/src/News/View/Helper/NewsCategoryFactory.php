<?php
/**
 * @category   	Shop99 library
 * @copyright  	http://shop99.vn
 * @license    	http://shop99.vn/license
 */
namespace News\View\Helper;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class NewsCategoryFactory implements FactoryInterface
{
    /**
     * @author Mienlv
     * @param ServiceLocatorInterface $hpm
     * @return \News\View\Helper\NewsCategory
     */
    public function createService(ServiceLocatorInterface $hpm)
    {
        /* @var $hpm \Zend\View\HelperPluginManager */
        $helper = new NewsCategory($hpm->getServiceLocator());
        return $helper;
    }
}