<?php
namespace Admin\Dg;

class DgPage extends \Base\Dg\Table{
    protected function init(){
        $headerArr = array(
            array(
                'label' => 'ID',
                'style' => 'width: 3%'
            ),
            array(
                'label' => 'Tiêu đề'
            ),
//            array(
//                'label' => 'Danh mục'
//            ),
//            array(
//                'label' => 'Hiển thị'
//            ),
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
                    'value' => '<a href="/admin/page/edit/'.$item->getId().'">'. $item->getName().'</a>',
                ),
//                array(
//                    'type' => 'text',
//                    'value' => '',
//                ),
//                array(
//                    'type' => 'display',
//                    'value' => '<select class="typeOption span12 m-wrap" data-id="'.$item->getId().'" data-placeholder="Choose a Category" tabindex="1">
//                                    <option>none</option>
//                                    <option value="1" '.($item->getType() == 1 ? 'selected':'').'>Vị trí 1</option>
//                                    <option value="2" '.($item->getType() == 2 ? 'selected':'' ).'>Vị trí 2</option>
//                                    <option value="3" '.($item->getType() == 3 ? 'selected':'' ).'>Vị trí 3</option>
//                                    <option value="4" '.($item->getType() == 4 ? 'selected':'' ).'>Vị trí 4</option>
//                                </select>',
//                ),
                array (
                    'type' => 'link',
                    'value' => '<a class="changeType cursor" data-id="'.$item->getId().'">'.($item->getStatus() == 1 ? '<i class="fa fa-eye"></i>':'<i class="fa fa-eye-slash"></i>').'</a>',
                    'htmlOptions'=>array('style'=>'text-align: center'),
                ),
                array(
                    'type' => 'action',
                    'value' => '<a class="cursor deletePage fa fa-trash-o" data-id="'.$item->getId().'"></a>',
                    'htmlOptions'=> array('style'=>'text-align: center'),
                ),
            );
        }
        $this->rows = $rows;
    }
}
