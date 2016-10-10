<?php
namespace Admin\Model;
use Base\Model\Base;
 
class Menu extends Base{

    protected $id;
    protected $type;
    protected $storeId;
    protected $parentId;
    protected $itemId;
    protected $name;
    protected $description;
    protected $url;
    protected $updateDateTime;
    protected $positionId;
    protected $childs;
    protected $childIds;
    protected $status;

    const POSITION_ONE = 1;
    const POSITION_TWO = 2;

    protected $position = array(
        self::POSITION_ONE => 'Menu chính',
        self::POSITION_TWO => 'Menu footer',
    );

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
     * @param array $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * @return array
     */
    public function getPosition()
    {
        return $this->position;
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

    public function toFormValues()
    {
        $data =  array(
            'positionId' => $this->getPositionId(),
            'type' => $this->getType(),
            'storeId' => $this->getStoreId(),
            'parentId' => $this->getParentId(),
            'itemId' => $this->getItemId(),
            'name' => html_entity_decode($this->getName()),
            'description' => html_entity_decode($this->getDescription()),
            'url' => $this->getUrl(),
            'updateDateTime' => $this->getUpdateDateTime(),
            'status' => $this->getStatus()
        );
        return $data;
    }

}
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 