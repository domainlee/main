<?php
/**
 * @author    Mienlv
 * @category  Shop99 library
 * @copyright http://shop99.vn
 * @license   http://shop99.vn/license
 */

namespace Order\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CartServiceFactory implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $sl
     * @return mixed|Cart
     */
    public function createService(ServiceLocatorInterface $sl)
    {
        $cart = new Cart($sl);
        return $cart;
    }
}
