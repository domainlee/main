<?php
namespace Admin\Dg;

class DgPosition extends \Base\Dg\Table{
	protected function init(){
		$headerArr = array(
			array(
				'label' => 'ID'
			),
			array(
				'label' => 'Vị trí'
			),
			array(
					'label' => 'Mô tả'
			),
			array(
					'label' => 'Tóm tắt'
			),
			array(
					'label' => 'Trạng thái'
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
					array (
							'type' => 'text',
							'value' => $item->getName()
					),
					
					array (
							'type' => 'text',
							'value' => $item->getDescription(),
							'class' => 'intro'
					),
					
					array (
							'type' => 'text',
							'value' => $item->getIntro(),
							'class' => 'intro'
					),
					array (
							'type' => 'link',
							'value' => '',
							'tag' => 'span',
							'htmlOptions'=>array('title'=>$item->getStatus()? 'Đang hoạt động':'Ngừng hoạt động'),
							'class' => 'changeactive clAct',
							'elementClass' => $item->getStatus()? 'fa fa-check icon-green' : ' fa fa-minus-circle icon-red',
							'href' => '/admin/position/changeactive/'.$item->getId(),
					),
					array (
							'type' => 'link',
							'value' => '',
							'tag' => 'span',
							'htmlOptions' => array (
									'title' => 'Thêm vị trí'
							),
							'class' => 'clAct',
							'elementClass' => 'fa fa-plus',
							'href' => '/admin/position/add'
					),
					array (
							'type' => 'link',
							'value' => '',
							'tag' => 'span',
							'htmlOptions' => array (
									'title' => 'Sửa vị trí'
							),
							'class' => 'clAct',
							'elementClass' => 'fa fa-pencil',
							'href' => '/admin/position/edit/'.$item->getId ()
					),
					
					array(
							'type' => 'action',
							'value' => '',
							'htmlOptions'=> array('title' => 'Xóa bài viết'),
							'class' => 'delete clAct',
							'tag' => 'span',
							'elementClass' => 'fa fa-trash-o',
							'href' => '/admin/position/delete/'.$item->getId ()
					),
// 					array(
// 							'type' => 'action',
// 							'value' => '',
// 							'htmlOptions'=> array('title' => 'Xóa tài khoản - gian hàng'),
// 							'class' => 'clAct delete',
// 							'tag' => 'span',
// 							'elementClass' => 'icon-trash',
// 							'href' => '/account/account/deluser?userId='.$accountUser->getUserId()
// 							.'&accountId='.$accountUser->getAccountId(),
// 					),
					
			);
		}
		$this->rows = $rows;
	}
}














