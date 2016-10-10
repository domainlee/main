<?php
/**
 * @category    Shop99 library
 * @copyright   http://shop99.vn
 * @license     http://shop99.vn/license
 */

namespace Product\Model;

use Base\Model\Base;

class WishList extends Base
{
    const TYPE_WISHLIST = 1;
    const TYPE_CART = 2;
    const TYPE_WAITING = 3;

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 2;

    /**
     * @var int
     */
    protected $id;

    /**
     * @var int
     */
    protected $type;

    /**
     * @var int
     */
    protected $domainId;

    /**
     * @var int
     */
    protected $storeId;

    /**
     * @var int
     */
    protected $productStoreId;

    /**
     * @var int
     */
    protected $quantity;

    /**
     * @var string
     */
    protected $userCookie;

    /**
     * @var int
     */
    protected $userId;

    /**
     * @var string
     */
    protected $userEmail;

    /**
     * @var int
     */
    protected $status;

    /**
     * @var \DateTime
     */
    protected $createdDate;

    /**
     * @var \DateTime
     */
    protected $createdDateTime;

    /**
     * @var \DateTime
     */
    protected $updatedDateTime;

    protected $productSize;

    protected $productColor;

    protected $attrType;

    /**
     * @param mixed $attrType
     */
    public function setAttrType($attrType)
    {
        $this->attrType = $attrType;
    }

    /**
     * @return mixed
     */
    public function getAttrType()
    {
        return $this->attrType;
    }

    /**
     * @param mixed $productColor
     */
    public function setProductColor($productColor)
    {
        $this->productColor = $productColor;
    }

    /**
     * @return mixed
     */
    public function getProductColor()
    {
        return $this->productColor;
    }

    /**
     * @param mixed $productSize
     */
    public function setProductSize($productSize)
    {
        $this->productSize = $productSize;
    }

    /**
     * @return mixed
     */
    public function getProductSize()
    {
        return $this->productSize;
    }

    /**
     * @return int
     */
    public function getStoreId()
    {
        return $this->storeId;
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
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
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
     * @param \DateTime $createdDate
     */
    public function setCreatedDate($createdDate)
    {
        $this->createdDate = $createdDate;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
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
     * @param int $productStoreId
     */
    public function setProductStoreId($productStoreId)
    {
        $this->productStoreId = $productStoreId;
    }

    /**
     * @return int
     */
    public function getProductStoreId()
    {
        return $this->productStoreId;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param int $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param \DateTime $updatedDateTime
     */
    public function setUpdatedDateTime($updatedDateTime)
    {
        $this->updatedDateTime = $updatedDateTime;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedDateTime()
    {
        return $this->updatedDateTime;
    }

    /**
     * @param string $userCookie
     */
    public function setUserCookie($userCookie)
    {
        $this->userCookie = $userCookie;
    }

    /**
     * @return string
     */
    public function getUserCookie()
    {
        return $this->userCookie;
    }

    /**
     * @param string $userEmail
     */
    public function setUserEmail($userEmail)
    {
        $this->userEmail = $userEmail;
    }

    /**
     * @return string
     */
    public function getUserEmail()
    {
        return $this->userEmail;
    }

    /**
     * @param int $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

}
