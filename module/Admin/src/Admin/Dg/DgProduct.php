<?php
namespace Admin\Dg;

class DgProduct extends \Base\Dg\Table{
	protected function init(){
		$headerArr = array(
			array(
					'label' => 'ID'
			),
			array(
					'label' => 'Tên sản phẩm'
			),
            array(
                'label' => 'Danh mục'
            ),
			array(
                'label' => 'Trạng thái',
                'style' => 'text-align: center;width: 8%'
            ),
			array(
                'label' => 'Xóa',
                'style' => 'text-align: center;width: 5%'
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
							'value' => '<a href="/admin/product/edit/'.$item->getId().'">'. $item->getName().'</a>',
					),
                    array(
                        'type' => 'text',
                        'value' => $item->getCateName() ? $item->getCateName() : '',
                    ),
					array(
                        'type' => 'link',
                        'value' => '<a class="changeType cursor" data-id="'.$item->getId().'">'.($item->getStatus() == 1 ? '<i class="fa fa-eye"></i>':'<i class="fa fa-eye-slash"></i>').'</a>',
                        'htmlOptions'=> array('style'=>'text-align: center'),
                    ),
					array(
                        'htmlOptions'=> array('style'=>'text-align: center'),
                        'type' => 'display',
                        'value' => '<a id="deleteProduct" data-id="'.$item->getId().'" class="deleteProduct fa fa-trash-o"></a>',
					),
			);
		}
		$this->rows = $rows;
	}
}














