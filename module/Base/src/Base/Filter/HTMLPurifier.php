<?php
namespace Base\Filter;

use  \HTMLPurifier as HTMLPurifierLib;

class HTMLPurifier extends \Zend\Filter\AbstractFilter
{
    public function filter($value)
    {
        // perform some transformation upon $value to arrive on $valueFiltered
        $options = array(
		    // Allow only paragraph tags
		    // and anchor tags wit the href attribute
		    array(
		        'HTML.Allowed',
		        'p,a[href]'
		    ),
		    // Format end output with Tidy
		    array(
		        'Output.TidyFormat',
		        true
		    ),
		    // Assume XHTML 1.0 Strict Doctype
		    array(
		        'HTML.Doctype',
		        'XHTML 1.0 Strict'
		    ),
		    // Disable cache, but see note after the example
		    array(
		        'Cache.DefinitionImpl',
		        null
		    )
		);

		// Configuring HTMLPurifier
		$config = \HTMLPurifier_Config::createDefault();
		foreach ($options as $option) {
		    $config->set($option[0], $option[1]);
		}

		// Creating a HTMLPurifier with it's config
		$purifier = new HTMLPurifierLib($config);

		$valueFiltered = $purifier->purify($value);

        return $valueFiltered;
    }
}