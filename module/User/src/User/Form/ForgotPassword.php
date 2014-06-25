<?php

namespace User\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Db\Adapter\AdapterAwareInterface;
use Zend\Db\Adapter\Adapter;
use Zend\Validator;

class ForgotPassword extends Form implements InputFilterProviderInterface, AdapterAwareInterface
{
    /**
     * @var Adapter
     */
    protected $adapter;

    public function setDbAdapter (Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    public function __construct ()
    {
        parent::__construct('form-forgot-password', ['method' => 'post']);

        $this->add(
            [
                'name'       => 'email',
                'options'    => [
                    'label' => 'Email'
                ],
                'attributes' => [
                    'autocomplete' => 'off',
                    'placeholder'  => 'Email',
                    'id'           => 'forgot-password-email',
                    'autofocus'    => 'autofocus',
                    'tabindex'     => 1,
                    'class'        => 'span2',
                ],
            ]
        );

        $this->add(
            [
                'type'       => 'Button',
                'name'       => 'submit',
                'options'    => [
                    'label' => 'Send'
                ],
                'attributes' => [
                    'type'     => 'submit',
                    'class'    => 'btn btn-success',
                    'tabindex' => 2,
                ],
            ]
        );
    }

    public function getInputFilterSpecification ()
    {
        return [
            'email' => [
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
                            'message' => 'Email is required and can\'t be empty',
                        ],
                    ],
                    [
                        'name'                   => 'EmailAddress',
                        'break_chain_on_failure' => true,
                        'options'                => [
                            'allow'      => Validator\Hostname::ALLOW_ALL,
                            'useMxCheck' => true,
                            'message'    => 'Email address doesn\'t appear to be valid.',
                        ],
                    ],
                    [
                        'name'    => 'Db\RecordExists',
                        'options' => [
                            'table'   => 'users',
                            'field'   => 'email',
                            'adapter' => $this->adapter,
                            'message' => 'This e-mail was not used on our site'
                        ],
                    ],
                ],
            ],
        ];
    }
}
