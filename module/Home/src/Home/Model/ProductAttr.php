<?php
namespace Home\Model;
use Base\Model\Base;

class ProductAttr extends Base
{
    const COLOR = 1;
    const SIZE = 2;

    protected $id;
    protected $type;
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