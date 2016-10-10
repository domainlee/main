<?php

namespace User\Form;

use Base\InputFilter\ProvidesEventsInputFilter;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class SignupFilter extends ProvidesEventsInputFilter
{
    public function __construct()
    {
        $this->add(array(
            'name'       => 'username',
            'filters'   => array(
                array('name' => 'StringTrim'),
            	array('name' => 'StringToLower')
            ),
            'validators' => array(
                array(
                    'name'    => 'NotEmpty',
                	'break_chain_on_failure' => true,
                	'options' => array(
                		'messages' => array(
                			'isEmpty' => 'Bạn chưa nhập tên đăng nhập'
                		),
                	)
                ),
                array(
                    'name'    => 'StringLength',
                	'break_chain_on_failure' => true,
                    'options' => array(
                        'min' => 4,
                    	'messages' => array(
							'stringLengthTooShort' => 'Tên đăng nhập phải có từ 4 đến 32 kí tự'
                    	)
                    ),
                ),
                array(
                    'name'    => 'Regex',
                	'break_chain_on_failure' => true,
                    'options' => array(
                        'pattern' => "/^[a-z0-9_-]{4,32}$/",
                    	'messages' => array(
							'regexNotMatch' => 'Chỉ chấp nhận các kí tự là chữ, chữ số, dấu - và dấu _'
                    	)
                    ),
                ),
                array(
                    'name'    => 'Db\NoRecordExists',
                    'options' => array(
                        'table' => 'users',
                        'field' => 'username',
                        'adapter' => \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter(),
                    	'messages' => array(
                    		'recordFound' => "Tên đăng nhập này đã được sử dụng"
                    	)
                    ),
                ),
            ),
        ));

        $this->add(array(
            'name'       => 'password',
            'validators' => array(
                array(
                    'name'    => 'NotEmpty',
                	'break_chain_on_failure' => true,
                	'options' => array(
                		'messages' => array(
                			'isEmpty' => 'Bạn chưa nhập mật khẩu'
                		)
                	)
                ),
                array(
                    'name'    => 'StringLength',
                	'break_chain_on_failure' => true,
                    'options' => array(
                        'min' => 6,
                    	'messages' => array(
							'stringLengthTooShort' => 'Mật khẩu phải có từ 6 kí tự trở lên'
                    	)
                    ),
                ),
            ),
        ));


        $this->add ( array (
            'name' => 'image_upload',
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

//         $this->add(array(
//             'name'       => 'password2',
//             'validators' => array(
//                 array(
//                     'name'    => 'NotEmpty',
//                 	'break_chain_on_failure' => true,
//                 	'options' => array(
//                 		'messages' => array(
//                 			'isEmpty' => 'Bạn chưa nhập xác nhận mật khẩu'
//                 		)
//                 	)
//                 ),
//                 array(
//                     'name'    => 'StringLength',
//                 	'break_chain_on_failure' => true,
//                     'options' => array(
//                         'min' => 6,
//                     	'messages' => array(
// 							'stringLengthTooShort' => 'Mật khẩu phải có từ 6 kí tự trở lên'
//                     	)
//                     ),
//                 ),
//                 array(
//                     'name'    => 'Identical',
//                 	'break_chain_on_failure' => true,
//                     'options' => array(
//                         'token' => 'password',
//                     	'messages' => array(
// 							'notSame' => 'Xác nhận mật khẩu không chính xác'
//                     	)
//                     ),
//                 ),
//             ),
//         ));

        $this->add(array(
            'name'       => 'fullName',
            'required' => false,
            'validators' => array(
                array(
                    'name'    => 'NotEmpty',
                	'break_chain_on_failure' => true,
                	'options' => array(
                		'messages' => array(
                			'isEmpty' => 'Bạn chưa nhập tên đầy đủ'
                		)
                	)
                ),
            ),
        ));
        $this->add(array(
        		'name'       => 'gender',
                'required' => false,
                'validators' => array(
        				array(
        						'name'    => 'NotEmpty',
        						'break_chain_on_failure' => true,
        						'options' => array(
        								'messages' => array(
        										'isEmpty' => 'Bạn chưa chọn giới tính '
        								)
        						)
        				),
        		),
        ));

        $this->add(array(
            'name'       => 'email',
            'validators' => array(
                array(
                    'name'    => 'NotEmpty',
                	'break_chain_on_failure' => true,
                	'options' => array(
                		'messages' => array(
                			'isEmpty' => 'Bạn chưa nhập Email'
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
                array(
                    'name'    => 'Db\NoRecordExists',
                    'options' => array(
                        'table' => 'users',
                        'field' => 'email',
                        'adapter' => \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter(),
                    	'messages' => array(
                    		'recordFound' => "Email này đã được sử dụng"
                    	)
                    ),
                ),
            ),
        ));

        $this->add(array(
            'name'       => 'mobile',
            'validators' => array(
                array(
                    'name'    => 'NotEmpty',
                	'break_chain_on_failure' => true,
                	'options' => array(
                		'messages' => array(
                			'isEmpty' => 'Bạn chưa nhập số điện thoại'
                		)
                	)
                ),
            ),
        ));

        $this->getEventManager()->trigger('init', $this);
    }
    public function setExcludedId($id) {
    	$this->remove ( 'username' );
    	$this->remove ( 'email' );
    	$this->add ( array (
    			'name' => 'username',
    			'filters' => array (
    					array (
    							'name' => 'StringTrim'
    					),
    					array (
    							'name' => 'StringToLower'
    					),
    					array (
    							'name' => '\Base\Filter\HTMLPurifier'
    					)
    			),
    			'validators' => array (
    					array (
    							'name' => 'NotEmpty',
    							'break_chain_on_failure' => true,
    							'options' => array (
    									'messages' => array (
    											'isEmpty' => 'Bạn chưa nhập tên đăng nhập'
    									)
    							)
    					),
    					array (
    							'name' => 'Db\NoRecordExists',
    							'options' => array (
    									'table' => 'users',
    									'field' => 'username',
    									'field' => 'email',
    									'exclude' => array (
    											'field' => 'id',
    											'value' => $id
    									),
    									'adapter' => \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter (),
    									'messages' => array (
    											'recordFound' => "Tên người dùng này đã được sử dụng"
    									)
    							)
    					)
    			)
    	) );
    }
}