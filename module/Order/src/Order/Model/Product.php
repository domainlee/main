<?php
/**
 * @category  Shop99 library
 * @copyright http://shop99.vn
 * @license   http://shop99.vn/license
 */

namespace Order\Model;

use Base\Model\Base;

class Product extends Base
{
    protected $id;
    protected $orderId;
    protected $productId;
    protected $storeId;
    protected $productPrice;
    protected $productColor;
    protected $productSize;
    protected $quantity;

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
     * @param mixed $orderId
     */
    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;
    }

    /**
     * @return mixed
     */
    public function getOrderId()
    {
        return $this->orderId;
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
     * @param mixed $productPrice
     */
    public function setProductPrice($productPrice)
    {
        $this->productPrice = $productPrice;
    }

    /**
     * @return mixed
     */
    public function getProductPrice()
    {
        return $this->productPrice;
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
     * @param mixed $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * @return mixed
     */
    public function getQuantity()
    {
        return $this->quantity;
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


}