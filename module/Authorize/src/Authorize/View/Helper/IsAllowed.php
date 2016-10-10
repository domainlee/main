<?php
/**
 * User\View\Helper\User
 *
 * @category   	Restaurant library
 * @copyright  	http://restaurant.vn
 * @license    	http://restaurant.vn/license
 */

namespace Authorize\View\Helper;

use Zend\View\Helper\AbstractHelper;

class IsAllowed extends AbstractHelper {

	/**
	 * @var \Authorize\Service\Authorize
	 */
	protected $serviceAuthorize;

	/**
	 * @return \Authorize\Service\Authorize
	 */
	public function getServiceAuthorize() {
		return $this->serviceAuthorize;
	}

	/**
	 * @param \Authorize\Service\Authorize $serviceAuthorize
	 */
	public function setServiceAuthorize($serviceAuthorize) {
		$this->serviceAuthorize = $serviceAuthorize;
		return $this;
	}

	public function __invoke($resource, $privilege = null) {
		return $this->getServiceAuthorize()->isAllowed($resource, $privilege);
	}
}