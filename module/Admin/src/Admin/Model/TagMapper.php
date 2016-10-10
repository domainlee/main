<?php
namespace Admin\Model;
use Base\Mapper\Base;

class TagMapper extends Base{
	
	protected $tableName = 'tag';
    CONST TABLE_NAME = 'tag';

    public function get($item)
    {
        if(!$item->getId() && !$item->getName()){
            return null;
        }
        $select = $this->getDbSql()->select(array('ar' => $this->getTableName()));

        if($item->getId()){
            $select->where(['ar.id' => $item->getId()]);
        }
        if($item->getName()){
            $select->where(['ar.name LIKE ?' => $item->getName()]);
        }

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

	public function fetchAll($item)
	{
		/* @var $dbAdapter \Zend\Db\Adapter\Adapter */
		$dbAdapter = $this->getServiceLocator()->get('dbAdapter');
		/* @var $dbSql \Zend\Db\Sql\Sql */
		$dbSql = $this->getServiceLocator()->get('dbSql');
		$select = $dbSql->select(array("a" => $this->getTableName()));
		if($item->getName()) {
			$select->where("a.name LIKE '%{$item->getName()}%'");
		}
		$selectString = $dbSql->getSqlStringForSqlObject($select);
		$results = $dbAdapter->query($selectString, $dbAdapter::QUERY_MODE_EXECUTE);
		$rs = array();
		if($results->count()) {
			foreach ($results as $row) {
				$model = new \Admin\Model\Tag();
				$model->exchangeArray((array)$row);
				$rs[] = $model;
			}
		}
		return $rs;
	}

	/**
	 * @param \Admin\Model\Tag $model
	 */
	public function save($model) {
		$da = new \Base\Model\RDate;
		$data = array(
            'name' => htmlentities($model->getName()),
            'createdById'=>$model->getCreatedById(),
            'createdDateTime' => $model->getCreatedDateTime(),
		);
		/* @var $dbAdapter \Zend\Db\Adapter\Adapter */
		$dbAdapter = $this->getServiceLocator()->get('dbAdapter');
		/* @var $dbSql \Zend\Db\Sql\Sql */
		$dbSql = $this->getServiceLocator()->get('dbSql');
//		if (!$model->getId()) {
			$insert = $dbSql->insert($this->getTableName());
			$insert->values($data);
			$query = $dbSql->getSqlStringForSqlObject($insert);
			$results = $dbAdapter->query($query, $dbAdapter::QUERY_MODE_EXECUTE);
            $model->setId($results->getGeneratedValue());

//		} else {
//			$update = $dbSql->update($this->getTableName());
//			$update->set($data);
//			$update->where(array("id" => (int)$model->getId()));
//			$selectString = $dbSql->getSqlStringForSqlObject($update);
//			$results = $dbAdapter->query($selectString, $dbAdapter::QUERY_MODE_EXECUTE);
//		}
		return $results;
	}

	public function delete($item){
		/* @var $dbAdapter \Zend\Db\Adapter\Adapter */
		$dbAdapter = $this->getServiceLocator()->get('dbAdapter');
	
		/* @var $dbSql \Zend\Db\Sql\Sql */
		$dbSql = $this->getServiceLocator()->get('dbSql');
		$dbAdapter = $this->getServiceLocator()->get('dbAdapter');
		$select = $dbSql->delete($this->getTableName());
		$select->where(array('id'=>$item->getId()));
		$selectStr = $dbSql->getSqlStringForSqlObject($select);
		return $dbAdapter->query($selectStr,$dbAdapter::QUERY_MODE_EXECUTE);
	}
}






















