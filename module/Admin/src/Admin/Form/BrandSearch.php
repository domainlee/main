<?php
namespace Admin\Form;

use Base\Form\ProvidesEventsForm;

class BrandSearch extends ProvidesEventsForm{
    public function __construct($name = null) {
        parent::__construct ('ProductSearch');
        $this->setAttribute ( 'method', 'get' );
        $this->setAttribute ( 'class', 'fFilter' );
        $this->setOptions ( array (
            'decorator' => array (
                'type' => 'ul'
            )
        ) );
        $this->add ( array (
            'name' => 'id',
            'attributes' => array (
                'type' => 'text',
                'class' => 'tb m-wrap medium',
                'id' => 'id',
                'placeholder' => 'Id'
            ),
            'options' => array (
                'label' => '',
                'decorator' => array (
                    'type' => ''
                )
            )
        ) );
        $this->add ( array (
            'name' => 'name',
            'attributes' => array (
                'type' => 'text',
                'class' => 'tb m-wrap medium',
                'id' => 'name',
                'placeholder' => 'Tiêu đề'
            ),
            'options' => array (
                'label' => '',
                'decorator' => array (
                    'type' => ''
                )
            )
        ) );
        if (isset($name['isAdmin']) && $name['isAdmin']){
            $this->add ( array (
                'name' => 'storeId',
                'type' => 'select',
                'class' => 'tb',
                'attributes' => array (
                    'id' => 'storeId',
                    'style' => 'margin: 0 5px 0 0',
                    'class' => 'tb m-wrap medium',
                ),
                'options' => array (
                    'value_options' => array (
                        '' => '- Doanh nghiệp -'
                    ),
                    'decorator' => array (
                        'type' => ''
                    )
                )
            ) );
        }
        $this->add ( array (
            'name' => 'submit',
            'attributes' => array (
                'type' => 'submit',
                'value' => 'Tìm Kiếm',
                'class' => 'htmlBtn first btn btn-danger'
            ),
            'options' => array (
                'decorator' => array (
                    'type' => '',
                    'attributes' => array (
                        'class' => 'btns'
                    )
                )
            )
        ) );


        $this->getEventManager ()->trigger ( 'init', $this );
    }
    public function setStoreIds($arr){
        if(!!$element = $this->get('storeId')){
            $element->setValueOptions(array(
                    ''=>'- Doanh nghiệp -'
                )+$arr);
        }
    }
}