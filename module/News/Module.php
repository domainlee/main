<?php
/**
 * @category   	Shop99 library
 * @copyright  	http://shop99.vn
 * @license    	http://shop99.vn/license
 */
namespace News;

class Module
{

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php'
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
                )
            )
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig()
    {
        return array(
            'invokables' => array(
                'News\Model\Article' => 'News\Model\Article',
                'News\Model\ArticleMapper' => 'News\Model\ArticleMapper',
                'News\Model\Category' => 'News\Model\Category',
                'News\Model\CategoryMapper' => 'News\Model\CategoryMapper',
                'News\Service\News' => 'News\Service\News',
            )
        );
    }

    public function getViewHelperConfig()
    {
        return array(
            'invokables' => array(
                'News\View\Helper\NewsFactory' => 'News\View\Helper\NewsFactory',
                'News\View\Helper\NewsCategoryFactory' => 'News\View\Helper\NewsCategoryFactory',
            ),
            'factories' => array(
                'news' => 'News\View\Helper\NewsFactory',
                'newsCategory' => 'News\View\Helper\NewsCategoryFactory',
            )
        );
    }
}