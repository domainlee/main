<?php
return [
    'controllers'  => [
        'invokables' => [
            'News\Controller\News' => 'News\Controller\NewsController',
        ],
    ],
    'router'       => [
        'routes' => [
            'news'             => [
                'type'          => 'Segment',
                'options'       => [
                    'route'       => '[/:locale]/news',
                    'constraints' => [
                        'locale' => '[a-z]{2}-[a-z]{2}'
                    ],
                    'defaults'    => [
                        '__NAMESPACE__' => 'News\Controller',
                        'controller'    => 'News\Controller\News',
                        'action'        => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'default'   => [
                        'type'    => 'segment',
                        'options' => [
                            'route'       => '[/:action]',
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ],
                            'defaults'    => [
                                '__NAMESPACE__' => 'News\Controller',
                                'controller'    => 'News\Controller\News',
                                'action'        => 'index',
                            ],
                        ],
                    ],
                ]
            ],
            'categoryNews'  => [
                'type'    => 'segment',
                'options' => [
                    'route' => '/n[:id][/:name]',
                    'constraints' => [
                        'id' => '[0-9]+'
                    ],
                    'defaults'    => [
                        '__NAMESPACE__' => 'News\Controller',
                        'controller' => 'News\Controller\News',
                        'action'     => 'category',
                    ],
                ],
            ],
            'profilo' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/profilo/[:id][/:name]',
                    'constraints' => array(
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'News\Controller',
                        'controller' => 'News\Controller\News',
                        'action'     => 'profilo',
                    ),
                ),
            ),
            'view' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/news/[:id][-:name]',
                    'constraints' => array(
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'News\Controller',
                        'controller' => 'News\Controller\News',
                        'action'     => 'view',
                    ),
                ),
            ),
            'blog' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/blog',
                    'constraints' => array(
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'News\Controller',
                        'controller' => 'News\Controller\News',
                        'action'     => 'blog',
                    ),
                ),
            ),
            'blogview' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/blog/[:id][/:name]',
                    'constraints' => array(
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'News\Controller',
                        'controller' => 'News\Controller\News',
                        'action'     => 'blogview',
                    ),
                ),
            ),
            'about' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/about',
                    'constraints' => array(
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'News\Controller',
                        'controller' => 'News\Controller\News',
                        'action'     => 'about',
                    ),
                ),
            ),
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            'news' => __DIR__ . '/../view',
        ],
    ],
];