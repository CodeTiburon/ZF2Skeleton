<?php

return [
    'acl' => [
        'resources' => [
            'mvc' => [
                'children' => [
                    'user' => [
                        'children' => [
                            'index' => [
                                'children' => [
                                    'index' => [
                                        'allow' => 'user'
                                    ],
                                    'profile' => [
                                        'allow' => 'user'
                                    ],
                                    'save' => [
                                        'allow' => 'user'
                                    ],
                                    'uploadAvatar' => [
                                        'allow' => 'user'
                                    ],
                                ],
                            ],
                            'auth' => [
                                'children' => [
                                    'index' => [
                                        'allow' => 'guest',
                                        'deny'  => 'user',
                                    ],
                                    'signup' => [
                                        'allow' => 'guest',
                                        'deny'  => 'user',
                                    ],
                                    'forgotPassword' => [
                                        'allow' => 'guest',
                                        'deny'  => 'user',
                                    ],
                                    'logout' => [
                                        'allow' => 'user',
                                    ],
                                    'restorePassword' => [
                                        'allow' => 'guest',
                                        'deny'  => 'user',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'ui'  => [],
        ],
    ]
];
