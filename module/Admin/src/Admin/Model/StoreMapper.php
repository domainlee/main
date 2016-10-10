<?php
namespace Admin\Model;

use Base\Mapper\Base;
class StoreMapper extends Base{
	protected $tableName = 'stores';
	
	public function getId($id){
		/* @var $dbAdapter \Zend\Db\Adapter\Adapter */
		$dbAdapter = $this->getServiceLocator()->get('dbAdapter');
		/* @var $dbSql \Zend\Db\Sql\Sql */
		$dbSql = $this->getServiceLocator()->get('dbSql');
		
		$select = $dbSql->select(array('st'=>$this->getTableName()));
		$selectStr = $dbSql->getSqlStringForSqlObject($select);
		$results = $dbAdapter->query($selectStr,$dbAdapter::QUERY_MODE_EXECUTE);
		if(count($results)){
			$model = new \Admin\Model\Store();
			$data = (array)$results->current();
			$model->exchangeArray($data);
			return $model;
		}
	}
	
	public function fetchAll($item){
		/* @var $dbAdapter \Zend\Db\Adapter\Adapter */
		$dbAdapter = $this->getServiceLocator()->get('dbAdapter');
		/* @var $dbSql \Zend\Db\Sql\Sql */
		$dbSql = $this->getServiceLocator()->get('dbSql');
		
		$select = $dbSql->select(array('st'=>$this->getTableName()));
		if($item->getParentId()){
			$select->where(array('st.parentId'=>$item->getParentId()));
		}
        if($item->getId()){
            $select->where(array('st.id'=>$item->getId()));
        }
		$selectStr = $dbSql->getSqlStringForSqlObject($select);
		$results = $dbAdapter->query($selectStr,$dbAdapter::QUERY_MODE_EXECUTE);
		$rs = array();
		if(count($results)){
			foreach ($results as $rows){
				$model = new \Admin\Model\Store();
				$model->exchangeArray((array)$rows);
				$rs[] = $model;
			}
		}
		return $rs;
	}
	public function search($item,$paging){
		/* @var $dbAdapter \Zend\Db\Adapter\Adapter */
		$dbAdapter = $this->getServiceLocator()->get('dbAdapter');
		/* @var $dbSql \Zend\Db\Sql\Sql */
		$dbSql = $this->getServiceLocator()->get('dbSql');
		
		$select = $dbSql->select(array('st'=>$this->getTableName()));
		$rCount = $dbSql->select(array('st'=>$this->getTableName()),array('c'=>'count(id)'));

		
		$currentPage = isset ( $paging [0] ) ? $paging [0] : 1;
		$limit = isset ( $paging [1] ) ? $paging [1] : 20;
		$offset = ($currentPage - 1) * $limit;
		$select->limit ( $limit );
		$select->offset ( $offset );
		$select->order ( 'st.id DESC' );
		
		$selectStr = $dbSql->getSqlStringForSqlObject($select);
		$rCountStr = $dbSql->getSqlStringForSqlObject($rCount);		
		$results = $dbAdapter->query($selectStr,$dbAdapter::QUERY_MODE_EXECUTE);
		$count = $dbAdapter->query($rCountStr,$dbAdapter::QUERY_MODE_EXECUTE);
		
		$rs = array();
		if(count($results)){
			foreach ($results as $rows){
				$model = new \Admin\Model\Store();
				$model->exchangeArray((array) $rows);
				$rs[] = $model;
			}
		}
		return new \Base\Dg\Paginator ( $count->count (), $rs, $paging, count ( $results ) );
	}
	public function save($model){
		$data = array(
			'parentId'=>$model->getParentId(),
			'name'=> htmlentities($model->getName()),
			'logo'=>$model->getLogo()?: null,
			'username'=> htmlentities($model->getUserName()),
			'password'=> htmlentities($model->getPassword()),
			'address'=> htmlentities($model->getAddress()),
			'email'=> htmlentities($model->getEmail()),
			'mobile'=> htmlentities($model->getMobile()),
			'status'=>$model->getStatus()
		);
		/*@var $dbAdapter \Zend\Db\Adapter\Adapter */
		$dbAdapter = $this->getServiceLocator()->get('dbAdapter');		
		/* @var $dbSql \Zend\Db\Sql\Sql */
		$dbSql = $this->getServiceLocator()->get('dbSql');
		if($model->getId() == null){
			$insert = $dbSql->insert($this->getTableName());
			$insert->values($data);
			$insertStr = $dbSql->getSqlStringForSqlObject($insert);
			return $dbAdapter->query($insertStr,$dbAdapter::QUERY_MODE_EXECUTE);
		}
		else{
			$update = $dbSql->update($this->getTableName());
			$update->set($data);
			$updateStr = $dbSql->getSqlStringForSqlObject($update);
			return $dbAdapter->query($updateStr,$dbAdapter::QUERY_MODE_EXECUTE);
		}
		
	}
}


















