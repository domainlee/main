<?php

date_default_timezone_set('Asia/Saigon');

function vdump($data = null) {
	if(getenv('APPLICATION_ENV') == 'development' && $data !== null) {
		echo "<pre>";
		var_dump($data);
		echo "</pre>";
	}
}

function srcLink($paths, $version = 1)
{
    if (!is_array($paths) || !count($paths)) {
        return '';
    }
    if ($version < 10) {
        $version = '_v0' . abs($version);
    } elseif ($version < 100) {
        $version = '_v' . abs($version);
    } else {
        $version = '_v01';
    }
    return '/min/f=(DWEB.VN)' . chunk_split(base64_encode(implode(',', $paths) . $version), 100, '/');
}

//function srcLink($paths, $version = 1)
//{
//    if(!$paths){
//        return '';
//    }
//    if(is_string($paths)){
//        $paths = [$paths];
//    }
//    if ($version < 10) {
//        $version = '_v0' . abs($version);
//    } elseif ($version < 100) {
//        $version = '_v' . abs($version);
//    } else {
//        $version = '_v01';
//    }
//
//    return '/min/f=' . chunk_split(base64_encode(implode(',', $paths) . $version), 100, '/');
//}

list($usec, $sec) = explode(" ", microtime());
global $beginTime;
$beginTime = ((float)$usec + (float)$sec);

defined('DS') || define('DS', DIRECTORY_SEPARATOR);
defined('BASE_PATH') || define('BASE_PATH', dirname(dirname(__FILE__)));
defined('UPLOAD_PATH') || define('UPLOAD_PATH', BASE_PATH .'/public/uploads');
defined('MEDIA_PATH') || define('MEDIA_PATH', realpath(BASE_PATH .'/public/images'));

defined('LIB_PATH') || define('LIB_PATH', getenv("LIB_PATH"));
defined('VENDOR_PATH') || define('VENDOR_PATH', realpath(BASE_PATH .'/vendor'));
defined('TEMPLATES_PATH') || define('TEMPLATES_PATH', realpath(BASE_PATH .'/public/tp'));
set_include_path(implode(
	PATH_SEPARATOR,
	array(
		LIB_PATH,
		VENDOR_PATH,
		get_include_path()
	)
));

$zendPath = in_array(getenv('APPLICATION_ENV'), array('development', 'localhost')) ? LIB_PATH : VENDOR_PATH;
//echo $zendPath;die;
/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
define('PUBLIC_PATH', dirname(__FILE__));
chdir(dirname(__DIR__));

include 'Zend/Loader/AutoloaderFactory.php';
include 'HTMLPurifier/HTMLPurifier.auto.php';
include 'simple_html_dom.php';

Zend\Loader\AutoloaderFactory::factory(array(
	'Zend\Loader\StandardAutoloader' => array(
		'namespaces' => array(
			'Zend'     		=> $zendPath . '/Zend',
			'Menu'    => $zendPath . '/Menu',
            'ZendX'    => $zendPath . '/ZendX',
			'HTMLPurifier' 	=> $zendPath . '/HTMLPurifier',
			'PHPExcel' 	=> $zendPath . '/PHPExcel',
		),
	)
));
Zend\Mvc\Application::init(require 'config/application.config.php')->run();