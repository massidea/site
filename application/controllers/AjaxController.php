<?php
/**
 *  AjaxController -> 
 *
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
 *  AjaxController - class
 *
 *  @package     controllers
 *  @author      Jaakko Paukamainen  
 *  @copyright   2010 Jaakko Paukamainen 
 *  @license     GPL v2
 *  @version     1.0
 */
class AjaxController extends Oibs_Controller_CustomController
{
	public function init()
	{
		parent::init();
		$this->_helper->layout->disableLayout();
	}
	
	function indexAction()
	{
	}
	
	function getrecentcontentAction()
	{
	    
    	sleep(1);
        // Get requests
        $params = $this->getRequest()->getParams(); 
        $offset = isset($params['offset']) ? $params['offset'] : 0;
        //$check  = isset($params['check']) ? $params['check'] : 0;
        

	    	// Get cache from registry
	    	//$cache = Zend_Registry::get('cache');
	    	 
	    	// Load recent posts from cache
	    	//$cachePosts = 'IndexPosts_' . $this->view->language;
	
	    	//if(!$result = $cache->load($cachePosts)) {
	    	$contentModel = new Default_Model_Content();
	    	$contentHasTagModel = new Default_Model_ContentHasTag();
	
	    	// get data
	    	$recentposts_raw = $contentModel->listRecent(
				'all', $offset, 12, 'created', $this->view->language, -1
	    	);
	
	    	$recentposts = array();
	
	    	$i = 0;
	    	// gather data for recent posts
	    	foreach ($recentposts_raw as $post) {
		    	$recentposts[$i] = $post;
		    	$recentposts[$i]['tags'] = $contentHasTagModel->getContentTags($post['id_cnt']);
		    	$i++;
	    	}
	
	    	// Save recent posts data to cache
	    	//$cache->save($recentposts, $cachePosts);
	    	//} else {
	    		//$recentposts = $result;
	    	//}
	    	$this->view->recentposts = $recentposts;
	}
	
	function checkrecentcontentAction()
	{
        // Get cache from registry
        $cache = Zend_Registry::get('cache');
        
        // Load most popular tags from cache
        if(!$result = $cache->load('LatestPostHash')) {
        	$output = md5(time());
            $cache->save($output, 'LatestPostHash');
        } else {
            $output = $result;
        }

		$this->view->output = $output;
	}
}