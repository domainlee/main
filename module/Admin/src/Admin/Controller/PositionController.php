<?php
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;


class PositionController extends AbstractActionController{
	public function indexAction(){
		$this->layout('layout\admin');
		$model = new \Admin\Model\Position();
		$mapper = $this->getServiceLocator()->get('Admin\Model\PositionMapper');
		$modelSt = new \Admin\Model\Store();
		$mapperSt = $this->getServiceLocator()->get('Admin\Model\StoreMapper');
		$model->exchangeArray((array)$this->getRequest()->getQuery());
		$fFilter = new \Admin\Form\ProductcSearch();
		$fFilter->setStoreIds($modelSt->toSelectBoxArray($mapperSt->fetchAll($modelSt)));
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
		$model = new \Admin\Model\Position();
		$modelSt = new \Admin\Model\Store();
		$mapperSt = $this->getServiceLocator()->get('Admin\Model\StoreMapper');
		$fFilter = $this->getServiceLocator()->get('Admin\Form\PositionFilter');
		$form = $this->getServiceLocator()->get('Admin\Form\Position');
		
		$form->setInputFilter($fFilter);
		$form->setStoreIds($modelSt->toSelectBoxArray($mapperSt->fetchAll($modelSt)));
		$form->bind($model);
		if($this->getRequest()->isPost()){
			$form->setData($this->getRequest()->getPost());
			if($form->isValid()){
				$model->setId(null);
				$mapper = $this->getServiceLocator()->get('Admin\Model\PositionMapper');
				$mapper->save($model);
				$this->redirect()->toUrl('/admin/position');
			}
		}
		return new ViewModel(array(
			'form'=>$form
		));
	}
	public function editAction(){
		$this->layout('layout/admin');
		$id = $this->getEvent()->getRouteMatch()->getParam('id');
		$mapper = $this->getServiceLocator()->get('Admin\Model\PositionMapper');
		$model = $mapper->getId($id);
		$modelSt = new \Admin\Model\Store();
		$mapperSt = $this->getServiceLocator()->get('Admin\Model\StoreMapper');
		$fFilter = $this->getServiceLocator()->get('Admin\Form\PositionFilter');
		//$fFilter->setExcludedId($id);
		$form = $this->getServiceLocator()->get('Admin\Form\Position');
		$form->setInputFilter($fFilter);
		$form->setStoreIds($modelSt->toSelectBoxArray($mapperSt->fetchAll($modelSt)));
		$form->bind($model);
		if($this->getRequest()->isPost()){
			$form->setData($this->getRequest()->getPost());
			if($form->isValid()){
// 				echo '<pre>';
// 				print_r($model);die();
// 				echo '</pre>';
				$mapper = $this->getServiceLocator()->get('Admin\Model\PositionMapper');
				$mapper->save($model);
				$this->redirect()->toUrl('/admin/position');
			}
		}
		return new ViewModel(array(
			'form'=>$form
		));
	}
	public function deleteAction(){
		$this->layout('layout/admin');
		$id = $this->getEvent()->getRouteMatch()->getParam('id');
		$mapper = $this->getServiceLocator()->get('Admin\Model\PositionMapper');
		$model = $mapper->getId($id);
		
		$mapper->delete($model);
		return new JsonModel(array(
				'code'=>1
		));
		}
	public function changeactiveAction(){
		$this->layout('layout/admin');
		$id = $this->getEvent()->getRouteMatch()->getParam('id');
		$mapper = $this->getServiceLocator()->get('Admin\Model\PositionMapper');
		$model = $mapper->getId($id);
		if(($model->getStatus()) == \Admin\Model\Position::STATUS_ACTIVE){
			$model->setStatus(\Admin\Model\Position::STATUS_INACTIVE);
		}
		else{
			$model->setStatus(\Admin\Model\Position::STATUS_ACTIVE);
		}
		$mapper->save($model);
		$this->redirect()->toUrl('/admin/position');
	}
}





















