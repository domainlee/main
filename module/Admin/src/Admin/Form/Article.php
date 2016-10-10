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


class Article extends ProvidesEventsForm{

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

        $type = new Hidden('type');
        $this->add($type);
        $filter->add(array(
            'name' => 'type',
            'required' => false
        ));
        $tag = new Text('tag');
        $this->add($tag);

        $filter->add(array(
            'name' => 'tag',
            'required' => false
        ));


        $title = new Text('title');
        $title->setLabel('Tiêu đề:');
        $this->add($title);

        $filter->add(array(
            'name' => 'title',
            'attributes' => array(
                'class' => 'tb',
                'id' => 'title',
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

        $title1 = new Text('title1');
        $title1->setLabel('Tiêu đề 1:');
        $this->add($title1);

        $filter->add(array(
            'name' => 'title1',
            'attributes' => array(
                'class' => 'tb',
                'id' => 'title1',
            ),
            'required' => false,
            'options' => array(
                'label' => 'Tiêu đề 1:',
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

        $link = new Text('link');
        $link->setLabel('Link:');
        $this->add($link);

        $filter->add(array(
            'name' => 'link',
            'attributes' => array(
                'class' => 'tb',
                'id' => 'link',
            ),
            'required' => false,
            'options' => array(
                'label' => 'Link:',
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
        $description->setLabel('Tóm tắt:');
        $this->add($description);

        $filter->add(array(
            'name' => 'description',
            'attributes' => array(
                'class' => 'tb ckeditor',
                'id' => 'textarea_full1',
                "rows" => "5",
                "cols" => "30",
            ),
            'required' => false,
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
                            'isEmpty' => 'Bạn chưa nhập mô tả'
                        )
                    )
                )
            )
        ));

        $content = new Textarea('content');
        $content->setLabel('Nội dung:');
        $this->add($content);

        $filter->add(array(
            'name' => 'content',
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
                            'isEmpty' => 'Bạn chưa nhập mô tả'
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

        $categoryId = new Select('categoryId');
        $categoryId->setLabel('Danh mục:');
        $this->add($categoryId);
        $filter->add ( array (
				'name' => 'categoryId',
				'class' => 'tb',
				'attributes' => array (
						'id' => 'categoryId'
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

        $image_upload = new File('image_upload');
        $image_upload->setLabel('ảnh:');
        $this->add($image_upload);

        $filter->add(array(
            'name' => 'image_upload',
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

//        $imagemulti = new File('imagemulti');
//        $imagemulti->setLabel('ảnh:');
//        $this->add($imagemulti);
//
//        $filter->add(array(
//            'name' => 'imagemulti',
//            'type'       => 'Zend\InputFilter\FileInput',
//            'required' => false,
//            'allowEmpty' => true,
//            'validators' => array(
//                array(
//                    'name'    => 'NotEmpty',
//                    'break_chain_on_failure' => true,
//                    'options' => array(
//                        'messages' => array(
//                            'isEmpty' => 'Bạn chưa chọn file'
//                        )
//                    )
//                ),
//                array(
//                    'name'    => 'File\Size',
//                    'break_chain_on_failure' => true,
//                    'options' => array(
//                        'max' => '3MB',
//                        'messages' => array(
//                            \Zend\Validator\File\Size::TOO_BIG => 'File upload phải < 3Mb'
//                        )
//                    )
//                ),
//                array(
//                    'name'    => 'File\Extension',
//                    'break_chain_on_failure' => true,
//                    'options' => array(
//                        'extension' => array('png','jpeg','jpg'),
//                        'messages' => array(
//                            \Zend\Validator\File\Extension::FALSE_EXTENSION => 'File upload phải là file ảnh: png, jpeg, jpg'
//                        )
//                    )
//                ),
//            ),
//        ));

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
		if(!!($element = $this->get('categoryId'))){
			$element->setValueOptions(array(
					''=>'- Danh mục -'
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








