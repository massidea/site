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
	protected		$_url;
	protected		$_translate;

	protected		$_userList = array();
	protected		$_topLists = array();
	protected		$_topListsLinks = array();
	
	protected		$_topList = array();
	protected		$_topListIds = array();
	protected		$_addedTops = array();
	protected		$_addedUser = array();
	
	protected		$_limit = 10;


	public function __construct() {
		$this->_userModel = new Default_Model_User();
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
			
			$this->_topListIds[$name] = null;
			$this->_topList[$name] = null;
		}
		
		
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
			foreach($this->_topList as $name => $list) {
			    $this->_topList[$name]['title'] = $this->_translate->translate("userlist-top-title-".strtolower($name));
			}
		}
		return $this;
	}
	
	public function addDescriptions() {
		if($this->_topList) {
			foreach($this->_topList as $name => $list) {
			    $this->_topList[$name]['description'] = $this->_translate->translate("userlist-top-description-".strtolower($name));
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
		
	public function getUsers() {
		return $this->_userList;
	}

	public function getTopList() {
		return $this->_topList;
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
