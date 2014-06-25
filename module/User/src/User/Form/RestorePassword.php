<?php

namespace User\Form;

use \Zend\Form\Form;
use \Zend\InputFilter\InputFilterProviderInterface;

class RestorePassword extends Form implements InputFilterProviderInterface
{
    public function __construct ()
    {
        parent::__construct('form-restore-password', ['method' => 'post']);

        $this->add(
            [
                'name'       => 'password',
                'type'       => 'Password',
                'options'    => [
                    'label' => 'Password',
                ],
                'attributes' => [
                    'id'          => 'restore-password-password',
                    'placeholder' => 'Password',
                ],
            ]
        );

        $this->add(
            [
                'name'       => 'cpassword',
                'type'       => 'Password',
                'options'    => [
                    'label' => 'Re-enter password',
                ],
                'attributes' => [
                    'id'          => 'restore-password-cpassword',
                    'placeholder' => 'Repeat password',
                ],
            ]
        );

        $this->add(
            [
                'type'       => 'Button',
                'name'       => 'submit',
                'options'    => ['label' => 'Update'],
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

            'password'  => [
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
                            'message' => 'Password is required and can\'t be empty'
                        ],
                    ],
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'min'     => 8,
                            'message' => 'Password is less than 8 characters long'
                        ],
                    ],
                ],
            ],
            'cpassword' => [
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
                            'message' => 'Confirm Password is required and can\'t be empty'
                        ],
                    ],
                    [
                        'name'    => 'Identical',
                        'options' => [
                            'token'   => 'password',
                            'message' => 'Entered passwords do not match.'
                        ],
                    ],
                ],
            ],
        ];
    }
}
