<?php

class Oibs_Controller_Plugin_Toplist_Users extends Oibs_Controller_Plugin_TopList {
	
	public function __construct() {
		parent::__construct();
	}
	
	public function addUser($id) {
		$this->_addUserRank($id);
		$this->_addAddedUserToTop();
		return $this;
	}
	
	public function autoSet() {
		foreach($this->_topLists as $name => $info) {
			$this->setTop($name);
		}
		$this->addDescriptions();
		$this->addTitleLinks();
		$this->addTitles();
		return $this;
	}
	
	/**
	 * @return	Oibs_Controller_Plugin_TopList_Users
	 */
	public function setTop($choice) {
		try { $this->_initializeTop($choice); }
		catch (Exception $e) { echo "Exception: ".$e->getMessage(); }
		$this->_topListIds[$choice] = $this->_getChoicesSortedUserList($choice,$this->_topLists[$choice]);
		$this->_getUserInfo($choice);
		return $this;
	}
	
	private function _addUserRank($id) {
		if(!is_array($id)) $id = array($id);
		$tops = $this->_addedTops;
		
		if(!empty($tops)) {
			foreach($tops as $top) {
				$sortedUsers = array();
				$value = array();
				
				$sortedUsers = $this->_getChoicesSortedUserList($top,$this->_topLists[$top]);
				
				$value = $this->_getChoiceValue($top,$id);

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

				$value = $this->_getChoiceValue($key,$id);
				if(empty($value)) $rank = "-1";

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
		
		if(!empty($getIds)) {
			
			$temp = $this->_getChoiceValue($choice,$getIds);
	
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