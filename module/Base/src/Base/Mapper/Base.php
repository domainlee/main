<?php

namespace Base\Mapper;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
//use Zend\Paginator\Paginator;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Home\Paginator\Paginator;

abstract class Base implements ServiceLocatorAwareInterface {

	/**
	 * @var string
	 */
	protected $tableName;

	/**
	 * @var ServiceLocatorInterface
	 */
	protected $serviceLocator;
	
	protected $select = null;

	/**
	 * @return the $tableName
	 */
	public function getTableName() {
		return $this->tableName;
	}

	/**
	 * @return \Zend\ServiceManager\ServiceManager
	 */
	public function getServiceLocator() {
		return $this->serviceLocator;
	}

	/**
	 * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
	 */
	public function setServiceLocator(ServiceLocatorInterface $serviceLocator) {
		$this->serviceLocator = $serviceLocator;
		return $this;
	}

	/**
	 * @return \Zend\Db\Sql\Sql
	 */
	public function getDbSql() {
		return $this->getServiceLocator()->get('dbSql');
	}

	/**
	 * @return \Zend\Db\Adapter\Adapter
	 */
	public function getDbAdapter() {
		return $this->getServiceLocator()->get('dbAdapter');
	}

    /**
     * return table's primary key
     */
    public function getPrimaryKey() {
        return 'id';
    }
    public function setSelect($select)
    {
    	$this->select = $select;
    	return $this;
    }
    
    /**
     * Get a Select object. If no previous select object was set,
     * create a new \Zend\Db\Sql\Select object.
     *
     * Resets all Select parts before returning, so always a "clean"
     * instance is returned.
     *
     * @return \Zend\Db\Sql\Select
     */
    public function getSelect()
    {
    	if ($this->select === null) {
    		$this->setSelect(new Select());
    	}
    
    	if (!$this->select->isTableReadOnly()) {
    		$this->select->reset(Select::TABLE);
    		$this->select->reset(Select::COLUMNS);
    		$this->select->reset(Select::JOINS);
    		$this->select->reset(Select::WHERE);
    		$this->select->reset(Select::GROUP);
    		$this->select->reset(Select::HAVING);
    		$this->select->reset(Select::LIMIT);
    		$this->select->reset(Select::OFFSET);
    		$this->select->reset(Select::ORDER);
    	}
    
    	return $this->select;
    }

    /**
     * @param $id
     * return single row
     */
    public function getById($id)
    {
        if (is_numeric($id) && $id > 0) {
            $dbAdapter = $this->getServiceLocator()->get('dbAdapter');
            $dbSql = $this->getServiceLocator()->get('dbSql');
            $select = $dbSql->select(array('maintable' => $this->getTableName()));
            $select->where(array('maintable.'.$this->getPrimaryKey() => $id));
            $selectString = $dbSql->getSqlStringForSqlObject($select);
            $result = $dbAdapter->query($selectString, $dbAdapter::QUERY_MODE_EXECUTE);
			return $result;
        }
    }

    /**
     * @param $attrs array
     * return boolean
     */
    public function updateAttributes($id, $attrs)
    {
        if (is_array($attrs) && $id){
            $dbAdapter = $this->getServiceLocator()->get('dbAdapter');
            $dbSql = $this->getServiceLocator()->get('dbSql');
            $update = $dbSql->update($this->getTableName());
            $update->set($attrs);
            $update->where(array($this->getPrimaryKey() => (int)$id));
            $selectString = $dbSql->getSqlStringForSqlObject($update);
            try {
                $dbAdapter->query($selectString, $dbAdapter::QUERY_MODE_EXECUTE);
                return true;
            } catch (Exception $e) {
                return false;
            }
        }
    }

    protected function getPaginatorForSelect($objectPrototype, $page, $icpp = 20)
    {
    	$resultSetPrototype = new ResultSet();
    	$resultSetPrototype->buffer();
    	$resultSetPrototype->setArrayObjectPrototype($objectPrototype);
    
    	$paginatorAdapter = new DbSelect($this->getSelect(), $this->getDbAdapter(), $resultSetPrototype);
    	$paginator = new Paginator($paginatorAdapter);
    
    	$paginator->setItemCountPerPage($icpp);
    	$paginator->setCurrentPageNumber($page);
    
    	return $paginator;
    }

    protected function preparePaginator($select, $paging, $objectPrototype = null)
    {
        $resultSetPrototype = null;
        if($objectPrototype){
            $resultSetPrototype = new ResultSet();
            $resultSetPrototype->buffer();
            $resultSetPrototype->setArrayObjectPrototype($objectPrototype);
        }
        $paginatorAdapter = new DbSelect($select, $this->getDbAdapter(), $resultSetPrototype);
        $paginator = new Paginator($paginatorAdapter);

        if(isset($paging['icpp'])) {
            $paginator->setItemCountPerPage($paging['icpp']);
        }
        if(isset($paging['page'])) {
            $paginator->setCurrentPageNumber($paging['page']);
        }

        return $paginator;
    }
}