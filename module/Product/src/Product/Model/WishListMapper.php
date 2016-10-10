<?php
/**
 * @category    Shop99 library
 * @copyright   http://shop99.vn
 * @license     http://shop99.vn/license
 */

namespace Product\Model;

use Base\Mapper\Base;

class WishListMapper extends Base
{

    /**
     * @var string
     */
    protected $tableName = 'product_wishlist';

    CONST TABLE_NAME = 'product_wishlist';

    public function get(WishList $wishList)
    {
        $select = $this->getDbSql()->select(['w' => self::TABLE_NAME]);
        if ($wishList->getUserId()) {
            $select->where(['w.userId' => $wishList->getUserId()]);
        }
        if ($wishList->getType()) {
            $select->where(['w.type' => $wishList->getType()]);
        }
        if ($wishList->getUserCookie()) {
            $select->where(['w.userCookie' => $wishList->getUserCookie()]);
        }
        if ($wishList->getUserEmail()) {
            $select->where(['w.userEmail' => $wishList->getUserEmail()]);
        }
        if ($wishList->getDomainId()) {
            $select->where(['w.domainId' => $wishList->getDomainId()]);
        }
        if ($wishList->getProductStoreId()) {
            $select->where(['w.productStoreId' => $wishList->getProductStoreId()]);
        }
        if($wishList->getAttrType()){
            $select->where(['w.attrType' => $wishList->getAttrType()]);
        }
        if($wishList->getProductSize()){
            $select->where(['w.productSize' => $wishList->getProductSize()]);
        }
        if($wishList->getProductColor()){
            $select->where(['w.productColor' => $wishList->getProductColor()]);
        }
        $select->limit(1);

        $dbAdapter = $this->getDbAdapter();
        $query = $this->getDbSql()->getSqlStringForSqlObject($select);
        $results = $dbAdapter->query($query, $dbAdapter::QUERY_MODE_EXECUTE);
        if ($results->count()) {
            $wishList->exchangeArray((array)$results->current());
            return $wishList;
        }
        return null;
    }

    /**
     * @param WishList $wishList
     * @return int
     */
    public function save(WishList $wishList)
    {
        $dbAdapter = $this->getDbAdapter();
        $qtt = $wishList->getQuantity();
        if ($this->get($wishList)) {
            $data = [
                'updatedDateTime' => date('Y-m-d H:i:s'),
                'status'          => $wishList::STATUS_ACTIVE
            ];
            if($wishList->getOption('updateQuantity')){
                $data['quantity'] = $qtt;
            }else{
                $data['quantity'] = $qtt;
                $data['quantity'] = $wishList->getQuantity() + $qtt;
            }
            $update = $this->getDbSql()->update(self::TABLE_NAME);
//            print_r($data);die;
            $update->set($data);
            $update->where(['id' => $wishList->getId()]);
            $query = $this->getDbSql()->getSqlStringForSqlObject($update);
            $nProduct = 0;
        } else {
            $data = [
                'type'            => $wishList->getType() ?: null,
                'domainId'        => $wishList->getDomainId() ?: null,
                'productStoreId'  => $wishList->getProductStoreId() ?: null,
                'quantity'        => $wishList->getQuantity() ?: 1,
                'userCookie'      => $wishList->getUserCookie() ?: null,
                'userId'          => $wishList->getUserId() ?: null,
                'userEmail'       => $wishList->getUserEmail() ?: null,
                'status'          => $wishList->getStatus() ?: $wishList::STATUS_ACTIVE,
                'productColor'    => $wishList->getProductColor() ? : null,
                'productSize'     => $wishList->getProductSize() ? : null,
                'attrType'        => $wishList->getAttrType() ? : null,
                'createdDate'     => date('Y-m-d'),
                'createdDateTime' => date('Y-m-d H:i:s'),
            ];
            $insert = $this->getDbSql()->insert(self::TABLE_NAME);
            $insert->values($data);
            $query = $this->getDbSql()->getSqlStringForSqlObject($insert);
            $nProduct = 1;
        }
        $dbAdapter->query($query, $dbAdapter::QUERY_MODE_EXECUTE);
        return $nProduct;
    }

    /**
     * @param WishList $wishList
     * @return \Zend\Db\Adapter\Driver\StatementInterface|\Zend\Db\ResultSet\ResultSet
     */
    public function update(WishList $wishList)
    {
        $dbAdapter = $this->getDbAdapter();
        $update = $this->getDbSql()->update(self::TABLE_NAME);

        $data = [
            'updatedDateTime' => date('Y-m-d H:i:s'),
        ];
        if ($wishList->getOption('data') && is_array($wishList->getOption('data'))) {
            $data = array_merge($data, $wishList->getOption('data'));
        }
        $update->set($data);
        if ($wishList->getType()) {
            $update->where(['type' => $wishList->getType()]);
        }
        if ($wishList->getDomainId()) {
            $update->where(['domainId' => $wishList->getDomainId()]);
        }
        if ($wishList->getUserCookie()) {
            $update->where(['userCookie' => $wishList->getUserCookie()]);
        }
        if ($wishList->getUserId()) {
            $update->where(['userId' => $wishList->getUserId()]);
        }
        if ($wishList->getStatus()) {
            $update->where(['status' => $wishList->getStatus()]);
        }
        $query = $this->getDbSql()->getSqlStringForSqlObject($update);
        return $dbAdapter->query($query, $dbAdapter::QUERY_MODE_EXECUTE);
    }

    /**
     * @param WishList $wishList
     * @return \Zend\Db\Adapter\Driver\StatementInterface|\Zend\Db\ResultSet\ResultSet
     */
    public function switchHolding(WishList $wishList)
    {
        $dbAdapter = $this->getDbAdapter();
        $update = $this->getDbSql()->update(self::TABLE_NAME);

        $data = [
            'updatedDateTime' => date('Y-m-d H:i:s'),
            'status'          => $wishList->getStatus()
        ];
        $update->set($data);

        if ($wishList->getType()) {
            $update->where(['type' => $wishList->getType()]);
        }
        if ($wishList->getProductStoreId()) {
            $update->where(['productStoreId' => $wishList->getProductStoreId()]);
        }
        if ($wishList->getDomainId()) {
            $update->where(['domainId' => $wishList->getDomainId()]);
        }
        if ($wishList->getUserCookie()) {
            $update->where(['userCookie' => $wishList->getUserCookie()]);
        }
        if ($wishList->getUserId()) {
            $update->where(['userId' => $wishList->getUserId()]);
        }
        $query = $this->getDbSql()->getSqlStringForSqlObject($update);
        return $dbAdapter->query($query, $dbAdapter::QUERY_MODE_EXECUTE);
    }

    /**
     * @param WishList $wishList
     * @return bool
     */
    public function synchronizeCart(WishList $wishList)
    {
        if (!($userId = $wishList->getUserId()) || !($userCookie = $wishList->getUserCookie()) || !$wishList->getDomainId()) {
            return false;
        }
        $dbAdapter = $this->getDbAdapter();

        //---------synchronize wishlist products
        $select = $this->getDbSql()->select(self::TABLE_NAME);
        $select->columns(['productStoreId']);
        $select->where([
            'type'     => $wishList::TYPE_WISHLIST,
            'domainId' => $wishList->getDomainId(),
            'userId'   => $wishList->getUserId()
        ]);
        $query = $this->getDbSql()->getSqlStringForSqlObject($select);
        $results = $dbAdapter->query($query, $dbAdapter::QUERY_MODE_EXECUTE);
        if ($results->count()) {
            $pIds = [];
            foreach ($results as $row) {
                $pIds[] = $row['productStoreId'];
            }

            $delete = $this->getDbSql()->delete(self::TABLE_NAME);
            $delete->where([
                'type'           => $wishList::TYPE_CART,
                'domainId'       => $wishList->getDomainId(),
                'userId'         => $wishList->getUserId(),
                'productStoreId' => $pIds
            ]);
            unset($pIds);
            $query = $this->getDbSql()->getSqlStringForSqlObject($delete);
            $dbAdapter->query($query, $dbAdapter::QUERY_MODE_EXECUTE);
        }

        $wishList->setUserCookie(null);
        $wishList->addOption('data', [
            'type'   => $wishList::TYPE_WISHLIST,
            'status' => $wishList::STATUS_ACTIVE
        ]);
        $this->update($wishList);
        $wishList->setUserId(null);
        $wishList->setUserCookie($userCookie);
        $wishList->addOption('data', [
            'userId'     => $userId,
            'userCookie' => null
        ]);
        $this->update($wishList);
        $wishList->setUserId($userId);
        return true;
    }

    /**
     * @param WishList $wishList
     * @return array|\Zend\Paginator\Paginator
     */
    public function searchProductWishlist(WishList $wishList)
    {
        $select = $this->getDbSql()->select(['p' => \Product\Model\StoreMapper::TABLE_NAME]);
        $select->join(['w' => self::TABLE_NAME], 'p.id = w.productStoreId', []);
        if ($wishList->getUserId()) {
            $select->where(['w.userId' => $wishList->getUserId()]);
        }
        if ($wishList->getType()) {
            $select->where(['w.type' => $wishList->getType()]);
        }
        if ($wishList->getDomainId()) {
            $select->where(['w.domainId' => $wishList->getDomainId()]);
        }
        $select->order('w.id DESC');
        if ($wishList->getOption('limit') && $wishList->getOption('limit') > 0) {
            if ($wishList->getOption('limit') > 50) {
                $wishList->addOption('limit', 50);
            }
            $select->limit($wishList->getOption('limit'));
            if ($wishList->getOption('offset')) {
                $select->offset($wishList->getOption('offset'));
            }
            $dbAdapter = $this->getDbAdapter();
            $query = $this->getDbSql()->getSqlStringForSqlObject($select);
            $results = $dbAdapter->query($query, $dbAdapter::QUERY_MODE_EXECUTE);
            $products = [];
            if ($results->count()) {
                foreach ($results as $row) {
                    $p = new Store();
                    $p->exchangeArray((array)$row);
                    $products[] = $p;
                }
            }
            return $products;
        }
        if ($wishList->getOption('icpp', 1) <= 0 || $wishList->getOption('icpp') > 50) {
            $wishList->addOption('icpp', 50);
        }

        $this->setSelect($select);
        $paginator = $this->getPaginatorForSelect(new Store(), $wishList->getOption('page', 1), $wishList->getOption('icpp', 20));

        return $paginator;
    }

    public function search(WishList $wishList)
    {
        $select = $this->getDbSql()->select(['w' => self::TABLE_NAME]);
        if ($wishList->getUserId()) {
            $select->where(['w.userId' => $wishList->getUserId()]);
        }
        if ($wishList->getUserCookie()) {
            $select->where(['w.userCookie' => $wishList->getUserCookie()]);
        }
        if ($wishList->getType()) {
            $select->where(['w.type' => $wishList->getType()]);
        }
        if ($wishList->getDomainId()) {
            $select->where(['w.domainId' => $wishList->getDomainId()]);
        }
//        if ($wishList->getOption('limit') && $wishList->getOption('limit') > 0) {
//            if ($wishList->getOption('limit') > 50) {
//                $wishList->addOption('limit', 50);
//            }
//            $select->limit($wishList->getOption('limit'));
//        }
        $dbAdapter = $this->getDbAdapter();
        $query = $this->getDbSql()->getSqlStringForSqlObject($select);
        $results = $dbAdapter->query($query, $dbAdapter::QUERY_MODE_EXECUTE);
        if (!$results->count()) {
            return null;
        }
        $wishLists = [];
        foreach ($results as $row) {
            $wishList = new WishList();
            $wishList->exchangeArray((array)$row);
            $wishLists[] = $wishList;
        }
        return $wishLists;
    }

    /**
     * @param WishList $wishList
     * @return int
     */
    public function remove(WishList $wishList)
    {
        $dbAdapter = $this->getDbAdapter();

        $query = $this->getDbSql()->delete($this->getTableName());
        if ($wishList->getDomainId()) {
            $query->where(['domainId' => $wishList->getDomainId()]);
        }
        if ($wishList->getType()) {
            $query->where(['type' => $wishList->getType()]);
        }
        if ($wishList->getUserId()) {
            $query->where(['userId' => $wishList->getUserId()]);
        }
        if ($wishList->getUserCookie()) {
            $query->where(['userCookie' => $wishList->getUserCookie()]);
        }
        if ($wishList->getUserEmail()) {
            $query->where(['userEmail' => $wishList->getUserEmail()]);
        }
        if ($wishList->getProductStoreId()) {
            $query->where(['productStoreId' => $wishList->getProductStoreId()]);
        }
        if ($wishList->getProductSize()) {
            $query->where(['productSize' => $wishList->getProductSize()]);
        }
        if ($wishList->getProductColor()) {
            $query->where(['productColor' => $wishList->getProductColor()]);
        }

        $delete = $this->getDbSql()->getSqlStringForSqlObject($query);
//        echo $delete;die;

        $dbAdapter->query($delete, $dbAdapter::QUERY_MODE_EXECUTE);
        return $this->count($wishList);
    }

    /**
     * @param WishList $wishList
     * @return bool
     */
    public function check(WishList $wishList)
    {
        $select = $this->getDbSql()->select(['w' => self::TABLE_NAME]);
        if ($wishList->getDomainId()) {
            $select->where(['w.domainId' => $wishList->getDomainId()]);
        }
        if ($wishList->getType()) {
            $select->where(['w.type' => $wishList->getType()]);
        }
        if ($wishList->getUserId()) {
            $select->where(['w.userId' => $wishList->getUserId()]);
        }
        if ($wishList->getProductStoreId()) {
            $select->where(['w.productStoreId' => $wishList->getProductStoreId()]);
        }
        $select->limit(1);

        $dbAdapter = $this->getDbAdapter();
        $query = $this->getDbSql()->getSqlStringForSqlObject($select);
        $results = $dbAdapter->query($query, $dbAdapter::QUERY_MODE_EXECUTE);
        if ($results->count()) {
            return true;
        }
        return false;
    }

    /**
     * @param WishList $wishList
     * @return int
     */
    public function count(WishList $wishList)
    {
        $select = $this->getDbSql()->select(self::TABLE_NAME);
        $select->columns(["recordCount" => new \Zend\Db\Sql\Expression("COUNT(1)")]);
        if ($wishList->getDomainId()) {
            $select->where(['domainId' => $wishList->getDomainId()]);
        }
        if ($wishList->getType()) {
            $select->where(['type' => $wishList->getType()]);
        }
        if ($wishList->getUserId()) {
            $select->where(['userId' => $wishList->getUserId()]);
        }
        if ($wishList->getUserCookie()) {
            $select->where(['userCookie' => $wishList->getUserCookie()]);
        }
        if ($wishList->getUserEmail()) {
            $select->where(['userEmail' => $wishList->getUserEmail()]);
        }
        $dbAdapter = $this->getDbAdapter();
        $query = $this->getDbSql()->getSqlStringForSqlObject($select);
        $result = $dbAdapter->query($query, $dbAdapter::QUERY_MODE_EXECUTE);
        if ($result->count()) {
            return (int)$result->current()['recordCount'];
        }
        return 0;
    }

}
