<?php
return array(
    'modules' => array(
    	'Base',
    	'User',
    	'Authorize',
    	'Uitemplate',
    	'Store',	
        'Home',
        'Order',
    	'Admin',
    	'Product',
        'News',
    ),
    'module_listener_options' => array(
    	'module_paths' => array(
    		'./module',
    		'./vendor',
    	),
        'config_glob_paths'    => array(
            'config/autoload/{,*.}{global,local}.php',
        ),
        
    ),
);
