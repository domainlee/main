<?php

namespace Base\Model;

use Exception;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

abstract class Base implements ServiceLocatorAwareInterface {

	/**
	 * @var ServiceLocatorInterface
	 */
	protected $serviceLocator;
	
	/**
	 * @var string
	 */
	protected $fromDate;
	
	/**
	 * @var string
	 */
	protected $toDate;
	
	public function getToDate(){
		$date = new \Base\Model\RDate();
        return $this->toDate ? $date->toCommonDate($this->toDate) : null;
    }

    public function setToDate($date){
        $this->toDate = $date;
    }

    public function getFromDate(){
		$date = new \Base\Model\RDate();
        return $this->fromDate ? $date->toCommonDate($this->fromDate) : null;
    }

    public function setFromDate($date){
        $this->fromDate = $date;
    }

	/**
	 * @var array
	 */
	protected $options;

    protected $translateOptions;

    /**
	 * @return \Zend\ServiceManager\ServiceManager
	 */
	public function getServiceLocator() {
		return $this->serviceLocator;
	}

	/**
	 * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
	 */
	public function setServiceLocator(ServiceLocatorInterface $serviceLocator) {
		$this->serviceLocator = $serviceLocator;
		return $this;
	}

    public function addOption($key, $value)
    {
        return $this->options[$key] = $value;
    }

    /**
     * @param string $key
     * @param null $defaultValue
     * @return null
     */
    public function getOption($key, $defaultValue = null)
    {
        return isset($this->options[$key]) ? $this->options[$key] : $defaultValue;
    }

	/**
	 * @return the $options
	 */
	public function getOptions() {
		return $this->options;
	}

	/**
	 * @param multitype: $options
	 */
	public function setOptions($options) {
		$this->options = $options;
		return $this;
	}

	/**
	 * Overloading: allow property access
	 *
	 * @param  string $name
	 * @param  mixed $value
	 * @return void
	 */
	public function __set($name, $value) {
		$method = 'set' . ucfirst($name);
		if ('mapper' == $name || !method_exists($this, $method)) {
			throw new Exception('Invalid property specified');
		}
		$this->$method($value);
	}

	/**
	 * Overloading: allow property access
	 *
	 * @param  string $name
	 * @return mixed
	 */
	public function __get($name) {
		$method = 'get' . ucfirst($name);
		if ('mapper' == $name || !method_exists($this, $method)) {
			throw new Exception('Invalid property specified');
		}
		return $this->$method();
	}

	/**
	 * extract object to array
	 */
	public function getArrayCopy()
	{
		return get_object_vars($this);
	}

	/**
	 * populate properties from array
	 * @param array $data
	 */
	public function exchangeArray($data)
	{
		if(is_array($data)) {
			foreach ($data as $key => $value) {
				$method = 'set' . ucfirst($key);
				if (in_array($method, get_class_methods($this))) {
					$this->$method($value);
				}
			}
		}
		return $this;
	}

	/**
	 * @param array $items
	 * @return array(id => name)
	 */
	public function toIds($items) {
		if(is_array($items) && count($items)) {
			$result = array();
			foreach($items as $item) {
				$result[$item->getId()] = $item->getId();
			}
			return $result;
		}
		return array();
	}

	/**
	 * @param array $items
	 * @return array(id => name)
	 */
	public function toSelectBoxArray($items)
	{
		if(is_array($items) && count($items)) {
			$result = array();
			foreach($items as $item) {
				$result[$item->getId()] = $item->getName();
			}
			return $result;
		}
		return array();
	}

    public function getTranslateOptions($item, $field)
    {
        if($this->translateOptions) {
            $supportedLocaleIds = $GLOBALS['domainConfigs']['locales']['supportedLocaleIds'];
            $localeId = $supportedLocaleIds[$GLOBALS['domainConfigs']['locales']['current']];
            if (isset($this->translateOptions[$localeId]) &&
                isset($this->translateOptions[$localeId][$field])
            ) {
                return $this->translateOptions[$localeId][$field];
            }
        } else {
            if ($this->getServiceLocator()
                && isset($GLOBALS['domainConfigs']['locales']['current'])
                && isset($GLOBALS['domainConfigs']['locales']['default'])
                && $GLOBALS['domainConfigs']['locales']['current'] != $GLOBALS['domainConfigs']['locales']['default']
            ) {
                if (isset($GLOBALS['domainConfigs']['locales']) &&
                    isset($GLOBALS['domainConfigs']['locales']['supported']) &&
                    count($GLOBALS['domainConfigs']['locales']['supported'])
                ) {
                    $translate = new \Website\Model\TranslateContent();
                    if ($item instanceof \Product\Model\Category) {
                        $translate->setType(\Website\Model\TranslateContent::TYPE_CATEGORY);
                        $translate->setItemId($item->getId());
                    } elseif($item instanceof \Product\Model\Store){
                        $translate->setType(\Website\Model\TranslateContent::TYPE_PRODUCT_STORE);
                        $translate->setItemId($item->getId());
                    } elseif($item instanceof \News\Model\Category){
                        $translate->setType(\Website\Model\TranslateContent::TYPE_ARTILE_CATEGORY);
                        $translate->setItemId($item->getId());
                    } elseif($item instanceof \News\Model\Article){
                        $translate->setType(\Website\Model\TranslateContent::TYPE_ARTILE);
                        $translate->setItemId($item->getId());
                    } elseif ($item instanceof \Website\Model\ContentValue) {
                        $translate->setType(\Website\Model\TranslateContent::TYPE_CONTENT_KEY);
                        $templateService = $this->getServiceLocator()->get('Website\Service\Template');
                        $contentKeyTemplateId = $templateService->getContentKeyTemplateId(['id' => $item->getKeyId()]);
                        $translate->setItemId($contentKeyTemplateId);
                    } elseif ($item instanceof \Website\Model\ContentKeyTemplate) {
                        $translate->setType(\Website\Model\TranslateContent::TYPE_CONTENT_KEY);
                        $translate->setItemId($item->getId());
                    }
                    else {
                        return null;
                    }

                    /* @var $translateMapper \Website\Model\TranslateContentMapper */
                    $translateMapper = $this->getServiceLocator()->get('Website\Model\TranslateContentMapper');

                    $translateOptions = $translateMapper->getLocaleContents($translate);

                    $supportedLocaleIds = $GLOBALS['domainConfigs']['locales']['supportedLocaleIds'];
                    if(!isset($supportedLocaleIds[$GLOBALS['domainConfigs']['locales']['current']])){
                        return null;
                    }
                    $localeId = $supportedLocaleIds[$GLOBALS['domainConfigs']['locales']['current']];
                    if ($translateOptions[$localeId][$field]) {
                        return $translateOptions[$localeId][$field];
                    }
                }
            }

        }
        return null;
    }

    /**
     * @param array $translateOptions
     */
    public function setTranslateOptions($translateOptions)
    {
        $this->translateOptions = $translateOptions;
    }
}