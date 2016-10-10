<?php
namespace Admin\Model;
use Base\Mapper\Base;

class AttrMapper extends Base{

    CONST TABLE_NAME = 'product_attr';

    /**
     * @param \Admin\Model\Attr $model
     */
    public function save($model) {
        $da = new \Base\Model\RDate;
        $data = array(
            'type' => $model->getType(),
            'name'=> htmlentities($model->getName()),
            'colorCode' => htmlentities($model->getColorCode()),
        );

        /* @var $dbAdapter \Zend\Db\Adapter\Adapter */
        $dbAdapter = $this->getServiceLocator()->get('dbAdapter');
        /* @var $dbSql \Zend\Db\Sql\Sql */
        $dbSql = $this->getServiceLocator()->get('dbSql');
        $results = false;
        if (null === ($id = $model->getId())) {
            $insert = $dbSql->insert(self::TABLE_NAME);
            $insert->values($data);
            $query = $dbSql->getSqlStringForSqlObject($insert);
            $results = $dbAdapter->query($query, $dbAdapter::QUERY_MODE_EXECUTE);
        } else {
            $update = $dbSql->update(self::TABLE_NAME);
            $update->set($data);
            $update->where(array("id" => (int)$model->getId()));
            $selectString = $dbSql->getSqlStringForSqlObject($update);
            $results = $dbAdapter->query($selectString, $dbAdapter::QUERY_MODE_EXECUTE);
        }
        return $results;
    }

    /**
     * @param \Admin\Model\Attr $item
     */
    public function search($item, $options = null){
        $select = $this->getDbSql()->select(array('pa' => self::TABLE_NAME));
        if ($item->getId()) {
            $select->where(['pa.id' => $item->getId()]);
        }
        if ($item->getName()) {
            $select->where(['pa.name LIKE ?' => '%'. $item->getName() .'%']);
        }
        $select->order(['pa.id' => 'DESC']);
        $paginator = $this->preparePaginator($select, $options , new Attr());
        return $paginator;
    }

    public function fetchAll($item)
    {
        /* @var $dbAdapter \Zend\Db\Adapter\Adapter */
        $dbAdapter = $this->getServiceLocator()->get('dbAdapter');
        /* @var $dbSql \Zend\Db\Sql\Sql */
        $dbSql = $this->getServiceLocator()->get('dbSql');
        $select = $dbSql->select(array("p" => self::TABLE_NAME));
        if($item->getType()){
            $select->where(['p.type' => $item->getType()]);
        }
        $selectString = $dbSql->getSqlStringForSqlObject($select);
        $results = $dbAdapter->query($selectString, $dbAdapter::QUERY_MODE_EXECUTE);

        $rs = array();
        if($results->count()) {
            foreach ($results as $row) {
                $model = new \Admin\Model\Attr();
                $model->exchangeArray((array)$row);
                $rs[] = $model;
            }
        }
        return $rs;
    }


}