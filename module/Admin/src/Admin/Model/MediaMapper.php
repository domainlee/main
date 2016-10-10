<?php
namespace Admin\Model;
use Base\Mapper\Base;

class MediaMapper extends Base{
	
	protected $tableName = 'media';

    public function get($item)
    {
        if(!$item->getFileName()){
            return null;
        }
        $select = $this->getDbSql()->select(array('m' => $this->getTableName()));

        if($item->getFileName()){
            $name = htmlentities($item->getFileName());
//            $select->where(['m.fileName'=> htmlentities($item->getFileName())]);
            $select->where("m.fileName LIKE '%{$name}%'");
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

	/**
	 * @param \Admin\Model\Media $model
	 */
	public function save($model) {
		$da = new \Base\Model\RDate;
		$data = array(
            'storeId' => htmlentities($model->getStoreId()),
            'type' => htmlentities($model->getType()),
            'fileName'=> htmlentities($model->getFileName()),
            'createdById' => $model->getCreatedById(),
            'createdDateTime' => $model->getCreatedDateTime() ?: \Base\Model\RDate::getCurrentDateTime(),
		);
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

    public function search($item, $paging){
        $dbAdapter = $this->getServiceLocator()->get('dbAdapter');
        /* @var $dbSql \Zend\Db\Sql\Sql */
        $dbSql = $this->getServiceLocator()->get('dbSql');
        $select = $dbSql->select(array('ac'=> $this->getTableName()));

//        $rCount = $dbSql->select(array('ac'=> $this->getTableName()),array('p'=>'count(id)'));
//        $select->join(array('a'=>'article_categories'),
//            'a.id = ac.categoryId',array(
//                'cateName' => 'name'
//            ), \Zend\Db\Sql\Select::JOIN_LEFT
//        );
//        print_r($item->getOptions()['loadAll']);die;



        if(isset($item->getOptions()['loadAll']) && $item->getOptions()['loadAll'] == 'false'){
            $select->join(['mi' => 'media_item'], 'ac.id=mi.fileItem', ['sort', 'itemId', 'type']);
            if($item->getId()){
                $select->where(array('ac.id'=> $item->getId()));
    //            $rCount->where(array('ac.id'=> $item->getId()));
            }
            if(isset($item->getOptions()['itemId'])){
                $select->where(array('mi.itemId'=> $item->getOptions()['itemId']));
    //            $rCount->where(array('mi.itemId'=> $item->getOptions()['itemId']));
            }
            if(isset($item->getOptions()['type'])){
                $select->where(array('mi.type'=> $item->getOptions()['type']));
            }
            $select->group('ac.id');
            if(isset($item->getOptions()['order'])){
                if($item->getOptions()['order'] == 'ASC'){
                    $select->order ('mi.sort ASC');
                }else{
                    $select->order ('ac.id DESC');
                }
            }
        }else{
            if($item->getStoreId()){
                $select->where(array('ac.storeId'=> $item->getStoreId()));
            }
            if($item->getId()){
                $select->where(array('ac.id'=> $item->getId()));
            }
            if(isset($item->getOptions()['order'])){
                $select->order ('ac.id '.$item->getOptions()['order']);
            }
        }


        $currentPage = isset ( $paging [0] ) ? $paging [0] : 1;
        $limit = isset ( $paging [1] ) ? $paging [1] : 20;
        $offset = ($currentPage - 1) * $limit;
        $select->limit($limit);
        $select->offset($offset);



        $selectStr = $dbSql->getSqlStringForSqlObject($select);
//        echo $selectStr;die;
//        $rCountStr = $dbSql->getSqlStringForSqlObject($rCount);
        $results = $dbAdapter->query($selectStr, $dbAdapter::QUERY_MODE_EXECUTE);
//        $count = $dbAdapter->query($rCountStr, $dbAdapter::QUERY_MODE_EXECUTE);
        $rs = array();
        if($results->count()){
            foreach ($results as $row){
                $model = new \Admin\Model\Media();
                $model->exchangeArray((array)$row);
                $rs[] = $model;
            }
        }
        return new \Base\Dg\Paginator(count($results),$rs, $paging, count($results));

    }

    public function search2($item, $paging){
        $dbAdapter = $this->getServiceLocator()->get('dbAdapter');
        /* @var $dbSql \Zend\Db\Sql\Sql */
        $dbSql = $this->getServiceLocator()->get('dbSql');
        $select = $dbSql->select(array('ac'=> $this->getTableName()));

//        $rCount = $dbSql->select(array('ac'=> $this->getTableName()),array('p'=>'count(id)'));

//        $select->join(['mi' => 'media_item'], 'ac.id=mi.fileItem', ['sort', 'itemId']);

//        $select->join(array('a'=>'article_categories'),
//            'a.id = ac.categoryId',array(
//                'cateName' => 'name'
//            ), \Zend\Db\Sql\Select::JOIN_LEFT
//        );

//        if($item->getId()){
//            $select->where(array('ac.id'=> $item->getId()));
//            $rCount->where(array('ac.id'=> $item->getId()));
//        }

//        $select->group('ac.id');

        $currentPage = isset ( $paging [0] ) ? $paging [0] : 1;
        $limit = isset ( $paging [1] ) ? $paging [1] : 20;
        $offset = ($currentPage - 1) * $limit;
        $select->limit($limit);
        $select->offset($offset);

//        if($item->getOptions()['itemId']){
//            $select->where(array('mi.itemId' => $item->getOptions()['itemId']));
////            $rCount->where(array('mi.itemId' => $item->getOptions()['itemId']));
//        }

//        $select->order ('mi.sort ASC');

        $selectStr = $dbSql->getSqlStringForSqlObject($select);
//        $rCountStr = $dbSql->getSqlStringForSqlObject($rCount);
//        print_r($selectStr);die;

        $results = $dbAdapter->query($selectStr, $dbAdapter::QUERY_MODE_EXECUTE);
//        $count = $dbAdapter->query($rCountStr, $dbAdapter::QUERY_MODE_EXECUTE);
        $rs = array();
        if($results->count()){
            foreach ($results as $row){
                $model = new \Admin\Model\Media();
                $model->exchangeArray((array)$row);
                $rs[] = $model;
            }
        }

//        print_r($rs);die;

        return new \Base\Dg\Paginator(count($results),$rs, $paging, count($results));

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






















