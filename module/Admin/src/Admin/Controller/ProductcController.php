<?php
namespace Admin\Controller;

use \Zend\Mvc\Controller\AbstractActionController;
use \Zend\View\Model\ViewModel;
use \Zend\View\Model\JsonModel;
use Zend\Form\Form;

class ProductcController extends AbstractActionController{
	
	public function indexAction(){
		$this->layout('layout\admin');
		$model = new \Admin\Model\Productc();
		$mapper = $this->getServiceLocator()->get('Admin\Model\ProductcMapper');
		$modelSt = new \Admin\Model\Store();
		$mapperSt = $this->getServiceLocator()->get('Admin\Model\StoreMapper');
		$modelCate = new \Admin\Model\Productc();
		$mapperCate = $this->getServiceLocator()->get('Admin\Model\ProductcMapper');
		$category = $mapperCate->fetchAll($modelCate);
		$abc = $model->toSelectBoxArray($category,\Admin\Model\Product::SELECT_MODE_ALL);
		$model->exchangeArray((array)$this->getRequest()->getQuery());
		$fFilter = new \Admin\Form\ProductcSearch();
		$fFilter->setStoreIds($modelSt->toSelectBoxArray($mapperSt->fetchAll($modelSt)));
		$fFilter->bind($model);
		$page = (int)$this->getRequest()->getQuery()->page ? : 1;
		$results = $mapper->search($model, array($page,10));
		
		return new ViewModel(array(
			'results'=>$results,
			'fFilter'=>$fFilter,
			'abc'=>$abc
		));
	}
	public function addAction(){
//		$this->layout('layout/admin');
//		$model = new \Admin\Model\Productc();
//		$mapper = $this->getServiceLocator()->get('Admin\Model\ProductcMapper');
//		$parents = $mapper->fetchAll($model);
//		$modelSt = new \Admin\Model\Store();
//		$mapperSt = $this->getServiceLocator()->get('Admin\Model\StoreMapper');
//		$fFilter = $this->getServiceLocator()->get('Admin\Form\ProductcFilter');
//		$form = $this->getServiceLocator()->get('Admin\Form\Productc');
//        $form = new \Admin\Form\Productc($this->getServiceLocator());
//        $form->setInputFilter($fFilter);
//		$form->setParentIds($model->toSelectboxArray($parents,\Admin\Model\Productc::SELECT_MODE_ALL));
//        $u = $this->getServiceLocator()->get('User\Service\User');
//        if($this->user()->isAdmin()){
//            $form->setStoreIds($modelSt->toSelectBoxArray($mapperSt->fetchAll($modelSt)));
//        }else{
//            $model->setStoreId($u->getUser()->getStoreId());
//        }
//		$form->bind($model);
//		if($this->getRequest()->isPost()){
//			$files = $this->getRequest()->getFiles();
//			$form->setData($this->getRequest()->getPost());
//			if($form->isValid()) {
//				$model->setId(null);
//				$model->setCreatedById($this->user()->getId());
//				$mapper = $this->getServiceLocator()->get('Admin\Model\ProductcMapper');
//
//				$file = $files ['image_upload'];
//				$isSaved = false;
//
//                if($file ['name']) {
//					// save image
//					$targetFolder = \Base\Model\Uri::getSavePath($model);
//					if(! file_exists($targetFolder)) {
//						mkdir($targetFolder, 0777, true);
//					}
//					$extension = \Base\Model\Ultility::getFileExtension($file ['name']);
//					$newName = md5(\Base\Model\RDate::getCurrentDatetime()) . '.' . $extension;
//					$fileFilter = new \Zend\Filter\File\Rename(array(
//							'target' => $targetFolder . '/' . $newName,
//							'overwrite' => true
//					));
//					if(($rs = $fileFilter->filter($file)) != false) {
//						$model->setImage($newName);
//
//                        $mapper->save($model);
//						$isSaved = true;
//						$this->redirect()->toUrl('/admin/productc');
//					} else {
//						$form->setMessages(array(
//								'image_upload' => array(
//										'Upload ảnh thất bại'
//								)
//						));
//					}
//				} else {
//                    $mapper->save($model);
//					$this->redirect()->toUrl('/admin/productc');
//					$isSaved = true;
//				}
//			}
//		}
//		return new ViewModel(array(
//			'form' => $form
//		));
	}
	public function editAction(){
		$this->layout('layout/admin');
		if(!($id = $this->getEvent()->getRouteMatch()->getParam('id'))){
			$this->redirect()->toUrl('/admin/productc');
		}
		$mapper = $this->getServiceLocator()->get('Admin\Model\ProductcMapper');
		if(($model = $mapper->getId($id)) == null){
			$this->redirect()->toUrl('/admin/productc');
		}
		$modparent = new \Admin\Model\Productc();
		$oldImage = $model->getImage ();
		$parents = $mapper->fetchAll($modparent);
		$modelSt = new \Admin\Model\Store();
		$mapperSt = $this->getServiceLocator()->get('Admin\Model\StoreMapper');
		
		$fFilter = $this->getServiceLocator()->get('Admin\Form\ProductcFilter');
		$fFilter->setExcludedId($id);
		$form = $this->getServiceLocator()->get('Admin\Form\Productc');
		$form->setInputFilter($fFilter);
		$form->setParentIds($model->toSelectboxArray($parents,\Admin\Model\Productc::SELECT_MODE_ALL));
		$form->setStoreIds($modelSt->toSelectBoxArray($mapperSt->fetchAll($modelSt)));
		$form->bind($model);
		
		if($this->getRequest()->isPost()){
			$files = $this->getRequest()->getFiles();
			$form->setData($this->getRequest()->getPost());
			if($form->isValid()){
				//$model->exchangeArray((array) $this->getRequest()->getPost());
				$mapper = $this->getServiceLocator()->get('Admin\Model\ProductcMapper');
					
				$file = $files ['image_upload'];
				$isSaved = false;
				if($file ['name']) {
					// save image
					$targetFolder = \Base\Model\Uri::getSavePath($model);
					if(! file_exists($targetFolder)) {
						mkdir($targetFolder, 0777, true);
					}
					$extension = \Base\Model\Ultility::getFileExtension($file ['name']);
					$newName = md5(\Base\Model\RDate::getCurrentDatetime()) . '.' . $extension;
					$fileFilter = new \Zend\Filter\File\Rename(array(
							'target' => $targetFolder . '/' . $newName,
							'overwrite' => true
					));
					if (($rs = $fileFilter->filter ( $file )) != false) {
						$model->setImage ( $newName );
						if ($oldImage && $oldImage != $model->getImage ()) {
							@unlink ( \Base\Model\Uri::getSavePath ( $model ) . "/" . $oldImage );
						}
						$mapper->save ( $model );
						$isSaved = true;
						$this->redirect()->toUrl('/admin/productc');
					} else {
						$form->setMessages(array(
								'image_upload' => array(
										'Upload ảnh thất bại'
								)
						));
					}
				} else {
					$mapper->save($model);
					$this->redirect()->toUrl('/admin/productc');
					$isSaved = true;
				}
			}
		}
		return new ViewModel(array(
				'form' =>$form
		));
		
	}
	public function uploadAction(){
		if(!($id = $this->getEvent()->getRouteMatch()->getParam('id'))){
			$this->redirect()->toUrl('/admin/productc');
		}
		$mapper = $this->getServiceLocator()->get('Admin\Model\ProductcMapper');
		if(($model = $mapper->getId($id)) == null){
			$this->redirect()->toUrl('/admin/productc');
		}
	//	$oldImage = $model->getImage ();
		
		$fFilter = $this->getServiceLocator()->get('Admin\Form\UploadFilter');
		$form = new \Admin\Form\Upload();
		$form->setInputFilter($fFilter);
		$form->bind($model);
		if($this->getRequest()->isPost()){
			$files = $this->getRequest()->getFiles();
			$form->setData($this->getRequest()->getPost());
			if($form->isValid()){
				$mapper = $this->getServiceLocator()->get('Admin\Model\ProductcMapper');
				$file = $files ['image_upload'];
				$isSaved = false;
				if($file ['name']) {
					// save image
					$targetFolder = \Base\Model\Uri::getSavePath($model);
					if(! file_exists($targetFolder)) {
						mkdir($targetFolder, 0777, true);
					}
					$extension = \Base\Model\Ultility::getFileExtension($file ['name']);
					$newName = md5(\Base\Model\RDate::getCurrentDatetime()) . '.' . $extension;
					$fileFilter = new \Zend\Filter\File\Rename(array(
							'target' => $targetFolder . '/' . $newName,
							'overwrite' => true
					));
					if(($rs = $fileFilter->filter($file)) != false) {
						$model->setImage($newName);
						$mapper->save($model);
						$isSaved = true;
						$this->redirect()->toUrl('/admin/productc');
					} else {
						$form->setMessages(array(
								'image_upload' => array(
										'Upload ảnh thất bại'
								)
						));
					}
				}else{
					$mapper->save($model);
					$isSaved = true;
					$this->redirect()->toUrl('/admin/productc');
				}
			}else{
				echo 'thất bại';
			}
		}
		
	//	$this->redirect()->toUrl('/admin');
		return new JsonModel(array(
				'code' => 1
		) );
	}
	public function deleteAction()
	{
		$id = (int) $this->getEvent()->getRouteMatch()->getParam('id');
			
		/* @var $mapper \Product\Model\AproductcMapper */
		$mapper = $this->getServiceLocator()->get('Admin\Model\ProductcMapper');
		/* @var $model Product\Model\Aproductc */
		$model = $mapper->getId($id);
		//product
		$proModel = new \Admin\Model\Product();
		$proModel->setCategoryId($id);
		$proMapper = $this->getServiceLocator()->get('Admin\Model\ProductMapper');
		$results = $proMapper->fetchAll($proModel);
		if(count($results)){
			return new JsonModel(array(
					'code'=>0,
					'message'=>"Thể loại sản phẩm này đã có danh mục con không thể xóa được",
			));
		}
	
		if($model->getImage()){
			@unlink(\Base\Model\Uri::getSavePath($model)."/".$model->getImage());
		}
	
		$mapper->delete($model);
		return new JsonModel(array(
				'code' => 1
		) );
	
	}
	public function changeactiveAction(){
		$this->layout('layout/admin');
		$id = $this->getEvent()->getRouteMatch()->getParam('id');
		$mapper = $this->getServiceLocator()->get('Admin/Model/ProductcMapper');
		$model = $mapper->getId($id);
		
		if($model->getStatus() == \Admin\Model\Productc::STATUS_ACTIVE){
			$model->setStatus(\Admin\Model\Productc::STATUS_INACTIVE);
		}
		else {
			$model->setStatus(\Admin\Model\Productc::STATUS_ACTIVE);
		}
		
		$mapper->save($model);
		$this->redirect()->toUrl('/admin/productc');
	}
}



































