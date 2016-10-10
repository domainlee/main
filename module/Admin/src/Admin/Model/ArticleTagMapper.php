<?php
namespace Admin\Model;
use Base\Mapper\Base;

class ArticleTagMapper extends Base{
	
	protected $tableName = 'article_tags';

    CONST TABLE_NAME = 'article_tags';

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

//	public function fetchAll($item)
//	{
//		/* @var $dbAdapter \Zend\Db\Adapter\Adapter */
//		$dbAdapter = $this->getServiceLocator()->get('dbAdapter');
//		/* @var $dbSql \Zend\Db\Sql\Sql */
//		$dbSql = $this->getServiceLocator()->get('dbSql');
//		$select = $dbSql->select(array("a" => $this->getTableName()));
//		if($item->getName()) {
//			$select->where("a.name LIKE '%{$item->getName()}%'");
//		}
//		$selectString = $dbSql->getSqlStringForSqlObject($select);
//		$results = $dbAdapter->query($selectString, $dbAdapter::QUERY_MODE_EXECUTE);
//		$rs = array();
//		if($results->count()) {
//			foreach ($results as $row) {
//				$model = new \Admin\Model\Tag();
//				$model->exchangeArray((array)$row);
//				$rs[] = $model;
//			}
//		}
//		return $rs;
//	}


    /**
     * @author Mienlv
     * @param \Admin\Model\ArticleTag $item
     */
    public function fetchAll($item){

        $select = $this->getDbSql()->select(array('tt'=> self::TABLE_NAME));
        $dbAdapter = $this->getServiceLocator()->get('dbAdapter');

        if ($item->getArticleId()){
            $select->where(['tt.articleId'=> $item->getArticleId()]);
        }
        if($item->getTagId()){
            $select->where(['tt.tagId'=> $item->getTagId()]);
        }
        $query = $this->getDbSql()->getSqlStringForSqlObject($select);

        $rows = $this->getDbAdapter()->query($query, $dbAdapter::QUERY_MODE_EXECUTE);
        $result =[];
        $tagIds = [];
        if ($rows->count()){
            foreach ($rows as $row){
                $row = (array)$row;
                $articletag = new ArticleTag();
                $articletag->exchangeArray($row);
                $result[] = $articletag;
                $tagIds[$articletag->getTagId()] = $articletag->getTagId();
            }

        } else {
            return null;
        }
        if(isset($tagIds)){
            $tags = [];
            $select = $this->getDbSql()->select(array('t'=> \Admin\Model\TagMapper::TABLE_NAME));
            $select->where(['id' => $tagIds]);
            $query = $this->getDbSql()->getSqlStringForSqlObject($select);

            $rows = $this->getDbAdapter()->query($query,$dbAdapter::QUERY_MODE_EXECUTE);
            if($rows->count()){
                foreach ($rows as $row){
                    $row = (array) $row;
                    $tag = new \Admin\Model\Tag();
                    $tag->exchangeArray($row);
                    $tags[$tag->getId()] = $tag;
                }
            }
        }
        if(count($result)){
            foreach ($result as $tag){
                if(isset($tags[$tag->getTagId()])){
                    $tag->addOption('Tag', $tags[$tag->getTagId()]);
                }
            }
        }
        return $result;
    }


	/**
	 * @param \Admin\Model\ArticleTag $model
	 */
	public function save($model) {
		$da = new \Base\Model\RDate;
		$data = array(
            'articleId' => $model->getArticleId(),
            'tagId'=> $model->getTagId(),
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
//		} else {
//			$update = $dbSql->update($this->getTableName());
//			$update->set($data);
//			$update->where(array("id" => (int)$model->getId()));
//			$selectString = $dbSql->getSqlStringForSqlObject($update);
//			$results = $dbAdapter->query($selectString, $dbAdapter::QUERY_MODE_EXECUTE);
//		}
		return $results;
	}

    /**
     * @author Mienlv
     * @param \Admin\Model\ArticleTag $item
     */
    public function deleteTaskTag($item){
        if(!$item){
            return false;
        }
        /* @var $dbAdapter \Zend\Db\Adapter\Adapter */
        $dbAdapter = $this->getServiceLocator()->get('dbAdapter');

        /* @var $dbSql \Zend\Db\Sql\Sql */
        $dbSql = $this->getServiceLocator()->get('dbSql');
        $delete = $this->getDbSql()->delete(self::TABLE_NAME);
        $delete->where([
            'articleId' => $item
        ]);
        $query = $dbSql->getSqlStringForSqlObject($delete);
        $result = $dbAdapter->query($query,$dbAdapter::QUERY_MODE_EXECUTE);
        return $result;
    }
}






















