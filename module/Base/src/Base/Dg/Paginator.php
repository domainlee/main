<?php
namespace Base\Dg;

class Paginator{

	public function __construct($rowCount, $data, $paging, $rowInPage = null) {
		$this->setRowCount($rowCount);
		$this->setData($data);
		$this->setPaging($paging);
		$this->setRowInPage($rowInPage);
	}
	/**
	 *
	 * @var array
	 */
	protected $data;
	/**
	 *
	 * @var int
	 */
	protected $rowCount;

	/**
	 * @var int
	 */
	protected $rowInPage;
	/**
	 * @var array
	 */
	protected $paging;
	/**
	 * @var int
	 */
	protected $numPerPage;
	/**
	 * @return the $rowInPage
	 */
	public function getRowInPage() {
		return $this->rowInPage;
	}

	/**
	 * @param number $rowInPage
	 */
	public function setRowInPage($rowInPage) {
		$this->rowInPage = $rowInPage;
	}

	/**
	 * @return the $data
	 */
	public function getData() {
		return $this->data;
	}

	/**
	 * @param multitype: $data
	 */
	public function setData($data) {
		$this->data = $data;
	}

	/**
	 * @return the $rowCount
	 */
	public function getRowCount() {
		return $this->rowCount;
	}

	/**
	 * @param int $rowCount
	 */
	public function setRowCount($rowCount) {
		$this->rowCount = $rowCount;
	}

	/**
	 * @return the $paging
	 */
	public function getPaging() {
		return $this->paging;
	}

	/**
	 * @param multitype: $paging
	 */
	public function setPaging($paging) {
		$this->paging = $paging;
	}

	/**
	 * @return the $numPerPage
	 */
	public function getNumPerPage() {
		return $this->numPerPage;
	}

	/**
	 * @param number $numPerPage
	 */
	public function setNumPerPage($numPerPage) {
		$this->numPerPage = $numPerPage;
	}

}