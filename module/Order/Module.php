<?php
/**
 * @category   	Restaurant library
* @copyright  	http://restaurant.vn
* @license    	http://restaurant.vn/license
*/

namespace Order;

use Zend\Json\Server\Smd\Service;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
class Module {

	public function onBootstrap(MvcEvent $e)
	{
		$e->getApplication()->getServiceManager()->get('translator');
		$eventManager        = $e->getApplication()->getEventManager();
		$moduleRouteListener = new ModuleRouteListener();
		$moduleRouteListener->attach($eventManager);

		/* $em = $e->getApplication()->getEventManager();
		$em->attach(MvcEvent::EVENT_DISPATCH, array($this, 'selectLayoutBasedOnRoute')); */
	}
	public function getConfig() {
		return include __DIR__ . '/config/module.config.php';
	}

	public function getAutoloaderConfig() {
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
	public function getServiceConfig()
	{
		return array(
			'invokables' => array(
                'Order\Model\Category' => 'Order\Model\Category',
                'Order\Model\CategoryMapper' => 'Order\Model\CategoryMapper',
                'Order\Model\Order' => 'Order\Model\Order',
                'Order\Model\OrderMapper' => 'Order\Model\OrderMapper',

                'Order\Model\Product' => 'Order\Model\Product',
                'Order\Model\ProductMapper' => 'Order\Model\ProductMapper',

                'Order\Service\CartServiceFactory' => 'Order\Service\CartServiceFactory',
//                'Order\Service\Cart' => 'Order\Service\Cart',
			),
            'factories' => array(
                'Order\Service\Cart'  => 'Order\Service\CartServiceFactory',
            ),
		);
	}
	public function getViewHelperConfig()
	{
		return array(
            'factories' => array(
                'order' => function ($hpm) {
                    /* @var $hpm \Zend\View\HelperPluginManager */
                    $helper = new View\Helper\Order($hpm->getServiceLocator());
                    return $helper;
                },
                'cart'  => function ($hpm){
                    $helper = new View\Helper\Cart($hpm->getServiceLocator());
                    return $helper;
                }
            ),
		);
	}
	/**
	 *
	 * @param MvcEvent $e
	 * @return void
	 */
	public function selectLayoutBasedOnRoute(MvcEvent $e)
	{
		
	}
	

}