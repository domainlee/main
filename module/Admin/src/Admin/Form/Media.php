<?php
namespace Admin\Form;
use Base\Form\ProvidesEventsForm;
use Zend\Form\Element\Text;
use Zend\Form\Element\Select;
use Zend\Form\Element\Textarea;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\File;
use Zend\Validator\InArray;
use Zend\Validator\IsInstanceOf;
use Zend\Form\Annotation\Validator;


class Media extends ProvidesEventsForm{

	public function __construct($name=null){

		parent::__construct($name);

        $filter = $this->getInputFilter();

        $this->setAttribute('method', 'post');
		$this->setAttribute('class', 'f');
		$this->setAttribute ( 'enctype', 'multipart/form-data' );
		$this->setOptions(array(
			'decorator' => array(
				'type' => 'ul'
			)
		));

        $imagemulti = new File('imagemulti');
        $imagemulti->setLabel('ảnh:');
        $this->add($imagemulti);

        $filter->add(array(
            'name' => 'imagemulti',
            'type'       => 'Zend\InputFilter\FileInput',
            'required' => false,
            'allowEmpty' => true,
            'validators' => array(
                array(
                    'name'    => 'NotEmpty',
                    'break_chain_on_failure' => true,
                    'options' => array(
                        'messages' => array(
                            'isEmpty' => 'Bạn chưa chọn file'
                        )
                    )
                ),
                array(
                    'name'    => 'File\Size',
                    'break_chain_on_failure' => true,
                    'options' => array(
                        'max' => '3MB',
                        'messages' => array(
                            \Zend\Validator\File\Size::TOO_BIG => 'File upload phải < 3Mb'
                        )
                    )
                ),
                array(
                    'name'    => 'File\Extension',
                    'break_chain_on_failure' => true,
                    'options' => array(
                        'extension' => array('png','jpeg','jpg'),
                        'messages' => array(
                            \Zend\Validator\File\Extension::FALSE_EXTENSION => 'File upload phải là file ảnh: png, jpeg, jpg'
                        )
                    )
                ),
            ),
        ));

        $this->add(array(
				'name' => 'submit',
				'attributes' => array(
						'type'  => 'submit',
						'value' => 'Lưu',
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
						'value' => 'Hủy',
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








