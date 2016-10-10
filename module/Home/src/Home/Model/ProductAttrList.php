<?php
namespace Home\Model;
use Base\Model\Base;

class ProductAttrList extends Base
{
    const COLOR = 1;
    const SIZE = 2;

    protected $productId;
    protected $type;
    protected $productattrId;
    protected $name;
    protected $colorCode;

    /**
     * @param mixed $colorCode
     */
    public function setColorCode($colorCode)
    {
        $this->colorCode = $colorCode;
    }

    /**
     * @return mixed
     */
    public function getColorCode()
    {
        return $this->colorCode;
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
     * @param mixed $productId
     */
    public function setProductId($productId)
    {
        $this->productId = $productId;
    }

    /**
     * @return mixed
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * @param mixed $productattrId
     */
    public function setProductattrId($productattrId)
    {
        $this->productattrId = $productattrId;
    }

    /**
     * @return mixed
     */
    public function getProductattrId()
    {
        return $this->productattrId;
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



}