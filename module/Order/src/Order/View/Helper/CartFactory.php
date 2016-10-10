<?php
/**
 * @category  Shop99 library
 * @copyright http://shop99.vn
 * @license   http://shop99.vn/license
 */
namespace Order\View\Helper;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CartFactory implements FactoryInterface
{
    /**
     * @author VanCK
     * @param ServiceLocatorInterface $hpm
     * @return \Order\View\Helper\Cart
     */
    public function createService(ServiceLocatorInterface $hpm)
    {
        /* @var $hpm \Zend\View\HelperPluginManager */
        $helper = new Cart($hpm->getServiceLocator());
        return $helper;
    }
}