<?php
namespace Admin\Model;

use \Base\Mapper\Base;

class ProductcMapper extends Base{
	
	protected $tableName = 'product_categories';

    const TABLE_NAME = 'product_categories';

    public function get($c){
        if(!$c->getId() && !$c->getName()){
            return null;
        }
        /* @var $dbAdapter \Zend\Db\Adapter\Adapter */
        $dbAdapter = $this->getServiceLocator()->get('dbAdapter');
        /* @var $dbSql \Zend\Db\Sql\Sql */
        $dbSql = $this->getServiceLocator()->get('dbSql');
        $select = $dbSql->select(array('p'=> self::TABLE_NAME));
        if($c->getId()){
            $select->where(array('p.id'=> $c->getId()));
        }
        if($c->getName()){
            $select->where(['p.name'=> htmlentities($c->getName())]);
        }
        $selectStr = $dbSql->getSqlStringForSqlObject($select);
//        echo $selectStr;die;

        $results = $dbAdapter->query($selectStr,$dbAdapter::QUERY_MODE_EXECUTE);
        if(count($results)){
            $c->exchangeArray((array)$results->current());
            return $c;
        }
    }

	public function getId($id){
		/* @var $dbAdapter \Zend\Db\Adapter\Adapter */
		$dbAdapter = $this->getServiceLocator()->get('dbAdapter');
		/* @var $dbSql \Zend\Db\Sql\Sql */
		$dbSql = $this->getServiceLocator()->get('dbSql');
		$select = $dbSql->select(array('pc'=>$this->getTableName()));
		$select->join(array('pcj'=>'product_categories'),
			'pcj.id = pc.parentId',
			array(
				'parentName'=>'name'
			),\Zend\Db\Sql\Select::JOIN_LEFT
		);
		$select->where(array('pc.id'=>$id));
		$selectStr = $dbSql->getSqlStringForSqlObject($select);
		$results = $dbAdapter->query($selectStr,$dbAdapter::QUERY_MODE_EXECUTE);
		if($results->count()){
			$model = new \Admin\Model\Productc();
			$data = (array)$results->current();
			$model->exchangeArray($data);
			return $model;
		}
	}

    public function fetchAll($item)
	{
		/* @var $dbAdapter \Zend\Db\Adapter\Adapter */
		$dbAdapter = $this->getServiceLocator()->get('dbAdapter');
	
		/* @var $dbSql \Zend\Db\Sql\Sql */
		$dbSql = $this->getServiceLocator()->get('dbSql');
		$select = $dbSql->select(array("pc" => $this->getTableName()));
		
//		if($item->getParentId()) {
//			$select->where(array('pc.parentId' => $item->getParentId()));
//		}
        if($item->getStoreId()){
            $select->where(array('pc.storeId' => $item->getStoreId()));
        }

		$selectString = $dbSql->getSqlStringForSqlObject($select);
		$results = $dbAdapter->query($selectString, $dbAdapter::QUERY_MODE_EXECUTE);
	
		$rs = array();
		if($results->count()) {
			foreach ($results as $row) {
				$model = new \Admin\Model\Productc();
				$model->exchangeArray((array)$row);
				$rs[] = $model;
			}
		}
		return $rs;
	}

	public function save($model){
		$data = array(
			'parentId'=>$model->getParentId() ? : null,
			'storeId'=>$model->getStoreId(),
			'name' => htmlentities($model->getName()),
			'image' => $model->getImage() ? : null,
			'description' => htmlentities($model->getDescription()) ? : null,
			'status' => $model->getStatus(),
            'updateTime' => $model->getUpdateTime() ? : null,
		);
		/* @var $dbAdapter \Zend\Db\Adapter\Adapter */
		$dbAdapter = $this->getServiceLocator()->get('dbAdapter');
		/* @var $dbSql \Zend\Db\Sql\Sql */
		$dbSql = $this->getServiceLocator()->get('dbSql');
		$results = false;
		if (null === ($id = $model->getId())) {
			$insert = $dbSql->insert($this->getTableName());
			$insert->values($data);
			$insertStr = $dbSql->getSqlStringForSqlObject($insert);
			$results = $dbAdapter->query($insertStr,$dbAdapter::QUERY_MODE_EXECUTE);
            $model->setId($results->getGeneratedValue());
        }
		else{
			$update = $dbSql->update($this->getTableName());
			$update->set($data);
			$update->where(array('id'=>(int)$model->getId()));
			$updateStr = $dbSql->getSqlStringForSqlObject($update);
			$results = $dbAdapter->query($updateStr,$dbAdapter::QUERY_MODE_EXECUTE);
		}
		return $results;
		
	}
	public function search($item,$paging){
		/* @var $dbAdapter \Zend\Db\Adapter\Adapter */
		$dbAdapter = $this->getServiceLocator()->get('dbAdapter');
		/* @var $dbSql \Zend\Db\Sql\Sql */
		$dbSql = $this->getServiceLocator()->get('dbSql');
		$select = $dbSql->select(array('pc'=>$this->getTableName()));
		$rCount = $dbSql->select(array("pc" => $this->getTableName()), array('c' => 'COUNT(id)'));
		$select->join(array('pcj'=>'product_categories'),
			'pcj.id = pc.parentId',
			array(
				'parentName'=>'name',
			),\Zend\Db\Sql\Select::JOIN_LEFT
		);
		
		if($item->getId()){
			$select->where(array('pc.id'=>$item->getId()));
			$rCount->where(array('pc.id'=>$item->getId()));
		}
		if($item->getStoreId()){
			$select->where(array('pc.storeId'=>$item->getStoreId()));
			$rCount->where(array('pc.storeId'=>$item->getStoreId()));
		}
		if($item->getName()){
			$select->where("pc.name LIKE '%{$item->getName()}%'");
			$rCount->where("pc.name LIKE '%{$item->getName()}%'");
		}
		$currentPage = isset ( $paging [0] ) ? $paging [0] : 1;
		$limit = isset ( $paging [1] ) ? $paging [1] : 20;
		$offset = ($currentPage - 1) * $limit;
		$select->limit ( $limit );
		$select->offset ( $offset );
//		$select->order ( 'pc.updateTime DESC' );

        $selectStr = $dbSql->getSqlStringForSqlObject($select);
		$rCountStr = $dbSql->getSqlStringForSqlObject($rCount);
		$results = $dbAdapter->query($selectStr,$dbAdapter::QUERY_MODE_EXECUTE);
		$count = $dbAdapter->query($rCountStr,$dbAdapter::QUERY_MODE_EXECUTE);
		
		$rs = array();
//        $cates = array();
//
//        $d = [];
//        $rc = [];
//        if(count($count)){
//            foreach($count as $c){
//                $model = new \Admin\Model\Productc();
//                $model->exchangeArray((array)$c);
//                $rc[] = $model;
//            }
//            foreach($rc as $cc){
//                if (!$cc->getParentId())
//                    $d[] = $cc;
//            }
//        }
        if(count($results)){
			foreach ($results as $row){
				$model = new \Admin\Model\Productc();
				$model->exchangeArray((array)$row);
				$rs[] = $model;
			}

//            foreach ($rc as $c) {
//                foreach ($rc as $subC) {
//                    if ($c->getId() === $subC->getParentId())
//                        $c->addChild($subC);
//                }
//                if (!$c->getParentId())
//                    $cates[] = $c;
//            }
		}
//        print_r($cates);
//        die;

//        echo count($d);die;
//        echo count($rs);
//        echo '<br/>';
//        echo $count->count();
//        echo '<br/>';
//        echo count($results);die;
		return new \Base\Dg\Paginator(count($count), $rs, $paging,count($results));
	}

	public function getChildren($item) {
		$model = new \Admin\Model\Productc();
		$model->setParentId($item->getId());
		$children = $this->fetchAll($model);
		if(count($children)) {
			return $children;
		}
		return false;
	}
	/**
	 *
	 * @param array<\Restaurant\Model\PositionCategory> $children
	 */
	public function deleteAllChildren($children) {
		if(count($children)) {
			foreach ($children as $item) {
				if(($itemChilds = $this->getChildren($item)) != false) {
					$this->deleteAllChildren($itemChilds);
				}
				$this->delete($item);
			}
		}
	}
	/**
	 *
	 * @param \Restaurant\Model\PositionCategory $item
	 */
	public function delete($item) {
		/* @var $dbAdapter \Zend\Db\Adapter\Adapter */
		$dbAdapter = $this->getServiceLocator()->get('dbAdapter');
		/* @var $dbSql \Zend\Db\Sql\Sql */
		$dbSql = $this->getServiceLocator()->get('dbSql');
		$delete  = $dbSql->delete($this->getTableName());
		// if have children then delete them first
//		if(($children = $this->getChildren($item)) != false) {
//			$this->deleteAllChildren($children);
//		}
		$delete->where(array('id' => $item->getId()));
        if($item->getStoreId()){
            $delete->where(array('storeId' => $item->getStoreId()));
        }
		$deleteStr = $dbSql->getSqlStringForSqlObject($delete);
		return $dbAdapter->query($deleteStr, $dbAdapter::QUERY_MODE_EXECUTE);
	}
}



























