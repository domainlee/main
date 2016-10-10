<?php
/**
 * @category   	Shop99 library
 * @copyright  	http://shop99.vn
 * @license    	http://shop99.vn/license
 */

namespace Store\Model;

use Base\Model\Base;

class Domain extends Base {

	/**
	 * @var int
	 */
    protected $id;

	/**
	 * @var int
	 */
    protected $storeId;

	/**
	 * @var string
	 */
    protected $name;

	/**
	 * @var string
	 */
    protected $alias;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var \Uitemplate\Model\Uitemplate
     */
    protected $uitemplate;

    /**
     * @var string
     */
    protected $uitemplateId;

    /**
     * @var string
     */
    protected  $gaTrackingCode;

    /**
     * @var string
     */
    protected $uitemplateOptions;

    /**
     * @var int
     */
    protected $createdById;

    /**
     * @var int
     */
    protected $createdDateTime;

    /**
     * @var int
     */
    protected $showInRoller;

    /**
     * @var string
     */
    protected $uitemplateImage;

	/**
	 * @return the $id
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param number $id
	 */
	public function setId($id) {
		$this->id = $id;
		return $this;
	}

	/**
	 * @return the $storeId
	 */
	public function getStoreId() {
		return $this->storeId;
	}

	/**
	 * @param number $storeId
	 */
	public function setStoreId($storeId) {
		$this->storeId = $storeId;
		return $this;
	}

	/**
	 * @return the $name
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName($name) {
		$this->name = $name;
		return $this;
	}

	/**
	 * @return the $alias
	 */
	public function getAlias() {
		return $this->alias;
	}

	/**
	 * @param string $alias
	 */
	public function setAlias($alias) {
		$this->alias = $alias;
		return $this;
	}

	/**
	 * @return the $description
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @param string $description
	 */
	public function setDescription($description) {
		$this->description = $description;
		return $this;
	}

	/**
	 * @return \Uitemplate\Model\Uitemplate
	 */
	public function getUitemplate() {
		return $this->uitemplate;
	}

	/**
	 * @param \Uitemplate\Model\Uitemplate $uitemplate
	 */
	public function setUitemplate($uitemplate) {
		$this->uitemplate = $uitemplate;
		return $this;
	}

	/**
	 * @param string $code
	 */
	public function setGaTrackingCode($code) {
		$this->gaTrackingCode = $code;
	}

	/**
	 * @return string
	 */
	public function getGaTrackingCode() {
		return $this->gaTrackingCode;
	}
	/**
	 * @return the $uitemplateId
	 */
	public function getUitemplateId() {
		return $this->uitemplateId;
	}

	/**
	 * @param string $uitemplateId
	 */
	public function setUitemplateId($uitemplateId) {
		$this->uitemplateId = $uitemplateId;
		return $this;
	}

	/**
	 * @return the $uitemplateOptions
	 */
	public function getUitemplateOptions() {
		return $this->uitemplateOptions;
	}

	/**
	 * @param string $uitemplateOptions
	 */
	public function setUitemplateOptions($uitemplateOptions) {
		$this->uitemplateOptions = $uitemplateOptions;
		return $this;
	}

	/**
	 * @return the $createdById
	 */
	public function getCreatedById() {
		return $this->createdById;
	}

	/**
	 * @param number $createdById
	 */
	public function setCreatedById($createdById) {
		$this->createdById = $createdById;
		return $this;
	}

	/**
	 * @return the $createdDateTime
	 */
	public function getCreatedDateTime() {
		return $this->createdDateTime;
	}

	/**
	 * @param number $createdDateTime
	 */
	public function setCreatedDateTime($createdDateTime) {
		$this->createdDateTime = $createdDateTime;
		return $this;
	}
	/**
	 * @return the $showInRoller
	 */
	public function getShowInRoller() {
		return $this->showInRoller;
	}

	/**
	 * @param number $showInRoller
	 */
	public function setShowInRoller($showInRoller) {
		$this->showInRoller = $showInRoller;
	}

	/**
	 * @return the $uitemplateImage
	 */
	public function getUitemplateImage() {
		return $this->uitemplateImage;
	}

	/**
	 * @param string $uitemplateImage
	 */
	public function setUitemplateImage($uitemplateImage) {
		$this->uitemplateImage = $uitemplateImage;
	}

	/**
	 * @return string
	 */
	public function getUitemplateImageUri() {
		return \Base\Model\Uri::getImgSrc($this, $this->getUitemplateImage());
	}
}