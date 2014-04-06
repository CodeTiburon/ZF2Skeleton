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
                        ],
                    ],
                ],
            ],
            'ui'  => [],
        ],
    ]
];
