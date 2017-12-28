<?php

return array(

    'controllers' => array(
        'invokables' => array(
            'Blog\Controller\Index' => 'Blog\Controller\IndexController',
        ),
    ),

//    'router' => array(
//        'routes' => array(
//            'blog' => array(
//                'type'    => 'literal',
//                'options' => array(
//                    'route'    => '/blog',
//                    'defaults' => array(
//                        'controller' => 'Blog\Controller\Index',
//                        'action'     => 'index',
//                    ),
//                ),
//            ),
//        ),
//    ),

    'doctrine' => [
        'driver' => [
            'blog_entity' => [
                'class' => \Doctrine\ORM\Mapping\Driver\AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [
                    __DIR__ . '/../src/Blog/Entity',
                ],
            ],

            'orm_default' => [
                'drivers' => [
                    'Blog\Entity' => 'blog_entity',
                ],
            ],
        ],
    ],

    'view_manager' => array(
        'template_path_stack' => array(
            'album' => __DIR__ . '/../view',
        ),
    ),
);