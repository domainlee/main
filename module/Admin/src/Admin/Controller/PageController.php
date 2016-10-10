<?php
namespace Admin\Controller;
use Admin\Model\Media;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Home\Form\FormBase;
use Home\Model\DateBase;
use Home\Filter\HTMLPurifier;


class PageController extends AbstractActionController{

	public function indexAction()
    {
		$this->layout('layout/admin');
		$page = new \Admin\Model\Page();
		$pageMapper = $this->getServiceLocator()->get('Admin\Model\PageMapper');
		$store = new \Admin\Model\Store();
        $u = $this->getServiceLocator()->get('User\Service\User');
        $storeId = $u->getStoreId();

        $mapperSt = $this->getServiceLocator()->get('Admin\Model\StoreMapper');
        $page->exchangeArray((array)$this->getRequest()->getQuery());
        $options['isAdmin'] = $this->user()->isSuperAdmin();
        $fFilter = new \Admin\Form\PageSearch($options);

        if(!$this->user()->isSuperAdmin()){
            $page->setStoreId($storeId);
        }else{
            $fFilter->setStoreIds($store->toSelectBoxArray($mapperSt->fetchAll($store)));
        }

        $fFilter->bind($page);
		$paginator = (int)$this->getRequest()->getQuery()->page ? : 1;
		$results = $pageMapper->search($page, array($paginator,10));
		
		return new ViewModel(array(
			'fFilter' => $fFilter,
			'results' => $results
		));
	}

	public function addAction()
    {
		$this->layout('layout/admin');

        $u = $this->getServiceLocator()->get('User\Service\User');
        $storeId = $u->getStoreId();

        $page = new \Admin\Model\Page();
        if(!$this->user()->isSuperAdmin()){
            $page->setStoreId($storeId);
        }
        $pageMapper = $this->getServiceLocator()->get('Admin\Model\PageMapper');
        $category = $pageMapper->fetchAll($page);

		$store = new \Admin\Model\Store();
		$mapperSt = $this->getServiceLocator()->get('Admin\Model\StoreMapper');
		$form = new \Admin\Form\Page();
        $a = [];
        foreach($category as $c){
            foreach($category as $cc){
                if($c->getId() === $cc->getParentId()){
                    $c->addChild($cc);
                }
            }
            if(!$c->getParentId()){
                $a[] = $c;
            }
        }
        $b = [];
        if(count($a)){
            foreach($a as $c){
                $b[$c->getId()] = $c->getName();
                if($c->getChilds()){
                    foreach($c->getChilds() as $cc){
                        $b[$cc->getId()] = '-- '.$cc->getName();
                        if($cc->getChilds()){
                            foreach($cc->getChilds() as $ccc){
                                $b[$ccc->getId()] = '------- '.$ccc->getName();
                            }
                        }
                    }
                }
            }
        }
		$form->setCategoryIds($b);

        if(!$this->user()->isSuperAdmin()){
            $store->setId($storeId);
        }
        $form->setStoreIds($store->toSelectBoxArray($mapperSt->fetchAll($store)));

		if($this->getRequest()->isPost()){
			$form->setData(array_merge_recursive($this->getRequest()->getPost()->toArray(),$this->getRequest()->getFiles()->toArray()));
			if($form->isValid()){
                $data = $form->getData();
                $page->exchangeArray($data);
                $page->setUpdateDate(DateBase::getCurrentDateTime());
                $pageMapper->save($page);
                $this->redirect()->toUrl('/admin/page');
			}
		}
		return new ViewModel(array(
				'form' => $form
		));
	}

	public function editAction()
    {
        $this->layout('layout/admin');
        $id = $this->getEvent()->getRouteMatch()->getParam('id');

        $u = $this->getServiceLocator()->get('User\Service\User');
        $storeId = $u->getStoreId();

        $pageMapper = $this->getServiceLocator()->get('Admin\Model\PageMapper');

        $page = new \Admin\Model\Page();
        $page->setId($id);

        if(!$pageMapper->get($page)){
            $this->redirect()->toUrl('/admin/page');
        }

        if(!$this->user()->isSuperAdmin()){
            $page->setStoreId($storeId);
        }
        $page1 = new \Admin\Model\Page();
        $page1->setStoreId($storeId);
        $category = $pageMapper->fetchAll($page1);

        $store = new \Admin\Model\Store();
        $mapperSt = $this->getServiceLocator()->get('Admin\Model\StoreMapper');
        $form = new \Admin\Form\Page();
        $a = [];
        foreach($category as $c){
            foreach($category as $cc){
                if($c->getId() === $cc->getParentId()){
                    $c->addChild($cc);
                }
            }
            if(!$c->getParentId()){
                $a[] = $c;
            }
        }
        $b = [];
        if(count($a)){
            foreach($a as $c){
                $b[$c->getId()] = html_entity_decode($c->getName());
                if($c->getChilds()){
                    foreach($c->getChilds() as $cc){
                        $b[$cc->getId()] = '-- '.html_entity_decode($cc->getName());
                        if($cc->getChilds()){
                            foreach($cc->getChilds() as $ccc){
                                $b[$ccc->getId()] = '------- '.html_entity_decode($ccc->getName());
                            }
                        }
                    }
                }
            }
        }
        $form->setCategoryIds($b);

        if(!$this->user()->isSuperAdmin()){
            $store->setId($storeId);
        }
        $data = $page->toFormValues();
        $form->setStoreIds($store->toSelectBoxArray($mapperSt->fetchAll($store)));
        $form->setData($data);

        if($this->getRequest()->isPost()){
            $form->setData(array_merge_recursive($this->getRequest()->getPost()->toArray(),$this->getRequest()->getFiles()->toArray()));
            if($form->isValid()){
                $data = $form->getData();
                $page->exchangeArray($data);
                $page->setUpdateDate(DateBase::getCurrentDateTime());
                $pageMapper->save($page);
                $this->redirect()->toUrl('/admin/page');
            }
        }
        return new ViewModel(array(
            'form' => $form
        ));
	}


	public function changeAction(){

        $id = $this->getRequest()->getPost()['id'];
		$mapper = $this->getServiceLocator()->get('Admin\Model\PageMapper');
        $page = new \Admin\Model\Page();
        $page->setId($id);

        if(!$mapper->get($page)){
            return new JsonModel(array(
                'code'=> 0,
                'messenger' => 'Chúng tôi không tìm thấy bài viết này'
            ));
        }
		
		if(($page->getStatus()) == \Admin\Model\Page::STATUS_ACTIVE){
            $page->setStatus(\Admin\Model\Page::STATUS_INACTIVE);
		}
		else{
            $page->setStatus(\Admin\Model\Page::STATUS_ACTIVE);
		}
		$mapper->save($page);
        return new JsonModel(array(
            'code'=> 1,
            'messenger' => 'Đã thay đổi',
            'status' => $page->getStatus()
        ));
	}

	public function deleteAction(){

        $id = $this->getRequest()->getPost()['id'];
        if(!is_numeric($id)){
            return new JsonModel(array(
                'code'=> 0,
                'messenger' => 'Chúng tôi không tìm thấy bài viết này'
            ));
        }

        $mapper = $this->getServiceLocator()->get('Admin\Model\PageMapper');
        $page = new \Admin\Model\Page();
        $page->setId($id);

        if(!$mapper->get($page)){
            return new JsonModel(array(
                'code' => 0,
                'messenger' => 'Chúng tôi không tìm thấy bài viết này'
            ));
        }

        $mapper->delete($page);

        return new JsonModel(array(
			'code' => 1,
            'messenger' => 'Đã xóa'
		));
	}


}






















