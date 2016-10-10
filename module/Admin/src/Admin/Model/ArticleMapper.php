<?php
namespace Admin\Model;
use Base\Mapper\Base;

class ArticleMapper extends Base{
	
	protected $tableName = 'articles';

    public function get($item)
    {
        if (! $item->getId() ) {
            return null;
        }
        $select = $this->getDbSql()->select(array('ar' => $this->getTableName()));
        $select->where(['ar.id' => $item->getId()]);
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
		$select = $dbSql->select(array("ac" => $this->getTableName()));
		$select->join(array('a'=>'article_categories'),
				'a.id = ac.categoryId',array(
						'cateName' => 'name'
				), \Zend\Db\Sql\Select::JOIN_LEFT
		);
		$select->where(array('ac.id'=>$id));
		$selectStr = $dbSql->getSqlStringForSqlObject($select);
		$result = $dbAdapter->query($selectStr, $dbAdapter::QUERY_MODE_EXECUTE);
		if($result->count()){
			
				$model = new \Admin\Model\Article();
				$data = (array)$result->current();
				$model->exchangeArray($data);
				return $model;
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
	
// 		if($item->getParentId()) {
// 			$select->where(array('a.parentId' => $item->getParentId()));
// 		}
		if($item->getExcludedId()) {
			$select->where("a.id <> {$item->getExcludedId()}");
		}
		if($item->getName()) {
			$select->where("a.name LIKE '%{$item->getName()}%'");
		}
		$selectString = $dbSql->getSqlStringForSqlObject($select);
		$results = $dbAdapter->query($selectString, $dbAdapter::QUERY_MODE_EXECUTE);
	
		$rs = array();
		if($results->count()) {
			foreach ($results as $row) {
				$model = new \Admin\Model\Article();
				$model->exchangeArray((array)$row);
				$rs[] = $model;
			}
		}
		return $rs;
	
	
	}
	/**
	 * @param \Admin\Model\Article $model
	 */

	public function save($model) {
		$da = new \Base\Model\RDate;
		$data = array(
            'categoryId' => $model->getCategoryId(),
            'storeId'=> $model->getStoreId(),
            'name' => htmlentities($model->getName()),
            'image' => $model->getImage(),
            'title' => htmlentities($model->getTitle()),
            'type' => $model->getType(),
            'description' => htmlentities($model->getDescription()),
            'content' => htmlentities($model->getContent()),
            'publishedDate' => $model->getPublishedDate() ? : null,
            'expiredDate' => $model->getExpiredDate() ? : null,
            'status' => $model->getStatus(),
            'extraContent' => $model->getExtraContent() ? : null,
//            'createdById' => $model->getCreatedById() ? : null,
            'createdDateTime' => $model->getCreatedDateTime() ?: \Base\Model\RDate::getCurrentDateTime(),
		);
//	    print_r($data);die;
		/* @var $dbAdapter \Zend\Db\Adapter\Adapter */
		$dbAdapter = $this->getServiceLocator()->get('dbAdapter');
		/* @var $dbSql \Zend\Db\Sql\Sql */
		$dbSql = $this->getServiceLocator()->get('dbSql');
		if (!$model->getId()) {
			$insert = $dbSql->insert($this->getTableName());
			$insert->values($data);
			$query = $dbSql->getSqlStringForSqlObject($insert);
			$results = $dbAdapter->query($query, $dbAdapter::QUERY_MODE_EXECUTE);
            $model->setId($results->getGeneratedValue());
        } else {
			$update = $dbSql->update($this->getTableName());
			$update->set($data);
			$update->where(array("id" => (int)$model->getId()));
			$selectString = $dbSql->getSqlStringForSqlObject($update);
			$results = $dbAdapter->query($selectString, $dbAdapter::QUERY_MODE_EXECUTE);
		}
		return $results;
	}
	
	public function search($item,$paging){
		$dbAdapter = $this->getServiceLocator()->get('dbAdapter');
		/* @var $dbSql \Zend\Db\Sql\Sql */
		$dbSql = $this->getServiceLocator()->get('dbSql');
		$select = $dbSql->select(array('ac'=>$this->getTableName()));
		$rCount = $dbSql->select(array('ac'=>$this->getTableName()),array('p'=>'count(id)'));
		$select->join(array('a'=>'article_categories'),
			'a.id = ac.categoryId',array(
			'cateName' => 'name'
			), \Zend\Db\Sql\Select::JOIN_LEFT
		);
		
		if($item->getId()){
			$select->where(array('ac.id'=>$item->getId()));
			$rCount->where(array('ac.id'=>$item->getId()));
		}
		if($item->getStoreId()){
			$select->where(array('ac.storeId'=>$item->getStoreId()));
			$rCount->where(array('ac.storeId'=>$item->getStoreId()));
		}
		if($item->getName()){
			$select->where("ac.name LIKE '%{$item->getName()}%'");
			$rCount->where("ac.name LIKE '%{$item->getName()}%'");
		}
		$currentPage = isset ( $paging [0] ) ? $paging [0] : 1;
		$limit = isset ( $paging [1] ) ? $paging [1] : 20;
		$offset = ($currentPage - 1) * $limit;
		$select->limit ( $limit );
		$select->offset ( $offset );
		$select->order ( 'ac.id DESC' );
		
		$selectStr = $dbSql->getSqlStringForSqlObject($select);
		$rCountStr = $dbSql->getSqlStringForSqlObject($rCount);
		$results = $dbAdapter->query($selectStr, $dbAdapter::QUERY_MODE_EXECUTE);
		$count = $dbAdapter->query($rCountStr, $dbAdapter::QUERY_MODE_EXECUTE);
		$rs = array();
		if($results->count()){
			foreach ($results as $row){
				$model = new \Admin\Model\Article();
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
		$dbAdapter = $this->getServiceLocator()->get('dbAdapter');
		$select = $dbSql->delete($this->getTableName());
		$select->where(array('id'=>$item->getId()));
		$selectStr = $dbSql->getSqlStringForSqlObject($select);
		return $dbAdapter->query($selectStr,$dbAdapter::QUERY_MODE_EXECUTE);
	}
}






















