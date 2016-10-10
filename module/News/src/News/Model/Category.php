<?php
/**
 * @category    Shop99 library
 * @copyright   http://shop99.vn
 * @license     http://shop99.vn/license
 */

namespace News\Model;

use Base\Model\Base;

class Category extends Base
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 2;

    /**
     * @var int
     */
    protected $id;

    /**
     * @var int
     */
    protected $storeId;

    /**
     * @var int
     */
    protected $parentId;

    /**
     * @var Category
     */
    protected $parent;

    /**
     * @var int
     */
    protected $domainId;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $picture;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var int
     */
    protected $status;

    /**
     * @var int
     */
    protected $order;

    /**
     * @var int
     */
    protected $createdById;

    /**
     * @var \Datetime
     */
    protected $createdDateTime;

    /**
     * @var array
     */
    protected $childs;

    /**
     * @var array
     */
    protected $childIds;

    /**
     * @param array $childs
     */
    public function setChilds($childs)
    {
        $this->childs = $childs;
    }

    /**
     * @return array
     */
    public function getChilds()
    {
        return $this->childs;
    }

    /**
     * @param int $createdById
     */
    public function setCreatedById($createdById)
    {
        $this->createdById = $createdById;
    }

    /**
     * @return int
     */
    public function getCreatedById()
    {
        return $this->createdById;
    }

    /**
     * @param \Datetime $createdDateTime
     */
    public function setCreatedDateTime($createdDateTime)
    {
        $this->createdDateTime = $createdDateTime;
    }

    /**
     * @return \Datetime
     */
    public function getCreatedDateTime()
    {
        return $this->createdDateTime;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param int $domainId
     */
    public function setDomainId($domainId)
    {
        $this->domainId = $domainId;
    }

    /**
     * @return int
     */
    public function getDomainId()
    {
        return $this->domainId;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        if($name = $this->getTranslateOptions($this, 'name')){
            return $name;
        }
        return $this->name;
    }

    /**
     * @param int $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param int $parentId
     */
    public function setParentId($parentId)
    {
        $this->parentId = $parentId;
    }

    /**
     * @return int
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * @param string $picture
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;
    }

    /**
     * @return string
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * @param int $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param int $storeId
     */
    public function setStoreId($storeId)
    {
        $this->storeId = $storeId;
    }

    /**
     * @return int
     */
    public function getStoreId()
    {
        return $this->storeId;
    }

    /**
     * @param \News\Model\Category $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return \News\Model\Category
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param array $childIds
     */
//     public function setChildIds($childIds)
//     {
//         $this->childIds = $childIds;
//     }

    /**
     * @return array
     */
//     public function getChildIds()
//     {
//         return $this->childIds;
//     }
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

    /**
     * @param int $childId
     * @return array
     */
    public function addChildIds($childId)
    {
        return $this->childIds[] = $childId;
    }

    /**
     * @param Category $child
     */
    public function addChild(Category $child)
    {
        $this->childs[] = $child;
    }

    /**
     * @return string
     */
    public function getPictureUri()
    {
        return \Base\Model\Uri::getImgSrc($this);
    }

    /**
     * @return string
     */
    public function getViewLink()
    {
        return \Base\Model\Uri::slugify($this);
    }

    /**
     * @param null $flatten
     * @return array|null
     */
    public function flattenParents(&$flatten = null)
    {
        if (!$flatten) {
            $flatten = array();
        }
        $flatten[] = $this;
        if ($this->getParent()) {
            $this->getParent()->flattenParents($flatten);
        }
        return $flatten;
    }

}