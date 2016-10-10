<?php
namespace Product\Model;
use \Base\Mapper\Base;

class ProductMapper extends Base{

	protected $tableName = 'products';
    CONST TABLE_NAME = 'products';

// 	public function getId($id){
// 		/* @var $dbAdapter \Zend\Db\Adapter\Adapter */
// 		$dbAdapter = $this->getServiceLocator()->get('dbAdapter');
		
// 		/* @var $dbSql \Zend\Db\Sql\Sql */
// 		$dbSql = $this->getServiceLocator()->get('dbSql');
// 		$select = $dbSql->select(array("p" => $this->getTableName()));
// // 		if($model->getStoreId()){
// // 			$select->where(array('p.storeId'=>$model->getStoreId()));
// // 		}
// 		$select->where(array('p.id'=>$id));
// 		$selectStr = $dbSql->getSqlStringForSqlObject($select);
// 		$results = $dbAdapter->query($selectStr,$dbAdapter::QUERY_MODE_EXECUTE);
		
// 		if(count($results)){
// 				$model = new \Product\Model\Product();
// 				$data = $results->current();
// 				$model->exchangeArray($data);
// 				return $model;			
// 		}
		
// 	}
    public function get($item)
    {
        if(!$item->getId()){
            return null;
        }
        $dbAdapter = $this->getServiceLocator()->get('dbAdapter');
        $dbSql = $this->getDbSql();
        $select = $dbSql->select(['p' => $this->getTableName()]);
        $select->where(['p.id' => $item->getId()]);
        $selectStr = $dbSql->getSqlStringForSqlObject($select);
        $result = $dbAdapter->query($selectStr, $dbAdapter::QUERY_MODE_EXECUTE);
        if($result->count()){
            $item->exchangeArray((array)$result->current());
            return $item;
        }
        return null;
    }

	public function getId($id){
		$dbAdapter = $this->getServiceLocator()->get('dbAdapter');
		$dbSql = $this->getServiceLocator()->get('dbSql');
	
		$select = $dbSql->select(array('p'=>$this->getTableName()));
//		$select->join(array('pcl'=>'product_color'),
//				'pcl.id = p.colorId',
//				array('value' => 'value')
//		);
		$select->where(array('p.id'=> $id->getId()));
		$selectString = $dbSql->getSqlStringForSqlObject($select);
		$results = $dbAdapter->query($selectString, $dbAdapter::QUERY_MODE_EXECUTE);
		if($results->count()){
			$model = new \Product\Model\Product();
			$data = (array)$results->current();
			$model->exchangeArray($data);
			return $model;
		}
		return null;
	}
	
	public function fetchAll($model){
		/* @var $dbAdapter \Zend\Db\Adapter\Adapter */
		$dbAdapter = $this->getServiceLocator()->get('dbAdapter');
		
		/* @var $dbSql \Zend\Db\Sql\Sql */
		$dbSql = $this->getServiceLocator()->get('dbSql');
		$select = $dbSql->select(array("p" => $this->getTableName()));
		if($model->getId()){
			$select->where(array('p.id'=>$model->getId()));
		}
        if($model->getStatus()){
            $select->where(array('p.status'=>$model->getStatus()));
        }
        if($model->getCategoryId()){
//            $select->where(array('p.categoryId'=> $model->getCategoryId()));
            $select->where(['p.categoryId' => $model->getCategoryId()]);
        }
		if($model->getStoreId()){
			$select->where(array('p.storeId'=> $model->getStoreId()));
		}
        if(isset($model->getOptions()['sale'])){
            $select->where(['p.priceOld != 0']);
        }
        if(isset($model->getOptions()['limit'])){
            $select->limit($model->getOptions()['limit'] ? $model->getOptions()['limit']:8);
        }
        $select->order('p.id DESC');


        $selectStr = $dbSql->getSqlStringForSqlObject($select);
		$results = $dbAdapter->query($selectStr,$dbAdapter::QUERY_MODE_EXECUTE);
		$rs = array();
		if(count($results)){
			foreach ($results as $rows){
				$model= new \Product\Model\Product();
				$model->exchangeArray((array)$rows);
				$rs[] = $model;
			}
		}
		return $rs;
	}
    /**
     * @param \Product\Model\Product $item
     */
    public function search($item){

		$dbSql = $this->getDbSql();

        $select = $dbSql->select(['at' => self::TABLE_NAME]);

        $select->where(['at.status = ?' => \Product\Model\Product::STATUS_ACTIVE]);

        if ($item->getId()) {
            $select->where(['at.id' => $item->getId()]);
        }
        if ($item->getStoreId()) {
            $select->where(['at.storeId = ?' => $item->getStoreId()]);
        }
        if ($item->getOption('excludedIds')) {
            $select->where(['at.id NOT IN (?)' => $item->getOption('excludedIds')]);
        }
        if ($item->getOption('excludedIdc')) {
            $select->where(['at.categoryId NOT IN (?)' => $item->getOption('excludedIdc')]);
        }
        if($item->getCategoryId()){
            $select->where(['at.categoryId' => $item->getCategoryId()]);
        }
        if($item->getName()){
            $nameLike = '%' . htmlentities($item->getName()) . '%';
            $select->where(['(at.name LIKE ? OR at.code LIKE ? OR at.intro LIKE ? OR at.description LIKE ?)' => [$nameLike, $nameLike, $nameLike, $nameLike]]);
        }
        if ($item->getPrices()) {
            $str = '(';
            foreach ($item->getPrices() as $pr) {
                $str .= isset($pr[1]) ? 'at.price between ' . (int)($pr[0] + 1) . ' and ' . (int)$pr[1] : 'at.price > ' . (int)$pr[0];
                $str .= ' or ';
            }
            $str = substr($str, 0, -4) . ')';
            $select->where($str);
        }

        if(isset($item->getOptions()['color']) && isset($item->getOptions()['size'])){
            $select->join(['pal' => 'product_attr_list'], 'at.id=pal.productId',['productattrId']);
            $select->where(['pal.productattrId' => [$item->getOptions()['color'], $item->getOptions()['size']]]);
            $select->group('pal.productId');
        }elseif(isset($item->getOptions()['color']) || isset($item->getOptions()['size'])){
            $select->join(['pal' => 'product_attr_list'], 'at.id=pal.productId',['productattrId']);
            if(isset($item->getOptions()['color'])){
                $select->where(['pal.productattrId' => [$item->getOptions()['color']]]);
            }
            if(isset($item->getOptions()['size'])){
                $select->where(['pal.productattrId' => [$item->getOptions()['size']]]);
            }
        }

        if(isset($item->getOptions()['show'])){
            if($item->getOptions()['show'] == 'priceAsc'){
                $select->order('at.price ASC');
            }elseif($item->getOptions()['show'] == 'priceDesc'){
                $select->order('at.price DESC');
            }elseif($item->getOptions()['show'] == 'SaleOff'){
                $select->where(['at.priceOld != 0']);
            }
        }

        if (($limit = $item->getOption('limit')) && ($limit = abs($limit)) > 0) {
            $select->limit($limit > 50 ? 50 : $limit);

            if ($item->getOption('offset')) {
                $select->offset($item->getOption('offset'));
            }
            $dbAdapter = $this->getServiceLocator()->get('dbAdapter');
            $selectStr = $dbSql->getSqlStringForSqlObject($select);
            $results = $dbAdapter->query($selectStr, $dbAdapter::QUERY_MODE_EXECUTE);
            $products = [];
            if ($results->count()) {
                foreach ($results as $row) {
                    $a = new \Product\Model\Product();
                    $a->exchangeArray((array)$row);
//                    $a->setServiceLocator($this->getServiceLocator());
                    $products[] = $a;
                }
            }
            return $products;
        }
        if ($item->getOption('icpp', 1) <= 0 || $item->getOption('icpp') > 50) {
            $item->addOption('icpp', 50);
        }

        $this->setSelect($select);

        $paginator = $this->getPaginatorForSelect(new \Product\Model\Product(), $item->getOption('page', 1), $item->getOption('icpp', 20));
        return $paginator;
	}

	public function getMostView($product){
		/* @var $dbAdapter \Zend\Db\Adapter\Adapter */
		$dbAdapter = $this->getServiceLocator()->get('dbAdapter');
		
		/* @var $dbSql \Zend\Db\Sql\Sql */
		$dbSql = $this->getServiceLocator()->get('dbSql');
		
		$select = $dbSql->select(array('p'=>$this->getTableName()));
		$select->where(array(
			'p.storeId'=>$product->getStoreId()
		));
		$select->limit(4);
		$selectStr = $dbSql->getSqlStringForSqlObject($select);
		$results = $dbAdapter->query($selectStr,$dbAdapter::QUERY_MODE_EXECUTE);
		$rs = array();
		if(count($results)){
			foreach ($results as $rows){
				$model = new \Product\Model\Product();
				$model->exchangeArray((array)$rows);
				$rs[] = $model; 
			}
		}
		return $rs;
	}

	public function getProchild($product,$id){
		/* @var $dbAdapter \Zend\Db\Adapter\Adapter */
		$dbAdapter = $this->getServiceLocator()->get('dbAdapter');
		
		/* @var $dbSql \Zend\Db\Sql\Sql */
		$dbSql = $this->getServiceLocator()->get('dbSql');
		
		$select = $dbSql->select(array('p'=>$this->getTableName()));
		$select->join(array('pcl'=>'product_color'),
				'pcl.id = p.colorId',
				array('value' => 'value')
		);
		$select->where(array(
				'p.storeId'=>$product->getStoreId()
		));
		$select->where(array(
			'p.parentId'=>$id
		));
		$selectStr = $dbSql->getSqlStringForSqlObject($select);
		$results = $dbAdapter->query($selectStr,$dbAdapter::QUERY_MODE_EXECUTE);
		$rs = array();
		if(count($results)){
			foreach ($results as $rows){
				$model = new \Product\Model\Product();
				$model->exchangeArray((array)$rows);
				$rs[] = $model;
			}
		}
		return $rs;
	}

	public function getChild($psId,$model,$attrs){
		/* @var $dbAdapter \Zend\Db\Adapter\Adapter */
		$dbAdapter = $this->getServiceLocator()->get('dbAdapter');
		
		/* @var $dbSql \Zend\Db\Sql\Sql */
		$dbSql = $this->getServiceLocator()->get('dbSql');
		$select = $dbSql->select(array('p'=>$this->getTableName()));
		$select->where(array(
			'p.parentId'=>$psId
		));
		$select->where(array(
				'p.storeId'=>$model->getStoreId()
		));
		$select->where($attrs);
// 		$select->where(array(
// 			'p.status'=>1
// 		));
		$select->limit(1);
		$selectStr = $dbSql->getSqlStringForSqlObject($select);
	//	echo $selectStr;
		$results = $dbAdapter->query($selectStr,$dbAdapter::QUERY_MODE_EXECUTE);
		$product = new \Product\Model\Product();
		$product->exchangeArray((array)$results->current());
		return $product;
	}

	public function getQuantity($model,$id){
		/* @var $dbAdapter \Zend\Db\Adapter\Adapter */
		$dbAdapter = $this->getServiceLocator()->get('dbAdapter');
		
		/* @var $dbSql \Zend\Db\Sql\Sql */
		$dbSql = $this->getServiceLocator()->get('dbSql');
		$select = $dbSql->select(array('p'=>$this->getTableName()));
		$select->where(array(
				'p.storeId'=>$model->getStoreId()
		));
		$select->where(array(
				'p.Id'=>$id
		));
		$selectStr = $dbSql->getSqlStringForSqlObject($select);
		//	echo $selectStr;
		$results = $dbAdapter->query($selectStr,$dbAdapter::QUERY_MODE_EXECUTE);
		$rs = array();
		if(count($results)){
			foreach ($results as $rows){
				$model = new \Product\Model\Product();
				$model->exchangeArray((array)$rows);
				$rs[] = $model;
			}
		}
		return $rs;
	}


}
















