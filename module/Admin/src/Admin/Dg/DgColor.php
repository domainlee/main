<?php
namespace Admin\Dg;

class DgColor extends \Base\Dg\Table{
	protected function init(){
		$headerArr = array(
			array(
				'label' => 'ID'
			),
			array(
				'label' => 'Màu sắc'
			),
			array(
					'label' => 'Giá trị'
			),
			array(
					'label' => 'Thêm'
			),
			array(
					'label' => 'Sửa'
			),
			array(
					'label' => 'Xóa'
			)
		);
		$this->headers = $headerArr;
		$rows = array ();
		foreach ( $this->dataSet as $item ) {
			$rows [] = array (
					array (
							'type' => 'text',
							'class' => 'id',
							'value' => $item->getId()
					),
					array(
							'type' => 'text',
							'value' => $item->getName(),
					),
					array (
							'type' => 'text',
							'value' => $item->getValue()
					),
					array (
							'type' => 'link',
							'value' => '',
							'tag' => 'span',
							'htmlOptions' => array (
									'title' => 'Thêm màu sắc'
							),
							'class' => 'clAct',
							'elementClass' => 'fa fa-plus',
							'href' => '/admin/product/addcolor'
					),
					array (
							'type' => 'link',
							'value' => '',
							'tag' => 'span',
							'htmlOptions' => array (
									'title' => 'Sửa màu sắc'
							),
							'class' => 'clAct',
							'elementClass' => 'fa fa-pencil',
							'href' => '/admin/product/editcolor/' . $item->getId ()
					),
					
					array(
							'type' => 'action',
							'value' => '',
							'htmlOptions'=> array('title' => 'Xóa màu sắc'),
							'class' => 'delete clAct',
							'tag' => 'span',
							'elementClass' => 'fa fa-trash-o',
							'href' => '/admin/product/deletecolor/'. $item->getId ()
					),
					
			);
		}
		$this->rows = $rows;
	}
}














