<?php
namespace Product\Model;

use Base\Mapper\Base;
class CategoryMapper extends Base{

	protected $tableName = 'product_categories';

    /**
     * @param \Product\Model\Category $category
     */
    public function get($category)
    {
//        $dbAdapter = $this->getDbSlaveAdapter();
        $dbAdapter = $this->getServiceLocator()->get('dbAdapter');

        $dbSql = $this->getDbSql();

        $select = $dbSql->select(['atCate' => $this->getTableName()]);
        if ($category->getId()) {
            $select->where(['id' => $category->getId()]);
        }
        $select->where(['atCate.status = ?' => Category::STATUS_ACTIVE]);
        $select->where(['atCate.storeId = ?' => $category->getStoreId()]);
        $selectStr = $dbSql->getSqlStringForSqlObject($select);
        $result = $dbAdapter->query($selectStr, $dbAdapter::QUERY_MODE_EXECUTE);

        if (!$result->count()) {
            return null;
        }

        $category->exchangeArray((array)$result->current());
//        $category->setServiceLocator($this->getServiceLocator());

        if ($category->getOption('childs') && $category->getOption('childs') == true) {
            $category->setChilds($this->fetchTree($category));
        }

        return $category;
    }

	public function fetchTree($category){
		/* @var $dbAdapter \Zend\Db\Adapter\Adapter */
		$dbAdapter = $this->getServiceLocator()->get('dbAdapter');
		
 		/* @var $dbSql \Zend\Db\Sql\Sql */
		$dbSql = $this->getServiceLocator()->get('dbSql');
		
		$select = $dbSql->select(array('pc'=>$this->getTableName()));
		if($category->getStoreId()){
			$select->where(array('pc.storeId'=>$category->getStoreId()));
		}
		$select->where(array('pc.status'=> \Product\Model\Product::STATUS_ACTIVE));

        $selectStr = $dbSql->getSqlStringForSqlObject($select);
		$results = $dbAdapter->query($selectStr,$dbAdapter::QUERY_MODE_EXECUTE);
//        print_r($selectStr);die;

		$categories = array();
		$cates = array();
		if(count($results)){
			foreach ($results as $rows){
				$model = new \Product\Model\Category();
				$model->exchangeArray((array)$rows);
				$categories[] = $model;
			}
			if ($category->getId()) {
                foreach ($categories as $c) {
                    foreach ($categories as $subC) {
                        if ($c->getId() === $subC->getParentId())
                            $c->addChild($subC);
                    }
                    if ($c->getParentId() == $category->getId())
                        $cates[] = $c;
                }
            } else {
                foreach ($categories as $c) {
                    foreach ($categories as $subC) {
                        if ($c->getId() === $subC->getParentId())
                            $c->addChild($subC);
                    }
                    if (!$c->getParentId())
                        $cates[] = $c;
                }
            }
        }
        return $cates;
    }
}