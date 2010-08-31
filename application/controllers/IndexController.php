<?php
/**
 *  IndexController -> main pages
 *
* 	Copyright (c) <2008>, Matti S�rkikoski <matti.sarkikoski@cs.tamk.fi>
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
 * License text found in /license/
 */
/**
 *  IndexController - class
 *
 *  @package 	controllers
 *  @author 	Matti S�rkikoski & Jani Palovuori
 *  @copyright 	2008 Matti S�rkikoski & Jani Palovuori
 *  @license 	GPL v2
 *  @version 	1.0
 */
class IndexController extends Oibs_Controller_CustomController
//class IndexController extends Zend_Controller_Action
{
	function init()
	{
		parent::init();
	}

	/**
	 *	Show mainpage and list newest and most viewed ideas and problems
	 */
    function indexAction()
    {
    	// Variable for number recent campaigns to be sent to view
    	$recentCampaignsCount = 0;
    	
		$this->view->title = "index-home";
        
        // Get cache from registry
        $cache = Zend_Registry::get('cache');
        
        // $contentTypesModel = new Default_Model_ContentTypes();
        // $userModel = new Default_Model_User();
        
        // Load recent posts from cache
        $cachePosts = 'IndexPosts_' . $this->view->language;
        
        if(!$result = $cache->load($cachePosts)) {
            $contentModel = new Default_Model_Content();
            $contentHasTagModel = new Default_Model_ContentHasTag();
            
            // get data
            //($cty = 'all', $page = 1, $count = -1, $order = 'created', $lang = 'en', $ind = 0)
            $recentposts_raw = $contentModel->listRecent(
                'all', 12, -1, 'created', $this->view->language, -1
            );
            
            $recentposts = array();
            
            $i = 0;
            // gather data for recent posts
            foreach ($recentposts_raw as $post) {
                $recentposts[$i] = $post;
                $recentposts[$i]['tags'] = $contentHasTagModel->getContentTags(
                    $post['id_cnt']
                );
                
                $i++;
            }
            
            // Save recent posts data to cache
            $cache->save($recentposts, $cachePosts);          
        } else {
            $recentposts = $result;
        }
        
        // Load most popular tags from cache
        if(!$result = $cache->load('IndexTags')) {
            $tagsModel = new Default_Model_Tags();
            $tags = $tagsModel->getPopular(20);
            
            /*
            // resize tags
            foreach ($tags as $k => $tag) {
                $size = round(50 + ($tag['count'] * 30));
                if ($size > 300) {
                    $size = 300;
                }
                $tags[$k]['tag_size'] = $size;
            }
            */
            
            // Action helper for tags
            $tags = $this->_helper->tagsizes->tagCalc($tags);
            
            
            // Action helper for define is tag running number divisible by two
            $tags = $this->_helper->tagsizes->isTagDivisibleByTwo($tags);
            
            // Save most popular tags data to cache
            $cache->save($tags, 'IndexTags');
        } else {
            $tags = $result;
        }
        
        // Laod most active users from cache
        if(!$result = $cache->load('IndexUsers')) {
            $contentHasUserModel = new Default_Model_ContentHasUser();        
            $activeusers = $contentHasUserModel->getMostActive(10);
            
            // Save most active users data to cache
            $cache->save($activeusers, 'IndexUsers');
        } else {
            $activeusers = $result;
        }
        
        // inject data to view
        if (isset($recentposts)) {
            $this->view->recentposts = $recentposts;
        } else {
            $this->view->recentposts = '';
        }

        // Get recent campaigns
        $grpmodel = new Default_Model_Groups();
        $campaignModel = new Default_Model_Campaigns();
    	$recentcampaigns = $campaignModel->getRecent(5);
        // If you find (time to think of) a better way to do this, be my guest.
        $cmps_new = array();
        foreach ($recentcampaigns as $cmp) {
            $grp = $grpmodel->getGroupData($cmp['id_grp_cmp']);
            $cmp['group_name_grp'] = $grp['group_name_grp'];
            $cmps_new[] = $cmp;
        }

        // Get recent groups
        $grps = $grpmodel->getRecent(5);
        $grps_new = array();
        $grpadm = new Default_Model_GroupAdmins();
        foreach ($grps as $grp) {
            $adm = $grpadm->getGroupAdmins($grp['id_grp']);
            $grp['id_admin'] = $adm[0]['id_usr'];
            $grp['login_name_admin'] = $adm[0]['login_name_usr'];
            $grps_new[] = $grp;
        }

        $this->view->campaigns = $cmps_new;
        $this->view->groups = $grps_new;
        $this->view->poptags = $tags;
        $this->view->activeusers = $activeusers;
        $this->view->isLoggedIn = Zend_Auth::getInstance()->hasIdentity();
        $this->view->recentCampaignsCount = $recentCampaignsCount;        
    }
}
