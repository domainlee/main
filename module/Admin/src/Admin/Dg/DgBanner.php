<?php
namespace Admin\Dg;

class DgBanner extends \Base\Dg\Table{
	protected function init(){
		$headerArr = array(
			array(
				'label' => 'ID',
                'style' => 'width: 5%'
            ),
			array(
				'label' => 'Tên banner'
			),
            array(
                'label' => 'Vị trí',
                'style' => 'text-align: center;width: 10%'
            ),
            array(
                'label' => 'Trạng thái',
                'style' => 'text-align: center;width: 10%'
            ),
			array(
				'label' => 'Xóa',
                'style' => 'text-align: center;width: 5%'
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
                        'value' => '<a href="/admin/media/editbanner/'.$item->getId().'">'.$item->getName().'</a>'
					),
                    array (
                        'htmlOptions'=>array('style'=>'text-align:center'),
                        'type' => 'text',
                        'value' => $item->getPosition()[$item->getPositionId()]
                    ),
                    array (
                        'htmlOptions'=>array('style'=>'text-align:center'),
                        'type' => 'text',
                        'value' => '<a class="changeType cursor" data-id="'.$item->getId().'">'.($item->getStatus() == 1 ? '<i class="fa fa-eye"></i>':'<i class="fa fa-eye-slash"></i>').'</a>',
                    ),
                    array (
                        'type' => 'action',
                        'htmlOptions'=>array('style'=>'text-align:center'),
                        'value' => '<a class="cursor deleteArticle fa fa-trash-o" data-id="'.$item->getId().'"></a>',
                    ),

					
			);
		}
		$this->rows = $rows;
	}
}














