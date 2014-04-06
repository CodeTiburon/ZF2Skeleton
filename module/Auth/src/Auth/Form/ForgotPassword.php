<?php

namespace Auth\Form;

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

    public function setDbAdapter(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    public function __construct()
    {
        parent::__construct('form-forgot-password', array(
            'method' => 'post',
        ));

        $this->add(array(
            'name' => 'email',
            'options' => array('label' => 'Email'),
            'attributes' => array(
                'autocomplete' => 'off',
                'placeholder' => 'Email',
                'id' => 'forgot-password-email',
                'autofocus' => 'autofocus',
                'tabindex' => 1,
                'class' => 'span2',
            ),
        ));

        $this->add(array(
            'type' => 'Button',
            'name' => 'submit',
            'options' => array('label' => 'Send'),
            'attributes' => array(
                'type' => 'submit',
                'class' => 'btn btn-success',
                'tabindex' => 2,
            ),
        ));
    }

    public function getInputFilterSpecification()
    {
        return array(
            'email' => array(
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'break_chain_on_failure' => true,
                        'options' => array(
                            'messages' => array(
                                'isEmpty' => 'Email is required and can\'t be empty',
                            ),
                        ),
                    ),
                    array(
                        'name' => 'EmailAddress',
                        'break_chain_on_failure' => true,
                        'options' => array(
                            'allow' => Validator\Hostname::ALLOW_ALL,
                            'useMxCheck' => true,
                            'messages' => array(
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
                            ),
                        ),
                    ),
                    array(
                        'name' => 'Db\RecordExists',
                        'options' => array(
                            'table' => 'users',
                            'field' => 'email',
                            'adapter' => $this->adapter,
                            'messages' => array(
                                'noRecordFound' => 'This e-mail was not used on our site'
                            ),
                        ),
                    ),
                ),
            ),
        );
    }
}
