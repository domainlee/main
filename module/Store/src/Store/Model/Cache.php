<?php
/**
 * @author VanCK
 * @category   	Shop99 library
 * @copyright  	http://shop99.vn
 * @license    	http://shop99.vn/license
 */

namespace Store\Model;

use Base\Model\Base;

class Cache extends Base
{
	const STORE_PRODUCT_CATEGORIES_ACTIVE = 'store_product_categories-active';

	/**
	 * @var int
	 */
	protected $storeId;
	/**
	 * @var string
	 */
	protected $name;
	/**
	 * @var int
	 */
	protected $content;

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
	}

	/**
	 * @return the $content
	 */
	public function getContent() {
		return $this->content;
	}

	/**
	 * @param number $content
	 */
	public function setContent($content) {
		$this->content = $content;
	}

	/**
	 * @author VanCK
	 * build active product categories
	 */
	public function getActiveProductCategories()
	{
		$arrCategories = json_decode($this->getContent(), true);
		if(!is_array($arrCategories) || !count($arrCategories)) {
			return null;
		}
		$results = [];
		foreach($arrCategories as $arrCategory) {
			$category = new \Product\Model\Category();
			$category->exchangeArray($arrCategory['data']);
			if(isset($arrCategory['childs']) && is_array($arrCategory['childs'])) {
				$category->setChilds($this->buildProductCategoryChilds($arrCategory['childs']));
			}
			$results[] = $category;
		}
		return $results;
	}

	/**
	 * @author VanCK
	 * @param \Product\Model\Category $category
	 * @param array $data
	 * @return array
	 */
	private function buildProductCategoryChilds($arrChilds)
	{
		if(!is_array($arrChilds) || !count($arrChilds)) {
			return null;
		}
		$results = [];
		foreach ($arrChilds as $childData) {
			if(!isset($childData['data']) || !is_array($childData['data']) || !count($childData['data'])) {
				continue;
			}
			$child = new \Product\Model\Category();
			$child->exchangeArray($childData['data']);
			$results[] = $child;
			if(isset($childData['childs'])) {
				$child->setChilds($this->buildProductCategoryChilds($childData['childs']));
			}
		}
		return $results;
	}
}