<?php

namespace News\Model;

use Base\Mapper\Base;

/**
 * Class CategoryMapper
 * @package News\Model
 */
Class CategoryMapper extends Base
{
    /**
     * @var string
     */
    protected $tableName = 'article_categories';

    CONST TABLE_NAME = 'article_categories';

    /**
     * @options     'childs' -> true/false: is allow get all childs
     * @param Category $category
     * @return null|Category
     */
    public function get(Category $category)
    {
//        $dbAdapter = $this->getDbSlaveAdapter();
        $dbAdapter = $this->getServiceLocator()->get('dbAdapter');

        $dbSql = $this->getDbSql();

        $select = $dbSql->select(['atCate' => $this->getTableName()]);
        if ($category->getId()) {
            $select->where(['id' => $category->getId()]);
        }
        $select->where(['atCate.status = ?' => Category::STATUS_ACTIVE]);
        $select->where(['atCate.storeId = ?' => $category->getStoreId()]);
        $selectStr = $dbSql->getSqlStringForSqlObject($select);
        $result = $dbAdapter->query($selectStr, $dbAdapter::QUERY_MODE_EXECUTE);

        if (!$result->count()) {
            return null;
        }

        $category->exchangeArray((array)$result->current());
        $category->setServiceLocator($this->getServiceLocator());

//        if ($category->getOption('childs') && $category->getOption('childs') == true) {
            $category->setChilds($this->fetchTree($category));
//        }

        return $category;
    }

    /**
     * @param $category \News\Model\Category
     * @return array
     */
    public function fetchTree(Category $category)
    {
//        $dbAdapter = $this->getDbSlaveAdapter();
        $dbAdapter = $this->getServiceLocator()->get('dbAdapter');

        $dbSql = $this->getDbSql();

        $select = $dbSql->select(["artc" => $this->getTableName()]);

        if ($category->getStatus()) {
            $select->where(["artc.status" => $category->getStatus()]);
        } else {
            $select->where(["artc.status" => $category::STATUS_ACTIVE]);
        }
        if ($category->getStoreId()) {
            $select->where(['artc.storeId = ?' => $category->getStoreId()]);
        } else {
            $select->where(['artc.storeId = ?' => $this->getServiceLocator()->get('Store\Service\Store')->getStoreId()]);
        }
        if ($category->getDomainId()) {
            $select->where(['artc.domainId = ?' => $category->getDomainId()]);
        }
        if ($category->getOption('excludedIds')) {
            $select->where(['artc.id NOT IN (?)' => $category->getOption('excludedIds')]);
        }
        $selectStr = $dbSql->getSqlStringForSqlObject($select);

        $results = $dbAdapter->query($selectStr, $dbAdapter::QUERY_MODE_EXECUTE);

        $categories = [];
        $cates = [];
        if ($results->count()) {
            foreach ($results as $row) {
                $cat = new Category();
                $cat->exchangeArray((array)$row);
                $cat->setServiceLocator($this->getServiceLocator());
                $categories[] = $cat;
            }
            if ($category->getId()) {
                /* @var $c \News\Model\Category */
                /* @var $subC \News\Model\Category */
                foreach ($categories as $c) {
                    foreach ($categories as $subC) {
                        if ($c->getId() == $subC->getParentId())
                            $c->addChild($subC);
                    }
                    if ($c->getParentId() == $category->getId()) {
                        $cates[] = $c;
                    }
                }
            } else {
                /* @var $c \News\Model\Category */
                /* @var $subC \News\Model\Category */
                foreach ($categories as $c) {
                    foreach ($categories as $subC) {
                        if ($c->getId() == $subC->getParentId())
                            $c->addChild($subC);
                    }
                    if (!$c->getParentId())
                        $cates[] = $c;
                }
            }
        }
        return $cates;
    }

    /**
     * @param Category $category
     * @return null
     */
    public function fetchParent(Category $category)
    {
        if (!$category->getParentId()) {
            return null;
        }

//        $dbAdapter = $this->getDbSlaveAdapter();
        $dbAdapter = $this->getServiceLocator()->get('dbAdapter');

        $dbSql = $this->getDbSql();

        $select = $dbSql->select(["ac" => self::TABLE_NAME]);
        $select->where(["ac.id = ?" => $category->getParentId()]);
        $select->where(['ac.storeId = ?' => $category->getStoreId()]);

        $selectStr = $dbSql->getSqlStringForSqlObject($select);

        $results = $dbAdapter->query($selectStr, $dbAdapter::QUERY_MODE_EXECUTE);

        if ($results->count()) {
            $parent = new Category();
            $parent->exchangeArray((array)$results->current());
            $category->setParent($parent);

            if ($parent->getParentId()) {
                $this->fetchParent($parent);
            }
        }
        return false;
    }

    /**
     * get childs id of category
     * @param \News\Model\Category $category
     * @return array|null
     */
    public function getCategory($category, $options = null)
    {
//        $dbAdapter = $this->getDbSlaveAdapter();
        $dbAdapter = $this->getServiceLocator()->get('dbAdapter');

        $dbSql = $this->getDbSql();

        $select = $dbSql->select(["artc" => $this->getTableName()]);
        $select->where(['artc.status' => 1]);
        $select->where(['storeId = ?' => $category->getStoreId()]);
        if (isset($options['limit']) && $options['limit'] > 0) {
            $select->limit($options['limit']);
        }
        else {
        	$select->limit(40);
        } 
        if (isset($options['id']) && $options['id']) {
            $select->where(['id' => $options['id']]);
        }
        if ($category->getParentId()) {
            $select->where(['parentId' => $category->getParentId()]);
        } else {
            $select->where('parentId IS NULL');
        }
        $selectStr = $dbSql->getSqlStringForSqlObject($select);

        $results = $dbAdapter->query($selectStr, $dbAdapter::QUERY_MODE_EXECUTE);

        if ($results->count()) {
            $categories = [];
            foreach ($results as $row) {
                $cat = new Category();
                $cat->exchangeArray((array)$row);
                $categories[] = $cat;
            }
            return $categories;
        }
        return null;
    }

    /**
     * @param $category \News\Model\Category
     * @param null $childIds
     * @return array|null
     */
    public function getChildIds($category)
    {
        $categories = $this->fetchTree($category);
        $childIds = $category->getChildIds($categories);
        return $childIds;
    }

    public function getId(Category $category, $options)
    {
//        $dbAdapter = $this->getDbSlaveAdapter();
        $dbAdapter = $this->getServiceLocator()->get('dbAdapter');

        $dbSql = $this->getDbSql();

        $select = $dbSql->select(['atCate' => $this->getTableName()]);
        if(isset($options)){
          $select->where(['id' => $options]);
        }
        $select->where(['atCate.status = ?' => Category::STATUS_ACTIVE]);
        $select->where(['atCate.storeId = ?' => $category->getStoreId()]);
        $selectStr = $dbSql->getSqlStringForSqlObject($select);

        $result = $dbAdapter->query($selectStr, $dbAdapter::QUERY_MODE_EXECUTE);
        if (!$result->count()) {
            return null;
        }
        $cate = [];
        foreach ($result as $row) {
            $cat = new Category();
            $cat->setServiceLocator($this->getServiceLocator());
            $cat->exchangeArray((array)$row);
            $cate[] = $cat;
        }
        return $cate;
    }

}