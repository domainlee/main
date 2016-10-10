<?php
namespace Admin\Model;

use Base\Model\Base;
class Store extends Base{
	
	protected $id;
	protected $parentId;
	protected $name;
	protected $logo;
	protected $username;
	protected $password;
	protected $email;
	protected $address;
	protected $mobile;
	protected $status;
	protected $childs;
	
	const STATUS_ACTIVE = 1;
	const STATUS_INACTIVE = 0;
	const SELECT_MODE_ALL 	= 1;
	const SELECT_MODE_LEAF 	= 2;
	const SELECT_MODE_JSON 	= 3;
	const SELECT_MODE_NORMAL = 4;
	
	protected $statuses = array(
			\Admin\Model\Store::STATUS_ACTIVE => 'Hoạt động',
			\Admin\Model\Store::STATUS_INACTIVE => 'Không hoạt động',
	);
	
	/**
	 * @return the $childs
	 */
	public function getChilds() {
		return $this->childs;
	}

	/**
	 * @param Ambigous <multitype:, unknown> $childs
	 */
	public function setChilds($childs) {
		$this->childs = $childs;
	}

	public function getStatuses() {
		return $this->statuses;
	}
	/**
	 * @return the $status
	 */
	public function getStatus() {
		return $this->status;
	}

	/**
	 * @param field_type $status
	 */
	public function setStatus($status) {
		$this->status = $status;
	}

	//
	public function setViewLink($viewLink){
		return $this->viewLink = $viewLink;
	}
	public function getViewLink(){
		return \Base\Model\Uri::slugify($this);
	}
	/**
	 * @return the $id
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param field_type $id
	 */
	public function setId($id) {
		$this->id = $id;
	}

	/**
	 * @return the $parentId
	 */
	public function getParentId() {
		return $this->parentId;
	}

	/**
	 * @param field_type $parentId
	 */
	public function setParentId($parentId) {
		$this->parentId = $parentId;
	}

	/**
	 * @return the $name
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param field_type $name
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * @return the $logo
	 */
	public function getLogo() {
		return $this->logo;
	}

	/**
	 * @param field_type $logo
	 */
	public function setLogo($logo) {
		$this->logo = $logo;
	}

	/**
	 * @return the $username
	 */
	public function getUsername() {
		return $this->username;
	}

	/**
	 * @param field_type $username
	 */
	public function setUsername($username) {
		$this->username = $username;
	}

	/**
	 * @return the $password
	 */
	public function getPassword() {
		return $this->password;
	}

	/**
	 * @param field_type $password
	 */
	public function setPassword($password) {
		$this->password = $password;
	}

	/**
	 * @return the $email
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * @param field_type $email
	 */
	public function setEmail($email) {
		$this->email = $email;
	}

	/**
	 * @return the $address
	 */
	public function getAddress() {
		return $this->address;
	}

	/**
	 * @param field_type $address
	 */
	public function setAddress($address) {
		$this->address = $address;
	}

	/**
	 * @return the $mobile
	 */
	public function getMobile() {
		return $this->mobile;
	}

	/**
	 * @param field_type $mobile
	 */
	public function setMobile($mobile) {
		$this->mobile = $mobile;
	}

	public function toSelectboxArray($items, $selectMode = \Admin\Model\Store::SELECT_MODE_ALL) {
		if(is_array($items) && count($items)) {
			/* @var $item \Admin\Model\Store */
            return $this->buildSelectBox(null, $this->buildTree($items), $selectMode);
		}
		return array();
	}
	
	/**
	 * @param array $result
	 */
	public function buildSelectBox($result, $items, $selectMode, $level = 0) {
		if(!$result) {
			$result = array();
		}
	
		if(count($items)) {
			foreach ($items as $item) {
				/* @var $item \Admin\Model\Store */
				if($selectMode == \Admin\Model\Store::SELECT_MODE_LEAF) {
					if(count($item->getChilds())) {
						$result[$item->getName()] = $this->buildSelectBox(null, $item->getChilds(), $selectMode);
					} else {
						$result[$item->getId()] = html_entity_decode($item->getName());
					}

                } else if ($selectMode == \Admin\Model\Store::SELECT_MODE_ALL) {
					$sign = str_repeat(" - ", $level*5);
					$result[$item->getId()] = $sign . html_entity_decode($item->getName());
					// array passed by value. change buildSelectBox(&$result) to pass by reference
					$result += $this->buildSelectBox(null, $item->getChilds(), $selectMode, ++$level);
                } else if ($selectMode == \Admin\Model\Store::SELECT_MODE_NORMAL) {
					$result[$item->getId()] = html_entity_decode($item->getName());
					$result += $this->buildSelectBox(null, $item->getChilds(), $selectMode, ++$level);
				} else {
					$sign = str_repeat(" - ", $level*5);
					$result[] = array('id' => $item->getId(), 'label' => $sign . html_entity_decode($item->getName()));
					// array passed by value. change buildSelectBox(&$result) to pass by reference
					$result = array_merge($result, $this->buildSelectBox(null, $item->getChilds(), $selectMode, ++$level));
					
				}
				$level--;
			}
		}
		return $result;
	}
	
	/**
	 * build select box
	 * @param array $items
	 */
	public function buildTree($items) {
		$result = array();
		$tmp = array();
		$ids = array();
		foreach($items as $item) {
			/* @var $item \Admin\Model\Store */
			$ids[] = $item->getId();
			$result[$item->getId()] = $item;
			$tmp[$item->getId()] = $item;
		}
	
		foreach($result as $item) {
			if($item->getParentId() && in_array($item->getParentId(), $ids)) {
				/* @var $d \Admin\Model\Store */
				$d = $tmp[$item->getParentId()];
				$d->addChild($item);
				unset($result[$item->getId()]);
			}
		}
		return $result;
	}
	
	public function addChild($child) {
		if(!is_array($this->childs)) {
			$this->childs = array();
		}
		$this->childs[] = $child;
	}
	
	
}