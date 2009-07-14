<?php
/**
 *  	index.php Zend Framework bootstrap file for oibs
 *
* 	Copyright (c) <2008>, Matti Särkikoski <matti.sarkikoski@cs.tamk.fi>
* 	Copyright (c) <2008>, Jani Palovuori <jani.palovuori@cs.tamk.fi>
*
* This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License 
* as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
* 
* This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied 
* warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for 
* more details.
* 
* You should have received a copy of the GNU General Public License along with this program; if not, write to the Free 
* Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
*
* License found in /license/
 */

/**
 *  index.php
 *
 *  @package 	public_html
 *  @author 	Matti Särkikoski & Jani Palovuori
 *  @copyright 	2008 Matti Särkikoski & Jani Palovuori
 *  @license 	GPL v2
 *  @version 	1.0
 */ 
  
error_reporting(E_ALL|E_STRICT);
ini_set('display_errors', 1);
date_default_timezone_set('Europe/Helsinki');

// directory setup and class loading
define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application/'));
set_include_path(APPLICATION_PATH . PATH_SEPARATOR . '../library/' . PATH_SEPARATOR . get_include_path());
	  
require_once "Zend/Loader.php";
Zend_Loader::registerAutoload();

/*
// load configuration
$config = new Zend_Config_Ini('../config/config.ini', 'general');
$registry = Zend_Registry::getInstance();
$registry->set('config', $config);


// setup database
$pdoParams = array(
    PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true
);
$params = array(
    'host'           => $config->db->host,
    'username'       => $config->db->username,
    'password'       => $config->db->password,
    'dbname'         => $config->db->dbname,
    'driver_options' => $pdoParams
);

$db = Zend_Db::factory($config->db->adapter, $params);
Zend_Db_Table::setDefaultAdapter($db);
Zend_Registry::set('db',$db);

// setup application authenticcation 
$auth = Zend_Auth::getInstance();
$auth->setStorage(new Zend_Auth_Storage_Session());

// create the application logger
$logger = new Zend_Log(new Zend_Log_Writer_Stream($config->logging->file));
Zend_Registry::set('logger', $logger);
*/


//try
//{
	require '../application/bootstrap.php';
//} catch(Exception $exception)
/*{
	echo '<html><body><center>'
		 .'An exception occured while bootstrapping the application.';
	if(defined('APPLICATION_ENVIRONMENT') && APPLICATION_ENVIRONMENT != 'production')
	{
		echo '<br /><br />'.$exception->getMessage().'<br />'
			 .'<div align="left">Stack Trace:'
			 .'<pre>'.$exception->getTraceAsString().'</pre></div>';
	}
	echo '</center></body></html>';
	exit(1);
}
*/

/*



// setup controller
$frontController = Zend_Controller_Front::getInstance();
$frontController->throwExceptions(true);
$frontController->setControllerDirectory($config->path->controllers);
$frontController->registerPlugin(new CustomControllerAclManager($auth));
$frontController->registerPlugin(new languagesPlugin());

Zend_Layout::startMvc(array('layoutPath'=> $config->path->layouts));
Zend_Layout::getMvcInstance()->getView()
	->doctype(Zend_View_Helper_Doctype::XHTML1_STRICT);

// dynamic route for viewing problems
$route = new Zend_Controller_Router_Route('problems/view/:id',
                                          array('controller' => 'problems',
                                                'action' => 'view')
);

$frontController->getRouter()->addRoute('view_problem', $route);

// dynamic route for viewing futureinfo
$route2 = new Zend_Controller_Router_Route('futureinfo/view/:id',
                                          array('controller' => 'futureinfo',
                                                'action' => 'view')
);

$frontController->getRouter()->addRoute('view_futureinfo', $route2);

// dynamic route for viewing stuff
$route3 = new Zend_Controller_Router_Route('view/:contenttype/:id',
                                          array('controller' => 'view',
                                                'action' => 'index')
);

$frontController->getRouter()->addRoute('view_futureinfo', $route3);
*/

// run!
//$frontController->dispatch();
Zend_Controller_Front::getInstance()->dispatch();