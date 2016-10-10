<?php
namespace Home\Model;
use Base\Model\Base;

class Menu extends Base
{

    protected $id;
    protected $positionId;
    protected $type;
    protected $storeId;
    protected $parentId;
    protected $itemId;
    protected $name;
    protected $description;
    protected $url;
    protected $updateDateTime;
    protected $status;

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $itemId
     */
    public function setItemId($itemId)
    {
        $this->itemId = $itemId;
    }

    /**
     * @return mixed
     */
    public function getItemId()
    {
        return $this->itemId;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $parentId
     */
    public function setParentId($parentId)
    {
        $this->parentId = $parentId;
    }

    /**
     * @return mixed
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * @param mixed $positionId
     */
    public function setPositionId($positionId)
    {
        $this->positionId = $positionId;
    }

    /**
     * @return mixed
     */
    public function getPositionId()
    {
        return $this->positionId;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

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
     * @param mixed $updateDateTime
     */
    public function setUpdateDateTime($updateDateTime)
    {
        $this->updateDateTime = $updateDateTime;
    }

    /**
     * @return mixed
     */
    public function getUpdateDateTime()
    {
        return $this->updateDateTime;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }


    protected $childs;
    protected $childIds;


    public function getChilds() {
        return $this->childs;
    }

    /**
     * @param field_type $childs
     */
    public function setChilds($childs) {
        $this->childs = $childs;
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

}