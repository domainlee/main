<?php
/**
 * @category       Shop99 library
 * @copyright      http://shop99.vn
 * @license        http://shop99.vn/license
 */

namespace Store;

use Zend\Mvc\MvcEvent;

class Module
{

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig()
    {
        return array(
            'invokables' => array(
                'Store\Model\DomainFactory'         => 'Store\Model\DomainFactory',
                'Store\Model\DomainMapper'          => 'Store\Model\DomainMapper',
                'Store\Model\PaymentAccount'        => 'Store\Model\PaymentAccount',
                'Store\Model\PaymentAccountMapper'  => 'Store\Model\PaymentAccountMapper',
                'Store\Model\BannerMapper'          => 'Store\Model\BannerMapper',
                'Store\Model\StoreSetting'          => 'Store\Model\StoreSetting',
                'Store\Model\StoreSettingMapper'    => 'Store\Model\StoreSettingMapper',
                'Store\Model\StoreEmail'            => 'Store\Model\StoreEmail',
                'Store\Model\StoreEmailMapper'      => 'Store\Model\StoreEmailMapper',
                'Store\Model\Store'                 => 'Store\Model\Store',
                'Store\Model\StoreMapper'           => 'Store\Model\StoreMapper',
                'Store\Model\Cache'                 => 'Store\Model\Cache',
                'Store\Model\CacheMapper'           => 'Store\Model\CacheMapper',
                'Store\Model\Depot'                 => 'Store\Model\Depot',
                'Store\Model\DepotMapper'           => 'Store\Model\DepotMapper',
                'Store\View\Helper\Store'           => 'Store\View\Helper\Store',
                'Store\View\Helper\StoreFactory'    => 'Store\View\Helper\StoreFactory',
                'Store\View\Helper\TemplateFactory' => 'Store\View\Helper\TemplateFactory',
                'Store\Service\StoreFactory'        => 'Store\Service\StoreFactory',
            ),
            'factories'  => array(
                'Store\Model\Domain'     => 'Store\Model\DomainFactory',
                'Store\Service\Store'    => 'Store\Service\StoreFactory',
                'Store\Service\Template' => 'Store\Service\TemplateFactory',
            ),
        );
    }

    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                'template' => 'Store\View\Helper\TemplateFactory',
                'store'    => 'Store\View\Helper\StoreFactory',
            ),
        );
    }

    public function onBootstrap(MvcEvent $e)
    {
        $sm = $e->getApplication()->getServiceManager();
        /* @var $eventManager \Zend\EventManager\EventManager */
        $eventManager = $e->getApplication()->getEventManager();
        $eventManager->attach($sm->get('Store\Service\Template'));
    }
}