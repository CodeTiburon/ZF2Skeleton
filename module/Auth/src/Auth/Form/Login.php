<?php

namespace Auth\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Db\Adapter\AdapterAwareInterface;
use Zend\Db\Adapter\Adapter;
use Zend\Validator;

class Login extends Form implements InputFilterProviderInterface, AdapterAwareInterface
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
        parent::__construct('form-login-widget', ['method' => 'post']);

        $this->add([
            'name' => 'email',
            'options' => ['label' => 'Email'],
            'attributes' => [
                'autocomplete' => 'off',
                'placeholder' => 'Email',
                'id' => 'login-email',
                'class' => 'span2',
                'tabindex' => 1,
            ],
        ]);

        $this->add([
            'type' => 'Password',
            'name' => 'password',
            'options' => ['label' => 'Password'],
            'attributes' => [
                'id' => 'login-password',
                'autocomplete' => 'off',
                'placeholder' => 'Password',
                'class' => 'span2',
                'tabindex' => 2,
            ],
        ]);

        $this->add([
            'type' => 'Button',
            'name' => 'submit',
            'options' => ['label' => 'Login'],
            'attributes' => [
                'type' => 'submit',
                'class' => 'btn btn-success span2',
                'tabindex' => 4,
            ],
        ]);

        $this->add([
            'type' => 'Checkbox',
            'name' => 'remember_me',
            'options' => [
                'label' => 'Remember me',
            ],
            'attributes' => [
                'id' => 'login-remember_me',
                'class' => 'checkboxInput',
                'tabindex' => 3,
            ],
        ]);

    }

    public function getInputFilterSpecification()
    {
        return [
            'email' => [
                'required' => true,
                'filters' => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    [
                        'name' => 'NotEmpty',
                        'break_chain_on_failure' => true,
                        'options' => [
                            'messages' => [
                                'isEmpty' => 'Email is required and can\'t be empty',
                            ],
                        ],
                    ],
                    [
                        'name' => 'EmailAddress',
                        'break_chain_on_failure' => true,
                        'options' => [
                            'allow' => Validator\Hostname::ALLOW_ALL,
                            'useMxCheck' => true,
                            'messages' => [
                                'emailAddressInvalidFormat' => 'Email address doesn\'t appear to be valid.',
                                'emailAddressInvalidMxRecord' => 'Email address doesn\'t appear to be valid.',
                                'emailAddressDotAtom' => 'Email address doesn\'t appear to be valid.',
                                'emailAddressQuotedString' => 'Email address doesn\'t appear to be valid.',
                                'emailAddressInvalidLocalPart' => 'Email address doesn\'t appear to be valid.',
                                'emailAddressInvalidHostname' => 'Email address doesn\'t appear to be valid.',
                                'hostnameUnknownTld' => 'Email address doesn\'t appear to be valid.',
                                'hostnameInvalidUri' => 'Email address doesn\'t appear to be valid.',
                                'hostnameInvalidLocalName' => 'Email address doesn\'t appear to be valid.',
                                'hostnameInvalidHostnameSchema' => 'Email address doesn\'t appear to be valid.',
                                'hostnameInvalidHostname' => 'Email address doesn\'t appear to be valid.',
                                'hostnameUndecipherableTld' => 'Email address doesn\'t appear to be valid.',
                            ],
                        ],
                    ],
                    [
                        'name' => 'Db\RecordExists',
                        'options' => [
                            'table' => 'users',
                            'field' => 'email',
                            'adapter' => $this->adapter,
                            'messages' => [
                                'noRecordFound' => 'This e-mail was not used on our site.'
                            ],
                        ],
                    ],
                ],
            ],
            'password' => [
                'required' => true,
                'filters' => [
                    ['name' => 'StripTags'],
                ],
                'validators' => [
                    [
                        'name' => 'NotEmpty',
                        'break_chain_on_failure' => true,
                        'options' => [
                            'messages' => [
                                'isEmpty' => 'Password is required and can\'t be empty',
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}
