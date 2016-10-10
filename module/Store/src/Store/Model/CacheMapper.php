<?php
/**
 * @author VanCK
 * @category   	Shop99 library
 * @copyright  	http://shop99.vn
 * @license    	http://shop99.vn/license
 */

namespace Store\Model;

use Base\Mapper\Base;

class CacheMapper extends Base
{
	/**
	 * @var string
	 */
	protected $tableName = 'store_caches';

    CONST TABLE_NAME = 'store_caches';

	/**
	 * @author VanCK
	 * @var array $options
	 * @return \Store\Model\Cache|null
	 */
	public function get($options)
	{
		$dbAdapter = $this->getDbAdapter();
		$select = $this->getDbSql()->select($this->getTableName());
		$select->where([
			'storeId' => $options['storeId'],
			'name' => $options['name']
		]);
		$select->limit(1);

		$query = $this->getDbSql()->getSqlStringForSqlObject($select);
		$results = $dbAdapter->query($query, $dbAdapter::QUERY_MODE_EXECUTE);

		if($results->count()) {
			$cache = new Cache();
			$cache->exchangeArray((array)$results->current());
			return $cache;
		}
		return null;
	}
}