<?php

namespace Home\Model;

use Base\Mapper\Base;

Class MediaMapper extends Base
{
    /**
     * @var string
     */
    protected $tableName = 'media';

    CONST TABLE_NAME = 'media';

    /**
     * @param $id int
     * @return Article|null
     */
    public function get($id)
    {
        $dbAdapter = $this->getServiceLocator()->get('dbAdapter');

        $dbSql = $this->getDbSql();

        $select = $dbSql->select(['at' => $this->getTableName()]);
        $select->where(['at.id = ?' => $id]);
        $select->where(['at.status = ?' => Article::STATUS_ACTIVE]);

        $selectStr = $dbSql->getSqlStringForSqlObject($select);

        $result = $dbAdapter->query($selectStr, $dbAdapter::QUERY_MODE_EXECUTE);

        if ($result->count()) {
            $news = new Article();
            $news->exchangeArray((array)$result->current());
            return $news;
        }
        return null;
    }

    /**
     * @param Article $article
     * @return array|\Zend\Paginator\Paginator
     */
    public function search(Article $article)
    {
        $dbSql = $this->getDbSql();

        $select = $dbSql->select(['at' => self::TABLE_NAME]);

        $select->where(['at.status = ?' => Article::STATUS_ACTIVE]);

        if ($article->getId()) {
            $select->where(['at.id' => $article->getId()]);
        }
        if ($article->getTitle()) {
            $select->where(['at.title LIKE ?' => '%' . $article->getTitle() . '%']);
        }
        if($article->getType()){
            $select->where(['at.type' => $article->getType()]);
        }
        if ($article->getStoreId()) {
            $select->where(['at.storeId = ?' => $article->getStoreId()]);
        }
        if ($article->getOption('excludedIds')) {
            $select->where(['at.id NOT IN (?)' => $article->getOption('excludedIds')]);
        }
        if ($article->getDomainId()) {
            $select->where(['at.domainId = ?' => $article->getDomainId()]);
        }
        if ($article->getCategoryId() || is_array($categories = $article->getCategoryIds()) && count($categories)) {
            if (is_array($categories = $article->getCategoryIds()) && count($categories)) {
                if ($article->getCategoryId()) {
                    $categories[] = $article->getCategoryId();
                }
                $select->where->in('at.categoryId', $categories);
            } else if ($article->getCategoryId()) {
                /* @var $categoryMapper \News\Model\CategoryMapper */
                $categoryMapper = $this->getServiceLocator()->get('News\Model\CategoryMapper');
                $category = new Category();
                $category->setStoreId($article->getStoreId());
                $category->setId($article->getCategoryId());
                $childIds = $categoryMapper->getChildIds($category);
                $childIds[] = $article->getCategoryId();
                $select->where(['at.categoryId' => $childIds]);

            }
        }
        if ($article->getOption('excludedIdc')) {
            $select->where(['at.categoryId NOT IN (?)' => $article->getOption('excludedIdc')]);
        }

        if (($limit = $article->getOption('limit')) && ($limit = abs($limit)) > 0) {
            $select->limit($limit > 50 ? 50 : $limit);

            if ($article->getOption('offset')) {
                $select->offset($article->getOption('offset'));
            }
            $dbAdapter = $this->getServiceLocator()->get('dbAdapter');
            $selectStr = $dbSql->getSqlStringForSqlObject($select);
            $results = $dbAdapter->query($selectStr, $dbAdapter::QUERY_MODE_EXECUTE);
            $articles = [];
            if ($results->count()) {
                foreach ($results as $row) {
                    $a = new Article();
                    $a->exchangeArray((array)$row);
                    $articles[] = $a;
                }
            }
            return $articles;
        }
        if ($article->getOption('icpp', 1) <= 0 || $article->getOption('icpp') > 50) {
            $article->addOption('icpp', 50);
        }

        $this->setSelect($select);

        $paginator = $this->getPaginatorForSelect(new Article(), $article->getOption('page', 1), $article->getOption('icpp', 20));
        return $paginator;
    }


}