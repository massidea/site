<?php
// Bootstrap definitions

date_default_timezone_set('Europe/Helsinki');
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(dirname(__FILE__) . '/../../library'),
    get_include_path(),
)));
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../../application'));
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? 
                                    getenv('APPLICATION_ENV') : 
                                    'production'));

// Use same data as main application

require_once 'Zend/Loader.php';
Zend_Loader::loadClass('Zend_Application');
$application = new Zend_Application(
	APPLICATION_ENV, 
	APPLICATION_PATH . '/../config/config.ini'
);
$autoloader = new Zend_Application_Module_Autoloader(array(
	'namespace' => 'Default',
	'basePath'  => '../../application/'
));

$application->getBootstrap()->bootstrap('db');

// Enable cache
$frontend = array('lifetime' => 7200,
				  'automatic_serialization' => true);
$backend = array('cache_dir' => '/../../tmp/',);
$cache = Zend_Cache::factory('core', 'File', $frontend, $backend);
Zend_Registry::set('cache', $cache);

// Execute service
require_once 'api.inc';
launchService();