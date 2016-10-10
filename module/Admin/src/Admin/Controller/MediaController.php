<?php
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Home\Form\FormBase;
use Home\Model\DateBase;

class MediaController extends AbstractActionController{

	public function indexAction()
    {
        $viewModel = new ViewModel();
        $request = $this->getRequest();
        $this->layout('layout/admin');
        $model = new \Admin\Model\Media();

//        if(!$this->user()->isSuperAdmin()){
            $u = $this->getServiceLocator()->get('User\Service\User');
            $storeId = $u->getStoreId();
            $model->setStoreId($storeId);
//        }

        $mediaMapper = $this->getServiceLocator()->get('Admin\Model\MediaMapper');
        $model->exchangeArray((array)$this->getRequest()->getQuery());

        $page = (int)$this->getRequest()->getQuery()->page ? : 1;

        if($request->getPost('itemId')){
            $model->addOption('itemId', $request->getPost('itemId'));
        }else{
            if($request->getPost('data') && $request->getPost('data') != ''){
                $model->setId($request->getPost('data'));
            }
        }
        if($request->getPost('type')){
            $model->addOption('type', $request->getPost('type'));
        }

        if($request->getPost('loadAll')){
            $model->addOption('loadAll', $request->getPost('loadAll'));
        }

        if($request->getPost('order') == 'ASC'){
            $model->addOption('order' , 'ASC');
        }elseif($request->getPost('order') == 'DESC'){
            $model->addOption('order' , 'DESC');
        }

        $results = $mediaMapper->search($model, array($page,28));

        if ($request->getPost('template')) {
            $viewModel->setTemplate($request->getPost('template'));
            $viewModel->setTerminal($request->getPost('terminal', false));
        }
        $viewModel->setVariable('results', $results);
        return $viewModel;
	}

//    public function loadAction()
//    {
//        $viewModel = new ViewModel();
//        $request = $this->getRequest();
//        $this->layout('layout/admin');
//        $model = new \Admin\Model\Media();
//        $mediaMapper = $this->getServiceLocator()->get('Admin\Model\MediaMapper');
//        $model->exchangeArray((array)$this->getRequest()->getQuery());
//        $page = (int)$this->getRequest()->getQuery()->page ? : 1;
//
//        if($request->getPost('data') && $request->getPost('data') != ''){
//            $model->setId($request->getPost('data'));
//        }
//
//        if($request->getPost('itemId')){
//            $model->addOption('itemId', $request->getPost('itemId'));
//        }
////        print_r($model);die;
//
//        $results = $mediaMapper->search2($model, array($page,28));
//
//        if ($request->getPost('template')) {
//            $viewModel->setTemplate($request->getPost('template'));
//            $viewModel->setTerminal($request->getPost('terminal', false));
//        }
//        $viewModel->setVariable('results', $results);
//        return $viewModel;
//    }

    public function uploadAction()
    {
        $jsonModel = new JsonModel();
        $form = new \Admin\Form\Media($this->getServiceLocator());
        $u = $this->getServiceLocator()->get('User\Service\User');
        $storeId = $u->getStoreId();

        if($this->getRequest()->isPost()){
            $form->setData(array_merge_recursive(
                $this->getRequest()->getPost()->toArray(),
                $this->getRequest()->getFiles()->toArray()));
            if($form->isValid()){
                $formData = $form->getData();
                $a = $formData['imagemulti'];

                foreach($a as $ff){
                    $mediaMapper = $this->getServiceLocator()->get('Admin\Model\MediaMapper');
                    $media = new \Admin\Model\Media();
                    $media->setType($media::FILE_IMAGES);
                    $media->setFileName($ff['name']);
                    $media->setCreatedById($u->getId());
                    $media->setStoreId($storeId);
                    $media->setCreatedDateTime(DateBase::getCurrentDateTime());
                    $targetFolder = \Base\Model\Uri::getSavePath($media);
                    if (! file_exists($targetFolder)) {
                        $oldmask = umask(0);
                        mkdir($targetFolder, 0777, true);
                        umask($oldmask);
                    }
                    rename($ff['tmp_name'], $targetFolder . '/' . $ff['name']);
                    $mediaMapper->save($media);

                }
                $jsonModel->setVariables([
                    'code' => 1,
                    'message' => 'Upload thành công'
                ]);
                return $jsonModel;
            }else{
                $jsonModel->setVariables([
                    'code' => 0,
                    'message' => $form->getErrorMessagesList()
                ]);
                return $jsonModel;
            }
        }
    }

    public function bannerAction(){
        $this->layout('layout/admin');
        $banner = new \Admin\Model\Banner();
        $mapper = $this->getServiceLocator()->get('Admin\Model\BannerMapper');
        $modelSt = new \Admin\Model\Store();
        $sl = $this->getServiceLocator();

        $u = $this->getServiceLocator()->get('User\Service\User');
        $storeId = $u->getStoreId();

        $mapperSt = $this->getServiceLocator()->get('Admin\Model\StoreMapper');
        $banner->exchangeArray((array)$this->getRequest()->getQuery());
        $options['isAdmin'] = $this->user()->isSuperAdmin();
        $fFilter = new \Admin\Form\ArticleSearch($options);
        if(!$this->user()->isSuperAdmin()){
            $banner->setStoreId($storeId);
        }else{
            $fFilter->setStoreIds($modelSt->toSelectBoxArray($mapperSt->fetchAll($modelSt)));
        }

        $fFilter->bind($banner);
        $page = (int)$this->getRequest()->getQuery()->page ? : 1;
        $results = $mapper->search($banner, array($page,10));

        return new ViewModel(array(
            'fFilter' => $fFilter,
            'results' => $results
        ));
    }

    public function addAction(){
        $this->layout('layout/admin');
        $banner = new \Admin\Model\Banner();
        $bannerMapper = $this->getServiceLocator()->get('Admin\Model\BannerMapper');

        $form = new \Admin\Form\Banner($this->getServiceLocator(), null);
        $form->bind($banner);
//        $storeId = $this->getServiceLocator()->get('Store\Service\Store')->getStoreId();
        $u = $this->getServiceLocator()->get('User\Service\User');
        $storeId = $u->getStoreId();

        $modelSt = new \Admin\Model\Store();
        if(!$this->user()->isSuperAdmin()){
            $modelSt->setId($storeId);
        }
        $mapperSt = $this->getServiceLocator()->get('Admin\Model\StoreMapper');
        $form->setStoreIds($modelSt->toSelectBoxArray($mapperSt->fetchAll($modelSt)));

        if($this->getRequest()->isPost()) {
            $form->setData(array_merge_recursive($this->getRequest()->getPost()->toArray(),$this->getRequest()->getFiles()->toArray()));
//            $form->setData($this->getRequest()->getPost());
            if($form->isValid()){
                $banner->setCreatedById(1);
                $banner->setCreatedDateTime(DateBase::getCurrentDateTime());
                $bannerMapper->save($banner);
                $mediaMapper = $this->getServiceLocator()->get('Admin\Model\MediaItemMapper');
                //Check exits cần một vòng for query select sẽ dài hơn where in id delete. Nên chỗ này xóa đi rồi saves
                $mediaMapper->deleteFileProduct($banner->getId());
                $postImages = $this->getRequest()->getPost()['images'];
                if(isset($postImages) && $postImages != ''){
                    $imagesArray = explode(',', $postImages);
                    $c = 1;
                    foreach($imagesArray as $i){
                        if($i){
                            $mediaItem = new \Admin\Model\MediaItem();
                            $mediaItem->setType(\Admin\Model\MediaItem::FILE_BANNER);
                            $mediaItem->setItemId($banner->getId());
                            $mediaItem->setFileItem($i);
                            $mediaItem->setSort($c++);
                            $mediaMapper->save($mediaItem);
                        }
                    }
                }
                $this->redirect()->toUrl('/admin/media/banner');
            }
        }
        return new ViewModel(array(
            'form' => $form
        ));
    }

    public function editbannerAction()
    {
        $this->layout('layout/admin');
        $id = $this->getEvent()->getRouteMatch()->getParam('id');
        $sl = $this->getServiceLocator();

        $mapper = $this->getServiceLocator()->get('Admin\Model\BannerMapper');
        $model = new \Admin\Model\Banner();
        $model->setId($id);
        $model = $mapper->get($model);
        $form = new \Admin\Form\Banner($this->getServiceLocator(), null);

//        $storeId = $this->getServiceLocator()->get('Store\Service\Store')->getStoreId();
        $u = $this->getServiceLocator()->get('User\Service\User');
        $storeId = $u->getStoreId();

        $modelSt = new \Admin\Model\Store();
        if(!$this->user()->isAdmin()){
            $modelSt->setId($storeId);
        }
        $mapperSt = $this->getServiceLocator()->get('Admin\Model\StoreMapper');

        $data = $model->toFormValues();

        $mediaItem = new \Admin\Model\MediaItem();
        $mediaItem->setItemId($id);
        $mediaItem->setType(\Admin\Model\MediaItem::FILE_BANNER);

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
        $form->setStoreIds($modelSt->toSelectBoxArray($mapperSt->fetchAll($modelSt)));

        if($this->getRequest()->isPost()){
            $form->setData(array_merge_recursive($this->getRequest()->getPost()->toArray(),$this->getRequest()->getFiles()->toArray()));
            if($form->isValid()){
                $data = $form->getData();
                $model = new \Admin\Model\Banner();
                $model->exchangeArray($data);
                $model->setId($id);
                $model->setCreatedById(1);
                $model->setCreatedDateTime(DateBase::getCurrentDateTime());

                $mapper->save($model);
                $mediaMapper = $this->getServiceLocator()->get('Admin\Model\MediaItemMapper');
                //Check exits cần một vòng for query select sẽ dài hơn where in id delete. Nên chỗ này xóa đi rồi saves
                $mediaMapper->deleteBanner($id);
                if(isset($data['images']) && $data['images'] != ''){
                    $imagesArray = explode(',', $data['images']);
                    $c = 1;
                    foreach($imagesArray as $i){
                        if($i){
                            $mediaItem = new \Admin\Model\MediaItem();
                            $mediaItem->setType(\Admin\Model\MediaItem::FILE_BANNER);
                            $mediaItem->setItemId($id);
                            $mediaItem->setFileItem($i);
                            $mediaItem->setSort($c++);
                            $mediaMapper->save($mediaItem);
                        }
                    }
                }
                $this->redirect()->toUrl('/admin/media/banner');
            }
        }
        return new ViewModel(array(
            'form' => $form,
            'itemId' => $id
        ));
    }

    public function deleteAction()
    {
        $id = $this->getRequest()->getPost()['id'];
        if(!is_numeric($id)){
            return new JsonModel(array(
                'code'=> 0,
                'messenger' => 'Chúng tôi không tìm thấy Banner này'
            ));
        }

        $mapper = $this->getServiceLocator()->get('Admin\Model\BannerMapper');
        $banner = new \Admin\Model\Banner();
        $banner->setId($id);

        if(!$mapper->get($banner)){
            return new JsonModel(array(
                'code' => 0,
                'messenger' => 'Chúng tôi không tìm thấy Banner này'
            ));
        }

        $mapper->delete($banner);

        if($banner->getId()){
            $mediaItem = new \Admin\Model\MediaItem();
            $mediaItem->setItemId($banner->getId());
            $mediaItem->setType(\Admin\Model\MediaItem::FILE_BANNER);
            $mediaMapper = $this->getServiceLocator()->get('Admin\Model\MediaItemMapper');
            $mediaMapper->deleteType($mediaItem);
        }

        return new JsonModel(array(
            'code' => 1,
            'messenger' => 'Đã xóa'
        ));
    }

    public function changeAction()
    {
        $id = $this->getRequest()->getPost('id');
        if(!is_numeric($id)){
            return new JsonModel(array(
                'code'=> 0,
                'messenger' => 'Chúng tôi không tìm thấy Banner này'
            ));
        }
        $mapper = $this->getServiceLocator()->get('Admin\Model\BannerMapper');
        $banner = new \Admin\Model\Banner();
        $banner->setId($id);

        if(!$mapper->get($banner)){
            return new JsonModel(array(
                'code'=> 0,
                'messenger' => 'Chúng tôi không tìm thấy sản phẩm này'
            ));
        }
        if($banner->getStatus() == \Admin\Model\Banner::STATUS_ACTIVE){
            $banner->setStatus(\Admin\Model\Banner::STATUS_INACTIVE);
        }else{
            $banner->setStatus(\Admin\Model\Banner::STATUS_ACTIVE);
        }
        $mapper->save($banner);

        return new JsonModel(array(
            'code'=> 1,
            'messenger' => 'Đã thay đổi',
            'status' => $banner->getStatus()
        ));
    }

}





















