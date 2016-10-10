<?php

namespace Home\Form;
use Base\InputFilter\ProvidesEventsInputFilter;

class UploadFilter extends ProvidesEventsInputFilter
{
    public function __construct()
    {
        $this->add(array(
            'name' => 'email',
            'required' => true,
            'filter' => array(
                array ('name' => 'StringStrim')
            ),
            'validators' => array (
                array (
                    'name' => 'NotEmpty',
                    'break_chain_on_failure' => true,
                    'options' => array (
                        'messages' => array (
                            'isEmpty' => 'Bạn chưa nhập email'
                        )
                    )
                ),
                array(
                    'name'    => 'EmailAddress',
                    'break_chain_on_failure' => true,
                    'options' => array(
                        'messages' => array(
                            'emailAddressInvalidFormat' => 'Địa chỉ email không hợp lệ'
                        )
                    )
                ),
            )
        ));

        $this->add ( array (
            'name' => 'phone',
            'required' => true,
            'filters' => array (
                array ('name' => 'StringTrim'),
                array ('name' => 'Digits')
            ),
            'validators' => array (
                array (
                    'name' => 'NotEmpty',
                    'break_chain_on_failure' => true,
                    'options' => array (
                        'messages' => array (
                            'isEmpty' => 'Bạn chưa nhập số điện thoại hoặc số điện thoại chưa phù hợp'
                        )
                    )
                ),
                array (
                    'name' => 'StringLength',
                    'break_chain_on_failure' => true,
                    'options' => array (
                        'min' => 10,
                        'max' => 11,
                        'messages' => array (
                            StringLength::INVALID => 'Số điện thoại phải là dạng 10-11 chữ số',
                            StringLength::TOO_SHORT => 'Số điện thoại phải là dạng 10-11 chữ số',
                            StringLength::TOO_LONG => 'Số điện thoại phải là dạng 10-11 chữ số'
                        )
                    )
                )
            )
        ));

        $this->add ( array (
            'name' => 'file',
            'required' => false,
            'validators' => array (
                array (
                    'name' => 'File_Size',
                    'break_chain_on_failure' => true,
                    'options' => array (
                        'messages' => array (
                            \Zend\Validator\File\Size::TOO_BIG => 'Ảnh không được vượt quá 500kb'
                        ),
                        'max' => 512000, // 500kb

                        array (
                            'name' => 'File_MimeType',
                            'break_chain_on_failure' => true,
                            'options' => array (
                                'messages' => array (
                                    \Zend\Validator\File\MimeType::FALSE_TYPE => 'Ảnh phải là định dạng gif, jpg, jpeg, png.'
                                ),
                                'mimeType' => 'image/gif,image/jpg,image/jpeg,image/png'
                            )
                        )
                    )
                )
            )
        ) );
        
        $this->getEventManager()->trigger('init', $this);
    }
}