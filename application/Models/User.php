<?php
/**
 *  User -> User database model for user table.
 *
*     Copyright (c) <2009>, Markus Riihelä
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
 *  @author         Markus Riihelä & Mikko Sallinen
 *  @copyright     2009 Markus Riihelä & Mikko Sallinen
 *  @license     GPL v2
 *  @version     1.0
 */ 
class Models_User extends Zend_Db_Table_Abstract
{
    // Table name
    protected $_name = 'users_usr';
    
    // Primary key of table
    protected $_primary = 'id_usr';
    
    // Tables model depends on
    protected $_dependentTables = array('Models_UserProfiles', 'Models_UserImages',
                                        'Models_PrivateMessages', 'Models_CommentRatings', 
                                        'Models_Comments', 'Models_ContentPublishTimes', 
                                        'Models_ContentHasUser', 'Models_UserHasGroup', 
                                        'Models_Links', 'Models_Files', 
                                        'Models_ContentRatings');
        
    // Table references  to other tables
    protected $_referenceMap    = array(
        'UserLanguage' => array(
            'columns'            => array('id_lng_usr'),
            'refTableClass'        => 'Models_Languages',
            'refColumns'        =>    array('id_lng')        
        ),
        'CommentUser' => array(
            'columns'            => array('id_usr'),
            'refTableClass'        => 'Models_Comments',
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
        
    /**
    *    getUserImageData
    *
    *    Function to get users image data
    *
    *    @param integer $id is of user
    *    @param boolean $thumb is the image thumbnail
    *    @return array
    */
    public function getUserImageData($id = 0, $thumb = true)
    {
        // Get image data        
        if ($id != 0) {
            $rowset = $this->find((int)$id)->current();
            
            $row = $rowset->findDependentRowset('Models_UserImages', 'UserUser')->current();
            
            if (!empty($row)) {
                $imageData = $thumb ? $row->thumbnail_usi : $row->image_usi;
            }
        }// end if
        
        // If there is no image return null
        $hasImage = empty($imageData) ? null : $imageData;
        
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
            
            $row = $rowset->findDependentRowset('Models_UserImages', 'UserUser')->current();
            
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
        
        // Add login to log
        $message = sprintf('Successful login attempt from %s user %s', $_SERVER['REMOTE_ADDR'], $this->_data->login_name_usr);
        
        $logger = Zend_Registry::get('logger');
        $logger->notice($message);
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
        //$identity->first_name = $this->profile->first_name;
        //$identity->last_name = $this->profile->last_name;
        $identity->email = $this->profile->email;
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
    * @todo pagination 
    * @todo ignore parameter / functionality to leave out a content
    * @todo splitting model-specific selects to their own models
    * @todo moving the logic to controller instead of model
    * @todo the functionality where this is used should be ajaxified
    * @param integer $author_id id of whose content to get
    * @param integer $page
    * @param integer $count
    * @return array
    */    
    public function getUserContent($author_id = 0, $page = 1, $count = -1)
    {
        $result = array();  // container for final results array
        
        // If author id is set get users content
        if ($author_id != 0) {
            //if($count == -1) {
                $contentSelect = $this->_db->select()->from(array('usr' => 'users_usr'), array('id_usr', 'login_name_usr'))
                                         ->where('usr.id_usr = ?', $author_id)
                                         ->order('id_cty_cnt ASC')
                                         ->order('created_cnt DESC')
                                         ->join(array('chu' => 'cnt_has_usr'),          'chu.id_usr = usr.id_usr',      array('*'))
                                         ->join(array('cnt' => 'contents_cnt'),         'cnt.id_cnt = chu.id_cnt',      array('id_cnt', 'id_cty_cnt', 'title_cnt', 'lead_cnt', 'published_cnt'))
                                         ->join(array('cty' => 'content_types_cty'),    'cty.id_cty = cnt.id_cty_cnt',  array('*'))
                                         //->join(array('vws' => 'cnt_views_vws'),        'vws.id_cnt_vws = cnt.id_cnt',  array('*'))
                ;
               $contentResult = $this->_db->fetchAll($contentSelect);

               $viewsSelect = $this->_db->select()
                                        ->from(array('vws' => 'cnt_views_vws'), array('id_cnt_vws', 'SUM(views_vws)'))
                                        ->distinct()
                                        ->group('id_cnt_vws')
                ;
                $viewsResult = $this->_db->fetchAll($viewsSelect);

                
                // This horrible device injects the views to a complete result array
                $i = 0; // keeps track of result array cells
                foreach ($contentResult as $content)                                    // For each content found by owner
                {
                    $result[$i] = $content;                                             // initialise results array cell
                    foreach ($viewsResult as $views)                                    // go through array of views
                    {
                        if ($views['id_cnt_vws'] == $content['id_cnt'])                 // if views for the content in question found
                        {
                            if (isset($result[$i]['views'])) {                          // if this is not the first views for content,
                                $result[$i]['views'] += $views['SUM(views_vws)'];       // add views to previous views  
                            } else {                                                    // otherwise 
                                    $result[$i]['views'] = $views['SUM(views_vws)']+1;  // make a new cell for views in results array
                            }
                        }
                    }                                                                   // after going through views and checking..
                    if (!isset($result[$i]['views'])) {                                 // ...if content views cell not set...
                        $result[$i]['views'] = 0;                                       // ...set views to 0 to prevent errors
                    }

                    $i++;
                }
          
                //echo"<pre>"; print_r($result); echo"</pre>"; die;
                
            //}
            //else {
            //    $select = $this->select()->limitPage($page, $count)
            //                             ->order('id_cty_cnt ASC')
            //                             ->order('created_cnt DESC');
            //}
            //try{
            
            /*}catch(Zend_Exception $e){
                echo '<pre>';
                print_r($e);
                echo '</pre>';
            }
            echo '<pre>';
            print_r($result);
            echo '</pre>';
            die();*/
            //$row = $this->find((int)$author_id)->current();
            //$result = $row->findModels_ContentViaModels_ContentHasUser($select);
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
    
    /** gets amount of letters in salt from user **/
    public function getSaltCountByUsername($username = null) 
    {
        $data = array();
        
        if ($username != null) {
            $select = $this->select()
                            ->from($this, array('password_salt_usr'))
                            ->where('login_name_usr = ?', $username)
            ;

            $result = $this->fetchAll($select)->toArray();
            
            return strlen($result[0]['password_salt_usr']);
        }
        
        return false;
    }
    
    
    /*
    *   getUserListing
    *
    *   Gets user listing.
    *
    *   @return array
    */
    public function getUserListing(&$filter = null)
    {
        $select = $this->_db->select()->from(array('usr' => 'users_usr'), 
                                             array('id_usr', 'login_name_usr', 'email_usr', 'last_login_usr', 'created_usr'))
                                      ->joinLeft(array('chu' => 'cnt_has_usr'), 
                                             'chu.id_usr = usr.id_usr', 
                                             array('contentCount' => 'COUNT(chu.id_usr)'))
                                      ->joinLeft(array('usi' => 'usr_images_usi'), 
                                             'usr.id_usr = usi.id_usr_usi', 
                                             array('hasImage' => 'COUNT(usi.id_usr_usi)'))
                                      ->joinLeft(array('usp' => 'usr_profiles_usp'), 
                                             'usr.id_usr = usp.id_usr_usp', 
                                             array('*'))
                                      ->group('usr.id_usr')
                                      ->order('usr.last_login_usr DESC');
        
        // Filter by join date between selected times
        // $select->where($this->_db->quoteInto('usr.created_usr BETWEEN ?', $pv1) . 
        //               $this->_db->quoteInto(' AND ?', $pv2));
        
        // Filter by username search
        //$select->where('usr.login_name_usr LIKE ?', '%'.$muuttuja.'%');
        
        $result = $this->_db->fetchAll($select);
        
        return $result;
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
        $select = $this->select()
				->from($this, array('login_name_usr'))
				->where("`id_usr` = $id_usr");

		$result = $this->fetchAll($select)->toArray();
        
        return $result[0]['login_name_usr'];
    } // end of getUserNameById
} // end of class