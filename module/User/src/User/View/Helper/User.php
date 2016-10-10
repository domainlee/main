<?php
/**
 * User\View\Helper\User
 *
 * @category   	Shop99 library
 * @copyright  	http://shop99.vn
 * @license    	http://shop99.vn/license
 */

namespace User\View\Helper;

use Zend\View\Helper\AbstractHelper;

class User extends AbstractHelper {

	/**
	 * @var \User\Service\User
	 */
	protected $serviceUser;

	/**
	 * @return the $serviceUser
	 */
	public function getServiceUser() {
		return $this->serviceUser;
	}

	/**
	 * @param \User\Service\User $serviceUser
	 */
	public function setServiceUser($serviceUser) {
		$this->serviceUser = $serviceUser;
		return $this;
	}

	public function __invoke() {
		return $this->getServiceUser();
	}
}