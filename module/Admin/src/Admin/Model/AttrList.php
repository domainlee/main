<?php
namespace Admin\Model;
use Base\Model\Base;

class AttrList extends Base{

    protected $productattrId;
    protected $productId;

    const COLOR = 1;
    const SIZE = 2;

    protected $type;

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


}
