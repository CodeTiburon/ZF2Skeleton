<?php
return [
    'modules'                 => [
        'Application',
        'Auth',
        'Zf2Db',
        'Zf2EmailService',
        'User',
        'Zf2Acl',
        'Zf2Multimedia',
    ],
    'module_listener_options' => [
        'config_glob_paths' => [
            'config/autoload/{,*.}{global,local}.php',
        ],
        'module_paths'      => [
            './module',
            './vendor',
        ],
    ],
];
