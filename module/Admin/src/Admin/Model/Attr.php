<?php
namespace Admin\Model;
use Base\Model\Base;

class Attr extends Base{

    protected $id;
    protected $productId;
    protected $name;
    protected $colorCode;

    const COLOR = 1;
    const SIZE = 2;

    protected $type = [self::COLOR => 'Màu sắc', self::SIZE => 'Size'];

    /**
     * @param array $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return array
     */
    public function getType()
    {
        return $this->type;
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



}
