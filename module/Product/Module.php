<?php
/**
 * @category   	Restaurant library
* @copyright  	http://restaurant.vn
* @license    	http://restaurant.vn/license
*/

namespace Product;

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
				'Product\Model\Product'=>'Product\Model\Product',
				'Product\Model\ProductMapper'=>'Product\Model\ProductMapper',
				'Product\Model\Category'=>'Product\Model\Category',
				'Product\Model\CategoryMapper'=>'Product\Model\CategoryMapper',
                'Product\Model\WishList'=>'Product\Model\WishList',
                'Product\Model\WishListMapper'=>'Product\Model\WishListMapper'

			),
			
		);
	}
	public function getViewHelperConfig()
	{
		return array(
				'factories' => array(
						'product' => function ($hpm) {
							/* @var $hpm \Zend\View\HelperPluginManager */
							$helper = new View\Helper\Product($hpm->getServiceLocator());
							return $helper;
						},
						'category' => function ($hpm) {
							/* @var $hpm \Zend\View\HelperPluginManager */
							$helper = new View\Helper\Category($hpm->getServiceLocator());
							return $helper;
						},
	
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