<?php

namespace Admin\Form;

use Base\InputFilter\ProvidesEventsInputFilter;

class ArticleFilter extends ProvidesEventsInputFilter {
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
                			 'isEmpty' => 'Bạn chưa nhập tên bài viết'
                		),
                	)
                ),
//                array(
//                    'name'    => 'Db\NoRecordExists',
//                    'options' => array(
//                        'table' => 'products',
//                        'field' => 'name',
//                        'adapter' => \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter(),
//                    	'messages' => array(
//                    		'recordFound' => "Tên bài viết này đã được sử dụng"
//                    	)
//                    ),
//                ),
            ),
        ));
		
		$this->add(array(
            'name'       => 'categoryId',
            'required' => false,
            'validators' => array(
                array(
                    'name'    => 'NotEmpty',
                    'break_chain_on_failure' => true,
                    'options' => array(
                        'messages' => array(
                            'isEmpty' => 'Bạn chưa chọn loại bài viết'
                        )
                    )
                ),
				),
		));
        $this->add(array(
            'name'       => 'storeId',
            'required' => false,

            'validators' => array(
                array(
                    'name'    => 'NotEmpty',
                    'break_chain_on_failure' => true,
                    'options' => array(
                        'messages' => array(
                            'isEmpty' => 'Bạn chưa nhập store'
                        )
                    )
                ),
            ),
        ));
		$this->add(array(
				'name' => 'description',
            'required' => false,

            'validators' => array(
						array(
								'name'    => 'NotEmpty',
								'break_chain_on_failure' => true,
								'options' => array(
										'messages' => array(
												'isEmpty' => 'Bạn chưa nhập tóm tắt'
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
				'name' => 'title',
            'required' => false,

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
                                        'isEmpty' => 'Bạn chưa nhập tiêu đề'
                                )
                        )
                    ),
				)
		) );
		$this->add ( array (
				'name' => 'content',
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
												'isEmpty' => 'Bạn chưa nhập nội dung'
										)
								)
						),
				)
		) );
		
		$this->add(array(
				'name' => 'status',
            'required' => false,

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
											'table' => 'articles',
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
