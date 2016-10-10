<?php

namespace User\Form;

use Zend\ServiceManager\ServiceManager;
use Base\Form\ProvidesEventsForm;
use Zend\Form\Form;
use Zend\Form\Element;

class Signup extends ProvidesEventsForm
{
	
    /**
     * @param null|string $name
     */
    public function __construct($name = null)
    {
        parent::__construct($name);
        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'f');
        $this->setOptions(array(
        	'decorator' => array(
        		'type' => 'ul',
        	)
        ));

        $this->add(array(
            'name' => 'username',
            'attributes' => array(
                'type'  => 'text',
            	'class' => 'tb validate[required,minSize[4],maxSize[32]]',
            	'id' => 'username',
                'placeholder' => 'Tài khoản'
            ),
            'options' => array(
                'label' => 'Tên đăng nhập:',
            	'decorator' => array('type' => 'li')
            ),
        ));

        $this->add(array(
            'name' => 'password',
            'attributes' => array(
                'type'  => 'password',
            	'class' => 'tb validate[required,minSize[6]]',
            	'id' => 'password',
                'placeholder' => 'Mật khẩu'
            ),
            'options' => array(
                'label' => 'Mật khẩu:',
            	'decorator' => array('type' => 'li')
            ),
        ));

//        $this->add ( array (
//            'name' => 'image_upload',
//            'attributes' => array (
//                'type' => 'file',
//                'class' => 'tb',
//                'id' => 'image_upload'
//            ),
//            'options' => array (
//                'label' => 'Ảnh đại diện:',
//                'decorator' => array (
//                    'type' => 'li'
//                ),
//                'description' => 'ảnh không được 500kb và phải là dạng gif, png, jpeg, jpg.'
//            )
//        ) );


//         $this->add(array(
//             'name' => 'password2',
//             'attributes' => array(
//                 'type'  => 'password',
//             	'class' => 'tb validate[required,minSize[6],equals[password]]',
//             	'id' => 'password2'
//             ),
//             'options' => array(
//                 'label' => 'Xác nhận mật khẩu:',
//             	'decorator' => array('type' => 'li')
//             ),
//         ));
        $this->add(array(
        		'name' => 'email',
        		'attributes' => array(
        				'type'  => 'text',
        				'class' => 'tb validate[required,custom[email]]',
        				'id' => 'email',
        				'placeholder'=>'Địa chỉ Email của bạn',
        		),
        		'options' => array(
        				'label' => 'Email:',
        				'decorator' => array('type' => 'li')
        		),
        ));
        
//        $this->add(array(
//            'name' => 'fullName',
//            'attributes' => array(
//                'type'  => 'text',
//            	'class' => 'tb validate[required]',
//            	'id' => 'fullName'
//            ),
//            'options' => array(
//                'label' => 'Tên đầy đủ:',
//            	'decorator' => array('type' => 'li')
//            ),
//        ));
//        $model = new \User\Model\User();
//        $this->add(array(
//        	'name' => 'gender',
//        	'type' => 'select',
//        	'class' =>'tb',
//        	'attributes' => array(
//        		'id' => 'gender'
//        	),
//        	'options' => array(
//        		'label' => 'Giới tính:',
//        		'decorator' => array('type' => 'li'),
//        		'value_options' => array (
//        			 '' => '- Giới tính -'
//        		)+ $model->getGenders()
//        	)
//        ));

        $this->add(array(
            'name' => 'mobile',
            'attributes' => array(
                'type'  => 'text',
            	'class' => 'tb validate[required,custom[phone]]',
            	'id' => 'mobile',
                'placeholder'=>'Số điện thoại',
            ),
            'options' => array(
                'label' => 'Mobile:',
            	'decorator' => array('type' => 'li')
            ),
        ));

		
//        $this->add(array(
//            'name' => 'address',
//            'attributes' => array(
//                'type'  => 'text',
//            	'class' => 'tb',
//            	'id' => 'address'
//            ),
//            'options' => array(
//                'label' => 'Địa chỉ:',
//            	'decorator' => array(
//            		'type' => 'li'
//            	)
//            ),
//        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Đăng ký',
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

        $this->getEventManager()->trigger('init', $this);
    }
}