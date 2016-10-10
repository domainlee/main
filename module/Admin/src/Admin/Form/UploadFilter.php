<?php
namespace Admin\Form;

use Base\InputFilter\ProvidesEventsInputFilter;

class UploadFilter extends ProvidesEventsInputFilter{

	public function __construct(){
		$this->add ( array (
				'name' => 'image_upload',
				'required' => false,
				'validators' => array (
						array (
								'name' => 'File_Size',
								'break_chain_on_failure' => true,
								'options' => array (
										'messages' => array (
												\Zend\Validator\File\Size::TOO_BIG => 'Ảnh không được vượt quá 500kb'
										),
										'max' => 512000, // 500kb
										array (
												'name' => 'File_MimeType',
												'break_chain_on_failure' => true,
												'options' => array (
														'messages' => array (
																\Zend\Validator\File\MimeType::FALSE_TYPE => 'Ảnh phải đúng định dạng gif, jpg, jpeg, png.'
														),
														'mimeType' => 'image/gif,image/jpg,image/jpeg,image/png'
												)
										)
								)
						)
				)
		) );
		$this->getEventManager()->trigger('init', $this);
	}
}
