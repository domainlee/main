<?php

namespace User\Form;

use Base\Form\ProvidesEventsForm;

class ChangePassword extends ProvidesEventsForm
{
	const ERROR_INVALID = "Mật khẩu cũ không chính xác";

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
        $this->setAttribute('class', 'profile');
        $this->setOptions(array(
        	'decorator' => array(
        		'type' => 'ul',
        	)
        ));
        $this->add(array(
        		'name' => 'oldpassword',
        		'attributes' => array(
        				'type'  => 'password',
        				'class' => 'validate[required],minSize[6]',
        				'id' => 'oldpassword'
        		),
        		'options' => array(
        				'label' => 'Mật khẩu cũ:',
        				'decorator' => array('type' => 'li')
        		),
        ));

        $this->add(array(
            'name' => 'newpassword',
            'attributes' => array(
                'type'  => 'password',
            	'class' => 'validate[required],minSize[6]',
            	'id' => 'newpassword'
            ),
            'options' => array(
                'label' => 'Mật khẩu mới:',
            	'decorator' => array('type' => 'li')
            ),
        ));
        $this->add(array(
        		'name' => 'repassword',
        		'attributes' => array(
        				'type'  => 'password',
        				'class' => 'validate[required],minSize[6],equals[newpassword]',
        				'id' => 'repassword'
        		),
        		'options' => array(
        				'label' => 'Nhập lại mật khẩu mới:',
        				'decorator' => array('type' => 'li')
        		),
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Xác nhận',
                'id' => 'btnSubmit',
            	'class' => 'htmlBtn first'
            ),
            'options' => array(
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