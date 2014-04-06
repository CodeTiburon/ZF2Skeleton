<?php

namespace Auth\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Db\Adapter\AdapterAwareInterface;
use Zend\Db\Adapter\Adapter;
use Zend\Form\Element;
use Zend\Validator;


class Signup extends Form implements InputFilterProviderInterface, AdapterAwareInterface
{
    /**
     * @var Adapter
     */
    protected $adapter;

    public function setDbAdapter(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    public function __construct()
    {
        parent::__construct('signup-form', ['method' => 'post']);

        $this
            ->add(
                [
                    'name'       => 'first_name',
                    'options'    => [
                        'label' => 'First Name',
                    ],
                    'attributes' => [
                        'placeholder' => 'First Name',
                        'id'          => 'signup-firstname',
                    ],
                ]
            )
            ->add(
                [
                    'name'       => 'last_name',
                    'options'    => [
                        'label' => 'Last Name',
                    ],
                    'attributes' => [
                        'placeholder' => 'Last Name',
                        'id'          => 'signup-lastname',
                    ],
                ]
            )
            ->add(
                [
                    'name'       => 'email',
                    'options'    => [
                        'label' => 'Email',
                    ],
                    'attributes' => [
                        'id'           => 'signup-email',
                        'autocomplete' => 'off',
                        'placeholder'  => 'Email',
                    ],
                ]
            )
            ->add(
                [
                    'name'       => 'password',
                    'type'       => 'Password',
                    'options'    => [
                        'label' => 'Password',
                    ],
                    'attributes' => [
                        'placeholder' => 'Password',
                        'id'          => 'signup-password',
                    ],
                ]
            )

            ->add(
                [
                    'name'       => 'cpassword',
                    'type'       => 'Password',
                    'options'    => [
                        'label' => 'Re-enter password',
                    ],
                    'attributes' => [
                        'placeholder' => 'Confirm Password',
                        'id'          => 'signup-cpassword',
                    ],
                ]
            )

            ->add(
                [
                    'type'       => 'Submit',
                    'name'       => 'submit',
                    'options'    => [
                        'label' => 'Register Now',
                    ],
                    'attributes' => [
                        'class' => 'btn btn-primary',
                        'value' => 'Submit',
                    ],
                ]
            );
    }

    public function getInputFilterSpecification()
    {
        return
            [
                'email'         => [
                    'required'   => true,
                    'filters'    => [
                        ['name' => 'StripTags'],
                        ['name' => 'StringTrim'],
                    ],
                    'validators' => [
                        [
                            'name'                   => 'NotEmpty',
                            'break_chain_on_failure' => true,
                            'options'                => [
                                'messages' => [
                                    'isEmpty' => 'Email is required and can\'t be empty',
                                ],
                            ],
                        ],
                        [
                            'name'                   => 'EmailAddress',
                            'break_chain_on_failure' => true,
                            'options'                => [
                                'allow'      => Validator\Hostname::ALLOW_ALL,
                                'useMxCheck' => true,
                                'messages'   => [
                                    'emailAddressInvalidFormat'     => 'Email address doesn\'t appear to be valid.',
                                    'emailAddressInvalidMxRecord'   => 'Email address doesn\'t appear to be valid.',
                                    'emailAddressDotAtom'           => 'Email address doesn\'t appear to be valid.',
                                    'emailAddressQuotedString'      => 'Email address doesn\'t appear to be valid.',
                                    'emailAddressInvalidLocalPart'  => 'Email address doesn\'t appear to be valid.',
                                    'emailAddressInvalidHostname'   => 'Email address doesn\'t appear to be valid.',
                                    'hostnameUnknownTld'            => 'Email address doesn\'t appear to be valid.',
                                    'hostnameInvalidUri'            => 'Email address doesn\'t appear to be valid.',
                                    'hostnameInvalidLocalName'      => 'Email address doesn\'t appear to be valid.',
                                    'hostnameInvalidHostnameSchema' => 'Email address doesn\'t appear to be valid.',
                                    'hostnameInvalidHostname'       => 'Email address doesn\'t appear to be valid.',
                                    'hostnameUndecipherableTld'     => 'Email address doesn\'t appear to be valid.',
                                ],
                            ],
                        ],
                        [
                            'name'                   => 'Db\NoRecordExists',
                            'break_chain_on_failure' => true,
                            'options'                => [
                                'table'    => 'users',
                                'field'    => 'email',
                                'adapter'  => $this->adapter,
                                'messages' => [
                                    'recordFound' => 'This e-mail is already in use.',
                                ],

                            ],
                        ],
                    ],
                ],
                'password'      => [
                    'required'               => true,
                    'break_chain_on_failure' => true,
                    'filters'                => [
                        ['name' => 'StripTags'],
                    ],
                    'validators'             => [
                        [
                            'name'                   => 'NotEmpty',
                            'break_chain_on_failure' => true,
                            'options'                => [
                                'messages' => [
                                    'isEmpty' => 'Password is required and can\'t be empty',
                                ],
                            ],
                        ],
                        [
                            'name'    => 'StringLength',
                            'options' => [
                                'min'      => 8,
                                'messages' => [
                                    'stringLengthTooShort' => 'Password is less than 8 characters long',
                                ],
                            ],
                        ],
                    ],
                ],
                'cpassword'     => [
                    'required'               => true,
                    'break_chain_on_failure' => true,
                    'filters'                => [
                        ['name' => 'StripTags'],
                    ],
                    'validators'             => [
                        [
                            'name'                   => 'NotEmpty',
                            'break_chain_on_failure' => true,
                            'options'                => [
                                'messages' => [
                                    'isEmpty' => 'Confirm Password is required and can\'t be empty',
                                ],
                            ],
                        ],
                        [
                            'name'    => 'Identical',
                            'options' => [
                                'token'    => 'password',
                                'messages' => [
                                    'notSame' => 'Entered passwords do not match.',
                                ],
                            ],
                        ],
                    ],
                ],
                'first_name'    => [
                    'required'               => true,
                    'break_chain_on_failure' => true,
                    'filters'                => [
                        ['name' => 'StripTags'],
                        ['name' => 'StringTrim'],
                    ],
                    'validators'             => [
                        [
                            'name'    => 'NotEmpty',
                            'options' => [
                                'messages' => [
                                    'isEmpty' => 'First name is required and can\'t be empty',
                                ],
                            ],
                        ],
                    ],
                ],
                'last_name'     => [
                    'required'               => true,
                    'break_chain_on_failure' => true,
                    'filters'                => [
                        ['name' => 'StripTags'],
                        ['name' => 'StringTrim'],
                    ],
                    'validators'             => [
                        [
                            'name'    => 'NotEmpty',
                            'options' => [
                                'messages' => [
                                    'isEmpty' => 'Last name is required and can\'t be empty',
                                ],
                            ],
                        ],
                    ],
                ],
            ];
    }
}
