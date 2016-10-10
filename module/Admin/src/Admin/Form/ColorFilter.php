<?php

namespace Admin\Form;

use Base\InputFilter\ProvidesEventsInputFilter;

class ColorFilter extends ProvidesEventsInputFilter {
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
                			 'isEmpty' => 'Bạn chưa nhập màu sắc'
                		),
                	)
                ),
                array(
                    'name'    => 'Db\NoRecordExists',
                    'options' => array(
                        'table' => 'product_color',
                        'field' => 'name',
                        'adapter' => \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter(),
                    	'messages' => array(
                    		'recordFound' => "Màu sắc này đã được sử dụng"
                    	)
                    ),
                ),
            ),
        ));
		$this->add(array(
				'name' => 'value',
				'validators' => array(
						array(
								'name'    => 'NotEmpty',
								'break_chain_on_failure' => true,
								'options' => array(
										'messages' => array(
												'isEmpty' => 'Bạn chưa nhập giá trị'
										)
								)
						),
				),
		));
		
		$this->getEventManager()->trigger('init', $this);
	}
	public function setExcludedId($id) {
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
													'isEmpty' => 'Bạn chưa nhập màu sắc'
											),
									)
							),
							array(
									'name'    => 'Db\NoRecordExists',
									'options' => array(
											'table' => 'product_color',
											'field' => 'name',
	                    					'exclude' => array (
												'field' => 'id',
												'value' => $id
											),
											'adapter' => \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter(),
											'messages' => array(
													'recordFound' => "Màu sắc này đã được sử dụng"
											)
									),
							),
					),
			));
	}
}
