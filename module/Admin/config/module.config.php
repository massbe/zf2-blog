<?php

return [
    'controllers' => [
        'invokables' => [
            'Admin\Controller\Index' => 'Admin\Controller\IndexController',
            'category' => 'Admin\Controller\CategoryController',
            'article' => 'Admin\Controller\ArticleController',
        ],
    ],

    'router' => [
        'routes' => [
            'admin' => [
                'type' => 'literal',
                'options' => [
                    'route' => '/admin/',
                    'defaults' => [
                        'controller' => 'Admin\Controller\Index',
                        'action' => 'index',
                    ],
                ],

                'may_terminate' => true,

                'child_routes' => [
                    'category' => [
                        'type' => 'segment',
                        'options' => [
                            'route' => 'category/[:action/][:id/]',
                            'defaults' => [
                                'controller' => 'category',
                                'action' => 'index',
                            ],
                        ],
                    ],

                    'article' => [
                        'type' => 'segment',
                        'options' => [
                            'route' => 'article/[:action/][:id/]',
                            'defaults' => [
                                'controller' => 'article',
                                'action' => 'index',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'navigation' => [
        'default' => [[
            'label' => 'Главная',
            'route' => 'home',
        ],],

        'admin_navigation' => [[
            'label' => 'Панель навигации сайтом',
            'route' => 'admin',
            'action' => 'index',
            'resource' => 'Admin\Controller\Index',

            'pages' => [
                [
                    'label' => 'Статьи',
                    'route' => 'admin/article',
                    'action' => 'index',
                ],

                [
                    'label' => 'Добавить статью',
                    'route' => 'admin/article',
                    'action' => 'add',
                ],

                [
                    'label' => 'Категории',
                    'route' => 'admin/category',
                    'action' => 'index',
                ],

                [
                    'label' => 'Добавить категорию',
                    'route' => 'admin/category',
                    'action' => 'add',
                ],
            ],
        ],],

    ],

    'service_manager' => [
        'factories' => [
        'navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory',
        'admin_navigation' => 'Admin\Lib\AdminNavigationFactory',
        ],
    ],

    'view_manager' => array(
        'template_path_stack' => [
            'album' => __DIR__ . '/../view',
        ],
        'template_map' => [
            'pagination_control' => __DIR__ . '/../view/admin/layout/pagination_control.phtml',
        ],
    ),
];