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

	private		$_userModel;

	private		$_userList = array();
	private		$_topLists = array();
	private		$_topList = array();
	private		$_topListIds = array();
	private		$_addedTops = array();
	private		$_addedUser = array();
	
	private		$_limit = 10;


	public function __construct() {
		$this->_userModel = new Default_Model_User();
		$this->_userList = $this->_userModel->getUserIds();
		$this->_topLists = array(
    		'Count' => 'COUNT(id_cnt) desc',
			'View' => 'COUNT(id_cnt_vws) desc',
			'Popularity' => 'COUNT(id_usr_vws) desc',
			'Rating' => 'SUM(rating_crt) desc',
			'Comment' => 'COUNT(id_cmt) desc',
		);
	}

	/*public function test() {
		//echo $this->_limit;
		print_r($this->_userModel->sortUsersByContentInfo($this->_userList,$this->_topLists['Count'],null,null));
		print_r($this->_userModel->sortUsersByContentInfo($this->_userList,$this->_topLists['Count'],null,$this->_limit));
		die;
	}*/
	
	private function _addUserRank($id) {
		if(!is_array($id)) $id = array($id);
		$tops = $this->_addedTops;
		
		if(!empty($tops)) {
			foreach($tops as $top) {
				$sortedUsers = array();
				$value = array();
				
				if($top == 'Count') {
					$sortedUsers = $this->_userModel->sortUsersByContentInfo($this->_userList,$this->_topLists[$top],null,null);
					$value = $this->_userModel->getUsersContentCount($id);
				}
				elseif($top == 'View') {
					$sortedUsers = $this->_userModel->sortUsersByViews($this->_userList,$this->_topLists[$top],null,null);
					$value = $this->_userModel->getUsersViews($id);
				}
				elseif($top == 'Popularity') {
					$sortedUsers = $this->_userModel->sortUsersByPopularity($this->_userList,$this->_topLists[$top],null,null);
					$value = $this->_userModel->getUsersPopularity($id);
				}
				elseif($top == 'Rating') {
					$sortedUsers = $this->_userModel->sortUsersByRating($this->_userList,$this->_topLists[$top],null,null);
					$value = $this->_userModel->getUsersRating($id);
				}
				elseif($top == 'Comment') {
					$sortedUsers = $this->_userModel->sortUsersByComments($this->_userList,$this->_topLists[$top],null,null);
					$value = $this->_userModel->getUsersCommentCount($id);
				}
				$rank = array(array('rank' => array_search($id[0],$sortedUsers)));
				$info = $this->_userModel->getUserInfo($id);
	
				if(($rank[0]['rank'] !== "") && (!empty($value))) {
					$merge = $this->_intersectMergeArray($value,$rank);
				}
				else {
					$rank[0]['rank'] = "-1";
					$value = array(array('id_usr' => $info[0]['id_usr']));
					$merge = $this->_intersectMergeArray($value,$rank);
				}
				$this->_addedUser[$top] = $this->_intersectMergeArray($merge,$info);
			}
		}
		else {
			foreach($this->_topListIds as $key => $list) {
				$rank = "-1";
				$value = array();
				
				if(in_array($id[0],$list)) {
						$rank = array_search($id[0],$list);
				}

				if($rank < $this->_limit) {
					if($key == 'Count') $value = $this->_userModel->getUsersContentCount($id);
					elseif($key == 'View') $value = $this->_userModel->getUsersViews($id);
					elseif($key == 'Popularity') $value = $this->_userModel->getUsersPopularity($id);
					elseif($key == 'Rating') $value = $this->_userModel->getUsersRating($id);
					elseif($key == 'Comment') $value = $this->_userModel->getUsersCommentCount($id);
				}
				
				$value[0]['rank'] = $rank;
				$info = $this->_userModel->getUserInfo($id);
				$final = $this->_intersectMergeArray($value,$info);
				$this->_addedUser[$key] = $final;

			}			
		}
		return;
	}
		
	public function addUser($id) {
		
		$this->_addUserRank($id);
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

   /**
	* @return	Oibs_Controller_Plugin_TopList
	*/
	public function setLimit($limit) {
		$this->_limit = $limit;
		return $this;
	}
	
	private function _getUserInfo($choice) {
		$getIds = array();
		for($i = 0; $i < $this->_limit; $i++) {
			$getIds[] = $this->_topListIds[$choice][$i];
		}
		if($choice == 'Count') $temp = $this->_userModel->getUsersContentCount($getIds);
		elseif($choice == 'View') $temp = $this->_userModel->getUsersViews($getIds);
		elseif($choice == 'Popularity') $temp = $this->_userModel->getUsersPopularity($getIds);
		elseif($choice == 'Rating') $temp = $this->_userModel->getUsersRating($getIds);
		elseif($choice == 'Comment') $temp = $this->_userModel->getUsersCommentCount($getIds);
		
		$this->_topList[$choice] = array(
			'users' => 
				$this->_finalizeToSortingOrderByUserId($getIds,
					$this->_intersectMergeArray($temp,
						$this->_userModel->getUserInfo($getIds))),
			'name' => $choice
		);
		
		return;
	}
	
	/**
	 * @return	Oibs_Controller_Plugin_TopList
	 */
	public function setTop($choice) {
		if(array_key_exists($choice,$this->_topLists)) {
			if($choice == 'Count') $this->_topListIds[$choice] = $this->_userModel->sortUsersByContentInfo($this->_userList,$this->_topLists[$choice],null,null);
			elseif($choice == 'View') $this->_topListIds[$choice] = $this->_userModel->sortUsersByViews($this->_userList,$this->_topLists[$choice],null,null);
			elseif($choice == 'Popularity') $this->_topListIds[$choice] = $this->_userModel->sortUsersByPopularity($this->_userList,$this->_topLists[$choice],null,null);
			elseif($choice == 'Rating') $this->_topListIds[$choice] = $this->_userModel->sortUsersByRating($this->_userList,$this->_topLists[$choice],null,null);
			elseif($choice == 'Comment') $this->_topListIds[$choice] = $this->_userModel->sortUsersByComments($this->_userList,$this->_topLists[$choice],null,null);
			$this->_getUserInfo($choice);	
			return $this;
		}
		else {
			$error = "Invalid choice. Possible choices are:";
			$keys = array_keys($this->_topLists);
			foreach($keys as $key) {
				$error .= ", ".$key;
			}
			return $error;
		}
	}
	
	private function _intersectMergeArray($arr1,$arr2) {
    	if((!(array)$arr1 )|| (!(array)$arr2)) return false;
    	$merged_array = array();
    	foreach($arr1 as $key => $a) {
    		$merged_array[$key] = array_merge($a, $arr2[$key]);
    	}
    	return $merged_array;
    } 
    
    private function _finalizeToSortingOrderByUserId($arr1,$arr2) {
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
