<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Artem
 * Date: 16.04.13
 * Time: 17:21
 * To change this template use File | Settings | File Templates.
 */

namespace User\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\InputFilter;

class UploadAvatarForm extends Form implements InputFilterProviderInterface
{

    public function __construct()
    {
        parent::__construct(
            'form-upload-avatar',
            array(
                'method' => 'post',
                'enctype' => 'multipart/form-data',
            )
        );

        $this->add(
            array(
                'type' => 'Zend\Form\Element\File',
                'name' => 'files',
            )
        );
    }

    public function getInputFilterSpecification()
    {
        return array(
            'files' => array(
                'required' => true,
                'validators' => array(
                    array(
                        'name' => 'filesize',
                        'options'=> array('max' => 15728640),
                    ),
                    array(
                        'name' => 'filemimetype',
                        'options' => array(
                            'mimeType' => array('image/png','image/x-png','image/jpeg','image/gif'),
                        ),
                    ),
                    array(
                        'name' => 'fileimagesize',
                        'options' => array(
                            'minWidth' => 168,
                            'minHeight' => 168,
                        ),
                    ),

                ),
            ),
        );
    }
}
