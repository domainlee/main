<?php
namespace Admin\Model;

use Base\Model\Base;
class OrderProduct extends Base{
	protected $id;
	protected $orderId;
	protected $productId;
	protected $storeId;
	protected $productPrice;
	protected $quantity;
	protected $product;
	protected $productColor;
    protected $productSize;

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
	 * @return the $product
	 */
	public function getProduct() {
		return $this->product;
	}

	/**
	 * @param field_type $product
	 */
	public function setProduct($product) {
		$this->product = $product;
	}

	/**
	 * @return the $id
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param field_type $id
	 */
	public function setId($id) {
		$this->id = $id;
	}

	/**
	 * @return the $orderId
	 */
	public function getOrderId() {
		return $this->orderId;
	}

	/**
	 * @param field_type $orderId
	 */
	public function setOrderId($orderId) {
		$this->orderId = $orderId;
	}

	/**
	 * @return the $productId
	 */
	public function getProductId() {
		return $this->productId;
	}

	/**
	 * @param field_type $productId
	 */
	public function setProductId($productId) {
		$this->productId = $productId;
	}

	/**
	 * @return the $storeId
	 */
	public function getStoreId() {
		return $this->storeId;
	}

	/**
	 * @param field_type $storeId
	 */
	public function setStoreId($storeId) {
		$this->storeId = $storeId;
	}

	/**
	 * @return the $productPrice
	 */
	public function getProductPrice() {
		return $this->productPrice;
	}

	/**
	 * @param field_type $productPrice
	 */
	public function setProductPrice($productPrice) {
		$this->productPrice = $productPrice;
	}

	/**
	 * @return the $quantity
	 */
	public function getQuantity() {
		return $this->quantity;
	}

	/**
	 * @param field_type $quantity
	 */
	public function setQuantity($quantity) {
		$this->quantity = $quantity;
	}

	
}