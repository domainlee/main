<?php
namespace Admin\Model;
use \Base\Model\Base;

class Position extends Base{
	protected $id;
	protected $name;
	protected $intro;
	protected $status;
	protected $viewLink;
	protected $storeId;
	
	const STATUS_ACTIVE = 1;
	const STATUS_INACTIVE = 0;
	const SELECT_MODE_ALL 	= 1;
	const SELECT_MODE_LEAF 	= 2;
	const SELECT_MODE_JSON 	= 3;
	const SELECT_MODE_NORMAL = 4;
	
	protected $statuses = array(
			\Admin\Model\Position::STATUS_ACTIVE => 'Hoạt động',
			\Admin\Model\Position::STATUS_INACTIVE => 'Không hoạt động',
	);
	
	/**
	 * @return the $storeId
	 */
	public function getStoreId() {
		return $this->storeId;
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
	public function setViewLink($viewLink){
		return $this->viewLink = $viewLink;
	}
	public function getViewLink(){
		return \Base\Model\Uri::slugify($this);
	}
	//
	public function setId($id){
		return $this->id = $id;
	}
	public function getId(){
		return $this->id;
	}
	//
	public function setName($name){
		return $this->name =$name;
	}
	public function getName(){
		return $this->name;
	}

	//
	public function getDescription() {
		return $this->description;
	}
	public function setDescription($description) {
		$this->description = $description;
		return $this;
	}
	//
	public function setStatus($status){
		return $this->status = $status;
	}
	public function getStatus(){
		return $this->status;
	}
	//
	public function setIntro($intro){
		return $this->intro = $intro;
	}
	public function getIntro(){
		return $this->intro;
	}

// 	public function toSelectboxArray($items, $selectMode = \Admin\Model\Banner::SELECT_MODE_ALL) {
// 		if(is_array($items) && count($items)) {
// 			/* @var $item \Product\Model\AProductc */
// 			return $this->buildSelectBox(null, $this->buildTree($items), $selectMode);
// 		}
// 		return array();
// 	}
	
// 	/**
// 	 * @param array $result
// 	 */
// 	public function buildSelectBox($result, $items, $selectMode, $level = 0) {
// 		if(!$result) {
// 			$result = array();
// 		}
	
// 		if(count($items)) {
// 			foreach ($items as $item) {
// 				/* @var $item \Product\Model\AProductc */
// 				if($selectMode == \Admin\Model\Banner::SELECT_MODE_LEAF) {
// 					if(count($item->getChilds())) {
// 						$result[$item->getName()] = $this->buildSelectBox(null, $item->getChilds(), $selectMode);
// 					} else {
// 						$result[$item->getId()] = $item->getName();
// 					}
// 				} else if ($selectMode == \Admin\Model\Banner::SELECT_MODE_ALL) {
// 					$sign = str_repeat(" - ", $level*5);
// 					$result[$item->getId()] = $sign . $item->getName();
// 					// array passed by value. change buildSelectBox(&$result) to pass by reference
// 					$result += $this->buildSelectBox(null, $item->getChilds(), $selectMode, ++$level);
// 				} else if ($selectMode == \Admin\Model\Banner::SELECT_MODE_NORMAL) {
// 					$result[$item->getId()] = $item->getName();
// 					$result += $this->buildSelectBox(null, $item->getChilds(), $selectMode, ++$level);
// 				} else {
// 					$sign = str_repeat(" - ", $level*5);
// 					$result[] = array('id' => $item->getId(), 'label' => $sign . $item->getName());
// 					// array passed by value. change buildSelectBox(&$result) to pass by reference
// 					$result = array_merge($result, $this->buildSelectBox(null, $item->getChilds(), $selectMode, ++$level));
// 				}
// 				$level--;
// 			}
// 		}
// 		return $result;
// 	}
	
// 	/**
// 	 * build select box
// 	 * @param array $items
// 	 */
// 	public function buildTree($items) {
// 		$result = array();
// 		$tmp = array();
// 		$ids = array();
// 		foreach($items as $item) {
// 			/* @var $item \Admin\Model\Banner */
// 			$ids[] = $item->getId();
// 			$result[$item->getId()] = $item;
// 			$tmp[$item->getId()] = $item;
// 		}
	
// 		foreach($result as $item) {
// 			if($item->getParentId() && in_array($item->getParentId(), $ids)) {
// 				/* @var $d \Admin\Model\Banner */
// 				$d = $tmp[$item->getParentId()];
// 				$d->addChild($item);
// 				unset($result[$item->getId()]);
// 			}
// 		}
// 		return $result;
// 	}
	
// 	public function addChild($child) {
// 		if(!is_array($this->childs)) {
// 			$this->childs = array();
// 		}
// 		$this->childs[] = $child;
// 	}
}




















