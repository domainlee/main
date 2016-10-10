<?php
/**
 * @category   	Shop99 library
 * @copyright  	http://shop99.vn
 * @license    	http://shop99.vn/license
 */

namespace Store\Model;

use Base\Mapper\Base;
use Zend\Db\Adapter\Adapter;

class DomainMapper extends Base
{
	/**
	 * @var string
	 */
	protected $tableName = 'store_domains';

    CONST TABLE_NAME = 'store_domains';

	/**
	 * @param \Store\Model\Domain $domain
	 * @return \Store\Model\Domain
	 */
	public function get($domain)
	{
		$select = $this->getDbSql()->select($this->getTableName());
		$select->where(array('name' => $domain->getName(), 'alias' => $domain->getName()), "OR");
		$select->limit(1);

		$query = $this->getDbSql()->getSqlStringForSqlObject($select);
		$results = $this->getDbAdapter()->query($query, Adapter::QUERY_MODE_EXECUTE);

		if($results->count()) {
			$domain = $domain->exchangeArray((array)$results->current());
			return $domain;
		}
		return null;
	}

	/**
	 * @return array
	 */
	public function fetchInRoller()
	{
		$select = $this->getDbSql()->select($this->getTableName());
		$select->where(array('showInRoller' => '1'));

		$query = $this->getDbSql()->getSqlStringForSqlObject($select);
		$rows = $this->getDbAdapter()->query($query, Adapter::QUERY_MODE_EXECUTE);

		$domains = array();
		if ($rows->count()) {
			foreach ($rows as $row) {
 				$domain = new Domain();
 				$domains[] = $domain->exchangeArray((array)$row);
			}
			return $domains;
		}
        return null;
	}
}