<?php
/**
 * @category   	Shop99 library
 * @copyright  	http://shop99.vn
 * @license    	http://shop99.vn/license
 */

namespace Store\Model;

use Base\Mapper\Base;

class StoreEmailMapper extends Base {

	/**
	 * @var string
	 */
	protected $tableName = 'store_emails';

    CONST TABLE_NAME = 'store_emails';


	public function getByStoreId($storeId)
	{
		$sl = $this->getServiceLocator();
		/*@var $adapter \Zend\Db\Adapter\Adapter */
		$adapter = $sl->get('dbAdapter');
		/*@var $sql \Zend\Db\Sql\Sql */
		$sql = $sl->get('dbSql');

		$select = $sql->select($this->tableName);
		$select->where(array('storeId' => $storeId));
		$query = $sql->getSqlStringForSqlObject($select);
		$result = $adapter->query($query,$adapter::QUERY_MODE_EXECUTE);

		if($result->count()){
			$storeMail = new \Store\Model\StoreEmail();
			$storeMail->exchangeArray((array)$result->current());
			return $storeMail;
		}
		return null;
	}
}