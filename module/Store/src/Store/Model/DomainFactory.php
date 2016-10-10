<?php
/**
 * @category   	Shop99 library
 * @copyright  	http://shop99.vn
 * @license    	http://shop99.vn/license
 */
namespace Store\Model;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class DomainFactory implements FactoryInterface
{

    /**
     * create Store Domain
     *
     * @author Domainlee
     * @param ServiceLocatorInterface $sl
     * @return \Store\View\Helper\Template
     */
    public function createService(ServiceLocatorInterface $sl)
    {
        $domain = new \Store\Model\Domain();
        $domain->setName(str_replace('www.', '', strtolower($_SERVER['HTTP_HOST'])));

        /* @var $domainMapper \Store\Model\DomainMapper */
        $domainMapper = $sl->get('Store\Model\DomainMapper');
        $domainMapper->get($domain);
        return $domain;
    }
}