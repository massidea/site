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
	    	$this->gtranslate->setLangFrom($post['language_cnt']);

	    	$tags = $contentHasTagModel->getContentTags($post['id_cnt']);
	    	
	    	// Action helper for define is tag running number divisible by two
			$tags = $this->_helper->tagsizes->isTagDivisibleByTwo($tags);
		    $translatedtags = $this->gtranslate->translateTags($tags);
			
	    	$translang = $this->gtranslate->getLangPair();

	    	$recentposts[$i]['original'] = $post;
	    	$recentposts[$i]['translated'] = $this->gtranslate->translateContent($post);
	    	$recentposts[$i]['original']['tags'] = $tags;
	    	$recentposts[$i]['translated']['tags'] = $translatedtags;
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
		
	public function getusercontentsAction() {
		$output = "";
		// Get requests

		$params = $this->getRequest()->getParams();
		$userId = isset($params['search']) ? $params['search'] : null;
		$start = isset($params['start']) ? $params['start'] : null;
		$cache = Zend_Registry::get('cache');

		if(is_numeric($userId) && is_numeric($start)) {

			// Load user locations from cache
			if($resultList = $cache->load('UserContentsList_'.$userId)) {
				$newContents = array();
				$userModel = new Default_Model_User();
				for($i = $start; $i < $start +3; $i++) {
					if($resultList[$i])
						$newContents[] = $resultList[$i];
				}
				if(!sizeof($newContents) == 0)
					$contentList = $userModel->getUserContentList($newContents,3);
				else $contentList = array();
			}
			$output = json_encode($contentList);
	
		}

		elseif(is_numeric($userId) && !$start) {
			
			// Load user locations from cache
			if($resultList = $cache->load('UserContentsList_'.$userId)) {
				$userModel = new Default_Model_User();
				$contentList = $userModel->getWholeUserContentList($userId, $resultList);
			}
			$output = json_encode($contentList);
		}
		
		$this->view->output = $output;
	}
	
	public function getuserstatisticsAction() {
		$output = "";

		$params = $this->getRequest()->getParams();
		$userId = isset($params['user']) ? $params['user'] : null;
		$search = isset($params['search']) ? $params['search'] : null;
		$cache = Zend_Registry::get('cache');
		
		if(is_numeric($userId) && $search == "graphs") {
			if($resultList = $cache->load('UserContentsList_'.$userId)) {
				$statisticsList = array("contentTypes");
				$userModel = new Default_Model_User();
				$contentTypes = $userModel->getUserStatistics($userId,$resultList,$statisticsList);
				$output = json_encode($contentTypes);
			}
		}
		$this->view->output = $output;
	}
	
	public function getuserlisttopAction() {
		$auth = Zend_Auth::getInstance();
		if($auth->hasIdentity()) $userid = $auth->getIdentity()->user_id;
			
		$params = $this->getRequest()->getParams();
		$userModel = new Default_Model_User();
		$userIds = $userModel->sortAndFilterUsers($params,null,null);
		
		$top = new Oibs_Controller_Plugin_TopList();
		$top->setUserIdList($userIds)
			->setTop("Count")
			->setTop("View")
			->setTop("Popularity")
			->setTop("Rating")
			->setTop("Comment")
			->addTitleLinks()
			->addTitles()
			->addDescriptions()
			;
		
		$topList = $top->getTopList();
		
		if($userid) $userTop = $top->addUser($userid)->getAddedUser();
		$topListMerge = array();

		if($userid) {
			foreach($topList as $key1 => $list1) {
				foreach($userTop as $key2 => $top1) {
					if($key1 == $key2) {
						$topListMerge[$key1] = array_merge($list1,array('addedUsers' => $top1));
						continue 2;
					}
				}
				$topListMerge[] = $list1;
			}
			$topList = $topListMerge;
		}
		
		$topListCountries = new Oibs_Controller_Plugin_TopList();
	        $topListCountries->setUserIdList($userIds)
	        	->fetchUserCountries()
	        	->setCountryTop("Count")
	        	->setCountryTop("View")
				->setCountryTop("Popularity")
				->setCountryTop("Rating")
				->setCountryTop("Comment")
				->addTitleLinks()
				->addTitles()
				->addDescriptions()
				;
			
			$topCountry = $topListCountries->getCountryGroups();
		
		
		$topListBoxes = array(
        	'Users' => $topList,
			'Countries' => $topCountry
        );
		
		$this->view->topListBoxes = $topListBoxes;
	}
	
	public function morefromuserAction() {
		// Get content owner data
        $userModel = new Default_Model_User();
        $limit = 5;
        $more = false;
        if (null != $this->params['more']) {
        	$limit = 100;
        	$more = true;
        }
		$rawcontents = $userModel->getUserContent($this->params['id_usr'], $this->params['id_cnt'], $limit);
		foreach($rawcontents as $rawcnt)
		{
			$this->gtranslate->setLangFrom($rawcnt['language_cnt']);
			$contents[] = $this->gtranslate->translateContent($rawcnt);
		}
		$this->view->more = $more;
		$this->view->contents = $contents;
	}
	
	public function relatedcontentAction() {
        // Get related contents
        $contentModel = new Default_Model_Content();
        $limit = 5;
        $more = false;
        if (null != $this->params['more']) { 
        	$limit = 100;
        	$more = true;
        }
        $rawcontents = $contentModel->getRelatedContents($this->params['id_cnt'], $limit);
        foreach($rawcontents as $rawcnt)
        {
			$this->gtranslate->setLangFrom($rawcnt['language_cnt']);
			$contents[] = $this->gtranslate->translateContent($rawcnt);
        }
        $this->view->id=$this->params['id_cnt'];
        $this->view->more = $more;
        $this->view->relatedContents = $contents;
	}
	
	public function contentratingAction() {
        // Get authentication
        $auth = Zend_Auth::getInstance();
        
        // Get content rating
        $contentRatingsModel = new Default_Model_ContentRatings();
        
		$rate = $this->params['rate'];
		if ($auth->hasIdentity())
		{
			if($rate == 1 || $rate == -1)
			{
	            $contentRatingsModel->addRating($this->params['id_cnt'], $auth->getIdentity()->user_id, $rate);
			}
		}
		
        $rating = $contentRatingsModel->getPercentagesById($this->params['id_cnt']);
		$this->view->hasIdentity = $auth->hasIdentity();
		$this->view->rating = $rating;
	}
	
	public function contentfavouriteAction() {
        // Get authentication
        $auth = Zend_Auth::getInstance();
		
        // Get contents total favourites
        $userFavouritesModel = new Default_Model_UserHasFavourites();
        $totalFavourites = $userFavouritesModel->getUsersCountByFavouriteContent($id);
        $totalFavourites = $totalFavourites[0]['users_count_fvr'];
        $isFavourite = $userFavouritesModel->checkIfContentIsUsersFavourite($id,$auth->getIdentity()->user_id);

        /*
         * favouritemethod comes from parameters sent by
         * ajax function (ajaxLoad_favourite(method)) in index.phtml in /view/.
         * this function gets parameter "method" (add/remove) from onClick event that is in index.ajax.phtml.
         * if this onClick event is activated by clicking "heart" (icon_fav_on/off) icon in content view page,
         * it runs the ajaxLoad_favourite(method) function which sends parameter "favourite" (add/remove) to
         * this viewController which then handles the adding or removing the content from favourites.
         */
        if($favouriteMethod != "NONE" && $auth->hasIdentity()) {
        	$favouriteUserId = $auth->getIdentity()->user_id;
        	//If favourite method was "add", then add content to user favourites
        	if($favouriteMethod == "add" && !$isFavourite) 
        		{
        		if($userFavouritesModel->addContentToFavourites($id,$favouriteUserId)) {
        			$this->view->favouriteMethod = $favouriteMethod;
        		} else $this->flash('favourite-adding-failed',$baseUrl.'/en/msg');
        	} 
        	//If favourite method was "remove" then remove content from user favourites.
        	elseif ($favouriteMethod == "remove" && $isFavourite)
        		{
        		if($userFavouritesModel->removeUserFavouriteContent($id,$favouriteUserId)) {
        			$this->view->favouriteMethod = $favouriteMethod;
        		} else $this->flash('favourite-removing-failed',$baseUrl.'/en/msg');
        	} else unset($favouriteMethod);
        }
        
        $favourite = array(
        	'total_favourites' 	=> $totalFavourites,
        	'is_favourite'		=> $isFavourite,
        );
        
        $this->view->favourite = $favourite;
	}
	
	public function postcommentAction() {
		//$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$auth = Zend_Auth::getInstance();
		$user = $auth->getIdentity();
		//Zend_Debug::dump($this->params);
		$params = $this->params;

		if ($auth->hasIdentity() && null != $params['msg'] && null != $params['type'] && null != $params['parent'] && null != $params['id'] ) {
			$msg = $params['msg'];
			$parent = $params['parent'];
			$type = $params['type'];
			$id = $params['id'];
			
			$comments = new Oibs_Controller_Plugin_Comments($type, $id);
			$comments->addComment($user->user_id, $parent, $msg);
		}
	}
	
	public function getcommentsAction() {

		//$this->_helper->viewRenderer->setNoRender(true);
				
		$auth = Zend_Auth::getInstance();
		
		if ($auth->hasIdentity()) {
			$type = $this->params['type'];
			$id = $this->params['id'];
			
			$comments = new Oibs_Controller_Plugin_Comments($type, $id);
			$newComments = array();
			$newComments = $comments->getNewComments($auth->getIdentity()->user_id);
			
			if (count($newComments) != 0) {
				$this->view->comments = $newComments;
			}
		}
	}
	
	public function idlerefreshAction() {
		$this->_helper->viewRenderer->setNoRender(true);
		$auth = Zend_Auth::getInstance();

		if ($auth->hasIdentity()) {
			$this->setOnline($auth->getIdentity()->user_id, 2);
		}
	}
}
