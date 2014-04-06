<?php

return [
    'session' => [
        'sessionConfig' => [
            'name'                => 'LASESSID',
            'cache_expire'        => 60 * 60 * 1,
            'cookie_lifetime'     => 60 * 60 * 1,
            'gc_maxlifetime'      => 60 * 60 * 1,
            'cookie_path'         => '/',
            'remember_me_seconds' => 432000, //60*60*24*5
            'use_cookies'         => true,
            'cookie_httponly'     => true,
        ]
    ]
];
