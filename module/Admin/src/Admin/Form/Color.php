<?php
namespace Admin\Form;
use Base\Form\ProvidesEventsForm;

class Color extends ProvidesEventsForm{
	public function __construct($name=null){
		parent::__construct($name);
		$this->setAttribute('method', 'post');
		$this->setAttribute('class', 'f');
		$this->setAttribute ( 'enctype', 'multipart/form-data' );
		$this->setOptions(array(
			'decorator' => array(
				'type' => 'ul'
			)
		));
		$this->add(array(
			'name' => 'id',
			'attributes' =>array(
				'type' => 'hidden',
				'class' => 'tb',
				'id' => 'id'
			)
		));
		$this->add(array(
				'name' => 'name',
				'attributes' => array(
						'type'  => 'text',
						'class' => 'tb',
						'id' => 'name',
				),
				'options' => array(
						'label' => 'Màu sắc:',
						'decorator' => array('type' => 'li'),
						'required' => true,
				),
		));
		
		$this->add(array(
				'name' => 'value',
				'attributes' => array(
						'type'  => 'text',
						'class' => 'tb color',
						'id' => 'value',
				),
				'options' => array(
						'label' => 'Giá trị:',
						'decorator' => array('type' => 'li'),
						'required' => true,
				),
		));

		$this->add(array(
				'name' => 'submit',
				'attributes' => array(
						'type'  => 'submit',
						'value' => 'Save',
						'id' => 'btnSubmit',
						'class' => 'htmlBtn first btn btn-danger'
				),
				'options' => array(
						'label' => ' ',
						'decorator' => array(
								'type' => 'li',
								'attributes' => array(
										'class' => 'btns'
								)
						)
				),
		));
		$this->add(array(
				'name' => 'reset',
				'attributes' => array(
						'type'  => 'reset',
						'value' => 'Reset',
						'id' => 'btnReset',
						'class' => 'btn btn-danger'
				),
				'options' => array(
						'label' => ' ',
						'decorator' => array(
								'type' => 'li',
								'attributes' => array(
										'class' => 'btns'
								)
						)
				),
		));
		
		$this->getEventManager()->trigger('init', $this);
	}
	
}








