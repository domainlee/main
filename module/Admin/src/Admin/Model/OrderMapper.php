<?php
namespace Admin\Model;

use Base\Mapper\Base;
class OrderMapper extends Base{

	protected $tableName = 'orders';
    const TABLE_NAME = 'orders';

    public function search($item,$paging){
        /* @var $dbAdapter \Zend\Db\Adapter\Adapter */
        $dbAdapter = $this->getServiceLocator()->get('dbAdapter');
        /* @var $dbSql \Zend\Db\Sql\Sql */
        $dbSql = $this->getServiceLocator()->get('dbSql');

        $select = $dbSql->select(array('p'=>self::TABLE_NAME));
        $rCount = $dbSql->select(array('p'=>self::TABLE_NAME),array('c'=>'count(id)'));

        $selectStr = $dbSql->getSqlStringForSqlObject($select);
        $productId = [];
        $results = $dbAdapter->query($selectStr,$dbAdapter::QUERY_MODE_EXECUTE);
        foreach($results as $r){
            $productId[] = $r['id'];
        }
        unset($select);

        $products = [];
        $select = $dbSql->select(array('o'=> 'order_products'));
        $select->where(array('o.orderId'=> $productId));
        $selectStr = $dbSql->getSqlStringForSqlObject($select);
        $results = $dbAdapter->query($selectStr,$dbAdapter::QUERY_MODE_EXECUTE);
        foreach($results as $r){

            $select = $dbSql->select(array('pa'=> 'product_attr'));
            $select->where(array('pa.id'=> [$r['productColor'], $r['productSize']]));
            $selectStr = $dbSql->getSqlStringForSqlObject($select);
            $attrs = [];
            $results = $dbAdapter->query($selectStr,$dbAdapter::QUERY_MODE_EXECUTE);
            if(count($results)){
                foreach($results as $pa){
                    $attr = new \Admin\Model\Attr();
                    $attr->exchangeArray((array)$pa);
                    $attrs[] = $attr;
                }
            }

            $product = new \Admin\Model\OrderProduct();
            $select = $dbSql->select(array('p'=> 'products'));
            $select->where(array('p.id'=> $r['productId']));
            $selectStr = $dbSql->getSqlStringForSqlObject($select);
            $results = $dbAdapter->query($selectStr,$dbAdapter::QUERY_MODE_EXECUTE);
            if(count($results)){
                foreach($results as $p){
                    $product->addOption('productName',$p['name']);
                    $product->addOption('priceOld',$p['priceOld']);
                    $product->addOption('price',$p['price']);
                }
            }

            $product->addOption('attr', $attrs);
            $product->exchangeArray((array) $r);
            $products[$r['orderId']][$r['id']] = $product;
        }
//        print_r($products);die;
        unset($select);

        $select = $dbSql->select(array('p'=>self::TABLE_NAME));
        $rCount = $dbSql->select(array('p'=>self::TABLE_NAME),array('c'=>'count(id)'));

        if($item->getId()){
            $select->where(array('p.id'=>$item->getId()));
            $rCount->where(array('p.id'=>$item->getId()));
        }
        if($item->getStoreId()){
            $select->where(array('p.storeId'=>$item->getStoreId()));
            $rCount->where(array('p.storeId'=>$item->getStoreId()));
        }
//        if($item->getName()){
//            $select->where("p.name LIKE '%{$item->getName()}%'");
//            $rCount->where("p.name LIKE '%{$item->getName()}%'");
//        }
        $currentPage = isset ( $paging [0] ) ? $paging [0] : 1;
        $limit = isset ( $paging [1] ) ? $paging [1] : 20;
        $offset = ($currentPage - 1) * $limit;
        $select->limit ( $limit );
        $select->offset ( $offset );
        $select->order ( 'p.id DESC' );

        $selectStr = $dbSql->getSqlStringForSqlObject($select);
        $rCountStr = $dbSql->getSqlStringForSqlObject($rCount);
        $results = $dbAdapter->query($selectStr,$dbAdapter::QUERY_MODE_EXECUTE);
        $count = $dbAdapter->query($rCountStr,$dbAdapter::QUERY_MODE_EXECUTE);
        $rs = array();
        if(count($results)){
            foreach ($results as $rows){
                    $model = new \Admin\Model\Order();
                    if(isset($products[$rows['id']])){
                        $model->addOption('product', $products[$rows['id']]);
                    }
                    $model->exchangeArray((array) $rows);
                    $rs[] = $model;
            }
        }

        return new \Base\Dg\Paginator ( $count->count (), $rs, $paging, count ( $results ) );
    }


	public function updateQtt($item){
// 		$data = array(
// 				'quantity' => $model->getQuantity(),
// 		);
		/* @var $dbAdapter \Zend\Db\Adapter\Adapter */
		$dbAdapter = $this->getServiceLocator()->get('dbAdapter');
		/* @var $dbSql \Zend\Db\Sql\Sql */
		$dbSql = $this->getServiceLocator()->get('dbSql');
		$select = $dbSql->select(array("od" => $this->getTableName()));
		$selectString = $dbSql->getSqlStringForSqlObject($select);
		$results = $dbAdapter->query($selectString,$dbAdapter::QUERY_MODE_EXECUTE);
		$rs = array();
		$orderIds = array();
		if($results->count()) {
			foreach ($results as $row) {
				$model = new \Admin\Model\Order();
				$model->exchangeArray((array)$row);
		
				$orderIds[] = $model->getId();
				$rs[$model->getId()] = $model;
				unset($row);
			}
		}
		// get detail bill
		unset($select);
		unset($selectString);
		unset($results);
		$proId = array();
		if(count($orderIds)) {
			$select = $dbSql->select(array("od" => 'order_products'));
			$select->join(array('d' => 'products'),
					'd.id = od.productId', array(
							'd.name' => 'name',
							'd.price' => 'price',
							'd.quantity'=>'quantity',
					));
				
			$select->where(array("od.orderId" => $orderIds));
			$selectString = $dbSql->getSqlStringForSqlObject($select);
		
			$results = $dbAdapter->query($selectString, $dbAdapter::QUERY_MODE_EXECUTE);
		
			if($results->count()) {
				foreach ($results as $row) {
					$orderDish = new \Admin\Model\OrderProduct();
					$orderDish->exchangeArray((array)$row);
					$dish = new \Admin\Model\Product();
					
					$dish->setName($row['d.name']);
					$dish->setPrice($row['d.price']);
					$quantity = ($dish->getQuantity() - $orderDish->getQuantity());
				//	$rs[$orderDish->getOrderId()]->qtt($dish->getQuantity() - $orderDish->getQuantity());
					$rs[$orderDish->getOrderId()]->addMoney($dish->getQuantity() * $orderDish->getQuantity());
// 					echo $dish->getQuantity();
// 					echo $orderDish->getQuantity();
					//echo $quantity;
					//$item->setQuantity($qtt);
					$orderDish->setProduct($dish);
					//$orderDish->setModifierName($row['md.name']);
					$proId[] = $orderDish->getProductId();
					
				}
				
			}
			$data = array(
					'quantity'=>$item->getQuantity()
			);
			$update = $dbSql->update('products');
			$update->set($data);
			$update->where(array(
					'id'=>$proId
			));
			$updateStr = $dbSql->getSqlStringForSqlObject($update);
			echo $updateStr;
			return $dbAdapter->query($updateStr,$dbAdapter::QUERY_MODE_EXECUTE);
		}
	}
	
// 	public function getId($id){
// 		/* @var $dbAdapter \Zend\Db\Adapter\Adapter */
// 		$dbAdapter = $this->getServiceLocator()->get('dbAdapter');
// 		/* @var $dbSql \Zend\Db\Sql\Sql */
// 		$dbSql = $this->getServiceLocator()->get('dbSql');
		
// 		$select = $dbSql->select(array('od'=>$this->getTableName()));
// 		$select->where(array(
// 				'od.id'=>$id
// 		));
// 		$selectStr = $dbSql->getSqlStringForSqlObject($select);
// 		$results = $dbAdapter->query($selectStr,$dbAdapter::QUERY_MODE_EXECUTE);
// 		if(count($results)){
// 			foreach ($results as $rows){
// 				$model = new \Admin\Model\Order();
// 				$data = (array)$results->current();
// 				$model->exchangeArray($data);
// 				return $model;
// 			}
// 		}
// 	}
	public function getId($id){
		/* @var $dbAdapter \Zend\Db\Adapter\Adapter */
		$dbAdapter = $this->getServiceLocator()->get('dbAdapter');
		/* @var $dbSql \Zend\Db\Sql\Sql */
		$dbSql = $this->getServiceLocator()->get('dbSql');
	
		$select = $dbSql->select(array('od'=>$this->getTableName()));
		$select->where(array('od.id'=>$id));
		$selectStr = $dbSql->getSqlStringForSqlObject($select);
		$results = $dbAdapter->query($selectStr,$dbAdapter::QUERY_MODE_EXECUTE);
		if(count($results)){
			foreach ($results as $row){
				$model = new \Admin\Model\Order();
				$data = (array)$results->current();
				$model->exchangeArray($data);
				return $model;
			}
		}
	
	}
	public function save($model){
		$data = array(
			'storeId'=>$model->getStoreId(),
			'shippingType'=>$model->getShippingType(),
			'customerName'=>$model->getCustomerName(),
			'customerAddress'=>$model->getCustomerAddress(),
			'customerMobile'=>$model->getCustomerMobile(),
			'customerEmail'=>$model->getCustomerEmail(),
			'description'=>$model->getDescription(),
			'createdbyId'=>$model->getCreatedbyId(),
			'createdDateTime'=>$model->getCreatedDateTime(),
			'confirmedDateTime'=>$model->getConfirmedDateTime(),
			'status'=>$model->getStatus()
		);
		/* @var $dbAdapter \Zend\Db\Adapter\Adapter */
		$dbAdapter = $this->getServiceLocator()->get('dbAdapter');
		/* @var $dbSql \Zend\Db\Sql\Sql */
		$dbSql = $this->getServiceLocator()->get('dbSql');
		if(($id = $model->getId()) === null){
			//echo 'Id null';die();
			$insert = $dbSql->insert($this->getTableName());
			$insert->values($data);
			$query = $dbSql->getSqlStringForSqlObject($insert);
			$results = $dbAdapter->query($query, $dbAdapter::QUERY_MODE_EXECUTE);
		}
		else {
			$update = $dbSql->update($this->getTableName());
			$update->set($data);
			$update->where(array("id" => (int)$model->getId()));
			$selectString = $dbSql->getSqlStringForSqlObject($update);
		
			$results = $dbAdapter->query($selectString, $dbAdapter::QUERY_MODE_EXECUTE);
		}
		return $results;
	}
		
	
} 





















