<?php
namespace Admin\Model;
use \Base\Mapper\Base;

class BannerMapper extends Base{
	
	protected $tableName = 'banners';
    const TABLE_NAME = 'banners';

    public function get($item)
    {
        if (!$item->getId()){
            return null;
        }
        $select = $this->getDbSql()->select(array('b' => $this->getTableName()));
        $select->where(['b.id' => $item->getId()]);
        $select->limit(1);

        $dbSql = $this->getServiceLocator()->get('dbSql');
        $dbAdapter = $this->getServiceLocator()->get('dbAdapter');
        $query = $dbSql->getSqlStringForSqlObject($select);
        $results = $dbAdapter->query($query, $dbAdapter::QUERY_MODE_EXECUTE);

        if ($results->count()) {
            $item->exchangeArray((array) $results->current());
            return $item;
        }

        return null;
    }

    public function getId($id){
		/* @var $dbAdapter \Zend\Db\Adapter\Adapter */
		$dbAdapter = $this->getServiceLocator()->get('dbAdapter');
		
		/* @var $dbSql \Zend\Db\Sql\Sql */
		$dbSql = $this->getServiceLocator()->get('dbSql');
		$select = $dbSql->select(array('b'=>$this->getTableName()));
		$select->join(array('po'=>'banner_positions'),
			'po.id = b.positionId',
			array(
				'positionName'=>'name'
			),\Zend\Db\Sql\Select::JOIN_LEFT
		);
		$selectStr = $dbSql->getSqlStringForSqlObject($select);
		$result = $dbAdapter->query($selectStr,$dbAdapter::QUERY_MODE_EXECUTE);
		if(count($result)){
			$model = new \Admin\Model\Banner();
			$data = (array)$result->current();
			$model->exchangeArray($data);
			return $model;
		}
		return null;
	}
	public function fetchAll($item){
		/* @var $dbAdapter \Zend\Db\Adapter\Adapter */
		$dbAdapter = $this->getServiceLocator()->get('dbAdapter');
		
		/* @var $dbSql \Zend\Db\Sql\Sql */
		$dbSql = $this->getServiceLocator()->get('dbSql');
		$select = $dbSql->select(array('b'=>$this->getTableName()));
		$select->join(array('po'=>'banner_positions'),
				'po.id = b.positionId',
				array(
						'positionName'=>'name'
				),\Zend\Db\Sql\Select::JOIN_LEFT
		);
		$selectStr = $dbSql->getSqlStringForSqlObject($select);
		$results = $dbAdapter->query($selectStr,$dbAdapter::QUERY_MODE_EXECUTE);
		$rs = array();
		if(count($results)){
			foreach ($results as $row){
				$model = new \Admin\Model\Banner();
				$model->exchangeArray((array)$row);
				$rs[] = $model; 
			}
			return $rs;
		}	
	}
	/**
	 * @param \Admin\Model\Banner $model
	 */
	
	public function save($model){
		$data = array(
            'name' => htmlentities($model->getName()),
            'description' => htmlentities($model->getDescription()),
			'positionId' => $model->getPositionId(),
			'storeId' => $model->getStoreId(),
			'status' => $model->getStatus(),
            'link' => htmlentities($model->getLink()),
            'video' => htmlentities($model->getVideo()),
            'createdById' => $model->getCreatedbyId(),
            'createdDateTime' => $model->getCreatedDateTime(),
		);
        /* @var $dbAdapter \Zend\Db\Adapter\Adapter */
        $dbAdapter = $this->getServiceLocator()->get('dbAdapter');
        /* @var $dbSql \Zend\Db\Sql\Sql */
        $dbSql = $this->getServiceLocator()->get('dbSql');
        if(($id = $model->getId()) == null){
            $insert = $dbSql->insert(self::TABLE_NAME);
            $insert->values($data);
            $insertStr = $dbSql->getSqlStringForSqlObject($insert);
            $results = $dbAdapter->query($insertStr,$dbAdapter::QUERY_MODE_EXECUTE);
            $model->setId($results->getGeneratedValue());
        }
        else{
            $update = $dbSql->update(self::TABLE_NAME);
            $update->set($data);
            $update->where(array('id'=>$model->getId()));
            $updateStr = $dbSql->getSqlStringForSqlObject($update);
            return $dbAdapter->query($updateStr,$dbAdapter::QUERY_MODE_EXECUTE);
        }
	}
	/**
	 * @param \Admin\Model\Banner $item
	 */
	public function search($item,$paging){
		/* @var $dbAdapter \Zend\Db\Adapter\Adapter */
		$dbAdapter = $this->getServiceLocator()->get('dbAdapter');
		/* @var $dbSql \Zend\Db\Sql\Sql */
		$dbSql = $this->getServiceLocator()->get('dbSql');
		
		$select = $dbSql->select(array('b'=>$this->getTableName()));
		$rCount = $dbSql->select(array('b'=>$this->getTableName()),array('p'=>'count(id)'));
//		$select->join(array('po'=>'banner_positions'),
//				    'po.id = b.positionId',
//					array(
//						'positionName'=>'name'
//					),\Zend\Db\Sql\Select::JOIN_LEFT
//		);
		if($item->getId()){
			$select->where(array('b.id'=>$item->getId()));
			$rCount->where(array('b.id'=>$item->getId()));
		}
		if($item->getStoreId()){
			$select->where(array('b.storeId'=>$item->getStoreId()));
			$rCount->where(array('b.storeId'=>$item->getStoreId()));
		}
		if($item->getName()){
			$select->where("b.name LIKE '%{$item->getName()}%'");
			$rCount->where("b.name LIKE '%{$item->getName()}%'");
		}
		$currentPage = isset ( $paging [0] ) ? $paging [0] : 1;
		$limit = isset ( $paging [1] ) ? $paging [1] : 20;
		$offset = ($currentPage - 1) * $limit;
		$select->limit ( $limit );
		$select->offset ( $offset );
		$select->order ( 'b.id DESC' );
		
		$selectStr = $dbSql->getSqlStringForSqlObject($select);
		$rCountStr = $dbSql->getSqlStringForSqlObject($rCount);
		$results = $dbAdapter->query($selectStr, $dbAdapter::QUERY_MODE_EXECUTE);
		$count = $dbAdapter->query($rCountStr, $dbAdapter::QUERY_MODE_EXECUTE);
		$rs = array();
		if(count($results)){
			foreach ($results as $row){
				$model = new \Admin\Model\Banner();
				$model->exchangeArray((array)$row);
				$rs[] = $model;
			}
		}
		return new \Base\Dg\Paginator($count->count(),$rs, $paging, count($results));
	}


	public function delete($item){
		/* @var $dbAdapter \Zend\Db\Adapter\Adapter */
		$dbAdapter = $this->getServiceLocator()->get('dbAdapter');
		/* @var $dbSql \Zend\Db\Sql\Sql */
		$dbSql = $this->getServiceLocator()->get('dbSql');
		$delete = $dbSql->delete($this->getTableName());
		$delete->where(array('id'=>$item->getId()));
		$deleteStr = $dbSql->getSqlStringForSqlObject($delete);
		return $dbAdapter->query($deleteStr,$dbAdapter::QUERY_MODE_EXECUTE);
	}
}


























