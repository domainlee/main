<?php
namespace Admin\Model;
use Base\Mapper\Base;

class MenuMapper extends Base{
	
	protected $tableName = 'menu';

    public function get($item)
    {
        if (! $item->getId() && !$item->getParentId()) {
            return null;
        }
        $select = $this->getDbSql()->select(array('ar' => $this->getTableName()));
        if($item->getId()){
            $select->where(['ar.id' => $item->getId()]);
        }
        if($item->getParentId()){
            $select->where(['ar.parentId' => $item->getParentId()]);
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

    public function checkExits($item)
    {

        if (!$item->getType() && !$item->getStoreId() && !$item->getItemId() && !$item->getName() ) {
            return null;
        }
        $select = $this->getDbSql()->select(array('ar' => $this->getTableName()));
        $select->where(['ar.type' => $item->getType()]);
        $select->where(['ar.storeId' => $item->getStoreId()]);
        $select->where(['ar.itemId' => $item->getItemId()]);
        $select->where(['ar.name' => $item->getName()]);
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
	
 		if($item->getStoreId()) {
 			$select->where(array('a.storeId' => $item->getStoreId()));
 		}
        $select->order ( 'a.updateDateTime DESC' );

        $selectString = $dbSql->getSqlStringForSqlObject($select);
		$results = $dbAdapter->query($selectString, $dbAdapter::QUERY_MODE_EXECUTE);
	
		$rs = array();
        $category  = [];
		if($results->count()) {
			foreach ($results as $row) {
				$model = new \Admin\Model\Menu();
				$model->exchangeArray((array)$row);
				$rs[] = $model;
			}

            if(count($rs)){
                foreach($rs as $r){
                    foreach($rs as $rr){
                        if($r->getId() === $rr->getParentId()){
                            $r->addChild($rr);
                        }
                    }
                    if(!$r->getParentId()){
                        $category[] = $r;
                    }
                }
            }
            return $category;
		}
		return $rs;
	}
	/**
	 * @param \Admin\Model\Menu $model
	 */
	public function save($model) {
		$data = array(
            'positionId' => htmlentities($model->getPositionId()),
            'type' => htmlentities($model->getType()),
            'storeId' => htmlentities($model->getStoreId()),
            'parentId' => htmlentities($model->getParentId()) ? : null,
            'itemId' => htmlentities($model->getItemId()) ? : null,
            'name' => htmlentities($model->getName()) ? : null,
            'description' => htmlentities($model->getDescription()) ? : null,
            'url' => htmlentities($model->getUrl()) ? : null,
            'updateDateTime' => $model->getUpdateDateTime() ? : null,
            'status' => htmlentities($model->getStatus()) ? : null,
		);
		/* @var $dbAdapter \Zend\Db\Adapter\Adapter */
		$dbAdapter = $this->getServiceLocator()->get('dbAdapter');
		/* @var $dbSql \Zend\Db\Sql\Sql */
		$dbSql = $this->getServiceLocator()->get('dbSql');
		if (!$model->getId()) {
//            echo 'insert';die;
			$insert = $dbSql->insert($this->getTableName());
			$insert->values($data);
			$query = $dbSql->getSqlStringForSqlObject($insert);
			$results = $dbAdapter->query($query, $dbAdapter::QUERY_MODE_EXECUTE);
            $model->setId($results->getGeneratedValue());
        } else {
//            echo 'update';die;
			$update = $dbSql->update($this->getTableName());
			$update->set($data);
            $update->where(['id' => $model->getId()]);
//            $update->where(['storeId' => $model->getStoreId()]);
//            $update->where(['itemId' => $model->getItemId()]);
//            $update->where(['name' => $model->getName()]);

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






















