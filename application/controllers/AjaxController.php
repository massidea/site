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
        $status = isset($this->params['status']) ? $this->params['status'] : 'active';

        $grpmodel = new Default_Model_Groups();
        $campaignModel = new Default_Model_Campaigns();

        // If you find (time to think of) a better way to do this, be my guest.
        if ($status === 'forthcoming') {
            $recentcampaigns = $campaignModel->getRecentForthcomingFromOffset($offset, 10);

        } else if ($status === 'ended') {
            $recentcampaigns = $campaignModel->getRecentEndedFromOffset($offset, 10);
        } else {
            $recentcampaigns = $campaignModel->getRecentFromOffset($offset, 10);
        }

        $cmps_new = array();
        foreach ($recentcampaigns as $cmp) {
            $grp = $grpmodel->getGroupData($cmp['id_grp_cmp']);
            $cmp['group_name_grp'] = $grp['group_name_grp'];
            $cmps_new[] = $cmp;
        }

    	$this->view->recentcampaigns = $cmps_new;
    }

    function getrecentgroupsAction()
    {
        $offset = isset($this->params['offset']) ? $this->params['offset'] : 0;

        $grpmodel = new Default_Model_Groups();
        $grpadm = new Default_Model_GroupAdmins();
        $usrHasGrp = new Default_Model_UserHasGroup();
        $cmpmodel = new Default_Model_Campaigns();

        $grps = $grpmodel->getRecentFromOffset($offset, 10);
        $grps_new = array();
        foreach ($grps as $grp) {
            $adm = $grpadm->getGroupAdmins($grp['id_grp']);
            $grp['id_admin'] = $adm[0]['id_usr'];
            $grp['login_name_admin'] = $adm[0]['login_name_usr'];
            $grp['campaign_count'] = count($cmpmodel->getCampaignsByGroup($grp['id_grp']));
            $grp['member_count'] = count($usrHasGrp->getAllUsersInGroup($grp['id_grp']));
            $grps_new[] = $grp;
        }

    	$this->view->recentgroups = $grps_new;
    }

	function getrecentcontentAction()
	{

		// Get requests
        $offset = isset($this->params['offset']) ? $this->params['offset'] :0;
        $contentType = isset($this->params['type']) ? $this->params['type'] : 'all';

        // Get models
    	$contentModel = new Default_Model_Content();
    	$contentHasTagModel = new Default_Model_ContentHasTag();

    	// Get recent post data
    	$recentposts_raw = $contentModel->listRecent(
			$contentType, $offset, 3, 'random', $this->view->language, -1
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
					if(isset($resultList[$i]))
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

		$userid = 0;
		if($auth->hasIdentity()) $userid = $auth->getIdentity()->user_id;

		$params = $this->getRequest()->getParams();

		$userModel = new Default_Model_User();
		$userIds = $userModel->sortAndFilterUsers($params,null,null);
		if(!$userIds) die;

		$serializedParams = serialize($params);
		$cacheFile = md5($serializedParams);
		$cache = Zend_Registry::get('short_cache');

		if(!$cacheResult = $cache->load('UserTopList_'.$cacheFile)) {
			$topListUsers = new Oibs_Controller_Plugin_Toplist_Users();
			$topListUsers->setUserIdList($userIds)
				->autoSet();
				;

			$topListCountries = new Oibs_Controller_Plugin_Toplist_Countries();
	        $topListCountries->setUserIdList($userIds)
	        	->fetchUserCountries()
	        	->setTopAmount()
	        	->autoSet()
				;

			$topListGroups = new Oibs_Controller_Plugin_Toplist_Groups();
			$topListGroups->setUserIdList($userIds)
							->fetchUsersInGroups()
							->setTopAmount()
							->autoSet()
							;
			$topListCities = new Oibs_Controller_Plugin_Toplist_Cities();
			$topListCities->setUserIdList($userIds)
						->fetchUsersWithCity()
						->setTopAmount()
						->autoSet()
						;
			$topListClasses = array(
		        	'Users' => $topListUsers,
		       		'Groups' => $topListGroups,
		       		'Cities' => $topListCities,
		        	'Countries' => $topListCountries,
		        );
		    $cache->save($topListClasses, 'UserTopList_'.$cacheFile);
		}
		else {
			$topListClasses = $cacheResult;
		}

		$topListUsers = $topListClasses['Users'];
        $topListCountries = $topListClasses['Countries'];
        $topListCities = $topListClasses['Cities'];
        $topListGroups = $topListClasses['Groups'];

		if($userid) $topListUsers->addUser($userid);
		$topList = $topListUsers->getTopList();

		if($userid) $topListCountries->addUser($userid);
		$topCountry = $topListCountries->getTopList();

		$topGroup = $topListGroups->getTopList();

		if($userid) $topListCities->addUser($userid);
		$topCity = $topListCities->getTopList();

		$topListBoxes = array(
        	'Users' => $topList,
			'Groups' => $topGroup,
			'Cities' => $topCity,
			'Countries' => $topCountry,
        );
        //print_r($topListBoxes);die;
		$this->view->topListBoxes = $topListBoxes;
	}

	public function morefromuserAction() {
		// Get content owner data
        $userModel = new Default_Model_User();
        $limit = 5;
        $more = false;
        if (isset($this->params['more'])) {
        	$limit = 100;
        	$more = true;
        }

        $contents = array();
		$rawcontents = $userModel->getUserContent($this->params['id_usr'], array('exclude' => $this->params['id_cnt'], 'limit' => $limit));
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
        if (isset($this->params['more'])) {
        	$limit = 100;
        	$more = true;
        }
        $rawcontents = $contentModel->getRelatedContents($this->params['id_cnt'], $limit);
        $contents = array();
        if ($rawcontents !== false) {
	        foreach($rawcontents as $rawcnt)
	        {
				$this->gtranslate->setLangFrom($rawcnt['language_cnt']);
				$contents[] = $this->gtranslate->translateContent($rawcnt);
	        }
        }
        $this->view->id=$this->params['id_cnt'];
        $this->view->more = $more;
        $this->view->relatedContents = $contents;
	}

	public function relatedcampaignsAction() {
		if (!isset($this->params['id_cnt'])) {
			echo "0";
			return false;
		}

		if (isset($this->params['more'])) {
			$limit = 100;
			$more = true;
		}

        $campaignHasContentModel = new Default_Model_CampaignHasContent();
        $campaigns = $campaignHasContentModel->getContentCampaigns($this->params['id_cnt']);
		$this->view->campaigns = $campaigns;
	}

	public function contentratingAction() {
        // Get authentication
        $auth = Zend_Auth::getInstance();
        $ident = $auth->getIdentity();
        if(isset($ident)) $userId = $ident->user_id;
        else $userId = 0;
        // Get content rating
        $contentRatingsModel = new Default_Model_ContentRatings();
        $contentModel = new Default_Model_Content();

        $userIsOwner = $contentModel->checkIfUserIsOwner($this->params['id_cnt'],$userId);

        if (isset($this->params['rate'])) {
			$rate = $this->params['rate'];
			if ($auth->hasIdentity() && !$userIsOwner)
			{
				if($rate == 1 || $rate == -1)
				{
		            $contentRatingsModel->addRating($this->params['id_cnt'], $userId, $rate);
		            $profileModel = new Default_Model_UserProfiles();
		            $profileModel->deleteNotificationCache($this->params['id_cnt']);
				}
			}
        }
        $rating = $contentRatingsModel->getPercentagesById($this->params['id_cnt']);
		$this->view->hasIdentity = $auth->hasIdentity();
		$this->view->userId = $userId;
		$this->view->userIsOwner = $userIsOwner;
		$this->view->rating = $rating;
	}

	public function contentfavouriteAction() {
        // Get authentication
        $auth = Zend_Auth::getInstance();
		$favouriteUserId = 0;
		if($auth->hasIdentity()) $favouriteUserId = $auth->getIdentity()->user_id;

        $params = $this->getRequest()->getParams();
		// get favourite method, "add" or "remove"
        $favouriteMethod = isset($params['method']) ? $params['method'] : "NONE";
        $id = isset($params['id_cnt']) ? $params['id_cnt'] : "0";
        // Get contents total favourites
        $userFavouritesModel = new Default_Model_UserHasFavourites();
        $contentModel = new Default_Model_Content();
        $totalFavourites = $userFavouritesModel->getUsersCountByFavouriteContent($id);
        $totalFavourites = $totalFavourites[0]['users_count_fvr'];
        $isFavourite = $userFavouritesModel->checkIfContentIsUsersFavourite($id,$favouriteUserId);
		$isOwner = $contentModel->checkIfUserIsOwner($id,$favouriteUserId);

        if($favouriteMethod != "NONE" && $auth->hasIdentity() && !$isOwner) {
        	//If favourite method was "add", then add content to user favourites
        	if($favouriteMethod == "add" && !$isFavourite)
        		{
        		if($userFavouritesModel->addContentToFavourites($id,$favouriteUserId)) {
        			$isFavourite = true;
        			$totalFavourites++;
        		} else $this->addFlashMessage('favourite-adding-failed',$baseUrl.'/en/msg');
        	}
        	//If favourite method was "remove" then remove content from user favourites.
        	elseif ($favouriteMethod == "remove" && $isFavourite)
        		{
        		if($userFavouritesModel->removeUserFavouriteContent($id,$favouriteUserId)) {
        			$isFavourite = false;
        			$totalFavourites--;
        		} else $this->addFlashMessage('favourite-removing-failed',$baseUrl.'/en/msg');
        	} else unset($favouriteMethod);
        }

        $favourite = array(
        	'total_favourites' 	=> $totalFavourites,
        	'is_favourite'		=> $isFavourite,
        );
        $thie->view->userid = $favouriteUserId;
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

			$profileModel = new Default_Model_UserProfiles();
		    $profileModel->deleteNotificationCache($id);
		}
	}

	public function getcommentsAction() {

		//$this->_helper->viewRenderer->setNoRender(true);

		$auth = Zend_Auth::getInstance();

		$user_id = ($auth->hasIdentity()) ? $auth->getIdentity()->user_id : "0";
		$type = $this->params['type'];
		$id = $this->params['id'];

		$comments = new Oibs_Controller_Plugin_Comments($type, $id);
		$newComments = array();
		$newComments = $comments->getNewComments($user_id);

		if (count($newComments) != 0) {
			$this->view->comments = $newComments;
		}
	}

	public function idlerefreshAction() {
		$this->_helper->viewRenderer->setNoRender(true);
		$auth = Zend_Auth::getInstance();

		if ($auth->hasIdentity()) {
			$this->setOnline($auth->getIdentity()->user_id, 2);
		}
	}

	public function readrssAction() {

		$this->_helper->viewRenderer->setNoRender(true);
		if (!isset($this->params['type']) || !isset($this->params['id'])) return;
		$reader = new Oibs_Controller_Plugin_RssReader();
		//$admin = groupadmins->userIsAdmin
		$data = $reader->read($this->params['id'], $this->params['type']);
		$auth = Zend_Auth::getInstance();

		$isAdmin = false;
		if ($auth->hasIdentity()) $isAdmin = $reader->isAdmin($auth->getIdentity()->user_id);

		//echo strlen(json_encode($data));
		//echo strlen($this->view->partial('partials/rssreader.phtml', array("data" => $data)));

		echo $this->view->partial('partials/rssreader.phtml', array("data" => $data, "admin" => $isAdmin, 'link' => $reader->getEditLink()));
	}

	public function validaterssAction() {
		$this->_helper->viewRenderer->setNoRender(true);
		$params = $this->getRequest()->getParams();
	   	try {
	    	Zend_Feed_Reader::import($params['url']);
	    	echo "1";
    	} catch (Exception $e) {
    		echo "0";
    	}

	}

	public function getnotificationsAction() {
		$favouritesModel = new Default_Model_UserHasFavourites();

		$auth = Zend_Auth::getInstance();
		$id_usr = 0;
		if ($auth->hasIdentity()) $id_usr = $auth->getIdentity()->user_id;


		//print_r($notifications);die;
		$ids = array();
		$total = 0;

		$cache = Zend_Registry::get('cache');

		if(!$cacheResult = $cache->load('Notifications_'.$id_usr)) {
			$notifications = $favouritesModel->getAllUpdatedContents($id_usr);
			$cache->save($notifications,'Notifications_'.$id_usr);
		}
		else {
			$notifications = $cacheResult;
		}
		//print_r($notifications);die;
		if($notifications) {
			foreach($notifications as $k => $notification) {
				foreach($notification as $l => $content) {
					$this->gtranslate->setLangFrom($content['original']['language_cnt']);
					$translang = $this->gtranslate->getLangPair();
					$notifications[$k][$l]['translated'] = $this->gtranslate->translateContent($content['original']);
					$notifications[$k][$l]['original']['translang'] = $translang;
			    	$notifications[$k][$l]['translated']['translang'] = $translang;
			    	$ids[] = $l;
			    	$total += $content['updates']['total'];
				}
			}
		}
		else $this->_helper->viewRenderer->setNoRender(true);

		$jsonIds = Zend_Json::encode($ids);

		$this->view->notifications = $notifications;
		$this->view->ids = $jsonIds;
		$this->view->total = $total;
	}

	/**
	 * To be deleted after first use.
	 */
	public function setdefaultfollowsAction() {
		$db = new Default_Model_UserProfiles();
		$db->setDefaultFollows();
		$this->_helper->viewRenderer->setNoRender(true);
	}
}
