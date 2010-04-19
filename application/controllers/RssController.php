<?php
/**
 *  RssController -> Controller for generating RSS feeds
 *
 *     Copyright (c) <2009>, Jaakko Paukamainen <jaakko.paukamainen@student.samk.fi>
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
 * License text found in /license/
 */

/**
 *  RssController - class
 *
 *  @package     controllers
 *  @author      Jaakko Paukamainen
 *  @copyright   2009 Jaakko Paukamainen
 *  @license     GPL v2
 *  @version     1.0
 */ 
class RssController extends Oibs_Controller_CustomController
{

    /**
    *    init
    *    
    *    Initialization of account controller.
    *
    */
    public function init()
    {
        parent::init();
        $this->view->title = 'rss-title';
    } // end of init()
    
    /**
    *    indexAction
    *    
    *    Displays the list of available RSS feeds
    *
    */
    public function indexAction()
    {	
		// Make baseurl absolute URL
    	$absoluteBaseUrl = strtolower(trim(array_shift(explode('/', $_SERVER['SERVER_PROTOCOL'])))) . 
    						'://' . $_SERVER['HTTP_HOST'];
		
    	// Does this qualify as an alternative solution to building an absolute baseurl?
    	// It works for me OK. -jaakkop
    	//
    	// $absoluteBaseUrl = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
    	//
		$this->view->absoluteBaseUrl = $absoluteBaseUrl;
    } // end of indexAction()
    
    /**
    *    generateAction
    *    
    *    Generate RSS feeds
    *
    *   @param  type    string      (optional) Content type ('problem' / 'finfo' / 'idea' / 'all'), 'all' by default
    *   @param  count   integer     (optional) How many items to generate
    *   
    */
    public function generateAction()
    {
    	// Set an empty layout for view
		$this->_helper->layout()->setLayout('empty');

		// Make baseurl absolute URL
		$absoluteBaseUrl = strtolower(trim(array_shift(explode('/', $_SERVER['SERVER_PROTOCOL'])))) . 
    						'://' . $_SERVER['HTTP_HOST'] . Zend_Controller_Front::getInstance()->getBaseUrl();
		$this->view->absoluteBaseUrl = $absoluteBaseUrl;
		
		// Get parameters
		$params = $this->getRequest()->getParams();
		
        // Get content type
        $cty = isset($params['type']) ? $params['type'] : 'all';
        
        // Get number of items
        $count = isset($params['count']) ? $params['count'] : 10;
    	
        // Set array for content data
        $data = array();
        
        // Get recent content by type
        $content = new Default_Model_Content();
        $data = $content->listRecent($cty, 1, $count, null, $this->view->language, null);
        
        // Set to view      
        $this->view->contentData = $data;
		
    } // end of indexAction()
} // end of class