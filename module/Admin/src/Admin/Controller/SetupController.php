<?php
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Home\Model\DateBase;


class SetupController extends AbstractActionController{

	public function indexAction(){
		$this->layout('layout/admin');
        $id = $this->getRequest()->getPost()['value'];
        print_r($id);

    }

	public function addAction(){

	}

	public function editAction()
    {

	}

    public function menuAction()
    {
        $this->layout('layout/admin');
        $u = $this->getServiceLocator()->get('User\Service\User');
        $storeId = $u->getStoreId();

        $menu = new \Admin\Model\Menu();
        $menuMapper = $this->getServiceLocator()->get('Admin\Model\MenuMapper');

        $store = new \Admin\Model\Store();

        $storeMapper = $this->getServiceLocator()->get('Admin\Model\StoreMapper');
        $menu->exchangeArray((array)$this->getRequest()->getQuery());

        $options['isAdmin'] = $this->user()->isSuperAdmin();
        $fFilter = new \Admin\Form\ArticleSearch($options);
        if(!$this->user()->isSuperAdmin()){
            $menu->setStoreId($storeId);
        }else{
            $fFilter->setStoreIds($store->toSelectBoxArray($storeMapper->fetchAll($store)));
        }

        $fFilter->bind($menu);
        $results = $menuMapper->fetchAll($menu);

        return new ViewModel(array(
            'fFilter' => $fFilter,
            'results' => $results
        ));
    }

    public function addmenuAction()
    {
        $this->layout('layout/admin');

        $u = $this->getServiceLocator()->get('User\Service\User');
        $storeId = $u->getStoreId();

        $menu = new \Admin\Model\Menu();
        if(!$this->user()->isSuperAdmin()){
            $menu->setStoreId($storeId);
        }
        $menuMapper = $this->getServiceLocator()->get('Admin\Model\MenuMapper');
        $a = $menuMapper->fetchAll($menu);
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
        $categoryP = new \Product\Model\Category();
        $categoryP->setStoreId($storeId);
        $categoryProductMapper = $this->getServiceLocator()->get('Product\Model\CategoryMapper');
        $categoryProduct = $categoryProductMapper->fetchTree($categoryP);
        $product = [];
        if(count($categoryProduct)){
            foreach($categoryProduct as $c){
                $product[$c->getViewLink()] = html_entity_decode($c->getName());
                if($c->getChilds()){
                    foreach($c->getChilds() as $cc){
                        $product[$cc->getViewLink()] = '-- '.html_entity_decode($cc->getName());
                        if($cc->getChilds()){
                            foreach($cc->getChilds() as $ccc){
                                $product[$ccc->getViewLink()] = '------- '.html_entity_decode($ccc->getName());
                            }
                        }
                    }
                }
            }
        }

        $categoryN = new \News\Model\Category();
        $categoryN->setStoreId($storeId);
        $categoryNewsMapper = $this->getServiceLocator()->get('News\Model\CategoryMapper');
        $categoryNews = $categoryNewsMapper->fetchTree($categoryN);
        $news = [];

        if(count($categoryNews)){
            foreach($categoryNews as $c){
                $news[$c->getViewLink()] = html_entity_decode($c->getName());
                if($c->getChilds()){
                    foreach($c->getChilds() as $cc){
                        $news[$cc->getViewLink()] = '-- '.html_entity_decode($cc->getName());
                        if($cc->getChilds()){
                            foreach($cc->getChilds() as $ccc){
                                $news[$ccc->getViewLink()] = '------- '.html_entity_decode($ccc->getName());
                            }
                        }
                    }
                }
            }
        }

        $page = new \Home\Model\Page();
        $page->setStoreId($storeId);
        $pageMapper = $this->getServiceLocator()->get('Home\Model\PageMapper');
        $resultPage = $pageMapper->fetchTree($page);

        $pages = [];

        if(count($resultPage)){
            foreach($resultPage as $c){
                $pages[$c->getViewLink()] = html_entity_decode($c->getName());
                if($c->getChilds()){
                    foreach($c->getChilds() as $cc){
                        $pages[$cc->getViewLink()] = '-- '.html_entity_decode($cc->getName());
                        if($cc->getChilds()){
                            foreach($cc->getChilds() as $ccc){
                                $pages[$ccc->getViewLink()] = '------- '.html_entity_decode($ccc->getName());
                            }
                        }
                    }
                }
            }
        }

        $menu = [];
        $menu = ['Trang chủ' => ['label'=>'TRANG CHỦ', 'options' => ['/' => 'Trang chủ']],'Sản phẩm'=> ['label' => 'SẢN PHẨM', 'options' => $product], 'Tin tức' => ['label' => 'TIN TỨC', 'options' => $news], 'Trang' => ['label' => 'TRANG', 'options' => $pages]];

        $store = new \Admin\Model\Store();
        $storeMapper = $this->getServiceLocator()->get('Admin\Model\StoreMapper');
        $form = new \Admin\Form\Menu();

        if(!$this->user()->isSuperAdmin()){
            $store->setId($storeId);
        }
        $form->setUrl($menu);
        $form->setCategoryIds($b);
        $form->setStoreIds($store->toSelectBoxArray($storeMapper->fetchAll($store)));

        if($this->getRequest()->isPost()){
            $form->setData(array_merge_recursive($this->getRequest()->getPost()->toArray(),$this->getRequest()->getFiles()->toArray()));
            if($form->isValid()){
                $data = $form->getData();
                $menu = new \Admin\Model\Menu();
                $menu->exchangeArray($data);
                $menu->setUpdateDateTime(DateBase::getCurrentDateTime());
                $menu->setStatus(1);
                $menuMapper->save($menu);
                $this->redirect()->toUrl('/admin/setup/menu');
            }
        }
        return new ViewModel(array(
            'form' => $form
        ));
    }

    public function editmenuAction()
    {
        $this->layout('layout/admin');
        $id = $this->getEvent()->getRouteMatch()->getParam('id');
        $u = $this->getServiceLocator()->get('User\Service\User');
        $storeId = $u->getStoreId();

        $menu = new \Admin\Model\Menu();
        $menu->setId($id);

        $menuMapper = $this->getServiceLocator()->get('Admin\Model\MenuMapper');

        if(!$menuMapper->get($menu)){
            $this->redirect()->toUrl('/admin/setup/menu');
        }
        $data = $menu->toFormValues();

        $menu = new \Admin\Model\Menu();
        if(!$this->user()->isSuperAdmin()){
            $menu->setStoreId($storeId);
        }
        $a = $menuMapper->fetchAll($menu);
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
        $categoryP = new \Product\Model\Category();
        $categoryP->setStoreId($storeId);
        $categoryProductMapper = $this->getServiceLocator()->get('Product\Model\CategoryMapper');
        $categoryProduct = $categoryProductMapper->fetchTree($categoryP);
        $product = [];
        if(count($categoryProduct)){
            foreach($categoryProduct as $c){
                $product[$c->getViewLink()] = html_entity_decode($c->getName());
                if($c->getChilds()){
                    foreach($c->getChilds() as $cc){
                        $product[$cc->getViewLink()] = '-- '.html_entity_decode($cc->getName());
                        if($cc->getChilds()){
                            foreach($cc->getChilds() as $ccc){
                                $product[$ccc->getViewLink()] = '------- '.html_entity_decode($ccc->getName());
                            }
                        }
                    }
                }
            }
        }

        $categoryN = new \News\Model\Category();
        $categoryN->setStoreId($storeId);
        $categoryNewsMapper = $this->getServiceLocator()->get('News\Model\CategoryMapper');
        $categoryNews = $categoryNewsMapper->fetchTree($categoryN);
        $news = [];

        if(count($categoryNews)){
            foreach($categoryNews as $c){
                $news[$c->getViewLink()] = html_entity_decode($c->getName());
                if($c->getChilds()){
                    foreach($c->getChilds() as $cc){
                        $news[$cc->getViewLink()] = '-- '.html_entity_decode($cc->getName());
                        if($cc->getChilds()){
                            foreach($cc->getChilds() as $ccc){
                                $news[$ccc->getViewLink()] = '------- '.html_entity_decode($ccc->getName());
                            }
                        }
                    }
                }
            }
        }

        $page = new \Home\Model\Page();
        $page->setStoreId($storeId);
        $pageMapper = $this->getServiceLocator()->get('Home\Model\PageMapper');
        $resultPage = $pageMapper->fetchTree($page);

        $pages = [];

        if(count($resultPage)){
            foreach($resultPage as $c){
                $pages[$c->getViewLink()] = html_entity_decode($c->getName());
                if($c->getChilds()){
                    foreach($c->getChilds() as $cc){
                        $pages[$cc->getViewLink()] = '-- '.html_entity_decode($cc->getName());
                        if($cc->getChilds()){
                            foreach($cc->getChilds() as $ccc){
                                $pages[$ccc->getViewLink()] = '------- '.html_entity_decode($ccc->getName());
                            }
                        }
                    }
                }
            }
        }

        $menu = [];
        $menu = ['Trang chủ' => ['label'=>'TRANG CHỦ', 'options' => ['/' => 'Trang chủ']],'Sản phẩm'=> ['label' => 'SẢN PHẨM', 'options' => $product], 'Tin tức' => ['label' => 'TIN TỨC', 'options' => $news], 'Trang' => ['label' => 'TRANG', 'options' => $pages]];

        $store = new \Admin\Model\Store();
        $storeMapper = $this->getServiceLocator()->get('Admin\Model\StoreMapper');
        $form = new \Admin\Form\Menu();

        if(!$this->user()->isSuperAdmin()){
            $store->setId($storeId);
        }

        $form->setData($data);
        $form->setUrl($menu);
        $form->setCategoryIds($b);
        $form->setStoreIds($store->toSelectBoxArray($storeMapper->fetchAll($store)));

        if($this->getRequest()->isPost()){
//            print_r($this->getRequest()->getPost()->toArray());die;
            $form->setData(array_merge_recursive($this->getRequest()->getPost()->toArray(),$this->getRequest()->getFiles()->toArray()));
            if($form->isValid()){
                $data1 = $form->getData();
                $menu = new \Admin\Model\Menu();
                $menu->setId($id);
                $menu->exchangeArray($data1);
                $menu->setStatus($data['status']);
                $menuMapper->save($menu);
                $this->redirect()->toUrl('/admin/setup/menu');
            }
        }
        return new ViewModel(array(
            'form' => $form
        ));
    }

    public function deletemenuAction()
    {
        $id = $this->getRequest()->getPost()['id'];

        $id = isset($id) ? (string)(int)$id : false;

        if(!is_numeric($id)){
            return new JsonModel(array(
                'code'=> 0,
                'messenger' => 'Chúng tôi không tìm thấy bài viết này'
            ));
        }

        $mapper = $this->getServiceLocator()->get('Admin\Model\MenuMapper');
        $menu = new \Admin\Model\Menu();
        $menu->setId($id);

        if(!$mapper->get($menu)){
            return new JsonModel(array(
                'code' => 0,
                'messenger' => 'Chúng tôi không tìm thấy nội dung này'
            ));
        }
        $menu = new \Admin\Model\Menu();
        $menu->setParentId($id);

        if($mapper->get($menu)){
            return new JsonModel(array(
                'code' => 0,
                'messenger' => 'Bạn cần xóa danh mục con trước'
            ));
        }
        $menu = new \Admin\Model\Menu();
        $menu->setId($id);

        $mapper->delete($menu);

        return new JsonModel(array(
            'code' => 1,
            'messenger' => 'Đã xóa'
        ));
    }

    public function changeStatusAction()
    {
        $viewModel = new JsonModel();

        $id = $this->getRequest()->getPost()['id'];
        $id = isset($id) ? (string)(int)$id : false;

        $mapper = $this->getServiceLocator()->get('Admin\Model\MenuMapper');
        $menu = new \Admin\Model\Menu();
        $menu->setId($id);

        if(!$mapper->get($menu)){
            $viewModel->setVariable('code', 0);
            $viewModel->setVariable('messenger', 'Chúng tôi không tìm thấy nội dung này');
        }


        if($this->getRequest()->getPost()['order']){
            $menu->setUpdateDateTime(DateBase::getCurrentDateTime());
        }else{
            if($menu->getStatus() == 1){
                $menu->setStatus(0);
            }else{
                $menu->setStatus(1);
            }
        }

        $menu->setName(html_entity_decode($menu->getName()));

        $mapper->save($menu);

        $viewModel->setVariable('code', 1);
        $viewModel->setVariable('messenger', 'Đã thay đổi');
        $viewModel->setVariable('status', $menu->getStatus());

        return $viewModel;
    }

}




















