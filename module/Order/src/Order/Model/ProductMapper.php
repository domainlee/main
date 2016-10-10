<?php
/**
 * @category  Shop99 library
 * @copyright http://shop99.vn
 * @license   http://shop99.vn/license
 */

namespace Order\Model;

use Zend\Db\Sql\Expression;
use Base\Mapper\Base;

class ProductMapper extends Base
{
    protected $tableName = 'order_products';

    CONST TABLE_NAME = 'order_products';

    /**
     * @param \Order\Model\Product $model
     */
    public function save($model)
    {
        $data = array(
            'orderId' => $model->getOrderId(),
            'productId' => $model->getProductId(),
            'storeId' => $model->getStoreId(),
            'productPrice' => $model->getProductPrice(),
            'productColor' => $model->getProductColor(),
            'productSize' => $model->getProductSize(),
            'quantity' => $model->getQuantity(),
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

    /**
     * @param array $orderIds
     * @return array
     */
    public function getByOrderIds($orderIds)
    {
        $dbAdapter = $this->getDbAdapter();

        $select = $this->getDbSql()->select(array("op" => $this->getTableName()));
        $select->join(
            array('ps' => 'product_stores'),
            'op.productStoreId = ps.id',
            array(
                'ps.parentId'  => 'parentId',
                'ps.storeId'   => 'storeId',
                'ps.code'      => 'code',
                'ps.name'      => 'name',
                'ps.image'     => 'image',
                'ps.imagePath' => 'imagePath',
                'ps.thumbnail' => 'thumbnail'
            )
        );
        $select->where(['op.orderId' => $orderIds]);

        $query = $this->getDbSql()->getSqlStringForSqlObject($select);
        $results = $dbAdapter->query($query, $dbAdapter::QUERY_MODE_EXECUTE);

        if ($results->count()) {
            $orderProducts = array();
            foreach ($results as $row) {
                $product = new \Product\Model\Store();
                $product->setId($row['productStoreId']);
                $product->setStoreId($row['ps.storeId']);
                $product->setCode($row['ps.code']);
                $product->setName($row['ps.name']);
                $product->setImage($row['ps.image']);
                $product->setImagePath($row['ps.imagePath']);
                $product->setThumbnail($row['ps.thumbnail']);

                $orderProduct = new Product();
                $orderProduct->exchangeArray((array)$row);
                $orderProduct->setProduct($product);

                $orderProducts[] = $orderProduct;
            }
            return $orderProducts;
        }
        return null;
    }

    public function getListOrders($orderIds)
    {
        $dbAdapter = $this->getDbAdapter();

        $select = $this->getDbSql()->select(array("o" => OrderMapper::TABLE_NAME));
//        $select->columns(['op.orderId']);
        $select->join(
            array('op' => ProductMapper::TABLE_NAME), 'op.orderId = o.id',
            array('op.quantity' => 'quantity')
        );
        $select->where(['o.id' => $orderIds]);
        $select->where(["op.productStoreId >= 311443 AND op.productStoreId <=311452"]);
//        $select->limit(51);
        $query = $this->getDbSql()->getSqlStringForSqlObject($select);
        $results = $dbAdapter->query($query, $dbAdapter::QUERY_MODE_EXECUTE);

        if ($results->count()) {
            /* @var $ctMaper \Address\Model\CityMapper */
            $ctMapper = $this->getServiceLocator()->get('Address\Model\CityMapper');
            /* @var $dtMapper \Address\Model\DistrictMapper */
            $dtMapper = $this->getServiceLocator()->get('Address\Model\DistrictMapper');
            $orders = array();
            foreach ($results as $result) {
                $order = new Order();
                $row = (array)$result;
                $order->exchangeArray($row);
                $order->setCustomerDistrict($dtMapper->get($row['customerDistrictId']));
                $order->setCustomerCity($ctMapper->get($row['customerCityId']));
                $order->setQuantity($row['op.quantity']);
                $orders[] = $order;
            }
            return $orders;
        }
        return null;
    }

    public function getMostOrderProducts(\Product\Model\Store $ps)
    {
        $dbAdapter = $this->getDbAdapter();
        $options = $ps->getOptions();
        $select = $this->getDbSql()->select(array('ps' => 'product_stores'));
        if (isset($options['selectColumns'])) {
            $select->columns($options['selectColumns']);
        }
        $select->join(array('pso' => $this->getTableName()), 'ps.id=pso.productStoreId',
            array('sale' => new \Zend\Db\Sql\Expression("SUM(IFNULL(pso.quantity,0))")), $select::JOIN_LEFT);

        $select->where(array('pso.date >= ?' => date("Y-m-d", strtotime('-1 month', strtotime(date("Y-m-d"))))));
        if ($ps->getCategoryId()) {
            $cateMapper = $this->getServiceLocator()->get('Product\Model\CategoryMapper');
            $category = new \Product\Model\Category();
            $category->setId($ps->getCategoryId());
            $cate = $cateMapper->get($category);
            $childIds = $cateMapper->getChildIds($cate);
            $childIds[] = $cate->getId();
            $select->where(['ps.categoryId' => $childIds]);
        }
        $select->group('ps.id');
        $select->order('sale DESC, ps.id DESC');
        if (isset($options['limit'])) {
            $select->limit($options['limit']);
        }
        $query = $this->getDbSql()->getSqlStringForSqlObject($select);
        $results = $dbAdapter->query($query, $dbAdapter::QUERY_MODE_EXECUTE);
        if ($results->count()) {
            $ps = [];
            foreach ($results as $row) {
                $product = new \Product\Model\Store();
                $product->exchangeArray((array)$row);
                $ps[] = $product;
            }
            return $ps;
        }
        return null;
    }

    /**
     * @author VanCK
     * @param \Product\Model\Store $product
     * @return array|null|\Product\Model\Store
     */
    public function getLastOrderProducts($product)
    {
    	if (!isset($options['limit'])) {
            $options['limit'] = 4;
        }
        if($options['limit'] > 50) {
        	$options['limit'] = 50;
        }

        $dbAdapter = $this->getDbAdapter();
    	$select = $this->getDbSql()->select(['op' => self::TABLE_NAME]);
    	$select->columns(['productStoreId' => new Expression("DISTINCT productStoreId")]);
    	$select->where(['storeId = ?' => $product->getStoreId()]);
    	$select->order("orderId DESC");
    	$select->limit($options['limit']);

        $query = $this->getDbSql()->getSqlStringForSqlObject($select);
        $results = $dbAdapter->query($query, $dbAdapter::QUERY_MODE_EXECUTE);

        if (!$results->count()) {
        	return null;
        }

        $ids = [];
		foreach ($results as $row) {
			$ids[] = $row['productStoreId'];
        }

		// lấy thông tin sản phẩm
        $select = $this->getDbSql()->select(['ps' => 'product_stores']);
        $select->where(['id' => $ids]);

        $query = $this->getDbSql()->getSqlStringForSqlObject($select);
        $results = $dbAdapter->query($query, $dbAdapter::QUERY_MODE_EXECUTE);
        if (!$results->count()) {
        	return null;
        }

		$ps = [];
		foreach ($results as $row) {
			$product = new \Product\Model\Store();
			$product->exchangeArray((array)$row);
			$ps[] = $product;
		}
		return $ps;
    }
}