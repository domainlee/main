<?php
namespace Base\Form;

class OptionalSelect extends \Zend\Form\Element\Select {

	public function getInputSpecification() {
		$inputSpecification = parent::getInputSpecification();
		$inputSpecification['required'] = false;//isset($this->attributes['required']) && $this->attributes['required'];
		return $inputSpecification;
	}

}