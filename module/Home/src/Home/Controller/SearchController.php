<?php
/**
 * Home\Controller
 *
 * @category   	Shop99 library
 * @copyright  	http://shop99.vn
 * @license    	http://shop99.vn/license
 */

namespace Home\Controller;

use Zend\View\Model\JsonModel;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use \stdClass;

class SearchController extends AbstractActionController
{
	public function indexAction()
	{
        $request = $this->getRequest();

        if($request->getQuery('q') == '' || $request->getQuery('q') == ' '){
            $this->redirect()->toUrl('/');
        }

        $sl = $this->getServiceLocator();
        $storeId = $this->getServiceLocator()->get('Store\Service\Store')->getStoreId();
        $productMapper = $sl->get('Product\Model\ProductMapper');

        $viewModel = new ViewModel();
        $options = [
            'page' => $request->getQuery('page'),
            'icpp' => 15,
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
        if($request->getQuery('limit')){
            $product->addOption('limit', $request->getQuery('limit'));
        }
        $variables = $product->prepareSearch(null, $request);
//        print_r($product);die;
        $paginator = $productMapper->search($product);

        if($request->getPost('template')){
            $viewModel->setTemplate('home/search/' . $request->getPost('template'));
            $viewModel->setTerminal($request->getPost('terminal', false));
        }

        $viewModel->setVariable('request', $request);
        $viewModel->setVariables($variables);
        $viewModel->setVariable('paginator', $paginator);

        return $viewModel;
    }

    /**
     * @uses autocomplete
     */
    public function suggestionAction()
    {
        /* @var $request \Zend\Http\Request */
        $request = $this->getRequest();

        $data = array();
        if (!($q = urldecode(trim($request->getQuery('q'))))) {
            return new JsonModel($data);
        }
        $ps = new \Product\Model\Store();
        $ps->setServiceLocator($this->getServiceLocator());
        /* @var $psMapper \Product\Model\StoreMapper */
        $psMapper = $this->getServiceLocator()->get('Product\Model\StoreMapper');
        $ps->prepareSearch();
        $limit = trim($request->getQuery('limit'));
        $options['limit'] = $limit > 0 ? $limit : 20;
        $products = $psMapper->search($ps, $options);
        if (is_array($products) && count($products)) {
            /* @var $ps \Product\Model\Store */
            foreach ($products as $ps) {
                $category = $psMapper->getDefaultCategory($ps->getId());
                $obj = new stdClass();
                $name = str_replace($q, '<b>' . $q . '</b>', $ps->getName());
                $obj->id = $ps->getId();
                $obj->label = $name;
                $obj->value = $ps->getViewLink();
                $obj->promotionValue = $ps->getPromotionValue();
                $obj->price = $ps->getPrice();
                $obj->imgThumb = $ps->getThumbnailUri();
                $obj->categoryId = $category ? $category->getId() : '';
                $obj->categoryName = $category ? $category->getName() : '';
                $data[] = $obj;
            }
        }
        return new JsonModel($data);
    }

    public function noresultAction()
    {
        /* @var $request \Zend\Http\Request */
        $request = $this->getRequest();

        $layoutMode = trim($request->getQuery('layout', null));
    	$view = new ViewModel();
    	if($layoutMode == 'false') {
    		$view->setTerminal(true);
    	}
    	return $view;
    }

    public function albumAction()
    {
        /* @var $request \Zend\Http\Request */
        $request = $this->getRequest();

        $album = new \Album\Model\Album();
        $album->setServiceLocator($this->getServiceLocator());

        $page = (int)$request->getQuery('page', 1);
        $icpp = (int)$request->getQuery('icpp', 20);

        $options = array(
            'page' => $page > 0 ? $page : 1,
            'icpp' => $icpp > 0 ? ($icpp > 100 ? 100 : $icpp) : 20,
        );

        $variables = $album->searchOptions($options);
        /* @var $AlbumMapper \Album\Model\AlbumMapper */
        $AlbumMapper = $this->getServiceLocator()->get('Album\Model\AlbumMapper');
        $paginator = $AlbumMapper->search($album, $options);

        $viewModel = new ViewModel();

        $viewModel->setVariables(array(
            'paginator' => $paginator,
            'query' => urldecode($request->getQuery('q')),
        ));
        $viewModel->setVariables($variables);

        return $viewModel;
    }

}