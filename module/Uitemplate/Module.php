<?php
/**
 * @itemplate   Shop99 library
 * @copyright   http://shop99.vn
 * @license     http://shop99.vn/license
 */

namespace Uitemplate;

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

    public function getServiceConfig()
    {
        return array(
            'invokables' => array(
                'Uitemplate\Model\Uitemplate' => 'Uitemplate\Model\Uitemplate',
                'Uitemplate\Model\UitemplateMapper' => 'Uitemplate\Model\UitemplateMapper'
            )
        );
    }
}