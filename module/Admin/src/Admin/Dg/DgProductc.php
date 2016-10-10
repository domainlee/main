<?php
namespace Admin\Dg;

class DgProductc extends \Base\Dg\Table{

	protected function init(){
		$headerArr = array(
			array(
				'label' => 'ID'
			),
			array(
				'label' => 'Danh mục'
			),
            array(
                'label' => 'Danh mục cha'
            ),
			array(
                'label' => 'Trạng thái',
                'style' => 'text-align:center;width: 8%'
            ),
            array(
                'label' => 'Thứ tự',
                'style' => 'text-align:center;width: 7%'
            ),
			array(
                'label' => 'Xóa',
                'style' => 'text-align:center;width: 5%'
			)
		);
		$this->headers = $headerArr;
		    $rows = array();
			foreach ($this->dataSet as $item){
				$rows[] = array(
					array(
						'type'=>'text',
						'class'=>'id',
						'value'=> $item->getId()
					),
					array(
						'type'=>'text',
						'class'=>'name',
						'value'=> '<a href="/admin/product/editcategory/'.$item->getId().'">'.$item->getName().'</a>',
					),
                    array(
                        'type' => 'text',
                        'value' => $item->getParent()? $item->getParent()->getName() : '',
                    ),
					array(
						'type' => 'link',
                        'value' => '<a class="changeType cursor" data-id="'.$item->getId().'">'.($item->getStatus() == 1 ? '<i class="fa fa-eye"></i>':'<i class="fa fa-eye-slash"></i>').'</a>',
                        'htmlOptions'=> array('style' => 'text-align:center;'),
                    ),
                    array(
                        'type' => 'link',
                        'value' => !$item->getParent() ? '<a style="cursor: pointer;" class="updateOrder" title="Lên đầu" data-id="'.$item->getId().'"><i class="fa fa-arrow-up" aria-hidden="true"></i></a>':null ,
                        'htmlOptions'=> array('style' => 'text-align:center;'),
                    ),
					array(
						'type' => 'action',
						'value' => '<a style="cursor: pointer;" class="deleteCategory fa fa-trash-o" data-id="'.$item->getId().'"></a>',
                        'htmlOptions'=> array('style' => 'text-align:center;'),
                    ),

				);
			}
			$this->rows = $rows;
	}
}































