<?php
date_default_timezone_set('Europe/Helsinki');

set_include_path(implode(PATH_SEPARATOR, array(
    realpath(dirname(__FILE__) . '/../library'),
    get_include_path(),
)));

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application/'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? 
                                    getenv('APPLICATION_ENV') : 
                                    'production'));

// Zend_Application
require_once 'Zend/Application.php'; 

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV, 
    APPLICATION_PATH . '/../config/config.ini'
);

// Right. Let's launch this.
$application->bootstrap()
            ->run();

            
            
/*

MOVED TO BOOTSTRAP

set_include_path(implode(PATH_SEPARATOR, array(
    realpath(dirname(__FILE__) . '/../library'),
    get_include_path(),
)));

date_default_timezone_set('Europe/Helsinki');

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application/'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'general'));

// Not sure if this is needed anymore, it doesn't work as I wanted...
//define('URL_BASE', '/oibs190/www/'); 
    
// Zend_Application
require_once 'Zend/Application.php';  

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV, 
    APPLICATION_PATH . '/../config/config.ini'
);

// Logging paths...
$logger = new Zend_Log(new Zend_Log_Writer_Stream('../logs/debug.log'));
Zend_Registry::set('logger', $logger);

$logger_registration = new Zend_Log(new Zend_Log_Writer_Stream('../logs/register.log'));
Zend_Registry::set('logger_registration', $logger_registration);

// Not completely sure if this is anymore necessary...I'll test this some day.
$frontController = Zend_Controller_Front::getInstance();
$frontController->setParam('env', APPLICATION_ENV);

// Register plugins....
Zend_Controller_Front::getInstance()->registerPlugin(new Oibs_Controller_Plugin_Language());
Zend_Controller_Front::getInstance()->registerPlugin(new Oibs_Controller_Plugin_CleanQuery());

// static route for language, I couldn't get this to work in the external routes. Will test more later on.
$router = $frontController->getRouter();
$route = new Zend_Controller_Router_Route( 
            ':language/:controller/:action/*', 
                array( 
                    'language'   => 'en', 
                    'module'     => 'default', 
                    'controller' => 'index', 
                    'action'     => 'index' 
                ) 
            ); 
$router->addRoute('lang_default', $route);

// Right. Let's launch this.
$application->bootstrap();
$application->run();
****************/