<?php
namespace User\Dg;

class DgUser extends \Base\Dg\Table {
	protected function init(){
		$headerArr = array(
				array(
						'label' => 'STT'
				),
				array(
						'label' => 'Tên đăng nhập'
				),
				array(
						'label' => 'Mật khẩu'
				),
				array(
						'label' => 'Họ tên'
				),
				array(
						'label' => 'Email'
				),
				array(
						'label' => 'Ảnh đại diện'
				),
				array(
						'label' => 'Quyền'
				),
				array(
						'label' => 'Trạng thái'
				),
				array(
						'label' => 'Khóa'
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
		$rows = array();
		foreach ($this->dataSet as $item){
			$rows[] = array(
					array(
						'type' => 'text',
						'class' => 'id',
						'value' => $item->getId()
					),
					array(
						'type' => 'text',
						'class' => 'id',
						'value' => $item->getUsername()
						
					),
					array(
						'type' => 'text',
						'class' => 'id',
						'value' => $item->getPassword()
					),
					array(
						'type' => 'text',
						'class' => 'id',
						'value' => $item->getFullName()
						
					),
					array(
						'type' => 'text',
						'class' => 'id',
						'value' => $item->getEmail()
					),
					array(
						'type' => 'text',
						'class' => 'id',
						'value' => '<img class="img" src="'.(\Base\Model\Uri::getImgSrc($item) ? \Base\Model\Uri::getImgSrc($item) : '../images/imgno.jpg').'">',
						
					),
					array(
						'type' => 'text',
						'class' => 'id',
						'value' => $item->getRoleName()
					),
					array (
							'type' => 'link',
							'value' => '',
							'tag' => 'span',
							'htmlOptions'=>array('title'=>$item->getActive()? 'Đang hoạt động':'Ngừng hoạt động'),
							'class' => 'changeactive clAct',
							'elementClass' => $item->getActive()? 'fa fa-check icon-green' : 'fa fa-minus-circle icon-red',
							'href' => '/mainweb/public/user/user/changeactive/'.$item->getId(),
					),
					array(
							'type' => 'link',
							'value' => '',
							'htmlOptions'=> array('title' => $item->getLock()? 'Bị khóa' : 'Không khóa'),
							'class' => 'changelock clAct',
							'tag' => 'span',
							'elementClass' => $item->getLock()? 'fa fa-lock icon-red' : 'fa fa-unlock icon-green',
							'href' => '/mainweb/public/user/user/changelock/'.$item->getId(),
					),
					array (
							'type' => 'link',
							'value' => '',
							'tag' => 'span',
							'htmlOptions' => array (
									'title' => 'Thêm người dùng'
							),
							'class' => 'clAct',
							'elementClass' => 'fa fa-plus',
							'href' => '/mainweb/public/user/user/add'
					),
					array (
							'type' => 'link',
							'value' => '',
							'tag' => 'span',
							'htmlOptions' => array (
									'title' => 'Sửa người dùng'
							),
							'class' => 'clAct',
							'elementClass' => 'fa fa-pencil',
							'href' => '/mainweb/public/user/user/edit/' . $item->getId ()
					),
						
					array(
							'type' => 'action',
							'value' => '',
							'htmlOptions'=> array('title' => 'Xóa vị trí'),
							'class' => 'delete clAct',
							'tag' => 'span',
							'elementClass' => 'fa fa-trash-o',
							'href' => '/mainweb/public/user/user/delete/'. $item->getId ()
					),
			);
		}
		$this->rows = $rows;
	}
}
					









