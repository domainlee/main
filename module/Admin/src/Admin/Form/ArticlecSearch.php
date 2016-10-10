<?php
namespace Admin\Form;
use Base\Form\ProvidesEventsForm;

class ArticlecSearch extends ProvidesEventsForm{
	
public function __construct() {
		parent::__construct ();
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
						'class' => 'tb m-wrap medium',
						'id' => 'id',
						'placeholder' => 'Id' 
				),
				'options' => array (
						'label' => '',
						'decorator' => array (
						)
				) 
		) );
		$this->add ( array (
				'name' => 'name',
				'attributes' => array (
						'type' => 'text',
						'class' => 'tb m-wrap medium',
						'id' => 'name',
						'placeholder' => 'Tên thể loại' 
				),
				'options' => array (
						'label' => '',
						'decorator' => array (
						)
				) 
		) );
		
		$this->add ( array (
				'name' => 'storeId',
				'type' => 'select',
				'attributes' => array (
                    'id' => 'storeId',
                    'class' => 'tb m-wrap medium',
                    'style' => 'margin: 0 5px 0 0'
                ),
				'options' => array (
						'value_options' => array (
								'' => '- Doanh nghiệp -'
						),
						'decorator' => array (
						)
				)
		) );
		
		$this->add ( array (
				'name' => 'submit',
				'attributes' => array (
						'type' => 'submit',
						'value' => 'Tìm kiếm',
						'class' => 'htmlBtn first btn btn-danger' 
				),
				'options' => array (
						'decorator' => array (
								'attributes' => array (
										'class' => 'btns' 
								) 
						) 
				) 
		) );
		
		$this->getEventManager ()->trigger ( 'init', $this );
	}

	public function setStoreIds($arr){
		if(!! $element = $this->get('storeId')){
			$element->setValueOptions(array(
					''=>'- Doanh nghiệp -'
			)+$arr);
		}
	}
}

	