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
use Zend\Validator\StringLength;
use Base\Form\FormBase;


class Banner extends FormBase{

    public function __construct($sl, $name = null){

        parent::__construct($name);

        $this->setServiceLocator($sl);

        $filter = $this->getInputFilter();

        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'f');
        $this->setAttribute ( 'enctype', 'multipart/form-data' );
        $this->setOptions(array(
            'decorator' => array(
                'type' => 'ul'
            )
        ));

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
                'label' => 'Tiêu:',
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
            ),
            'required' => false,
            'options' => array(
                'label' => 'Tóm tắt:',
                'decorator' => array('type' => 'li'),
            ),
            'filter' => array(array('name'=>'StringStrim')),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'break_chain_on_failure' => true,
                    'options' => array(
                        'messages' => array(
                            'isEmpty' => 'Bạn chưa nhập tóm tắt'
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

//        $categoryId = new Select('categoryId');
//        $categoryId->setLabel('Danh mục:');
//        $this->add($categoryId);
//        $filter->add ( array (
//            'name' => 'categoryId',
//            'class' => 'tb',
//            'attributes' => array (
//                'id' => 'categoryId'
//            ),
//            'options' => array (
//                'label' => 'Danh mục:',
//                'value_options' => array (
//                    '' => '- Danh mục -'
//                ),
//                'decorator' => array (
//                    'type' => 'li'
//                )
//            ),
//            'filter' => array(array('name'=>'StringStrim')),
//            'validators' => array(
//                array(
//                    'name' => 'NotEmpty',
//                    'break_chain_on_failure' => true,
//                    'options' => array(
//                        'messages' => array(
//                            'isEmpty' => 'Bạn chưa chọn danh mục'
//                        )
//                    )
//                )
//            )
//        ) );

        $position = new \Admin\Model\Banner();

        $status = new Select('positionId');
        $status->setLabel('Vị trí:');
        $this->add($status);
        $status->setValueOptions([
                '' => '- Vị trí -',
            ]+ $position->getPosition());

        $filter->add ( array (
            'name' => 'positionId',
            'class' => 'tb',
            'required' => false,
            'attributes' => array(
                'id' => 'positionId'
            ),
            'options' => array (
                'label' => 'Trạng thái:',
                'decorator' => array (
                    'type' => 'li'
                ),
            )
        ) );

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
        ));

        $video = new Text('video');
        $video->setLabel('Video:');
        $this->add($video);

        $filter->add(array(
            'name' => 'video',
            'attributes' => array(
                'class' => 'tb',
                'id' => 'video',
            ),
            'required' => false,
            'options' => array(
                'label' => 'Video:',
                'decorator' => array('type' => 'li'),
            ),
        ));

        $status = new Select('status');
        $status->setLabel('Trạng thái:');
        $this->add($status);
        $status->setValueOptions([
                '' => '- Lựa chọn -',
            ]+ $position->getStatuses());

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

        $type1 = new Hidden('images');
        $this->add($type1);
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

//        $this->getEventManager()->trigger('init', $this);
    }


//    public function setCategoryIds($array){
//        if(!!($element = $this->get('categoryId'))){
//            $element->setValueOptions(array(
//                    ''=>'- Thể loại -'
//                )+ $array);
//        }
//    }

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
