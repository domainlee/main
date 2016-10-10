<?php
namespace Product\Model;

use Base\Model\Base;

class Category extends Base{
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
	protected $viewLink;
    protected $childIds;

    const STATUS_ACTIVE = 1;
	const STATUS_INACTIVE = 0;
	
	protected $statuses = array(
 		\Admin\Model\Productc::STATUS_ACTIVE => 'Hoạt động',
 		\Admin\Model\Productc::STATUS_INACTIVE => 'Không hoạt động',
 	);
	/**
	 * @return the $viewLink
	 */
	public function getViewLink() {
		return \Base\Model\Uri::slugify($this);
	}

	/**
	 * @param field_type $viewLink
	 */
	public function setViewLink($viewLink) {
		$this->viewLink = $viewLink;
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
	 * @return the $description
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @param field_type $description
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	 * @return the $image
	 */
	public function getImage() {
		return $this->image;
	}

	/**
	 * @param field_type $image
	 */
	public function setImage($image) {
		$this->image = $image;
	}

	/**
	 * @return the $excludedId
	 */
	public function getExcludedId() {
		return $this->excludedId;
	}

	/**
	 * @param field_type $excludedId
	 */
	public function setExcludedId($excludedId) {
		$this->excludedId = $excludedId;
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

	/**
	 * @return the $childs
	 */
	public function getChilds() {
		return $this->childs;
	}

	/**
	 * @param field_type $childs
	 */
	public function setChilds($childs) {
		$this->childs = $childs;
	}

	/**
	 * @return the $parent
	 */
	public function getParent() {
		return $this->parent;
	}

	/**
	 * @param field_type $parent
	 */
	public function setParent($parent) {
		$this->parent = $parent;
	}

	/**
	 * @return the $parentName
	 */
	public function getParentName() {
		return $this->parentName;
	}

	/**
	 * @param field_type $parentName
	 */
	public function setParentName($parentName) {
		$this->parentName = $parentName;
	}

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

	public function addChild($child){
		$this->childs[] = $child;
	}

    public function getChildIds($categories = null, &$cIds = null)
    {
        if (!$categories) {
            return $this->childIds;
        }
        if ($cIds == null) {
            $cIds = [];
        }
        if (count($categories)) {
            foreach ($categories as $c) {
                $cIds[] = $c->getId();
                if ($c->getChilds()) {
                    $this->getChildIds($c->getChilds(), $cIds);
                }
            }
        }
        return $cIds;
    }

    public function flattenParents(&$flatten = null)
    {
        if (!$flatten) {
            $flatten = [];
        }
        $flatten[] = $this;
        if ($this->getParent()) {
            $this->getParent()->flattenParents($flatten);
        }
        return $flatten;
    }


	
	
	
}