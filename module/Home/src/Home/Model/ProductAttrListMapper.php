<?php

namespace Home\Model;

use Base\Mapper\Base;

//use Home\Model\Base;

use Home\Model\BaseMapper;

Class ProductAttrListMapper extends Base
{
    /**
     * @var string
     */
    protected $tableName = 'product_attr_list';

    CONST TABLE_NAME = 'product_attr_list';

    /**
     * @param $item int
     * @return Home|null
     */
    public function get($item)
    {

        $dbAdapter = $this->getServiceLocator()->get('dbAdapter');
        $dbSql = $this->getDbSql();
        $select = $dbSql->select(['attr' => self::TABLE_NAME]);
        if($item->getProductId()){
            $select->where(['attr.productId' => $item->getProductId()]);
        }
        if($item->getType()){
            $select->where(['attr.type' => $item->getType()]);
        }
        if($item->getOptions()['group']){
            $select->group('attr.productattrId');
        }
        $select->join(['pa' => 'product_attr'], 'attr.productattrId=pa.id',['name','colorCode']);
        $selectStr = $dbSql->getSqlStringForSqlObject($select);
        $result = $dbAdapter->query($selectStr, $dbAdapter::QUERY_MODE_EXECUTE);
        $rs = [];

        if ($result->count()) {
            foreach ($result as $row){
                $row = (array)$row;
                $ProductAttrList = new \Home\Model\ProductAttrList();
                $ProductAttrList->exchangeArray($row);
                $rs[] = $ProductAttrList;
            }
            return $rs;
        }
        return null;
    }

}