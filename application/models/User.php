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
    *   Query in this function fetches all users
    *   from database. This will cause a creation
    *   of a very large array when user count
    *   is big.
    *
    *   @params array $filter Filtering options
    *   @return array
    */
	public function getUserListing(&$filter = null, $page = 1, $count = 10, $sort = 'usr.last_login_usr DESC')
    {

        // TODO: Filter by join date between selected times
        // $joinDate = 1;
        // if(!empty($filter['pv1']) && !empty($filter['pv2'])) {
        //  $joinDate = $this->_db->quoteInto('usr.created_usr BETWEEN ?', $pv1);
        //  $joinDate .= $this->_db->quoteInto(' AND ?', $pv2));
        // }

        $city = "'city'";
        $country = "'country'";
        
        $select = $this->_db->select()->from(array('usr' => 'users_usr'), 
                                             array('id_usr',
                                                   'login_name_usr',
                                                   'last_login_usr',
                                                   'created_usr'))
                                      ->joinLeft(array('chu' => 'cnt_has_usr'), 
                                                 'chu.id_usr = usr.id_usr', 
                                                 array('contentCount' => 'COUNT(DISTINCT chu.id_cnt)'))
                                      ->joinLeft(array('crt' => 'content_ratings_crt'),
                                                      'chu.id_cnt = crt.id_cnt_crt',
                                                      array('RatingAveragePositive' => 'CEIL(((SUM(crt.rating_crt) / COUNT(DISTINCT crt.id_cnt_crt))+1)*50)',
                                                      		'RatingAverageNegative' => 'FLOOR(100-((SUM(crt.rating_crt) / COUNT(DISTINCT crt.id_cnt_crt))+1)*50)',
                                                      		'RatingRatioPositive' => 'CEIL((SUM(crt.rating_crt) / COUNT(crt.id_cnt_crt)+1)*50)',
                                                      		'RatingRatioNegative' => 'FLOOR(100-(SUM(crt.rating_crt) / COUNT(crt.id_cnt_crt)+1)*50)',
                                                      		'ratingAmount' => 'COUNT(crt.id_cnt_crt)',
                                                      		'ratedContents' => 'COUNT(DISTINCT crt.id_cnt_crt)'))
                                      ->joinLeft(array('usp' => 'usr_profiles_usp'),
                                      			"usr.id_usr = usp.id_usr_usp AND usp.profile_key_usp = $city",
                                      			array('city' => 'usp.profile_value_usp'))
                                      ->joinLeft(array('usp2' => 'usr_profiles_usp'),
                                      			"usr.id_usr = usp2.id_usr_usp AND usp2.profile_key_usp = $country",
                                      			array('countryId' => 'usp2.profile_value_usp'))
                                      ->joinLeft(array('usc' => 'countries_ctr'),
                                      			'usc.iso_ctr = usp2.profile_value_usp',
                                      			array('countryName' => 'usc.name_ctr'))
                                      
                                      ->where($this->getUserSearchUsernameFilter($filter['username']))
                                      ->where($this->getUserSearchCountryFilter($filter['country']))
                                      ->where($this->getUserSearchCityFilter($filter['city']))
                                      ->having($this->getUserSearchContentCountFilter($filter['contentlimit'],
                                                                                      $filter['counttype']))
                                      // TODO: Filter by join date
                                      //->where($joinDate)
                                      ->group('usr.id_usr')
                                      ->order($sort)
                                      ->limitPage($page, $count)
                                      ;
        
        // Fetch all results from database
        $result = $this->_db->fetchAll($select);
        
        //$userProfile = new Default_Model_UserProfiles();
        //$userProfile->getUserCountry();
        //print_r($result);die;
        
        return $result;
    }
    
    /**
    *   getUserSearchCityFilter
    *
    *   Gets user search city filter.
    *
    *   @param int $city
    *   @return string
    */
    public function getUserSearchCityFilter(&$city) 
    {
        $result = 1;
        if (!empty($city)) {
            $result = $this->_db->quoteInto("usp.profile_key_usp = 'city'
                                                AND usp.public_usp = 1
                                                AND usp.profile_value_usp LIKE ?", 
                                             '%' . $city . '%');
        }
        
        return $result;
    }	
    
    /**
    *   getUserSearchCountryFilter
    *
    *   Gets user search country filter.
    *
    *   @param int $country
    *   @return string
    */
    public function getUserSearchCountryFilter(&$country) 
    {
        $result = 1;
        if ($country != 0) {
            $result = $this->_db->quoteInto("usp.profile_key_usp = 'country'
                                                AND usp.public_usp = 1
                                                AND usp.profile_value_usp = ?", 
                                             $country);
        }
        
        return $result;
    }
    
    /**
    *   getUserSearchUsernameFilter
    *
    *   Get user search username filter.
    *
    *   @param string $username
    *   @return string
    */
    public function getUserSearchUsernameFilter(&$username) 
    {
        $result = 1;
        if(!empty($username)) {
            $result = $this->_db->quoteInto('usr.login_name_usr LIKE ?', 
                                              '%' . $username . '%');
        }
        
        return $result;
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
    public function getUserSearchContentCountFilter(&$count, &$type) 
    {
        $result = 1;
        if(!empty($count) && 
            $char = $this->checkContentCount($type)) {
            $result = $this->_db->quoteInto('contentCount ' . $char . ' ?', 
                                                  $count);
        }
        
       return $result;
    }
    
    /**
    *   getUserCountBySearch
    *
    *   Get total user count by search
    *
    *   @param 
    *   @return array
    */
    public function getUserCountBySearch(&$filter = null) 
    {
        $select = $this->_db->select()->from(array('usr' => 'users_usr'), 
                                             array('userCount' => 'COUNT(DISTINCT usr.id_usr)'))
                                      ->joinLeft(array('chu' => 'cnt_has_usr'), 
                                                 'chu.id_usr = usr.id_usr', 
                                                 array('contentCount' => 'COUNT(DISTINCT chu.id_cnt)'))
                                      ->joinLeft(array('usp' => 'usr_profiles_usp'), 
                                                 'usr.id_usr = usp.id_usr_usp', 
                                                 array())
                                      ->where($this->getUserSearchUsernameFilter($filter['username']))
                                      ->where($this->getUserSearchCountryFilter($filter['country']))
                                      ->where($this->getUserSearchCityFilter($filter['city']))
                                      ->having($this->getUserSearchContentCountFilter($filter['contentlimit'],
                                                                                      $filter['counttype']))
                                      // TODO: Filter by join date
                                      //->where($joinDate)
                                      /*->group('usr.id_usr')*/;        
        $data = $this->_db->fetchAll($select);
        
        return isset($data[0]['userCount']) ? $data[0]['userCount'] : 0;   
    }
    
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
    private function checkContentCount(&$action/*, &$limit, &$count*/)
    {
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
    	$city = 'city';
    	$select = $this->_db->select()
    				->from('usr_profiles_usp', array('profile_value_usp AS name','COUNT(*) AS amount'))
    				->distinct()
    				->where('profile_key_usp = ?' ,$city)
    				->order('profile_value_usp')
    				->group('name');
    	$result = $this->_db->fetchAll($select);
    	$result = array('cities' => $result);
    	
    	return $result;
    }
    

      public function getUserContentList($author_id = 0, $sort = 0, $type = 0) {
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
                                           ->joinLeft(array('crt' => 'content_ratings_crt'),
                                                      'chu.id_cnt = crt.id_cnt_crt',
                                                      array('rating_sum' => 'SUM(crt.rating_crt)',
                                                      		'ratings' => 'COUNT(crt.id_cnt_crt)'))
                                           ->joinLeft(array('cnt' => 'contents_cnt'),         
                                                  'cnt.id_cnt = chu.id_cnt',
                                                  array('id_cnt', 'id_cty_cnt', 'title_cnt', 
                                                        'lead_cnt', 'created_cnt'))
                                           ->joinLeft(array('cmt' => 'comments_cmt'),
                                                      'cnt.id_cnt = cmt.id_cnt_cmt',
                                                      array('comments' => 'COUNT(DISTINCT cmt.id_cmt)'))
                                           ->joinLeft(array('chc' => 'cnt_has_cnt'),
                                                      'cnt.id_cnt = chc.id_parent_cnt',
                                                      array('cntHasCntCount' => 'COUNT(DISTINCT chc.id_child_cnt)'))
                                           ->joinLeft(array('cty' => 'content_types_cty'),    
                                                  'cty.id_cty = cnt.id_cty_cnt',  
                                                  array('key_cty'))
                                            ->where('chu.id_usr = ?', $author_id)
                                            ->order('cnt.created_cnt DESC')
                                            ->group('chu.id_cnt')
                ;
                
                $result = $this->_db->fetchAll($contentSelect);
                //TODO: If you have skills, combine queries in one query :D
                //The challenge is having ratings and views in same query
                $contentSelect = $this->_db->select()
                							->from(array('chu' => 'cnt_has_usr'), 
                                                  array('id_usr', 'id_cnt'))
                                           ->joinLeft(array('vws' => 'cnt_views_vws'),
                                                      'chu.id_cnt = vws.id_cnt_vws',
                                                      array('views' => 'SUM(vws.views_vws)'))
                                            ->where('chu.id_usr = ?', $author_id)
                                            ->order('chu.id_cnt DESC')
                                            ->group('chu.id_cnt')
 				;
 				
 				$result2 = $this->_db->fetchAll($contentSelect);
 				
 				foreach($result as $key => $res) {
 						$result[$key] = array_merge($res,$result2[$key]);
 				}
 				
        } // end if        

        return $result;
    } // end of getUserContentList
    
    /*
     * getUsersViewers
     * 
     * gets list of users who has read users content, sorted by amount of views
     * 
     * @param 	id 			users id
     * @param 	limit		limit of users, default 10
     * @return 	array		array (views => viewcount, id_usr_vws => viewers user id)
     */
    public function getUsersViewers($id, $limit = 10) {
    	//select id_usr_vws, sum(views_vws) FROM cnt_views_vws JOIN (cnt_has_usr) on (cnt_has_usr.id_cnt = cnt_views_vws.id_cnt_vws) 
    	//	where id_usr=2 group by id_usr_vws order by sum(views_vws) desc;
		$select = $this->_db->select()
					   		 ->from('cnt_has_usr', array())
					   		 ->where('cnt_has_usr.id_usr = ?', $id)
					   		 ->join('cnt_views_vws', 'cnt_views_vws.id_cnt_vws = cnt_has_usr.id_cnt', array('views' => 'sum(views_vws)' , 'id_usr_vws'))
					   		 ->join('users_usr', 'id_usr_vws = users_usr.id_usr', array('login_name_usr'))
					   		 ->group('id_usr_vws')
					   		 ->order('views desc')
					   		 ->limit($limit);

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
