<?php
namespace Admin\Form;

use Base\Form\ProvidesEventsForm;

class StoreSearch extends ProvidesEventsForm{
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
				'name' => 'name',
				'attributes' => array (
						'type' => 'text',
						'class' => 'tb wt',
						'id' => 'name',
						'placeholder' => 'Tên doanh nghiệp'
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
				'attributes' => array (
						'type' => 'text',
						'class' => 'tb wt',
						'id' => 'storeId',
						'placeholder' => 'Tên doanh nghiệp' 
				),
				'options' => array (
						'label' => '',
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
	
}