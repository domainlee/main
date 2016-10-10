<?php
/**
 * Home\View\Helper\Home
 *
 * @category    Shop99 library
 * @copyright   http://shop99.vn
 * @license     http://shop99.vn/license
 */

namespace Home\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class Home
 * @package Media\View\Helper
 */
class Home extends AbstractHelper
{

    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator($serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     */
    public function __construct($serviceLocator)
    {
        $this->setServiceLocator($serviceLocator);
    }

    public function getImages($option = null)
    {
        if(!isset($option)){
            return null;
        }
        /* @var $CategoryMapper \Home\Model\MediaItemMapper */

        $mediaItemMapper = $this->getServiceLocator()->get('Home\Model\MediaItemMapper');
        $mediaItem = new \Home\Model\MediaItem();
        $mediaItem->setItemId($option['id']);
        $mediaItem->setType($option['type']);
        if($mediaItemMapper->get($mediaItem)){
            return $mediaItemMapper->get($mediaItem);
        }
        return null;
    }

    public function getLike($option = null)
    {
        if(!isset($option)){
            return null;
        }
        /* @var $CategoryMapper \Home\Model\MediaItemMapper */
        $likeMapper = $this->getServiceLocator()->get('Home\Model\LikeMapper');
        $like = new \Home\Model\Like();
        $like->setItemId($option['id']);
        $like->setType($option['type']);
        return $likeMapper->get($like);
    }

    public function getAttr($option = null)
    {
        if(!isset($option['id']) && !isset($option['type']) && !isset($option['group'])){
            return null;
        }
        $AttrListMapper = $this->getServiceLocator()->get('Home\Model\ProductAttrListMapper');
        $attr = new \Home\Model\ProductAttrList();
        if(isset($option['id'])){
            $attr->setProductId($option['id']);
        }
        if(isset($option['type'])){
            $attr->setType($option['type']);
        }
        if(isset($option['group'])){
            $attr->addOption('group', true);
        }
        return $AttrListMapper->get($attr);
    }

    public function menu($option = null)
    {
        $menuMapper = $this->getServiceLocator()->get('Home\Model\MenuMapper');

        $menu = new \Home\Model\Menu();
        $storeId = $this->getServiceLocator()->get('Store\Service\Store')->getStoreId();
        $menu->setStoreId($storeId);
        if(isset($option['positionId'])){
            $menu->setPositionId($option['positionId']);
        }
        return $menuMapper->fetchTree($menu);
    }
}