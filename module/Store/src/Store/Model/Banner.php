<?php
/**
 * @category       Shop99 library
 * @copyright      http://shop99.vn
 * @license        http://shop99.vn/license
 */

namespace Store\Model;

use Base\Model\Base;

class Banner extends Base
{

    /**
     * @var int
     */
    protected $positionCode;

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
    protected $positionId;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $intro;

    /**
     * @var int
     */
    protected $order;

    /**
     * @var string
     */
    protected $image;

    /**
     * @var string
     */
    protected $sticky;

    /**
     * @var string
     */
    protected $config;

    /**
     * @var string
     */
    protected $link;

    /**
     * @var int
     */
    protected $status;

    /**
     * @var \DateTime
     */
    protected $publishedDateTime;

    /**
     * @var \DateTime
     */
    protected $expiredDateTime;

    /**
     * @var int
     */
    protected $createdById;

    /**
     * @var \DateTime
     */
    protected $createdDateTime;

    /**
     * @param string $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    /**
     * @return string
     */
    public function getConfig()
    {
        return $this->config;
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
     * @param \DateTime $createdDateTime
     */
    public function setCreatedDateTime($createdDateTime)
    {
        $this->createdDateTime = $createdDateTime;
    }

    /**
     * @return \DateTime
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
     * @param \DateTime $expiredDateTime
     */
    public function setExpiredDateTime($expiredDateTime)
    {
        $this->expiredDateTime = $expiredDateTime;
    }

    /**
     * @return \DateTime
     */
    public function getExpiredDateTime()
    {
        return $this->expiredDateTime;
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
     * @param string $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $intro
     */
    public function setIntro($intro)
    {
        $this->intro = $intro;
    }

    /**
     * @return string
     */
    public function getIntro()
    {
        return $this->intro;
    }

    /**
     * @param string $link
     */
    public function setLink($link)
    {
        $this->link = $link;
    }

    /**
     * @return string
     */
    public function getLink()
    {
        return $this->link;
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
     * @param int $positionCode
     */
    public function setPositionCode($positionCode)
    {
        $this->positionCode = $positionCode;
    }

    /**
     * @return int
     */
    public function getPositionCode()
    {
        return $this->positionCode;
    }

    /**
     * @param int $positionId
     */
    public function setPositionId($positionId)
    {
        $this->positionId = $positionId;
    }

    /**
     * @return int
     */
    public function getPositionId()
    {
        return $this->positionId;
    }

    /**
     * @param \DateTime $publishedDateTime
     */
    public function setPublishedDateTime($publishedDateTime)
    {
        $this->publishedDateTime = $publishedDateTime;
    }

    /**
     * @return \DateTime
     */
    public function getPublishedDateTime()
    {
        return $this->publishedDateTime;
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
     * @param string $sticky
     */
    public function setSticky($sticky)
    {
        $this->sticky = $sticky;
    }

    /**
     * @return string
     */
    public function getSticky()
    {
        return $this->sticky;
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
     * @return string
     */
    public function getImageSrc()
    {
        return \Base\Model\Uri::getImgSrc($this);
    }

    /**
     * @return string
     */
    public function getImageSticky()
    {
        return \Base\Model\Uri::getImgSrc($this, $this->getSticky());
    }

}