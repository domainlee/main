<?php
namespace Admin\Controller;
use Admin\Model\Media;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Home\Form\FormBase;
use Home\Model\DateBase;
use Home\Model\Base;

class ArticleController extends AbstractActionController{

	public function indexAction()
    {
        $this->layout('layout/admin');
		$model = new \Admin\Model\Article();
		$mapper = $this->getServiceLocator()->get('Admin\Model\ArticleMapper');
		$modelSt = new \Admin\Model\Store();
        $sl = $this->getServiceLocator();
        $u = $this->getServiceLocator()->get('User\Service\User');
        $storeId = $u->getStoreId();

        $mapperSt = $this->getServiceLocator()->get('Admin\Model\StoreMapper');
		$model->exchangeArray((array)$this->getRequest()->getQuery());
        $options['isAdmin'] = $this->user()->isSuperAdmin();
        $fFilter = new \Admin\Form\ArticleSearch($options);
        if(!$this->user()->isSuperAdmin()){
            $model->setStoreId($storeId);
        }else{
            $fFilter->setStoreIds($modelSt->toSelectBoxArray($mapperSt->fetchAll($modelSt)));
        }

        $fFilter->bind($model);
		$page = (int)$this->getRequest()->getQuery()->page ? : 1;
		$results = $mapper->search($model, array($page,10));
		
		return new ViewModel(array(
			'fFilter' => $fFilter,
			'results' => $results
		));
	}

	public function addAction()
    {
		$this->layout('layout/admin');
//        $storeId = $this->getServiceLocator()->get('Store\Service\Store')->getStoreId();

        $u = $this->getServiceLocator()->get('User\Service\User');
        $storeId = $u->getStoreId();

        $model = new \Admin\Model\Article();
		$modelCate = new \Admin\Model\Articlec();
        if(!$this->user()->isSuperAdmin()){
            $modelCate->setStoreId($storeId);
        }
		$mapperCate = $this->getServiceLocator()->get('Admin\Model\ArticlecMapper');
        $mapper = $this->getServiceLocator()->get('Admin\Model\ArticleMapper');
        $category = $mapperCate->fetchAll($modelCate);
		$modelSt = new \Admin\Model\Store();
		$mapperSt = $this->getServiceLocator()->get('Admin\Model\StoreMapper');
		$form = new \Admin\Form\Article();
		$form->setCategoryIds($model->toSelectBoxArray($category,\Admin\Model\Article::SELECT_MODE_ALL));
        $sl = $this->getServiceLocator();

        if(!$this->user()->isSuperAdmin()){
            $modelSt->setId($storeId);
        }
        $form->setStoreIds($modelSt->toSelectBoxArray($mapperSt->fetchAll($modelSt)));

		if($this->getRequest()->isPost()){
			$form->setData(array_merge_recursive($this->getRequest()->getPost()->toArray(),$this->getRequest()->getFiles()->toArray()));
			if($form->isValid()){
                $data = $form->getData();
                $extraContent = json_encode(['serivce' => $data['title1'] ? $data['title1']:'','link' => $data['link'] ? $data['link']:'']);
                $model->exchangeArray($data);
                $model->setExtraContent($extraContent);
                $model->setName($data['title']);
                $model->setCreatedDateTime(DateBase::getCurrentDateTime());

                $mapper->save($model);

                if(isset($data['tag']) && $data['tag'] != ''){
                    $tagMapper = $this->getServiceLocator()->get('Admin\Model\TagMapper');
                    $articletagMapper = $this->getServiceLocator()->get('Admin\Model\ArticleTagMapper');
                    $tagArray = explode(',', $data['tag']);
                    foreach($tagArray as $t){
                        $tagId = '';
                        $t = strtolower($t);
                        if($t){
                            $tag = new \Admin\Model\Tag();
                            $tag->setName($t);
                            if($tagMapper->get($tag)){
                                $tagId = $tag->getId();
                            }else{
                                $tag = new \Admin\Model\Tag();
                                $tag->setName($t);
                                $tag->setCreatedById(1);
                                $tag->setCreatedDateTime(DateBase::getCurrentDateTime());
                                $tagMapper->save($tag);
                                $tagId = $tag->getId();
                            }
                            $articleTag = new \Admin\Model\ArticleTag();
                            $articleTag->setTagId($tagId);
                            $articleTag->setArticleId($model->getId());
                            $articletagMapper->save($articleTag);
                        }
                    }
                }

                $mediaMapper = $this->getServiceLocator()->get('Admin\Model\MediaItemMapper');
                //Check exits cần một vòng for query select sẽ dài hơn where in id delete. Nên chỗ này xóa đi rồi saves
                $mediaMapper->deleteTaskTag($model->getId());
                if(isset($data['images']) && $data['images'] != ''){
                    $imagesArray = explode(',', $data['images']);
                    $c = 1;
                    foreach($imagesArray as $i){
                        if($i){
                            $mediaItem = new \Admin\Model\MediaItem();
                            $mediaItem->setType(\Admin\Model\MediaItem::FILE_ARTICLE);
                            $mediaItem->setItemId($model->getId());
                            $mediaItem->setFileItem($i);
                            $mediaItem->setSort($c++);
                            $mediaMapper->save($mediaItem);
                        }
                    }
                }

                $this->redirect()->toUrl('/admin/article');
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
//        $a = '231341&33333333&-4132-&423';
//        echo Base::Replace($a);
//        die;
        $sl = $this->getServiceLocator();
//        $storeId = $this->getServiceLocator()->get('Store\Service\Store')->getStoreId();
        $u = $this->getServiceLocator()->get('User\Service\User');
        $storeId = $u->getStoreId();

        $mapper = $this->getServiceLocator()->get('Admin\Model\ArticleMapper');
        $model = new \Admin\Model\Article();
        $model->setId($id);
        $model = $mapper->get($model);
		$modelCate = new \Admin\Model\Articlec();
        if(!$this->user()->isSuperAdmin()){
            $modelCate->setStoreId($storeId);
        }
		$mapperCate = $this->getServiceLocator()->get('Admin\Model\ArticlecMapper');
		$category = $mapperCate->fetchAll($modelCate);

        $modelSt = new \Admin\Model\Store();
        $mapperSt = $this->getServiceLocator()->get('Admin\Model\StoreMapper');

        if(!$this->user()->isSuperAdmin()){
            $modelSt->setId($storeId);
        }

		$form = new \Admin\Form\Article();

        $form->setStoreIds($modelSt->toSelectBoxArray($mapperSt->fetchAll($modelSt)));

        $data = $model->toFormValues();
        $mediaItem = new \Admin\Model\MediaItem();
        $mediaItem->setItemId($model->getId());
        $mediaItem->setType(\Admin\Model\MediaItem::FILE_ARTICLE);

        if(isset($data['extraContent'])){
            $d = json_decode($data['extraContent']);
            $data['title1'] = $data['extraContent'] ? $d->serivce:'';
            $data['link'] = $data['extraContent'] ? $d->link:'';
        }
        $mediaMapper = $this->getServiceLocator()->get('Admin\Model\MediaItemMapper');
        $m = $mediaMapper->fetchAll($mediaItem);
        $fI = [];
        if(isset($m)){
            foreach($m as $i){
                $fI[] = $i->getFileItem();
            }
        }
        $data['images'] = implode(',', $fI);

        $articleTag = new \Admin\Model\ArticleTag();
        $articleTag->setArticleId($model->getId());
        $articletagMapper = $this->getServiceLocator()->get('Admin\Model\ArticleTagMapper');
        $r = $articletagMapper->fetchAll($articleTag);
        $tags = [];
        if(isset($r)){
            foreach($r as $t){
                $tags[] = $t->getOption('Tag')->getName();
            }
        }
        $data['tag'] = implode(',', $tags);
        $form->setData($data);
        $images = $model->toFormValues()['image_upload'];
        $type = $model->toFormValues()['type'];

        $form->setCategoryIds($model->toSelectBoxArray($category,\Admin\Model\Article::SELECT_MODE_ALL));
		if($this->getRequest()->isPost()){
            $form->setData(array_merge_recursive($this->getRequest()->getPost()->toArray(),$this->getRequest()->getFiles()->toArray()));
            if($form->isValid()){
                $data = $form->getData();
                $model = new \Admin\Model\Article();
                $model->setId($id);
                $model->exchangeArray($data);
                $model->setType($type);
                $model->setName($data['title']);
//                $model->setStoreId($storeId);
                $model->setCreatedDateTime(DateBase::getCurrentDateTime());
                $extraContent = json_encode(['serivce' => $data['title1'] ? $data['title1']:'','link' => $data['link'] ? $data['link']:'']);
                $model->setExtraContent($extraContent);
//                $model->setCreatedById(1);
                $articletagMapper = $this->getServiceLocator()->get('Admin\Model\ArticleTagMapper');
                //Check exits cần một vòng for query select sẽ dài hơn where in id delete. Nên chỗ này xóa đi rồi saves
                $articletagMapper->deleteTaskTag($id);
                if(isset($data['tag']) && $data['tag'] != ''){
                    $tagMapper = $this->getServiceLocator()->get('Admin\Model\TagMapper');
                    $tagArray = explode(',', $data['tag']);
                    foreach($tagArray as $t){
                        $tagId = '';
                        if($t){
                            $tag = new \Admin\Model\Tag();
                            $tag->setName($t);
                            if($tagMapper->get($tag)){
                                $tagId = $tag->getId();
                            }else{
                                $tag = new \Admin\Model\Tag();
                                $tag->setName($t);
                                $tag->setCreatedById(1);
                                $tag->setCreatedDateTime(DateBase::getCurrentDateTime());
                                $tagMapper->save($tag);
                                $tagId = $tag->getId();
                            }
                            $articleTag = new \Admin\Model\ArticleTag();
                            $articleTag->setTagId($tagId);
                            $articleTag->setArticleId($id);
                            $articletagMapper->save($articleTag);
                        }
                    }
                }
                $mediaMapper = $this->getServiceLocator()->get('Admin\Model\MediaItemMapper');
                //Check exits cần một vòng for query select sẽ dài hơn where in id delete. Nên chỗ này xóa đi rồi saves
                $mediaMapper->deleteTaskTag($id);
                if(isset($data['images']) && $data['images'] != ''){
                    $imagesArray = explode(',', $data['images']);
                    $c = 1;
                    foreach($imagesArray as $i){
                        if($i){
                            $mediaItem = new \Admin\Model\MediaItem();
                            $mediaItem->setType(\Admin\Model\MediaItem::FILE_ARTICLE);
                            $mediaItem->setItemId($id);
                            $mediaItem->setFileItem($i);
                            $mediaItem->setSort($c++);
                            $mediaMapper->save($mediaItem);
                        }
                    }
                }
                $mapper->save($model);
                $this->redirect()->toUrl('/admin/article');
			}else{
                print_r($form->getMessages());die;
            }
		}
		return new ViewModel(array(
			'form' => $form,
            'itemId' => $id
		));
	}


	public function changeactiveAction(){
		$this->layout('layout/admin');
		$id = $this->getEvent()->getRouteMatch()->getParam('id');
		$mapper = $this->getServiceLocator()->get('Admin\Model\ArticleMapper');
		$model = $mapper->getId($id);
		
		if(($model->getStatus()) == \Admin\Model\Article::STATUS_ACTIVE){
			$model->setStatus(\Admin\Model\Article::STATUS_INACTIVE);
		}
		else{
			$model->setStatus(\Admin\Model\Article::STATUS_ACTIVE);
		}
		$mapper->save($model);
		$this->redirect()->toUrl('/admin/article');
	}

	public function deleteAction(){

        $id = $this->getRequest()->getPost()['id'];
        if(!is_numeric($id)){
            return new JsonModel(array(
                'code'=> 0,
                'messenger' => 'Chúng tôi không tìm thấy bài viết này'
            ));
        }

        $mapper = $this->getServiceLocator()->get('Admin\Model\ArticleMapper');
        $article = new \Admin\Model\Article();
        $article->setId($id);

        if(!$mapper->get($article)){
            return new JsonModel(array(
                'code' => 0,
                'messenger' => 'Chúng tôi không tìm thấy bài viết này'
            ));
        }

        $mapper->delete($article);

        if($article->getId()){
            $articletagMapper = $this->getServiceLocator()->get('Admin\Model\ArticleTagMapper');
            $articletagMapper->deleteTaskTag($article->getId());
            $mediaMapper = $this->getServiceLocator()->get('Admin\Model\MediaItemMapper');
            $mediaMapper->deleteTaskTag($article->getId());
        }

        return new JsonModel(array(
			'code' => 1,
            'messenger' => 'Đã xóa'
		));
	}

//	public function uploadAction(){
//		$this->layout('layout/admin');
//		$id = $this->getEvent()->getRouteMatch()->getParam('id');
//		$mapper = $this->getServiceLocator()->get('Admin\Model\ArticleMapper');
//		$model = $mapper->getId($id);
//
//		$oldImage = $model->getImage ();
//		$fFilter = new \Admin\Form\UploadFilter();
//		//$fFilter->setExcludedId($id);
//		$form = new \Admin\Form\Article();
//		$form->setInputFilter($fFilter);
//		//$form->setCategoryIds($model->toSelectBoxArray($category,\Admin\Model\Article::SELECT_MODE_ALL));
//		$form->bind($model);
//
//		if($this->getRequest()->isPost()){
//			$form->setData($this->getRequest()->getPost());
//			$files = $this->getRequest()->getFiles();
//			if($form->isValid()){
//				$mapper = $this->getServiceLocator()->get('Admin\Model\ArticleMapper');
//				$file = $files ['image_upload'];
//				$isSaved = false;
//				if($file ['name']) {
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
//					if (($rs = $fileFilter->filter ( $file )) != false) {
//						$model->setImage ( $newName );
//						if ($oldImage && $oldImage != $model->getImage ()) {
//							@unlink ( \Base\Model\Uri::getSavePath ( $model ) . "/" . $oldImage );
//						}
//						$mapper->save ( $model );
//						$isSaved = true;
//						$this->redirect()->toUrl('/admin/article');
//					} else {
//						$form->setMessages(array(
//								'image_upload' => array(
//										'Upload ảnh thất bại'
//								)
//						));
//					}
//				}else{
//					$mapper->save($model);
//					$this->redirect()->toUrl('/admin/article');
//					$isSaved = true;
//				}
//			}
//		}
//		return new ViewModel(array(
//				'form' => $form
//		));
//	}

    public function categoryAction()
    {
        $this->layout('layout/admin');
//        $storeId = $this->getServiceLocator()->get('Store\Service\Store')->getStoreId();
        $u = $this->getServiceLocator()->get('User\Service\User');
        $storeId = $u->getStoreId();

        $model = new \Admin\Model\Articlec();

        $mapper = $this->getServiceLocator()->get('Admin\Model\ArticlecMapper');
        $modelSt = new \Admin\Model\Store();
        $mapperSt = $this->getServiceLocator()->get('Admin\Model\StoreMapper');
        $model->exchangeArray((array)$this->getRequest()->getQuery());
        $fFilter = new \Admin\Form\ArticlecSearch();
        $pages = $this->getRequest()->getQuery()->pages ?: 1;
        $fFilter->bind($model);

        if(!$this->user()->isSuperAdmin()){
            $model->setStoreId($storeId);
        }else{
            $fFilter->setStoreIds($modelSt->toSelectBoxArray($mapperSt->fetchAll($modelSt)));
        }
        $results = $mapper->search($model, array($pages,10));
        return new ViewModel(array(
            'fFilter'=> $fFilter,
            'results'=> $results
        ));
    }

    public function addcategoryAction()
    {
        $this->layout('layout/admin');
//        $storeId = $this->getServiceLocator()->get('Store\Service\Store')->getStoreId();
        $u = $this->getServiceLocator()->get('User\Service\User');
        $storeId = $u->getStoreId();

        $model = new \Admin\Model\Articlec();
        if(!$this->user()->isSuperAdmin()){
            $model->setStoreId($storeId);
        }
        $mapper = $this->getServiceLocator()->get('Admin\Model\ArticlecMapper');
        $parents = $mapper->fetchAll($model);
        $form = new \Admin\Form\Articlec();
        $form->setParentIds($model->toSelectBoxArray($parents,\Admin\Model\Articlec::SELECT_MODE_ALL));

        $modelSt = new \Admin\Model\Store();
        $mapperSt = $this->getServiceLocator()->get('Admin\Model\StoreMapper');
        if(!$this->user()->isSuperAdmin()){
            $modelSt->setId($storeId);
        }
        $form->setStoreIds($modelSt->toSelectBoxArray($mapperSt->fetchAll($modelSt)));

        $form->bind($model);
        if($this->getRequest()->isPost()){
            $form->setData($this->getRequest()->getPost());
            if($form->isValid()) {
                $mapper->save($model);
                $this->redirect()->toUrl('/admin/article/category');
            }
        }
        return new ViewModel(array(
            'form' => $form
        ));
    }

    public function editcategoryAction()
    {
        $this->layout('layout/admin');
        if(!($id = $this->getEvent()->getRouteMatch()->getParam('id'))){
            $this->redirect()->toUrl('/admin/article/category');
        }
        if(!is_numeric($id)){
            $this->redirect()->toUrl('/admin/article/category');
        }
//        $storeId = $this->getServiceLocator()->get('Store\Service\Store')->getStoreId();
        $u = $this->getServiceLocator()->get('User\Service\User');
        $storeId = $u->getStoreId();

        $mapper = $this->getServiceLocator()->get('Admin\Model\ArticlecMapper');

        $category = new \Admin\Model\Articlec();
        $category->setId($id);
        if(!$mapper->get($category)){
            $this->redirect()->toUrl('/admin/article/category');
        }
        if(!$this->user()->isAdmin()){
            $category->setStoreId($storeId);
        }
        $parents = $mapper->fetchAll($category);

        $form = new \Admin\Form\Articlec();
        $form->setParentIds($category->toSelectBoxArray($parents,\Admin\Model\Articlec::SELECT_MODE_ALL));

        $modelSt = new \Admin\Model\Store();
        $mapperSt = $this->getServiceLocator()->get('Admin\Model\StoreMapper');
        if(!$this->user()->isAdmin()){
            $modelSt->setId($storeId);
        }
        $form->setStoreIds($modelSt->toSelectBoxArray($mapperSt->fetchAll($modelSt)));
        $form->bind($category);
        if($this->getRequest()->isPost()){
            $form->setData($this->getRequest()->getPost());
            if($form->isValid()) {
//                print_r($form->getData());die;

//                $category = new \Admin\Model\Articlec();
//                $category->exchangeArray($form->getData());
//                print_r($category);die;
                $mapper->save($category);
                $this->redirect()->toUrl('/admin/article/category');
            }
        }
        return new ViewModel(array(
            'form' => $form
        ));
    }

    public function changetypeAction()
    {
        $jsonModel = new JsonModel();
        $id = $this->getRequest()->getPost('id');
        $type = $this->getRequest()->getPost('type');

        $articleMapper = $this->getServiceLocator()->get('Admin\Model\ArticleMapper');
        $article = new \Admin\Model\Article();
        $article->setId($id);
        if(!$articleMapper->get($article))
        {
            $jsonModel->setVariables([
                'code' => 0,
                'messages' => 'Không tìm thấy bài viết'
            ]);
            return $jsonModel;
        }
        $article->setType($type);
//        print_r($article);die;
        $articleMapper->save($article);

        $jsonModel->setVariables([
            'code' => 1,
            'messages' => 'Đã đổi vị trí'
        ]);
        return $jsonModel;
    }

    public function scanAction()
    {
        $this->layout('layout/admin');
        $content = file_get_contents('http://vnexpress.net/tin-tuc/the-gioi');
        $pattern = '#class="block_image_news width_common">.*class="title_news">.*href="(.*)".*class="thumb">.*src="(.*)".*alt="(.*)".*class="news_lead".*data-mobile-href=".*">(.*)</div>#imsU';
        preg_match_all($pattern, $content, $matches);
        print_r($matches);
        die;
    }

    public function changeAction()
    {
        $id = $this->getRequest()->getPost('id');
        $id = isset($id) ? (string)(int)$id : false;

        $mapper = $this->getServiceLocator()->get('Admin\Model\ArticleMapper');
        $article = new \Admin\Model\Article();
        $article->setId($id);

        if(!$mapper->get($article)){
            return new JsonModel(array(
                'code'=> 0,
                'messenger' => 'Chúng tôi không tìm thấy sản phẩm này'
            ));
        }
        if($article->getStatus() == \Admin\Model\Article::STATUS_ACTIVE){
            $article->setStatus(\Admin\Model\Article::STATUS_INACTIVE);
        }else{
            $article->setStatus(\Admin\Model\Article::STATUS_ACTIVE);
        }
        $mapper->save($article);

        return new JsonModel(array(
            'code'=> 1,
            'messenger' => 'Đã thay đổi',
            'status' => $article->getStatus()
        ));
    }

    public function changecAction()
    {
        $id = $this->getRequest()->getPost('id');
        $mapper = $this->getServiceLocator()->get('Admin\Model\ArticlecMapper');
        $category = new \Admin\Model\Articlec();
        $category->setId($id);

        if(!$mapper->get($category)){
            return new JsonModel(array(
                'code'=> 0,
                'messenger' => 'Chúng tôi không tìm thấy sản phẩm này'
            ));
        }
        if($category->getStatus() == \Admin\Model\Articlec::STATUS_ACTIVE){
            $category->setStatus(\Admin\Model\Articlec::STATUS_INACTIVE);
        }else{
            $category->setStatus(\Admin\Model\Articlec::STATUS_ACTIVE);
        }
        $mapper->save($category);

        return new JsonModel(array(
            'code'=> 1,
            'messenger' => 'Đã thay đổi',
            'status' => $category->getStatus()
        ));
    }


}






















