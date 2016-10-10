<?php
/**
 * News\Controller
 *
 * @news        Shop99 library
 * @copyright   http://shop99.vn
 * @license     http://shop99.vn/license
 */

namespace News\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

class NewsController extends AbstractActionController
{

    public function indexAction()
    {
        /* @var $request \Zend\Http\Request */
        $request = $this->getRequest();
        $sl = $this->getServiceLocator();
        $viewModel = new ViewModel();
//        $category = new \News\Model\Category();
//        $category->setId((int)trim($this->params('id')));
//        $category->setStoreId($sl->get('Store\Service\Store')->getStoreId());
//        $category->setOptions(['childs' => true]);

        /* @var $categoryMapper \News\Model\CategoryMapper */
//        $categoryMapper = $sl->get('News\Model\CategoryMapper');

//        if (!!$category = $categoryMapper->get($category)) {
//            $categoryMapper->fetchParent($category);
            $options = [
                'page' => $request->getQuery('page'),
                'icpp' => 18,
            ];

            /* @var $articleMapper \News\Model\ArticleMapper */
            $articleMapper = $sl->get('News\Model\ArticleMapper');
            /* @var $ps \News\Model\Article */

//            $aIds = $category->getChildIds($category->getChilds());
//            $aIds[] = $category->getId();

            $article = new \News\Model\Article();
            $article->setOptions($options);
//            $article->setCategoryIds($aIds);
            $article->setStoreId($sl->get('Store\Service\Store')->getStoreId());

            $paginator = $articleMapper->search($article);
//            $viewModel->setVariable('category', $category);

            $viewModel->setVariable('paginator', $paginator);
//        } else {
//            $viewModel->setTemplate('error/404');
//            return $viewModel;
//        }
//        if ($request->getPost('template')) {
//            $viewModel->setTemplate($request->getPost('template'));
//            $viewModel->setTerminal($request->getPost('terminal', false));
//        }
        // Switch to json view mode
//        if ($request->getQuery('format') == 'json') {
//            $articles = [];
//            foreach ($paginator as $page) {
//                $article = new \News\Model\Article();
//                $article->exchangeArray((array)$page);
//                $articles[] = $article->toStd();
//            }
//            return new JsonModel([
//                'articles' => $articles,
//                'category' => $category
//            ]);
//        }
        return $viewModel;
    }

    public function categoryAction()
    {
        /* @var $request \Zend\Http\Request */
        $request = $this->getRequest();
        $sl = $this->getServiceLocator();
        $viewModel = new ViewModel();
        $category = new \News\Model\Category();
        $category->setId((int)trim($this->params('id')));
        $category->setStoreId($sl->get('Store\Service\Store')->getStoreId());
        $category->setOptions(['childs' => true]);

        /* @var $categoryMapper \News\Model\CategoryMapper */
        $categoryMapper = $sl->get('News\Model\CategoryMapper');

        if (!!$category = $categoryMapper->get($category)) {
            $categoryMapper->fetchParent($category);
            $options = [
                'page' => $request->getQuery('page'),
                'icpp' => 12,
            ];

            /* @var $articleMapper \News\Model\ArticleMapper */
            $articleMapper = $sl->get('News\Model\ArticleMapper');
            /* @var $ps \News\Model\Article */

            $aIds = $category->getChildIds($category->getChilds());
            $aIds[] = $category->getId();

            $article = new \News\Model\Article();
            $article->setOptions($options);
            $article->setCategoryIds($aIds);
            $article->setStoreId($sl->get('Store\Service\Store')->getStoreId());

            $paginator = $articleMapper->search($article);
            $viewModel->setVariable('category', $category);

            $viewModel->setVariable('paginator', $paginator);
        } else {
            $viewModel->setTemplate('error/404');
            return $viewModel;
        }
        if ($request->getPost('template')) {
            $viewModel->setTemplate($request->getPost('template'));
            $viewModel->setTerminal($request->getPost('terminal', false));
        }
        // Switch to json view mode
        if ($request->getQuery('format') == 'json') {
            $articles = [];
            foreach ($paginator as $page) {
                $article = new \News\Model\Article();
                $article->exchangeArray((array)$page);
                $articles[] = $article->toStd();
            }
            return new JsonModel([
                'articles' => $articles,
                'category' => $category
            ]);
        }
        return $viewModel;
    }

    public function searchAction()
    {
        /* @var $request \Zend\Http\Request */
        $request = $this->getRequest();

        $sl = $this->getServiceLocator();

        $viewModel = new ViewModel();
        if (!($q = $request->getQuery('q')) || !$q) {
            $viewModel->setTemplate('error/404');
            return $viewModel;
        }
        /* @var $articleMapper \News\Model\ArticleMapper */
        $articleMapper = $sl->get('News\Model\ArticleMapper');

        $article = new \News\Model\Article();
        $article->setTitle($q);
        $article->setOptions(['page' => $request->getQuery('page'), 'icpp' => $request->getQuery('icpp')]);
        $article->setStoreId($sl->get('Store\Service\Store')->getStoreId());

        $viewModel->setVariable('q', $q);

        if ($request->getPost('template')) {
            $limit = $request->getQuery('limit');
            if ($limit) {
                $article->addOption('limit', $limit > 0 ? $limit : 20);
            }
            $articles = $articleMapper->search($article);
            $viewModel->setTemplate('news/news/' . $request->getPost('template'));
            $viewModel->setTerminal($request->getPost('terminal', false));

            $viewModel->setVariables([
                'articles' => $articles
            ]);
            return $viewModel;
        }

        $paginator = $articleMapper->search($article);
        $viewModel->setVariable('paginator', $paginator);

        return $viewModel;
    }

    public function viewAction()
    {
        $viewModel = new ViewModel();
        /* @var $mapper \News\Model\ArticleMapper */
        $mapper = $this->getServiceLocator()->get('News\Model\ArticleMapper');

        $new = new \News\Model\Article();
        $new->setId((int)trim($this->params('id')));

        if (!$news = $mapper->get($new)) {
            $viewModel->setTemplate('error/404');
            return $viewModel;
        }
        $category = new \News\Model\Category();
        $category->setId($news->getCategoryId());
        $category->setStoreId($this->getServiceLocator()->get('Store\Service\Store')->getStoreId());

        /* @var $categoryMapper \News\Model\CategoryMapper */
        $categoryMapper = $this->getServiceLocator()->get('News\Model\CategoryMapper');
        if ($category->getId() && $categoryMapper->get($category)) {
            $categoryMapper->fetchParent($category);
        }
//        $news->loadTranslates($news,\Website\Model\TranslateContent::TYPE_ARTILE, $news->getId());
        $viewModel->setVariables([
            'news'     => $news,
            'category' => $category
        ]);

        return $viewModel;
    }

    public function profiloAction()
    {
        $viewModel = new ViewModel();
        /* @var $mapper \News\Model\ArticleMapper */
        $mapper = $this->getServiceLocator()->get('News\Model\ArticleMapper');

        $new = new \News\Model\Article();
        $new->setId((int)trim($this->params('id')));

        if (!$news = $mapper->get($new)) {
            $viewModel->setTemplate('error/404');
            return $viewModel;
        }
        $category = new \News\Model\Category();
        $category->setId($news->getCategoryId());
        $category->setStoreId($this->getServiceLocator()->get('Store\Service\Store')->getStoreId());

        /* @var $categoryMapper \News\Model\CategoryMapper */
        $categoryMapper = $this->getServiceLocator()->get('News\Model\CategoryMapper');
        if ($category->getId() && $categoryMapper->get($category)) {
            $categoryMapper->fetchParent($category);
        }
//        $news->loadTranslates($news,\Website\Model\TranslateContent::TYPE_ARTILE, $news->getId());
        $viewModel->setVariables([
            'news'     => $news,
            'category' => $category
        ]);

        return $viewModel;
    }

    public function blogAction()
    {
        /* @var $request \Zend\Http\Request */
        $request = $this->getRequest();
        $sl = $this->getServiceLocator();
        $viewModel = new ViewModel();

        $category = new \News\Model\Category();
        $category->setId(3);
        $category->setStoreId($sl->get('Store\Service\Store')->getStoreId());
        $category->setOptions(['childs' => true]);
        /* @var $categoryMapper \News\Model\CategoryMapper */
        $categoryMapper = $sl->get('News\Model\CategoryMapper');
        if (!!$category = $categoryMapper->get($category)) {
            $categoryMapper->fetchParent($category);
            $options = [
                'page' => $request->getQuery('page'),
                'icpp' => 9,
            ];
            /* @var $articleMapper \News\Model\ArticleMapper */
            $articleMapper = $sl->get('News\Model\ArticleMapper');
            /* @var $ps \News\Model\Article */
            $article = new \News\Model\Article();

            $article->setOptions($options);

            $aIds = $category->getChildIds($category->getChilds());
            $aIds[] = $category->getId();

            $article->setCategoryIds($aIds);
            $article->setStoreId($sl->get('Store\Service\Store')->getStoreId());

            $paginator = $articleMapper->search($article);
            $viewModel->setVariable('category', $category);
            $viewModel->setVariable('paginator', $paginator);
        } else {
            $viewModel->setTemplate('error/404');
            return $viewModel;
        }
        return $viewModel;
    }

    public function blogviewAction()
    {
        $viewModel = new ViewModel();
        /* @var $mapper \News\Model\ArticleMapper */
        $new = new \News\Model\Article();
        $new->setId((int)trim($this->params('id')));

        $mapper = $this->getServiceLocator()->get('News\Model\ArticleMapper');
        if (!$news = $mapper->get($new)) {
            $viewModel->setTemplate('error/404');
            return $viewModel;
        }

        $category = new \News\Model\Category();
        $category->setId($news->getCategoryId());
        $category->setStoreId($this->getServiceLocator()->get('Store\Service\Store')->getStoreId());

        /* @var $categoryMapper \News\Model\CategoryMapper */
        $categoryMapper = $this->getServiceLocator()->get('News\Model\CategoryMapper');
        if ($category->getId() && $categoryMapper->get($category)) {
            $categoryMapper->fetchParent($category);
        }

//        $news->loadTranslates($news,\Website\Model\TranslateContent::TYPE_ARTILE, $news->getId());

        $viewModel->setVariables([
            'news'     => $news,
            'category' => $category
        ]);

        return $viewModel;
    }

    public function aboutAction()
    {

    }

}