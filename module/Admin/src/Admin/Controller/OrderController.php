<?php
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
class OrderController extends AbstractActionController{
	
	public function indexAction(){
		$this->layout('layout/admin');
		$model = new \Admin\Model\Order();
		$mapper = $this->getServiceLocator()->get('Admin\Model\OrderMapper');
		$model->exchangeArray((array)$this->getRequest()->getQuery());
		$page = (int)$this->getRequest()->getQuery()->page ?: 1;
		$results = $mapper->search($model, array($page, 30));
		return new ViewModel(array(
			'pages'=>$page,
			'results'=>$results,
		));
	}
	public function addAction(){
		
	}
	public function editAction(){
		
	}
	public function deleteAction(){
		
	}
	public function changeStatusAction(){
		$this->layout('layout/admin');
		$request = $this->getRequest();
		$id = $this->getEvent()->getRouteMatch()->getParam('id');
		$value = $request->getPost('value');
		$mapper = $this->getServiceLocator()->get('Admin\Model\OrderMapper');
		$model = $mapper->getId($id);
		$model->setStatus($value);
		$mapper->save($model);
		if($model->getStatus()== \Admin\Model\Order::STATUS_COMPLATE){
// 			$modelOPro = new \Admin\Model\OrderProduct();
//  			$mapperOPro = $this->getServiceLocator()->get('Admin\Model\OrderProductMapper');
 			$modelPro = new \Admin\Model\Product();
// 			//$qtt = ((int)$modelPro->getQuantity() - $modelOPro->getQuantity());
			$model = new \Admin\Model\Order();
// 			$mapper = $this->getServiceLocator()->get('Admin\Model\OrderMapper');
 			$qtt = $model->getTotalMoney();
 			echo $qtt;
 			$modelPro->setQuantity($qtt);
// 			$modelOPro->setQuantity(10);
			$mapper->updateQtt($modelPro);
			
			return new JsonModel(array(
					'code'=>1
			));
		}
		return new JsonModel(array(
			'code'=>1
		));
		
	}
}



















