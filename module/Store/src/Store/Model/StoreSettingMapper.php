<?php
/**
 * @author      Chautm
 * @category    Shop99 library
 * @copyright   http://shop99.vn
 * @license     http://shop99.vn/license
 */

namespace Store\Model;

use Base\Mapper\Base;

class StoreSettingMapper extends Base
{

    /**
     * @var string
     */
    protected $tableName = 'store_settings';

    CONST TABLE_NAME = 'store_settings';

    /**
     * @param StoreSetting $storeSetting
     * @return null|StoreSetting
     */
    public function get(StoreSetting $storeSetting)
    {
        $adapter = $this->getDbAdapter();

        $select = $this->getDbSql()->select($this->getTableName());
        $select->where(['storeId' => $storeSetting->getStoreId(), 'type' => $storeSetting->getType()])->limit(1);

        $query = $this->getDbSql()->getSqlStringForSqlObject($select);
        $result = $adapter->query($query, $adapter::QUERY_MODE_EXECUTE);
        if ($result->count()) {
            $storeSetting->exchangeArray((array)$result->current());
            return $storeSetting;
        }
        return null;
    }

}