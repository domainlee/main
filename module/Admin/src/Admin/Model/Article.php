<?php
namespace Admin\Model;
use Base\Model\Base;
 
class Article extends Base{
 	protected $id;
 	protected $name;
 	protected $title;
 	protected $viewLink;
 	protected $cateName;
 	protected $categoryId;
 	protected $image;
 	protected $excludedId;
 	protected $status;
 	protected $childs;
 	protected $content;
 	protected $description;
 	protected $publishedDate;
 	protected $expiredDate;
 	protected $createdDateTime;
 	protected $storeId;
    protected $createdById;
    protected $type;
    protected $extraContent;

    const POSITION_ONE = 1;
    const POSITION_TWO = 2;
    const POSITION_THREE = 3;
    const POSITION_FOUR = 4;

    protected $types = array(
        self::POSITION_ONE => 'Vị trí 1',
        self::POSITION_TWO => 'Vị trí 2',
        self::POSITION_THREE => 'Vị trí 3',
        self::POSITION_FOUR => 'Vị trí 4',
    );

    /**
     * @param mixed $extraContent
     */
    public function setExtraContent($extraContent)
    {
        $this->extraContent = $extraContent;
    }

    /**
     * @return mixed
     */
    public function getExtraContent()
    {
        return $this->extraContent;
    }

    /**
     * @param array $types
     */
    public function setTypes($types)
    {
        $this->types = $types;
    }

    /**
     * @return array
     */
    public function getTypes()
    {
        return $this->types;
    }


    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }


    /**
     * @param mixed $createdById
     */
    public function setCreatedById($createdById)
    {
        $this->createdById = $createdById;
    }

    /**
     * @return mixed
     */
    public function getCreatedById()
    {
        return $this->createdById;
    }

 	const STATUS_ACTIVE = 1;
 	const STATUS_INACTIVE = 0;
 	const SELECT_MODE_ALL 	= 1;
	const SELECT_MODE_LEAF 	= 2;
	const SELECT_MODE_JSON 	= 3;
    const SELECT_MODE_NORMAL = 4;
 	
 	protected $statuses = array(
 		\Admin\Model\Article::STATUS_ACTIVE => 'Hoạt động',
 		\Admin\Model\Article::STATUS_INACTIVE => 'Không hoạt động',
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
 	public function setCategoryId($categoryId){
 		return $this->categoryId = $categoryId;
 	}
 	public function getCategoryId(){
 		return $this->categoryId;
 	}
 	//
 	public function setCateName($cateName){
 		return $this->cateName = $cateName;
 	}
 	public function getCateName(){
 		return $this->cateName;
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
 	public function setContent($content){
 		return $this->content = $content;
 	}
 	public function getContent(){
 		return $this->content;
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
 	//
 	public function getTitle() {
 		return $this->title;
 	}
 	public function setTitle($title) {
 		$this->title = $title;
 		return $this;
 	}
 	//
 	public function getDescription() {
 		return $this->description;
 	}
 	public function setDescription($description) {
 		$this->description = $description;
 		return $this;
 	}
 	/**
 	 * @return the $publishedDate
 	 */
 	public function getPublishedDate(){
 		return $this->publishedDate;
 	}
 	/**
 	 * @param field_type $publishedDate
 	 */
 	public function setPublishedDate($publishedDate){
 		$this->publishedDate = $publishedDate;
 		return $this;
 	}
 	/**
 	 * @return the $expiredDate
 	 */
 	public function getExpiredDate(){
 		return $this->expiredDate;
 	}
 	/**
 	 * @param field_type $expiredDate
 	 */
 	public function setExpiredDate($expiredDate){
 		$this->expiredDate = $expiredDate;
 		return $this;
 	}
 	public function setStatus($status){
 		return $this->status = $status;
 	}
 	public function getStatus(){
 		return $this->status;
 	}
 	//
 	public function getCreatedDateTime(){
 		return $this->createdDateTime;
 	}
 	
 	/**
 	 * @param field_type $createdDateTime
 	 */
 	public function setCreatedDateTime($createdDateTime){
 		$this->createdDateTime = $createdDateTime;
 		return $this;
 	}
 	//
 	public function setCategoryIds($array){
 		if (!!($element = $this->get('categoryId'))){
 			$element->setValueOptions(array('' => '- Thể loại -') + $array);
 		}
 	}
// 	public function exchangeArray($data,$convertDate = false){
// 		parent::exchangeArray($data);
// 		if(isset($data['cateName'])){
// 			$model = new \Admin\Model\Article();
// 			$model->setName($data['cateName']);
// 			$model->setCateName($model);
// 		}
// 		if($convertDate){
//			if(isset($data['publishedDate'])) {
//				$date = new \Base\Model\RDate();
//				$this->setPublishedDate($date->toCommonDate($data['publishedDate']));
//			}
//			if(isset($data['expiredDate'])) {
//				$date = new \Base\Model\RDate();
//				$this->setExpiredDate($date->toCommonDate($data['expiredDate']));
//			}
//		}
//
// 	}
 	public function toSelectboxArray($items, $selectMode = \Admin\Model\Article::SELECT_MODE_ALL) {
 		if(is_array($items) && count($items)) {
 			/* @var $item \Product\Model\AProductc */
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
 				/* @var $item \Product\Model\Article */
 				if($selectMode == \Admin\Model\Article::SELECT_MODE_LEAF) {
 					if(count($item->getChilds())) {
 						$result[$item->getName()] = $this->buildSelectBox(null, $item->getChilds(), $selectMode);
 					} else {
 						$result[$item->getId()] = $item->getName();
 					}
 				} else if ($selectMode == \Admin\Model\Article::SELECT_MODE_ALL) {
 					$sign = str_repeat(" - ", $level*5);
 					$result[$item->getId()] = $sign . $item->getName();
 					// array passed by value. change buildSelectBox(&$result) to pass by reference
 					$result += $this->buildSelectBox(null, $item->getChilds(), $selectMode, ++$level);
 				} else if ($selectMode == \Admin\Model\Article::SELECT_MODE_NORMAL) {
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
 			/* @var $item \Article\Model\Aarticle */
 			$ids[] = $item->getId();
 			$result[$item->getId()] = $item;
 			$tmp[$item->getId()] = $item;
 		}
 	
 		foreach($result as $item) {
 			if($item->getParentId() && in_array($item->getParentId(), $ids)) {
 				/* @var $d \Article\Model\Aarticle */
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
 	public function getArrayCopy(){
 		$data = get_object_vars($this);
 		$date = new \Base\Model\RDate();
 		if(isset($data['publishedDate'])){
 			$data['publishedDate'] = $date->dateToString($data['publishedDate']);
 		}
 		if(isset($data['expiredDate'])){
 			$data['expiredDate'] = $date->dateToString($data['expiredDate']);
 		}
 		return $data;
 	}
 	public function toStd(){
 		$a = new \stdClass();
 		$a->id= $this->getId();
 		$a->name = $this->getName();
 		$a->title = $this->getTitle();
 		$a->content = $this->getContent();
		return $a;
 	}

    public function toFormValues()
    {
        $data =  array(
//            'id' => $this->getId(),
            'type' => $this->getType(),
            'title' => $this->getTitle(),
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'content' => $this->getContent(),
            'status' => $this->getStatus(),
            'categoryId' => $this->getCategoryId(),
            'storeId' => $this->getStoreId(),
            'image_upload' => $this->getImage(),
            'extraContent' => $this->getExtraContent(),
        );
//        if($this->getSubmissionDeadline()){
//            $data['submissionDeadline'] = DateBase::toDisplayDate($this->getSubmissionDeadline());
//        }
//        if($this->getDeadline()){
//            $data['deadline'] = DateBase::toDisplayDate($this->getDeadline());
//        }
        return $data;
    }

}
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 