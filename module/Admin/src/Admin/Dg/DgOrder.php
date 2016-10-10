<?php
namespace Admin\Dg;

class DgOrder extends \Base\Dg\Table{
    protected function init(){
        $headerArr = array(
            array(
                'label' => 'ID'
            ),
            array(
                'label' => 'Tên khách hàng'
            ),
            array(
                'label' => 'Địa chỉ',
                'style' => ''
            ),
            array(
                'label' => 'Sản phẩm',
                'style' => ''
            ),
            array(
                'label' => 'Xóa',
                'style' => 'text-align: center;width: 5%'
            )
        );
        $this->headers = $headerArr;
        $rows = array();
        foreach ($this->dataSet as $item){
//            print_r($item);
//            die;
            $c = '';
            $m = '';
            $q = '';

            if($item->getOptions('product')){
                foreach($item->getOptions('product') as $p){
                    foreach($p as $o){
                        $attrs = '';
                        foreach($o->getOptions()['attr'] as $attr){
                            $attrs .= ' ['.$attr->getName() .']';
                        }
                        $n = number_format($o->getOptions()['priceOld'] != 0 ? $o->getOptions()['priceOld']:$o->getOptions()['price']);
                        $m += ($o->getOptions()['priceOld'] != 0 ? $o->getOptions()['priceOld']:$o->getOptions()['price'])*$o->getQuantity();
                        $c .= '<li><a href="/admin/product?id='.$o->getProductId().'">'.$o->getOptions()['productName'].' '.$attrs.' [SL: '.$o->getQuantity().'] ['.$n.'đ]</a></li>';
                    }
//                    foreach($p as $i){
//                        $m += ($i['priceOld'] != 0 ? $i['priceOld']:$i['price']);
//                        $c .= '<li><a href="/admin/product?id='.$p['productId'].'">'.$p->getOptions()['productName'].' </a></li>';
//                    }
                }
            }
            $rows[] = array(
                array (
                    'type' => 'text',
                    'class' => 'id',
                    'value' => $item->getId()
                ),
                array (
                    'type' => 'display',
                    'value' => '<ul style="margin: 0 0 0 20px">'.'<li>'.$item->getCustomerName().'</li>'.'<li>'.$item->getCustomerMobile().'</li>'.'</ul>',
                ),
                array(
                    'type' => 'text',
                    'value' => $item->getCustomerAddress(),
                ),

                array(
                    'type' => 'display',
                    'value' => '<ul style="margin: 0 0 0 20px;">'.$c.'<li>Tổng tiền: '.number_format($m).'đ</li></ul>',
                ),
                array(
                    'htmlOptions'=> array('style'=>'text-align: center'),
                    'type' => 'display',
                    'value' => '<a id="deleteProduct" data-id="'.$item->getId().'" class="deleteProduct fa fa-trash-o"></a>',
                ),
            );
        }
//        die;
        $this->rows = $rows;
    }
}
