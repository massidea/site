<?php
/**
 *  RssReader
 *
 *   Copyright (c) <2010>, Sami Suuriniemi <sami.suuriniemi@samk.fi>
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
 *  RssReader - class
 *
 *  @package    plugins
 *  @author     Sami Suuriniemi
 *  @copyright  2010 Sami Suuriniemi
 *  @license    GPL v2
 *  @version    1.0
 */ 
 
class Oibs_Controller_Plugin_RssReader {
	private $limit = 10;
	private $id;
	private $typename;
	private $admin = false;
	private $helper;
	
	public function __construct($id = null, $type = null) {
		    	$this->helper = new Zend_View_Helper_Url();
		if (null != $id && null != $type) {
			$this->id = $id;
			$this->type = $type;
			$pageTypeModel = new Default_Model_PageTypes();
			$this->typename = $pageTypeModel->getName($type);
		}
	}
	
	public function getTitle() {
		if (isset($this->typename)) {
			switch ($this->typename) {
				case "group": return $this->getGroupTitle(); break;
				case "account": return $this->getAccountTitle(); break;
				case "campaign": return $this->getCampaignTitle(); break;
				case "content": return $this->getContentTitle(); break;
			}
		}
		return null;
	}
	
    public function read($id, $type) {
    	
    	$rssModel = new Default_Model_RssFeeds();
    	$pageTypeModel = new Default_Model_PageTypes();

    	$type = $pageTypeModel->getId($type);
    	$this->type = $type;
    	if (null == $type) return false;
    	$this->typename = $pageTypeModel->getName($type);
    	$this->id = $id;
    	if (!$urls = $rssModel->getUrls($id, $type)) return false;
    	$feeds = array();
    	foreach($urls as $url) {
	    	try {
		    	$feed = Zend_Feed_Reader::import($url['url_rss']);
		    	$feeds[] = $feed;
	    	} catch (Exception $e) {
	    		echo "Error with feed";
	    	}
    	}
    	if (count($feeds) != 0) $data = $this->sortFeed($feeds);
    	else return false;
    	
    	return $data;
    }
    
    public function getEditLink() {
    	return $this->helper->url(array('controller' => 'rss',
    									'action' => 'editfeeds',
    									'type' => $this->type,
    									'id' => $this->id), 
    									'lang_default', true);
    }

    public function getLinkBack() {
		switch($this->typename) {
			case 'content': return $this->getContentUrl();
			case 'account': return $this->getAccountUrl();
			case 'group': return $this->getGroupUrl();
			case 'campaign': return $this->getCampaignUrl();
		}    	
    	
    }

    private function getCampaignUrl() {
    	$url =  $this->helper->url(array('language' => 'en',
    									'cmpid' => $this->id), 
    									"campaign_view", true);
    	return $url;
    }
    
    
    private function getGroupUrl() {
    	$url = $this->helper->url(array('language' => 'en',
    									'groupid' => $this->id),
    									'group_shortview', true);
    	return $url;
    }
    private function getAccountUrl() {
    	$url = $this->helper->url(array('user' => $this->id,
    									'controller' => 'account',
    									'action' => 'view'),
    									'lang_default', true);
    	return $url;
    }
    private function getContentUrl() {
    	$url = $this->helper->url(array('language' => 'en',
    									'content_id' => $this->id),
    									'content_shortview', true);
    	return $url;
    }
    
    private function sortFeed($channels) {
    	$feedData = array();
    	foreach ($channels as $channel) {
	
	    	$feedData['titles'][] = $channel->getTitle();
	    	$i = 0;
	    	foreach ($channel as $item) {
	    		$tempItem = array();
	    		$tempItem['title'] = $item->getTitle();
	    		$tempItem['link'] =  $item->getLink();
				$tempItem['desc'] = $item->getContent();
				$tempItem['date'] = $item->getDateCreated()->get();
	    		$feedData['items'][] = $tempItem;
	    		$i++;	
        		if ($i >= $this->limit/count($channels)) break;

	    	}
    	}
		usort($feedData['items'], array('Oibs_Controller_Plugin_RssReader', 'cmp'));
	    return $feedData;
    }
    
	private function cmp($a, $b) {
    	if ($a['date'] == $b['date']) return 0;
    	return $a['date'] > $b['date'] ? -1 : 1;
    }
    
    public function isAdmin($userId = 0) {
    	if ($this->admin) return true;
    	if (!isset($this->typename) || !isset($this->id) || $userId == 0) return false;
		
    	switch($this->typename) {
    		case "content": return $this->isContentAdmin($userId); break;
    		case "account": return $this->isAccountAdmin($userId); break;
    		case "group": return $this->isGroupAdmin($userId); break;
    		case "campaign": return $this->isCampaignAdmin($userId); break;
    	}
    	return false;
    }
    
	private function isContentAdmin($userId) {
    	$chuModel = new Default_Model_ContentHasUser();
    	$owners = $chuModel->getContentOwners($this->id);
    	if ($owners['id_usr'] == $userId) {
    		$this->admin = true;
    		return true;
    	}
    	return false;
    }
    
	private function isAccountAdmin($userId) {
    	$userModel = new Default_Model_User();
    	if ($userId == $userModel->getIdByUsername($this->id)) { 
	    	$this->admin = true;
	    	return true;
    	}
    	return false;
    }
    
	private function isGroupAdmin($userId) {
    	$groupModel = new Default_Model_GroupAdmins();
    	foreach ($groupModel->getGroupAdmins($this->id) as $user) {
    		if ($user['id_usr'] == $userId) {
    			$this->admin = true;
    			return true;
    		} 
    	}
    	return false;
    }
    
	private function isCampaignAdmin($userId) {
    	$userModel = new Default_Model_User();
    	foreach ($userModel->getUserCampaigns($userId) as $cmp) {
    		if ($this->id == $cmp['id_cmp']) {
    			$this->admin = true;
    			return true;
    		}
    	}
    	return false;
    }

    private function getCampaignTitle() {
    	$cmpModel = new Default_Model_Campaigns();
    	$cmp = $cmpModel->getCampaignById($this->id);
    	
    	return $cmp->name_cmp;
    }
    
    private function getGroupTitle() {
    	$grpModel = new Default_Model_Groups();
    	$grp = $grpModel->getGroupData($this->id);

    	return $grp['group_name_grp'];
    }
    
    private function getAccountTitle() {
    	return $this->id;
    }
    
    private function getContentTitle() {
    	$cntModel = new Default_Model_Content();
    	$cnt = $cntModel->getContentRow($this->id);
    	
    	return $cnt['title_cnt'];
    }
    
}
    
