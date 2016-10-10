<?php
namespace Admin\Model;
use \Base\Mapper\Base;
use Base\Dg\Paginator;

class ProductMapper extends Base{

	const TABLE_NAME = 'products';

	public function get($product){
        if(!$product->getId() && !$product->getName() && !$product->getCategoryId()){
            return null;
        }
//        print_r($product);die;


		/* @var $dbAdapter \Zend\Db\Adapter\Adapter */
		$dbAdapter = $this->getServiceLocator()->get('dbAdapter');
		/* @var $dbSql \Zend\Db\Sql\Sql */
		$dbSql = $this->getServiceLocator()->get('dbSql');
		$select = $dbSql->select(array('p'=> self::TABLE_NAME));
        if($product->getId()){
		    $select->where(array('p.id'=> $product->getId()));
        }
        if($product->getName()){
            $select->where(['p.name LIKE ?' => $product->getName()]);
        }
        if($product->getCategoryId()){
            $select->where(array('p.categoryId'=> $product->getCategoryId()));
        }
        $selectStr = $dbSql->getSqlStringForSqlObject($select);
//        echo $selectStr;die;
		$results = $dbAdapter->query($selectStr,$dbAdapter::QUERY_MODE_EXECUTE);
		if(count($results)){
            $product->exchangeArray((array)$results->current());
            return $product;
		}
	}
	
    public function fetchAll($item)
	{
		/* @var $dbAdapter \Zend\Db\Adapter\Adapter */
		$dbAdapter = $this->getServiceLocator()->get('dbAdapter');
	
		/* @var $dbSql \Zend\Db\Sql\Sql */
		$dbSql = $this->getServiceLocator()->get('dbSql');
		$select = $dbSql->select(array("p" => self::TABLE_NAME));

		$selectString = $dbSql->getSqlStringForSqlObject($select);
		$results = $dbAdapter->query($selectString, $dbAdapter::QUERY_MODE_EXECUTE);
	
		$rs = array();
		if($results->count()) {
			foreach ($results as $row) {
				$model = new \Admin\Model\Productc();
				$model->exchangeArray((array)$row);
				$rs[] = $model;
			}
		}
		return $rs;
	}
	
	public function save($model){
		$data = array(
            'categoryId' => $model->getCategoryId(),
            'storeId' => $model->getStoreId(),
            'brandId' => $model->getBrandId(),
            'name' => htmlentities($model->getName()),
            'code' => $model->getCode(),
            'intro' => htmlentities($model->getIntro()),
            'description' => htmlentities($model->getDescription()),
            'price' => $model->getPrice(),
            'priceOld' => $model->getPriceOld(),
            'quantity' => $model->getQuantity(),
            'status' => $model->getStatus()
		);
		/* @var $dbAdapter \Zend\Db\Adapter\Adapter */
		$dbAdapter = $this->getServiceLocator()->get('dbAdapter');
		/* @var $dbSql \Zend\Db\Sql\Sql */
		$dbSql = $this->getServiceLocator()->get('dbSql');
		if(($id = $model->getId()) == null){
			$insert = $dbSql->insert(self::TABLE_NAME);
			$insert->values($data);
			$insertStr = $dbSql->getSqlStringForSqlObject($insert);
//            echo $insertStr;die;
            $results = $dbAdapter->query($insertStr,$dbAdapter::QUERY_MODE_EXECUTE);
            $model->setId($results->getGeneratedValue());
        }
		else{
			$update = $dbSql->update(self::TABLE_NAME);
			$update->set($data);
			$update->where(array('id'=>$model->getId()));
			$updateStr = $dbSql->getSqlStringForSqlObject($update);
			return $dbAdapter->query($updateStr,$dbAdapter::QUERY_MODE_EXECUTE);
		}
	}

	
	public function search($item,$paging){
		/* @var $dbAdapter \Zend\Db\Adapter\Adapter */
		$dbAdapter = $this->getServiceLocator()->get('dbAdapter');
		/* @var $dbSql \Zend\Db\Sql\Sql */
		$dbSql = $this->getServiceLocator()->get('dbSql');
		
		$select = $dbSql->select(array('p'=>self::TABLE_NAME));
		$rCount = $dbSql->select(array('p'=>self::TABLE_NAME),array('c'=>'count(id)'));
		$select->join(array('pc'=>'product_categories'),
				'pc.id = p.categoryId',array(
						'cateName' => 'name'
				),\Zend\Db\Sql\Select::JOIN_LEFT
		);
//		$select->join(array('pcl'=>'product_color'),
//				'pcl.id = p.colorId',array(
//					'colorName'=>'name'
//				)
//		);
		if($item->getId()){
			$select->where(array('p.id'=>$item->getId()));
			$rCount->where(array('p.id'=>$item->getId()));
		}
		if($item->getStoreId()){
			$select->where(array('p.storeId'=>$item->getStoreId()));
			$rCount->where(array('p.storeId'=>$item->getStoreId()));
		}
		if($item->getName()){
            $name = htmlentities($item->getName());
			$select->where("p.name LIKE '%{$name}%'");
			$rCount->where("p.name LIKE '%{$name}%'");
		}
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
				$model = new \Admin\Model\Product();
				$model->exchangeArray((array) $rows);
				$rs[] = $model;
			}
		}
		return new \Base\Dg\Paginator ( $count->count (), $rs, $paging, count ( $results ) );
	}


	public function delete($item){
		/* @var $dbAdapter \Zend\Db\Adapter\Adapter */
		$dbAdapter = $this->getServiceLocator()->get('dbAdapter');
		/* @var $dbSql \Zend\Db\Sql\Sql */
		$dbSql = $this->getServiceLocator()->get('dbSql');
		$delete = $dbSql->delete(self::TABLE_NAME);
		$delete->where(array('id'=>$item->getId()));
		$deleteStr = $dbSql->getSqlStringForSqlObject($delete);
		return $dbAdapter->query($deleteStr,$dbAdapter::QUERY_MODE_EXECUTE);
	}

    public function convertToKey($data){
        if(!$data){
            return null;
        }
        $result = [];
        foreach($data as $key => $val){
            $keyOut = '';
            switch (strtolower($key)){
                case 'tên sản phẩm':
                    $keyOut = 'name';
                    break;
                case 'mô tả':
                    $keyOut = 'intro';
                    break;
                case 'mã sản phẩm':
                    $keyOut = 'code';
                    break;
                case 'số lượng':
                    $keyOut = 'quantity';
                    break;
                case 'giá':
                    $keyOut = 'price';
                    break;
                case 'giá khuyến mãi':
                    $keyOut = 'priceOld';
                    break;
                case 'tên danh mục sản phẩm':
                    $keyOut = 'categoryId';
                    break;
                case 'tên thương hiệu':
                    $keyOut = 'brandId';
                    break;
                case 'tên file':
                    $keyOut = 'file';
                    break;
            }
            $result[$keyOut] = $val;
        }
        return $result;
    }
}




























