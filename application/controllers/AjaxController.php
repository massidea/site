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
 *  @author      Jaakko Paukamainen & Jari Korpela
 *  @copyright   2010 Jaakko Paukamainen 
 *  @license     GPL v2
 *  @version     1.0
 */
class AjaxController extends Oibs_Controller_CustomController
{
 	public function init()
 	{
 		parent::init();

		// For debugging purposes set to true
		$this->debug = true;
		$ajaxRequest = $this->debug ? true : $this->_request->isXmlHttpRequest();
		
		// If requested via ajax
		if($ajaxRequest)
		{
			// Disable layout to be rendered
			$this->_helper->layout->disableLayout();
			
			// Set variables available for access in all actions in this class.
			$this->params = $this->getRequest()->getParams(); 
	        $this->id = isset($this->params['id']) ? (int)$this->params['id'] : 0;
		}
		// if not
		else
		{
			echo "No go :(";
			die;
		}
 	}
 	
 	function indexAction()
 	{
		echo "Move along people, there's nothing to see here! <br />";
 	}
	
    function getrecentcampaignsAction()
    {
        $offset = isset($this->params['offset']) ? $this->params['offset'] : 0;

        $grpmodel = new Default_Model_Groups();
        $campaignModel = new Default_Model_Campaigns();

        // If you find (time to think of) a better way to do this, be my guest.
    	$recentcampaigns = $campaignModel->getRecentFromOffset($offset, 10);
        $cmps_new = array();
        foreach ($recentcampaigns as $cmp) {
            $grp = $grpmodel->getGroupData($cmp['id_grp_cmp']);
            $cmp['group_name_grp'] = $grp['group_name_grp'];
            $cmps_new[] = $cmp;
        }

    	$this->view->recentcampaigns = $cmps_new;
    }

	function getrecentcontentAction()
	{
		// Get requests
        $offset = isset($this->params['offset']) ? $this->params['offset'] : 0;
        $contentType = isset($this->params['type']) ? $this->params['type'] : 'all';

        // Get models
    	$contentModel = new Default_Model_Content();
    	$contentHasTagModel = new Default_Model_ContentHasTag();
	
    	// Get recent post data
    	$recentposts_raw = $contentModel->listRecent(
			$contentType, $offset, 15, 'created', $this->view->language, -1
    	);

    	$recentposts = array();

    	// Gather data for recent posts
    	$i = 0;
    	foreach ($recentposts_raw as $post) {
	    	$tags = $contentHasTagModel->getContentTags($post['id_cnt']);
	    	$this->gtranslate->setLangFrom($post['language_cnt']);
	    	$translang = $this->gtranslate->getLangPair();

	    	$recentposts[$i]['original'] = $post;
	    	$recentposts[$i]['translated'] = $this->gtranslate->translateContent($post);
	    	$recentposts[$i]['original']['tags'] = $tags;
	    	$recentposts[$i]['translated']['tags'] = $tags;
	    	$recentposts[$i]['original']['translang'] = $translang;
	    	$recentposts[$i]['translated']['translang'] = $translang;
	    	
	    	$i++;
    	}

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
	
	public function getuserlocationsAction() {

		$output = "";
		// Get requests
		$params = $this->getRequest()->getParams();
		$search = isset($params['search']) ? $params['search'] : null;
		//if(strlen($search) <= 1) $search = null;

		if($search) {
			// Get cache from registry
			$cache = Zend_Registry::get('cache');

			// Load user locations from cache
			if(!$resultList = $cache->load('UserLocationsList')) {
				$userModel = new Default_Model_User();
				$locations = $userModel->getAllUsersLocations();
				$cache->save($locations, 'UserLocationsList');

			} else {
				$locations = $resultList;
			}
			
			if($search == "cities") {
				$output = json_encode($locations['cities']);
			}
			elseif($search == "countries") {
				$output = json_encode($locations['countries']);
			}
		}
		$this->view->output = $output;
	}
	
	public function getusercontentsAction() {
		$output = "";
		// Get requests
		$params = $this->getRequest()->getParams();
		$search = isset($params['search']) ? $params['search'] : null;
		$search = (int)$search;
		if(is_int($search) && $search != 0) {
			
			// Get cache from registry
			$cache = Zend_Registry::get('cache');

			// Load user locations from cache
			if(!$resultList = $cache->load('UserContentsList_'.$search)) {
				$userModel = new Default_Model_User();
				$contentList = $userModel->getUserContentList($search);
				$cache->save($contentList, 'UserContentsList_'.$search);

			} else {
				$contentList = $resultList;
			}
			$output = json_encode($contentList);
			//$output = $contentList;
		}
		$this->view->output = $output;
	}
}