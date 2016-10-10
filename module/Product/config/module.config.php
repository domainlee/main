<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Product\Controller\Product' => 'Product\Controller\ProductController',
        	'Product\Controller\Wishlist' => 'Product\Controller\WishlistController',
        ),
    ),
    'router' => array(
        'routes' => array(
        	'product' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '[/:locale]/product',
                    'constraints' => [
                        'locale' => '[a-z]{2}-[a-z]{2}'
                    ],
                    'defaults' => array(
                    	'__NAMESPACE__' => 'Product\Controller',
                        'controller' => 'Product\Controller\Product',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
		            'default' => array(
		                'type'    => 'segment',
		                'options' => array(
		                    'route'    => '[/:action]',
		                    'constraints' => array(
		                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
		                    ),
		                    'defaults' => array(
		                    	'__NAMESPACE__' => 'Product\Controller',
		                        'controller' => 'Product\Controller\Product',
		                        'action'     => 'index',
		                    ),
		                ),
		            ),
//                    'category' => array(
//                        'type'    => 'segment',
//                        'options' => array(
//                            'route'       => '/c[:id][-:name]',
//                            'constraints' => array(
//                                'id' => '[0-9]+'
//                            ),
//                            'defaults'    => array(
//                                '__NAMESPACE__' => 'Product\Controller',
//                                'controller'    => 'Product\Controller\Product',
//                                'action'        => 'category',
//                            ),
//                        ),
//                    ),
				)
        	),
            'category' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'       => '[/:locale]/c[:id][/:name]',
                    'constraints' => array(
                        'id' => '[0-9]+'
                    ),
                    'defaults'    => array(
                        '__NAMESPACE__' => 'Product\Controller',
                        'controller'    => 'Product\Controller\Product',
                        'action'        => 'category',
                    ),
                ),
            ),
            'viewProduct' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/p[:id][/:name]',
                    'constraints' => array(
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Product\Controller',
                        'controller' => 'Product\Controller\Product',
                        'action'     => 'view',
                    ),
                ),
            ),
            'viewProduct2' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/c[:categoryId]/p[:id][/:name]',
                    'constraints' => array(
                        'categoryId' => '[0-9]+',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Product\Controller',
                        'controller' => 'Product\Controller\Product',
                        'action'     => 'view',
                    ),
                ),
            ),
			'wishlist' => array(
					'type' => 'Literal',
					'options' => array(
							'route' => '/wishlist',
							'defaults' => array(
									'__NAMESPACE__' => 'Product\Controller',
									'controller' => 'Product\Controller\Wishlist',
									'action'     => 'index',
							),
					),
					'may_terminate' => true,
					'child_routes' => array(
						'add' => array(
								'type' => 'Literal',
								'options' => array(
									'route' => '/add',
									'defaults' => array(
											'__NAMESPACE__' => 'Product\Controller',
											'controller' => 'Wishlist',
											'action' => 'add'
									),
							),
					),
					'remove' => array(
							'type' => 'Literal',
							'options' => array(
									'route' => '/remove',
									'defaults' => array(
											'__NAMESPACE__' => 'Product\Controller',
											'controller' => 'Wishlist',
											'action' => 'remove'
									),
							),
					),
				)
			)
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'product' => __DIR__ . '/../view',
        ),
    ),
);