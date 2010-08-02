<?php

class Oibs_Controller_Plugin_Toplist_Users extends Oibs_Controller_Plugin_TopList {
	
	public function __construct() {
		parent::__construct();
	}
	
	public function addUser($id) {
		
		$this->_addUserRank($id);
		return $this;
	}
	
	public function getAddedUser() {
		return $this->_addedUser;
	}
	
	/**
	 * @return	Oibs_Controller_Plugin_TopList_Users
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
	
	private function _getUserInfo($choice) {
		$getIds = array();
		$max = $this->_limit;
		if(sizeof($this->_topListIds[$choice]) < $max) $max = sizeof($this->_topListIds[$choice]);
		
		for($i = 0; $i < $max; $i++) {
			if($this->_topListIds[$choice][$i]) $getIds[] = $this->_topListIds[$choice][$i];
		}
		
		if($getIds) {
		
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

		}
		return;
	}
	
}