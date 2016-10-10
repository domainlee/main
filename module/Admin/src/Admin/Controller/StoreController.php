<?php
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class StoreController extends AbstractActionController{
	public function indexAction(){
		$this->layout('layout/admin');
		$model = new \Admin\Model\Store();
		$mapper = $this->getServiceLocator()->get('Admin\Model\StoreMapper');
		$model->exchangeArray((array)$this->getRequest()->getQuery());
		$fFilter = new \Admin\Form\StoreSearch();
		$fFilter->bind($model);
		$page = (int)$this->getRequest()->getQuery()->page ? : 1;
		$results = $mapper->search($model, array($page,10));
		return new ViewModel(array(
			'fFilter'=>$fFilter,
			'results'=>$results
		));
	}
	public function addAction(){
		$this->layout('layout/admin');
		$model = new \Admin\Model\Store();
		$mapper = $this->getServiceLocator()->get('Admin\Model\StoreMapper'); 
		$parents = $mapper->fetchAll($model);
		$form = new \Admin\Form\Store();
		$fFilter = $this->getServiceLocator()->get('Admin\Form\StoreFilter');
		$form->setInputFilter($fFilter);
		$form->setParentIds($model->toSelectBoxArray($parents,\Admin\Model\Store::SELECT_MODE_ALL));
		$form->bind($model);
		
		if($this->getRequest()->isPost()){
			$form->setData($this->getRequest()->getPost());
			$files = $this->getRequest()->getFiles();
			if($form->isValid()){
				$model->setId(null);
				$mapper = $this->getServiceLocator()->get('Admin\Model\StoreMapper');
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
						$this->redirect()->toUrl('/admin/store');
					} else {
						$form->setMessages(array(
								'image_upload' => array(
										'Upload ảnh thất bại'
								)
						));
					}
				} else {
					$mapper->save($model);
					$this->redirect()->toUrl('/admin/store');
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
		$modelParent = new \Admin\Model\Store();
		$mapper = $this->getServiceLocator()->get('Admin\Model\StoreMapper');
		$model = $mapper->getId($id);
		$parents = $mapper->fetchAll($model);
		$oldImage = $model->getLogo();
		
		$form = new \Admin\Form\Store();
		$fFilter = $this->getServiceLocator()->get('Admin\Form\StoreFilter');
		$fFilter->setExcludedId($id);
		$form->setInputFilter($fFilter);
		$form->setParentIds($modelParent->toSelectboxArray($parents,\Admin\Model\Store::SELECT_MODE_ALL));
		$form->bind($model);
		if($this->getRequest()->isPost()){
			$form->setData($this->getRequest()->getPost());
			$files = $this->getRequest()->getFiles();
			if($form->isValid()){
				$mapper = $this->getServiceLocator()->get('Admin\Model\StoreMapper');
					
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
						$model->setLogo($newName);
						if ($oldImage && $oldImage != $model->setLogo()) {
							@unlink ( \Base\Model\Uri::getSavePath ( $model ) . "/" . $oldImage );
						}
						$mapper->save ( $model );
						$isSaved = true;
						$this->redirect()->toUrl('/admin/store');
					} else {
						$form->setMessages(array(
								'image_upload' => array(
										'Upload ảnh thất bại'
								)
						));
					}
				} else {
					$mapper->save($model);
					$this->redirect()->toUrl('/admin/store');
					$isSaved = true;
				}
			}
		}
		return new ViewModel(array(
				'form' => $form
		));
	}
}




















