<?php
/**
 * @category   	Shop99 library
 * @copyright  	http://shop99.vn
 * @license    	http://shop99.vn/license
 */
namespace Store\Model;

use Base\Mapper\Base;

class DepotMapper extends Base
{
	protected $tableName = 'stores';

    CONST TABLE_NAME = 'stores';

    /**
     * @param \Store\Model\DepotStore $depot
     * @return array
     */
    public function get(DepotStore $depot)
	{
		$sl = $this->getServiceLocator();

		/* @var $dbAdapter \Zend\Db\Adapter\Adapter */
		$dbAdapter = $sl->get('dbAdapter');

		/* @var $dbSql \Zend\Db\Sql\Sql */
		$dbSql = $sl->get('dbSql');
		$select = $dbSql->select(array("dps" => $this->getTableName()));
//		$select->join(array('dp'=>'depots'),'dp.id = dps.depotId',array('depotName'=>'name','depotAddress'=>'address'));
//		$select->join(array('c'=>'cities'),'c.id = dp.cityId',array('nativeName'));
		/* $select->join(array('dp'=>'depots'),'dp.id = dps.depotId',array('name','address'))->columns(array('name','address')); */
		if($depot->getStoreId()){
			$select->where(array("storeId = ?" => $depot->getStoreId()));
		}
		if($depot->getId()){
			$select->where(array("id = ?" => $depot->getId()));
		}
//		if($depot->getDepotId()){
//			$select->where(array("depotId = ?" => $depot->getDepotId()));
//		}
		/* if($depot->getDepotId()){
			$select->join(array('dp'=>'depots'),'dp.id = dps.depotId');
			$select->where(array("depotId = ?" => $depot->getDepotId()));
		} */
		if($depot->getCreatedById()){
			$select->where(array("createdById = ?" => $depot->getCreatedByid()));
		}
		if($depot->getCityId()){
			$select->where(array("cityId = ?" => $depot->getCityId()));
		}
		$selectString = $dbSql->getSqlStringForSqlObject($select);
		$results = $dbAdapter->query($selectString,$dbAdapter::QUERY_MODE_EXECUTE);
		$depots = array();

		if($results->count()){
			foreach($results as $row){
				$depotStore = new DepotStore();
				$depotStore->exchangeArray((array)$row);
				$depots[] = $depotStore;
			}
			return $depots;
		}
        return null;
	}
}