<?php

return array(

    'controllers' => array(
        'invokables' => array(
            'Blog\Controller\Index' => 'Blog\Controller\IndexController',
        ),
    ),

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
            'blog' => __DIR__ . '/../view',
        ),
    ),
);