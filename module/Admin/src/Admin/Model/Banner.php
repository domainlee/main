<?php
namespace Admin\Model;
use \Base\Model\Base;

class Banner extends Base{
    protected $id;
    protected $name;
    protected $description;
    protected $status;
    protected $storeId;
    protected $positionId;
    protected $createdById;
    protected $createdDateTime;
    protected $link;
    protected $video;

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    const POSITION_1 	= 1;
    const POSITION_2 	= 2;
    const POSITION_3 	= 3;
    const POSITION_4    = 4;
    const POSITION_5    = 5;

    protected $statuses = array(
        self::STATUS_ACTIVE => 'Hoạt động',
        self::STATUS_INACTIVE => 'Không hoạt động',
    );

    protected $position = array(
        self::POSITION_1 => 'Vị trí 1',
        self::POSITION_2 => 'Vị trí 2',
        self::POSITION_3 => 'Vị trí 3',
        self::POSITION_4 => 'Vị trí 4',
        self::POSITION_5 => 'Vị trí 5',
    );

    /**
     * @param mixed $link
     */
    public function setLink($link)
    {
        $this->link = $link;
    }

    /**
     * @return mixed
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param mixed $video
     */
    public function setVideo($video)
    {
        $this->video = $video;
    }

    /**
     * @return mixed
     */
    public function getVideo()
    {
        return $this->video;
    }

    /**
     * @param mixed $createDateTime
     */
    public function setCreatedDateTime($createDateTime)
    {
        $this->createdDateTime = $createDateTime;
    }

    /**
     * @return mixed
     */
    public function getCreatedDateTime()
    {
        return $this->createdDateTime;
    }

    /**
     * @param mixed $createdbyId
     */
    public function setCreatedById($createdbyId)
    {
        $this->createdById = $createdbyId;
    }

    /**
     * @return mixed
     */
    public function getCreatedById()
    {
        return $this->createdById;
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

    public function toFormValues($serviceLocator = null){
        $data = [
            'id' => $this->getId(),
            'positionId' => $this->getPositionId(),
            'storeId' => $this->getStoreId(),
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'status' => $this->getStatus(),
            'link' => $this->getLink(),
            'video' => $this->getVideo(),
            'createdById' => $this->getCreatedbyId(),
            'createdDateTime' => $this->getCreatedDateTime()
        ];

        return $data;
    }

}
