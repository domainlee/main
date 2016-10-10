<?php
return [
    'controllers'  => [
        'invokables' => [
            'Order\Controller\Order'  => 'Order\Controller\OrderController',
            'Order\Controller\Orders' => 'Order\Controller\OrdersController',
            'Order\Controller\Cart'   => 'Order\Controller\CartController',
            'Order\Controller\Carts'  => 'Order\Controller\CartsController',
        ],
    ],
    'router'       => [
        'routes' => [
            'order'  => [
                'type'          => 'Segment',
                'options'       => [
                    'route'       => '[/:locale]/order',
                    'constraints' => [
                        'locale' => '[a-z]{2}-[a-z]{2}'
                    ],
                    'defaults'    => [
                        '__NAMESPACE__' => 'Order\Controller',
                        'controller'    => 'Order\Controller\Order',
                        'action'        => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'default'      => [
                        'type'    => 'segment',
                        'options' => [
                            'route'       => '[/:action][/:id]',
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'     => '[0-9]+',
                            ],
                            'defaults'    => [
                                '__NAMESPACE__' => 'Order\Controller',
                                'controller'    => 'Order\Controller\Order',
                                'action'        => 'index',
                            ],
                        ],
                    ],
                    'view'         => [
                        'type'    => 'segment',
                        'options' => [
                            'route'       => '[/:id][-:name]',
                            'constraints' => [
                                'id' => '[0-9]+',
                            ],
                            'defaults'    => [
                                'controller' => 'Order\Controller\Order',
                                'action'     => 'view',
                            ],
                        ],
                    ],
                    'savequickbuy' => [
                        'type'    => 'segment',
                        'options' => [
                            'route'       => '[/:id][-:name]',
                            'constraints' => [
                                'id' => '[0-9]+',
                            ],
                            'defaults'    => [
                                'controller' => 'Order\Controller\Order',
                                'action'     => 'view',
                            ],
                        ],
                    ],
                ]
            ],
            'orders' => [
                'type'          => 'Segment',
                'options'       => [
                    'route'       => '[/:locale]/orders',
                    'constraints' => [
                        'locale' => '[a-z]{2}-[a-z]{2}',
                    ],
                    'defaults'    => [
                        '__NAMESPACE__' => 'Order\Controller',
                        'controller'    => 'Orders',
                        'action'        => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'default' => [
                        'type'    => 'segment',
                        'options' => [
                            'route'       => '[/:action][/:id]',
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'     => '[0-9]+',
                            ],
                            'defaults'    => [
                                '__NAMESPACE__' => 'Order\Controller',
                                'controller'    => 'Orders',
                                'action'        => 'index',
                            ],
                        ],
                    ],
                    'view'    => [
                        'type'    => 'segment',
                        'options' => [
                            'route'       => '[/:id][-:name]',
                            'constraints' => [
                                'id' => '[0-9]+',
                            ],
                            'defaults'    => [
                                'controller' => 'Orders',
                                'action'     => 'view',
                            ],
                        ],
                    ]
                ]
            ],
            'success'   => [
                'type'    => 'Segment',
                'options' => [
                    'route'       => '[/:locale]/cart[/:action][/:id]',
                    'constraints' => [
                        'locale' => '[a-z]{2}-[a-z]{2}',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ],
                    'defaults'    => [
                        '__NAMESPACE__' => 'Order\Controller',
                        'controller'    => 'Cart',
                        'action'        => 'success',
                    ],
                ],
            ],
            'cart'   => [
                'type'    => 'Segment',
                'options' => [
                    'route'       => '[/:locale]/cart[/:action]',
                    'constraints' => [
                        'locale' => '[a-z]{2}-[a-z]{2}',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ],
                    'defaults'    => [
                        '__NAMESPACE__' => 'Order\Controller',
                        'controller'    => 'Cart',
                        'action'        => 'index',
                    ],
                ],
            ],

            'carts'  => [
                'type'    => 'Segment',
                'options' => [
                    'route'       => '[/:locale]/carts[/:action]',
                    'constraints' => [
                        'locale' => '[a-z]{2}-[a-z]{2}',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ],
                    'defaults'    => [
                        '__NAMESPACE__' => 'Order\Controller',
                        'controller'    => 'Carts',
                        'action'        => 'index',
                    ],
                ],
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            'order' => __DIR__ . '/../view',
        ],
        'strategies'          => [
            'ViewJsonStrategy',
        ]
    ],
];