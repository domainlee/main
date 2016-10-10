<?php 
namespace Admin\Model;

use Base\Model\Base;

class Articlec extends Base{
	
	protected $id;
	protected $name;
	protected $description;
	protected $image;
	protected $excludedId;
	protected $parentId;
	protected $status;
	protected $childs;
	protected $parent;
	protected $parentName;
	protected $storeId;

	
	const STATUS_ACTIVE = 1;
 	const STATUS_INACTIVE = 0;
 	const SELECT_MODE_ALL 	= 1;
 	const SELECT_MODE_LEAF 	= 2;
 	const SELECT_MODE_JSON 	= 3;
 	const SELECT_MODE_NORMAL = 4;
 	
	protected $statuses = array(
		\Admin\Model\Articlec::STATUS_ACTIVE => 'Hoạt động',
		\Admin\Model\Articlec::STATUS_INACTIVE => 'Không hoạt động'
		
	);
	/**
	 * @return the $parentName
	 */
	public function getParentName() {
		return $this->parentName;
	}

	/**
	 * @return the $storeId
	 */
	public function getStoreId() {
		return $this->storeId;
	}

	/**
	 * @param field_type $parentName
	 */
	public function setParentName($parentName) {
		$this->parentName = $parentName;
	}

	/**
	 * @param field_type $storeId
	 */
	public function setStoreId($storeId) {
		$this->storeId = $storeId;
	}

	public function getStatuses() {
 		return $this->statuses;
 	}
 	//
 	public function setParentId($parentId){
 		return $this->parentId = $parentId;
 	}
 	public function getParentId(){
 		return $this->parentId;
 	}
	//
	public function getParent() {
		return $this->parent;
	}
	
	/**
	 * @param field_type $parent
	 */
	public function setParent($parent) {
		$this->parent = $parent;
		return $this;
	}
	//
	
 	public function setId($id){
 		return $this->id = $id;
 	}
 	public function getId(){
 		return $this->id;
 	}
 	//
 	public function getExcludedId() {
 		return $this->excludedId;
 	}
 	
 	/**
 	 * @param field_type $excludedId
 	 */
 	public function setExcludedId($excludedId) {
 		$this->excludedId = $excludedId;
 	}
 	//
 	public function setName($name){
 		return $this->name =$name;
 	}
 	public function getName(){
 		return $this->name;
 	}
 	//
 	public function setDescription($description){
 		return $this->description = $description;
 	}
 	public function getDescription(){
 		return $this->description;
 	}
 	//
	public function getImage() {
		return $this->image;
	}
	public function setImage($image) {
		$this->image = $image;
		return $this;
	}
	//
	public function getChilds() {
		return $this->childs;
	}
	
	/**
	 * @param field_type $childs
	 */
	public function setChilds($childs) {
		$this->childs = $childs;
	}
	//
	public function getStatus() {
		return $this->status;
	}
	public function setStatus($status) {
		$this->status = $status;
		return $this;
	}
	
	
	
	
	public function setParentIds($array){
		if(!!($element = $this->get('parentId'))){
			$element->setValueOptions(array('' => '- Thể loại cha -') + $array);
		}
	}

    public function exchangeArray($data){
		parent::exchangeArray($data);
		if(isset($data['parentName'])){
			$parent = new \Admin\Model\Articlec();
			$parent->setName($data['parentName']);
			$this->setParent($parent);
		}
	}

	public function toSelectboxArray($items, $selectMode = \Admin\Model\Articlec::SELECT_MODE_ALL) {
		if(is_array($items) && count($items)) {
			/* @var $item \Admin\Model\Productc */
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
				/* @var $item \Admin\Model\Articlec */
				if($selectMode == \Admin\Model\Articlec::SELECT_MODE_LEAF) {
					if(count($item->getChilds())) {
						$result[$item->getName()] = $this->buildSelectBox(null, $item->getChilds(), $selectMode);
					} else {
						$result[$item->getId()] = $item->getName();
					}
				} else if ($selectMode == \Admin\Model\Articlec::SELECT_MODE_ALL) {
					$sign = str_repeat(" - ", $level*5);
					$result[$item->getId()] = $sign . $item->getName();
					// array passed by value. change buildSelectBox(&$result) to pass by reference
					$result += $this->buildSelectBox(null, $item->getChilds(), $selectMode, ++$level);
				} else if ($selectMode == \Admin\Model\Articlec::SELECT_MODE_NORMAL) {
					$result[$item->getId()] = $item->getName();
					$result += $this->buildSelectBox(null, $item->getChilds(), $selectMode, ++$level);
				} else {
					$sign = str_repeat(" - ", $level*5);
					$result[] = array('id' => $item->getId(), 'label' => $sign . $item->getName());
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
			/* @var $item \Admin\Model\Articlec */
			$ids[] = $item->getId();
			$result[$item->getId()] = $item;
			$tmp[$item->getId()] = $item;
		}
	
		foreach($result as $item) {
			if($item->getParentId() && in_array($item->getParentId(), $ids)) {
				/* @var $d \Admin\Model\Articlec */
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














