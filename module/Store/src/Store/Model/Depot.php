<?php
namespace Store\Model;

use Base\Model\Base;

class Depot
{
	protected $depotName;
	protected $depotAddress;
	protected $depotCode;

	public function getDepotName(){
		return $this->depotName;
	}

	public function setDepotName($depotName){
		$this->depotName = $depotName;
		return $this;
	}

	public function getDepotAddress(){
		return $this->depotAddress;
	}

	public function setDepotAddress($depotAddress){
		$this->depotAddress = $depotAddress;
		return $this;
	}

	public function getDepotCode(){
		return $this->depotCode;
	}

	public function setDepotCode($depotCode){
		$this->depotCode = $depotCode;
		return $this;
	}
}