<?php
namespace Home\Model;

use Base\Mapper\Base;

class MenuMapper extends Base{

    protected $tableName = 'menu';

    /**
     * @param \Home\Model\Page $item
     */
    public function get($item)
    {
//        $dbAdapter = $this->getDbSlaveAdapter();
        $dbAdapter = $this->getServiceLocator()->get('dbAdapter');

        $dbSql = $this->getDbSql();

        $select = $dbSql->select(['atCate' => $this->getTableName()]);
        if ($item->getId()) {
            $select->where(['id' => $item->getId()]);
        }
        $select->where(['atCate.storeId = ?' => $item->getStoreId()]);
        $selectStr = $dbSql->getSqlStringForSqlObject($select);
        $result = $dbAdapter->query($selectStr, $dbAdapter::QUERY_MODE_EXECUTE);

        if (!$result->count()) {
            return null;
        }

        $item->exchangeArray((array)$result->current());
//        $category->setServiceLocator($this->getServiceLocator());

        if ($item->getOption('childs') && $item->getOption('childs') == true) {
            $item->setChilds($this->fetchTree($item));
        }
        return $item;
    }

    public function fetchTree($category){
        /* @var $dbAdapter \Zend\Db\Adapter\Adapter */
        $dbAdapter = $this->getServiceLocator()->get('dbAdapter');

        /* @var $dbSql \Zend\Db\Sql\Sql */
        $dbSql = $this->getServiceLocator()->get('dbSql');

        $select = $dbSql->select(array('pc'=>$this->getTableName()));
        if($category->getStoreId()){
            $select->where(array('pc.storeId' => $category->getStoreId()));
        }
        $select->where(array('pc.status'=> 1));
        $select->order ( 'pc.updateDateTime DESC' );

        $selectStr = $dbSql->getSqlStringForSqlObject($select);
        $results = $dbAdapter->query($selectStr,$dbAdapter::QUERY_MODE_EXECUTE);

        $categories = array();
        $cates = array();
        if(count($results)){
            foreach ($results as $rows){
                $model = new \Home\Model\Menu();
                $model->exchangeArray((array)$rows);
                $categories[] = $model;
            }
//            if ($category->getId()) {
//                /* @var $c \Product\Model\Category */
//                /* @var $subC \Product\Model\Category */
//                foreach ($categories as $c) {
//                    foreach ($categories as $subC) {
//                        if ($c->getId() === $subC->getParentId())
//                            $c->addChild($subC);
//                    }
//                    if ($c->getParentId() == $category->getId())
//                        $cates[] = $c;
//                }
//            } else {
                /* @var $c \Product\Model\Category */
                /* @var $subC \Product\Model\Category */
                foreach ($categories as $c) {
                    foreach ($categories as $subC) {
                        if ($c->getId() === $subC->getParentId())
                            $c->addChild($subC);
                    }
                    if (!$c->getParentId())
                        $cates[] = $c;
                }
//            }
        }
        return $cates;
    }
}