<?php

class Oibs_Controller_Plugin_Toplist_Countries extends Oibs_Controller_Plugin_TopList {
	
	private		$_topListUsersCountry = array();
	private		$_usersWithCountry = array();
	
	public function __construct() {
		parent::__construct();
	}
	
	/**
	* @return	Oibs_Controller_Plugin_TopList_Countries
	*/
	public function fetchUserCountries() {
		$this->_usersWithCountry = $this->_userModel->getUsersWithCountry($this->_userList);
		return $this;
	}
		
	public function setTop($choice) {
		if(array_key_exists($choice,$this->_topLists) && !empty($this->_usersWithCountry)) {
			$getIds = array_keys($this->_usersWithCountry);

			if($choice == 'Count') $this->_topListIds[$choice] = $this->_userModel->getUsersContentCount($getIds);
			elseif($choice == 'View') $this->_topListIds[$choice] = $this->_userModel->getUsersViews($getIds);
			elseif($choice == 'Popularity') $this->_topListIds[$choice] = $this->_userModel->getUsersPopularity($getIds);
			elseif($choice == 'Rating') $this->_topListIds[$choice] = $this->_userModel->getUsersRating($getIds);
			elseif($choice == 'Comment') $this->_topListIds[$choice] = $this->_userModel->getUsersCommentCount($getIds);
				
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
			$error = "Invalid choice or countries not fetched. Possible choices are:";
			$keys = array_keys($this->_topLists);
			foreach($keys as $key) {
				$error .= ", ".$key;
			}
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
			
		}
		else {
			$this->_topList[$choice]['countries'] = array("No users");
		}
		$this->_topList[$choice]['name'] = $choice; 
		return;
	}
}