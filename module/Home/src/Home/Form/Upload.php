<?php

namespace Home\Form;

use Base\Form\ProvidesEventsForm;

class Upload extends ProvidesEventsForm {
	const ERROR_INVALID = "Tên đăng nhập hoặc mật khẩu không chính xác";
	const ERROR_LOCKED = "Tài khoản của bạn đã bị khóa";
	const ERROR_INACTIVE = "Tài khoản của bạn chưa được kích hoạt";

	public function showInvalidMessage($error = self::ERROR_INVALID) {
		$this->get('username')->setMessages(array($error));
	}
	

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
            'name' => 'email',
            'attributes' => array(
                'type'  => 'text',
            	'class' => 'tb text',
            	'id' => 'username',
            ),
            'options' => array(
                'label' => 'Email:',
            	'decorator' => array('type' => 'li')
            ),
        ));
        $this->add(array(
            'name' => 'phone',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'tb text',
                'id' => 'username',
            ),
            'options' => array(
                'label' => 'Điện thoại:',
                'decorator' => array('type' => 'li')
            ),
        ));

        $this->add(array(
            'name' => 'file',
            'attributes' => array(
                'type'  => 'file',
            	'class' => 'tb text',
            	'id' => 'password'
            ),
            'options' => array(
                'label' => 'file',
            	'decorator' => array('type' => 'li')
            ),
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Đăng nhập',
                'id' => 'btnSubmits',
            	'class' => 'htmlBtn first btn btn-danger'
            ),
            'options' => array(
            	'label' => '',
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