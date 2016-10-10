<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Store\Controller\Index' => 'Store\Controller\IndexController',
        	'Store\Controller\Item' => 'Store\Controller\ItemController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'product' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/store[/:controller][/:action]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                    	'__NAMESPACE__' => 'Store\Controller',
                        'controller' => 'Store\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'store' => __DIR__ . '/../view',
        ),
    ),
);