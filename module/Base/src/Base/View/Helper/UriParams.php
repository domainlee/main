<?php
/**
 * Base\View\Helper\UriParams
 *
 * @category   	Shop99 library
 * @copyright  	http://shop99.vn
 * @license    	http://shop99.vn/license
 */
namespace Base\View\Helper;

use Zend\View\Helper\AbstractHelper;

class UriParams extends AbstractHelper
{
	/**
	 * @var \Zend\Http\Request
	 */
    protected $request;

    /**
     * @param \Zend\Http\Request $request
     */
    public function setRequest($request)
    {
        $this->request = $request;
    }

    /**
     * @return \Zend\Http\Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param \Zend\Http\Request $request
     */
    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * @return array
     */
    public function getQueryParams() {
    	return $this->getRequest()->getQuery()->toArray();
    }

    /**
     * @param string $key
     * @return string|NULL
     */
    public function getQueryParam($key) {
    	$parmas = $this->getRequest()->getQuery()->toArray();
    	if(isset($parmas[$key])) {
    	    return $parmas[$key];
    	}
    	return null;
    }

    /**
     * @param array $params
     * @param string|null $uri
     * @return mixed|null|string
     */
    public function build($params, $uri = null)
    {
    	$result = $uri ?: $this->getRequest()->getUri()->getPath();
		$result = str_replace("%2F", "/", $result);
		$result = str_replace("%3A", ":", $result);

    	// append params to uri
        if(is_array($params)) {
        	foreach ($params as $param => $value) {
        		$paramStartPos = strpos($result, "$param=");
        		// param is in uri, replace the value
				if($paramStartPos !== false) {
					$paramLength = strlen($param);
					$valuleLength = strlen($this->getRequest()->getQuery($param));
					$result = substr_replace($result, $value, $paramStartPos + $paramLength + 1, $valuleLength);
            	} else { // param is not in uri
            		if(strpos($result, '?')) {
            			$result .= "&$param=$value";
					} else {
						$result .= "?$param=$value";
            		}
            	}
        	}
        }
        return $result;
    }
}