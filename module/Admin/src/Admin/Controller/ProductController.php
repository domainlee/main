<?php
namespace Admin\Controller;

use Admin\Model\AttrList;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\View\View;
use Admin\Model\Attr;
use Home\Model\DateBase;


class ProductController extends AbstractActionController{

	public function indexAction(){
		$this->layout('layout/admin');
		$model = new \Admin\Model\Product();
		$mapper = $this->getServiceLocator()->get('Admin\Model\ProductMapper');
		$model->exchangeArray((array)$this->getRequest()->getQuery());
		$modelSt = new \Admin\Model\Store();
		$mapperSt = $this->getServiceLocator()->get('Admin\Model\StoreMapper');
        $u = $this->getServiceLocator()->get('User\Service\User');
//        $storeId = $this->getServiceLocator()->get('Store\Service\Store')->getStoreId();
        $storeId = $u->getStoreId();
        $options['isAdmin'] = $this->user()->isSuperAdmin();
        $fFilter = new \Admin\Form\ProductSearch($options);
        if(!$this->user()->isSuperAdmin()){
            $model->setStoreId($storeId);
        }else{
            $fFilter->setStoreIds($modelSt->toSelectBoxArray($mapperSt->fetchAll($modelSt)));
        }
		$fFilter->bind($model);
		$page = (int)$this->getRequest()->getQuery()->page ? : 1;
		$results = $mapper->search($model, array($page,20));
		return new ViewModel(array(
            'fFilter' => $fFilter,
            'results' => $results
		));
	}

    protected function getPagingParams($page = null, $icpp = null)
    {
        /* @var $request \Zend\Http\Request */
        $request = $this->getRequest();

        $page = (int)$request->getQuery('page', $page);
        $icpp = (int)$request->getQuery('icpp', $icpp);
        $options = array(
            'page' => $page > 0 ? $page : 1,
            'icpp' => $icpp > 0 ? ($icpp > 200 ? 200 : $icpp) : 30,
        );
        return $options;
    }

    public function attrAction(){
        $this->layout('layout/admin');
        $attr = new \Admin\Model\Attr();
        $mapper = $this->getServiceLocator()->get('Admin\Model\AttrMapper');
        $attr->exchangeArray($this->params()->fromQuery());
        $results = $mapper->search($attr, $this->getPagingParams(null, 10));
        return new ViewModel(array(
            'results' => $results
        ));
    }

    public function addattrAction(){
        $this->layout('layout/admin');
        $form = new \Admin\Form\Attr($this->getServiceLocator());
        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());
            if ($form->isValid()){
                $attr = new Attr();
                $attr->exchangeArray($form->getData());
                $attrMapper = $this->getServiceLocator()->get('Admin\Model\AttrMapper');
                $attrMapper->save($attr);
                return $this->redirect()->toUrl('/admin/product/attr');
            }
        }
        return new ViewModel(array(
            'form' => $form,
        ));
    }

	public function changeactiveAction(){
		$this->layout('layout\admin');
		$id = $this->getEvent()->getRouteMatch()->getParam('id');
		$mapper = $this->getServiceLocator()->get('Admin\Model\ProductMapper');
		$model = $mapper->getId($id);
		if($model->getStatus() == \Admin\Model\Product::STATUS_ACTIVE){
			$model->setStatus(\Admin\Model\Product::STATUS_INACTIVE);
		}
		else{
			$model->setStatus(\Admin\Model\Product::STATUS_ACTIVE);
		}
		$mapper->save($model);
		$this->redirect()->toUrl('/admin/product');
	}
	
	public function addAction(){

		$this->layout('layout/admin');
		$model = new \Admin\Model\Product();
        $u = $this->getServiceLocator()->get('User\Service\User');
        $storeId = $u->getStoreId();
//        $storeId = $this->getServiceLocator()->get('Store\Service\Store')->getStoreId();

        $modelCate = new \Admin\Model\Productc();

        if(!$this->user()->isSuperAdmin()){
            $modelCate->setStoreId($storeId);
        }
		$mapperCate = $this->getServiceLocator()->get('Admin\Model\ProductcMapper');
		$category = $mapperCate->fetchAll($modelCate);
		$modelSt = new \Admin\Model\Store();
        if(!$this->user()->isSuperAdmin()){
            $modelSt->setId($storeId);
        }
		$mapperSt = $this->getServiceLocator()->get('Admin\Model\StoreMapper');
        $attr = new \Admin\Model\Attr();
        $attr->setType(\Admin\Model\Attr::COLOR);
        $attrMapper = $this->getServiceLocator()->get('Admin\Model\AttrMapper');
        $attrlistMapper = $this->getServiceLocator()->get('Admin\Model\AttrListMapper');
        $attrs = $attrMapper->fetchAll($attr);
        $a = [];
        foreach($attrs as $b){
            $a[$b->getId()] = ['label' => $b->getName(), 'value' => $b->getId()];
        }
        $s = [];
        $attr = new \Admin\Model\Attr();
        $attr->setType(\Admin\Model\Attr::SIZE);
        $attrs = $attrMapper->fetchAll($attr);
        foreach($attrs as $b){
            $s[$b->getId()] = ['label' => $b->getName(), 'value' => $b->getId()];
        }

        $brand = new \Admin\Model\Brand();
        $brand->setStoreId($storeId);
        $brandMapper = $this->getServiceLocator()->get('Admin\Model\BrandMapper');
        $rBrand = $brandMapper->fetchAll($brand);

        $form = new \Admin\Form\Product($this->getServiceLocator(), null);
		$form->setCategoryIds($model->toSelectBoxArray($category,\Admin\Model\Product::SELECT_MODE_ALL));
		$form->setStoreIds($modelSt->toSelectBoxArray($mapperSt->fetchAll($modelSt)));
        if(count($rBrand)){
            $form->setBrandId($brand->fetchAllBrand($rBrand));
        }
        $form->setColor($a);
        $form->setSize($s);
        $form->bind($model);

        if($this->getRequest()->isPost()){
            $form->setData(array_merge_recursive($this->getRequest()->getPost()->toArray(),$this->getRequest()->getFiles()->toArray()));
            if($form->isValid($edit = 2)){
				$model->setId(null);
				$mapper = $this->getServiceLocator()->get('Admin\Model\ProductMapper');
//                print_r($model);die;

                $mapper->save($model);
                $attrlistMapper->update($model->getId(), \Admin\Model\AttrList::COLOR, $this->getRequest()->getPost()['color']);
                $attrlistMapper->update($model->getId(), \Admin\Model\AttrList::SIZE, $this->getRequest()->getPost()['size']);

                $mediaMapper = $this->getServiceLocator()->get('Admin\Model\MediaItemMapper');
                //Check exits cần một vòng for query select sẽ dài hơn where in id delete. Nên chỗ này xóa đi rồi saves
                $mediaMapper->deleteFileProduct($model->getId());
                $postImages = $this->getRequest()->getPost()['images'];

                if(isset($postImages) && $postImages != ''){
                    $imagesArray = explode(',', $postImages);
                    $c = 1;
                    foreach($imagesArray as $i){
                        if($i){
                            $mediaItem = new \Admin\Model\MediaItem();
                            $mediaItem->setType(\Admin\Model\MediaItem::FILE_PRODUCT);
                            $mediaItem->setItemId($model->getId());
                            $mediaItem->setFileItem($i);
                            $mediaItem->setSort($c++);
                            $mediaMapper->save($mediaItem);
                        }
                    }
                }

                $this->redirect()->toUrl('/admin/product');
			}
			}
			return new ViewModel(array(
					'form' => $form
			));
	}

	public function editAction(){
		$this->layout('layout/admin');
		if(!($id = $this->getEvent()->getRouteMatch()->getParam('id'))){
			$this->redirect()->toUrl('/admin/product');
		}
        if(!is_numeric($id)){
            $this->redirect()->toUrl('/admin/product');
        }
        $product = new \Admin\Model\Product();
        $product->setId($id);
		$mapper = $this->getServiceLocator()->get('Admin\Model\ProductMapper');
		if(!$mapper->get($product)){
            $this->redirect()->toUrl('/admin/product');
        }

//        $storeId = $this->getServiceLocator()->get('Store\Service\Store')->getStoreId();

        $u = $this->getServiceLocator()->get('User\Service\User');
        $storeId = $u->getStoreId();
        /******* category ********/
		$modelCate = new \Admin\Model\Productc();
        $mappercate = $this->getServiceLocator()->get('Admin\Model\ProductcMapper');
//        $u = $this->getServiceLocator()->get('User\Service\User');
        if(!$this->user()->isSuperAdmin()){
            $modelCate->setStoreId($storeId);
        }
		$category = $mappercate->fetchAll($modelCate);

        /******* store ********/
//        echo $u->getUser()->getStoreId();die;
		$modelSt = new \Admin\Model\Store();
        if(!$this->user()->isSuperAdmin()){
            $modelSt->setId($storeId);
        }
		$mapperSt = $this->getServiceLocator()->get('Admin\Model\StoreMapper');

        /********* Attr **********/
        $attrl =  new AttrList();
        $attrl->setProductId($id);
        $attrlistMapper = $this->getServiceLocator()->get('Admin\Model\AttrListMapper');
        $al = $attrlistMapper->fetchAll($attrl);
        $ala = [];
        foreach($al as $c){
            $ala[] = $c->getProductattrId();
        }
        $attr = new \Admin\Model\Attr();
        $attr->setType(\Admin\Model\Attr::COLOR);
        $attrMapper = $this->getServiceLocator()->get('Admin\Model\AttrMapper');
        $attrs = $attrMapper->fetchAll($attr);
        $a = [];
        foreach($attrs as $b){
            if(in_array($b->getId(), $ala)){
                $a[$b->getId()] = ['label' => $b->getName(), 'value' => $b->getId(), 'selected' => true];
            }else{
                $a[$b->getId()] = ['label' => $b->getName(), 'value' => $b->getId()];
            }
        }

        $attr = new \Admin\Model\Attr();
        $attr->setType(\Admin\Model\Attr::SIZE);
        $attrs = $attrMapper->fetchAll($attr);
        $s = [];
        foreach($attrs as $b){
            if(in_array($b->getId(), $ala)){
                $s[$b->getId()] = ['label' => $b->getName(), 'value' => $b->getId(), 'selected' => true];
            }else{
                $s[$b->getId()] = ['label' => $b->getName(), 'value' => $b->getId()];
            }
        }

        /******** Brand **********/
        $brand = new \Admin\Model\Brand();
        $brand->setStoreId($storeId);
        $brandMapper = $this->getServiceLocator()->get('Admin\Model\BrandMapper');
        $rBrand = $brandMapper->fetchAll($brand);


        /******** Form **********/
//        $form = new \Admin\Form\Product();
        $form = new \Admin\Form\Product($this->getServiceLocator(), null);

        $form->setCategoryIds($product->toSelectBoxArray($category,\Admin\Model\Product::SELECT_MODE_ALL));
		$form->setStoreIds($modelSt->toSelectBoxArray($mapperSt->fetchAll($modelSt)));
        $form->setColor($a);
        $form->setSize($s);
        $form->setBrandId($brand->fetchAllBrand($rBrand));
        $data = $product->toFormValues();

        $mediaItem = new \Admin\Model\MediaItem();
        $mediaItem->setItemId($id);
        $mediaItem->setType(\Admin\Model\MediaItem::FILE_PRODUCT);

        $mediaMapper = $this->getServiceLocator()->get('Admin\Model\MediaItemMapper');
        $m = $mediaMapper->fetchAll($mediaItem);
        $fI = [];
        if(isset($m)){
            foreach($m as $i){
                $fI[] = $i->getFileItem();
            }
        }
        $data['images'] = implode(',', $fI);

        $form->setData($data);

		if($this->getRequest()->isPost()){
            $form->setData(array_merge_recursive($this->getRequest()->getPost()->toArray(),$this->getRequest()->getFiles()->toArray()));
            if($form->isValid($edit = 1)){
                $data = $form->getData();
                $attrlistMapper = $this->getServiceLocator()->get('Admin\Model\AttrListMapper');
                if($this->getRequest()->getPost()){
                    $attrlistMapper->update($id, \Admin\Model\AttrList::COLOR, $this->getRequest()->getPost()['color']);
                    $attrlistMapper->update($id, \Admin\Model\AttrList::SIZE, $this->getRequest()->getPost()['size']);
                }
                $model = new \Admin\Model\Product();
                $model->exchangeArray($data);
                $model->setId($id);
				$mapper = $this->getServiceLocator()->get('Admin\Model\ProductMapper');
                $mapper->save($model);

                $mediaMapper = $this->getServiceLocator()->get('Admin\Model\MediaItemMapper');
                //Check exits cần một vòng for query select sẽ dài hơn where in id delete. Nên chỗ này xóa đi rồi saves
                $mediaMapper->deleteFileProduct($id);
                if(isset($data['images']) && $data['images'] != ''){
                    $imagesArray = explode(',', $data['images']);
                    $c = 1;
                    foreach($imagesArray as $i){
                        if($i){
                            $mediaItem = new \Admin\Model\MediaItem();
                            $mediaItem->setType(\Admin\Model\MediaItem::FILE_PRODUCT);
                            $mediaItem->setItemId($model->getId());
                            $mediaItem->setFileItem($i);
                            $mediaItem->setSort($c++);
                            $mediaMapper->save($mediaItem);
                        }
                    }
                }

                $this->redirect()->toUrl('/admin/product');
			}
		}
		return new ViewModel(array(
			'form' => $form,
            'itemId' => $id
		));
	}

	public function deleteAction(){
        $id = $this->getRequest()->getPost()['id'];
        if(!is_numeric($id)){
            return new JsonModel(array(
                'code'=> 0,
                'messenger' => 'Chúng tôi không tìm thấy sản phẩm này'
            ));
        }

		$mapper = $this->getServiceLocator()->get('Admin\Model\ProductMapper');
        $product = new \Admin\Model\Product();
        $product->setId($id);

		if(!$mapper->get($product)){
            return new JsonModel(array(
                'code'=> 0,
                'messenger' => 'Chúng tôi không tìm thấy sản phẩm này'
            ));
        }

        $product = clone($product);
		$mapper->delete($product);

        $mediaMapper = $this->getServiceLocator()->get('Admin\Model\MediaItemMapper');
        $mediaMapper->deleteFileProduct($product->getId());

        $attrlistMapper = $this->getServiceLocator()->get('Admin\Model\AttrListMapper');

        $attrlistMapper->update($product->getId(), \Admin\Model\AttrList::COLOR, null);
        $attrlistMapper->update($product->getId(), \Admin\Model\AttrList::SIZE, null);

		return new JsonModel(array(
			'code'=> 1,
            'messenger' => 'Đã xóa'
		));
	}

    public function categoryAction(){

        $this->layout('layout/admin');
//        $storeId = $this->getServiceLocator()->get('Store\Service\Store')->getStoreId();

        $u = $this->getServiceLocator()->get('User\Service\User');
        $storeId = $u->getStoreId();

        $model = new \Admin\Model\Productc();
        $mapper = $this->getServiceLocator()->get('Admin\Model\ProductcMapper');
        $modelSt = new \Admin\Model\Store();
        $mapperSt = $this->getServiceLocator()->get('Admin\Model\StoreMapper');
        $modelCate = new \Admin\Model\Productc();
        $mapperCate = $this->getServiceLocator()->get('Admin\Model\ProductcMapper');
        $category = $mapperCate->fetchAll($modelCate);
        $abc = $model->toSelectBoxArray($category,\Admin\Model\Product::SELECT_MODE_ALL);
        $model->exchangeArray((array)$this->getRequest()->getQuery());
        $u = $this->getServiceLocator()->get('User\Service\User');
        $options['isAdmin'] = $this->user()->isSuperAdmin();
        $fFilter = new \Admin\Form\ProductcSearch();

        $fFilter = new \Admin\Form\ProductSearch($options);
        if(!$this->user()->isSuperAdmin()){
            $model->setStoreId($storeId);
        }else{
            $fFilter->setStoreIds($modelSt->toSelectBoxArray($mapperSt->fetchAll($modelSt)));
        }

//        $fFilter->setStoreIds($modelSt->toSelectBoxArray($mapperSt->fetchAll($modelSt)));
        $fFilter->bind($model);
        $page = (int)$this->getRequest()->getQuery()->page ? : 1;
        $results = $mapper->search($model, array($page,15));

	return new ViewModel(array(
            'results'=>$results,
            'fFilter'=>$fFilter,
            'abc'=>$abc
        ));
    }

    public function addcategoryAction()
    {
        $this->layout('layout/admin');
//        $storeId = $this->getServiceLocator()->get('Store\Service\Store')->getStoreId();

        $u = $this->getServiceLocator()->get('User\Service\User');
        $storeId = $u->getStoreId();

        $model = new \Admin\Model\Productc();
        $mapper = $this->getServiceLocator()->get('Admin\Model\ProductcMapper');
        $u = $this->getServiceLocator()->get('User\Service\User');

        if(!$this->user()->isSuperAdmin()){
            $model->setStoreId($storeId);
        }
        $parents = $mapper->fetchAll($model);
        $modelSt = new \Admin\Model\Store();
        $mapperSt = $this->getServiceLocator()->get('Admin\Model\StoreMapper');
        $fFilter = $this->getServiceLocator()->get('Admin\Form\ProductcFilter');
//		$form = $this->getServiceLocator()->get('Admin\Form\Productc');
        $form = new \Admin\Form\Productc($this->getServiceLocator());
        $form->setInputFilter($fFilter);

        if(!$this->user()->isSuperAdmin()){
            $modelSt->setId($storeId);
        }
        $form->setStoreIds($modelSt->toSelectBoxArray($mapperSt->fetchAll($modelSt)));
        $form->setParentIds($model->toSelectboxArray($parents,\Admin\Model\Productc::SELECT_MODE_ALL));

		$form->bind($model);
		if($this->getRequest()->isPost()){
			$form->setData($this->getRequest()->getPost());
			if($form->isValid()){
                $mapper->save($model);
                $mediaMapper = $this->getServiceLocator()->get('Admin\Model\MediaItemMapper');
                //Check exits cần một vòng for query select sẽ dài hơn where in id delete. Nên chỗ này xóa đi rồi saves
                $mediaMapper->deleteImageCategory($model->getId());
                $postImages = $this->getRequest()->getPost()['images'];

                if(isset($postImages) && $postImages != ''){
                    $imagesArray = explode(',', $postImages);
                    $c = 1;
                    foreach($imagesArray as $i){
                        if($i){
                            $mediaItem = new \Admin\Model\MediaItem();
                            $mediaItem->setType(\Admin\Model\MediaItem::FILE_CATEGORY_PRODUCT);
                            $mediaItem->setItemId($model->getId());
                            $mediaItem->setFileItem($i);
                            $mediaItem->setSort($c++);
                            $mediaMapper->save($mediaItem);
                        }
                    }
                }

                $this->redirect()->toUrl('/admin/product/category');
			}
		}
        return new ViewModel(array(
            'form' => $form
        ));
    }

    public function deletecategoryAction(){
        $id = $this->getRequest()->getPost()['id'];
        if(!is_numeric($id)){
            return new JsonModel(array(
                'code'=> 0,
                'messenger' => 'Chúng tôi không tìm thấy sản phẩm này'
            ));
        }
        $u = $this->getServiceLocator()->get('User\Service\User');
        $storeId = $u->getStoreId();

        $categoryMapper = $this->getServiceLocator()->get('Admin\Model\ProductcMapper');
        $category = new \Admin\Model\Productc();
        $category->setId($id);
        $category->setStoreId($storeId);

        if(!$categoryMapper->get($category)){
            return new JsonModel(array(
                'code'=> 0,
                'messenger' => 'Chúng tôi không tìm thấy sản phẩm này'
            ));
        }

        $categoryMapper->delete($category);

        return new JsonModel(array(
            'code' => 1,
            'messenger' => 'Đã xóa'
        ));
    }

    public function editcategoryAction()
    {
        $this->layout('layout/admin');
        if(!($id = $this->getEvent()->getRouteMatch()->getParam('id'))){
            $this->redirect()->toUrl('/admin/product/category');
        }
        if(!is_numeric($id)){
            $this->redirect()->toUrl('/admin/product/category');
        }

        $productCategory = new \Admin\Model\Productc();
        $productCategory->setId($id);

        $u = $this->getServiceLocator()->get('User\Service\User');
        $storeId = $u->getStoreId();

        $mapper = $this->getServiceLocator()->get('Admin\Model\ProductcMapper');

        if(!$mapper->get($productCategory)){
            $this->redirect()->toUrl('/admin/product/category');
        }

        if(!$this->user()->isSuperAdmin()){
            $productCategory->setStoreId($storeId);
        }
        $parents = $mapper->fetchAll($productCategory);

        $modelSt = new \Admin\Model\Store();
        $mapperSt = $this->getServiceLocator()->get('Admin\Model\StoreMapper');
        $fFilter = $this->getServiceLocator()->get('Admin\Form\ProductcFilter');

        $form = new \Admin\Form\Productc($this->getServiceLocator());
        $form->setInputFilter($fFilter);

        if(!$this->user()->isSuperAdmin()){
            $modelSt->setId($storeId);
        }

        $mediaItem = new \Admin\Model\MediaItem();
        $mediaItem->setItemId($id);
        $mediaItem->setType(\Admin\Model\MediaItem::FILE_CATEGORY_PRODUCT);

        $mediaMapper = $this->getServiceLocator()->get('Admin\Model\MediaItemMapper');
        $m = $mediaMapper->fetchAll($mediaItem);
        $fI = [];
        if(isset($m)){
            foreach($m as $i){
                $fI[] = $i->getFileItem();
            }
        }
        $data = $productCategory->toFormValues();
        $data['images'] = implode(',', $fI);

        $form->setStoreIds($modelSt->toSelectBoxArray($mapperSt->fetchAll($modelSt)));
        $form->setParentIds($productCategory->toSelectboxArray($parents,\Admin\Model\Productc::SELECT_MODE_ALL));

        $form->setData($data);

        if($this->getRequest()->isPost()){
            $files = $this->getRequest()->getFiles();
            $form->setData($this->getRequest()->getPost());
            if($form->isValid()){
                $data = $form->getData();
                $category1 = new \Admin\Model\Productc();
                $category1->exchangeArray((array)$data);
                $category1->setId($id);
                $category1->setParentId($form->getData()['parentId']);
                $mapper->save($category1);
                $mediaMapper = $this->getServiceLocator()->get('Admin\Model\MediaItemMapper');
                //Check exits cần một vòng for query select sẽ dài hơn where in id delete. Nên chỗ này xóa đi rồi saves
                $mediaMapper->deleteImageCategory($productCategory->getId());
                $postImages = $this->getRequest()->getPost()['images'];

                if(isset($postImages) && $postImages != ''){
                    $imagesArray = explode(',', $postImages);
                    $c = 1;
                    foreach($imagesArray as $i){
                        if($i){
                            $mediaItem = new \Admin\Model\MediaItem();
                            $mediaItem->setType(\Admin\Model\MediaItem::FILE_CATEGORY_PRODUCT);
                            $mediaItem->setItemId($productCategory->getId());
                            $mediaItem->setFileItem($i);
                            $mediaItem->setSort($c++);
                            $mediaMapper->save($mediaItem);
                        }
                    }
                }

                $this->redirect()->toUrl('/admin/product/category');
            }
        }
        return new ViewModel(array(
            'form' => $form,
            'itemId' => $id
        ));
    }

    public function changeAction()
    {
        $id = $this->getRequest()->getPost('id');
        $mapper = $this->getServiceLocator()->get('Admin\Model\ProductMapper');
        $product = new \Admin\Model\Product();
        $product->setId($id);

        if(!$mapper->get($product)){
            return new JsonModel(array(
                'code'=> 0,
                'messenger' => 'Chúng tôi không tìm thấy sản phẩm này'
            ));
        }
        if($product->getStatus() == \Admin\Model\Product::STATUS_ACTIVE){
            $product->setStatus(\Admin\Model\Product::STATUS_INACTIVE);
        }else{
            $product->setStatus(\Admin\Model\Product::STATUS_ACTIVE);
        }
        $mapper->save($product);

        return new JsonModel(array(
            'code'=> 1,
            'messenger' => 'Đã thay đổi',
            'status' => $product->getStatus()
        ));
    }

    public function changecAction()
    {
        $viewModel = new JsonModel();

        $id = $this->getRequest()->getPost('id');
        $mapper = $this->getServiceLocator()->get('Admin\Model\ProductcMapper');
        $category = new \Admin\Model\Productc();
        $category->setId($id);

        if(!$mapper->get($category)){
            return new JsonModel(array(
                'code'=> 0,
                'messenger' => 'Chúng tôi không tìm thấy sản phẩm này'
            ));
        }
        if(!$this->getRequest()->getPost('order')){
            if($category->getStatus() == \Admin\Model\Productc::STATUS_ACTIVE){
                $category->setStatus(\Admin\Model\Productc::STATUS_INACTIVE);
            }else{
                $category->setStatus(\Admin\Model\Productc::STATUS_ACTIVE);
            }
            $mapper->save($category);
            $viewModel->setVariable('code', 1);
            $viewModel->setVariable('messenger', 'Đã thay đổi');
            $viewModel->setVariable('status', $category->getStatus());
        }elseif($this->getRequest()->getPost('order')){
            $category->setUpdateTime(DateBase::getCurrentDateTime());
            $mapper->save($category);
            $viewModel->setVariable('code', 1);
            $viewModel->setVariable('messenger', 'Đã thay đổi');
        }
        return $viewModel;

    }

    public function orderAction()
    {
        $this->layout('layout/admin');
        $order = new \Admin\Model\Order();
        $orderMapper = $this->getServiceLocator()->get('Admin\Model\OrderMapper');
        $order->exchangeArray((array)$this->getRequest()->getQuery());
        $modelSt = new \Admin\Model\Store();
        $mapperSt = $this->getServiceLocator()->get('Admin\Model\StoreMapper');
        $u = $this->getServiceLocator()->get('User\Service\User');
//        $storeId = $this->getServiceLocator()->get('Store\Service\Store')->getStoreId();
        $storeId = $u->getStoreId();
        $options['isAdmin'] = $this->user()->isSuperAdmin();
        $fFilter = new \Admin\Form\ProductSearch($options);
        if(!$this->user()->isSuperAdmin()){
            $order->setStoreId($storeId);
        }else{
            $fFilter->setStoreIds($modelSt->toSelectBoxArray($mapperSt->fetchAll($modelSt)));
        }
        $fFilter->bind($order);
        $page = (int)$this->getRequest()->getQuery()->page ? : 1;
//        print_r($order);die;
        $results = $orderMapper->search($order, array($page,20));

        return new ViewModel(array(
            'fFilter' => $fFilter,
            'results' => $results
        ));
    }

    public function brandAction()
    {
        $this->layout('layout/admin');
        $u = $this->getServiceLocator()->get('User\Service\User');
        $storeId = $u->getStoreId();

        $brand = new \Admin\Model\Brand();
        $brandMapper = $this->getServiceLocator()->get('Admin\Model\BrandMapper');

        $store = new \Admin\Model\Store();

        $storeMapper = $this->getServiceLocator()->get('Admin\Model\StoreMapper');
        $brand->exchangeArray((array)$this->getRequest()->getQuery());

        $options['isAdmin'] = $this->user()->isSuperAdmin();
        $fFilter = new \Admin\Form\BrandSearch($options);
        if(!$this->user()->isSuperAdmin()){
            $brand->setStoreId($storeId);
        }else{
            $fFilter->setStoreIds($store->toSelectBoxArray($storeMapper->fetchAll($store)));
        }

        $fFilter->bind($brand);
//        print_r($brand);die;

        $page = (int)$this->getRequest()->getQuery()->page ? : 1;
        $results = $brandMapper->search($brand, array($page,20));

        return new ViewModel(array(
            'fFilter' => $fFilter,
            'results' => $results
        ));
    }

    public function addbrandAction()
    {
        $this->layout('layout/admin');
        $u = $this->getServiceLocator()->get('User\Service\User');
        $storeId = $u->getStoreId();
        $brand = new \Admin\Model\Brand();
        $brandMapper = $this->getServiceLocator()->get('Admin\Model\BrandMapper');
        $store = new \Admin\Model\Store();
        $storeMapper = $this->getServiceLocator()->get('Admin\Model\StoreMapper');
        $options['isAdmin'] = $this->user()->isSuperAdmin();
        $form = new \Admin\Form\Brand($options);

        if(!$this->user()->isSuperAdmin()){
            $brand->setStoreId($storeId);
        }else{
            $form->setStoreIds($store->toSelectBoxArray($storeMapper->fetchAll($store)));
        }
        if($this->getRequest()->isPost()){
            $form->setData(array_merge_recursive($this->getRequest()->getPost()->toArray(),$this->getRequest()->getFiles()->toArray()));
            if($form->isValid()){
                $data = $form->getData();
                $brand->exchangeArray($data);
                $brand->setUpdateDateTime(DateBase::getCurrentDateTime());
                $brand->setStatus(1);
                $brandMapper->save($brand);
                $mediaMapper = $this->getServiceLocator()->get('Admin\Model\MediaItemMapper');
                //Check exits cần một vòng for query select sẽ dài hơn where in id delete. Nên chỗ này xóa đi rồi saves
                $mediaItem = new \Admin\Model\MediaItem();
                $mediaItem->setItemId($brand->getId());
                $mediaItem->setType(\Admin\Model\MediaItem::FILE_BRAND);
                $mediaMapper->deleteType($mediaItem);

                $postImages = $this->getRequest()->getPost()['images'];

                if(isset($postImages) && $postImages != ''){
                    $imagesArray = explode(',', $postImages);
                    $c = 1;
                    foreach($imagesArray as $i){
                        if($i){
                            $mediaItem = new \Admin\Model\MediaItem();
                            $mediaItem->setType(\Admin\Model\MediaItem::FILE_BRAND);
                            $mediaItem->setItemId($brand->getId());
                            $mediaItem->setFileItem($i);
                            $mediaItem->setSort($c++);
                            $mediaMapper->save($mediaItem);
                        }
                    }
                }

                $this->redirect()->toUrl('/admin/product/brand');
            }
        }
        return new ViewModel(array(
            'form' => $form,
            'admin' => $options,
        ));
    }

    public function editbrandAction()
    {
        $this->layout('layout/admin');
        $id = $this->getEvent()->getRouteMatch()->getParam('id');

        $u = $this->getServiceLocator()->get('User\Service\User');
        $storeId = $u->getStoreId();

        $brand = new \Admin\Model\Brand();
        $brand->setId($id);
        $brandMapper = $this->getServiceLocator()->get('Admin\Model\BrandMapper');

        if(!$brandMapper->get($brand)){
            $this->redirect()->toUrl('/admin/product/brand');
        }

        $store = new \Admin\Model\Store();
        $storeMapper = $this->getServiceLocator()->get('Admin\Model\StoreMapper');
        $options['isAdmin'] = $this->user()->isSuperAdmin();
        $form = new \Admin\Form\Brand($options);

        $mediaItem = new \Admin\Model\MediaItem();
        $mediaItem->setItemId($id);
        $mediaItem->setType(\Admin\Model\MediaItem::FILE_BRAND);

        $mediaMapper = $this->getServiceLocator()->get('Admin\Model\MediaItemMapper');
        $m = $mediaMapper->fetchAll($mediaItem);
        $fI = [];
        if(isset($m)){
            foreach($m as $i){
                $fI[] = $i->getFileItem();
            }
        }
        $data = $brand->toFormValues();
        $data['images'] = implode(',', $fI);
        $form->setData($data);

        if(!$this->user()->isSuperAdmin()){
            $brand->setStoreId($storeId);
        }else{
            $form->setStoreIds($store->toSelectBoxArray($storeMapper->fetchAll($store)));
        }
        if($this->getRequest()->isPost()){
            $form->setData(array_merge_recursive($this->getRequest()->getPost()->toArray(),$this->getRequest()->getFiles()->toArray()));
            if($form->isValid()){
                $data = $form->getData();
                $brand->exchangeArray($data);
                $brand->setUpdateDateTime(DateBase::getCurrentDateTime());
                $brand->setStatus(1);

                $brandMapper->save($brand);
                $mediaMapper = $this->getServiceLocator()->get('Admin\Model\MediaItemMapper');
                //Check exits cần một vòng for query select sẽ dài hơn where in id delete. Nên chỗ này xóa đi rồi saves
                $mediaItem = new \Admin\Model\MediaItem();
                $mediaItem->setItemId($brand->getId());
                $mediaItem->setType(\Admin\Model\MediaItem::FILE_BRAND);
                $mediaMapper->deleteType($mediaItem);

                $postImages = $this->getRequest()->getPost()['images'];

                if(isset($postImages) && $postImages != ''){
                    $imagesArray = explode(',', $postImages);
                    $c = 1;
                    foreach($imagesArray as $i){
                        if($i){
                            $mediaItem = new \Admin\Model\MediaItem();
                            $mediaItem->setType(\Admin\Model\MediaItem::FILE_BRAND);
                            $mediaItem->setItemId($brand->getId());
                            $mediaItem->setFileItem($i);
                            $mediaItem->setSort($c++);
                            $mediaMapper->save($mediaItem);
                        }
                    }
                }

                $this->redirect()->toUrl('/admin/product/brand');
            }
        }
        return new ViewModel(array(
            'form' => $form,
            'admin' => $options,
        ));
    }

    public function changeBrandAction()
    {
        $id = $this->getRequest()->getPost()['id'];
        $id = isset($id) ? (string)(int)$id : false;

        if(!is_numeric($id)){
            return new JsonModel(array(
                'code'=> 0,
                'messenger' => 'Chúng tôi không tìm thấy bài viết này'
            ));
        }

        $mapper = $this->getServiceLocator()->get('Admin\Model\BrandMapper');
        $brand = new \Admin\Model\Brand();
        $brand->setId($id);

        if(!$mapper->get($brand)){
            return new JsonModel(array(
                'code' => 0,
                'messenger' => 'Chúng tôi không tìm thấy nội dung này'
            ));
        }

        if($brand->getStatus() == \Admin\Model\Product::STATUS_ACTIVE){
            $brand->setStatus(\Admin\Model\Product::STATUS_INACTIVE);
        }else{
            $brand->setStatus(\Admin\Model\Product::STATUS_ACTIVE);
        }
        $mapper->save($brand);

        return new JsonModel(array(
            'code' => 1,
            'messenger' => 'Đã thay đổi',
            'status' => $brand->getStatus(),
        ));

    }

    public function deletebrandAction()
    {
        $id = $this->getRequest()->getPost()['id'];
        $id = isset($id) ? (string)(int)$id : false;

        if(!is_numeric($id)){
            return new JsonModel(array(
                'code'=> 0,
                'messenger' => 'Chúng tôi không tìm thấy bài viết này'
            ));
        }

        $mapper = $this->getServiceLocator()->get('Admin\Model\BrandMapper');
        $brand = new \Admin\Model\Brand();
        $brand->setId($id);

        if(!$mapper->get($brand)){
            return new JsonModel(array(
                'code' => 0,
                'messenger' => 'Chúng tôi không tìm thấy nội dung này'
            ));
        }

        $mapper->delete($brand);
        $mediaMapper = $this->getServiceLocator()->get('Admin\Model\MediaItemMapper');
        //Check exits cần một vòng for query select sẽ dài hơn where in id delete. Nên chỗ này xóa đi rồi saves
        $mediaItem = new \Admin\Model\MediaItem();
        $mediaItem->setItemId($brand->getId());
        $mediaItem->setType(\Admin\Model\MediaItem::FILE_BRAND);
        $mediaMapper->deleteType($mediaItem);

        return new JsonModel(array(
            'code' => 1,
            'messenger' => 'Đã xóa'
        ));
    }

    public function importexcelAction()
    {
        $u = $this->getServiceLocator()->get('User\Service\User');
        $storeId = $u->getStoreId();

        $data = $this->getRequest()->getPost()['data'];
        $listArrs = json_decode($data);
        if ($listArrs){
            $listReference = [];
            foreach ($listArrs as $arr){
                $listReference = $arr;
            }
        }
        $productMapper = $this->getServiceLocator()->get('Admin\Model\ProductMapper');
        $categoryMapper = $this->getServiceLocator()->get('Admin\Model\ProductcMapper');
        $brandMapper = $this->getServiceLocator()->get('Admin\Model\BrandMapper');
        $mediaMapper = $this->getServiceLocator()->get('Admin\Model\MediaMapper');
        $mediaItemMapper = $this->getServiceLocator()->get('Admin\Model\MediaItemMapper');

        foreach ($listReference as $key => $val){
            $arrReference = $productMapper->convertToKey($val);

            /******** GetCategoryId ********/
            $categoryProduct = new \Admin\Model\Productc();
            $categoryProduct->setName($arrReference['categoryId']);
            $categoryId = '';
            if($categoryMapper->get($categoryProduct)){
                $categoryId = $categoryProduct->getId();
            }else{
                $categoryProduct = new \Admin\Model\Productc();
                $categoryProduct->setName($arrReference['categoryId']);
                $categoryProduct->setStoreId($storeId);
                $categoryProduct->setDescription('Mô tả '.$arrReference['categoryId']);
                $categoryProduct->setStatus(1);
                $categoryMapper->save($categoryProduct);
                $categoryId = $categoryProduct->getId();
            }

            /******** GetBrandId ********/
            $brand = new \Admin\Model\Brand();
            $brand->setName($arrReference['brandId']);
            $brandId = '';
            if($brandMapper->get($brand)){
                $brandId = $brand->getId();
            }else{
                $brand = new \Admin\Model\Brand();
                $brand->setName($arrReference['brandId']);
                $brand->setDescription('Mô tả '.$arrReference['brandId']);
                $brand->setStoreId($storeId);
                $brand->setStatus(1);
                $brand->setUpdateDateTime(DateBase::getCurrentDateTime());
                $brandMapper->save($brand);
                $brandId = $brand->getId();
            }

            $product = new \Admin\Model\Product();
            $product->exchangeArray((array)$arrReference);
            $product->setPriceOld((int)$arrReference['priceOld']);
            $product->setPrice((int)$arrReference['price']);
            $product->setCategoryId($categoryId);
            $product->setBrandId($brandId);
            $product->setStoreId($storeId);
            $product->setStatus(1);
//            print_r($product);die;

            $productMapper->save($product);

            if($arrReference['file']){
                $medias = explode(',',$arrReference['file']);
                $c = 1;
                foreach($medias as $m){
                    $media = new \Admin\Model\Media();
                    $media->setFileName(trim($m,' '));
                    if($mediaMapper->get($media)){
                        $mediaItem = new \Admin\Model\MediaItem();
                        $mediaItem->setType(\Admin\Model\MediaItem::FILE_PRODUCT);
                        $mediaItem->setItemId($product->getId());
                        $mediaItem->setFileItem($media->getId());
                        $mediaItem->setSort($c++);
                        $mediaItemMapper->save($mediaItem);
                    }
                }
            }
        }

        return new JsonModel(array(
            'code' => 1,
            'messenger' => 'Import thành công'
        ));

    }



}
















