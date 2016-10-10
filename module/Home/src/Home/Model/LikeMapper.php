<?php

namespace Home\Model;

use Base\Mapper\Base;
use Zend\Db\Sql\Expression;

Class LikeMapper extends Base
{
    /**
     * @var string
     */
    protected $tableName = 'like';

    CONST TABLE_NAME = 'like';

    /**
     * @param \Home\Model\Like $item
     */
    public function save($item){
        $data = array(
            'type' => $item->getType() ?: null,
            'itemId'=> $item->getItemId(),
            'createdById'=> $item->getCreatedById() ? : null,
            'createdDateTime' => $item->getCreatedDateTime() ? : null,
        );
        $dbAdapter = $this->getServiceLocator()->get('dbAdapter');
        $dbSql = $this->getServiceLocator()->get('dbSql');
        if(!$item->getId()){
            $insert = $dbSql->insert($this->getTableName());
            $insert->values($data);
            $query = $dbSql->getSqlStringForSqlObject($insert);
            $results = $dbAdapter->query($query, $dbAdapter::QUERY_MODE_EXECUTE);
        }
        else {
            $update = $dbSql->update($this->getTableName());
            $update->set($data);
            $update->where(array("id" => (int)$item->getId()));
            $selectString = $dbSql->getSqlStringForSqlObject($update);
            $results = $dbAdapter->query($selectString, $dbAdapter::QUERY_MODE_EXECUTE);
        }
        return $results;
    }

    /**
     * @param \Home\Model\Like $item
     */
    public function get($item)
    {
        $dbAdapter = $this->getServiceLocator()->get('dbAdapter');
        $dbSql = $this->getDbSql();
        $select = $dbSql->select(['l' => $this->getTableName()]);
        $select->group('l.itemId');
        $select->where(['l.itemId = ?' => $item->getItemId()]);
        $select->where(['l.type = ?' => $item->getType()]);
        $select->columns([
            'totalNumber' => new Expression('COUNT(id)'),
        ]);
        $selectStr = $dbSql->getSqlStringForSqlObject($select);
        $result = $dbAdapter->query($selectStr, $dbAdapter::QUERY_MODE_EXECUTE);
        $total = '0';
        if ($result->count()) {
            foreach($result->toArray() as $a){
                $total = $a['totalNumber'];
            }
        }
        return $total;
    }

}