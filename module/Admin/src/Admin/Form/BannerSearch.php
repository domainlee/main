<?php
namespace Admin\Form;

use Base\Form\ProvidesEventsForm;

class BannerSearch extends ProvidesEventsForm{
	public function __construct($name = null) {
		parent::__construct ( $name );
		$this->setAttribute ( 'method', 'get' );
		$this->setAttribute ( 'class', 'fFilter' );
		$this->setOptions ( array (
				'decorator' => array (
						'type' => 'ul'
				)
		) );
		$this->add ( array (
				'name' => 'id',
				'attributes' => array (
						'type' => 'text',
						'class' => 'tb nb input-mini',
						'id' => 'id',
						'placeholder' => 'Id'
				),
				'options' => array (
						'label' => '',
						'decorator' => array (
								'type' => 'li'
						)
				)
		) );
		$this->add ( array (
				'name' => 'storeId',
				'type' => 'select',
				'class' => 'tb',
				'attributes' => array (
                    'id' => 'storeId',
                    'style' => 'margin: 0 5px 0 0',
                    'class' => 'tb m-wrap medium',
				),
				'options' => array (
						'value_options' => array (
								'' => '- Doanh nghiệp -'
						),
						'decorator' => array (
								'type' => 'li'
						)
				)
		) );
		$this->add ( array (
				'name' => 'storeId',
				'type' => 'select',
				'class' => 'tb',
				'attributes' => array (
						'id' => 'storeId'
				),
				'options' => array (
						'value_options' => array (
								'' => '- Doanh nghiệp -'
						),
						'decorator' => array (
								'type' => 'li'
						)
				)
		) );
		$this->add ( array (
				'name' => 'submit',
				'attributes' => array (
						'type' => 'submit',
						'value' => 'Tìm Kiếm',
						'class' => 'htmlBtn first btn btn-danger'
				),
				'options' => array (
						'decorator' => array (
								'type' => 'li',
								'attributes' => array (
										'class' => 'btns'
								)
						)
				)
		) );
		
		
		$this->getEventManager ()->trigger ( 'init', $this );
	}
	public function setStoreIds($arr){
		if(!!$element = $this->get('storeId')){
			$element->setValueOptions(array(
				''=>'- Doanh nghiệp -'
			)+$arr);
		}
	}
}