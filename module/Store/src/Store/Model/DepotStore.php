<?php
/**
 * @category   	Shop99 library
 * @copyright  	http://shop99.vn
 * @license    	http://shop99.vn/license
 */

namespace Store\Model;

use Base\Model\Base;

/**
 * Class DepotStore
 * @package Store\Model
 */
class DepotStore extends Base
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var int
     */
    protected $depotId;

    /**
     * @var int
     */
    protected $storeId;

    /**
     * @var string
     */
    protected $image;

    /**
     * @var int
     */
    protected $type;

    /**
     * @var int
     */
    protected $defaultShopping;

    /**
     * @var int
     */
    protected $typeRetail;

    /**
     * @var int
     */
    protected $retailRequireCustomerInfo;

    /**
     * @var int
     */
    protected $typeShipping;

    /**
     * @var int
     */
    protected $imexEditableDays;

    /**
     * @var int
     */
    protected $printCopyRetailBill;

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
    protected $address;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $phone;

    /**
     * @var string
     */
    protected $note;

    /**
     * @var int
     */
    protected $createdById;

    /**
     * @var \DateTime
     */
    protected $createdDateTime;

    /**
     * @var int
     */
    protected $cityId;

    /**
     * @var string
     */
    protected $depotName;

    /**
     * @var string
     */
    protected $depotAddress;

    /**
     * @var string
     */
    protected $nativeName;

    /**
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param int $cityId
     */
    public function setCityId($cityId)
    {
        $this->cityId = $cityId;
    }

    /**
     * @return int
     */
    public function getCityId()
    {
        return $this->cityId;
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
     * @param int $defaultShopping
     */
    public function setDefaultShopping($defaultShopping)
    {
        $this->defaultShopping = $defaultShopping;
    }

    /**
     * @return int
     */
    public function getDefaultShopping()
    {
        return $this->defaultShopping;
    }

    /**
     * @param string $depotAddress
     */
    public function setDepotAddress($depotAddress)
    {
        $this->depotAddress = $depotAddress;
    }

    /**
     * @return string
     */
    public function getDepotAddress()
    {
        return $this->depotAddress;
    }

    /**
     * @param int $depotId
     */
    public function setDepotId($depotId)
    {
        $this->depotId = $depotId;
    }

    /**
     * @return int
     */
    public function getDepotId()
    {
        return $this->depotId;
    }

    /**
     * @param string $depotName
     */
    public function setDepotName($depotName)
    {
        $this->depotName = $depotName;
    }

    /**
     * @return string
     */
    public function getDepotName()
    {
        return $this->depotName;
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
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
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
     * @param int $imexEditableDays
     */
    public function setImexEditableDays($imexEditableDays)
    {
        $this->imexEditableDays = $imexEditableDays;
    }

    /**
     * @return int
     */
    public function getImexEditableDays()
    {
        return $this->imexEditableDays;
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
     * @param string $nativeName
     */
    public function setNativeName($nativeName)
    {
        $this->nativeName = $nativeName;
    }

    /**
     * @return string
     */
    public function getNativeName()
    {
        return $this->nativeName;
    }

    /**
     * @param string $note
     */
    public function setNote($note)
    {
        $this->note = $note;
    }

    /**
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param int $printCopyRetailBill
     */
    public function setPrintCopyRetailBill($printCopyRetailBill)
    {
        $this->printCopyRetailBill = $printCopyRetailBill;
    }

    /**
     * @return int
     */
    public function getPrintCopyRetailBill()
    {
        return $this->printCopyRetailBill;
    }

    /**
     * @param int $retailRequireCustomerInfo
     */
    public function setRetailRequireCustomerInfo($retailRequireCustomerInfo)
    {
        $this->retailRequireCustomerInfo = $retailRequireCustomerInfo;
    }

    /**
     * @return int
     */
    public function getRetailRequireCustomerInfo()
    {
        return $this->retailRequireCustomerInfo;
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
     * @param int $typeRetail
     */
    public function setTypeRetail($typeRetail)
    {
        $this->typeRetail = $typeRetail;
    }

    /**
     * @return int
     */
    public function getTypeRetail()
    {
        return $this->typeRetail;
    }

    /**
     * @param int $typeShipping
     */
    public function setTypeShipping($typeShipping)
    {
        $this->typeShipping = $typeShipping;
    }

    /**
     * @return int
     */
    public function getTypeShipping()
    {
        return $this->typeShipping;
    }

}