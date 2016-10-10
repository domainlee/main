<?php
namespace Product\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

class ProductController extends AbstractActionController{

	public function indexAction(){

//        $cache = $this->getServiceLocator()->get('cache');
//        // set unique Cache key
//        $key    = 'unique-cache-key';
//        // get the Cache data
//        $success = 'domainlee';
//
//        $result = $cache->getItem($key, $success);
//        if (!$success) {
//            // if not set the data for next request
//            $result = 'data 1';
//            $cache->setItem($key, $result);
//        }
//        // result
//        echo $result;
//        die;
//
//        return new ViewModel();

        $request = $this->getRequest();
        $sl = $this->getServiceLocator();
        $storeId = $this->getServiceLocator()->get('Store\Service\Store')->getStoreId();
        $productMapper = $sl->get('Product\Model\ProductMapper');

        $viewModel = new ViewModel();
        $options = [
            'page' => $request->getQuery('page'),
            'icpp' => 18,
        ];
        $product = new \Product\Model\Product();
        $product->setOptions($options);
        $product->setStoreId($storeId);

        if($request->getQuery('color')){
            if(is_numeric($request->getQuery('color'))){
                $product->addOption('color', $request->getQuery('color'));
                $viewModel->setVariable('requestColor', $request->getQuery('color'));
            }
        }
        if($request->getQuery('size')){
            if(is_numeric($request->getQuery('size'))){
                $product->addOption('size', $request->getQuery('size'));
                $viewModel->setVariable('requestSize', $request->getQuery('size'));
            }
        }
        if($request->getQuery('show')){
            if(in_array($request->getQuery('show'), ['priceAsc','priceDesc','SaleOff'])){
                $product->addOption('show', $request->getQuery('show'));
            }
        }
        $variables = $product->prepareSearch(null, $request);
        $paginator = $productMapper->search($product);

        $viewModel->setVariable('request', $request);
        $viewModel->setVariables($variables);
        $viewModel->setVariable('paginator', $paginator);

        return $viewModel;
	}

    public function categoryAction()
    {
        $request = $this->getRequest();
        $sl = $this->getServiceLocator();
        $storeId = $this->getServiceLocator()->get('Store\Service\Store')->getStoreId();
        $categoryMapper = $sl->get('Product\Model\CategoryMapper');
        $productMapper = $sl->get('Product\Model\ProductMapper');

        $viewModel = new ViewModel();
        $category = new \Product\Model\Category();
        $category->setId(trim($this->params('id')));
        $category->setStoreId($storeId);
        $category->setOptions(['childs' => true]);
        $categoryMapper->get($category);
        $ids = $category->getChildIds($category->getChilds());
        $ids[] = $category->getId();
        $options = [
            'page' => $request->getQuery('page'),
            'icpp' => 12,
        ];
        $product = new \Product\Model\Product();
        $product->setCategoryId($ids);
        $product->setOptions($options);
        $product->setStoreId($storeId);

        if($request->getQuery('color')){
            if(is_numeric($request->getQuery('color'))){
                $product->addOption('color', $request->getQuery('color'));
                $viewModel->setVariable('requestColor', $request->getQuery('color'));
            }
        }
        if($request->getQuery('size')){
            if(is_numeric($request->getQuery('size'))){
                $product->addOption('size', $request->getQuery('size'));
                $viewModel->setVariable('requestSize', $request->getQuery('size'));
            }
        }
        if($request->getQuery('show')){
            if(in_array($request->getQuery('show'), ['priceAsc','priceDesc','SaleOff'])){
                $product->addOption('show', $request->getQuery('show'));
            }
        }
        $variables = $product->prepareSearch(null, $request);
        $paginator = $productMapper->search($product);

        $viewModel->setVariable('request', $request);
        $viewModel->setVariables($variables);
        $viewModel->setVariable('category', $category);
        $viewModel->setVariable('paginator', $paginator);

        return $viewModel;

    }

	public function viewAction()
    {
        $viewModel = new ViewModel();

        $model = new \Product\Model\Product();
        $model->setId(trim($this->params('id')));
		$mapper = $this->getServiceLocator()->get('Product\Model\ProductMapper');
		$request = $this->getRequest();
        $storeId = $this->getServiceLocator()->get('Store\Service\Store')->getStoreId();
		$model->setStoreId($storeId);
        $results = $mapper->getId($model);

        $category = new \Product\Model\Category();
        $category->setId($results->getCategoryId());
        $category->setStoreId($storeId);
        $mapper = $this->getServiceLocator()->get('Product\Model\CategoryMapper');
        $c = $mapper->get($category);

        $mediaItemMapper = $this->getServiceLocator()->get('Home\Model\MediaItemMapper');
        $mediaItem = new \Home\Model\MediaItem();
        $mediaItem->setItemId($model->getId());
        $mediaItem->setType(\Home\Model\MediaItem::FILE_PRODUCT);
        $images = $mediaItemMapper->get($mediaItem);

//        $viewModel->setVariables(array(
//            'images' => $images,
//        ));

        $viewModel->setVariables(array(
            'results' => $results,
            'category' => $c,
            'images' => $images,
        ));

        return $viewModel;
	}



}















