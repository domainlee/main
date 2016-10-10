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

class PageController extends AbstractActionController
{
	public function indexAction()
	{
        $viewModel = new ViewModel();
        /* @var $mapper \Home\Model\PageMapper */
        $mapper = $this->getServiceLocator()->get('Home\Model\PageMapper');

        $page = new \Home\Model\Page();
        $page->setId((int)trim($this->params('id')));
        $page->setStoreId($this->getServiceLocator()->get('Store\Service\Store')->getStoreId());
        if (!$pages = $mapper->get($page)) {
            $viewModel->setTemplate('error/404');
            return $viewModel;
        }

        $viewModel->setVariables([
            'page' => $pages,
        ]);

        return $viewModel;
    }

}