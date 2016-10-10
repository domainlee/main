<?php
namespace Product\Model;
use Base\Model\Base;


class Product extends Base{
	protected $id;
	protected $name;
	protected $price;
    protected $priceOld;
	protected $viewLink;
	protected $cateName;
	protected $categoryId;
	protected $image;
	protected $excludedId;
	protected $status;
	protected $childs;
	protected $intro;
	protected $quantity;
	protected $code;
	protected $size;
	protected $color;
	protected $colorId;
	protected $storeId;
	protected $parentId;
	protected $value;
	protected $prices;

	const STATUS_ACTIVE = 1;
	const STATUS_INACTIVE = 0;
	const SELECT_MODE_ALL 	= 1;
	const SELECT_MODE_LEAF 	= 2;
	const SELECT_MODE_JSON 	= 3;
	const SELECT_MODE_NORMAL = 4;
	
	protected $statuses = array(
			\Product\Model\Product::STATUS_ACTIVE => 'Hoạt động',
			\Product\Model\Product::STATUS_INACTIVE => 'Không hoạt động',
	);
	/**
	 * @return the $colorId
	 */
	public function getColorId() {
		return $this->colorId;
	}

    /**
     * @param mixed $priceOld
     */
    public function setPriceOld($priceOld)
    {
        $this->priceOld = $priceOld;
    }

    /**
     * @return mixed
     */
    public function getPriceOld()
    {
        return $this->priceOld;
    }

	/**
	 * @param field_type $colorId
	 */
	public function setColorId($colorId) {
		$this->colorId = $colorId;
	}

	/**
	 * @return the $value
	 */
	public function getValue() {
		return $this->value;
	}

	/**
	 * @param field_type $value
	 */
	public function setValue($value) {
		$this->value = $value;
	}

	/**
	 * @return the $parentId
	 */
	public function getParentId() {
		return $this->parentId;
	}

	/**
	 * @param field_type $parentId
	 */
	public function setParentId($parentId) {
		$this->parentId = $parentId;
	}

	/**
	 * @return the $storeId
	 */
	public function getStoreId() {
		return $this->storeId;
	}

	/**
	 * @param field_type $storeId
	 */
	public function setStoreId($storeId) {
		$this->storeId = $storeId;
	}

	/**
	 * @return the $size
	 */
	public function getSize() {
		return $this->size;
	}

	/**
	 * @param field_type $size
	 */
	public function setSize($size) {
		$this->size = $size;
	}

	/**
	 * @return the $color
	 */
	public function getColor() {
		return $this->color;
	}

	/**
	 * @param field_type $color
	 */
	public function setColor($color) {
		$this->color = $color;
	}

	public function getStatuses() {
		return $this->statuses;
	}
	//
	public function setCategoryId($categoryId){
		return $this->categoryId = $categoryId;
	}
	public function getCategoryId(){
		return $this->categoryId;
	}
	//
	public function setCateName($cateName){
		return $this->cateName = $cateName;
	}
	public function getCateName(){
		return $this->cateName;
	}
	//
	public function setViewLink($viewLink){
		return $this->viewLink = $viewLink;
	}
	public function getViewLink(){
		return \Base\Model\Uri::slugify($this);
	}
	//
	public function getImgUri() {
		return \Base\Model\Uri::getImgSrc($this);
	}
	//
	public function setId($id){
		return $this->id = $id;
	}
	public function getId(){
		return $this->id;
	}
	//
	public function setName($name){
		return $this->name =$name;
	}
	public function getName(){
		return $this->name;
	}
	//
	public function setPrice($price){
		return $this->price = $price;
	}
	public function getPrice(){
		return $this->price;
	}
	//
	public function getExcludedId() {
		return $this->excludedId;
	}
	
	/**
	 * @param field_type $excludedId
	 */
	public function setExcludedId($excludedId) {
		$this->excludedId = $excludedId;
	}
	//
	public function getImage() {
		return $this->image;
	}
	public function setImage($image) {
		$this->image = $image;
		return $this;
	}
	//
	public function getChilds() {
		return $this->childs;
	}
	
	/**
	 * @param field_type $childs
	 */
	public function setChilds($childs) {
		$this->childs = $childs;
	}
	//
	//
	public function getIntro() {
		return $this->intro;
	}
	public function setIntro($intro) {
		$this->intro = $intro;
		return $this;
	}
	//
	public function getCode() {
		return $this->code;
	}
	public function setCode($code) {
		$this->code = $code;
		return $this;
	}
	//
	public function getQuantity() {
		return $this->quantity;
	}
	public function setQuantity($quantity) {
		$this->quantity = $quantity;
		return $this;
	}
	//
	//
	public function setStatus($status){
		return $this->status = $status;
	}
	public function getStatus(){
		return $this->status;
	}

    public function setPrices($prices)
    {
        $this->prices = $prices;
    }
    /**
     * @return array
     */
    public function getPrices()
    {
        return $this->prices;
    }

	//
	public function exchangeArray($data){
		parent::exchangeArray($data);
		if(isset($data['value'])){
			$model = new \Admin\Model\Article();
			$model->setName($data['value']);
			$model->setCateName($model);
		}
	}
	private function multiexplode ($delimiters,$string) {
		$ary = explode($delimiters[0],$string);
		array_shift($delimiters);
		if($delimiters != NULL) {
			foreach($ary as $key => $val) {
				$ary[$key] = $this->multiexplode($delimiters, $val);
			}
		}
		return  $ary;
	}

	public function prepareSearch($options = null, $request = null)
	{
//        $request = $this->getServiceLocator()->get('Request');
		$variables = [];
	
//		if (isset($options['storeId'])) {
//			$this->setStoreId($options['storeId']);
//		} else {
//			$store = $request->getQuery('store', $this->getServiceLocator()->get('Store\Service\Store')->getStoreId());
//			$this->setStoreId((int)$store);
//		}

        $price = $request->getQuery('price');
        if (isset($options['price'])) {
            $price = $options['price'];
        }
        if ($price && is_array($prices = $this->multiexplode([',', ':'], $price)) && count($prices)) {
            $this->setPrices($prices);
            $variables['price'] = $prices;
        }
        // search by name, discount (has oldPrice)
        $name = strip_tags($request->getQuery('q'));

        if (isset($options['name'])) {
            $name = $options['name'];
        }
        if ($name) {
            $this->setName($name);
            $variables['q'] = $name;
        }

		return $variables;
	}

	public function toStd(){
		$o = new \stdClass();
		$o->id = $this->getId();
		$o->name = $this->getName();
		$o->quantity = (int)$this->getQuantity();
		return $o;
	}
	
	
	
	
	
	
	
	
	
	
	
}