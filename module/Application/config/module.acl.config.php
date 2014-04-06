<?php

return [
    'acl' => [
        'default'   => [
            'role'   => 'guest',
            'access' => 'deny',
        ],
        'roleKey'   => 'role',
        'roles'     => [
            'guest' => null,
            'user'  => 'guest',
            'admin' => 'user',
        ],
        'resources' => [
            'mvc' => [
                'children' => [
                    'application' => [
                        'children' => [
                            'index' => [
                                'children' => [
                                    'index' => [],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'ui'  => [],
        ],
    ],
];
