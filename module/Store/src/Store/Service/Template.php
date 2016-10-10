<?php
/**
 * @category       Shop99 library
 * @copyright      http://shop99.vn
 * @license        http://shop99.vn/license
 */

namespace Store\Service;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class Template implements ListenerAggregateInterface, ServiceLocatorAwareInterface
{

    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * @var \Store\Service\Store
     */
    protected $serviceStore;

    /**
     * @var \Zend\Stdlib\CallbackHandler[]
     */
    protected $listeners = array();

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
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
     * @param \Store\Service\Store $serviceStore
     */
    public function setServiceStore($serviceStore)
    {
        $this->serviceStore = $serviceStore;
    }

    /**
     * @return \Store\Service\Store
     */
    public function getServiceStore()
    {
        return $this->serviceStore;
    }

    /**
     * {@inheritDoc}
     */
    public function attach(EventManagerInterface $e)
    {
        $this->listeners[] = $e->attach(MvcEvent::EVENT_RENDER, array($this, 'onRender'), 2000);
        $config = $this->getServiceLocator()->get('Config');
        if (isset($config['db']['profilerEnabled']) && $config['db']['profilerEnabled']) {
            $this->listeners[] = $e->attach(MvcEvent::EVENT_FINISH, array($this, 'onFinish'), -2000);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function detach(EventManagerInterface $e)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($e->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }

    /**
     * Event callback to be triggered on dispatch for
     * adding ViewTemplatePathStack and ViewTemplateMapResolver
     *
     * @author VanCK
     * @return void
     */
    public function onRender()
    {
        if (!$this->getServiceStore()->getUitemplate() ||
            !($dir = $this->getServiceStore()->getUitemplate()->getDirectory()) ||
            !is_dir($dirView = TEMPLATES_PATH . DS . $dir)
        ) {
            return;
        }

        /* @var $tps \Zend\View\Resolver\TemplatePathStack */
        $tps = $this->getServiceLocator()->get('ViewTemplatePathStack');
        /* @var $tmr \Zend\View\Resolver\TemplateMapResolver */
        $tmr = $this->getServiceLocator()->get('ViewTemplateMapResolver');

        // desktop
        if (is_dir($dirView = TEMPLATES_PATH . DS . $dir . DS . 'view')) {
            $tps->addPath($dirView);
            if (is_file($mapFile = $dirView . DS . 'template_map.php')) {
                $tmr->add(include $mapFile);
            }
        }

         require_once 'vendor/Mobile_Detect.php';
         $detect = new \Mobile_Detect();
         $device = 'desktop';
         if (isset($_SESSION['switchDevice'])) {
             $device = $_SESSION['switchDevice'];
         }

//         // any mobile device (phones or tablets)
         if (($detect->isMobile() || $device === 'mobile') && is_dir($dirMobile = TEMPLATES_PATH . DS . $dir . DS . 'view.mobile')) {

             $tps->addPath($dirMobile);
             if (is_file($mobileMapFile = $dirMobile . DS . 'template_map.php')) {
                 $tmr->add(include $mobileMapFile);
             }
         }

//         // any tablet device
         if (($detect->isTablet() || $device === 'tablet') && is_dir($dirTablet = TEMPLATES_PATH . DS . $dir . DS . 'view.tablet')) {
             $tps->addPath($dirTablet);
             if (is_file($tabletMapFile = $dirTablet . DS . 'template_map.php')) {
                 $tmr->add(include $tabletMapFile);
             }
         }
    }

    /**
     * Event callback to be triggered on finish for
     * profiling and logging sql queries
     *
     * @author VanCK
     * @return void
     */
    public function onFinish()
    {
        /* @var $profiler \Zend\Db\Adapter\Profiler\Profiler */
        $profiler = $this->getServiceLocator()->get('dbAdapter')->getProfiler();
        $profiles = $profiler->getProfiles();

        $totalQueries = 0;
        $totalTime = 0;
        foreach ($profiles as $profile) {
            $totalQueries++;
            $totalTime += $profile['elapse'];
        }

        $totalTime = round($totalTime, 5);
        /* @var $log \Zend\Log\Logger */
        $log = $this->getServiceLocator()->get('log');
        $log->info("$totalQueries queries ($totalTime)");
        $i = 1;
        foreach ($profiles as $profile) {
            $totalTime += $profile['elapse'];
            $sql = $profile['sql'];
            if (isset($profile['parameters'])) {
                /* @var $parameters \Zend\Db\Adapter\ParameterContainer */
                $parameters = $profile['parameters'];
                foreach ($parameters->getNamedArray() as $position => $data) {
                    $sql = str_replace(':' . $position, $data, $sql);
                }
            }
            $log->info($i++ . ' ~ ' . round($profile['elapse'], 5) . ' ' . $sql);
        }
    }
}