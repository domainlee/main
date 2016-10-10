<?php
namespace Admin\Form;
use Base\Form\ProvidesEventsForm;

class Position extends ProvidesEventsForm{
	public function __construct($name=null){
		parent::__construct($name);
		$this->setAttribute('method', 'post');
		$this->setAttribute('class', 'f');
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
		$this->add ( array (
				'name' => 'storeId',
				'type' => 'select',
				'class' => 'tb',
				'attributes' => array (
						'id' => 'storeId'
				),
				'options' => array (
						'label' => 'Doanh nghiệp:',
						'value_options' => array (
								'' => '- Doanh nghiệp -'
						),
						'decorator' => array (
								'type' => 'li'
						)
				)
		) );
		$this->add(array(
				'name' => 'name',
				'attributes' => array(
						'type'  => 'text',
						'class' => 'tb',
						'id' => 'name',
				),
				'options' => array(
						'label' => 'Tên vị trí:',
						'decorator' => array('type' => 'li'),
						'required' => true,
				),
		));
	
		$this->add(array(
				'name' => 'intro',
				'attributes' => array(
						'type'  => 'textarea',
						'class' => 'tb',
						'id' => 'title',
				),
				'options' => array(
						'label' => 'Tóm tắt:',
						'decorator' => array('type' => 'li'),
						'required' => true,
				),
		));

		$this->add(array(
				'name' => 'description',
				'attributes' => array(
						'type'  => 'textarea',
						'class' => 'tb',
						'id' => 'textarea_full',
						"rows" => "5",
						"cols" => "30",
				),
				'options' => array(
						'label' => 'Mô tả:',
						'decorator' => array('type' => 'li'),
						'required' => true,
				),
		));
		$model = new \Admin\Model\Position();
		$this->add ( array (
				'name' => 'status',
				'type' => 'select',
				'class' => 'tb',
				'attributes' => array (
						'id' => 'parentId'
				),
				'options' => array (
						'label' => 'Trạng thái:',
						'decorator' => array (
								'type' => 'li'
						),
						'value_options' => array (
								'' => '- Trạng thái -'
						)+ $model->getStatuses()
				)
		) );
		
		
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
	public function setStoreIds($arr){
		if(!!($element = $this->get('storeId'))){
			$element->setValueOptions(array(
					''=>'- Thể loại -'
			)+$arr);
		}
	
	}
}








