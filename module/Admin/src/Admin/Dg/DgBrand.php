<?php
namespace Admin\Dg;

class DgBrand extends \Base\Dg\Table{

	protected function init(){
		$headerArr = array(
			array(
				'label' => 'ID',
                'style' => 'text-align:center;width: 3%'
            ),
			array(
				'label' => 'Tên'
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
						'value'=> $item->getId(),
                        'htmlOptions'=> array('style' => 'text-align:center;'),
                    ),
					array(
						'type'=>'text',
						'class'=>'name',
						'value'=> '<a href="/admin/product/editbrand/'.$item->getId().'">'.$item->getName().'</a>',
					),
					array(
						'type' => 'link',
                        'value' => '<a class="changeType cursor" data-id="'.$item->getId().'">'.($item->getStatus() == 1 ? '<i class="fa fa-eye"></i>':'<i class="fa fa-eye-slash"></i>').'</a>',
                        'htmlOptions'=> array('style' => 'text-align:center;'),
                    ),
                    array(
                        'type' => 'link',
                        'value' => '',
                        'htmlOptions'=> array('style' => 'text-align:center;'),
                    ),
					array(
						'type' => 'action',
						'value' => '<a style="cursor: pointer;" class="deleteBrand fa fa-trash-o" data-id="'.$item->getId().'"></a>',
                        'htmlOptions'=> array('style' => 'text-align:center;'),
                    ),

				);
			}
			$this->rows = $rows;
	}
}































