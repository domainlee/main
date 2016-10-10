<?php
namespace Admin\Form;
use Base\Form\ProvidesEventsForm;
use Zend\Form\Element\Text;
use Zend\Form\Element\Select;

class Attr extends ProvidesEventsForm{
    public function __construct($serviceLocator = null, $option = null){
        parent::__construct('Attr');

//        $this->setServiceLocator ( $serviceLocator );

        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'f');
        $this->setAttribute ( 'enctype', 'multipart/form-data' );
        $filter = $this->getInputFilter();

        $type = new \Admin\Model\Attr();

        $types = new Select ('type');
        $this->add ( $types );
        $types->setLabel ('Màu size:');
        $types->setAttributes ( [
            'id' => 'selectattr'
        ]);
        $types->setValueOptions ( array (
            '' => ' Màu - Size ',
            \Admin\Model\Attr::COLOR => 'Màu sắc',
            \Admin\Model\Attr::SIZE => 'Size',
        ) );
        $filter->add(array(
            'name' => 'type',
            'required' => true,
            'validators' => array(
                array(
                    'name'    => 'NotEmpty',
                    'break_chain_on_failure' => true,
                    'options' => array(
                        'messages' => array(
                            'isEmpty' => 'Bạn chưa chọn màu sắc'
                        )
                    )
                ),

            ),
        ));

        $name = new Text ( 'name' );
        $name->setLabel ( 'Tên:' );
        $name->setAttributes ( [
            'maxlenght' => 50
        ]);
        $this->add ($name);
        $filter->add (array (
            'name' => 'name',
            'required' => true,
            'filter' => array (
                array ('name' => 'StringStrim')
            ),
            'validators' => array (
                array (
                    'name' => 'NotEmpty',
                    'break_chain_on_failure' => true,
                    'options' => array (
                        'messages' => array (
                            'isEmpty' => 'Bạn chưa nhập tên'
                        )
                    )
                )
            )
        ) );

        $colorCode = new Text ( 'colorCode' );
        $colorCode->setLabel ( 'Mã màu:' );
        $colorCode->setAttributes ( [
            'maxlenght' => 50,
            'class' => 'colorpicker-default'
        ]);
        $this->add ($colorCode);
        $filter->add (array (
            'name' => 'colorCode',
            'required' => false,
            'filter' => array (
                array ('name' => 'StringStrim')
            ),
            'validators' => array (
                array (
                    'name' => 'NotEmpty',
                    'break_chain_on_failure' => true,
                    'options' => array (
                        'messages' => array (
                            'isEmpty' => 'Bạn chưa lấy mã màu'
                        )
                    )
                )
            )
        ) );

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
                'type'  => 'submit',
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
}