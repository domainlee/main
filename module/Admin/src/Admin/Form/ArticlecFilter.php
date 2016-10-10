<?php
namespace Admin\Form;

use Base\InputFilter\ProvidesEventsInputFilter;

class ArticlecFilter extends ProvidesEventsInputFilter{
	
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
												'isEmpty' => 'Bạn chưa nhập loại bài viết'
										),
								)
						),
						array(
								'name'    => 'Db\NoRecordExists',
								'options' => array(
										'table' => 'article_categories',
										'field' => 'name',
										'adapter' => \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter(),
										'messages' => array(
												'recordFound' => "Tên loại bài viết này đã được sử dụng"
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
				'name' => 'description',
				'validators' => array(
						array(
								'name'    => 'NotEmpty',
								'break_chain_on_failure' => true,
								'options' => array(
										'messages' => array(
												'isEmpty' => 'Bạn chưa nhập mô tả'
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
												'isEmpty' => 'Bạn chưa nhập loại bài viết'
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
												'recordFound' => "Tên loại bài viết này đã được sử dụng"
										)
								)
						)
				)
		) );
		}
}
