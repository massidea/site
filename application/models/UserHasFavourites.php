<?php
/**
 *  UserHasFavourites -> UserHasFavourites database model for user favourites table.
 *
 * 	Copyright (c) <2010> Jari Korpela
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
 *  UserHasFavourites - class
 *
 *  @package    models
 *  @author     Jari Korpela
 *  @copyright  2010 Jari Korpela
 *  @license    GPL v2
 *  @version    1.0
 */

class Default_Model_UserHasFavourites extends Zend_Db_Table_Abstract
{
	// Name of table
	protected $_name = 'usr_has_fvr';

	// Primary keys of table
	protected $_primary = array('id_cnt','id_usr');

	protected $_referenceMap = array(
        'FavouritesContent' => array(
            'columns'           => array('id_cnt'),
            'refTableClass'     => 'Default_Model_Content',
            'refColumns'        => array('id_cnt')
	),
		 'FavouritesUser' => array(
            'columns'           => array('id_usr'),
            'refTableClass'     => 'Default_Model_User',
            'refColumns'        => array('id_usr')
	),
	);
	
	//Get all favourite content ID:s that user has
	public function getAllFavouriteContentIdsFromUser($id_usr = 0)
	{
		if($id_usr != 0) {
			$select = $this->select()
			->from($this, array('id_cnt'))
			->where('id_usr = ?',$id_usr);

			$result = $this->fetchAll($select)->toArray();
			return $result;
		} else {
			return NULL;
		}
	}
	
	public function getAllFavouriteContentIdsFromUserWithLastChecked($id_usr = 0)
	{
		if($id_usr != 0) {
			$select = $this->select()
			->from($this, array('id_cnt','last_checked'))
			->where('id_usr = ?',$id_usr);

			$result = $this->fetchAll($select)->toArray();
			$return = array();
			foreach($result as $res) {
				$return[$res['id_cnt']] = $res['last_checked'];
			}
			return $return;
		} else {
			return NULL;
		}
	}

	//Get all user ID:s that have same favourite content ID:s
	public function getAllUserIdsFromFavouriteContent($id_cnt = 0)
	{
		if($id_cnt != 0) {
			$select = $this->select()
			->from($this, array('id_usr'))
			->where('id_cnt = ?',$id_cnt);

			$result = $this->fetchAll($select)->toArray();
			return $result;
		} else {
			return NULL;
		}
	}

	//Get favourites count by user
	public function getFavouritesCountByUser($id_usr = 0)
	{
		if($id_usr != 0) {
			$select = $this->select()
			->from($this, array('favourites_count' => 'COUNT(id_cnt)'))
			->where('id_usr = ?',$id_usr);

			$result = $this->fetchAll($select)->toArray();
			return $result;
		} else {
			return NULL;
		}
	}

	//Get users count by favourite content
	public function getUsersCountByFavouriteContent($id_cnt = 0)
	{
		if($id_cnt != 0) {
			$select = $this->select()
			->from($this, array('users_count_fvr' => 'COUNT(id_usr)'))
			->where('id_cnt = ?',$id_cnt);

			$result = $this->fetchAll($select)->toArray();
			return $result;
		} else {
			return NULL;
		}
	}

	//Check if user has content favourited
	public function checkIfUserHasFavouriteContent($id_usr = 0)
	{
		$return = false;

		if($id_usr != 0) {
			$select = $this->select()
			->from($this, array('*'))
			->where('id_usr = ?',$id_usr);

			$this->fetchAll($select)->count() == 0 ? $return = false : $return = true;
				
		}
		return $return;
	}

	//Check if content is added to favourites by any user
	public function checkIfContentIsFavourited($id_cnt = 0)
	{
		$return = false;

		if($id_cnt != 0) {
			$select = $this->select()
			->from($this, array('*'))
			->where('id_cnt = ?',$id_cnt);

			$this->fetchAll($select)->count() == 0 ? $return = false : $return = true;
				
		}
		return $return;
	}

	//Check if content is added to user favourites
	public function checkIfContentIsUsersFavourite($id_cnt = 0, $id_usr = 0)
	{
		$return = false;

		if($id_cnt != 0 && $id_usr != 0) {
			$select = $this->select()
			->from($this, array('*'))
			->where('id_cnt = ?',$id_cnt)
			->where('id_usr = ?',$id_usr);

			$this->fetchAll($select)->count() == 0 ? $return = false : $return = true;
				
		}
		return $return;
	}

	//Add content to favourites
	public function addContentToFavourites($id_cnt = 0, $id_usr = 0)
	{
		$return = false;

		if($id_cnt != 0 && $id_usr != 0) {
			$content = $this->createRow();

			$content->id_cnt = $id_cnt;
			$content->id_usr = $id_usr;
			$content->last_checked = new Zend_Db_Expr('NOW()');

			if(!$content->save()) {
				$return = false;
			} else {
				$return = true;
			}
		}
		return $return;
	}
	
	//Removes all favourite content from user by user id
	//This is used when user wants to remove all of his favourite content
	public function removeAllFavouriteContentByUserId($id_usr = 0)
	{
		$return = false;

		if($id_usr != 0) {

			$where = $this->getAdapter()->quoteInto('id_usr = ?', (int)$id_usr);

			if(!$this->delete($where)) {
				$return = false;
			} else {
				$return = true;
			}
		}
		return $return;
	}
	
	//Removes favourite content from user by user id
	//This is used when user wants to remove single content from his favourite list
	public function removeUserFavouriteContent($id_cnt = 0, $id_usr = 0)
	{
		$return = false;

		if($id_cnt != 0 && $id_usr != 0) {
			$where[] = $this->getAdapter()->quoteInto('id_usr = ?', (int)$id_usr);
			$where[] = $this->getAdapter()->quoteInto('id_cnt = ?', (int)$id_cnt);
			
			if(!$this->delete($where)) {
				$return = false;
			} else {
				$return = true;
			}
		}
		return $return;
	}
	
	//Removes all favourite content from users by content id
	//If content is deleted this is used to remove references to deleted content
	public function removeAllContentFromFavouritesByContentId($id_cnt = 0) 
	{
		$return = false;

		if($id_cnt != 0) {
			$where = $this->getAdapter()->quoteInto('id_cnt = ?', (int)$id_cnt);

			if(!$this->delete($where)) {
				$return = false;
			} else {
				$return = true;
			}
		}
		return $return;
	}
	
	/*
	 * Functions that are related to content following
	 */
	
	public function updateLastChecked($id_usr, $id_cnt) {
		$this->update(array('last_checked' => new Zend_Db_Expr('NOW()')),
					"id_usr = $id_usr and id_cnt = $id_cnt");
		return;
	}
	
	private function _getFollows() {
		$select = $this->_db->select()->from('follows_flw',array('bit','name'));
		$result = $this->_db->fetchAssoc($select);
		$returnArray = array();
		foreach($result as $key => $value) {
			$returnArray[$key] = $value['name'];
		}
		return $returnArray;
	}
	
	private function _getProfileSettingsForFollows($id_usr,$type = 'all') {
		$userProfileModel = new Default_Model_UserProfiles();
		$return = array();
		if($type == "all") $types = array('own_follows','fvr_follows');
		else $types = array($type);
		
		foreach($types as $type) {
			$list['profile_value_usp'] = 0;
			if($value = $userProfileModel->getUserProfileValue($id_usr, $type))
				$list = $value->toArray();
			$return[$type] = $list['profile_value_usp'];
		}
		//print_r($return);
		return $return;
	}
	
	private function _simplifyArray($array) {
		$newArray = array();
		function simplify($item,$key,$array){$array[] = $item;}
		array_walk_recursive($array,"simplify",&$newArray);
		return $newArray;
	}
	
	private function _getWhatUserIsFollowing($id_usr = 0, $type = 'all') {
		$follows = $this->_getFollows();

		if($type = "all")
			$settings = $this->_getProfileSettingsForFollows($id_usr);
		else $settings = $this->_getProfileSettingsForFollows($id_usr,$type);
			
		$following = array();
		foreach($settings as $key => $set) {
			foreach($follows as $b => $follow) {
				if($b & $set) $following[$key][$b] = $follow;
			}
		}
		return $following;
	}
	
	public function getUsersWhoFollowContent($id_cnt) {
		$favouriteModel = new Default_Model_UserHasFavourites();
		$userProfileModel = new Default_Model_UserProfiles();
		$contentHasUserModel = new Default_Model_ContentHasUser();
		$favouriteIds = $this->_simplifyArray($favouriteModel->getAllUserIdsFromFavouriteContent($id_cnt));
		$ownerIds = $this->_simplifyArray($contentHasUserModel->getContentOwners($id_cnt));
		$mergedIds = array_merge($favouriteIds,$ownerIds);
		$followingUsers = array_keys($userProfileModel->getUsersWhoFollowContents($mergedIds));
		return $followingUsers;
	}
	
	public function getAllUpdatedContents($id_usr) {
		$followsToFetch = $this->_getWhatUserIsFollowing($id_usr);
		//print_r($followsToFetch);die;
		$updatedContents = array();
		if(isset($followsToFetch['own_follows'])) {
			$updatedContents['own'] = $this->_fetchUpdatedContents($id_usr,$followsToFetch['own_follows'],"own");
		}
		if(isset($followsToFetch['fvr_follows'])) {
			$updatedContents['fvr'] = $this->_fetchUpdatedContents($id_usr,$followsToFetch['fvr_follows'],"fvr");
		}
		if($this->_noNewContents($updatedContents)) return false;
		
		//print_r($updatedContents);die;
		
		$sortedUpdated = array();
		$uniqueUsers = array();
		$actorUsers = array();
		
		foreach($updatedContents as $k => $binArray) {
			foreach($binArray as $bin => $contentArray) {
				if(is_array($contentArray)) {
					foreach($contentArray as $info) {
						$actorUsers[$info['id_cnt']]['users'][] = $info['id_usr'];
						$actorUsers[$info['id_cnt']]['bin'][] = $bin;
						$actorUsers[$info['id_cnt']]['time'][] = $info['time'];
						$uniqueUsers[$info['id_usr']] = 1;						
						
						if(!isset($sortedUpdated[$k][$info['id_cnt']])) $sortedUpdated[$k][$info['id_cnt']] = array('time' => $info['time'], 'id_usr' => $info['id_usr']);
						else if(strtotime($sortedUpdated[$k][$info['id_cnt']]['time']) < strtotime($info['time']))
								$sortedUpdated[$k][$info['id_cnt']] = array('time' => $info['time'], 'id_usr' => $info['id_usr']);
					}
				}
			}
		}

		$userModel = new Default_Model_User();
		$actorUsersInfo = $userModel->getUserInfo(array_keys($uniqueUsers));
		$actorList = array();
		foreach($actorUsers as $id_cnt => $dataArray) {
			foreach($dataArray['users'] as $index => $id_usr) {
				foreach($actorUsersInfo as $info) {
					if($info['id_usr'] == $id_usr) {
						$actorList[$id_cnt]['info'][$id_usr] = $info;
						$actorList[$id_cnt]['users'][] = $id_usr;
						$actorList[$id_cnt]['bin'][] = $dataArray['bin'][$index];
						$actorList[$id_cnt]['time'][] = $dataArray['time'][$index];
						continue 2;
					}
				}	
			}		
		}
		//print_r($actorUsers);die;
		//print_r($actorUsersInfo);
		//print_r($actorList);die;
		
		//print_r($sortedUpdated);die;
		foreach($sortedUpdated as $k => $contentArray) {
			foreach($contentArray as $id => $info) {
				$sortedUpdated[$k][$id] = $info['time'];
			}
		}
		//print_r($sortedUpdated);die;
		function compare($a,$b){if($a==$b)return(0);return((strtotime($a)>strtotime($b))?-1:1);}
		foreach($sortedUpdated as $key => $val) {uasort($sortedUpdated[$key],"compare");}
		
		$countsContents = array();
		foreach($updatedContents as $k => $binArray) {
			foreach($binArray as $l => $timeArray) {
				if(is_array($timeArray)) {
					foreach($timeArray as $time => $info) {
						$countsContents[$k][$l][$time] = $info['id_cnt']; 
					}
				}
			}
		}
		$updatedCounts = $this->_getCounts($countsContents);
		//print_r($updatedCounts);die;
		$updated = $this->_getContentInfo($updatedCounts);
		$followable = $this->_getFollows();
		//print_r($updated);die;
		$merge = array();
		foreach($updated as $k => $contentArray) {
			foreach($contentArray as $id_cnt => $info) {		
				$merge[$k][$id_cnt]['original'] = $info;
				$merge[$k][$id_cnt]['translated'] = null;
				
				$total = 0;
				foreach($updatedCounts as $l => $binArray) {
					foreach($binArray as $bin => $contentArray) {
						if(isset($contentArray[$id_cnt])) {
							$total += $contentArray[$id_cnt];
							$merge[$l][$id_cnt]['updates']['bins'][$followable[$bin]]['amount'] = $contentArray[$id_cnt];
						}
					}
				}
				$merge[$k][$id_cnt]['updates']['total'] = $total;
				foreach($actorList[$id_cnt]['time'] as $index => $data) {
					$followBinName = $followable[$actorList[$id_cnt]['bin'][$index]];
					$userId = $actorList[$id_cnt]['users'][$index];
					$userInfo = $actorList[$id_cnt]['info'][$userId];
					$userInfo = array_merge($userInfo, array('time' => $data));
					$merge[$k][$id_cnt]['updates']['bins'][$followBinName]['values'][] = $userInfo;
				}
				
			}
		}	
		
		//print_r($followable);die;
		$contents = array();
		//print_r($merge);die;
		//print_r($sortedUpdated);die;
		//print_r($actorList);die;
		
		foreach($sortedUpdated as $k => $contentArray) {
			foreach($contentArray as $id => $time) {
				$contents[$k][$id] = $merge[$k][$id];
				$userId = $actorList[$id]['users'][0];
				$bin = $followable[$actorList[$id]['bin'][0]];
				$contents[$k][$id]['updates']['latest'] = array_merge($actorList[$id]['info'][$userId], array('time' => $time, 'bin' => $bin));
			}
		}
		//print_r($contents);die;
		//print_r($merge);
		//print_r($updatedCounts);print_r($updated);
		//die;
		return $contents;
	}
	
	private function _noNewContents($updatedContents) {
		foreach($updatedContents as $arrayBin) {
			foreach($arrayBin as $value) {
				if(is_array($value)) return false;
			}
		}
		return true;
	}
	
	private function _getContentInfo($updatedCounts) {
		//print_r($updatedCounts);die;
		$uniqueContents = array();
		$contentsToFetch = array();
		$contents = array();
		foreach($updatedCounts as $k => $arrayBin) {
			foreach($arrayBin as $bin => $arrayContent) {
				if(is_array($arrayContent)) {
					foreach($arrayContent as $cnt_id => $count) {
						$uniqueContents[$k][$cnt_id] = 1;
						$contentsToFetch[$cnt_id] = 1;
					}
				}
			}
		}

		$contentsToFetch = array_keys($contentsToFetch);
		$contentModel = new Default_Model_Content();
		$contentInfo = $contentModel->getContentRows($contentsToFetch);
		
		foreach($contentInfo as $k => $content) {
			foreach($uniqueContents as $l => $arrayContents) {
				if(isset($arrayContents[$content['id_cnt']])) {
					$contents[$l][$content['id_cnt']] = $content;
					continue 2;
				}
			}
		}
		
		return $contents;
	}
	
	/**
	 * 
	 * @param unknown_type $contents
	 */
	private function _getCounts($contents) {
		$counts = array();
		foreach($contents as $k => $arrayBin) {
			foreach($arrayBin as $bin => $arrayContent) {
				$counts[$k][$bin] = empty($arrayContent) ? null : array_count_values($arrayContent);
			}
		}
		return $counts;
	}
	
	/**
	 * 
	 * @param unknown_type $id_usr
	 * @param unknown_type $follows
	 * @param unknown_type $type
	 */
	private function _fetchUpdatedContents($id_usr,$follows, $type) {
		$contents = array();
		$updatedContents = array();
		if($type == "own") {
			$userModel = new Default_Model_User();
			$contents = $userModel->getUsersContentsLastCheck($id_usr);
			$contents = $contents[$id_usr];
		}
		//print_r($contents);die;
		if($type == "fvr") {
			$contents = $this->getAllFavouriteContentIdsFromUserWithLastChecked($id_usr);
		}

		//print_r($contents);die;
		foreach($follows as $bin => $follow) {
			if($follow == "comment") { $temp = $this->_getNewComments($contents); }
			elseif($follow == "rating") { $temp = $this->_getNewRatings($contents); }
			elseif($follow == "linking") { $temp = $this->_getNewLinkings($contents);	}
			elseif($follow == "translation") { $temp = $this->_getNewTranslations($contents); }
			elseif($follow == "modified") {	$temp = $this->_getModified($contents); }
			if(empty($temp)) $temp = null;
			$updatedContents[$bin] = $temp;
		}
		//print_r($updatedContents);die;
		return $updatedContents;
	}
	
	/**
	 * 
	 * @param unknown_type $contentIds
	 */
	private function _getNewComments($contentIds) {
		$sqlIds = array_keys($contentIds);
		$select = $this->_db->select()->from(array('cmt' => 'comments_cmt'), 
                                             array('id_target_cmt',
                                             	   'created_cmt',
                                             	   'id_usr_cmt'))
                                             ->where('id_target_cmt IN (?)',$sqlIds)
                                             ->where('type_cmt = ?',2)
                                             ->order(array('created_cmt desc'))
                                             ;
                                          
        $result = $this->_db->fetchAll($select);

        $newComments = array();
        foreach($result as $res) {
        	if(strtotime($res['created_cmt']) > strtotime($contentIds[$res['id_target_cmt']]))
        		$newComments[] = array('id_cnt' => $res['id_target_cmt'],
        								'id_usr' => $res['id_usr_cmt'],
        								'time' => $res['created_cmt']);
        }
        
        return $newComments;
	}
	
	/**
	 * 
	 * @param unknown_type $contentIds
	 */
	private function _getNewRatings($contentIds) {
		$sqlIds = array_keys($contentIds);
		$select = $this->_db->select()->from(array('crt' => 'content_ratings_crt'), 
                                             array('id_cnt_crt',
                                             	   'created_crt',
                                             		'id_usr_crt'))
                                             ->where('id_cnt_crt IN (?)',$sqlIds)
                                             ->order(array('created_crt desc'))
                                             ;
                                          
        $result = $this->_db->fetchAll($select);

        $newRatings = array();
        foreach($result as $res) {
        	if(strtotime($res['created_crt']) > strtotime($contentIds[$res['id_cnt_crt']]))
        		$newRatings[] = array('id_cnt' => $res['id_cnt_crt'],
        							'id_usr' => $res['id_usr_crt'],
        							'time' => $res['created_crt']);
        }

        return $newRatings;
		
	}
	
	/**
	 * 
	 * @param unknown_type $contentIds
	 */
	private function _getNewLinkings($contentIds) {
		$sqlIds = array_keys($contentIds);
		$select = $this->_db->select()->from(array('chc' => 'cnt_has_cnt'), 
                                             array('id_parent_cnt',
                                             	   'created_cnt'))
                                             ->join(array('chu' => 'cnt_has_usr'),
                                             		'chc.id_child_cnt = chu.id_cnt',
                                             		array('id_usr' => 'chu.id_usr'))
                                             ->where('id_parent_cnt IN (?)',$sqlIds)
                                             ->where('chu.owner_cnt_usr = 1')
                                             ->order(array('created_cnt desc'))
                                             ;
                                          
        $result = $this->_db->fetchAll($select);

        $newLinkings = array();
        foreach($result as $res) {
        	if(strtotime($res['created_cnt']) > strtotime($contentIds[$res['id_parent_cnt']]))
        		$newLinkings[] = array('id_cnt' => $res['id_parent_cnt'],
        								'id_usr' => $res['id_usr'],
        								'time' => $res['created_cnt']);
        }

        return $newLinkings;
	}
	/**
	 * This function is yet to be implemented when translation feature is available.
	 * @param $contentIds
	 */
	private function _getNewTranslations($contentIds) {
		return; 
	}
	
	/**
	 * 
	 * @param array $contentIds
	 */
	private function _getModified($contentIds) {
		$sqlIds = array_keys($contentIds);
		$select = $this->_db->select()->from(array('cnt' => 'contents_cnt'), 
                                             array('id_cnt',
                                             	   'modified_cnt'))
                                             ->join(array('chu' => 'cnt_has_usr'),
                                             		'cnt.id_cnt = chu.id_cnt',
                                             		array('id_usr' => 'chu.id_usr'))
                                             ->where('cnt.id_cnt IN (?)',$sqlIds)
                                             ->where('chu.owner_cnt_usr = 1')
                                             ->order(array('modified_cnt desc'))
                                             ;
                                          
        $result = $this->_db->fetchAll($select);

        $newLinkings = array();
        foreach($result as $res) {
        	if(strtotime($res['modified_cnt']) > strtotime($contentIds[$res['id_cnt']]))
        		$newLinkings[] = array('id_cnt' => $res['id_cnt'],
        							'id_usr' => $res['id_usr'],
        							'time' => $res['modified_cnt']);
        }

        return $newLinkings;
	}
	
} // end of class

?>