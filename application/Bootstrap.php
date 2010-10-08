<?php
/**
 *  Bootstrap -> 
 *
 *
 *  This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License 
 *  as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied  
 *  warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for  
 *  more details.
 * 
 *  You should have received a copy of the GNU General Public License along with this program; if not, write to the Free 
 *  Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 *
 *  License text found in /license/
 */
 
/**
 *  Bootstrap - class
 *
 *  @package        bootstrap
 *  @author         
 *  @copyright      
 *  @license        GPL v2
 *  @version        1.0
 */
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    /**
    *   The wonderful autoloader!
    *   Death to procedural jargon-dargon bootstrappies!
    *
    */
    protected function _initAutoload()
    {
        $autoloader = new Zend_Application_Module_Autoloader(array(
            'namespace' => 'Default',
            'basePath'  => dirname(__FILE__),
        ));
        
        return $autoloader;
    }
    
    /**
    *
    *
    */
    protected function _initConfiguration()  
    {  
        $config = new Zend_Config_Ini(  
            APPLICATION_PATH . '/../config/config.ini',
            APPLICATION_ENV
        );
        
        Zend_Registry::set('config', $config);
        
        return $config;  
    }
    
    /**
    *
    *
    */
    protected function _initFrontController()
    {
        $frontController = Zend_Controller_Front::getInstance();  
        $frontController->throwExceptions(false);
        $frontController->setControllerDirectory(  
            APPLICATION_PATH . '/controllers'  
        ); 
        
        // getContainer() returns the local registry 
        $frontController->setParam('registry', $this->getContainer());
        $frontController->setParam('env', APPLICATION_ENV);
        
        return $frontController;
    }
    
    /**
    *   taking routes into use, check configs/routes.ini for more
    *   sure beats the variable injection, no? :)
    *
    */
	protected function _initRoutes()
    {
        $frontController = $this->getResource('frontController');
        
    	$config = new Zend_Config_Ini (
    		APPLICATION_PATH . '/config/routes.ini', 'general'
    	);
        
    	$router = $frontController->getRouter();
    	$router->addConfig( $config, 'routes' );
    }
    
    /**
    *   the doctype is now also declared in the bootstrap...
    *
    */
    protected function _initView()
    {
        //$this->bootstrap('view');
        //$view = $this->getResource('view');
        //$view->doctype('XHTML11');
        
        $view = new Zend_View();
        $view->setEncoding('UTF-8');
        $view->doctype('XHTML11');
        //$view->setEscape('htmlentities');
        return $view;
    }
    
    /**
    *   _initPlugins
    *
    *   Init plugins
    */
    protected function _initPlugins()
    {
        $frontController = $this->getResource('frontController');
    
        $frontController->registerPlugin(new Oibs_Controller_Plugin_Language());
        $frontController->registerPlugin(new Oibs_Controller_Plugin_CleanQuery());
    }
    
    /**
    *
    *
    *
    */
    protected function _initLogger()
    {
        $config = $this->getResource('configuration');
        
        $logArray = array();  
        
        if(isset($config->log)) {
            foreach($config->log as $name => $log) {
                if((bool)$log->enabled) {
                    $logArray[$name] = 
                        new Zend_Log(new Zend_Log_Writer_Stream($log->path));
                }
            }        
        }
        
        Zend_Registry::set('logs', $logArray);
    }
    
    /**
    *
    *
    *
    */
    protected function _initCache()
    {
        if(true) {
            // Set lifetime to 2 hours
            $frontend = array('lifetime' => 7200,
                              'automatic_serialization' => true
                              );
            
            $backend = array('cache_dir' => '../tmp/',);
            
            $cache = Zend_Cache::factory('core',
                                         'File',
                                         $frontend,
                                         $backend
                                         );
                                         
            Zend_Registry::set('cache', $cache);
            
            // cache with short lifetime
            $frontendShort = array('lifetime' => 600,
                              'automatic_serialization' => true
                              );
            
            $backendShort = array('cache_dir' => '../tmp/',);
            
            $sCache = Zend_Cache::factory('core',
                                         'File',
                                         $frontendShort,
                                         $backendShort
                                         );
                                         
            Zend_Registry::set('short_cache', $sCache);
            Zend_Db_Table_Abstract::setDefaultMetadataCache($cache);
            
        }
    }
    
    protected function _initHelpers()
    {
        Zend_Controller_Action_HelperBroker::addPath(
            APPLICATION_PATH .'/controllers/helpers');
    }
}