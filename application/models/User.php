<?php
/**
 *  User -> User database model for user table.
 *
*     Copyright (c) <2009>, Markus Riihel�
*     Copyright (c) <2009>, Mikko Sallinen
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
 *  User - class
 *
 *  @package     models
 *  @author         Markus Riihel� & Mikko Sallinen
 *  @copyright     2009 Markus Riihel� & Mikko Sallinen
 *  @license     GPL v2
 *  @version     1.0
 */ 
class Default_Model_User extends Zend_Db_Table_Abstract
{
    // Table name
    protected $_name = 'users_usr';
    
    // Primary key of table
    protected $_primary = 'id_usr';
    
    // Tables model depends on
    protected $_dependentTables = array('Default_Model_UserProfiles', 'Default_Model_UserImages',
                                        'Default_Model_PrivateMessages', 'Default_Model_CommentRatings', 
                                        'Default_Model_Comments', 'Default_Model_ContentPublishTimes', 
                                        'Default_Model_ContentHasUser', 'Default_Model_UserHasGroup', 
                                        'Default_Model_Links', 'Default_Model_Files',
                                        'Default_Model_ContentRatings','Default_Model_UserHasFavourites',
                                        'Default_Model_UserHasNotifications','Default_Model_UserFavourites');

        
    // Table references  to other tables
    protected $_referenceMap    = array(
        'UserLanguage' => array(
            'columns'            => array('id_lng_usr'),
            'refTableClass'        => 'Default_Model_Languages',
            'refColumns'        =>    array('id_lng')        
        ),
        'CommentUser' => array(
            'columns'            => array('id_usr'),
            'refTableClass'        => 'Default_Model_Comments',
            'refColumns'        => array('id_usr_cmt')
        )
    );
    
    protected $_id = 0;
    protected $_data = null;
    
    /**
    *   __construct
    *
    *   User constructor.
    *
    *   @param integer $id User id value.
    */
    public function __construct($id = -1)
    {
        parent::__construct();
        
        $this->_id = $id;
        
        if ($id != -1){
            $this->_data = $this->find((int)$id)->current();
        } // end if
    }
        
    public function loginUser($data)
    {
        $id = $this->getIdByUsername($data['username']);
        $user = $this->find((int)$id)->current();
        $salt = $user['password_salt_usr'];
        $auth = Zend_Auth::getInstance();
        $authAdapter = new 
        Zend_Auth_Adapter_DbTable($this->getAdapter(),'users_usr');
        $authAdapter->setIdentityColumn('login_name_usr')
                    ->setCredentialColumn('password_usr');
        $authAdapter->setIdentity($data['username'])
                     ->setCredential(md5($salt.$data['password'].$salt));
				
        $result = $auth->authenticate($authAdapter);
        if($result->isValid())
        {
            $storage = new Zend_Auth_Storage_Session();
            $storage->write($authAdapter->getResultRowObject());
            return true;
        } 

        return false;
    }

        
    /**
    *    getUserImageData
    *
    *    Function to get users image data, if $thumb
    *    is true return data for thumbnail version of
    *    userimage, else return data for full version 
    *    of userimage.
    *
    *    FIX TODO:
    *    Currently when editing user settings, 
    *    new empty image is created to database.
    *    This function returns the most recently
    *    created image. Since new image is created
    *    anytime user edits his/her settings, 
    *    this function will return null if user
    *    has not added image with his/her most
    *    recent profile.
    *    FIXED: Works somehow, new empty images
    *    should not be created when editing 
    *    profile anymore.
    *
    *    @param integer $id is of user
    *    @param boolean $thumb is the image thumbnail
    *    @return array
    */
    public function getUserImageData($id=0, $thumb=true)
    {
        // Get image data        
        if ($id != 0) {
            // Check if we should get data for thumbnail
            // or the full image
            $thumbnail = $thumb ? 'thumbnail_usi' : 'image_usi'; 
        
            // Create query
            $select = $this->_db->select()
                                ->from('usr_images_usi', 
                                       array($thumbnail, 'created_usi', 'modified_usi'))
                                ->where('id_usr_usi = ?', $id)
                                ->order('modified_usi DESC');
            
            // Fetch data from database
            $result = $this->_db->fetchAll($select);
            
            // There's no need for this check here
            //if($result != null) {
            //    $imageData = $result[0][$thumbnail];
            //} else {
            //    $imageData = null;
            //}
            
           //$rowset = $this->find((int)$id)->current();
            
            //$row = $rowset->findDependentRowset('Default_Model_UserImages', 'UserUser')->current();
            
            // Basically same check as above
            // if (!empty($row)) {
            //if (!empty($imageData)){
                //$imageData = $thumb ? $row->thumbnail_usi : $row->image_usi;
                //  $imageData = $thumb ? $imageData : $imageData;
            //}
        }// end if
        
        // If there is no image return null
        //$hasImage = empty($imageData) ? null : $imageData;
        $hasImage = $result != null ? $result[0] : null;
        
        return $hasImage;
    } // end of getUserImageData
    
    /**
    * userHasProfileImage
    *
    * Function to get users thumbnail image data
    *
    * @param $id integer userid
    * @return boolean
    */
    public function userHasProfileImage($id = 0)
    {
        // Get image data        
        if ($id != 0) {
            $rowset = $this->find((int)$id)->current();
            
            $row = $rowset->findDependentRowset('Default_Model_UserImages', 'UserUser')->current();
            
            if(isset($row->thumbnail_usi) && isset($row->image_usi)) {
                //$image_data = $row->thumbnail_usi;
                return true;
            } // end if
        } // end if
        
        // If there is no image return null
        return false;//empty($image_data) ? null : $image_data;    
    } // end of userHasProfileImage
    
    /**
    *    loginSuccess
    *
    *    Log successfull user logins 
    *
    */
    public function loginSuccess()
    {
        //$this->getUserRow();
        
        // Update users last login time
        $this->_data->last_login_usr = new Zend_Db_Expr('NOW()');
        $this->_data->save();
        
    } // end of loginSuccess
    
    /**
    *    createAuthIdentity
    *
    *    Creates users identity for session
    *
    *    @return array
    */
    public function createAuthIdentity()
    {
        //$this->getUserRow();
        $identity = new stdClass;
        
        $identity->user_id = $this->_data->id_usr;
        $identity->username = $this->_data->login_name_usr;
        //$identity->user_type = $this->userlevel;
        //$identity->firstname = $this->profile->firstname;
        //$identity->surname = $this->profile->surname;
        //$identity->email = $this->profile->email;
        $identity->created = $this->_data->created_usr;
        return $identity;
    } // end of createAuthIdentity
    
    /**
    * registerUser Adds user register data to database
    *
    * Removed logic and validation from here, let's trust the isValid function
    *
    * @todo user languages
    * @param array $formData register form data
    */    
    public function registerUser($formData = null)
    {
        if ($formData == null) {
            return false;
        }
        
        // Create new empty user row
        $row = $this->createRow();
        
        // Set user data (needs sanitation)
        $row->login_name_usr = htmlentities($formData['username']);
        $row->email_usr = htmlentities($formData['email']);
        
        // Set language to some random language (needs fixing)
        $row->id_lng_usr = 12;
        
        // Generate salt
        $salt = $this->generateSalt();
        
        // Create and set password hash - md5(salt + password string + salt)
        $row->password_usr = md5($salt.$formData['password'].$salt);
        $row->password_salt_usr = $salt;
        
        $row->created_usr = new Zend_Db_Expr('NOW()');
        $row->modified_usr = new Zend_Db_Expr('NOW()');
        
        // Save user data
        return $row->save();
    } // end of registerUser
        
    /**
    *    generateSalt
    *
    *    Generates random salt for password hashing and returns it.
    *
    *    @param integer $length length of salt
    *    @return string
    */
    public function generateSalt($length = 128)
    {
        // Valid characters
        $pattern = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        
        // Get count of valid characters
        $k = strlen($pattern) - 1;
        
        // Get random character from valid characters and add it to output
        $salt = $pattern{rand(0, $k)};
        
        for($i = 1; $i < $length; $i++) {
            $salt .= $pattern{rand(0, $k)};
        } // end for
    
        return $salt;
    } // end of generateSalt
    
    /**
    *    Create a new password for user and set it to profile to wait activation and send email to user
    */
    /*
    public function fetchPassword($lang)
    {
    
        if (!$this->isSaved())
        {
            return false;
        }
        // generate new password properties
        $this->newPassword = $this->createRandomPassword();
        $this->profile->new_password = md5($this->newPassword);
        $this->profile->new_password_ts = time();
        $this->profile->new_password_key = md5(uniqid() .
        $this->getId() .
        $this->newPassword);
        // save new password to profile and send e-mail
        $this->profile->save();
        
        // Send password to user
        $mail = new Zend_Mail();
        $emailText = '';
        
        // open the resetpassword email template text with correct language
        $handle = @fopen('../application/layouts/passwordreset_email_plain_'.$lang .'.txt', 'r');
        if ($handle) {
            // read first line of file to be the mail subject
            $mailSubject = fgets($handle);
            while (!feof($handle)) {
                // read rest of the file to the mail text
                $emailText .= fgets($handle);
            }
            fclose($handle);
        
        
            $activation_link = 'http://oibs.projects.tamk.fi/account/fetchpassword?action=confirm&id='.$this->userid.'&key='.$this->profile->new_password_key.'';
            
            // insert username and password to text
            $emailText = str_replace('<activation_link>', $activation_link, $emailText);
            $emailText = str_replace('<username>', $this->username, $emailText);
            $emailText = str_replace('<password>', $this->newPassword, $emailText);
            
            $mail->setBodyText($emailText, "UTF-8");
            $mail->setFrom('noreply@oibs.tamk.fi');
            $mail->addTo($this->profile->email);
            $mail->setSubject(trim($mailSubject));
            $mail->send();
            
            return true;
        }
    }
    */
    
    /**
    *    getUserByName
    *
    *    Get user data by username
    *
    *    @param string $username
    *    @return array
    */
    public function getUserByName($username = null)
    {
        /*
        $select = $this->_db->select()
            ->from('users_usr', array('*'))
            ->where('login_name_usr = ?', $username);
        
        $stmt = $this->_db->query($select);

        $result = $stmt->fetchAll();
        */
        
        $where = $this->select()->where('login_name_usr = ?', $username);
        $result = $this->fetchAll($where)->current();
        /*
        echo '<pre>';
        print_r($result);
        echo '</pre>';
        */
        if (!empty($result)) {
            return $result;
        }
        else {
            return null;
        }
    } // end of getUserByName
    
    
    /**
    *    getUserRow
    *
    *    Get user data by id
    *
    *    @param integer $id user id
    *    @return array
    */
    public function getUserRow($id = -1)
    {
        if($id == -1) {
            $id = $this->_id;
        } // end if
        
        return $this->find((int)$id)->current();
    } // end of getUserRow
    
    /**
    * getUserContent
    *
    * Get (all) content from a specific user. This is a horrible, horrible 
    * function but will have to do for now. 
    *
    * Edited 11/09/2009 by Pekka Piispanen:
    * Removed the retrieval of user id and username. That data was never needed
    * when this function was used, and that data caused unnecessary empty row
    * to user's content row, so the check if user hasn't got any contents failed.
    *
    * Edited 19.5.2010 by Mikko Korpinen
    * For edit links in user pages array need cntHasCntCount (edit links will be
    * showing if content has any content.
    *
    * @author Pekka Piispanen
    * @author Joel Peltonen
    * @author Mikko Aatola
    * @todo pagination
    * @todo ignore parameter / functionality to leave out a content
    * @todo splitting model-specific selects to their own models
    * @todo the functionality where this is used should be ajaxified
    * @param integer $author_id id of whose content to get
    * @param string $type limit search to a specific content type
    * @param integer $id_cnt 	id to be skipped
    * @return array
    */    
    public function getUserContent($author_id = 0, $type = 0, $id_cnt = 0, $limit = -1)
    {
        $result = array();  // container for final results array
        
        $whereType = 1;
        if($type !== 0) {
            $whereType = $this->_db->quoteInto('cty.key_cty = ?', $type);
        } else {
            $whereType = '1 = 1';
        }
        
        // If author id is set get users content
        if ($author_id != 0) {
            //if($count == -1) {
                $contentSelect = $this->_db->select()
                                           ->from(array('chu' => 'cnt_has_usr'), 
                                                  array('id_usr', 'id_cnt'))
                                           ->joinLeft(array('cnt' => 'contents_cnt'),         
                                                  'cnt.id_cnt = chu.id_cnt',
                                                  array('id_cnt', 'id_cty_cnt', 'title_cnt', 
                                                        'lead_cnt', 'language_cnt', 'published_cnt', 'created_cnt'))
                                           ->joinLeft(array('cty' => 'content_types_cty'),    
                                                  'cty.id_cty = cnt.id_cty_cnt',  
                                                  array('key_cty'))
                                           ->joinLeft(array('vws' => 'cnt_views_vws'),
                                                      'vws.id_cnt_vws = cnt.id_cnt',
                                                      array('views' => 'SUM(vws.views_vws)'))
                                           ->joinLeft(array('crt' => 'content_ratings_crt'),
                                                      'cnt.id_cnt = crt.id_cnt_crt',
                                                      array('ratings' => 'COUNT(DISTINCT crt.id_crt)'))
                                           ->joinLeft(array('cmt' => 'comments_cmt'),
                                                      'cnt.id_cnt = cmt.id_cnt_cmt',
                                                      array('comments' => 'COUNT(DISTINCT cmt.id_cmt)'))
                                           ->joinLeft(array('chc1' => 'cnt_has_cnt'),
                                                      'cnt.id_cnt = chc1.id_parent_cnt',
                                                      array('cntHasCntCountParent' => 'COUNT(DISTINCT chc1.id_child_cnt)'))
                                           ->joinLeft(array('chc2' => 'cnt_has_cnt'),
                                                      'cnt.id_cnt = chc2.id_child_cnt',
                                                      array('cntHasCntCountChild' => 'COUNT(DISTINCT chc2.id_parent_cnt)'))
                                           ->joinLeft(array('cmpHasCnt' => 'cmp_has_cnt'),
                                                      'cnt.id_cnt = cmpHasCnt.id_cnt',
                                                      array('cmpHasCntCount' => 'COUNT(DISTINCT cmpHasCnt.id_cmp)'))
                                           ->where('chu.id_usr = ?', $author_id)
                                           ->where($whereType)
                                           ->where('cnt.id_cnt != ?', "") // Odd hack
                                           ->where('cnt.id_cnt != ?', $id_cnt)
                                           ->order('cnt.created_cnt DESC')
                                           ->group('cnt.id_cnt')
				;
				if($limit != -1) $contentSelect->limit($limit);

                $result = $this->_db->fetchAll($contentSelect);
                
            //}
            //else {
            //    $select = $this->select()->limitPage($page, $count)
            //                             ->order('id_cty_cnt ASC')
            //                             ->order('created_cnt DESC');
            //}
            
            //$row = $this->find((int)$author_id)->current();
            //$result = $row->findDefault_Model_ContentViaDefault_Model_ContentHasUser($select);
        } // end if        

        return $result;
    } // end of getUserContent

    public function getSimpleUserDataById($id = -1) 
    {
        $data = array();
        
        if ($id != -1) {
            $select = $this->_db->select()
                            ->from(array('users_usr' => 'users_usr'), array('id_usr', 'id_lng_usr', 'login_name_usr', 'created_usr'))
                            ->where('id_usr = ?', $id)
            ;
            $result = $this->_db->fetchAll($select);
            
            if (count($result == 1)) $data = $result;
        }
        
        return $data[0];
    }
    
    /*
    *   getUserListing
    *
    *   Gets user listing with filtering options.
    *
    *   @params array $filter $page, $count $order $list
    *   @return array
    *   @author Jari Korpela
    */
	public function getUserListing(&$filter = null, $page = 1, $count = 10, $order = 'login', $list = 'DESC', &$listSize = 1)
    {
    	//For some odd reason this order and list default set has to be done here and wont work on ^...
		if(!$order) $order = 'username';
		if(!$list) $list = 'ASC';
		
		//Get full sorted and filtered userIdList
    	$userIdList = $this->sortAndFilterUsers(&$filter, $order, $list);
    	$listSize = sizeof($userIdList);
    	
    	if($listSize == 0) return array(); //If list size is 0, we just return
    	
    	//Then we choose the part of id list we want to show and collect data on
    	$userIdListCut = array();
    	$i = ($page-1)*$count;
    	$limit = $i+$count;
    	for($i; $i < $limit; $i++) {
    		if(!$userIdList[$i]) break;
    		$userIdListCut[] = $userIdList[$i];
    	}
    	
    	$userIdList = $userIdListCut; //We replace the whole list with the user Ids we want (this is for final ordering purpose)
    	$userInfo = $this->getUserInfo($userIdList); //Get basic user information
    	$userData = $userInfo; //We start to collect data about users to $userData array
    	
    	$userContents = $this->getUsersContents($userIdList); //Get all content ID's from users in id list

    	//Add these contents to $userData array in which we collect data (if user doesnt have content we add empty array)
    	foreach($userData as $key => $data) {
    		 if (!$userContents[$data['id_usr']])
    		 	 $userContents[$data['id_usr']] = array();
    		 $userData[$key]['contents'] = $userContents[$data['id_usr']];
    		 
    	}
    	ksort($userContents); //We sort $userContents again because we might have added empty arrays

    	//Calculate contentCounts for users.
    	foreach($userData as $key => $data) {
    		$userData[$key]['contentCount'] = sizeof($data['contents']);
    	}
    	
    	//Get Ratings statistics
    	$userRatings = $this->getUserContentRatings($userContents);
    	$userData = $this->intersectMergeArray($userData,$userRatings);
    	
    	//Get location info
    	$userLocations = $this->getUsersLocation($userIdList);
    	$userData = $this->intersectMergeArray($userData,$userLocations);
    	
    	//Finally we sort the $userData array to same order as our $userIdList
    	$final = array();
    	foreach($userIdList as $id) {
    		foreach($userData as $data) {
    			if ($data['id_usr'] == $id) {
    				$final[] = $data;
    				continue 2;
    			}
    		}
    	}

        return $final;
    }
    
    /*
     * sortAndFilterUsers
     * 
     * Sort and Filter Users
     * 
     * @param array $filter
     * @param string $order
     * @param string $list
     * @author Jari Korpela
     */
    private function sortAndFilterUsers(&$filter, $order, $list) {

		$orderGroups = array(
			'userInfo' => array(
						'username' => 'login_name_usr',
						'joined' => 'created_usr',
						'login' => 'last_login_usr'),
			'contentInfo' => array('content' => 'COUNT(id_cnt)'),
			'contentViews' => array('views' => 'COUNT(id_cnt_vws)'),
			'contentPopularity' => array('popularity' => 'COUNT(id_usr_vws)'),
			'contentRatings' => array('rating' => 'SUM(rating_crt)'),
			'contentComments' => array('comments' => 'COUNT(id_cmt)')
		);
		
        $groupName = "";
        foreach($orderGroups as $key => $group) {
        	if($group[$order]) {
        		$groupName = $key;
        	}
        }

   		if($order) $sort = $orderGroups[$groupName][$order]." ".$list;
        else $sort = "id_usr";

        $select = $this->select()->from($this, 'id_usr')
                                 ->order('id_usr');
                                 
	        if($filter['city'] != "")
	          $select->where('id_usr IN (?)',$this->getCityFilter($filter['city']));
	
	        if($filter['username'] != "")
	          $select->where('id_usr IN (?)',$this->getUsernameFilter($filter['username']));  
	          
	        if($filter['country'] != "0")
	          $select->where('id_usr IN (?)',$this->getCountryFilter($filter['country']));
	        
	        if($filter['group'] != "")
	          $select->where('id_usr IN (?)',$this->getGroupFilter($filter['group'],$filter['exactg']));
	                                 
        $result = $this->_db->fetchAll($select);      
        if(!$result) return array();
        
        $userIDList = $this->simplifyArray($result,'id_usr');
        
        if($groupName == "userInfo")
       		$output = $this->sortByUserInfo($userIDList, $sort, $list);
        elseif($groupName == "contentInfo")
        	$output = $this->sortUsersByContentInfo($userIDList, $sort, $list, null);
        elseif($groupName == "contentViews")
        	$output = $this->sortUsersByViews($userIDList, $sort, $list, null);
        elseif($groupName == "contentRatings")
        	$output = $this->sortUsersByRating($userIDList, $sort, $list, null);
        elseif($groupName == "contentPopularity")
        	$output = $this->sortUsersByPopularity($userIDList, $sort, $list, null);	
        elseif($groupName == "contentComments")	
        	$output = $this->sortUsersByComments($userIDList, $sort, $list, null);
        return $output;
        
    }
    
    /**
     * intersectMergeArray
     * 
     * This function merges data in same keys in 2 arrays together 
     * 
     * @param array $arr1
     * @param array $arr2
     * @return array
     * @author Jari Korpela
     */
    private function intersectMergeArray($arr1,$arr2) {
    	if((!(array)$arr1 )|| (!(array)$arr2)) return false;
    	$merged_array = array();
    	foreach($arr1 as $key => $a) {
    		$merged_array[$key] = array_merge($a, $arr2[$key]);
    	}
    	return $merged_array;
    } 

    /**
     * simplyfyArray
     * 
     * There might be function in Zend so we dont need this but I didnt find, perhaps you can? ;)
     * This function makes associative array to nonassociative 
     * 
     * @param $result
     * @return non associative array $userIdList
     * @author Jari Korpela
     */
    private function simplifyArray($result,$by) {
        $userIDList = array();

        foreach($result as $res) {
           $userIDList[] = $res[$by];
        }
        return $userIDList;
    }
    
    private function addMissingIdsToResult($result, $userIDList, $list) {
        if($list == "desc") {
	        foreach($userIDList as $id) {
	        	if(!in_array($id,$result)) $result[] = $id;
	        }
        }
        elseif($list == "asc") {
        	$final = array();
        	foreach($userIDList as $id) {
	        	if(!in_array($id,$result)) $final[] = $id;
	        }
	        foreach($result as $res) {
	        	$final[] = $res;
	        }
	        $result = $final;
        }
        return $result;
    }
    
    private function finalizeToSortingOrderByUserId($arr1,$arr2) {
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
    
    /*
     * getUserIds
     * 
     * This function retrieves all user ID:s
     * 
     * @return array
     * @author Jari Korpela
     */
    public function getUserIds() {
    	$select = $this->select()->from($this, 'id_usr')
                                 ->order('id_usr');
        $result = $this->simplifyArray($this->_db->fetchAll($select),'id_usr');
        return $result;
    } 
    
    /*
     * getUsersLocation
     * 
     * Gets users locations (city and country)
     * 
     * @param array $userIdList
     * @return array $list
     * @author Jari Korpela
     */
    private function getUsersLocation($userIdList) {
    	sort($userIdList);
    	$select = $this->_db->select()->from(array('usp' => 'usr_profiles_usp'),
                                      			array('id_usr_usp','profile_key_usp',
                                      			'profile_value_usp'))
                                      ->joinLeft(array('usc' => 'countries_ctr'),
                                      			 'usc.iso_ctr = usp.profile_value_usp AND usp.profile_key_usp = "country"',
                                      			 array('countryName' => 'usc.printable_name_ctr',
                                      			 	   'countryIso' => 'usc.iso_ctr'))
                                      ->where('usp.id_usr_usp IN (?)', $userIdList)
                                      ->where('usp.public_usp = 1')
                                      ->where('usp.profile_key_usp = "city" OR usp.profile_key_usp = "country"')
                                      ->group(array('usp.id_usr_usp','usp.id_usp'))
                                      ->order('usp.id_usr_usp')
                                      ;
       $result = $this->_db->fetchAll($select);
       
	   $list = array();
	   foreach($userIdList as $id) {
	   	$city = "";
	   	$country = "";
	   	$countryIso = "";
	   	$checks = 0;
	   	foreach($result as $res ) {
	   		if($res['id_usr_usp'] == $id) {
	   			$checks++;
	   			if($res['profile_key_usp'] == "city") {
	   				$city = $res['profile_value_usp'];
	   			}
	   			elseif($res['profile_key_usp'] == "country") {
	   				$country = $res['countryName'];
	   				$countryIso = $res['countryIso'];
	   			}
	   		}
	   		if(($country != "" && $city != "") || $checks == 2) break;
	   	}
	   	$list[] = array(
   				'id_usr' => $id,
   				'city'	=> $city,
	   			'country' => $country,
	   			'countryIso' => $countryIso
   				);
	   }
       return $list;                             
    }
     
    /*
     * getUsersViews
     * 
     * gets users views count
     * 
     * @param array $userIDList
     * @return $resultList
     * @author Jari Korpela
     */   
    public function getUsersViews($userIDList) {

    	$select = $this->_db->select()->from('cnt_views_vws',
    									array('id_usr' => 'id_usr_vws',
    										'value' => 'COUNT(id_cnt_vws)'))
    							->where('id_usr_vws IN (?)',$userIDList)
    							->group('id_usr_vws')
    							->order('id_usr_vws')
    							;
    							
        $result = $this->_db->fetchAll($select); 
        
		return $result;
    }    
    
    /*
     * getUsersPopularity
     * Popularity means how many unique users has viewed users contents
     * 
     * @param array $userIDList
     * @return $resultList
     * @author Jari Korpela
     */   
    public function getUsersPopularity($userIDList) {

    	$select = $this->_db->select()->from(array('cnt' => 'cnt_has_usr'),
    									array('id_usr'))
    									->joinLeft(array('vws' => 'cnt_views_vws'),
    											'cnt.id_cnt = vws.id_cnt_vws',
    									array('value' => 'COUNT(id_usr_vws)'))
    							->where('cnt.id_usr IN (?)',$userIDList)
    							->group('cnt.id_usr')
    							->order('cnt.id_usr')
    							;
        $result = $this->_db->fetchAll($select);

		return $result;
    }
    
    /*
     * getUsersRating
     * 
     * Gets users rating sum
     * 
     * @param array $userIDList
     * @return $resultList
     * @author Jari Korpela
     */   
    public function getUsersRating($userIDList) {

    	$select = $this->_db->select()->from(array('cnt' => 'cnt_has_usr'),
    									array('id_usr'))
    									->joinLeft(array('crt' => 'content_ratings_crt'),
    												'crt.id_cnt_crt = cnt.id_cnt',
    												array('value' => 'SUM(rating_crt)'))
    							->where('id_usr IN (?)',$userIDList)
    							->group('id_usr')
    							->order('id_usr')
    							;
					
        $result = $this->_db->fetchAll($select);

		return $result;
    }
    
    /*
     * getUsersCommentCount
     * 
     * Gets users comment count
     * 
     * @param array $userIDList
     * @return $resultList
     * @author Jari Korpela
     */   
    public function getUsersCommentCount($userIDList) {

    	$select = $this->_db->select()->from('comments_cmt',
    									array('id_usr' => 'id_usr_cmt',
    										'value' => 'COUNT(id_cmt)'))
    							->where('id_usr_cmt IN (?)',$userIDList)
    							->group('id_usr_cmt')
    							->order('id_usr_cmt')
    							;

        $result = $this->_db->fetchAll($select); 
 
		return $result;
    }       
    
    /*
     * getUserContentRatings
     * 
     * Gets Users Content Ratings info
     * 
     * @param array $userContents as $userId => $content
     * @return array $list
     * @author Jari Korpela
     */
    private function getUserContentRatings($userContents) {
    	$userRatings = array();
    	foreach($userContents as $userId => $content) {
    		if($content) {
	    	$select = $this->_db->select()->from(array('crt' => 'content_ratings_crt'),
	                                             array('ratingAveragePositive' => 'CEIL(((SUM(crt.rating_crt) / COUNT(DISTINCT crt.id_cnt_crt))+1)*50)',
	                                                   'ratingAverageNegative' => 'FLOOR(100-((SUM(crt.rating_crt) / COUNT(DISTINCT crt.id_cnt_crt))+1)*50)',
	                                                   'ratingRatioPositive' => 'CEIL((SUM(crt.rating_crt) / COUNT(crt.id_cnt_crt)+1)*50)',
	                                                   'ratingRatioNegative' => 'FLOOR(100-(SUM(crt.rating_crt) / COUNT(crt.id_cnt_crt)+1)*50)',
	                                                   'ratingAmount' => 'COUNT(crt.id_cnt_crt)',
	                                                   'ratedContents' => 'COUNT(DISTINCT crt.id_cnt_crt)'))
	                                      ->where('id_cnt_crt IN (?)',$content)
	                                      ->order('id_cnt_crt')
	                                     ;
	        $result = $this->_db->fetchAll($select);
	        $userRatings[] = array_merge(array('id_usr' => $userId),$result[0]);
    		}
    		else {
    			$userRatings[] = array_merge(array('id_usr' => $userId),array(
						    		'ratingAveragePositive' => '',
						            'ratingAverageNegative' => '',
						            'ratingRatioPositive' => '',
						            'ratingRatioNegative' => '',
						            'ratingAmount' => '',
						            'ratedContents' => ''  			
    								));
    		}
    	}
        return $userRatings;
    }
    
    /*
     * getUserInfo 
     * 
     * Gets basic user information from users_usr table
     * 
     * @param array $userIdList
     * @return array $list
     * @author Jari Korpela
     */
	public function getUserInfo($userIdList) {
		$select = $this->_db->select()->from(array('usr' => 'users_usr'), 
                                             array('id_usr',
                                                   'login_name_usr',
                                                   'last_login_usr',
                                                   'created_usr'))
                                             ->where('id_usr IN (?)',$userIdList)
                                             ->order('id_usr')
                                             ;
        return $this->_db->fetchAll($select);                                      
    }

    /*
     * getUsersContentCount
     * 
     *  Gets Users content counts
     *  
     *  @param array $userIdList
     *  @return array $list
     *  @author Jari Korpela
     * 
     */
    public function getUsersContentCount($userIdList) {
    	sort($userIdList);
    	
    	$select = $this->_db->select()->from(array('chu' => 'cnt_has_usr'), 
                                             array('id_usr',
                                             	   'value' => 'COUNT(id_cnt)'))
                                             ->where('id_usr IN (?)', $userIdList)
                                             ->group('id_usr')
                                             ->order('id_usr')
                                             ;
                        
        $result = $this->_db->fetchAll($select);
        $resultList = array();

        foreach($userIdList as $id) {
        	foreach($result as $user) {
        		if($user['id_usr'] == $id) {
        			$resultList[] = $user;
        			continue 2;
        		}
        	}

        	$resultList[] = array('id_usr' => $id, 'value' => 0);
        }
                
        return $resultList;
    }
    
    /*
     * getUsersContents
     * 
     *  Gets users content ID:s
     *  
     *  @param array $userIdList
     *  @return array $list
     *  @author Jari Korpela     *
     */
    private function getUsersContents($userIdList) {
    	$select = $this->_db->select()->from(array('chu' => 'cnt_has_usr'), 
                                             array('id_usr',
                                             	   'id_cnt'))
                                             ->group('id_cnt')
                                             ->where('id_usr IN (?)',$userIdList)
                                             ->order(array('id_usr','id_cnt DESC'))
                                             ;
        $result = $this->_db->fetchAll($select);
        
        $contentArray = array();
        
        foreach($result as $res) {
        	$contentArray[$res['id_usr']][] = $res['id_cnt'];
        }
        
        return $contentArray;
        
    }
    
    private function getUserStatisticsContentTypes($contentIdList) {
		
    	$select = $this->_db->select()->from(array('cnt' => 'contents_cnt'),
    											array())
    									->joinLeft(array('cty' => 'content_types_cty'),    
                                                  'cty.id_cty = cnt.id_cty_cnt',  
                                                  array('type' => 'key_cty',
                                                  'amount' => 'COUNT(key_cty)'))
                                        ->where('id_cnt IN (?)',$contentIdList)
                                        ->group('key_cty')
         ;
                           
        $result = $this->_db->fetchAll($select);
        
        return $result;                                 
    }
    
    /*
     * sortUsersByContentInfo
     * 
     * Sorts $userIdList by $sort
     * 
     * @param array $userIDList
     * @param string $sort
     * @return $resultList
     * @author Jari Korpela
     */    
    public function sortUsersByContentInfo($userIDList, $sort, $list, $limit) {
    	$content = new Default_Model_ContentHasUser(); 
    	$select = $content->select()->from('cnt_has_usr',
    									array('id_usr'))
    							->where('id_usr IN (?)',$userIDList)
    							->order(array($sort,'id_usr'))
    							->group('id_usr')    							
    							;
    	if($limit) $select->limit($limit,0);
    	//print_r($select->assemble());echo "\n";
        $result = $this->simplifyArray($content->_db->fetchAll($select),'id_usr');
		if($list) $result = $this->addMissingIdsToResult($result, $userIDList, $list);
        
        return $result;
    }
    
    /*
     * sortByUserInfo
     * 
     * Sorts $userIdList by $sort
     * 
     * @param array $userIDList
     * @param string $sort
     * @return $resultList
     * @author Jari Korpela
     */   
    private function sortByUserInfo($userIDList, $sort) {
    	$select = $this->select()->from($this,
    									array('id_usr'))
    							->where('id_usr IN (?)',$userIDList)
    							->order($sort)
    							;
        $result = $this->_db->fetchAll($select);
        
		return $this->simplifyArray($result,'id_usr');
    }
    
    /*
     * sortUsersByViews
     * 
     * Sorts $userIdList by $sort
     * 
     * @param array $userIDList
     * @param string $sort
     * @return $resultList
     * @author Jari Korpela
     */   
    public function sortUsersByViews($userIDList, $sort, $list, $limit) {

    	$select = $this->_db->select()->from('cnt_views_vws',
    									array('id_usr_vws'))
    							->where('id_usr_vws IN (?)',$userIDList)
    							->group('id_usr_vws')
    							->order(array($sort,'id_usr_vws'))
    							;
    	if($limit) $select->limit($limit,0);
        $result = $this->simplifyArray($this->_db->fetchAll($select),'id_usr_vws'); 
        if($list) $result = $this->addMissingIdsToResult($result, $userIDList, $list);
        
		return $result;
    }
    
    /*
     * sortUsersByComments
     * 
     * Sorts $userIdList by $sort
     * 
     * @param array $userIDList
     * @param string $sort
     * @return $resultList
     * @author Jari Korpela
     */   
    public function sortUsersByComments($userIDList, $sort, $list, $limit) {

    	$select = $this->_db->select()->from('comments_cmt',
    									array('id_usr_cmt'))
    							->where('id_usr_cmt IN (?)',$userIDList)
    							->group('id_usr_cmt')
    							->order(array($sort,'id_usr_cmt'))
    							;
    	if($limit) $select->limit($limit,0);
        $result = $this->simplifyArray($this->_db->fetchAll($select),'id_usr_cmt'); 
        if($list) $result = $this->addMissingIdsToResult($result, $userIDList, $list);
        
		return $result;
    }    
    
    /*
     * sortUsersByPopularity
     * Popularity means how many unique users has viewed users contents
     * Sorts $userIdList by $sort
     * 
     * @param array $userIDList
     * @param string $sort
     * @return $resultList
     * @author Jari Korpela
     */   
    public function sortUsersByPopularity($userIDList, $sort, $list, $limit) {

    	$select = $this->_db->select()->from(array('cnt' => 'cnt_has_usr'),
    									array('id_usr'))
    									->joinLeft(array('vws' => 'cnt_views_vws'),
    											'cnt.id_cnt = vws.id_cnt_vws',
    									array('readers' => 'COUNT(id_usr_vws)'))
    							->where('cnt.id_usr IN (?)',$userIDList)
    							->group('cnt.id_usr')
    							->order(array($sort,'id_usr'))
    							;
    	if($limit) $select->limit($limit,0);
        $result = $this->_db->fetchAll($select);
        $result = $this->simplifyArray($result,'id_usr');
        if($list) $result = $this->addMissingIdsToResult($result, $userIDList, $list);
        
		return $result;
    }
     
    /*
     * sortUsersByRating
     * 
     * Sorts $userIdList by $sort
     * 
     * @param array $userIDList
     * @param string $sort
     * @return $resultList
     * @author Jari Korpela
     */   
    public function sortUsersByRating($userIDList, $sort, $list, $limit) {

    	$select = $this->_db->select()->from(array('cnt' => 'cnt_has_usr'),
    									array('id_usr'))
    									->joinLeft(array('crt' => 'content_ratings_crt'),
    												'crt.id_cnt_crt = cnt.id_cnt',
    												array())
    							->where('id_usr IN (?)',$userIDList)
    							->group('id_usr')
    							->order(array($sort,'id_usr'))
    							;
    	if($limit) $select->limit($limit,0);						
        $result = $this->_db->fetchAll($select);
        $result = $this->simplifyArray($result,'id_usr');
        if($list) $result = $this->addMissingIdsToResult($result, $userIDList, $list);
        
		return $result;
    }
    
    /**
    *   getCityFilter
    *
    *   Gets user search city filter.
    *
    *   @param int $city
    *   @return string
    */
    private function getCityFilter(&$city) 
    {
    	
        $profile = new Default_Model_UserProfiles();
    	$select = $profile->select()->from($profile, 'id_usr_usp')
    								->where('public_usp = 1 AND profile_key_usp = "city" AND profile_value_usp LIKE ?','%'. $city. '%');
        
        return $select;
    }
    
    
    /**
    *   getCountryFilter
    *
    *   Gets user search city filter.
    *
    *   @param int $country
    *   @return string
    */
    private function getCountryFilter(&$country) 
    {

        $profile = new Default_Model_UserProfiles();
    	$select = $profile->select()->from($profile, 'id_usr_usp')
    								->where('public_usp = 1 AND profile_key_usp = "country" AND profile_value_usp = ?', $country);
        						
        return $select;
    }
    
    /**
    *   getGroupFilter
    *
    *   Get Group Filter.
    *
    *   @param string $group
    *   @return string
    */    
    private function getGroupFilter(&$group,$exactg) 
    {
    	
        if($exactg == "1") {
    		$select1 = $this->_db->select()->from('usr_groups_grp', 'id_grp')
	    								->where('group_name_grp = ?',$group);
    	}
    	else { 
	    	$select1 = $this->_db->select()->from('usr_groups_grp', 'id_grp')
	    								->where('group_name_grp LIKE ?','%'. $group .'%');
    	}
    							
		$select = $this->_db->select()->from('usr_has_grp', 'id_usr')
    								->where('id_grp IN (?)', $select1);
        return $select;
    } 
    
    /**
    *   getUsernameFilter
    *
    *   Get user search username filter.
    *
    *   @param string $username
    *   @return string
    */
    private function getUsernameFilter(&$username) 
    {
    	$select = $this->select()->from($this, 'id_usr')
    								->where('login_name_usr LIKE ?','%'. $username. '%');
        
        return $select;
    } 
    
    
    /**
    *   getUserSearchContentCountFilter
    *
    *   Get user search content count filter.
    *
    *   @param int $count
    *   @param int $type
    *   @return string
    */
    /*public function getUserSearchContentCountFilter(&$count, &$type) 
    {
        $result = 1;
        if(!empty($count) && 
            $char = $this->checkContentCount($type)) {
            $result = $this->_db->quoteInto('contentCount ' . $char . ' ?', 
                                                  $count);
        }
        
       return $result;
    }
    */

    /**
    *    checkContentCount
    *
    *    Checks content count to given limit and comparison type.
    *
    *    @params integer action comparision type
    *    @params integer limit user given limit value
    *    @params integer count content count
    *    @return boolean
    */
    //private function checkContentCount(&$action/*, &$limit, &$count*/)
    /*{
        switch($action) {
            case 0:
                //if($count > $limit) {
                    return '>';
                //}
                //break;
            case 1:
                //if($count < $limit) {
                    return '<';
                //}
                //break;
            case 2:
                //if ($count == $limit) {
                    return '=';
                //}
                //break;
            default:
                return false;        
        }
    }
    */
    
	/*
    *   changeUserEmail
    *
    *   Changes the e-mail address of the user.
    *
    *   @return N/A
    */
	public function changeUserEmail($id = -1, $email_address)
	{
		// this is the simplest possible example of the update() -function :) -sokuni
		$data = array('email_usr' => $email_address);			
		$where = $this->getAdapter()->quoteInto('id_usr = ?', $id);
		$this->update($data, $where);	
	}
	
    /**
    * getUserEmail
    *
    * @author joel peltonen
    * @param id userid
    * @return string e-mail-address of user
    */
    public function getUserEmail($id = -1){
        if ($id == -1) {
            return false;
        }
        
        $select = $this->select()
                    ->from($this, array('email_usr'))
                    ->where('id_usr = ?', $id);
                    
        $result = $this->fetchRow($select)->toArray();

        
        return $result['email_usr'];
    }
    
	/*
    *   changeUserPassword
    *
    *   Changes the password of the user.
    *
    *   @return N/A
    */
	public function changeUserPassword($id = -1, $password)
	{
		$select = $this->select()
                            ->from($this, array('password_salt_usr'))
                            ->where('id_usr = ?', $id);
        $result = $this->fetchRow($select)->toArray();
		
		$salt = $result['password_salt_usr'];
		$hashed_password = md5($salt.$password.$salt);
		
		$data = array('password_usr' => $hashed_password);			
		$where = $this->getAdapter()->quoteInto('id_usr = ?', $id);
		$this->update($data, $where);	
	}
    
    /**
    *   Checks if username exists in database
    *
    *   @param $username string username
    *
    *   @return boolean TRUE if username exists, FALSE if not
    */
    public function usernameExists($username)
    {
        $select = $this->_db->select()
                        ->from(array('users_usr' => 'users_usr'), array('id_usr'))
                        ->where('login_name_usr = ?', $username)
        ;
        $result = $this->_db->fetchAll($select);

        if (!isset($result[0])) {
            return false;
        } else {
            return true;
        }
    }

    /**
    *   Checks if username exists in database
    *
    *   @param $username string username
    *
    *   @return integer userid
    *   @author Joel Peltonen
    */
    public function getIdByUsername($username)
    {
        $select = $this->_db->select()
                        ->from(array('users_usr' => 'users_usr'), array('id_usr'))
                        ->where('login_name_usr = ?', $username)
        ;
        
        $result = $this->_db->fetchAll($select);
        
        if (isset($result[0])) {
            $uid = $result[0]['id_usr'];
        } else {
            $uid = null;
        }
    
        return $uid;
    }
    
    public function getUserNameById($id_usr)
    {
        if($id_usr != 0)
        {
            $select = $this->select()
                    ->from($this, array('login_name_usr'))
                    ->where("`id_usr` = $id_usr");

            $result = $this->fetchAll($select)->toArray();
            
            return $result[0]['login_name_usr'];
        }
        else
        {
            return "privmsg-message-sender-system";
        }
    } // end of getUserNameById
    
    public function getContentOwner($contentId) {
    	
    	$select = $this->select()
    					->from('users_usr')
    					->join(array('cnt_has_usr'), 'cnt_has_usr.id_usr = users_usr.id_usr', array())
    					->where('cnt_has_usr.id_cnt = ?', $contentId);

        $result = $this->fetchAll($select)->toArray();
	    return $result[0];
    }
    
   /**
    * getUserFavouriteContent
    *
    * Get (all) favourite content from a specific user. 
    *
    * @author Jari Korpela
    * @param integer $author_id id of whose favourite content to get
    * @return array
    */    
    public function getUserFavouriteContent($author_id = 0, $type = 0)
    {
        $result = array();  // container for final results array
        
        $whereType = 1;
        if($type !== 0) {
            $whereType = $this->_db->quoteInto('cty.key_cty = ?', $type);
        }
        // If author id is set get users content
        if ($author_id != 0) {

                $contentSelect = $this->_db->select()
	                ->from(array('uhf' => 'usr_has_fvr'),
	                			array('id_usr','id_cnt','content_edited'))
	                ->joinLeft(array('cnt' => 'contents_cnt'),
	                			'uhf.id_cnt = cnt.id_cnt',
	                			array('id_cnt', 'id_cty_cnt', 'title_cnt', 
	                                  'lead_cnt', 'published_cnt', 'created_cnt'))
	                ->joinLeft(array('cty' => 'content_types_cty'),    
	                                  'cty.id_cty = cnt.id_cty_cnt',  
	                                  array('key_cty'))
	                ->joinLeft(array('vws' => 'cnt_views_vws'),
	                                 'vws.id_cnt_vws = cnt.id_cnt',
	                                  array('views' => 'COUNT(DISTINCT vws.views_vws)'))
	                ->joinLeft(array('crt' => 'content_ratings_crt'),
	                                 'cnt.id_cnt = crt.id_cnt_crt',
	                                 array('ratings' => 'COUNT(DISTINCT crt.id_crt)'))
	                ->joinLeft(array('cmt' => 'comments_cmt'),
	                                 'cnt.id_cnt = cmt.id_cnt_cmt',
	                                 array('comments' => 'COUNT(DISTINCT cmt.id_cmt)'))   
	                ->where('uhf.id_usr = ?', $author_id)
	                ->order('cnt.id_cty_cnt ASC')
	                ->order('cnt.created_cnt DESC')
	                ->group('cnt.id_cnt')
	                ;
                
                $result = $this->_db->fetchAll($contentSelect);
        } 
        return $result;
    } // end of getUserFavouriteContent
    
   
    /*
     * getAllUsersLocations
     * 
     * Gets all location info from users (Countries not yet done because they dont exist yet :p)
     * 
     * array(
     * 	cities => array(
     * 		cityindex => array(name, amount)),
     * 	countries => array(
     * 		countryindex => array(name, amount))
     * )
     * 
     * @author Jari Korpela
     * @return Array
     */
    public function getAllUsersLocations() {
    	$result = array();

        $select = $this->_db->select()->from(array('usp' => 'usr_profiles_usp'),
                                      	array('profile_key_usp',
                                      	'profile_value_usp',
                                      	'COUNT(profile_value_usp) AS amount'))
                                      ->joinLeft(array('usc' => 'countries_ctr'),
                                      	 'usc.iso_ctr = usp.profile_value_usp AND usp.profile_key_usp = "country"',
                                      	 array('countryName' => 'usc.printable_name_ctr',
                                      	 		'countryIso' => 'usc.iso_ctr'))
                                      ->where('usp.public_usp = 1')
                                      ->where('usp.profile_key_usp = "city" OR usp.profile_key_usp = "country"')
                                      ->order('usp.id_usr_usp')
                                      ->group('usp.profile_value_usp')
                                      ->distinct()
                                      ;
       $result = $this->_db->fetchAll($select);
       $final = array();
       foreach($result as $res) {
       	if($res['profile_key_usp'] == "city" && $res['profile_value_usp'] != "") {
       		$final['cities'][] = array('name' => $res['profile_value_usp'],'amount' => $res['amount']);
       		continue;
       	}
       	if($res['profile_key_usp'] == "country" && $res['countryName'] != "") {
       		$final['countries'][] = array('name' => $res['countryName'],'amount' => $res['amount'], 'countryIso' => $res['countryIso']);
       		continue;
       	}
       }
    	
    	return $final;
    }
    

      public function getUserContentList($contentIdList, $amount) {
        $result = array();  // container for final results array
                
        $contentSelect = $this->_db->select()
                                           ->from(array('cnt' => 'contents_cnt'),         
                                                  array('id_cnt', 'title_cnt', 'created_cnt'))
                                           ->joinLeft(array('cty' => 'content_types_cty'),    
                                                  'cty.id_cty = cnt.id_cty_cnt',  
                                                  array('key_cty'))
                                            ->where('cnt.id_cnt IN (?)', $contentIdList)
                                            ->where('cnt.published_cnt = 1')
                                            ->order('cnt.created_cnt DESC')
                                            ->limit($amount)
                ;       
        $result = $this->_db->fetchAll($contentSelect);
        return $result;
    } // end of getUserContentList
    
    /*
     * array $statisticsList holds info about what statistics you want to have
     */
    public function getUserStatistics($userId, $contentIdList, $statisticsList) {
    	
    	$statistics = array();
    	if(in_array("contentTypes",$statisticsList)) {
    		$statistics = array_merge($statistics,$this->getUserStatisticsContentTypes($contentIdList));
    	}
    	return $statistics;
    }
    
    public function getWholeUserContentList($userId, $contentIdList) {
    	$result = "";
    	if(is_numeric($userId) && is_array($contentIdList)) {
			$contentSelect = $this->_db->select()
                                           ->from(array('chu' => 'cnt_has_usr'),
                                                  array('id_cnt'))
                                           ->joinLeft(array('crt' => 'content_ratings_crt'),
                                                      'chu.id_cnt = crt.id_cnt_crt',
                                                      array('rating_sum' => 'SUM(crt.rating_crt)',
                                                       'ratings' => 'COUNT(crt.id_cnt_crt)'))
                                           ->joinLeft(array('cnt' => 'contents_cnt'),
                                                  'cnt.id_cnt = chu.id_cnt',
                                                  array('id_cnt', 'id_cty_cnt', 'title_cnt',
                                                        'lead_cnt', 'created_cnt'))
                                           ->joinLeft(array('vws' => 'cnt_views_vws'),
				                                 'vws.id_cnt_vws = chu.id_cnt',
				                                  array('views' => 'SUM(vws.views_vws)'))
                                           ->joinLeft(array('cmt' => 'comments_cmt'),
                                                      'cnt.id_cnt = cmt.id_cnt_cmt',
                                                      array('comments' => 'COUNT(DISTINCT cmt.id_cmt)'))
                                           ->joinLeft(array('cty' => 'content_types_cty'),
                                                  'cty.id_cty = cnt.id_cty_cnt',
                                                  array('key_cty'))
                                           ->where('chu.id_cnt IN (?)', $contentIdList)
                                           ->group(array('chu.id_cnt'))          
            ;                                
            $result = $this->_db->fetchAll($contentSelect);                         
		}
			
		return $result;
    }
    
    /*
     * getUsersViewers
     * 
     * gets list of users who has read users content, sorted last viewed
     * 
     * @param 	id 			users id
     * @param 	limit		limit of users, default 10
     * @return 	array		array (views => viewcount, id_usr_vws => viewers user id)
     */
    public function getUsersViewers($id, $limit = 10) {
    	// select max(modified_vws), id_usr_vws from cnt_has_usr,cnt_views_vws 
    	// where id_usr=2 and id_cnt=id_cnt_vws and modified_vws is not null and id_usr_vws != 0 group by id_usr_vws order by modified_vws desc;
    	$select = $this->select()->setIntegrityCheck(false)
    							 ->from('cnt_has_usr', array())
    							 ->where('cnt_has_usr.id_usr = ?', $id)
    							 ->join('cnt_views_vws', 
    							 		'cnt_views_vws.id_cnt_vws = cnt_has_usr.id_cnt',
    							  		array('latest' => 'max(modified_vws)', 'id_usr_vws' ))
    							 ->join('users_usr', 'id_usr_vws = users_usr.id_usr', array('login_name_usr'))
    							 ->where('users_usr.id_usr != ?', $id)
    							 ->where('cnt_has_usr.id_usr != 0')
    							 ->where('modified_vws is not null')
    							 ->group('id_usr_vws')
    							 ->order('modified_vws desc')
    							 ->limit($limit)
    							 ;
		$result = $this->_db->fetchAll($select);
		return $result;		   		 
    }
    
    /*
     * getGravatarStatus
     * @return 1 or 0 (true, false)
     * @param user id
     */
    public function getGravatarStatus($id = 0) {
        $select = $this->select()
                            ->from($this, array('gravatar_usr'))
                            ->where('id_usr = ?', $id);

        // Fetch data from database
        $result = $this->_db->fetchRow($select); 
        
        return $result['gravatar_usr'];
    }
    
    /*
     * changeGravatarStatus
     * @return true, false
     * @param user id
     */
    public function changeGravatarStatus($id = 0,$status = -1) {
    	if($status != false && $status != true) return false;
    	if($status == -1) return false;

    	$status == false ? $status = 0 : $status = 1;
    	
    	$data = array('gravatar_usr' => $status);			
		$where = $this->getAdapter()->quoteInto('id_usr = ?', $id);
		if ($this->update($data, $where)) return true;
		return false;
    }
} // end of class
