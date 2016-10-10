<?php
namespace Admin\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

class ArticlecController extends AbstractActionController{

	public function indexAction(){
		$this->layout('layout/admin');
		$model = new \Admin\Model\Articlec();
		$mapper = $this->getServiceLocator()->get('Admin\Model\ArticlecMapper');
		$modelSt = new \Admin\Model\Store();
		$mapperSt = $this->getServiceLocator()->get('Admin\Model\StoreMapper');
		$model->exchangeArray((array)$this->getRequest()->getQuery());
		$fFilter = new \Admin\Form\ArticlecSearch();
		$pages = $this->getRequest()->getQuery()->pages ?: 1;
		$fFilter->setStoreIds($modelSt->toSelectBoxArray($mapperSt->fetchAll($modelSt),\Admin\Model\Articlec::SELECT_MODE_ALL));
		$fFilter->bind($model);
		$results = $mapper->search($model, array($pages,10));
		
		return new ViewModel(array(
			'fFilter'=>$fFilter,
			'results'=>$results
		));
		
	}

	public function addAction(){
		$this->layout('layout/admin');
		$model = new \Admin\Model\Articlec();
		$mapper = $this->getServiceLocator()->get('Admin\Model\ArticlecMapper');
		$parents = $mapper->fetchAll($model);
		$modelSt = new \Admin\Model\Store();
		$mapperSt = $this->getServiceLocator()->get('Admin\Model\StoreMapper');
		
		$fFilter = new \Admin\Form\ArticlecFilter();
		$form = new \Admin\Form\Articlec();
		$form->setInputFilter($fFilter);
		$form->setStoreIds($modelSt->toSelectBoxArray($mapperSt->fetchAll($modelSt)));
		$form->setParentIds($model->toSelectBoxArray($parents,\Admin\Model\Articlec::SELECT_MODE_ALL));
		$form->bind($model);
		
		if($this->getRequest()->isPost()){
			$form->setData($this->getRequest()->getPost());
			$files = $this->getRequest()->getPost();
		if($form->isValid()) {
				$model->setId(null);
				$mapper = $this->getServiceLocator()->get('Admin\Model\ArticlecMapper');
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
						$this->redirect()->toUrl('/admin/articlec');
					} else {
						$form->setMessages(array(
								'image_upload' => array(
										'Upload ảnh thất bại'
								)
						));
					}
				} else {
					$mapper->save($model);
					$this->redirect()->toUrl('/admin/articlec');
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
		$mapper = $this->getServiceLocator()->get('Admin\Model\ArticlecMapper');
		$model = $mapper->getId($id);
		$modelSt = new \Admin\Model\Store();
		$mapperSt = $this->getServiceLocator()->get('Admin\Model\StoreMapper');
		$modelParent = new \Admin\Model\Articlec();
		$parents = $mapper->fetchAll($modelParent);
		$oldImage = $model->getImage();
		
		$fFilter = $this->getServiceLocator()->get('Admin\Form\ArticlecFilter');
		$fFilter->setExcludedId($id);
		$form = new \Admin\Form\Articlec();
		$form->setInputFilter($fFilter);
		$form->setParentIds($model->toSelectBoxArray($parents,\Admin\Model\Articlec::SELECT_MODE_ALL));
		$form->setStoreIds($modelSt->toSelectBoxArray($mapperSt->fetchAll($modelSt)));
		$form->bind($model);
		if($this->getRequest()->isPost()){
			$form->setData($this->getRequest()->getPost());
			$files = $this->getRequest()->getFiles();
		if($form->isValid()){
				$mapper = $this->getServiceLocator()->get('Admin\Model\ArticlecMapper');
			
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
						$this->redirect()->toUrl('/admin/articlec');
					} else {
						$form->setMessages(array(
								'image_upload' => array(
										'Upload ảnh thất bại'
								)
						));
					}
				} else {
					$mapper->save($model);
					$this->redirect()->toUrl('/admin/articlec');
					$isSaved = true;
				}
			}
			}
			return new ViewModel(array(
					'form' => $form
			));
	}
	public function deleteAction(){
		$id = $this->getEvent()->getRouteMatch()->getParam('id');
		$mapper = $this->getServiceLocator()->get('Admin\Model\ArticlecMapper');
		$model = $mapper->getId($id);
		
		if($model->getImage()){
			@unlink(\Base\Model\Uri::getSavePath($model)."/".$model->getImage());
		}
		$mapper->delete($model);
		return new JsonModel(array(
			'code'=>1
		));
	}
	public function changeactiveAction(){
		$this->layout('layout\admin');
		$id = $this->getEvent()->getRouteMatch()->getParam('id');
		$mapper = $this->getServiceLocator()->get('Admin\Model\ArticlecMapper');
		$model = $mapper->getId($id);
		if($model->getStatus() == \Admin\Model\Articlec::STATUS_ACTIVE){
			$model->setStatus(\Admin\Model\Articlec::STATUS_INACTIVE);
		}
		else{
			$model->setStatus(\Admin\Model\Articlec::STATUS_ACTIVE);
		}
		$mapper->save($model);
		$this->redirect()->toUrl('/admin/articlec');
	}
}








