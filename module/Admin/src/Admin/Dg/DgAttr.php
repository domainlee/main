<?php

namespace Admin\Dg;

//use ZendX\DataGrid\DataGrid;
//use ZendX\DataGrid\Row;
use ZendX\DataGrid\DataGrid;
use ZendX\DataGrid\Row;
use Home\Form;

class DgAttr extends DataGrid{
    public function init()
    {
        $this->addHeader([
            'attributes' => array(),
            'options' => array(),
            'columns' => array(
                array(
                    'name' => 'id',
                    'content' => 'ID',
                ),
                array(
                    'name' => 'name',
                    'content' => 'Tên',
                ),
                array(
                    'name' => 'colorCode',
                    'content' => 'Mã màu',
                ),
                array(
                    'name' => 'type',
                    'content' => 'Kiểu',
                ),
            )
        ]);
        if(!$this->getDataSource() instanceof \Zend\Paginator\Paginator || !$this->getDataSource()->getCurrentModels()) {
            return;
        }
        foreach($this->getDataSource()->getCurrentModels() as $item) {
            $row = new Row();
            $this->addRow($row);
            $row->addColumn(array(
                'name' 			=> 'id',
                'content' 		=> $item->getId(),
            ));
            $row->addColumn(array(
                'name' 			=> 'name',
                'content' 		=> $item->getName(),
            ));
            $row->addColumn(array(
                'name' 			=> 'colorCode',
                'content' 		=> $item->getColorCode().'<span style="display: inline-block;width: 10px;height: 10px;float: right;background:'.$item->getColorCode().'"></span>',
            ));
            $row->addColumn(array(
                'name' 			=> 'type',
                'content' 		=> '',
            ));
        }

    }
}