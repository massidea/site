<?php
// application/bootstrap.php
// 
// APPLICATION CONSTANTS - Set the constants to use in this application.
// These constants are accessible throughout the application, even in ini 
// files. We optionally set APPLICATION_PATH here in case our entry point 
// isn't index.php (e.g., if required from our test suite or a script).
defined('APPLICATION_PATH')
    or define('APPLICATION_PATH', dirname(__FILE__));

//APPLICATION_ENVIRONMENT defines the purpose of the server environment: if dev, it shows stack traces with errors.
defined('APPLICATION_ENVIRONMENT')		
    or define('APPLICATION_ENVIRONMENT', 'development');

// FRONT CONTROLLER - Get the front controller.
// The Zend_Front_Controller class implements the Singleton pattern, which is a
// design pattern used to ensure there is only one instance of
// Zend_Front_Controller created on each request.
$frontController = Zend_Controller_Front::getInstance();
//$frontController->throwExceptions(true);


// CONFIGURATION - Setup the configuration object
// The Zend_Config_Ini component will parse the ini file, and resolve all of
// the values for the given section.  Here we will be using the section name
// that corresponds to the APP's Environment
$configuration = new Zend_Config_Ini('../config/config.ini', 'general');

// REGISTRY - setup the application registry
// An application registry allows the application to store application 
// necessary objects into a safe and consistent (non global) place for future 
// retrieval.  This allows the application to ensure that regardless of what 
// happends in the global scope, the registry will contain the objects it 
// needs.
$registry = Zend_Registry::getInstance();
$registry->config = $configuration;
//$registry->dbAdapter     = $dbAdapter;

$frontController->setBaseUrl($configuration->path->baseurl);

// CONTROLLER DIRECTORY SETUP - Point the front controller to your action
// controller directory.
$frontController->setControllerDirectory(APPLICATION_PATH . '/controllers'/*$config->path->controllers*/);

// setup controller
/*$frontController->registerPlugin(new CustomControllerAclManager($auth));
$frontController->registerPlugin(new languagesPlugin());
Zend_Layout::startMvc(array('layoutPath'=> $config->path->layouts));
Zend_Layout::getMvcInstance()->getView()->doctype(Zend_View_Helper_Doctype::XHTML1_STRICT);*/

// APPLICATION ENVIRONMENT - Set the current environment.
// Set a variable in the front controller indicating the current environment --
// commonly one of development, staging, testing, production, but wholly
// dependent on your organization's and/or site's needs.
$frontController->setParam('env', APPLICATION_ENVIRONMENT);
$router = $frontController->getRouter();

// LAYOUT SETUP - Setup the layout component
// The Zend_Layout component implements a composite (or two-step-view) pattern
// In this call we are telling the component where to find the layouts scripts.
Zend_Layout::startMvc(APPLICATION_PATH . '/layouts/scripts');

// init zend_session
Zend_Session::start();


		// hack to bypass Flashmessenger one hop limiter forgetting session variables due to 
		// "ghost redirecting"
		if (isset($_SESSION["FlashMessenger"]["default"][1])) {
			$_SESSION["msg"] = $_SESSION["FlashMessenger"]["default"][1];
		}
		elseif (isset($_SESSION["FlashMessenger"]["default"][0])) {
			$_SESSION["msg"] = $_SESSION["FlashMessenger"]["default"][0];
		}
		// see customcontrolleraction postdispatch for rest of hax
		// end of hax

// VIEW SETUP - Initialize properties of the view object
// The Zend_View component is used for rendering views. Here, we grab a "global"
// view instance from the layout object, and specify the doctype we wish to
// use -- in this case, XHTML1 Strict.
$view = Zend_Layout::getMvcInstance()->getView();
$view->doctype('XHTML11');

$pdoParams = array(
	PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
);

$dbParams = array(
	'host' => $configuration->database->host,
	'username' => $configuration->database->username,
	'password' => $configuration->database->password,
	'dbname' => $configuration->database->dbname,
	'driver_options' => $pdoParams,
);

$db = Zend_Db::factory($configuration->database->adapter, $dbParams);
Zend_Db_Table::setDefaultAdapter($db);
Zend_Registry::set('db', $db);

// setup application authenticcation 
$auth = Zend_Auth::getInstance();
$auth->setStorage(new Zend_Auth_Storage_Session());

// create the application loggers
$logger = new Zend_Log(new Zend_Log_Writer_Stream($configuration->logging->file));
Zend_Registry::set('logger', $logger);

$logger_registration = new Zend_Log(new Zend_Log_Writer_Stream($configuration->logging->registerfile));
Zend_Registry::set('logger_registration', $logger_registration);

/*
$route = new Zend_Controller_Router_Route(
			'/*',
			array('language'   => 'en', 
				  'module' 	   => 'index',
                  'controller' => 'index', 
                  'action'     => 'index' )
	);
$router->addRoute('no_lang', $route);
*/
$route = new Zend_Controller_Router_Route( 
            ':language/:controller/:action/*', 
                array( 
                    'language'   => 'en', 
                    'module'     => 'default', 
                    'controller' => 'index', 
                    'action'     => 'index' 
                ) 
            ); 
			
$route2 = new Zend_Controller_Router_Route(
			':language/content/add/:contenttype',
				array(
					'module'	 => 'default',
					'controller' => 'content',
					'action'	 => 'add'
				)
			);
            
$route_view = new Zend_Controller_Router_Route(
			':language/content/view2/:content_type/:content_id',
				array(
					'module'	 => 'default',
					'controller' => 'content',
					'action'	 => 'view2'
				)
			);
			
$route_shortview = new Zend_Controller_Router_Route(
			':language/view/:content_id',
				array(
					'module'	 => 'default',
					'controller' => 'view',
					'action'	 => 'index'
				)
			);
            
$route_content_publish = new Zend_Controller_Router_Route(
			':language/content/publish/:content_id',
				array(
					'module'	 => 'default',
					'controller' => 'content',
					'action'	 => 'publish'
				)
			);

$route_content_remove = new Zend_Controller_Router_Route(
			':language/content/remove/:content_id',
				array(
					'module'	 => 'default',
					'controller' => 'content',
					'action'	 => 'remove'
				)
			);
            
$route_content_edit = new Zend_Controller_Router_Route(
			':language/content/edit/:content_id',
				array(
					'module'	 => 'default',
					'controller' => 'content',
					'action'	 => 'edit'
				)
			);
            
$route_privmsg_send = new Zend_Controller_Router_Route(
			':language/privmsg/send/:username',
				array(
					'module'	 => 'default',
					'controller' => 'privmsg',
					'action'	 => 'send'
				)
			);
			

$router->addRoute('lang_default', $route);
$router->addRoute('content_type', $route2);
$router->addRoute('content_view', $route_view);
$router->addRoute('content_shortview', $route_shortview);
$router->addRoute('content_publish', $route_content_publish);
$router->addRoute('content_remove', $route_content_remove);
$router->addRoute('content_edit', $route_content_edit);
$router->addRoute('privmsg_send', $route_privmsg_send);

/*
//  Router config
$routerConfig = new Zend_Config_Xml(APPLICATION_PATH . '/config/routes.xml', 'staging');
// Set config
$router->addConfig($routerConfig);
*/

Zend_Controller_Front::getInstance()->registerPlugin(new Oibs_Controller_Plugin_Language());
Zend_Controller_Front::getInstance()->registerPlugin(new Oibs_Controller_Plugin_CleanQuery());

// Step 5: CLEANUP - Remove items from global scope.
// This will clear all our local boostrap variables from the global scope of 
// this script (and any scripts that called bootstrap).  This will enforce 
// object retrieval through the applications's registry.
unset($frontController, $view, $configuration, $dbAdapter, $registry, $db);

?>