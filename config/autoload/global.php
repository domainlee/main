<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overridding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

return array(
	'db' => array(
        'driver'         => 'Pdo',
        'driver_options' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"
        ),
    ),
	
    'locale' => array(
    		'default' => 'vi_VN',
    		'supported' => array('vi_VN', 'en_US')
    ),
    'app' => array(
    		'session.tableName' => 'sessions'
    ),
    'session' => array(
    		'name' => 'smku2o10dabo5cp',
    		'remember_me_seconds' => 86400,
    		'use_cookies'       => true,
    		'cookie_httponly'   => true,
    		'cookie_lifetime'   => 86400,
    		'gc_maxlifetime'    => 86400,
    		'save_path' => './data/session',
    ),
    'smtpOptions'  => [
        'name'              => 'no-reply',
        'host'              => 'smtp.gmail.com',
        'port'              => 587,
        'connection_class'  => 'login',
        'connection_config' => [
            'username' => 'domainlee.niit@gmail.com',
            'password' => 'domainlee1790',
            'ssl'      => 'tls',
        ],
    ],
    'service_manager' => array(
    		'factories' => array(
    				'Zend\Db\Adapter\Adapter' => function ($serviceManager) {
    					$adapterFactory = new Zend\Db\Adapter\AdapterServiceFactory();
    					$adapter = $adapterFactory->createService($serviceManager);
    					\Zend\Db\TableGateway\Feature\GlobalAdapterFeature::setStaticAdapter($adapter);
    					// $adapter->setProfiler(new \Zend\Db\Adapter\Profiler\Profiler());
    					return $adapter;
    				},
    				'Zend\Db\Sql\Sql' => function ($sm) {
    					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    					$dbSql = new Zend\Db\Sql\Sql($dbAdapter);
    					return $dbSql;
    				},
    				'cache' => function () {
    					return \Zend\Cache\StorageFactory::factory(array(
    							'adapter' => array(
    									'name' => 'filesystem',
    									'options' => array(
    											'cache_dir' => __DIR__ . '/../../data/cache',
    											'ttl' => 100,
//                                                'lifetime' => '3600'
    									),
    							),
    							'plugins' => array(
    									array(
    											'name' => 'serializer',
    											'options' => array(
    
    											)
    									)
    							)
    					));
    				},
    		),
    		'aliases' => array(
    				'dbAdapter' => 'Zend\Db\Adapter\Adapter',
    				'dbSql' => 'Zend\Db\Sql\Sql',
    		)
   ),
    'view_manager' => array(
				'display_not_found_reason' => true,
				'display_exceptions'       => true,
				'doctype'                  => 'HTML5',
				'not_found_template'       => 'error/404',
				'exception_template'       => 'error/index',
				'template_map' => array(
						'error/404'               => @PUBLIC_PATH.'/error/404.phtml',
						'error/index'             => @PUBLIC_PATH . '/error/index.phtml',
				),
		),
);
