<?php

namespace Base\Form;

use Zend\Form\FormInterface;
use Zend\Form\Form;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Select;
use Zend\Form\Element\Text;
use Zend\Validator\IsInstanceOf;
use Zend\Form\Annotation\Validator;
//use Home\Model\Format;

class FormBase extends Form implements ServiceLocatorAwareInterface
{
    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * @author VanCK
     * @see \Zend\Form\Form::getData()
     */
    public function getData($flag = FormInterface::VALUES_NORMALIZED)
    {
		$data = parent::getData($flag);
		// populate default values
		foreach($this->getElements() as $element) {
			/* @var $element \Zend\Form\Element */
			if((!count($this->data) || !array_key_exists($element->getName(), $this->data))
			&& $element->getValue()) {
				$data[$element->getName()] = $element->getValue();
			}
		}
		return $data;
    }

    /**
     * @author KIenNN
     * get list of all error messages in only 1 level array
     * @return multitype:unknown |NULL
     */
    public function getErrorMessagesList(){
    	$errors = $this->getMessages();
    	if(count($errors)){
    		$result = [];
    		foreach ($errors as $elementName => $elementErrors){
    			foreach ($elementErrors as $errorMsg){
    				$result[] = $errorMsg;
    			}
    		}
    		return $result;
    	}
    	return null;
    }
}