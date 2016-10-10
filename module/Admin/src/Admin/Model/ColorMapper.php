<?php
namespace Admin\Model;
use \Base\Mapper\Base;

class ColorMapper extends Base{
	protected $tableName = 'product_color';
	
	public function getId($id){
		/* @var $dbAdapter \Zend\Db\Adapter\Adapter */
		$dbAdapter = $this->getServiceLocator()->get('dbAdapter');
		/* @var $dbSql \Zend\Db\Sql\Sql */
		$dbSql = $this->getServiceLocator()->get('dbSql');
		
		$select = $dbSql->select(array('pcl'=>$this->getTableName()));
		$select->where(array(
			'pcl.id'=>$id
		));
		$selectStr = $dbSql->getSqlStringForSqlObject($select);
		$results = $dbAdapter->query($selectStr,$dbAdapter::QUERY_MODE_EXECUTE);
		if(count($results)){
			foreach ($results as $rows){
				$model = new \Admin\Model\Color();
				$data = (array)$results->current();
				$model->exchangeArray($data);
				return $model;
			}
		}
	}

    public function fetchAll($item){
		/* @var $dbAdapter \Zend\Db\Adapter\Adapter */
		$dbAdapter = $this->getServiceLocator()->get('dbAdapter');
		/* @var $dbSql \Zend\Db\Sql\Sql */
		$dbSql = $this->getServiceLocator()->get('dbSql');
		
		$select = $dbSql->select(array('pcl'=>$this->getTableName()));
		$selectStr = $dbSql->getSqlStringForSqlObject($select);
		$results = $dbAdapter->query($selectStr,$dbAdapter::QUERY_MODE_EXECUTE);
		$rs = array();
		if(count($results)){
			foreach ($results as $rows){
				$model = new \Admin\Model\Color();
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
		
		$select = $dbSql->select(array('pcl'=>$this->getTableName()));
		$rCount = $dbSql->select(array('pcl'=>$this->getTableName()));
		
		$currentPage = isset ( $paging [0] ) ? $paging [0] : 1;
		$limit = isset ( $paging [1] ) ? $paging [1] : 20;
		$offset = ($currentPage - 1) * $limit;
		$select->limit ( $limit );
		$select->offset ( $offset );
		$select->order ( 'pcl.id DESC' );
		
		$selectStr = $dbSql->getSqlStringForSqlObject($select);
		$rCountStr = $dbSql->getSqlStringForSqlObject($rCount);
		$results = $dbAdapter->query($selectStr,$dbAdapter::QUERY_MODE_EXECUTE);
		$count = $dbAdapter->query($rCountStr,$dbAdapter::QUERY_MODE_EXECUTE);
		
		$rs = array();
		if(count($results)){
			foreach ($results as $rows){
				$model = new \Admin\Model\Color();
				$model->exchangeArray((array)$rows);
				$rs[] = $model;
			}
		}
		return new \Base\Dg\Paginator(count($count), $rs, $paging,count($results));
	}
	public function save($model){
		$data = array(
			'name'=>$model->getName(),
			'value'=>$model->getValue(),
		);
		/* @var $dbAdapter \Zend\Db\Adapter\Adapter */
		$dbAdapter = $this->getServiceLocator()->get('dbAdapter');
		/* @var $dbSql \Zend\Db\Sql\Sql */
		$dbSql = $this->getServiceLocator()->get('dbSql');
		
		if(($model->getId()) == null){
			$insert = $dbSql->insert($this->getTableName());
			$insert->values($data);
			$insertStr = $dbSql->getSqlStringForSqlObject($insert);
			return $dbAdapter->query($insertStr,$dbAdapter::QUERY_MODE_EXECUTE);
		}
		else{
			$update = $dbSql->update($this->getTableName());
			$update->set($data);
			$update->where(array("id" => (int)$model->getId()));
			$updateStr = $dbSql->getSqlStringForSqlObject($update);
			return $dbAdapter->query($updateStr,$dbAdapter::QUERY_MODE_EXECUTE);
		}
	}

}
















