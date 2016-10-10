<?php
namespace Admin\Controller;

use \Zend\Mvc\Controller\AbstractActionController;
use \Zend\View\Model\ViewModel;
use \Zend\View\Model\JsonModel;

class BannerController extends AbstractActionController{
	public function indexAction(){
		$this->layout('layout/admin');
		$model = new \Admin\Model\Banner();
		$modelSt = new \Admin\Model\Store();
		$mapperSt = $this->getServiceLocator()->get('Admin\Model\StoreMapper');
		$mapper = $this->getServiceLocator()->get('Admin\Model\BannerMapper');
		$model->exchangeArray((array)$this->getRequest()->getQuery());
		$fFilter = new \Admin\Form\BannerSearch();
		$fFilter->setStoreIds($modelSt->toSelectBoxArray($mapperSt->fetchAll($modelSt)));
		$fFilter->bind($model);
		$pages = $this->getRequest()->getQuery()->page ?: 1;
		$results = $mapper->search($model, array($pages,10));
		return new ViewModel(array(
			'fFilter'=>$fFilter,
			'results'=>$results
		));
	}
public function addAction(){
		$this->layout('layout/admin');
		$model = new \Admin\Model\Banner();
		
		$modelPo = new \Admin\Model\Position();
		$mapperPo = $this->getServiceLocator()->get('Admin\Model\PositionMapper');
		$position = $mapperPo->fetchAll($modelPo);
		$modelSt = new \Admin\Model\Store();
		$mapperSt = $this->getServiceLocator()->get('Admin\Model\StoreMapper');
		
		$fFilter = new \Admin\Form\BannerFilter();
		$form = new \Admin\Form\Banner();
		$form->setInputFilter($fFilter);
		$form->setPositionIds($model->toSelectBoxArray($position,\Admin\Model\Banner::SELECT_MODE_ALL));
		$form->setStoreIds($modelSt->toSelectBoxArray($mapperSt->fetchAll($modelSt)));
		$form->bind($model);
		if($this->getRequest()->isPost()){
			$form->setData($this->getRequest()->getPost());
			$files = $this->getRequest()->getFiles();
			if($form->isValid()){
				$model->setId(null);
				$mapper = $this->getServiceLocator()->get('Admin\Model\BannerMapper');
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
						$this->redirect()->toUrl('/admin/banner');
					} else {
						$form->setMessages(array(
								'image_upload' => array(
										'Upload ảnh thất bại'
								)
						));
					}
				} else {
					$mapper->save($model);
					$this->redirect()->toUrl('/admin/banner');
					$isSaved = true;
				}
			}
		}
		return new ViewModel(array(
				'form' => $form
		));
	}
	public function editAction(){
		$this->layout('layout/admin');
		$id = $this->getEvent()->getRouteMatch()->getParam('id');
		$mapper = $this->getServiceLocator()->get('Admin\Model\BannerMapper');
		$model = $mapper->getId($id);
		$modelSt = new \Admin\Model\Store();
		$mapperSt = $this->getServiceLocator()->get('Admin\Model\StoreMapper');
		
		$modelCate = new \Admin\Model\Position();
		$mapperCate = $this->getServiceLocator()->get('Admin\Model\PositionMapper');
		$category = $mapperCate->fetchAll($modelCate);
		$oldImage = $model->getImage ();
		
		$fFilter = new \Admin\Form\BannerFilter();
		$fFilter->setExcludedId($id);
		$form = new \Admin\Form\Article();
		$form->setInputFilter($fFilter);
		$form->setCategoryIds($modelCate->toSelectBoxArray($category));
		$form->setStoreIds($modelSt->toSelectBoxArray($mapperSt->fetchAll($modelSt)));
		$form->bind($model);
		
		if($this->getRequest()->isPost()){
			$form->setData($this->getRequest()->getPost());
			$files = $this->getRequest()->getFiles();
			if($form->isValid()){
				
			$mapper = $this->getServiceLocator()->get('Admin\Model\BannerMapper');
					
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
						$this->redirect()->toUrl('/admin/banner');
					} else {
						$form->setMessages(array(
								'image_upload' => array(
										'Upload ảnh thất bại'
								)
						));
					}
				}else{
					$mapper->save($model);
					$this->redirect()->toUrl('/admin/banner');
					$isSaved = true;
				}
			}
		}
		return new ViewModel(array(
			'form' => $form
		));
		
	}
	public function changeactiveAction(){
		$this->layout('layout/admin');
		$id = $this->getEvent()->getRouteMatch()->getParam('id');
		$mapper = $this->getServiceLocator()->get('Admin\Model\ArticleMapper');
		$model = $mapper->getId($id);
		
		if(($model->getStatus()) == \Admin\Model\Banner::STATUS_ACTIVE){
			$model->setStatus(\Admin\Model\Banner::STATUS_INACTIVE);
		}
		else{
			$model->setStatus(\Admin\Model\Banner::STATUS_ACTIVE);
		}
		$mapper->save($model);
		$this->redirect()->toUrl('/admin/banner');
	}
	public function deleteAction(){
		$this->layout('layout/admin');
		$id = $this->getEvent()->getRouteMatch()->getParam('id');
		$mapper = $this->getServiceLocator()->get('Admin\Model\BannerMapper');
		$model = $mapper->getId($id);
		
		if($model->getImage()){
			@unlink(\Base\Model\Uri::getSavePath($model)."/".$model->getImage());
		}
		$mapper->delete($model);
		return new JsonModel(array(
			'code'=>1
		));
	}
}

