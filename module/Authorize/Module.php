<?php
/**
 * @category   	Restaurant library
 * @copyright  	http://restaurant.vn
 * @license    	http://restaurant.vn/license
 */

namespace Authorize;
use Zend\Mvc\MvcEvent;

class Module
{

	public function getConfig() {
		return include __DIR__ . '/config/module.config.php';
	}

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

    public function getServiceConfig()
    {
        return array(
			'invokables' => array(
				'Authorize\Permission\Acl' => 'Authorize\Permission\Acl',
			),
        	'factories' => array(
        		'Authorize\Service\Authorize' => function($sm) {
        			/* @var $sm \Zend\ServiceManager\ServiceManager */
        			$authorizeService = new Service\Authorize;
	        		$authorizeService->setAcl($sm->get('Authorize\Permission\Acl'));
	        		$authorizeService->setUserService($sm->get('User\Service\User'));
	        		return $authorizeService;
        		},
        		'Authorize\Guard\Controller' => function($sm) {
        			/* @var $sm \Zend\ServiceManager\ServiceManager */
        			$guardController = new Guard\Controller;
        			/* @var $authorizeService \Authorize\Service\Authorize */
        			$authorizeService = $sm->get('Authorize\Service\Authorize');
        			$guardController->setAuthorizeService($authorizeService);
        			return $guardController;
        		},
		        'Authorize\View\UnauthorizedStrategy' => function($sm) {
		            return new \Authorize\View\Strategy\UnauthorizedStrategy();
		        },
        	)
        );
    }

    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                'IsAllowed' => function ($sm) {
                    $viewHelper = new View\Helper\IsAllowed();
                    $viewHelper->setServiceAuthorize($sm->getServiceLocator()->get('Authorize\Service\Authorize'));
                    return $viewHelper;
                },
            ),
        );
    }

	public function onBootstrap(MvcEvent $e)
	{
		$sm = $e->getApplication()->getServiceManager();

		/* @var $eventManager \Zend\EventManager\EventManager */
		$eventManager = $e->getApplication()->getEventManager();
		$eventManager->attach($sm->get('Authorize\Guard\Controller'));
		$eventManager->attach($sm->get('Authorize\View\UnauthorizedStrategy'));

		/* @var $acl \Authorize\Permission\Acl */
		$acl = $sm->get('Authorize\Permission\Acl');
		/* @var $serviceUser \User\Service\User */
		$serviceUser = $sm->get('User\Service\User');

 		\Zend\View\Helper\Navigation::setDefaultAcl($acl);
 		\Zend\View\Helper\Navigation::setDefaultRole($serviceUser->getRoleName());
	}
}