<?php

namespace User\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class User extends AbstractPlugin {

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