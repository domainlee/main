<?php
/**
 * @category    Shop99 library
 * @copyright    http://shop99.vn
 * @license        http://shop99.vn/license
 */

namespace Home;

class Module
{

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig()
    {
        return array(
            'invokables' => array(
                'Home\Form\Upload' => 'Home\Form\Upload',
                'Home\Form\UploadFilter' => 'Home\Form\UploadFilter',
                'Home\Model\Contact' => 'Home\Model\Contact',
                'Home\Model\ContactMapper' => 'Home\Model\ContactMapper',
                'Home\Model\Base' => 'Home\Model\Base',
                'Home\Model\BaseMapper' => 'Home\Model\BaseMapper',
                'Home\Model\MediaMapper' => 'Home\Model\MediaMapper',
                'Home\Model\MediaItemMapper' => 'Home\Model\MediaItemMapper',
                'Home\Model\LikeMapper' => 'Home\Model\LikeMapper',
                'Home\Model\BannerMapper' => 'Home\Model\BannerMapper',
                'Home\Model\ProductAttrListMapper' => 'Home\Model\ProductAttrListMapper',
                'Home\Model\ProductAttrMapper' => 'Home\Model\ProductAttrMapper',
                'Home\Model\PageMapper' => 'Home\Model\PageMapper',
                'Home\Model\MenuMapper' => 'Home\Model\MenuMapper',

            ),
        );
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
                )
            )
        );
    }

    public function getViewHelperConfig()
    {
        return array(
            'invokables' => array(
                'Home\View\Helper\HomeFactory' => 'Home\View\Helper\HomeFactory',
            ),
            'factories' => array(
                'home' => 'Home\View\Helper\HomeFactory',
            )
        );
    }
}