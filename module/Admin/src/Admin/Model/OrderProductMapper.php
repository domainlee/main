<?php
namespace Admin\Model;
use Base\Mapper\Base;
class OrderProductMapper extends Base{
	protected $tableName = 'order_products';
	
	public function save($model){
		$data = array(
			'orderId'=>$model->getOrderId(),
			'productId'=>$model->getProductId(),
			'storeId'=>$model->getStoreId(),
			'productPrice'=>$model->getProductPrice(),
			'quantity'=>$model->getQuantity(),	
		);
		/* @var $dbAdapter \Zend\Db\Adapter\Adapter */
		$dbAdapter = $this->getServiceLocator()->get('dbAdapter');
		/* @var $dbSql \Zend\Db\Sql\Sql */
		$dbSql = $this->getServiceLocator()->get('dbSql');
		
		if($model->getId() == null){
			$insert = $dbSql->insert($this->getTableName());
			$insert->values($data);
			$insertStr = $dbSql->getSqlStringForSqlObject($insert);
			return $dbAdapter->query($insertStr,$dbAdapter::QUERY_MODE_EXECUTE);
		}else{
			$update = $dbSql->update($this->getTableName());
			$update->set($data);
			$update->where(array('id'=>$model->getId()));
			$updateStr = $dbSql->getSqlStringForSqlObject($update);
			return $dbAdapter->query($updateStr,$dbAdapter::QUERY_MODE_EXECUTE);
		}
	}
	public function updateQtt($model){
		$data = array(
				'quantity' => $model->getQuantity(),
		);
		/* @var $dbAdapter \Zend\Db\Adapter\Adapter */
		$dbAdapter = $this->getServiceLocator()->get('dbAdapter');
		/* @var $dbSql \Zend\Db\Sql\Sql */
		$dbSql = $this->getServiceLocator()->get('dbSql');
		$select = $dbSql->select(array('odp'=>'order_products'));
		$select->join(array('od'=>'orders'),
				'od.id = odp.orderId',
				array(
						'name'=>'name'
				)
		);
		$selectStr = $dbSql->getSqlStringForSqlObject($select);
		$results = $dbAdapter->query($selectStr,$dbAdapter::QUERY_MODE_EXECUTE);
		if(count($results)){
			
		}
		if(count($results)){
			foreach ($results as $row){
				$orderPro = new \Admin\Model\OrderProduct();
				$orderPro->exchangeArray((array)$row);
				$product = new \Admin\Model\Product();
				$quantity = ($orderPro->getQuantity() - $product->getQuantity());
				$product->setQuantity($quantity);
				$update = $dbSql->update('products');
				$update->set($data);
				$update->where(array(
					'id'=>$orderPro->getProductId()
				));
				$updateStr = $dbSql->getSqlStringForSqlObject($update);
				echo $updateStr;
				return $dbAdapter->query($updateStr,$dbAdapter::QUERY_MODE_EXECUTE);
			}
		}
	}
}












