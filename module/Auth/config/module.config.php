<?php

return [
    'router'          => [
        'routes' => [
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'auth'            => [
                'type'          => 'Segment',
                'options'       => [
                    'route'       => '[/:lang]/auth',
                    'constraints' => [
                        'lang' => '[a-z]{2}?',
                    ],
                    'defaults'    => [
                        'controller' => 'Auth\Controller\Index',
                        'action'     => 'index',
                        'lang'       => 'en',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'default' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '[/:lang]/[:controller[/:action]]',
                            'constraints' => [
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'lang'       => '[a-z]{2}?',
                            ],
                            'defaults'    => [
                                'lang' => 'en',
                            ],
                        ],
                    ],
                ],
            ],
            'signup'          => [
                'type'    => 'Segment',
                'options' => [
                    'route'       => '[/:lang]/signup',
                    'constraints' => [
                        'lang' => '[a-z]{2}?',
                    ],
                    'defaults'    => [
                        'controller' => 'Auth\Controller\Index',
                        'action'     => 'signup',
                        'lang'       => 'en'
                    ],
                ],
            ],
            'logout'          => [
                'type'    => 'Literal',
                'options' => [
                    'route'    => '/logout',
                    'defaults' => [
                        'controller' => 'Auth\Controller\Index',
                        'action'     => 'logout',
                    ],
                ],
            ],
            'forgotPassword'  => [
                'type'    => 'Segment',
                'options' => [
                    'route'       => '[/:lang]/forgot-password',
                    'constraints' => [
                        'lang' => '[a-z]{2}?',
                    ],
                    'defaults'    => [
                        'controller' => 'Auth\Controller\Index',
                        'action'     => 'forgotPassword',
                        'lang'       => 'en'
                    ],
                ],
            ],
            'restorePassword' => [
                'type'    => 'Segment',
                'options' => [
                    'route'       => '[/:lang]/restore-password/:hash',
                    'constraints' => [
                        'lang' => '[a-z]{2}?',
                    ],
                    'defaults'    => [
                        'controller' => 'Auth\Controller\Index',
                        'action'     => 'restorePassword',
                        'lang'       => 'en'
                    ],
                ],
            ],
            'confirmation'    => [
                'type'    => 'Segment',
                'options' => [
                    'route'       => '[/:lang]/confirmation/:hash',
                    'constraints' => [
                        'lang' => '[a-z]{2}?',
                    ],
                    'defaults'    => [
                        'controller' => 'Auth\Controller\Index',
                        'action'     => 'confirmation',
                        'lang'       => 'en'
                    ],
                ],
            ],
            'resendMessage'   => [
                'type'    => 'Segment',
                'options' => [
                    'route'       => '[/:lang]/resend',
                    'constraints' => [
                        'lang' => '[a-z]{2}?',
                    ],
                    'defaults'    => [
                        'controller' => 'Auth\Controller\Index',
                        'action'     => 'resendMessage',
                        'lang'       => 'en'
                    ],
                ],
            ],
        ],
    ],
    'service_manager' => [
        'initializers' => [
            function ($instance, $sm) {
                if ($instance instanceof \Zend\ServiceManager\ServiceLocatorAwareInterface) {
                    $instance->setServiceLocator($sm);
                }
            }
        ],
        'invokables'   => [
            'Auth\LoginForm'              => 'Auth\Form\Login',
            'Auth\SignupForm'             => 'Auth\Form\Signup',
            'Auth\ForgotPasswordForm'     => 'Auth\Form\ForgotPassword',
            'Auth\RestorePasswordForm'    => 'Auth\Form\RestorePassword',
            'Auth\AuthenticationListener' => 'Auth\Event\AuthenticationListener',
        ],
        'factories'    => [
            'translator'                  => 'Zend\I18n\Translator\TranslatorServiceFactory',
            'Auth\Storage\SessionStorage' => 'Auth\Factory\SessionStorageFactory',
        ],
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
            'Auth\Controller\Index' => 'Auth\Controller\IndexController',
        ],
    ],
    'module_layouts'  => [
        'Auth' => 'layout/auth',
    ],
    'view_manager'    => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'template_map'             => [
            'layout/auth'                            => __DIR__ . '/../view/layout/auth.phtml',
            'auth/index/index'                       => __DIR__ . '/../view/auth/index/index.phtml',
            'auth/index/signup'                      => __DIR__ . '/../view/auth/index/signup.phtml',
            'auth/index/forgot-password'             => __DIR__ . '/../view/auth/index/forgot-password.phtml',
            /*******MAIL TEMPLATE*******/
            'auth/mail-template/signup'              => __DIR__ . '/../view/auth/mail-template/auth.signup.phtml',
            'auth/mail-template/signup.subj'         => __DIR__ . '/../view/auth/mail-template/auth.signup.subj',
            'auth/mail-template/forgotpassword'      => __DIR__ . '/../view/auth/mail-template/auth.forgotpassword.phtml',
            'auth/mail-template/forgotpassword.subj' => __DIR__ . '/../view/auth/mail-template/auth.forgotpassword.subj',
            'auth/mail-template/resendmessage'       => __DIR__ . '/../view/auth/mail-template/auth.resendmessage.phtml',
            'auth/mail-template/resendmessage.subj'  => __DIR__ . '/../view/auth/mail-template/auth.resendmessage.subj',
        ],
        'template_path_stack'      => [
            __DIR__ . '/../view',
        ],
        'strategies'               => [
            'ViewJsonStrategy',
        ],
    ],
    'view_helpers'    => [
        'initializers' => [
            function ($instance, $sm) {
                if ($instance instanceof \Zend\ServiceManager\ServiceLocatorAwareInterface) {
                    $instance->setServiceLocator($sm);
                }
            }
        ],
        'invokables'   => [],
        'factories'    => [],
    ],
];
