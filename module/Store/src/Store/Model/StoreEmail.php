<?php
/**
 * @category   	Shop99 library
 * @copyright  	http://shop99.vn
 * @license    	http://shop99.vn/license
 */

namespace Store\Model;

use Base\Model\Base;

class StoreEmail extends Base {

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
    protected $email;

	/**
	 * @var string
	 */
    protected $password;

    /**
     * @return $id
     */
    public function getId()
    {
    	return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
    	$this->id = $id;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
    	$this->password = $password;
    }

    /**
     * @return $password
     */
    public function getPassword()
    {
    	return $this->password;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
    	$this->email = $email;
    }

    /**
     * @return $email
     */
    public function getEmail()
    {
    	return $this->email;
    }
}
