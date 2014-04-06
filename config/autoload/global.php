<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overridding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

return [
    'db'                   => [
        'driver'         => 'Pdo',
        'dsn'            => 'mysql:dbname=DBNAME;host=localhost',
        'charset'        => 'utf8',
        'driver_options' => [
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'',
            'buffer_results'             => true
        ],
    ],
    'service_manager'      => [
        'factories' => [
            'Zend\Db\Adapter\Adapter'  => 'Zend\Db\Adapter\AdapterServiceFactory',
            'Zend\Mail\Transport\Smtp' => function ($sm) {
                $config = $sm->get('Config');
                $options = isset($config['smtp']) ? new \Zend\Mail\Transport\SmtpOptions($config['smtp']) : array();

                return new \Zend\Mail\Transport\Smtp($options);
            }
        ],
    ],
    'phpSettings'          => [
        'display_startup_errors' => 0,
        'display_errors'         => 0,
        'error_reporting'        => E_ALL,
        'error_log'              => __DIR__ . '/../../data/log/phperrors.log',
        'max_execution_time'     => 600,
    ],
    'settings'             => [
        // Remember me setting in seconds (defaults to 14 days)
        'rememberMe'    => 60 * 60 * 24 * 5,
        // Salt for passwords, be carefull to change - already generated
        // passwords will not work anymore
        'salt'          => 'CCa3HqqC2Yt7bWQKfPGAHftyAUW5reC5',
        'email_noreply' => 'noreply@email_noreply.com'
    ],
    'accessDeniedStrategy' => [
        'name'      => 'GuestRedirectUserSetHttpCode',
        'redirects' => [
            'hasIdentity'   => [
                'home' => ['auth', 'forgotPassword', 'signup', 'restorePassword'],
            ],
            'hasntIdentity' => ['auth' => ['all'],],
        ]
    ],
    'imagemagick'          => [
        'path' => '',
    ],
    'languages'            => [
        'en' => [
            'name'   => 'english',
            'locale' => 'en_US',
        ],
    ],
    'environment'          => 'production',
];
