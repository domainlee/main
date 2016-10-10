<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Admin\Controller\Admin' => 'Admin\Controller\AdminController',
        	'Admin\Controller\Article' => 'Admin\Controller\ArticleController',
        	'Admin\Controller\Articlec' => 'Admin\Controller\ArticlecController',
        	'Admin\Controller\Product' => 'Admin\Controller\ProductController',
        	'Admin\Controller\Productc' => 'Admin\Controller\ProductcController',
        	'Admin\Controller\Banner' => 'Admin\Controller\BannerController',
        	'Admin\Controller\Position' => 'Admin\Controller\PositionController',
        	'Admin\Controller\Store' => 'Admin\Controller\StoreController',
        	'Admin\Controller\Order' => 'Admin\Controller\OrderController',
            'Admin\Controller\Media' => 'Admin\Controller\MediaController',
            'Admin\Controller\Setup' => 'Admin\Controller\SetupController',
            'Admin\Controller\Page' => 'Admin\Controller\PageController',
        ),
    ),
    'router' => array(
        'routes' => array(
        	'admin' => array (
				'type' => 'Literal',
				'options' => array (
					'route' => '/admin',
					'defaults' => array (
						'__NAMESPACE__' => 'Admin\Controller',
						'controller' => 'Admin\Controller\Admin',
						'action' => 'index'
					)
				),
				'may_terminate' => true,
				'child_routes' => array (
					'default' => array (
						'type' => 'segment',
						'options' => array (
							'route' => '[/:controller][/:action][/:id]',
							'constraints' => array (
								'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
								'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
								'id' => '[0-9]*'
							)
						)
					),
                		
                   ),
        		),
        	),
    	),
		'service_manager' => array(
				'factories' => array(
						'translator' => 'Zend\I18n\Translator\TranslatorServiceFactory',
				),
		),
		'translator' => array(
				'locale' => 'en_US',
				'translation_file_patterns' => array(
						array(
								'type'     => 'gettext',
								'base_dir' => __DIR__ . '/../language',
								'pattern'  => '%s.mo',
						),
				),
		),
		'view_manager' => array(
            'template_path_stack' => array(
                    __DIR__ . '/../view',
            ),
            'strategies' => array(
                'ViewJsonStrategy'
            )
		),
		
);