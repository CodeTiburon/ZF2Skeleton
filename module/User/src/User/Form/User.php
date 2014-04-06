<?php

namespace User\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\InputFilter;

class User extends Form implements InputFilterProviderInterface
{

    public function __construct()
    {
        parent::__construct('form-user', ['method' => 'post']);

        $this
            ->add([
                'name' => 'email',
                'attributes' => [
                    'id' => 'email',
                ],
            ])

            ->add([
                    'name' => 'first_name',
                    'attributes' => [
                        'id' => 'first_name',
                    ],
            ])

            ->add([
                    'name' => 'last_name',
                    'attributes' => [
                        'id' => 'last_name',
                    ],
            ])

            ->add([
                    'name' => 'gender',
                    'attributes' => [
                        'id' => 'gender',
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
            ],
            'first_name' => [
                'required' => true,
                'filters' => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
            ],
            'last_name' => [
                'required' => true,
                'filters' => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
            ],
            'gender' => [
                'required' => true,
                'filters' => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
            ],
        ];
    }
}