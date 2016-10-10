<?php

namespace Order\Model;

use \Base\Mapper\Base;


class OrderMapper extends Base{

	protected $tableName = 'orders';
    CONST TABLE_NAME = 'orders';

    /**
     * @param \Order\Model\Order $model
     */

    public function save($model)
    {
        $data = array(
            'storeId' => $model->getStoreId(),
            'shippingType' => $model->getShippingType(),
            'customerName' => $model->getCustomerName(),
            'customerAddress' => $model->getCustomerAddress(),
            'customerMobile' => $model->getCustomerMobile(),
            'customerEmail' => $model->getCustomerEmail(),
            'description' => $model->getDescription(),
            'createdById' => $model->getCreatedById() ? : null,
            'createdDateTime' => $model->getCreatedDateTime() ? : null,
            'confirmedDateTime' => $model->getConfirmedDateTime(),
            'status' => $model->getStatus() ? : null,
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

	public function getAllArticle(){
	   
		$dbAdapter = $this->getServiceLocator()->get('dbAdapter');
		
		$dbSql = $this->getServiceLocator()->get('dbSql');
        
		$select = $dbSql->select(array('a'=>$this->getTableName()));
        
        $select->where(array('a.status = ?' => Article::STATUS_ACTIVE));
        
		$selectStr = $dbSql->getSqlStringForSqlObject($select);
        
		$results = $dbAdapter->query($selectStr,$dbAdapter::QUERY_MODE_EXECUTE);
		
		$rs = array();
		
		if(count($results)){
			foreach ($results as $row){
			$model = new \Article\Model\Article();
			$model->exchangeArray((array)$row);
			$rs[] = $model;
			}
		}
		return $rs;
	}
	
}

























