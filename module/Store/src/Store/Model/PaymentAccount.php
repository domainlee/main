<?php
/**
 * @category       Shop99 library
 * @copyright      http://shop99.vn
 * @license        http://shop99.vn/license
 */

namespace Store\Model;

use Base\Model\Base;

class PaymentAccount extends Base
{

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
    protected $storeId;

    /**
     * @var int
     */
    protected $bankId;

    /**
     * @var string
     */
    protected $bankBranch;

    /**
     * @var string
     */
    protected $bankAccountNumber;

    /**
     * @var string
     */
    protected $bankAccountHolder;

    /**
     * @var int
     */
    protected $paymentGatewayId;

    /**
     * @var int
     */
    protected $merchantId;

    /**
     * @var string
     */
    protected $username;

    /**
     * @var int
     */
    protected $status;

    /**
     * @var int
     */
    protected $createdById;

    /**
     * @var int
     */
    protected $createdDateTime;

    /**
     * @param string $bankAccountHolder
     */
    public function setBankAccountHolder($bankAccountHolder)
    {
        $this->bankAccountHolder = $bankAccountHolder;
    }

    /**
     * @return string
     */
    public function getBankAccountHolder()
    {
        return $this->bankAccountHolder;
    }

    /**
     * @param string $bankAccountNumber
     */
    public function setBankAccountNumber($bankAccountNumber)
    {
        $this->bankAccountNumber = $bankAccountNumber;
    }

    /**
     * @return string
     */
    public function getBankAccountNumber()
    {
        return $this->bankAccountNumber;
    }

    /**
     * @param string $bankBranch
     */
    public function setBankBranch($bankBranch)
    {
        $this->bankBranch = $bankBranch;
    }

    /**
     * @return string
     */
    public function getBankBranch()
    {
        return $this->bankBranch;
    }

    /**
     * @param int $bankId
     */
    public function setBankId($bankId)
    {
        $this->bankId = $bankId;
    }

    /**
     * @return int
     */
    public function getBankId()
    {
        return $this->bankId;
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
     * @param int $createdDateTime
     */
    public function setCreatedDateTime($createdDateTime)
    {
        $this->createdDateTime = $createdDateTime;
    }

    /**
     * @return int
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
     * @param int $merchantId
     */
    public function setMerchantId($merchantId)
    {
        $this->merchantId = $merchantId;
    }

    /**
     * @return int
     */
    public function getMerchantId()
    {
        return $this->merchantId;
    }

    /**
     * @param int $paymentGatewayId
     */
    public function setPaymentGatewayId($paymentGatewayId)
    {
        $this->paymentGatewayId = $paymentGatewayId;
    }

    /**
     * @return int
     */
    public function getPaymentGatewayId()
    {
        return $this->paymentGatewayId;
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
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

}