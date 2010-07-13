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
		if($choice == 'Count') $temp = $this->_userModel->getUsersContentCount($this->_topList[$choice]);
		elseif($choice == 'View') $temp = $this->_userModel->getUsersViews($this->_topList[$choice]);
		elseif($choice == 'Popularity') $temp = $this->_userModel->getUsersPopularity($this->_topList[$choice]);
		elseif($choice == 'Rating') $temp = $this->_userModel->getUsersRating($this->_topList[$choice]);
		elseif($choice == 'Comment') $temp = $this->_userModel->getUsersCommentCount($this->_topList[$choice]);
		
		$this->_topList[$choice] = array(
			'users' => 
				$this->_finalizeToSortingOrderByUserId($this->_topList[$choice],
					$this->_intersectMergeArray($temp,
						$this->_userModel->getUserInfo($this->_topList[$choice]))),
			'name' => $choice
		);
		
		return;
	}
	
	/**
	 * @return	Oibs_Controller_Plugin_TopList
	 */
	public function setTop($choice) {
		if(array_key_exists($choice,$this->_topLists)) {
			if($choice == 'Count') $this->_topList[$choice] = $this->_userModel->sortUsersByContentInfo($this->_userList,$this->_topLists[$choice],null,$this->_limit);
			elseif($choice == 'View') $this->_topList[$choice] = $this->_userModel->sortUsersByViews($this->_userList,$this->_topLists[$choice],null,$this->_limit);
			elseif($choice == 'Popularity') $this->_topList[$choice] = $this->_userModel->sortUsersByPopularity($this->_userList,$this->_topLists[$choice],null,$this->_limit);
			elseif($choice == 'Rating') $this->_topList[$choice] = $this->_userModel->sortUsersByRating($this->_userList,$this->_topLists[$choice],null,$this->_limit);
			elseif($choice == 'Comment') $this->_topList[$choice] = $this->_userModel->sortUsersByComments($this->_userList,$this->_topLists[$choice],null,$this->_limit);
			
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
