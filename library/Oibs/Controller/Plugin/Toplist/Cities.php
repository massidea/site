<?php
class Oibs_Controller_Plugin_Toplist_Cities extends Oibs_Controller_Plugin_TopList {
	
	private		$_usersWithCity = array();
	private		$_name = "cities";
	
	
	public function __construct() {
		parent::__construct();
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
	
	public function addUser($id) {
		$this->_addUserRank($id);
		$this->_addAddedUserToTop();
		return $this;
	}
	
	private function _addUserRank($id) {
		if(!is_array($id)) $id = array($id);
		
		$user = $this->_intersectMergeArray($this->_userModel->getUserInfo($id),
									$this->_userProfileModel->getUsersLocation($id));
		$city = $user[0]['city'];
		foreach($this->_topList as $name => $data) {
			$value = array();
			foreach($data[$this->_name] as $rank => $data) {
				if(mb_strtolower($data['name']) == mb_strtolower($city)) { $value[] = array_merge($data,array('rank' => $rank)); break; }
			}
						
			if(!empty($value)) {
				$user = $this->_intersectMergeArray($user,$value);
				$this->_addedUser[$name] = $user;
			}
		}

		return;
	}

	public function fetchUsersWithCity() {
		$this->_usersWithCity = $this->_userProfileModel->getUsersWithCity($this->_userList);
		return $this;
	}
	
	public function setTopAmount() {
		$choice = "Amount";

		$cities = array_values($this->_userProfileModel->getCityAmounts($this->_userList));
		
		$this->_topList[$choice][$this->_name] = $cities;
		$this->_topList[$choice]['name'] = $choice;
		$this->_cutToLimit($this->_name,$choice);
		
		foreach($this->_topList[$choice][$this->_name] as $key => $city) {
			$this->_topList[$choice][$this->_name][$key]['name'] = mb_convert_case($this->_topList[$choice][$this->_name][$key]['name'], MB_CASE_TITLE, "UTF-8");
		}
		
		return $this;
	}
	
	public function setTop($choice) {
		try { $this->_initializeTop($choice); }
		catch (Exception $e) { echo "Exception: ".$e->getMessage(); }
	
		try {
			if(!empty($this->_usersWithCity)) {
				$temp = $this->_getChoiceValue($choice,array_keys($this->_usersWithCity));
				if(!empty($temp))
					$this->_topListIds[$choice]['users'] = $this->_intersectMergeArray($temp,array_values($this->_usersWithCity));
				$this->_makeToCityTops($choice);
				
			}
			else {
				$error = "Cities not fetched.";
				throw new Exception($error);
			}
		}
		 catch (Exception $e) {
			echo "Exception: ".$e->getMessage();
		}
		
	}
	
	private function _makeToCityTops($choice) {
		if(isset($this->_topListIds[$choice])) {
			$final = array();
			if(isset($this->_topListIds[$choice]['users'])) {
				foreach($this->_topListIds[$choice]['users'] as $data) {
					if(empty($data['value'])) continue;
					$city = mb_convert_case($data['city'], MB_CASE_TITLE, "UTF-8");
					if(!isset($final[$city])) {
						$final[$city]['name'] = $city;
						$final[$city]['value'] = $data['value'];
					}
					else {
						$final[$city]['value'] += $data['value'];
					}
				}
			}
			$this->_topList[$choice][$this->_name] = $final;
			if(!empty($final)) {
				$this->_valueSort($this->_name,$choice);
				$this->_cutToLimit($this->_name,$choice);
				$this->_topList[$choice][$this->_name] = array_values($this->_topList[$choice][$this->_name]);
			}

		}
		else {
			$this->_topList[$choice][$this->_name] = array("No users");
		}
		$this->_topList[$choice]['name'] = $choice; 
		return;
	}
}