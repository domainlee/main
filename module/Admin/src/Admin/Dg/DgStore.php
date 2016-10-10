<?php
namespace Admin\Dg;

class DgStore extends \Base\Dg\Table{
	public function init(){
		$headerArr = array(
			array(
				'label' => 'ID'
			),
			array(
				'label' => 'Doanh nghiệp cha'
			),
			array(
				'label' => 'Tên doanh nghiệp'
			),
			array(
				'label' => 'logo'
			),
			array(
				'label' => 'Tên đăng nhập'
			),
			array(
				'label' => 'Mật khẩu'
			),
			array(
				'label' => 'Địa chỉ'
			),
			array(
				'label' => 'Email'
			),
			array(
				'label' => 'Số ĐT'
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
		$rows = array();
		foreach ($this->dataSet as $item){
			$rows[] = array(
					array (
							'type' => 'text',
							'class' => 'id',
							'value' => $item->getId()
					),
					array (
							'type' => 'text',
							'class' => 'parentId',
							'value' => '',
					),
					array (
							'type' => 'text',
							'class' => 'name',
							'value' => $item->getName()
					),
					array (
							'type' => 'text',
							'class' => 'image',
							'value' => \Base\Model\Uri::getImgSrc($item) ? '<span class="fa fa-picture-o"' : '<a href="#" class="fa fa-plus-circle icon-green addimg"></a>',
					),
					array (
							'type' => 'text',
							'class' => 'username',
							'value' => $item->getUsername()
					),
					array (
							'type' => 'text',
							'class' => 'password',
							'value' => $item->getPassword()
					),
					array (
							'type' => 'text',
							'class' => 'address',
							'value' => $item->getAddress()
					),
					array (
							'type' => 'text',
							'class' => 'email',
							'value' => $item->getEmail()
					),
					array (
							'type' => 'text',
							'class' => 'mobile',
							'value' => $item->getMobile()
					),
					array (
							'type' => 'link',
							'value' => '',
							'tag' => 'span',
							'htmlOptions'=>array('title'=>$item->getStatus()? 'Đang hoạt động':'Ngừng hoạt động'),
							'class' => 'changeactive clAct',
							'elementClass' => $item->getStatus()? 'fa fa-check icon-green' : ' fa fa-minus-circle icon-red',
							'href' => '/admin/article/changeactive/'.$item->getId(),
					),
					array (
							'type' => 'link',
							'value' => '',
							'tag' => 'span',
							'htmlOptions' => array (
									'title' => 'Thêm loại sản phẩm'
							),
							'class' => 'clAct',
							'elementClass' => 'fa fa-plus',
							'href' => '/admin/store/add'
					),
					array (
							'type' => 'link',
							'value' => '',
							'tag' => 'span',
							'htmlOptions' => array (
									'title' => 'Sửa loại sản phẩm'
							),
							'class' => 'clAct',
							'elementClass' => 'fa fa-pencil',
							'href' => '/admin/store/edit/' . $item->getId ()
					),
						
					array(
							'type' => 'action',
							'value' => '',
							'htmlOptions'=> array('title' => 'Xóa bài viết'),
							'class' => 'delete clAct',
							'tag' => 'span',
							'elementClass' => 'fa fa-trash-o',
							'href' => '/admin/store/delete/'. $item->getId ()
					),
			);
		}
		$this->rows = $rows;
	}
}











