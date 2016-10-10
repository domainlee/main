<?php
namespace User\Model;

use Base\Model\Base;

class User extends Base
{
	const ROLE_SUPERADMIN 				= 1;
	const ROLE_ADMIN 					= 2;
	const ROLE_ACCOUNT_MANAGER 			= 10;
	const ROLE_RESTAURANT_MANAGER 		= 20;
	const ROLE_RESTAURANT_CASHIER 		= 21;
	const ROLE_RESTAURANT_WAITER 		= 22;
	const ROLE_MEMBER 					= 3;
	const ROLE_HOTEL_RECEPTIONIST 		= 50;
	const ROLE_HOTEL_RESERVATIONIST 	= 51;
	const ROLE_HOTEL_OPERATOR		 	= 52;
	const ROLE_HOTEL_CASHIER		 	= 53;
	const ROLE_HOTEL_GUARD		 		= 54;
	
	protected $createdDateTime;

	protected $roles = array(
		User::ROLE_SUPERADMIN => 'Super Admin',
		User::ROLE_ADMIN => 'Admin',
		User::ROLE_ACCOUNT_MANAGER => 'Giám đốc',
		User::ROLE_RESTAURANT_MANAGER => 'Quản lý nhà hàng',
		User::ROLE_RESTAURANT_CASHIER => 'Nhân viên thu ngân',
		User::ROLE_RESTAURANT_WAITER => 'Nhân viên chạy bàn',
		User::ROLE_MEMBER => 'Thành viên',
		
	);
	protected $hotelRoles = array(
			User::ROLE_SUPERADMIN => 'Super Admin',
			User::ROLE_ADMIN => 'Admin',
			User::ROLE_ACCOUNT_MANAGER => 'Giám đốc',
			User::ROLE_HOTEL_RECEPTIONIST => 'Nhân viên lễ tân',
			User::ROLE_HOTEL_RESERVATIONIST => 'Nhân viên buồng phòng',
			User::ROLE_HOTEL_OPERATOR => 'Nhân viên trực điện thoại',
			User::ROLE_HOTEL_CASHIER => 'Nhân viên thu ngân',
			User::ROLE_HOTEL_GUARD => 'Nhân viên bảo vệ',
			);

	protected $systemRoles = array(
		User::ROLE_SUPERADMIN => 'Super Admin',
		User::ROLE_ADMIN => 'Admin',
		User::ROLE_ACCOUNT_MANAGER => 'Account Manager',
		User::ROLE_RESTAURANT_MANAGER => 'Restaurant Manager',
		User::ROLE_RESTAURANT_CASHIER => 'Restaurant Cashier',
		User::ROLE_RESTAURANT_WAITER => 'Restaurant Waiter',
		User::ROLE_MEMBER => 'Member'
	);

	protected $restaurantRoles = array(
		User::ROLE_RESTAURANT_MANAGER,
		User::ROLE_RESTAURANT_CASHIER,
		User::ROLE_RESTAURANT_WAITER,
	);

	const GENDER_MALE = 1;
	const GENDER_FEMALE = 2;
	const GENDER_ODER = 3;

	protected $genders = array(
		User::GENDER_MALE => 'Nam',
		User::GENDER_FEMALE => 'Nữ',
		User::GENDER_ODER => 'Khác'
	);

	const STATUS_ACTIVE = 1;
	const STATUS_INACTIVE = 0;

	protected $activeStatuses = array(
			User::STATUS_ACTIVE => 'Hoạt động',
			User::STATUS_INACTIVE => 'Không hoạt động',
	);

	const STATUS_LOCKED = 1;
	const STATUS_UNLOCKED = 0;

	protected $lockStatuses = array(
		\User\Model\User::STATUS_LOCKED => 'Đã khóa',
		\User\Model\User::STATUS_UNLOCKED => 'Không khóa'
	);

    /**
     * @param mixed $createdDateTime
     */
    public function setCreatedDateTime($createdDateTime)
    {
        $this->createdDateTime = $createdDateTime;
    }

    /**
     * @return mixed
     */
    public function getCreatedDateTime()
    {
        return $this->createdDateTime;
    }

    protected $storeId;

    /**
     * @param mixed $storeId
     */
    public function setStoreId($storeId)
    {
        $this->storeId = $storeId;
    }

    /**
     * @return mixed
     */
    public function getStoreId()
    {
        return $this->storeId;
    }

	/**
	 * @var int
	 */
    protected $id;

	/**
	 * @var string
	 */
	protected $role;

    /**
     * @var string
     */
	protected $username;

	/**
	 * @var string
	 */
	protected $salt;

	/**
	 * @var string
	 */
	protected $password;

	/**
	 * @var string
	 */
	protected $fullName;

	/**
	 * @var string
	 */
	protected $email;

	/**
	 * @var string
	 */
	protected $mobile;

	/**
	 * @var string
	 */
	protected $gender;

	/**
	 * @var string
	 */
	protected $birthday;

	/**
	 * @var int
	 */
	protected $cityId;

	/**
	 * @var \Address\Model\City
	 */
	protected $city;

	/**
	 * @var \Address\Model\District
	 */
	protected $district;

	/**
	 * @var int
	 */
	protected $districtId;

	/**
	 * @var string
	 */
	protected $address;

	/**
	 * @var int
	 */
	protected $careerId;

	/**
	 * @var string
	 */
	protected $lastAccess;

	/**
	 * @var string
	 */
	protected  $activeLink;

	/**
	 * @var string
	 */
	protected $activeKey;

	/**
	 * @var string
	 */
	protected $resetKey;

	/**
	 * @var string
	 */
	protected $lock;

	/**
	 * @var string
	 */
	protected $active;

	/**
	 * @var string
	 */
	protected $rememberMe;

	/**
	 * @var string
	 */
	protected $registeredDate;

	/**
	 * @var boolean
	 */
	protected $loadedManageableAccounts;

	/**
	 * @var array|null
	 */
	protected $manageableAccounts;

	/**
	 * @var boolean
	 */
	protected $loadedManageableRestaurants;

	/**
	 * @var array|null
	 */
	protected $manageableRestaurants;

	/**
	 * @var array|null
	 */
	protected $manageableHotels;

	/**
	 * @var boolean
	 */
	protected $loadedManageableHotels;

	/**
	 *
	 * @var int
	 */
	protected $accountId;
	
	/**
	 * @param Ambigous <multitype:, NULL> $manageableHotels
	 */
	public function setManageableHotels($manageableHotels) {
		$this->manageableHotels = $manageableHotels;
	}

	/**
	 * @return the $accountId
	 */
	public function getAccountId() {
		return $this->accountId;
	}

	/**
	 * @param number $accountId
	 */
	public function setAccountId($accountId) {
		$this->accountId = $accountId;
	}

	/**
	 * @return the $activeStatuses
	 */
	public function getActiveStatuses() {
		return $this->activeStatuses;
	}

	/**
	 * @return the $lockStatuses
	 */
	public function getLockStatuses() {
		return $this->lockStatuses;
	}

	/**
	 * @return the $roles
	 */
	public function getRoles() {
		return $this->roles;
	}
	public function getHotelRoles() {
		return $this->hotelRoles;
	}

	/**
	 * @return the $systemRoles
	 */
	public function getSystemRoles() {
		return $this->systemRoles;
	}

	/**
	 * @return the $genders
	 */
	public function getGenders() {
		return $this->genders;
	}
	
	
	public function getRoleName($role = null) {
		$role = $role?: $this->getRole();
		if(isset($this->roles[$role])) {
			return $this->roles[$role];
		}
		return "Guest";
	}
	/**
	 * @return the $id
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param number $id
	 */
	public function setId($id) {
		$this->id = $id;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getRole() {
		return $this->role;
	}

	/**
	 * @param string $role
	 */
	public function setRole($role) {
		$this->role = $role;
		return $this;
	}
	public function getHotelRole() {
		return $this->role;
	}
	
	/**
	 * @param string $role
	 */
	public function setHotelRole($role) {
		$this->role = $role;
		return $this;
	}

	/**
	 * @return the $username
	 */
	public function getUsername() {
		return $this->username;
	}

	/**
	 * @param string $username
	 */
	public function setUsername($username) {
		$this->username = $username;
		return $this;
	}

	/**
	 * @return the $salt
	 */
	public function getSalt() {
		return $this->salt;
	}

	/**
	 * @param string $salt
	 */
	public function setSalt($salt) {
		$this->salt = $salt;
		return $this;
	}

	/**
	 * @return the $password
	 */
	public function getPassword() {
		return $this->password;
	}

	/**
	 * @param string $password
	 */
	public function setPassword($password) {
		$this->password = $password;

	}

	/**
	 *
	 * @return string $activeLink
	 */
	public function getActiveLink() {
		return $this->activeLink;
	}

	public function setActiveLink($activeLink) {
		$this->activeLink = $activeLink;
	}


	/**
	 * @return the $fullName
	 */
	public function getFullName() {
		return $this->fullName;
	}

	/**
	 * @param string $fullName
	 */
	public function setFullName($fullName) {
		$this->fullName = $fullName;
		return $this;
	}

	/**
	 * @return the $email
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * @param string $email
	 */
	public function setEmail($email) {
		$this->email = $email;
		return $this;
	}

	/**
	 * @return the $mobile
	 */
	public function getMobile() {
		return $this->mobile;
	}

	/**
	 * @param string $mobile
	 */
	public function setMobile($mobile) {
		$this->mobile = $mobile;
		return $this;
	}

	/**
	 * @return the $gender
	 */
	public function getGender() {
		return $this->gender;
	}

	/**
	 * @param string $gender
	 */
	public function setGender($gender) {
		$this->gender = $gender;
		return $this;
	}

	/**
	 * @return the $birthday
	 */
	public function getBirthday() {
		return $this->birthday;
	}

	/**
	 * @param string $birthday
	 */
	public function setBirthday($birthday) {
		$this->birthday = $birthday;
		return $this;
	}

	/**
	 * @return the $cityId
	 */
	public function getCityId() {
		return $this->cityId;
	}
	/**
	 *
	 * @param \Address\Model\City $city
	 */
	public function setCity($city) {
		$this->city = $city;
	}
	/**
	 *@return $city
	 */
	public function getCity() {
		return $this->city;
	}
	/**
	 *
	 * @param \Address\Model\District $dt
	 */
	public function  setDistrict($dt) {
		$this->district = $dt;
	}
	/**
	 * @return $district
	 */
	public function getDistrict() {
		return $this->district;
	}

	/**
	 * @param number $cityId
	 */
	public function setCityId($cityId) {
		$this->cityId = $cityId;
		return $this;
	}

	/**
	 * @return the $districtId
	 */
	public function getDistrictId() {
		return $this->districtId;
	}

	/**
	 * @param number $districtId
	 */
	public function setDistrictId($districtId) {
		$this->districtId = $districtId;
		return $this;
	}

	/**
	 * @return the $address
	 */
	public function getAddress() {
		return $this->address;
	}

	/**
	 * @param string $address
	 */
	public function setAddress($address) {
		$this->address = $address;
		return $this;
	}

	/**
	 * @return the $careerId
	 */
	public function getCareerId() {
		return $this->careerId;
	}

	/**
	 * @param number $careerId
	 */
	public function setCareerId($careerId) {
		$this->careerId = $careerId;
		return $this;
	}

	/**
	 * @return the $lastAccess
	 */
	public function getLastAccess() {
		return $this->lastAccess;
	}

	/**
	 * @return the $activeKey
	 */
	public function getActiveKey() {
		return $this->activeKey;
	}

	/**
	 * @return the $resetKey
	 */
	public function getResetKey() {
		return $this->resetKey;
	}

	/**
	 * @param string $lastAccess
	 */
	public function setLastAccess($lastAccess) {
		$this->lastAccess = $lastAccess;
		return $this;
	}

	/**
	 * @param string $activeKey
	 */
	public function setActiveKey($activeKey) {
		$this->activeKey = $activeKey;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getActiveUri() {
		return "/user/active/?username={$this->getUsername()}&activeKey={$this->getActiveKey()}";
	}

	/**
	 * @param string $resetKey
	 */
	public function setResetKey($resetKey) {
		$this->resetKey = $resetKey;
		return $this;
	}

	/**
	 * @return the $locked
	 */
	public function getLocked() {
		return $this->lock;
	}

    public function getLock() {
        return $this->lock;
    }

    public function setLock($lock) {
        $this->lock = $lock;
    }

	/**
	 * @param string $locked
	 */
	public function setLocked($lock) {
		$this->lock = $lock;
		return $this;
	}

	/**
	 * @return the $active
	 */
	public function getActive() {
		return $this->active;
	}

	/**
	 * @param string $active
	 */
	public function setActive($active) {
		$this->active = $active;
		return $this;
	}

	/**
	 * @return the $rememberMe
	 */
	public function getRememberMe() {
		return $this->rememberMe;
	}

	/**
	 * @param string $rememberMe
	 */
	public function setRememberMe($rememberMe) {
		$this->rememberMe = $rememberMe;
		return $this;
	}

	/**
	 * @return the $registeredDate
	 */
	public function getRegisteredDate() {
		return $this->registeredDate;
	}

	/**
	 * @param string $registeredDate
	 */
	public function setRegisteredDate($registeredDate) {
		$this->registeredDate = $registeredDate;
		return $this;
	}
	public function getGenderName($gender = null) {
		$gender = $gender?: $this->getGender();
		if(isset($this->genders[$gender])) {
			return $this->genders[$gender];
		}
		return "";
	}
	public function getSystemRoleName($role = null) {
		$role = $role ?: $this->getRole();
		if(isset($this->systemRoles[$role])) {
			return $this->systemRoles[$role];
		}
		return "Guest";
	}

	public function exchangeArray($data, $change = false) {
		parent::exchangeArray($data);
		if(isset($data['birthday']) && $change) {
			$date = new \Base\Model\RDate();
			$this->setBirthday($date->toCommonDate($data['birthday']));
		}
	}

	public function getArrayCopy()
	{
		$data = get_object_vars($this);
		if(isset($data['birthday'])) {
			$date = new \Base\Model\RDate();
			$data['birthday'] = $date->dateToString($data['birthday']);
		}
		return $data;
	}

	public function generateSalt() {
		$this->setSalt(substr(md5(rand(2000, 5000) . time() . rand(2000, 5000)), 0, 20));
	}

	public function generatePassword() {
		$this->setPassword(md5($this->getSalt() . $this->getPassword()));
	}

	/**
	 * @return \stdClass
	 */
	public function toStdClass()
	{
		$obj = new \stdClass();
		$obj->id = $this->getId();
		$obj->label = $this->getFullName() . " (". $this->getUsername().")";
		$obj->username = $this->getUsername();
		$obj->fullName = $this->getFullName();
		return $obj;
	}

	/**
	 * @return int
	 */
	public function getTotalManageableAccounts() {
		if(is_array($this->getManageableAccounts())) {
			return count($this->getManageableAccounts());
		}
		return 0;
	}

	/**
	 * get manageable accounts based on role and settings
	 * @return array
	 */
	public function getManageableAccounts()
	{
		/* if($this->loadedManageableAccounts) {
			return $this->manageableAccounts;
		} */

		switch ($this->getRole()) {
			case self::ROLE_SUPERADMIN:
			case self::ROLE_ADMIN:
				/* @var $accMapper \Account\Model\AccountMapper */
				$accMapper = $this->getServiceLocator()->get('Account\Model\AccountMapper');
				$this->manageableAccounts = $accMapper->fetchAll();
				break;
			case self::ROLE_ACCOUNT_MANAGER:
			case self::ROLE_RESTAURANT_MANAGER:
			case self::ROLE_RESTAURANT_CASHIER:
			case self::ROLE_RESTAURANT_WAITER:
				$accountUser = new \Account\Model\AccountUser();
				$accountUser->setUserId($this->getId());
				/* @var $accUserMapper \Account\Model\AccountUserMapper */
				$accUserMapper = $this->getServiceLocator()->get('Account\Model\AccountUserMapper');
				$accUserMapper->fetchAll($accountUser);
				$this->manageableAccounts = $accountUser->getAccounts();
				break;
			case self::ROLE_MEMBER:
				$this->manageableAccounts = array();
				break;
		}

		$this->loadedManageableAccounts = true;
		return $this->manageableAccounts;
	}

	/**
	 * get manageable accounts based on role and settings
	 * @return array
	 */
	public function getManageableRestaurants()
	{
		if($this->loadedManageableRestaurants) {
			return $this->manageableRestaurants;
		}

		switch ($this->getRole()) {
			case self::ROLE_SUPERADMIN:
			case self::ROLE_ADMIN:
				if(!$this->getAccountId()){
					/* @var $resMapper \Restaurant\Model\RestaurantMapper */
					$resMapper = $this->getServiceLocator()->get('Restaurant\Model\RestaurantMapper');
					$this->manageableRestaurants = $resMapper->fetchAll();
				}else{
					$acc = new \Account\Model\Account();
					$accRes = new \Account\Model\AccountRestaurant();
					if($this->getAccountId()){
						$accRes->setAccountId($this->getAccountId());
					}
					/* @var $accResMapper \Account\Model\AccountUserMapper */
					$accResMapper = $this->getServiceLocator()->get('Account\Model\AccountRestaurantMapper');
					$accResMapper->fetchAll($accRes);
					$this->manageableRestaurants = $accRes->getRestaurants();
				}
				break;
			case self::ROLE_ACCOUNT_MANAGER:
				$acc = new \Account\Model\Account();
				$accRes = new \Account\Model\AccountRestaurant();
				$accRes->setAccountIds($acc->toIds($this->getManageableAccounts()));
				if($this->getAccountId()){
					$accRes->setAccountId($this->getAccountId());
				}
				/* @var $accResMapper \Account\Model\AccountUserMapper */
				$accResMapper = $this->getServiceLocator()->get('Account\Model\AccountRestaurantMapper');
				$accResMapper->fetchAll($accRes);
				$this->manageableRestaurants = $accRes->getRestaurants();
				break;
			case self::ROLE_RESTAURANT_MANAGER:
			case self::ROLE_RESTAURANT_CASHIER:
			case self::ROLE_RESTAURANT_WAITER:
				$resUser = new \Restaurant\Model\RestaurantUser();
				$resUser->setUserId($this->getId());
				/* @var $resUserMapper \Restaurant\Model\RestaurantUserMapper */
				$resUserMapper = $this->getServiceLocator()->get('Restaurant\Model\RestaurantUserMapper');
				$resUserMapper->fetchAll($resUser);
				$this->manageableRestaurants = $resUser->getRestaurants();
				break;
			case self::ROLE_MEMBER:
				$this->manageableRestaurants = array();
				break;
		}

		$this->loadedManageableRestaurants = true;
		return $this->manageableRestaurants;
	}

	public function getManageableRoles(){
		switch ($this->getRole()){
			case User::ROLE_SUPERADMIN:
			case User::ROLE_ADMIN:
				return $this->getRoles();
			case User::ROLE_ACCOUNT_MANAGER:
				return array(
					User::ROLE_ACCOUNT_MANAGER => 'Quản lý gian hàng',
					User::ROLE_RESTAURANT_MANAGER => 'Quản lý nhà hàng',
					User::ROLE_RESTAURANT_CASHIER => 'Nhân viên thu ngân',
					User::ROLE_RESTAURANT_WAITER => 'Nhân viên chạy bàn',
					User::ROLE_MEMBER => 'Thành viên',
				);
			case User::ROLE_RESTAURANT_MANAGER:
				return array(
					User::ROLE_RESTAURANT_MANAGER => 'Quản lý nhà hàng',
					User::ROLE_RESTAURANT_CASHIER => 'Nhân viên thu ngân',
					User::ROLE_RESTAURANT_WAITER => 'Nhân viên chạy bàn',
					User::ROLE_MEMBER => 'Thành viên',
				);
			case User::ROLE_RESTAURANT_CASHIER:
			case User::ROLE_RESTAURANT_WAITER:
			case User::ROLE_MEMBER:
			default:
				return array();
		}
	}
	public function getManageableHotelRoles(){
	switch ($this->getHotelRole()){
			case User::ROLE_SUPERADMIN:
			case User::ROLE_ADMIN:
			return $this->getHotelRoles();
			case User::ROLE_HOTEL_RECEPTIONIST:
			case User::ROLE_HOTEL_RESERVATIONIST:
			case User::ROLE_HOTEL_OPERATOR:
			case User::ROLE_HOTEL_CASHIER:
			case User::ROLE_HOTEL_GUARD:
			case User::ROLE_MEMBER:
			default:
				return array();
		}
	}

	public function isAdmin(){
		switch ($this->getRole()){
//			case User::ROLE_SUPERADMIN:
			case User::ROLE_ADMIN:
				return true;
			default:
				return false;
		}
	}

    public function isSuperAdmin()
    {
        switch ($this->getRole()){
            case User::ROLE_SUPERADMIN:
                return true;
            default:
                return false;
        }
    }

	public function getRoleIdByName($roleName){
		foreach ($this->roles as $key => $value) {
			if($value == $roleName) return $key;
		}
		return \User\Model\User::ROLE_MEMBER;
	}

	public function isRestaurantRole($role = null){
		$role = $role?: $this->getRole();
		if(in_array($role, $this->restaurantRoles)){
			return true;
		}
		return false;
	}

	/**
	 * get manageable hotels based on role and settings
	 * @return array
	 */
public function getManageableHotels()
	{
		/* if($this->loadedManageableHotels) {
			return $this->manageableHotels;
		} */

		switch ($this->getHotelRole()) {
			case self::ROLE_SUPERADMIN:
			case self::ROLE_ADMIN:
				/* @var $accMapper \Hotel\Model\HotelMapper */
				$hotelMapper = $this->getServiceLocator()->get('Hotel\Model\HotelMapper');
				$this->manageableHotels = $hotelMapper->fetchAll();
				break;
			case User::ROLE_HOTEL_RECEPTIONIST:
			case User::ROLE_HOTEL_RESERVATIONIST:
			case User::ROLE_HOTEL_OPERATOR:
			case User::ROLE_HOTEL_CASHIER:
			case User::ROLE_HOTEL_GUARD:
				$hotelUser = new \Hotel\Model\HotelUser();
				$hotelUser->setUserId($this->getId());
				/* @var $accUserMapper \Account\Model\AccountUserMapper */
				$hotelUserMapper = $this->getServiceLocator()->get('Hotel\Model\HotelUserMapper');
				$hotelUserMapper->fetchAll($hotelUser);
				$this->manageableHotels = $hotelUser->getHotel();
				break;
				case self::ROLE_MEMBER:
				$this->manageableHotels = array();
				break;
		}

		$this->loadedManageableHotels = true;
		return $this->manageableHotels;
	}
}