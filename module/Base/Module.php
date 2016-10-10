<?php

namespace Base;

// use Locale;
use Base\View\Helper\UriParams;

use Zend\Mvc\MvcEvent;
use Zend\Mvc\ModuleRouteListener;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Db\TableGateway\TableGateway;
use Zend\Session\Config\SessionConfig;
use Zend\Session\SessionManager;
use Zend\Session\Container;
use Zend\Session\SaveHandler\DbTableGateway;
use Zend\Session\SaveHandler\DbTableGatewayOptions;
use Zend\Log\Logger;
use Zend\Log\Writer\FirePhp as FirePhpWriter;

require_once 'vendor/FirePHPCore/FirePHP.class.php';

class Module implements AutoloaderProviderInterface
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
            'factories' => array(
                'log' => function($sm) {
                    $log = new Logger();
                    $writer = new FirePhpWriter();
                    $log->addWriter($writer);
                    return $log;
                },
            ),
        );
    }

    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                'uriParams' => function($vhm) {
               		\Zend\Paginator\Paginator::setDefaultScrollingStyle('Sliding');
               		\Zend\View\Helper\PaginationControl::setDefaultViewPartial("layout/paginatorItem");
                    return new View\Helper\UriParams($vhm->getServiceLocator()->get('Request'));
                },
            ),
        );
    }

	public function onBootstrap(MvcEvent $e)
    {
    	/* @var $app \Zend\Mvc\Application */
    	$app = $e->getApplication();

    	/* @var $sm \Zend\ServiceManager\ServiceManager */
    	$sm = $app->getServiceManager();
    	$config = $sm->get('Config');

    	// bootstrap session
    	$tableGateway = new TableGateway($config['app']['session.tableName'], $sm->get('dbAdapter'));
    	$saveHandler = new DbTableGateway($tableGateway, new DbTableGatewayOptions());
    	$sessionConfig = new SessionConfig();
//    	$sessionConfig->setOptions($config['session']);
    	$sessionManager = new SessionManager($sessionConfig);
    	$sessionManager->setSaveHandler($saveHandler);
    	$sessionManager->start();
    	Container::setDefaultManager($sessionManager);

    	// translate
   		$sm->get('translator');
    	$eventManager        = $e->getApplication()->getEventManager();
    	$moduleRouteListener = new ModuleRouteListener();
    	$moduleRouteListener->attach($eventManager);

    	// bootstrap locale
//     	$headers = $app->getRequest()->getHeaders();
//     	Locale::setDefault($config['locale']['default']);
//     	if($headers->has('Accept-Language')) {
//     		$locales = $headers->get('Accept-Language')->getPrioritized();
//     		// Loop through all locales, highest priority first
//     		foreach($locales as $locale) {
//     			if(!!($match = Locale::lookup($config['locale']['supported'], $locale->typeString))) {
//     				// The locale is one of our supported list
//     				Locale::setDefault($match);
//     				break;
//     			}
//     		}
//     		if(!$match) {
//     			// Nothing from the supported list is a match
//     			Locale::setDefault($config['locale']['default']);
//     		}
//     	}
    }
}
