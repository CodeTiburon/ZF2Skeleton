<?php

return [
    'router'          => [
        'routes' => [
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'home'    => [
                'type'          => 'Segment',
                'options'       => [
                    'route'       => '[/:lang]/',
                    'constraints' => [
                        'lang' => '[a-z]{2}?',
                    ],
                    'defaults'    => [
                        '__NAMESPACE__' => 'User\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                        'lang'          => 'en',
                    ],

                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'default' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/[:controller[/:action]]',
                            'constraints' => [
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ],
                            'defaults'    => [],
                        ],
                    ],
                ],
            ],
            'profile' => [
                'type'    => 'Segment',
                'options' => [
                    'route'       => '[/:lang]/my-profile',
                    'constraints' => [
                        'lang' => '[a-z]{2}?',
                    ],
                    'defaults'    => [
                        '__NAMESPACE__' => 'User\Controller',
                        'controller'    => 'Index',
                        'action'        => 'profile',
                        'lang'          => 'en',
                    ],
                ],
            ],
            'save'    => [
                'type'    => 'Segment',
                'options' => [
                    'route'       => '[/:lang]/save',
                    'constraints' => [
                        'lang' => '[a-z]{2}?',
                    ],
                    'defaults'    => [
                        '__NAMESPACE__' => 'User\Controller',
                        'controller'    => 'Index',
                        'action'        => 'save',
                        'lang'          => 'en',
                    ],
                ],
            ],
        ],
    ],
    'service_manager' => [
        'initializers' => [],
        'invokables'   => [
            'User\UsersMapper'      => 'User\Mapper\Users',
            'User\UsersService'     => 'User\Service\Users',
            'User\UploadAvatarForm' => 'User\Form\UploadAvatarForm',
            'User\UserForm'         => 'User\Form\User',
            'User\ProfileForm'      => 'User\Form\Profile',
        ],
        'factories'    => [],
    ],
    'translator'      => [
        'locale'                    => 'en_US',
        'translation_file_patterns' => [
            [
                'type'     => 'phparray',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.php',
            ],
        ],
    ],
    'controllers'     => [
        'invokables' => [
            'User\Controller\Index' => 'User\Controller\IndexController',
        ],
    ],
    'module_layouts'  => [
        'User' => 'layout/user',
    ],
    'view_manager'    => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'template_map'             => [
            'layout/user'         => __DIR__ . '/../view/layout/user.phtml',
            'partials/event-item' => __DIR__ . '/../view/user/partials/event-item.phtml',
        ],
        'template_path_stack'      => [
            __DIR__ . '/../view',
        ],
        'strategies'               => [
            'ViewJsonStrategy',
        ],
    ],
    'view_helpers'    => [
        'initializers' => [],
        'invokables'   => [
            'currentUser' => 'User\View\Helper\CurrentUser',
        ],
        'factories'    => [],
    ],
];
