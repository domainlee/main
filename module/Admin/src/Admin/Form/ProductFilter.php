<?php

namespace Admin\Form;

use Base\InputFilter\ProvidesEventsInputFilter;
use Zend\Validator\StringLength;


class ProductFilter extends ProvidesEventsInputFilter {
	public function __construct()
	{
		$this->add(array(
            'name'   => 'name',
            'filters'   => array(
                array('name' => 'StringTrim'),
            	array('name' => '\Base\Filter\HTMLPurifier')
            ),
            'validators' => array(
                array(
                    'name'    => 'NotEmpty',
                	'break_chain_on_failure' => true,
                	'options' => array(
                		'messages' => array(
                			 'isEmpty' => 'Bạn chưa nhập Tên sản phẩm'
                		),
                	)
                ),
                array(
                    'name'    => 'Db\NoRecordExists',
                    'options' => array(
                        'table' => 'products',
                        'field' => 'name',
                        'adapter' => \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter(),
                    	'messages' => array(
                    		'recordFound' => "Tên sản phẩm này đã được sử dụng"
                    	)
                    ),
                ),
            ),
        ));

		$this->add(array(
				'name'       => 'code',
				'validators' => array(
					array(
						'name'    => 'NotEmpty',
						'break_chain_on_failure' => true,
						'options' => array(
								'messages' => array(
										'isEmpty' =>'Bạn chưa nhập mã sản phẩm !'
								)
						)
					),
					array(
						'name'    => 'StringLength',
						'break_chain_on_failure' => true,
						'options' => array(
								'min' => 6,
								'messages' => array(
										'stringLengthTooShort' => 'Mã sản phẩm phải có từ 6 kí tự trở lên'
								)
						),
					),
				),
		));
		$this->add(array(
				'name'       => 'categoryId',
				'validators' => array(
                    array(
                        'name'    => 'NotEmpty',
                        'break_chain_on_failure' => true,
                        'options' => array(
                                'messages' => array(
                                        'isEmpty' => 'Bạn chưa chọn loại sản phẩm'
                                )
                        )
                    ),
				),
		));
		$this->add(array(
				'name' => 'intro',
				'validators' => array(
						array(
								'name'    => 'NotEmpty',
								'break_chain_on_failure' => true,
								'options' => array(
										'messages' => array(
												'isEmpty' => 'Bạn chưa nhập mô tả'
										)
								)
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
		$this->add ( array (
				'name' => 'quantity',
				'filters' => array (
						array (
								'name' => 'StringTrim'
						)
				),
				'validators' => array (
						array (
								'name' => 'NotEmpty',
								'break_chain_on_failure' => true,
								'options' => array (
										'messages' => array (
												'isEmpty' => 'Bạn chưa nhập Số lượng sản phẩm'
										)
								)
						),
						array (
								'name' => 'Digits',
								'break_chain_on_failure' => true,
								'options' => array (
										'messages' => array (
												\Zend\Validator\Digits::INVALID => 'Số lượng sản phẩm phải là số nguyên',
												\Zend\Validator\Digits::NOT_DIGITS => 'Số lượng phải là số nguyên'
										)
								)
						),
						array (
								'name' => 'GreaterThan',
								'break_chain_on_failure' => true,
								'options' => array (
										'messages' => array (
												\Zend\Validator\GreaterThan::NOT_GREATER => 'Số lượng phải lớn hơn 0'
										),
										'min' => - 1
								)
						)
				)
		) );

        $this->add ( array (
            'name' => 'price',
            'required' => true,
            'filters' => array (
                array ('name' => 'Digits'),
                array ('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'break_chain_on_failure' => true,
                    'options' => array(
                        'messages' => array(
                            'isEmpty' => 'Bạn chưa nhập giá trị'
                        )
                    )
                ),
                array(
                    'name'    => 'StringLength',
                    'break_chain_on_failure' => true,
                    'options' => array(
                        'messages' => array(
                            StringLength::INVALID => 'Giá trị phải là dạng số',
                        )
                    )
                ),

            ),
        ) );

        $this->add ( array (
            'name' => 'priceOld',
            'required' => false,
            'filters' => array (
                array ('name' => 'Digits'),
                array ('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'break_chain_on_failure' => true,
                    'options' => array(
                        'messages' => array(
                            'isEmpty' => 'Bạn chưa nhập giá trị'
                        )
                    )
                ),
                array(
                    'name'    => 'StringLength',
                    'break_chain_on_failure' => true,
                    'options' => array(
                        'messages' => array(
                            StringLength::INVALID => 'Giá trị phải là dạng số',
                        )
                    )
                ),

            ),
        ) );
//
//		$this->add ( array (
//				'name' => 'price',
//				'filters' => array (
//						array (
//								'name' => 'StringTrim'
//						)
//				),
//				'validators' => array (
//						array (
//								'name' => 'NotEmpty',
//								'break_chain_on_failure' => true,
//								'options' => array (
//										'messages' => array (
//												'isEmpty' => 'Bạn chưa nhập Số lượng loại sản phẩm'
//										)
//								)
//						),
//						array (
//								'name' => 'Digits',
//								'break_chain_on_failure' => true,
//								'options' => array (
//										'messages' => array (
//												\Zend\Validator\Digits::INVALID => 'Số lượng sản phẩm phải là số nguyên',
//												\Zend\Validator\Digits::NOT_DIGITS => 'Số lượng phải là số nguyên'
//										)
//								)
//						),
//						array (
//								'name' => 'GreaterThan',
//								'break_chain_on_failure' => true,
//								'options' => array (
//										'messages' => array (
//												\Zend\Validator\GreaterThan::NOT_GREATER => 'Số lượng phải lớn hơn 0'
//										),
//										'min' => - 1
//								)
//						)
//				)
//		) );

        $this->add(array(
            'name' => 'color',
            'required' => false,
            'validators' => array(
                array(
                    'name'    => 'NotEmpty',
                    'break_chain_on_failure' => false,
                    'options' => array(
                        'messages' => array(
                            'isEmpty' => 'Bạn chưa chọn loại sản phẩm'
                        )
                    )
                ),
            ),
        ));

		$this->add(array(
				'name' => 'status',
				'validators' => array(
						array(
								'name'    => 'NotEmpty',
								'break_chain_on_failure' => true,
								'options' => array(
										'messages' => array(
												'isEmpty' => 'Bạn chưa chọn trạng thái'
										)
								)
						),
				),
		));



		$this->getEventManager()->trigger('init', $this);
	}
public function setExcludedId($categoryId,$id = null) {
	$excludedStr = "categoryId = $categoryId";
    	if($id) {
    		$excludedStr .= " AND id != $id";
    	}
		$this->remove('name');
		$this->add(array(
				'name'   => 'name',
				'filters'   => array(
						array('name' => 'StringTrim'),
						array('name' => '\Base\Filter\HTMLPurifier')
				),
				'validators' => array(
						array(
								'name'    => 'NotEmpty',
								'break_chain_on_failure' => true,
								'options' => array(
										'messages' => array(
												'isEmpty' => 'Bạn chưa nhập tên sản phẩm'
										),
								)
						),
						array(
								'name'    => 'Db\NoRecordExists',
								'options' => array(
										'table' => 'products',
										'field' => 'name',
                    					'exclude' => $excludedStr,
										'adapter' => \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter(),
										'messages' => array(
												'recordFound' => "Tên sản phẩm này đã được sử dụng"
										)
								),
						),
				),
		));
	}
}
