<?php

return [
    'router'          => [
        'routes' => [
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'home'            => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/',
                    'defaults' => [
                        '__NAMESPACE__' => 'User\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
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
            'profile'         => [
                'type'    => 'Segment',
                'options' => [
                    'route'    => '[/:lang]/my-profile',
                    'defaults' => [
                        '__NAMESPACE__' => 'User\Controller',
                        'controller'    => 'Index',
                        'action'        => 'profile',
                    ],
                ],
            ],
            'save'            => [
                'type'    => 'Segment',
                'options' => [
                    'route'    => '[/:lang]/save',
                    'defaults' => [
                        '__NAMESPACE__' => 'User\Controller',
                        'controller'    => 'Index',
                        'action'        => 'save',
                    ],
                ],
            ],
            'auth'            => [
                'type'    => 'Literal',
                'options' => [
                    'route'    => '/auth',
                    'defaults' => [
                        'controller' => 'User\Controller\Auth',
                        'action'     => 'index',
                    ],
                ],
            ],
            'signup'          => [
                'type'    => 'Literal',
                'options' => [
                    'route'    => '/signup',
                    'defaults' => [
                        'controller' => 'User\Controller\Auth',
                        'action'     => 'signup',
                    ],
                ],
            ],
            'logout'          => [
                'type'    => 'Literal',
                'options' => [
                    'route'    => '/logout',
                    'defaults' => [
                        'controller' => 'User\Controller\Auth',
                        'action'     => 'logout',
                    ],
                ],
            ],
            'forgotPassword'  => [
                'type'    => 'Literal',
                'options' => [
                    'route'    => '/forgot-password',
                    'defaults' => [
                        'controller' => 'User\Controller\Auth',
                        'action'     => 'forgotPassword',
                    ],
                ],
            ],
            'restorePassword' => [
                'type'    => 'Segment',
                'options' => [
                    'route'    => '/restore-password/:hash',
                    'defaults' => [
                        'controller' => 'User\Controller\Auth',
                        'action'     => 'restorePassword',
                    ],
                ],
            ],
        ],
    ],
    'service_manager' => [
        'initializers' => [],
        'invokables'   => [
            'User\UsersMapper'            => 'User\Mapper\Users',
            'User\UsersService'           => 'User\Service\Users',
            'User\AuthenticationListener' => 'User\Event\AuthenticationListener',
            'User\UploadAvatarForm'       => 'User\Form\UploadAvatarForm',
            'User\ProfileForm'            => 'User\Form\Profile',
            'User\LoginForm'              => 'User\Form\Login',
            'User\SignupForm'             => 'User\Form\Signup',
            'User\ForgotPasswordForm'     => 'User\Form\ForgotPassword',
            'User\RestorePasswordForm'    => 'User\Form\RestorePassword',
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
            'User\Controller\Auth'  => 'User\Controller\AuthController',
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
