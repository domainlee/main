<?php
/**
 * @category   	Shop99 library
 * @copyright  	http://shop99.vn
 * @license    	http://shop99.vn/license
 */

namespace User;

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

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig()
    {
        return array(
			'invokables' => array(
				'User\Model\User' => 'User\Model\User',
				'User\Model\UserMapper' => 'User\Model\UserMapper',
				'User\Form\Signin' => 'User\Form\Signin',
				'User\Form\SigninFilter' => 'User\Form\SigninFilter',
				'User\Form\Signup' => 'User\Form\Signup',
				'User\Form\SignupFilter' => 'User\Form\SignupFilter',
				'User\Form\ChangePassword' => 'User\Form\ChangePassword',
				'User\Form\ChangePasswordFilter' => 'User\Form\ChangePasswordFilter',
				'User\Form\GetActiveCode' => 'User\Form\GetActiveCode',
				'User\Form\GetActiveCodeFilter' => 'User\Form\GetActiveCodeFilter',
				'User\Service\GoogleLogin' => 'User\Service\GoogleLogin',
				'User\Service\FacebookLogin' => 'User\Service\FacebookLogin',
				'User\Service\VGLogin' => 'User\Service\VGLogin',
				'User\Service\RestRequest' => 'User\Service\RestRequest',
				'User\Form\UserSearch' => 'User\Form\UserSearch',
			),
            'factories' => array(
            	'User\Service\User' => function($sm) {
            		/* @var $sm \Zend\ServiceManager\ServiceManager */
        			$service = new \User\Service\User();
        			$service->setAuthService($sm->get('User\Auth\Service'));
        			return $service;
            	},
                'User\Auth\Service' => function ($sm) {
                	/* @var $sm \Zend\ServiceManager\ServiceManager */
                    return new \Zend\Authentication\AuthenticationService(
                        new \Zend\Authentication\Storage\Session(),
                        new \Zend\Authentication\Adapter\DbTable($sm->get('Zend\Db\Adapter\Adapter'))
                    );
                },
            ),
        );
    }

    public function getControllerPluginConfig()
    {
        return array(
            'factories' => array(
                'User' => function ($pluginManager) {
                	/* @var $pluginManager \Zend\Mvc\Controller\PluginManager */
                    $userPlugin = new Controller\Plugin\User();
                    $userPlugin->setServiceUser($pluginManager->getServiceLocator()->get('User\Service\User'));
                    return $userPlugin;
                }
            ),
        );
    }
    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                'User' => function ($sm) {
                    $viewHelper = new View\Helper\User();
                    $viewHelper->setServiceUser($sm->getServiceLocator()->get('User\Service\User'));
                    return $viewHelper;
                },
            ),

        );
    }
}