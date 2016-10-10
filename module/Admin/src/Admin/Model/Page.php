<?php
namespace Admin\Model;
use Base\Model\Base;
 
class Page extends Base{

 	protected $id;
 	protected $storeId;
 	protected $parentId;
 	protected $name;
 	protected $description;
    protected $updateDate;
    protected $status;

    protected $childs;
    protected $childIds;

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

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
     * @param mixed $updateDate
     */
    public function setUpdateDate($updateDate)
    {
        $this->updateDate = $updateDate;
    }

    /**
     * @return mixed
     */
    public function getUpdateDate()
    {
        return $this->updateDate;
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
            'storeId' => $this->getStoreId(),
            'parentId' => $this->getParentId(),
            'name' => html_entity_decode($this->getName()),
            'description' => html_entity_decode($this->getDescription()),
            'updateDate' => $this->getUpdateDate(),
            'status' => $this->getStatus(),
        );

        return $data;
    }


}
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 