<?php
namespace Admin\Model;

use Base\Model\Base;

class Brand extends Base{
	
	protected $id;
    protected $storeId;
	protected $name;
    protected $description;
    protected $status;
    protected $updateDateTime;

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
     * @param array $statuses
     */
    public function setStatuses($statuses)
    {
        $this->statuses = $statuses;
    }

    /**
     * @return array
     */
    public function getStatuses()
    {
        return $this->statuses;
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


	const STATUS_ACTIVE = 1;
 	const STATUS_INACTIVE = 0;
 	
 	protected $statuses = array(
 		\Admin\Model\Productc::STATUS_ACTIVE => 'Hoạt động',
 		\Admin\Model\Productc::STATUS_INACTIVE => 'Không hoạt động',
 	);

    public function toFormValues($serviceLocator = null)
    {
        $data = [
            'id' => $this->getId(),
            'storeId' => $this->getStoreId(),
            'name' => html_entity_decode($this->getName()),
            'description' => html_entity_decode($this->getDescription()),
            'status' => $this->getStatus(),
            'updateDateTime' => $this->getUpdateDateTime(),
        ];

        return $data;
    }

    public function fetchAllBrand($a = null)
    {
        if(count($a)){
            $c = [];
            foreach($a as $b){
                $c[$b->getId()] = $b->getName();
            }
            return $c;
        }
        return [];
    }
}













