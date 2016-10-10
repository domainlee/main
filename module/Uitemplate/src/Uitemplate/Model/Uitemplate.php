<?php
/**
 * @category   	Shop99 library
 * @copyright  	http://shop99.vn
 * @license    	http://shop99.vn/license
 */

namespace Uitemplate\Model;

use Base\Model\Base;

class Uitemplate extends Base {

	/**
	 * @var int
	 */
    protected $id;

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
    protected $directory;

    /**
     * @var string
     */
    protected $version;

	/**
	 * @var int
	 */
    protected $type;

	/**
	 * @var string
	 */
    protected $options;

    /**
     * @var int
     */
    protected $createdById;

    /**
     * @var string
     */
    protected $createdDateTime;

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
	 * @return the $directory
	 */
	public function getDirectory() {
		return $this->directory;
	}

	/**
	 * @param string $directory
	 */
	public function setDirectory($directory) {
		$this->directory = $directory;
		return $this;
	}

	/**
	 * @return the $version
	 */
	public function getVersion() {
		return $this->version;
	}

	/**
	 * @param string $version
	 */
	public function setVersion($version) {
		$this->version = $version;
		return $this;
	}

	/**
	 * @return the $type
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @param number $type
	 */
	public function setType($type) {
		$this->type = $type;
		return $this;
	}

	/**
	 * @return the $options
	 */
	public function getOptions() {
		return $this->options;
	}

	/**
	 * @param string $options
	 */
	public function setOptions($options) {
		$this->options = $options;
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
	 * @param string $createdDateTime
	 */
	public function setCreatedDateTime($createdDateTime) {
		$this->createdDateTime = $createdDateTime;
		return $this;
	}
}