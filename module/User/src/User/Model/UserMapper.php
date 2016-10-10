<?php
/**
 * @category   	Shop99 library
 * @copyright  	http://shop99.vn
 * @license    	http://shop99.vn/license
 */

namespace User\Model;

use Base\Mapper\Base;
use Zend\Db\Adapter\Adapter;

class UserMapper extends Base {

	/**
	 * @var string
	 */
	protected $tableName = 'users';

	/**
	 * @param \User\Model\User $user
     * @return true|false
	 */
	public function save($user)
	{
		$data = array(
			'username' => htmlentities($user->getUsername()) ?: null,
			'email' => htmlentities($user->getEmail()) ?: null,
			'role' => $user->getRole() ?: null,
			'salt' => $user->getSalt() ?: null,
            'storeId' => $user->getStoreId() ? : null,
			'password' => $user->getPassword() ?: null,
			'fullName' => $user->getFullName() ?: null,
			'gender' => $user->getGender() ?: null,
			'birthday' => $user->getBirthday() ?: null,
			'mobile' => htmlentities($user->getMobile()) ?: null,
			'address' => $user->getAddress() ?: null,
			'active' => $user->getActive() ? : null,
			'lock' => $user->getLock() ? : null,
			'activeKey' => $user->getActiveKey() ? : null,
            'createdDateTime' => $user->getCreatedDateTime() ? : null,
		);

		/* @var $dbAdapter \Zend\Db\Adapter\Adapter */
		$dbAdapter = $this->getServiceLocator()->get('dbAdapter');
		/* @var $dbSql \Zend\Db\Sql\Sql */
		$dbSql = $this->getServiceLocator()->get('dbSql');
		$results = false;
		if (null === ($id = $user->getId())) {
			$insert = $dbSql->insert($this->getTableName());
			$insert->values($data);
			$query = $dbSql->getSqlStringForSqlObject($insert);
			$results = $dbAdapter->query($query, $dbAdapter::QUERY_MODE_EXECUTE);
			$user->setId($results->getGeneratedValue());
		} else {
			$update = $dbSql->update($this->getTableName());
			$update->set($data);
			$update->where(array("id" => (int)$user->getId()));
			$selectString = $dbSql->getSqlStringForSqlObject($update);
			$results = $dbAdapter->query($selectString, $dbAdapter::QUERY_MODE_EXECUTE);
// 			echo '<pre>';
// 			print_r($results);die();
// 			echo '</pre>';
		}
		return $results;
}


	public function updateUser(User $user)
	{
		$updateArray = array(
				'username' => $user->getUsername() ?: null,
				'email' => $user->getEmail() ?: null,
				'role' => $user->getRole() ?: null,
				'salt' => $user->getSalt() ?: null,
				'password' => $user->getPassword() ?: null,
				'fullName' => $user->getFullName() ?: null,
				'gender' => $user->getGender() ?: null,
				'birthday' => $user->getBirthday() ?: null,
				'mobile' => $user->getMobile() ?: null,
				'address' => $user->getAddress() ?: null,
				'active' => $user->getActive() ?: null,
				'lock' => $user->getLock() ?: 0
		);
		$updateArray = array_filter($updateArray,'strlen');
		$updateArray = array_filter($updateArray);
		$update = $this->getDbSql()->update($this->getTableName());
		if($user->getId()) {
			$update->where(array('id' => $user->getId()));
		}

		$update->set($updateArray);
		$query = $this->getDbSql()->getSqlStringForSqlObject($update);
		$result = $this->getDbAdapter()->query($query, Adapter::QUERY_MODE_EXECUTE);
		return $result;
	}

	/**
	 * @param int|null $id
	 * @param string|null $username
	 * @param string|null $email
	 * @return User
	 */
	public function get($id = null, $username = null , $email = null)
	{
		if(!$id && !$username &&!$email) {
			return null;
		}

		$select = $this->getDbSql()->select($this->getTableName());
		if($id) {
			$select->where(array('id' => $id));
		}
		if ($username) {
			$select->where(array('username' => $username));
		}
		if($email)
		 {
			$select->where(array('email' => $email));
		}
		$select->limit(1);
		$selectString = $this->getDbSql()->getSqlStringForSqlObject($select);
		$results = $this->getDbAdapter()->query($selectString, Adapter::QUERY_MODE_EXECUTE);
		if($results->count()) {
			/* @var $dtMapper \Address\Model\DistrictMapper */
// 			$dtMapper = $this->getServiceLocator()->get('Address\Model\DistrictMapper');
// 			/* @var $ctMapper \Address\Model\CityMapper */
// 			$ctMapper = $this->getServiceLocator()->get('Address\Model\CityMapper');
			$user = new User();
			$row = (array)$results->current();
			$user->exchangeArray($row);
// 			$user->setCity($ctMapper->get($row['cityId']));
// 			$user->setDistrict($dtMapper->get($row['districtId']));
			return $user;
		}
		return null;
	}

	/**
	 * @param \User\Model\User $user
     * @return true|false
	 */
	public function checkExistsUserActive($user)
	{
		if(!$user->getActiveKey() || !$user->getUsername()) {
			return false;
		}
//        echo 'd';die;
		$select = $this->getDbSql()->select($this->getTableName());
		$select->where([
			'username' => $user->getUsername(),
			'activeKey' => $user->getActiveKey()
		]);
//		$select->where('active IS NULL');
		$query = $this->getDbSql()->getSqlStringForSqlObject($select);
		$result = $this->getDbAdapter()->query($query, Adapter::QUERY_MODE_EXECUTE);
		if($result->count() == 1) {
			return true;
		}
		return false;
	}

	/**
	 *
	 * @param \User\Model\User $user
     * @return true|false
	 */
	public function activeUser($user)
	{
		if(!$this->checkExistsUserActive($user)) {
			return false;
		} else {
			$update = $this->getDbSql()->update($this->getTableName());
			$update->set(array('active' => 1));
			$update->where(array('username' => $user->getUsername(), 'activeKey' => $user->getActiveKey()));
			$query = $this->getDbSql()->getSqlStringForSqlObject($update);
			$this->getDbAdapter()->query($query, Adapter::QUERY_MODE_EXECUTE);
			return true;
		}
	}
	public function search($item,$paging){
		/* @var $dbAdapter \Zend\Db\Adapter\Adapter */
		$dbAdapter = $this->getServiceLocator()->get('dbAdapter');
		
		/* @var $dbSql \Zend\Db\Sql\Sql */
		$dbSql = $this->getServiceLocator()->get('dbSql');
		$select = $dbSql->select(array('u'=>$this->getTableName()));
		$rCount = $dbSql->select(array('u'=>$this->getTableName()));
		
		if($item->getId()){
			$select->where(array('u.id'=>$item->getId()));
			$rCount->where(array('u.id'=>$item->getId()));
		}
		if($item->getFullName()){
			$select->where("u.fullName LIKE '%{$item->getFullName()}%'");
			$rCount->where("u.fullName LIKE '%{$item->getFullName()}%'");
		}
		$currentPage = isset ( $paging [0] ) ? $paging [0] : 1;
		$limit = isset ( $paging [1] ) ? $paging [1] : 20;
		$offset = ($currentPage - 1) * $limit;
		$select->limit ( $limit );
		$select->offset ( $offset );
		$select->order ( 'u.id DESC' );
		
		$selectStr = $dbSql->getSqlStringForSqlObject($select);
		$rCount = $dbSql->getSqlStringForSqlObject($rCount);
		
		$results = $dbAdapter->query($selectStr,$dbAdapter::QUERY_MODE_EXECUTE);
		$count = $dbAdapter->query($rCount,$dbAdapter::QUERY_MODE_EXECUTE);
		
		$rs = array();
		if($results->count()){
			foreach ($results as $rows){
				$model = new \User\Model\User();
				$model->exchangeArray((array)$rows);
				$rs[] = $model;
			}
		}
		return new \Base\Dg\Paginator($count->count(),$rs, $paging, count($results));
	}
}





















