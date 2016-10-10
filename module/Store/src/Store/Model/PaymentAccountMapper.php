<?php
/**
 * @category   	Shop99 library
 * @copyright  	http://shop99.vn
 * @license    	http://shop99.vn/license
 */

namespace Store\Model;

use Base\Mapper\Base;

class PaymentAccountMapper extends Base {

	/**
	 * @var string
	 */
	protected $tableName = 'store_payment_accounts';

    CONST TABLE_NAME = 'store_payment_accounts';

	/**
	 * @param \Store\Model\PaymentAccount $paymentAccount
	 * @return \Store\Model\PaymentAccount
	 */
	public function get($paymentAccount)
	{
		$dbAdapter = $this->getDbAdapter();

		$select = $this->getDbSql()->select($this->getTableName());
		$select->where(array('storeId = ?' => $paymentAccount->getStoreId()));
        if($paymentAccount->getPaymentGatewayId()){
            $select->where(array('paymentGatewayId' => $paymentAccount->getPaymentGatewayId()));
        }
		$select->order('id DESC');
		$select->limit(1);

		$selectString = $this->getDbSql()->getSqlStringForSqlObject($select);
		$results = $dbAdapter->query($selectString, $dbAdapter::QUERY_MODE_EXECUTE);

		if($results->count()) {
			$paymentAccount = $paymentAccount->exchangeArray((array)$results->current());
			return $paymentAccount;
		}
		return null;
	}
}