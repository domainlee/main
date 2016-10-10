<?php
/**
 * Home\Controller
 *
 * @category       Shop99 library
 * @copyright      http://shop99.vn
 * @license        http://shop99.vn/license
 */

namespace Home\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Home\Model\DateBase;


class IndexController extends AbstractActionController
{

    public function indexAction()
    {
    }

    public function addAction()
    {

    }

    public function editAction()
    {

    }

    public function deleteAction()
    {

    }

    public function introAction()
    {

    }

    public function likeAction()
    {
        $id = $this->getRequest()->getPost('id');
        $value = $this->getRequest()->getPost('data');
        if(!$id || !$value){
            return new JsonModel([
                'code' => '0',
                'mes' => 'Bạn đã like bài viết này'
            ]);
        }
        setcookie($id, $value, time()+3600 * 24 * 365, '/');
        if(!isset($_COOKIE[$id])) {
            $likeMapper = $this->getServiceLocator()->get('Home\Model\LikeMapper');
            $like = new \Home\Model\Like();
            $like->setType(\Home\Model\Like::NEWS);
            $like->setItemId($id);
            $like->setCreatedDateTime(DateBase::getCurrentDateTime());
            $likeMapper->save($like);
            $getLike = new \Home\Model\Like();
            $getLike->setType(\Home\Model\Like::NEWS);
            $getLike->setItemId($id);
            $likeMapper = $this->getServiceLocator()->get('Home\Model\LikeMapper');
            $r = $likeMapper->get($getLike);
            return new JsonModel([
                'code' => '1',
                'mes' => 'Cảm ơn bạn đã thích chủ đề này',
                'total' => $r
            ]);
        } else {
            return new JsonModel([
                'code' => '0',
                'mes' => 'Bạn đã like bài viết này'
            ]);
        }
    }

    public function loadimagesAction()
    {

    }

}