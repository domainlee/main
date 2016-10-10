<?php
namespace Admin\Form;
use Base\Form\ProvidesEventsForm;

class Store extends ProvidesEventsForm{
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
			'name' => 'parentId',
			'type' => '\Base\Form\OptionalSelect',
			'attributes' => array(
				'id' => 'parentId',
			),
            'options' => array(
                'label' => 'Thể loại cha:',
            	'empty_option' => '- Thể loại cha -',
            	'decorator' => array(
            		'type' => 'li'
            	),
            ),
		));
		$this->add(array(
				'name' => 'name',
				'attributes' => array(
						'type'  => 'text',
						'class' => 'tb',
						'id' => 'name',
				),
				'options' => array(
						'label' => 'Tên doanh nghiệp:',
						'decorator' => array('type' => 'li'),
						'required' => true,
				),
		));
		
		$this->add ( array (
				'name' => 'image_upload',
				'attributes' => array (
						'type' => 'file',
						'class' => 'tb',
						'id' => 'image_upload'
				),
				'options' => array (
						'label' => 'Logo:',
						'decorator' => array (
								'type' => 'li'
						),
						'description' => 'ảnh không được 500kb và phải là dạng gif, png, jpeg, jpg.'
				)
		) );
		$this->add(array(
				'name' => 'username',
				'attributes' => array(
						'type'  => 'text',
						'class' => 'tb',
						'id' => 'username',
				),
				'options' => array(
						'label' => 'Tên người dùng:',
						'decorator' => array('type' => 'li'),
						'required' => true,
				),
		));

		$this->add(array(
				'name' => 'password',
				'attributes' => array(
						'type'  => 'text',
						'class' => 'tb',
						'id' => 'password',
				),
				'options' => array(
						'label' => 'Mật khẩu:',
						'decorator' => array('type' => 'li'),
						'required' => true,
				),
		));
		$this->add(array(
				'name' => 'email',
				'attributes' => array(
						'type'  => 'text',
						'class' => 'tb',
						'id' => 'email',
				),
				'options' => array(
						'label' => 'Email:',
						'decorator' => array('type' => 'li'),
						'required' => true,
				),
		));
		$this->add(array(
				'name' => 'address',
				'attributes' => array(
						'type'  => 'text',
						'class' => 'tb',
						'id' => 'address',
				),
				'options' => array(
						'label' => 'Địa chỉ:',
						'decorator' => array('type' => 'li'),
						'required' => true,
				),
		));
		$this->add(array(
				'name' => 'mobile',
				'attributes' => array(
						'type'  => 'text',
						'class' => 'tb',
						'id' => 'mobile',
				),
				'options' => array(
						'label' => 'Số điện thoại:',
						'decorator' => array('type' => 'li'),
						'required' => true,
				),
		));
		
		
		$model = new \Admin\Model\Store();
		$this->add ( array (
				'name' => 'status',
				'type' => 'select',
				'class' => 'tb',
				'attributes' => array (
						'id' => 'status'
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
	public function setParentIds($arr){
		if(!! $element = $this->get('parentId')){
			$element->setValueOptions(array(
				''=>'-Thể loại cha-'
				
			)+$arr);
		}
	}
	
}








