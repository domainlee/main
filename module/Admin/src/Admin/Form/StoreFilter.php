<?php
namespace Admin\Form;

use Base\InputFilter\ProvidesEventsInputFilter;

class StoreFilter extends ProvidesEventsInputFilter{
	
	public function __construct()
	{
		$this->add(array(
				'name'   => 'name',
				'filters'   => array(
						array('name' => 'StringTrim'),
						array('name' => '\Base\Filter\HTMLPurifier')
				),
				'validators' => array(
						array(
								'name'    => 'NotEmpty',
								'break_chain_on_failure' => true,
								'options' => array(
										'messages' => array(
												'isEmpty' => 'Bạn chưa nhập doanh nghiệp'
										),
								)
						),
						array(
								'name'    => 'Db\NoRecordExists',
								'options' => array(
										'table' => 'stores',
										'field' => 'name',
										'adapter' => \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter(),
										'messages' => array(
												'recordFound' => "Tên doanh nghiệp này đã được sử dụng"
										)
								),
						),
				),
		));
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
		$this->add(array(
				'name' => 'address',
				'validators' => array(
						array(
								'name'    => 'NotEmpty',
								'break_chain_on_failure' => true,
								'options' => array(
										'messages' => array(
												'isEmpty' => 'Bạn chưa nhập địa chỉ'
										)
								)
						),
				),
		));
		$this->add(array(
				'name' => 'username',
				'validators' => array(
						array(
								'name'    => 'NotEmpty',
								'break_chain_on_failure' => true,
								'options' => array(
										'messages' => array(
												'isEmpty' => 'Bạn chưa nhập tên đăng nhập'
										)
								)
						),
				),
		));
		$this->add(array(
				'name' => 'password',
				'validators' => array(
						array(
								'name'    => 'NotEmpty',
								'break_chain_on_failure' => true,
								'options' => array(
										'messages' => array(
												'isEmpty' => 'Bạn chưa nhập tên đăng nhập'
										)
								)
						),
				),
		));
		$this->add(array(
				'name'       => 'email',
				'validators' => array(
						array(
								'name'    => 'NotEmpty',
								'break_chain_on_failure' => true,
								'options' => array(
										'messages' => array(
												'isEmpty' => 'Bạn chưa nhập Email'
										)
								)
						),
						array(
								'name'    => 'EmailAddress',
								'break_chain_on_failure' => true,
								'options' => array(
										'messages' => array(
												'emailAddressInvalidFormat' => 'Địa chỉ email không hợp lệ'
										)
								)
						),
						array(
								'name'    => 'Db\NoRecordExists',
								'options' => array(
										'table' => 'stores',
										'field' => 'email',
										'adapter' => \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter(),
										'messages' => array(
												'recordFound' => "Email này đã được sử dụng"
										)
								),
						),
				),
		));
		$this->add(array(
				'name'       => 'mobile',
				'validators' => array(
						array(
								'name'    => 'NotEmpty',
								'break_chain_on_failure' => true,
								'options' => array(
										'messages' => array(
												'isEmpty' => 'Bạn chưa nhập số điện thoại'
										)
								)
						),
				),
		));
		$this->add(array(
				'name' => 'status',
				'validators' => array(
						array(
								'name'    => 'NotEmpty',
								'break_chain_on_failure' => true,
								'options' => array(
										'messages' => array(
												'isEmpty' => 'Bạn chưa chọn trạng thái'
										)
								)
						),
				),
		));
		
		$this->getEventManager()->trigger('init', $this);
		}
public function setExcludedId($id) {
		$this->remove ( 'name' );
		$this->remove('email');
		$this->add ( array (
				'name' => 'name',
				'filters' => array (
						array (
								'name' => 'StringTrim'
						),
						array (
								'name' => 'StringToLower'
						),
						array (
								'name' => '\Base\Filter\HTMLPurifier'
						)
				),
				'validators' => array (
						array (
								'name' => 'NotEmpty',
								'break_chain_on_failure' => true,
								'options' => array (
										'messages' => array (
												'isEmpty' => 'Bạn chưa nhập doanh nghiệp'
										)
								)
						),
						array (
								'name' => 'Db\NoRecordExists',
								'options' => array (
										'table' => 'article_categories',
										'field' => 'name',
										'exclude' => array (
												'field' => 'id',
												'value' => $id
										),
										'adapter' => \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter (),
										'messages' => array (
												'recordFound' => "Tên doanh nghiệp này đã được sử dụng"
										)
								)
						)
				)
		) );
		}
}
