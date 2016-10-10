<?php
/**
 * @category   	Shop99 library
 * @copyright  	http://shop99.vn
 * @license    	http://shop99.vn/license
 */

namespace Uitemplate\Model;

use Base\Mapper\Base;

class UitemplateMapper extends Base {

	/**
	 * @var string
	 */
	protected $tableName = 'uitemplates';

    CONST TABLE_NAME = 'uitemplates';

	/**
	 * @param int $user
	 * @return User
	 */
	public function get($id) {
		$sl = $this->getServiceLocator();

		/* @var $dbAdapter \Zend\Db\Adapter\Adapter */
		$dbAdapter = $sl->get('dbAdapter');

		/* @var $dbSql \Zend\Db\Sql\Sql */
		$dbSql = $sl->get('dbSql');
		$select = $dbSql->select($this->getTableName());
		$select->where(array('id' => $id));
		$select->limit(1);

		$selectString = $dbSql->getSqlStringForSqlObject($select);
		$results = $dbAdapter->query($selectString, $dbAdapter::QUERY_MODE_EXECUTE);
		if($results) {
			$uitemplate = new Uitemplate();
			return $uitemplate->exchangeArray((array)$results->current());
		}
		return null;
	}
}