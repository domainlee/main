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


class Page extends ProvidesEventsForm{

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

//        $id = new Hidden('id');
//        $this->add($id);
//        $filter->add(array(
//            'name' => 'id',
//            'required' => false
//        ));

        $title = new Text('name');
        $title->setLabel('Tiêu đề:');
        $this->add($title);

        $filter->add(array(
            'name' => 'name',
            'attributes' => array(
                'class' => 'tb',
                'id' => 'name',
            ),
            'required' => true,
            'options' => array(
                'label' => 'Tiêu đề:',
                'decorator' => array('type' => 'li'),
            ),
            'filter' => array(array('name'=>'StringStrim')),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'break_chain_on_failure' => true,
                    'options' => array(
                        'messages' => array(
                            'isEmpty' => 'Bạn chưa nhập tiêu đề'
                        )
                    )
                )
            )
        ));

        $description = new Textarea('description');
        $description->setLabel('Nội dung:');
        $this->add($description);

        $filter->add(array(
            'name' => 'description',
            'attributes' => array(
                'class' => 'tb ckeditor',
                'id' => 'textarea_full',
                "rows" => "5",
                "cols" => "30",
            ),
            'required' => true,
            'options' => array(
                'label' => 'Nội dung:',
                'decorator' => array('type' => 'li'),
            ),
            'filter' => array(array('name'=>'StringStrim')),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'break_chain_on_failure' => true,
                    'options' => array(
                        'messages' => array(
                            'isEmpty' => 'Bạn chưa nhập nội dung'
                        )
                    )
                )
            )
        ));

        $storeId = new Select('storeId');
        $storeId->setLabel('Store:');
        $this->add($storeId);
        $filter->add ( array (
            'name' => 'storeId',
            'class' => 'tb',
            'attributes' => array (
                'id' => 'storeId'
            ),
            'options' => array (
                'label' => 'Store:',
                'value_options' => array (
                    '' => '- Store -'
                ),
                'decorator' => array (
                    'type' => 'li'
                )
            ),
            'filter' => array(array('name'=>'StringStrim')),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'break_chain_on_failure' => true,
                    'options' => array(
                        'messages' => array(
                            'isEmpty' => 'Bạn chưa chọn Store'
                        )
                    )
                )
            )
        ) );

        $parentId = new Select('parentId');
        $parentId->setLabel('Lựa chọn:');
        $this->add($parentId);
        $filter->add ( array (
				'name' => 'parentId',
				'class' => 'tb',
                'required' => false,
                'attributes' => array (
                    'id' => 'parentId'
				),
				'options' => array (
                    'label' => 'Thể loại:',
                    'value_options' => array (
                            '' => '- Thể loại -'
                    ),
                    'decorator' => array (
                            'type' => 'li'
                    )
				),
                'filter' => array(array('name'=>'StringStrim')),
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'break_chain_on_failure' => true,
                        'options' => array(
                            'messages' => array(
                                'isEmpty' => 'Bạn chưa chọn danh mục'
                            )
                        )
                    )
                )
		) );


        $model = new \Admin\Model\Article();

        $status = new Select('status');
        $status->setLabel('Trạng thái:');
        $this->add($status);
        $status->setValueOptions([
            '' => '- Trạng thái -',
        ]+ $model->getStatuses());

        $filter->add ( array (
				'name' => 'status',
				'class' => 'tb',
                'required' => false,
				'attributes' => array (
						'id' => 'parentId'
				),
				'options' => array (
                    'label' => 'Trạng thái:',
                    'decorator' => array (
                            'type' => 'li'
                    ),
				)
		) );

        $type = new Hidden('images');
        $this->add($type);
        $filter->add(array(
            'name' => 'images',
            'required' => false
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

	public function setCategoryIds($array){
		if(!!($element = $this->get('parentId'))){
			$element->setValueOptions(array(
					''=>'- Trang -'
			)+ $array);
		}
	}

	public function setStoreIds($arr){
		if(!!($element = $this->get('storeId'))){
			$element->setValueOptions(array(
				''=>'- Store -'
			)+$arr);
		}
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








