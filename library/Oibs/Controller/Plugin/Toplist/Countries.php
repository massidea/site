<?php

class Oibs_Controller_Plugin_Toplist_Countries extends Oibs_Controller_Plugin_TopList {
	
	private		$_topListUsersCountry = array();
	private		$_usersWithCountry = array();
	private		$_name = "countries";
	
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
		$found = false;
		if(empty($this->_usersWithCountry) || !is_array($this->_usersWithCountry)) return;
		foreach($this->_usersWithCountry as $_id => $data) {
			if($id == $_id ) {
				$found = true;
				break;
			}
		}
		if(!$found) return;
		
		if(!is_array($id)) $id = array($id);
		
		$user = $this->_intersectMergeArray($this->_userModel->getUserInfo($id),
									$this->_userProfileModel->getUsersLocation($id));

		$userCountry = $user[0]['countryIso'];

		foreach($this->_topListIds as $name => $data) {
			if(empty($data[$this->_name])) continue;
			$iso = array_keys($data[$this->_name]);
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
		$this->_usersWithCountry = $this->_userProfileModel->getUsersWithCountry($this->_userList);
		if(empty($this->_usersWithCountry)) $this->_usersWithCountry = 1;
		return $this;
	}
	
	public function setTopAmount() {
		$choice = "Amount";

		$countries = $this->_userProfileModel->getCountryAmounts($this->_userList);
		
		/* Couldnt decide between sql query or loop... I'll leave this here if someone notices it to be faster.
		$countries = array();
		foreach($this->_usersWithCountry as $data) {
			if(!isset($countries[$data['countryIso']])) $countries[$data['countryIso']] = 1;
			else $countries[$data['countryIso']] += 1;
		}
		*/
		if(empty($countries)) $this->_topList[$choice][$this->_name] = array("No countries");
		else { 
			$this->_topList[$choice][$this->_name] = $countries;
			$this->_topListIds[$choice] = $this->_topList[$choice];
			$this->_cutToLimit($this->_name,$choice);
		}
		$this->_topList[$choice]['name'] = $choice;
		
		
		return $this;

	}
		
	public function setTop($choice) {
		try { $this->_initializeTop($choice); }
		catch (Exception $e) { echo "Exception: ".$e->getMessage(); }
		
		try {
			if(!empty($this->_usersWithCountry)) {
				if($this->_usersWithCountry != 1) {
					$getIds = array_keys($this->_usersWithCountry);
					$this->_topListIds[$choice] = $this->_getChoiceValue($choice,$getIds);
					$final = array();
					//print_r($this->_usersWithCountry);die;
					foreach($this->_topListIds[$choice] as $userValue) {
						foreach(array_values($this->_usersWithCountry) as $userInfo) {
							if($userValue['id_usr'] == $userInfo['id_usr']) {
								$final[] = array_merge($userValue,$userInfo);
								continue 2;
							}
						}
					}
				}
				else {
					$final = null;
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
				throw new Exception($error);
			}
		} catch (Exception $e) {
			echo "Exception: ".$e->getMessage();
		}

	}
	
	private function _makeToCountryGroups($choice) {
		if($this->_topListUsersCountry[$choice]['users']) {
			$this->_topList[$choice][$this->_name] = null;
			foreach($this->_topListUsersCountry[$choice]['users'] as $user) {
					$countryIso = $user['countryIso'];
					if(isset($this->_topList[$choice][$this->_name][$countryIso]))
						$arrayIso = $this->_topList[$choice][$this->_name][$countryIso];
					else $arrayIso = null;
					
					if(!$arrayIso['value']) $this->_topList[$choice][$this->_name][$countryIso]['value'] = 0;
					
					if($user['countryName']) $countryName = $user['countryName'];
					else $countryName = null;
					
					$this->_topList[$choice][$this->_name][$countryIso]['name'] = $countryName;
					$this->_topList[$choice][$this->_name][$countryIso]['value'] += $user['value'];
				
			}

			$this->_valueSort($this->_name,$choice);
			$this->_topListIds[$choice] = $this->_topList[$choice];
			$this->_cutToLimit($this->_name,$choice);
			
		}
		else {
			$this->_topList[$choice][$this->_name] = array("No countries");
		}
		$this->_topList[$choice]['name'] = $choice; 
		return;
	}
}