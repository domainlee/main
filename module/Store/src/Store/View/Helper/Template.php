<?php

namespace Store\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Store\Model\Uitemplate;

class Template extends AbstractHelper
{
    /**
     * @var \Store\Model\Uitemplate
     */
    protected $storeUitemplate;

	/**
	 * @return \Store\Model\Uitemplate
	 */
	public function getStoreUitemplate() {
		return $this->storeUitemplate;
	}

	/**
	 * @param \Store\Model\Uitemplate $storeUitemplate
	 */
	public function setStoreUitemplate($storeUitemplate) {
		$this->storeUitemplate = $storeUitemplate;
		return $this;
	}

	public function __toString() {
		if($this->getStoreUitemplate()->getUitemplate()) {
			return $this->getStoreUitemplate()->getUitemplate()->getName();
		}
	}

	/**
     * @return String
     */
    public function __invoke() {
		return $this;
    }
}