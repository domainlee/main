<?php
namespace Admin\Model;
use Base\Mapper\Base;
use Admin\Model\AttrList;

class AttrListMapper extends Base{

    CONST TABLE_NAME = 'product_attr_list';

    /**
     * @param \Admin\Model\AttrList $model
     */
    public function save($model) {
        $data = array(
            'productattrId' => $model->getProductattrId(),
            'productId' => $model->getProductId(),
            'type' => $model->getType(),
        );
        /* @var $dbAdapter \Zend\Db\Adapter\Adapter */
        $dbAdapter = $this->getServiceLocator()->get('dbAdapter');
        /* @var $dbSql \Zend\Db\Sql\Sql */
        $dbSql = $this->getServiceLocator()->get('dbSql');
        $results = false;

        if($this->checkExist($model)) {
            $update = $dbSql->update(self::TABLE_NAME);
            $update->set($data);
            $update->where(array("productattrId" => (int)$model->getProductattrId()));
            $update->where(['productId' => (int)$model->getProductId()]);
            $selectString = $dbSql->getSqlStringForSqlObject($update);
            $results = $dbAdapter->query($selectString, $dbAdapter::QUERY_MODE_EXECUTE);
        }
        elseif($model->getProductId() && $model->getProductattrId()) {
            $insert = $dbSql->insert(self::TABLE_NAME);
            $insert->values($data);
            $query = $dbSql->getSqlStringForSqlObject($insert);
            $results = $dbAdapter->query($query, $dbAdapter::QUERY_MODE_EXECUTE);
        }
        return $results;
    }

    /**
     * @param \Admin\Model\AttrList $item
     */
    public function checkExist($item)
    {
        if(!$item->getProductId() && !$item->getProductattrId()){
            return null;
        }
        $select = $this->getDbSql()->select(array('pal' => self::TABLE_NAME ));
        if ($item->getProductId() && $item->getProductattrId()) {
            $select->where([
                'pal.productId' => $item->getProductId()
            ]);
            $select->where([
                'pal.productattrId' => $item->getProductattrId()
            ]);
        }
        $dbAdapter = $this->getServiceLocator()->get('dbAdapter');
        $dbSql = $this->getServiceLocator()->get('dbSql');
        $selectStr = $dbSql->getSqlStringForSqlObject($select);
        $results = $dbAdapter->query($selectStr,$dbAdapter::QUERY_MODE_EXECUTE);
        if ($results->count()) {
            $item->exchangeArray((array) $results->current());
            return $item;
        }
        return null;
    }

    public function update($productId,$type, $attr){
        if(!$productId){
            return null;
        }
        $attrlist = new \Admin\Model\AttrList();
        $attrlist->setProductId($productId);
        $attrlist->setType($type);

        $this->delete($attrlist);
//        echo count($attr);die;
        if(count($attr)){
            foreach($attr as $a){
                $attrlists = new \Admin\Model\AttrList();
                $attrlists->setProductattrId($a);
                $attrlists->setType($type);
                $attrlists->setProductId($productId);
                $this->save($attrlists);
            }
        }
    }

    /**
     * @param \Admin\Model\AttrList $attr
     */

    public function delete($attr)
    {
        if(!$attr->getProductId()){
            return null;
        }
        $delete = $this->getDbSql()->delete(self::TABLE_NAME);
        $delete->where(['productId'=> $attr->getProductId()]);
        $delete->where(['type' => $attr->getType()]);
        $dbAdapter = $this->getServiceLocator()->get('dbAdapter');
        $dbSql = $this->getServiceLocator()->get('dbSql');
        $selectStr = $dbSql->getSqlStringForSqlObject($delete);
        $dbAdapter->query($selectStr,$dbAdapter::QUERY_MODE_EXECUTE);
        return null;
    }

    /**
     * @param \Admin\Model\AttrList $attr
     */

    public function fetchAll($attr)
    {
        if(!$attr->getProductId()){
            return null;
        }
        /* @var $dbAdapter \Zend\Db\Adapter\Adapter */
        $dbAdapter = $this->getServiceLocator()->get('dbAdapter');

        /* @var $dbSql \Zend\Db\Sql\Sql */
        $dbSql = $this->getServiceLocator()->get('dbSql');
        $select = $dbSql->select(array("p" => self::TABLE_NAME));
        $select->where(['productId' => $attr->getProductId()]);
        $selectString = $dbSql->getSqlStringForSqlObject($select);
        $results = $dbAdapter->query($selectString, $dbAdapter::QUERY_MODE_EXECUTE);

        $rs = array();
        if($results->count()) {
            foreach ($results as $row) {
                $model = new \Admin\Model\AttrList();
                $model->exchangeArray((array)$row);
                $rs[] = $model;
            }
        }
        return $rs;
    }

    /**
     * @param \Admin\Model\AttrList $item
     */
//    public function search($item, $options = null){
//        $select = $this->getDbSql()->select(array('pa' => self::TABLE_NAME));
//        if ($item->getId()) {
//            $select->where(['pa.id' => $item->getId()]);
//        }
//        if ($item->getName()) {
//            $select->where(['pa.name LIKE ?' => '%'. $item->getName() .'%']);
//        }
//        $select->order(['pa.id' => 'DESC']);
//        $paginator = $this->preparePaginator($select, $options , new Attr());
//        return $paginator;
//    }

}