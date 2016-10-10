<?php
namespace Admin\Model;
use \Base\Mapper\Base;

class PositionMapper extends Base{
	
	protected $tableName = 'banner_positions';
	
public function getId($id){
		$dbAdapter = $this->getServiceLocator()->get('dbAdapter');
		$dbSql = $this->getServiceLocator()->get('dbSql');
		
		$select = $dbSql->select(array('po'=>$this->getTableName()));
		$select->where(array('po.id'=>$id));
		$selectString = $dbSql->getSqlStringForSqlObject($select);
		$results = $dbAdapter->query($selectString, $dbAdapter::QUERY_MODE_EXECUTE);
		if($results->count()){
			$model = new \Admin\Model\Position();
			$data = (array)$results->current();
			$model->exchangeArray($data);
			return $model;
		}
		return null;
	}
	public function fetchAll(){
		/* @var $dbAdapter \Zend\Db\Adapter\Adapter */
		$dbAdapter = $this->getServiceLocator()->get('dbAdapter');
		/* @var $dbSql \Zend\Db\Sql\Sql */
		$dbSql = $this->getServiceLocator()->get('dbSql');
		$select = $dbSql->select($this->getTableName());
		$selectStr = $dbSql->getSqlStringForSqlObject($select);
		$results = $dbAdapter->query($selectStr,$dbAdapter::QUERY_MODE_EXECUTE);
		$rs = array();
		if(count($results)){
			foreach ($results as $row){
				$model = new \Admin\Model\Position();
				$model->exchangeArray((array)$row);
				$rs[] = $model;
			}
		}
		return $rs;
	}
	/**
	 * @param \Admin\Model\Position $model
	 */
	public function save($model){
		$data = array(
				'storeId'=>$model->getStoreId(),
				'name'=>$model->getName(),
				'description'=>$model->getDescription(),
				'intro'=>$model->getIntro(),
				'status'=>$model->getStatus()
		);
		/* @var $dbAdapter \Zend\Db\Adapter\Adapter */
		$dbAdapter = $this->getServiceLocator()->get('dbAdapter');
		/* @var $dbSql \Zend\Db\Sql\Sql */
		$dbSql = $this->getServiceLocator()->get('dbSql');
		if(($id = $model->getId()) === null){
			//echo 'Id null';die();
			$insert = $dbSql->insert($this->getTableName());
			$insert->values($data);
			$query = $dbSql->getSqlStringForSqlObject($insert);
			$results = $dbAdapter->query($query, $dbAdapter::QUERY_MODE_EXECUTE);
		}
		else {
			$update = $dbSql->update($this->getTableName());
			$update->set($data);
			$update->where(array("id" => (int)$model->getId()));
			$selectString = $dbSql->getSqlStringForSqlObject($update);
			$results = $dbAdapter->query($selectString, $dbAdapter::QUERY_MODE_EXECUTE);
		}
		return $results;
	}
	/** 
	 * @param $item \Admin\Model\Position
	 */
	public function search($item,$paging){
		/* @var $dbAdapter \Zend\Db\Adapter\Adapter */
		$dbAdapter = $this->getServiceLocator()->get('dbAdapter');
		/* @var $dbSql \Zend\Db\Sql\Sql */
		$dbSql = $this->getServiceLocator()->get('dbSql');
		
		$select = $dbSql->select(array('po'=>$this->getTableName()));
		$rCount = $dbSql->select(array('po'=>$this->getTableName()));
		
		if($item->getId()){
			$select->where(array('po.id'=>$item->getId()));
			$rCount->where(array('po.id'=>$item->getId()));
		}
		if($item->getStoreId()){
			$select->where(array('po.storeId'=>$item->getStoreId()));
			$rCount->where(array('po.storeId'=>$item->getStoreId()));
		}
		if($item->getName()){
			$select->where("po.name LIKE '%{$item->getName()}%'");
			$rCount->where("po.name LIKE '%{$item->getName()}%'");
		}
		$currentPage = isset ( $paging [0] ) ? $paging [0] : 1;
		$limit = isset ( $paging [1] ) ? $paging [1] : 20;
		$offset = ($currentPage - 1) * $limit;
		$select->limit ( $limit );
		$select->offset ( $offset );
		$select->order ( 'po.id DESC' );
		
		$selectStr = $dbSql->getSqlStringForSqlObject($select);
		$rCountStr = $dbSql->getSqlStringForSqlObject($rCount);
		$results = $dbAdapter->query($selectStr, $dbAdapter::QUERY_MODE_EXECUTE);
		$count = $dbAdapter->query($rCountStr, $dbAdapter::QUERY_MODE_EXECUTE);
		
		$rs = array();
		if(count($results)){
			foreach ($results as $row){
				$model = new \Admin\Model\Position();
				$model->exchangeArray((array)$row);
				$rs[] = $model;
			}
		}
		return new \Base\Dg\Paginator(count($count), $rs, $paging,count($results));
	}
	public function delete($item){
		/* @var $dbAdapter \Zend\Db\Adapter\Adapter */
		$dbAdapter = $this->getServiceLocator()->get('dbAdapter');
	
		/* @var $dbSql \Zend\Db\Sql\Sql */
		$dbSql = $this->getServiceLocator()->get('dbSql');
		$select = $dbSql->delete($this->getTableName());
		$select->where(array('id'=>$item->getId()));
		$selectStr = $dbSql->getSqlStringForSqlObject($select);
		return $dbAdapter->query($selectStr,$dbAdapter::QUERY_MODE_EXECUTE);
	
	}
}



























