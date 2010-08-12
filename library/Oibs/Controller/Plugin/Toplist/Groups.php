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
		return $this;
	}
	
	public function setTop($choice) {
		try { $this->_initializeTop($choice); }
		catch (Exception $e) { echo "Exception: ".$e->getMessage(); die; }
	
		try {
			if(!empty($this->_usersInGroups)) {
				$temp = $this->_getChoiceValue($choice,$this->_usersInGroups);
				$final = array();
				foreach($temp as $value) {
					$final[$value['id_usr']]['value'] = $value['value'];
				}
				$this->_topListIds[$choice]['users'] = $final;
				$this->_makeToGroupTops($choice);
				
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
		if(isset($this->_topListIds[$choice])) {
			$final = array();
			foreach($this->_groupsWithIds as $data) {
				if(!isset($final[$this->_name][$data['id_grp']])) {
					$final[$this->_name][$data['id_grp']] = array('name' => $data['group_name_grp'],
															'id' => $data['id_grp'],
															'value' => $this->_topListIds[$choice]['users'][$data['id_usr']]['value']
															);
				}
				else {
					$final[$this->_name][$data['id_grp']]['value'] += $this->_topListIds[$choice]['users'][$data['id_usr']]['value'];
				}
			}
			$this->_topList[$choice] = $final;
			
			$this->_valueSort($this->_name,$choice);
			$this->_cutToLimit($this->_name,$choice);
			
		}
		else {
			$this->_topList[$choice][$this->_name] = array("No users");
		}
		$this->_topList[$choice]['name'] = $choice; 
		return;
	}
}