<?php
/**
 *  TopList - Class to make toplist
 *
 *   Copyright (c) <2010>, Jari Korpela <jari.korpela@student.samk.fi>
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
 *  TopList - class
 *
 *  @package    plugins
 *  @author     Jari Korpela
 *  @copyright  2010 Jari Korpela
 *  @license    GPL v2
 *  @version    1.0
 */
class Oibs_Controller_Plugin_TopList {

	protected		$_userModel; //Models
	protected		$_userProfileModel;
	protected		$_url;
	protected		$_translate;

	protected		$_userList = array();
	protected		$_topLists = array();
	protected		$_topListsLinks = array();
	protected		$_descriptions = array();
	protected		$_titles = array();
	
	protected		$_topList = array();
	protected		$_topListIds = array();
	protected		$_addedTops = array();
	protected		$_addedUser = array();
	
	protected		$_limit = 10;

	public function __construct() {
		$this->_userModel = new Default_Model_User();
		$this->_userProfileModel = new Default_Model_UserProfiles();
		$this->_url = new Zend_View_Helper_Url();
		$this->_translate = new Zend_View_Helper_Translate();
		$this->_userList = $this->_userModel->getUserIds();
		$this->_topLists = array(
    		'Count' => 'COUNT(id_cnt) desc',
			'View' => 'COUNT(id_cnt_vws) desc',
			'Popularity' => 'COUNT(id_usr_vws) desc',
			'Rating' => 'SUM(rating_crt) desc',
			'Comment' => 'COUNT(id_cmt) desc',
		);
		foreach($this->_topLists as $name => $info) {
			if($name == 'Count') $order = 'content';
			elseif($name == 'View') $order = 'views';
			elseif($name == 'Popularity') $order = 'popularity';
			elseif($name == 'Rating') $order = 'rating';
			elseif($name == 'Comment') $order = 'comments';
			$this->_topListsLinks[$name] = $this->_url->url(array('controller' => 'account',
							 'action' => 'userlist',
							 'order' => $order,
							 'list' => 'desc'), 
							 'lang_default', true);
			
			$this->_descriptions[$name] = $this->_translate->translate("userlist-top-description-".strtolower($name));
			$this->_titles[$name] = $this->_translate->translate("userlist-top-title-".strtolower($name));
			
			$this->_topListIds[$name] = null;
			$this->_topList[$name] = null;
		}
		$this->_topListsLinks['Amount'] = $this->_url->url(array('controller' => 'account',
							 'action' => 'userlist'), 
							 'lang_default', true);
		$this->_descriptions['Amount'] = "Has member count of ";
		$this->_titles['Amount'] = "Most members";
		
	}
		
	public function setUserIdList($list) {
		if(is_array($list)) $this->_userList = $list;
		else return "error";
		return $this;
	}
	
   /**
	* @return	Oibs_Controller_Plugin_TopList
	*/
	public function setLimit($limit) {
		$this->_limit = $limit;
		return $this;
	}
	
	public function addTitleLinks() {
		if($this->_topList) {
			foreach($this->_topList as $name => $list) {
				$this->_topList[$name]['link'] = $this->_topListsLinks[$name];
			}
		}
		return $this;
	}
		
	public function addTitles() {
		if($this->_topList) {
			foreach($this->_topList as $name => $data) {
				$this->_topList[$name]['title'] = $this->_titles[$name];
			}
		}
		return $this;
	}
	
	public function addDescriptions() {
		if($this->_topList) {
		foreach($this->_topList as $name => $data) {
				$this->_topList[$name]['description'] = $this->_descriptions[$name];
			}
		}
		return $this;
	}
	
	public function addTopsToUser($tops = "all") {
		if($tops = "all") $this->_addedTops = array_keys($this->_topLists);
		else {
			if(array_key_exists($tops,$this->_topLists))
				$this->_addedTops = $tops;
		}
		return $this;
	}

	public function getAddedUser() {
		return $this->_addedUser;
	}
	
	public function getUsers() {
		return $this->_userList;
	}

	public function getTopList() {
		return $this->_topList;
	}

	protected function _initializeTop($choice) {
		if(array_key_exists($choice,$this->_topLists)) {
			return true;
		}
		else {
			$error = "Invalid choice. Possible choices are:";
			$keys = array_keys($this->_topLists);
			foreach($keys as $key) {
				$error .= ", ".$key;
			}
			echo $error;
			throw new Exception($error);
		}
	}
	
	protected function _addAddedUserToTop() {
		foreach($this->_topList as $key1 => $top) {
			foreach($this->_addedUser as $key2 => $user) {
				if($key1 == $key2) {
					$topListMerge[$key1] = array_merge($top,array('addedUsers' => $user));
					continue 2;
				}
			}
			$topListMerge[] = $top;
		}
		$this->_topList = $topListMerge;
		return;
	}
	
	protected function _getChoiceValue($choice,$idList) {
		$value = null;
		if($choice == 'Count') $value = $this->_userModel->getUsersContentCount($idList);
		elseif($choice == 'View') $value = $this->_userModel->getUsersViews($idList);
		elseif($choice == 'Popularity') $value = $this->_userModel->getUsersPopularity($idList);
		elseif($choice == 'Rating') $value = $this->_userModel->getUsersRating($idList);
		elseif($choice == 'Comment') $value = $this->_userModel->getUsersCommentCount($idList);
		return $value;		
	}
	
	protected function _getChoicesSortedUserList($choice,$idList) {
		$sortedUsers = null;
		if($choice == 'Count') $sortedUsers = $this->_userModel->sortUsersByContentInfo($this->_userList,$idList,null,null);
		elseif($choice == 'View') $sortedUsers = $this->_userModel->sortUsersByViews($this->_userList,$idList,null,null);
		elseif($choice == 'Popularity') $sortedUsers = $this->_userModel->sortUsersByPopularity($this->_userList,$idList,null,null);
		elseif($choice == 'Rating') $sortedUsers = $this->_userModel->sortUsersByRating($this->_userList,$idList,null,null);
		elseif($choice == 'Comment') $sortedUsers = $this->_userModel->sortUsersByComments($this->_userList,$idList,null,null);
		return $sortedUsers;	
	}
	
	protected function _intersectMergeArray($arr1,$arr2) {
    	if((!(array)$arr1 )|| (!(array)$arr2)) return false;
    	$merged_array = array();
    	foreach($arr1 as $key => $a) {
    		$merged_array[$key] = array_merge($a, $arr2[$key]);
    	}
    	return $merged_array;
    } 
    
    protected function _finalizeToSortingOrderByUserId($arr1,$arr2) {
    	$final = array();
    	foreach($arr1 as $id) {
    		foreach($arr2 as $data) {
    			if ($data['id_usr'] == $id) {
    				$final[] = $data;
    				continue 2;
    			}
    		}
    	}
    	return $final;
    }
	
}
