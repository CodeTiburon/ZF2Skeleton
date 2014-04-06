<?php

return [
    'acl' => [
        'resources' => [
            'mvc' => [
                'children' => [
                    'auth' => [
                        'children' => [
                            'index' => [
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
                                    'confirmation' => [
                                        'allow' => 'guest',
                                    ],
                                    'resendMessage' => [
                                        'allow' => 'user'
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
