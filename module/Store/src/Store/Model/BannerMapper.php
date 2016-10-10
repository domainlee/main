<?php
/**
 * @category       Shop99 library
 * @copyright      http://shop99.vn
 * @license        http://shop99.vn/license
 */

namespace Store\Model;

use Base\Mapper\Base;

class BannerMapper extends Base
{

    /**
     * @var string
     */
    protected $tableName = 'store_banners';

    CONST TABLE_NAME = 'store_banners';

    /**
     * @param \Store\Model\Banner $banner
     * @return array|null
     */
    public function getByPositionCode($banner,$options)
    {
        $dbAdapter = $this->getDbAdapter();

        $select = $this->getDbSql()->select('store_banner_positions');
        $select->columns(array('id'));
        $select->where(array(
            'storeId = ?' => (int)($banner->getStoreId()),
            'code = ?'    => $banner->getPositionCode(),
            'status = 1'
        ));
        $select->order('id DESC');
        if(isset($options['limit']) && $options['limit'] > 0){
        	$select->limit($options['limit']);
        }

        $query = $this->getDbSql()->getSqlStringForSqlObject($select);
        $results = $dbAdapter->query($query, $dbAdapter::QUERY_MODE_EXECUTE);

        if ($results->count()) {
            $position = (array)$results->current();

            $select = $this->getDbSql()->select(array('sb' => $this->getTableName()));
            $select->where(array(
                'positionId = ?' => $position['id'],
                'status = 1'
            ));
            $select->order(array('order', 'id DESC'));

            $query = $this->getDbSql()->getSqlStringForSqlObject($select);
            $results = $dbAdapter->query($query, $dbAdapter::QUERY_MODE_EXECUTE);

            if ($results->count()) {
                $banners = array();
                foreach ($results as $row) {
                    $bn = new Banner();
                    $bn->exchangeArray((array)$row);
                    $banners[] = $bn;
                }
                return $banners;
            }
        }
        return null;
    }

}