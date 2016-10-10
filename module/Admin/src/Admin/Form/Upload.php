<?php
namespace Admin\Form;
use Base\Form\ProvidesEventsForm;

class Upload extends ProvidesEventsForm{
	public function __construct($name=null){
		parent::__construct($name);
		$this->setAttribute('method', 'post');
		$this->setAttribute('class', 'f');
		$this->setAttribute ( 'enctype', 'multipart/form-data' );
		$this->setOptions(array(
				'decorator'=>array(
						'type'=>'ul'
				)
		));
		$this->add ( array (
				'name' => 'image_upload',
				'attributes' => array (
						'type' => 'file',
						'class' => 'tb',
						'id' => 'image_upload'
				),
				'options' => array (
						'label' => 'Ảnh đại diện:',
						'decorator' => array (
								'type' => 'li'
						),
						'description' => 'ảnh không được 500kb và phải là dạng gif, png, jpeg, jpg.'
				)
		) );
		$this->getEventManager()->trigger('init', $this);
	}
}

