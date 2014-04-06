<?php

namespace User\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Db\Adapter\AdapterAwareInterface;
use Zend\Db\Adapter\Adapter;
use Zend\InputFilter;
use Zend\Validator;

class Profile extends Form implements InputFilterProviderInterface, AdapterAwareInterface
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
        parent::__construct('form-user', ['method' => 'post']);

        $this
            ->add(['name' => 'email'])
            ->add(['name' => 'first_name'])
            ->add(['name' => 'last_name'])
            ->add(['name' => 'lang'])
            ->add(['name' => 'gender'])
            ->add(['name' => 'password'])
            ->add(['name' => 'cpassword'])
            ->add(['name' => 'birth'])
            ->add(['name' => 'place_birth'])
            ->add(['name' => 'contact_number'])
            ->add(['name' => 'country'])
            ->add(['name' => 'city'])
            ->add(['name' => 'street'])
            ->add(['name' => 'number_house'])
            ->add(['name' => 'zip_code']);
    }

    public function getInputFilterSpecification()
    {
        return [
            'email'          => [
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
            'first_name'     => [
                'required'   => true,
                'filters'    => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    ['name' => 'NotEmpty'],
                ],
            ],
            'last_name'      => [
                'required'   => true,
                'filters'    => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    ['name' => 'NotEmpty'],
                ],
            ],
            'lang'      => [
                'required'   => true,
                'filters'    => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    ['name' => 'NotEmpty'],
                ],
            ],
            'gender'         => [
                'required'   => true,
                'filters'    => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    ['name' => 'NotEmpty'],
                ],
            ],
            'password'       => [
                'required'   => true,
                'filters'    => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    ['name' => 'NotEmpty'],
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'min' => 8,
                        ],
                    ],
                ],
            ],
            'cpassword'      => [
                'required'   => true,
                'filters'    => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    ['name' => 'NotEmpty'],
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
            'birth'          => [
                'required'   => true,
                'filters'    => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    ['name' => 'NotEmpty'],
                ],
            ],
            'place_birth'    => [
                'required'   => true,
                'filters'    => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    ['name' => 'NotEmpty'],
                ],
            ],
            'contact_number' => [
                'required'   => true,
                'filters'    => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    ['name' => 'NotEmpty'],
                ],
            ],
            'country'        => [
                'required'   => true,
                'filters'    => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    ['name' => 'NotEmpty'],
                ],
            ],
            'city'           => [
                'required'   => true,
                'filters'    => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    ['name' => 'NotEmpty'],
                ],
            ],
            'street'         => [
                'required'   => true,
                'filters'    => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    ['name' => 'NotEmpty'],
                ],
            ],
            'number_house'   => [
                'required'   => true,
                'filters'    => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    ['name' => 'NotEmpty'],
                ],
            ],
            'zip_code'       => [
                'required'   => true,
                'filters'    => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    ['name' => 'NotEmpty'],
                ],
            ],
        ];
    }
}