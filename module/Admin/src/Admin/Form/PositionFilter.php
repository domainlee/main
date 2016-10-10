<?php

namespace Admin\Form;

use Base\InputFilter\ProvidesEventsInputFilter;

class PositionFilter extends ProvidesEventsInputFilter {
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
                			 'isEmpty' => 'Bạn chưa nhập vị trí'
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
                    		'recordFound' => "vị trí này đã được sử dụng"
                    	)
                    ),
                ),
            ),
        ));
	
		$this->add(array(
				'name' => 'description',
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
				'name' => 'intro',
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
												'isEmpty' => 'Bạn chưa nhập tóm tắt'
										)
								)
						),	
				)
		) );
		
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
	public function setExcludedId($id) {
		$excludedStr = "id = $id";
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
													'isEmpty' => 'Bạn chưa nhập vị trí'
											),
									)
							),
							array(
									'name'    => 'Db\NoRecordExists',
									'options' => array(
											'table' => 'banners',
											 'field' => 'name',
	                    					'exclude' => $excludedStr,
											'adapter' => \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter(),
											'messages' => array(
													'recordFound' => "vị trí này đã được sử dụng"
											)
									),
							),
					),
			));
	}
}
