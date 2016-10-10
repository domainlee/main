<?php

namespace Home\Model;

use Base\Mapper\Base;

//use Home\Model\Base;

use Home\Model\BaseMapper;

Class ProductAttrMapper extends Base
{
    /**
     * @var string
     */
    protected $tableName = 'product_attr';

    CONST TABLE_NAME = 'product_attr';

    /**
     * @param $item int
     * @return Home|null
     */

    public function get($item)
    {
        if(!$item->getId()){
            return null;
        }
        $dbAdapter = $this->getServiceLocator()->get('dbAdapter');
        $dbSql = $this->getDbSql();
        $select = $dbSql->select(['pa' => $this->getTableName()]);
        $select->where(['pa.id' => $item->getId()]);
        $selectStr = $dbSql->getSqlStringForSqlObject($select);
        $result = $dbAdapter->query($selectStr, $dbAdapter::QUERY_MODE_EXECUTE);
        if($result->count()){
            $item->exchangeArray((array)$result->current());
            return $item;
        }
        return null;
    }

}