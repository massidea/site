<?php
/**
 * VoiceController
 *
 * Copyright (c) <2010>, Iiro Uusitalo <iiro.uusitalo [at] samk.fi>
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
 *  IndexController - class
 *
 *  @package    controllers
 *  @author     Iiro Uusitalo
 *  @copyright  2010 Iiro Uusitalo
 *  @license    GPL v2
 *  @version    0.1 b
 */
class VoiceController extends Oibs_Controller_CustomController
{
    public function init()
    {
        parent::init();
        
        $this->view->title = 'voice-title';
    }

    /**
     * IndexControl
     */
   /* function indexAction() {
    
  	}

	/**
	*	generateAction
	*
	*	Get data from database
	*
	*/
	function generateAction() {
		//Copy&Paste from RssController \o/ :D
		
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
        
        // Get tags for contents
        $tags_model = new Default_Model_ContentHasTag();
        $usersid_model = new Default_Model_ContentHasUser();
        $users_model = new Default_Model_User();
        $i = 0;
        foreach ($data as $dataRow)
        {
        	$tags = $tags_model->getContentTags($dataRow['id_cnt']);
        	$userId = $usersid_model->getContentOwners($dataRow['id_cnt']);

   			$user = $users_model->getSimpleUserDataById($userId['id_usr']);
   			$data[$i]['author'] = $user['login_name_usr'];
   			
        	$tagNames = array();
        	foreach ($tags as $tag)
        	{
        		$tagNames[] = $tag['name_tag'];
        	}
        	$data[$i]['tags'] = join(", ", $tagNames);
        	$i++;
        }
        
        // Set to view      
        $this->view->contentData = $data;
	}
}