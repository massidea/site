<?php

class Oibs_Controller_Plugin_Toplist_Countries extends Oibs_Controller_Plugin_TopList {
	
	private		$_topListUsersCountry = array();
	private		$_usersWithCountry = array();
	
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
									$this->_userModel->getUsersLocation($id));

		$userCountry = $user[0]['countryIso'];
		
		foreach($this->_topList as $name => $data) {
			$iso = array_keys($data['countries']);
			$value = array_search($userCountry, $iso);
			if($value !== false) {
				$user = $this->_intersectMergeArray($user,array(0 => array('rank' => $value)));
				$this->_addedUser[$name] = $user;
			}
		}
		
		return;
	}
	
	/**
	* @return	Oibs_Controller_Plugin_TopList_Countries
	*/
	public function fetchUserCountries() {
		$this->_usersWithCountry = $this->_userModel->getUsersWithCountry($this->_userList);
		return $this;
	}
		
	public function setTop($choice) {
		try { $this->_initializeTop($choice); }
		catch (Exception $e) { echo "Exception: ".$e->getMessage(); }
		
		if(!empty($this->_usersWithCountry)) {
			$getIds = array_keys($this->_usersWithCountry);
			$this->_topListIds[$choice] = $this->_getChoiceValue($choice,$getIds);
			$final = array();
			foreach($this->_topListIds[$choice] as $userValue) {
				foreach(array_values($this->_usersWithCountry) as $userInfo) {
					if($userValue['id_usr'] == $userInfo['id_usr']) {
						$final[] = array_merge($userValue,$userInfo);
						continue 2;
					}
				}
			}
			
			$this->_topListUsersCountry[$choice] = array(
				'users' => $final,
				'name' => $choice
			);
						
			$this->_makeToCountryGroups($choice);
			
			return $this;
		}
		else {
			$error = "Countries not fetched.";
			$this->_topListUsersCountry[$choice] = $error;
			return $this;
		}
	}
	
	private function _makeToCountryGroups($choice) {
		if($this->_topListUsersCountry[$choice]['users']) {
			$this->_topList[$choice]['countries'] = null;
			foreach($this->_topListUsersCountry[$choice]['users'] as $user) {
					$countryIso = $user['countryIso'];
					if(isset($this->_topList[$choice]['countries'][$countryIso]))
						$arrayIso = $this->_topList[$choice]['countries'][$countryIso];
					else $arrayIso = null;
					
					if(!$arrayIso['value']) $this->_topList[$choice]['countries'][$countryIso]['value'] = 0;
					
					if($user['countryName']) $countryName = $user['countryName'];
					else $countryName = null;
					
					$this->_topList[$choice]['countries'][$countryIso]['countryName'] = $countryName;
					$this->_topList[$choice]['countries'][$countryIso]['value'] += $user['value'];
				
			}
						
			foreach($this->_topList[$choice]['countries'] as $info) {
				if($info['countryName']) $country[] = $info['countryName'];
				else $country[] = null;
				if($info['value']) $value[] = $info['value'];
				else $value[] = null;
			}
			

			array_multisort($value, SORT_DESC, $country, SORT_ASC, $this->_topList[$choice]['countries']);	
			
			if(sizeof($this->_topList[$choice]['countries']) > $this->_limit) {
				$temp = $this->_topList[$choice]['countries'];
				$this->_topList[$choice]['countries'] = array();
				$i = 0;
				foreach($temp as $iso => $data) {
					if($i >= $this->_limit) break;
					$i++;
					$this->_topList[$choice]['countries'][$iso] = $data;
				}
			}
			
		}
		else {
			$this->_topList[$choice]['countries'] = array("No users");
		}
		$this->_topList[$choice]['name'] = $choice; 
		return;
	}
}