<?php
class Oibs_Controller_Plugin_Toplist_Groups extends Oibs_Controller_Plugin_TopList {
	
	private		$_usersInGroups = array();
	private		$_groupsWithIds = array();
	private		$_usrHasGroupModel;
	private		$_name = "groups";
	
	
	public function __construct() {
		parent::__construct();
		$this->_usrHasGroupModel = new Default_Model_UserHasGroup();
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

	public function fetchUsersInGroups() {
		$this->_usersInGroups = $this->_usrHasGroupModel->getAllUsers($this->_userList);
		$this->_groupsWithIds = $this->_usrHasGroupModel->getAllGroupsWithUsers($this->_userList);
		if(empty($this->_usersInGroups)) $this->_usersInGroups = 1;
		return $this;
	}
	
	public function setTopAmount() {
		$choice = "Amount";

		$groups = $this->_usrHasGroupModel->getGroupAmounts($this->_userList);
		if(empty($groups)) $this->_topList[$choice][$this->_name] = array("No groups");
		else {
			$this->_topList[$choice][$this->_name] = $groups;
			$this->_cutToLimit($this->_name,$choice);
		}
		$this->_topList[$choice]['name'] = $choice;	
		return $this;
	}	
	
	public function setTop($choice) {
		try { $this->_initializeTop($choice); }
		catch (Exception $e) { echo "Exception: ".$e->getMessage(); die; }
	
		try {
			if(!empty($this->_usersInGroups)) {
				if($this->_usersInGroups != 1) {
					$temp = $this->_getChoiceValue($choice,$this->_usersInGroups);
					$final = array();
					foreach($temp as $value) {
						$final[$value['id_usr']]['value'] = $value['value'];
					}
					$this->_topListIds[$choice]['users'] = $final;
					$this->_makeToGroupTops($choice);
				}
				else {
					$this->_topListIds[$choice]['users'] = null;
					$this->_makeToGroupTops($choice);
				}
			}
			else {
				$error = "Groups not fetched.";
				throw new Exception($error);
			}
		}
		 catch (Exception $e) {
			echo "Exception: ".$e->getMessage();
		}
		
	}
	
	private function _makeToGroupTops($choice) {
		if(!empty($this->_topListIds[$choice]['users'])) {
			$final = array();
			foreach($this->_groupsWithIds as $data) {
				if(!isset($final[$this->_name][$data['id_grp']])) {
					if(isset($this->_topListIds[$choice]['users'][$data['id_usr']]['value']))
					$final[$this->_name][$data['id_grp']] = array('name' => $data['group_name_grp'],
															'id' => $data['id_grp'],
															'value' => $this->_topListIds[$choice]['users'][$data['id_usr']]['value']
															);
				}
				else {
					$final[$this->_name][$data['id_grp']]['value'] += $this->_topListIds[$choice]['users'][$data['id_usr']]['value'];
				}
			}
			if(empty($final)) $final[$this->_name] = null;
			$this->_topList[$choice] = $final;
			if($final[$this->_name]) {
				$this->_valueSort($this->_name,$choice);
				$this->_cutToLimit($this->_name,$choice);
			}
			
		}
		else {
			$this->_topList[$choice][$this->_name] = array("No groups");
		}
		$this->_topList[$choice]['name'] = $choice; 
		return;
	}
}