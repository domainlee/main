<?php
/**
 * @category   	Shop99 library
 * @copyright  	http://shop99.vn
 * @license    	http://shop99.vn/license
 */

namespace Store\Model;

use Base\Mapper\Base;

class StoreMapper extends Base {

	/**
	 * @var string
	 */
	protected $tableName = 'stores';

    CONST TABLE_NAME = 'stores';

    /**
     * @param int $storeId
     * @return null|Store
     */
    public function get($storeId)
	{
		$adapter = $this->getDbAdapter();

		$select = $this->getDbSql()->select($this->getTableName())->where(array('id' => $storeId))->limit(1);
		$query = $this->getDbSql()->getSqlStringForSqlObject($select);
		$result = $adapter->query($query,$adapter::QUERY_MODE_EXECUTE);
		if($result->count()) {
			$store = new Store();
			$store->exchangeArray((array)$result->current());
			return $store;
		}
		return null;
	}
	

}