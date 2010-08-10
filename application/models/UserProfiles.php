<?php
/**
 *  UserProfiles -> UserProfiles database model for userprofiles table.
 *
* 	Copyright (c) <2009>, Markus Riihel�
* 	Copyright (c) <2009>, Mikko Sallinen
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
 *  UserProfiles - class
 *
 *  @package 	models
 *  @author     Markus Riihel� & Mikko Sallinen
 *  @copyright 	2009 Markus Riihel� & Mikko Sallinen
 *  @license 	GPL v2
 *  @version 	1.0
 */ 
class Default_Model_UserProfiles extends Zend_Db_Table_Abstract
{
	// Name of table
    protected $_name = 'usr_profiles_usp';
	
	// Primary key of table
	protected $_primary = 'id_usp';
	
	// Tables reference map
	protected $_referenceMap    = array(
        'UserProfile' => array(
            'columns'           => array('id_usr_usp'),
            'refTableClass'     => 'Default_Model_User',
            'refColumns'        => array('id_usr')
        )
    );
	
    /**
    *   sets data according to values received in profile settings form
    *   @param $id id of user whose profile
    *   @pram $formdata data array with key => value AND key_publicity => 1/0
    *   @author Joel Peltonen
    *   @see setValue
    *   @see setPublicity
    */
    public function setProfileData($id, $formdata){
        // check params, return false if failure
        if ($id < 0 || !is_array($formdata)) {
            return false;
        } 

        if (isset($formdata['username']))
            unset($formdata['username']);
        if (isset($formdata['username_publicity']))
            unset($formdata['username_publicity']);
        if (isset($formdata['confirm_password']))
            unset($formdata['confirm_password']);
        if (isset($formdata['gravatartext']))
            unset($formdata['gravatartext']);
        if (isset($formdata['save']))
            unset($formdata['save']);
        if (isset($formdata['cancel']))
            unset($formdata['cancel']);
        if (isset($formdata['weblinks_name_site1']))
            unset($formdata['weblinks_name_site1']);
        if (isset($formdata['weblinks_url_site1']))
            unset($formdata['weblinks_url_site1']);
        if (isset($formdata['weblinks_name_site2']))
            unset($formdata['weblinks_name_site2']);
        if (isset($formdata['weblinks_url_site2']))
            unset($formdata['weblinks_url_site2']);
        if (isset($formdata['weblinks_name_site3']))
            unset($formdata['weblinks_name_site3']);
        if (isset($formdata['weblinks_url_site3']))
            unset($formdata['weblinks_url_site3']);
        if (isset($formdata['weblinks_name_site4']))
            unset($formdata['weblinks_name_site4']);
        if (isset($formdata['weblinks_url_site4']))
            unset($formdata['weblinks_url_site4']);
        if (isset($formdata['weblinks_name_site5']))
            unset($formdata['weblinks_name_site5']);
        if (isset($formdata['weblinks_url_site5']))
            unset($formdata['weblinks_url_site5']);

        // needs replacing to single setValue($id, $key, $val, $pub)! <--- Is this some old comment?
        foreach ($formdata as $key => $val) {                                   // go through data
            if ($key != "email" && $key != "password"                           // ignore certain keys...
                && $key != "notifications")                                     // ignore
            {
                $publicity = strpos($key,'_publicity');                         // returns true if publicitied
                if($publicity !== false) {                                      // note the use of !== (not !=), this operator must be used since strpos can return boolean and integer values
                    $key_to_set = array_shift(explode('_publicity',$key,2));    // get key for which the public value is for (strstr replacement from php.net)
                    $this->setPublicity($id, $key_to_set, $val);                // set publicity (whose, what key, what value)
                } else {                                                        // if NOT_publicity value
                    $this->setValue($id, $key, $val);                           // set new value
                }
            }
        }
        return true;
    }
    
    /**
    * set or update existing key-val pair with publicity
    * @author joel peltonen
    * @param id
    * @param key
    * @param value
    * @param pub int (1/0) 
    */
    public function setValue($id, $key, $value, $pub = 0) 
    {
        // get old values
        $select = $this->select()
            ->from($this, array('profile_key_usp', 'profile_value_usp', 'public_usp'))
            ->where('id_usr_usp = ?', $id)
            ->where('profile_key_usp = ?', $key);
        $result = $this->fetchAll($select)->toArray();
        
        $new = array(
                'id_usr_usp' => $id,
                'profile_key_usp' => $key,
                'profile_value_usp' => $value,
                'public_usp' => $pub,
                'created_usp' => new Zend_Db_Expr('NOW()'),
                'modified_usp' => new Zend_Db_Expr('NOW()')
        );

        $update = array(
                'id_usr_usp' => $id,
                'profile_key_usp' => $key,
                'profile_value_usp' => $value,
                'public_usp' => $pub,
                'modified_usp' => new Zend_Db_Expr('NOW()')
        );
        
        // if old values found (= not new profile field)
		if(isset($result[0]['profile_value_usp'])) {
            if ($result[0]['profile_value_usp'] == $value 
            && $result[0]['public_usp'] == $pub) {
                return true;    // dont set the same values
            }
        
            // update old values
            $where[] = $this->getAdapter()->quoteInto('profile_key_usp = ?', $key);
            $where[] = $this->getAdapter()->quoteInto('id_usr_usp = ?', $id);
            
            
			if($this->update($update, $where)) {
                return true;
            } else {
                return false;
            }
        // if no old values (insert new data)
		} else {	
            // insert new values
			$this->insert($new);
        }
    }
    
    /**
    * 
    *   @param $id id of user whose profile
    *   @param $which which key is it that needs to be (de)published
    *   @param $what what are we setting the publicity to?
    *   @value of key to modify
    *   @author Joel Peltonen    
    */
    private function setPublicity($id = -1, $which = -1, $what = -1) 
    {
        // if params are not ok, continue
        if ($id != -1 && $which != -1 && ($what == 0 || $what == 1)) {
        
            //echo "setPublicity processing userid: " . $id . "<br>";
            // get pre-existing data
            $select = $this->select()
                ->from($this, array('profile_key_usp', 'public_usp'))
                ->where('id_usr_usp = ?', $id)
				->where('profile_key_usp = ?', $which);
            $result = $this->fetchAll($select)->toArray();
            
            // if there is no result, panic and die
            if (!isset($result[0]['profile_key_usp'])) {
                //echo "pub update failed for: " . $which . "<br>";
                return false;
            }
            
            // if the value is already ok
            if ($result[0]['public_usp'] == $what) {
                //echo "pub matched to old value for: " . $which . "<br>";
                return true;
            }
            
            // set these values
            $new_value = array( 'public_usp' => $what,
                                'modified_usp' => new Zend_Db_Expr('NOW()')
            );

            // identify the one whe are updating by these values.
            // disregard the profile value, we must not set that even by accident
            $where[] = $this->getAdapter()->quoteInto('id_usr_usp = ?', $id);
			$where[] = $this->getAdapter()->quoteInto('profile_key_usp = ?', $which);
            
            // update with new values
            if($this->update($new_value, $where)) {
                //echo "pub updated: " . $which . "<br>";
                return true;
            }
        } else {
            // params not ok, die
            //echo "pub update params failed for: " . $which . "<br>";
            return false;
        }
    }
    
    
    /**
    * Gets all public data as an array, used in public profile page
    *
    * @author joel peltonen
    * @param id int user whose data we are to get
    * @return array of user data
    */
    public function getPublicData($id = -1) {
        if ($id == -1) {
            return false;   // don't allow default value
        }
        
        // get only key and value fields, others are not needed
        $select = $this->select()
            ->from($this, array('profile_key_usp', 'profile_value_usp'))
            ->where('id_usr_usp = ?', $id)
            ->where('public_usp = ?', 1);

        // roll to array and return
        $results = $this->fetchAll($select);
        foreach ($results as $result)
        {
        	$collection[$result->profile_key_usp] = htmlspecialchars($result->profile_value_usp);
        }
        // Change gender to M or N
        if (isset($collection['gender']) && $collection['gender'] == 1)
            $collection['gender'] = 'Male';
        else if (isset($collection['gender']) && $collection['gender'] == 2)
            $collection['gender'] = 'Female';
        // Change employment "code" to text
        if (isset($collection['employment']))
            $collection['employment'] = $this->getEmploymentByEmployment($collection['employment']);
        // User timezone
        if (isset($collection['usertimezone'])) {
            $timezone_model = new Default_Model_Timezones();
            $collection['usertimezone'] = $timezone_model->getTimezoneTextById($collection['usertimezone']);
        }
        // User country
        if (isset($collection['country'])) {
            $country_model = new Default_Model_Countries();
            $collection['country'] = $country_model->getCountryPrintableNameByIso($collection['country']);
        }

        return $collection;
    }
    
	/*
    *   setUserFirstName
    *
    *   Changes the users first name.
    *
    *   @return N/A
    */
	public function setUserFirstName($id = -1, $formData, $publicity = 0)
	{		
        // get existing firstname for user
		$select = $this->select()
                  ->from($this, array('profile_key_usp', 'profile_value_usp'))
                  ->where('id_usr_usp = ?', $id)
				  ->where('profile_key_usp = ?', 'firstname');
						
		$result = $this->fetchAll($select)->toArray();
				
		// this is a bit tedious, I'll make a better way when I find one -sokuni
		$firstname = array(
			'id_usr_usp' => $id,
			'profile_key_usp' => 'firstname',
			'profile_value_usp' => $formData['firstname'],
			'public_usp' => $publicity,
			'modified_usp' => new Zend_Db_Expr('NOW()')
		);
		
        // if exists, override
		if(isset($result[0]['profile_value_usp']))
		{
			$where1[] = $this->getAdapter()->quoteInto('id_usr_usp = ?', $id);
			$where1[] = $this->getAdapter()->quoteInto('profile_key_usp = ?', $firstname['profile_key_usp']);
			$this->update($firstname, $where1);
		} else {
            // does not exist? create
			$this->insert($firstname);	
		}

	}
    
    /*
    *   setUserEmployment
    *
    *   Changes the users Employment status.
    *
    *   @param id           int     id of the user
    *   @param formData    string  data to be inserted as value
    *   @return N/A
    *   @author sokuni
    *   @author joel peltonen
    */
	public function setUserEmployment($id = -1, $formData, $publicity = 0) {		
		$select = $this->select()
                  ->from($this, array('profile_key_usp', 'profile_value_usp'))
                  ->where('id_usr_usp = ?', $id)
				  ->where('profile_key_usp = ?', 'employment');

		$result = $this->fetchAll($select)->toArray();
        
		$emp = array(
			'id_usr_usp' => $id,
			'profile_key_usp' => 'employment',
			'profile_value_usp' => $formData['employment'],
			'public_usp' => $publicity,
			'modified_usp' => new Zend_Db_Expr('NOW()')
		);
				
		if(isset($result[0]['profile_value_usp'])) {
			$where1[] = $this->getAdapter()->quoteInto('id_usr_usp = ?', $id);
			$where1[] = $this->getAdapter()->quoteInto('profile_key_usp = ?', $emp['profile_key_usp']);
			$this->update($emp, $where1);
		} else {	
			$this->insert($emp);	
		}

	}
	
	/*
    *   setUserSurname
    *
    *   Changes the users surname.
    *
    *   @return N/A
    */
	public function setUserSurname($id = -1, $formData,$publicity=0)
	{
		// This result is needed to check whether there are rows in the profile-table for the user...will be removed due to release. -sokuni
		$select = $this->select()
                  ->from($this, array('profile_key_usp', 'profile_value_usp'))
                  ->where('id_usr_usp = ?', $id)
				  ->where('profile_key_usp = ?', 'surname');
						
		$result = $this->fetchAll($select)->toArray();
				
		$surname = array(
			'id_usr_usp' => $id,
			'profile_key_usp' => 'surname',
			'profile_value_usp' => $formData['surname'],
			'public_usp' => $publicity,
			'modified_usp' => new Zend_Db_Expr('NOW()')
		);
				
		if(isset($result[0]['profile_value_usp']))
		{
			$where[] = $this->getAdapter()->quoteInto('id_usr_usp = ?', $id);
			$where[] = $this->getAdapter()->quoteInto('profile_key_usp = ?', $surname['profile_key_usp']);
			$this->update($surname, $where);
		}
		else
		{	
			$this->insert($surname);	
		}
	}
	/*
	*	Setting for user's gender
	*/
	public function setUserGender($id=-1, $formData,$publicity=0)
	{
		// This result is needed to check whether there are rows in the profile-table for the user...will be removed due to release. -sokuni
		$select = $this->select()
                  ->from($this, array('profile_key_usp', 'profile_value_usp'))
                  ->where('id_usr_usp = ?', $id)
				  ->where('profile_key_usp = ?', 'gender');
						
		$result = $this->fetchAll($select)->toArray();
				
		$gender = array(
			'id_usr_usp' => $id,
			'profile_key_usp' => 'gender',
			'profile_value_usp' => $formData['gender'],
			'public_usp' => $publicity,
			'modified_usp' => new Zend_Db_Expr('NOW()')
		);				
						
		if(isset($result[0]['profile_value_usp']))
		{
			$where[] = $this->getAdapter()->quoteInto('id_usr_usp = ?', $id);
			$where[] = $this->getAdapter()->quoteInto('profile_key_usp = ?', $gender['profile_key_usp']);
			$this->update($gender, $where);			
		}
		else
		{				
			$this->insert($gender);	
		}				
	}	
	/*
	*	Setting for user's profession
	*/
	public function setUserProf($id = -1, $formData,$publicity=0)
	{		
		// This result is needed to check whether there are rows in the profile-table for the user...will be removed due to release. -sokuni
		$select = $this->select()
                  ->from($this, array('profile_key_usp', 'profile_value_usp'))
                  ->where('id_usr_usp = ?', $id)
				  ->where('profile_key_usp = ?', 'profession');
						
		$result = $this->fetchAll($select)->toArray();
				
		$prof = array(
			'id_usr_usp' => $id,
			'profile_key_usp' => 'profession',
			'profile_value_usp' => $formData['profession'],
			'public_usp' => $publicity,
			'modified_usp' => new Zend_Db_Expr('NOW()')
		);
				
		if(isset($result[0]['profile_value_usp']))
		{
			$where[] = $this->getAdapter()->quoteInto('id_usr_usp = ?', $id);
			$where[] = $this->getAdapter()->quoteInto('profile_key_usp = ?', $prof['profile_key_usp']);
			$this->update($prof, $where);
		}
		else
		{	
			$this->insert($prof);	
		}
	}
	
	/*
	*	Setting for user's company
	*/
	public function setUserCom($id = -1, $formData,$publicity=0)
	{		
		// This result is needed to check whether there are rows in the profile-table for the user...will be removed due to release. -sokuni
		$select = $this->select()
                  ->from($this, array('profile_key_usp', 'profile_value_usp'))
                  ->where('id_usr_usp = ?', $id)
				  ->where('profile_key_usp = ?', 'company');
						
		$result = $this->fetchAll($select)->toArray();
				
		$com = array(
			'id_usr_usp' => $id,
			'profile_key_usp' => 'company',
			'profile_value_usp' => $formData['company'],
			'public_usp' => $publicity,
			'modified_usp' => new Zend_Db_Expr('NOW()')
		);
				
		if(isset($result[0]['profile_value_usp']))
		{
			$where[] = $this->getAdapter()->quoteInto('id_usr_usp = ?', $id);
			$where[] = $this->getAdapter()->quoteInto('profile_key_usp = ?', $com['profile_key_usp']);
			$this->update($com, $where);
		}
		else
		{	
			$this->insert($com);	
		}
	}
	
	/*
	*	Setting for user's biography
	*/
	public function setUserBio($id = -1, $formData,$publicity=0)
	{		
		// This result is needed to check whether there are rows in the profile-table for the user...will be removed due to release. -sokuni
		$select = $this->select()
                  ->from($this, array('profile_key_usp', 'profile_value_usp'))
                  ->where('id_usr_usp = ?', $id)
				  ->where('profile_key_usp = ?', 'biography');
						
		$result = $this->fetchAll($select)->toArray();
				
		$bio = array(
			'id_usr_usp' => $id,
			'profile_key_usp' => 'biography',
			'profile_value_usp' => $formData['biography'],
			'public_usp' => $publicity,
			'modified_usp' => new Zend_Db_Expr('NOW()')
		);
				
		if(isset($result[0]['profile_value_usp']))
		{
			$where[] = $this->getAdapter()->quoteInto('id_usr_usp = ?', $id);
			$where[] = $this->getAdapter()->quoteInto('profile_key_usp = ?', $bio['profile_key_usp']);
			$this->update($bio, $where);
		}
		else
		{	
			$this->insert($bio);	
		}
	}
	
	/* 
	* Settings for User's country
	*/
	public function setUserCountry($id=-1,$formData,$publicity=0)
	{
		$select = $this->select()
					->from($this, array('profile_key_usp', 'profile_value_usp'))
					->where('id_usr_usp = ?', $id)
					->where('profile_key_usp = ?', 'country');
						
		$result = $this->fetchAll($select)->toArray();
				
				
		// this a bit tedious way to do this, I'll work on a better solution when I bump into one -sokuni
		$country = array(
			'id_usr_usp' => $id,
			'profile_key_usp' => 'country',
			'profile_value_usp' => $formData['country'],
			'public_usp' => $publicity,
			'modified_usp' => new Zend_Db_Expr('NOW()')
		);			
			
		if(isset($result[0]['profile_value_usp']))
		{
			$where[] = $this->getAdapter()->quoteInto('id_usr_usp = ?', $id);
			$where[] = $this->getAdapter()->quoteInto('profile_key_usp = ?', $country['profile_key_usp']);
			$this->update($country, $where);
		}
		else
		{	
			$this->insert($country);	
		}
	}
    
	/*
	*	Set user's city
    *   
    *   @author sokuni
    *   @author Joel Peltonen
    *   @param User ID for whom we are setting city
    *   @param array with data that will be changed (string support coming)
    *   @param is the data punblic 1/0
	*/
	public function setUserCity($id = -1, $formData, $publicity = 0)
	{		
		// are there are rows in the profile-table for the user?
		$select = $this->select()
                  ->from($this, array('profile_key_usp', 'profile_value_usp'))
                  ->where('id_usr_usp = ?', $id)
				  ->where('profile_key_usp = ?', 'city');
						
		$result = $this->fetchAll($select)->toArray();
		
		$city = array(
			'id_usr_usp' => $id,
			'profile_key_usp' => 'city',
			'profile_value_usp' => $formData['city'],
			'public_usp' => $publicity,
			'modified_usp' => new Zend_Db_Expr('NOW()')
		);
		
        // if pre-existing value; override. else create new row
		if(isset($result[0]['profile_value_usp']))
		{
			$where[] = $this->getAdapter()->quoteInto('id_usr_usp = ?', $id);
			$where[] = $this->getAdapter()->quoteInto('profile_key_usp = ?', $city['profile_key_usp']);
			$this->update($city, $where);
		} else {	
			$this->insert($city);	
		}
	}
	
	/*
	*	Setting for user's birthday
	*/
	public function setUserBirthday($id = -1, $formData,$publicity=0)
	{		
		// This result is needed to check whether there are rows in the profile-table for the user...will be removed due to release. -sokuni
		$select = $this->select()
                  ->from($this, array('profile_key_usp', 'profile_value_usp'))
                  ->where('id_usr_usp = ?', $id)
				  ->where('profile_key_usp = ?', 'birthday');
						
		$result = $this->fetchAll($select)->toArray();
				
		$birth = array(
			'id_usr_usp' => $id,
			'profile_key_usp' => 'birthday',
			'profile_value_usp' => $formData['birthday'],
			'public_usp' => $publicity,
			'modified_usp' => new Zend_Db_Expr('NOW()')
		);
				
		if(isset($result[0]['profile_value_usp']))
		{
			$where[] = $this->getAdapter()->quoteInto('id_usr_usp = ?', $id);
			$where[] = $this->getAdapter()->quoteInto('profile_key_usp = ?', $birth['profile_key_usp']);
			$this->update($birth, $where);
		}
		else
		{	
			$this->insert($birth);	
		}
	}
	
	/*
	*	Setting for user's phone
	*/
	public function setUserPhone($id = -1, $formData,$publicity=0)
	{		
		// This result is needed to check whether there are rows in the profile-table for the user...will be removed due to release. -sokuni
		$select = $this->select()
                  ->from($this, array('profile_key_usp', 'profile_value_usp'))
                  ->where('id_usr_usp = ?', $id)
				  ->where('profile_key_usp = ?', 'phone');
						
		$result = $this->fetchAll($select)->toArray();
				
		$phone = array(
			'id_usr_usp' => $id,
			'profile_key_usp' => 'phone',
			'profile_value_usp' => $formData['phone'],
			'public_usp' => $publicity,
			'modified_usp' => new Zend_Db_Expr('NOW()')
		);
				
		if(isset($result[0]['profile_value_usp']))
		{
			$where[] = $this->getAdapter()->quoteInto('id_usr_usp = ?', $id);
			$where[] = $this->getAdapter()->quoteInto('profile_key_usp = ?', $phone['profile_key_usp']);
			$this->update($phone, $where);
		}
		else
		{	
			$this->insert($phone);	
		}
	}
	
	/*
	*	Setting for user's OpenID
	*/
	public function setUserOpenid($id = -1, $formData,$publicity=0)
	{		
		// This result is needed to check whether there are rows in the profile-table for the user...will be removed due to release. -sokuni
		$select = $this->select()
                  ->from($this, array('profile_key_usp', 'profile_value_usp'))
                  ->where('id_usr_usp = ?', $id)
				  ->where('profile_key_usp = ?', 'openid');
						
		$result = $this->fetchAll($select)->toArray();
				
		$openid = array(
			'id_usr_usp' => $id,
			'profile_key_usp' => 'openid',
			'profile_value_usp' => openid_makegoodurl($formData['openid']),
			'public_usp' => $publicity,
			'modified_usp' => new Zend_Db_Expr('NOW()')
		);
				
		if(isset($result[0]['profile_value_usp']))
		{
			$where[] = $this->getAdapter()->quoteInto('id_usr_usp = ?', $id);
			$where[] = $this->getAdapter()->quoteInto('profile_key_usp = ?', $openid['profile_key_usp']);
			$this->update($openid, $where);
		}
		else
		{	
			$this->insert($openid);	
		}
	}
    
    /** 
    *   setUserRoles
    *   Updates user's roles
    *   
    *   @param int id_usr The id of user
    *   @param array roles Array which includes user's roles
    *   @return bool $return
    *   @author Pekka Piispanen
    */
    public function setUserRoles($id_usr, $roles)
    {
        $roles = json_encode($roles);
            
        $data = array('profile_value_usp' => $roles);
        $where[] = $this->getAdapter()->quoteInto('id_usr_usp = ?', $id_usr);
        $where[] = $this->getAdapter()->quoteInto('profile_key_usp = ?', "permissions");
        if($this->update($data, $where))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    /**
    *   Set users profile rows value
    *
    *   @param id int user id
    *   @param key string row key
    *   @param formData array data from form
    */
    public function setUserProfileValue($id = -1, $key = null, $formData)
    {
        if ($id != -1 && $key != null) {
    		// This result is needed to check whether there are rows in the profile-table for the user...
            // will be removed due to release. -sokuni
    		$select = $this->select()
                                ->from($this, array('profile_key_usp', 'profile_value_usp'))
                                ->where('id_usr_usp = ?', $id)
                                ->where('profile_key_usp = ?', $key);
    						
    		$result = $this->fetchAll($select)->toArray();
            
            $row['id_usr_usp']          = $id;
            $row['profile_key_usp']     = $key;
    		$row['profile_value_usp']   = $formData[$key];
    		$row['public_usp']          = 0;
    		$row['modified_usp']        = new Zend_Db_Expr('NOW()');
    				
    		if(isset($result[0]['profile_value_usp'])) {
    			$where[] = $this->getAdapter()->quoteInto('id_usr_usp = ?', $id);
    			$where[] = $this->getAdapter()->quoteInto('profile_key_usp = ?', $row['profile_key_usp']);
    			$this->update($row, $where);
    		} else {	
    			$this->insert($row);	
    		}
        }
    }
    
    /**
    *   Get users profile rows value
    *
    *   @param id int user id
    *   @param key string key to value
    *   @return string
    */
    public function getUserProfileValue($id = -1, $key = null)
    {
        if($id != -1 && $key != null) {
            $select = $this->select()
							->from($this, array('profile_key_usp', 'profile_value_usp'))
							->where('id_usr_usp = ?', $id)
							->where('profile_key_usp = ?', $key);
                
            $result = $this->fetchAll($select)->current();
		}
        
        return !empty($result) ? $result : null;     
    }
    
	//Get user's Gender
	public function getUserGender($id=-1)
	{
		$where = $this->select()
							->from($this, array('profile_key_usp', 'profile_value_usp'))
							->where('id_usr_usp = ?', $id)
							->where('profile_key_usp = ?','gender');
		

        $result = $this->fetchAll($where)->current();
		if (!empty($result)) {
            return $result;
        }
        else {
            return null;
        }
	}
	
	//Get user's profession
	public function getUserProf($id=-1)
	{
		$where = $this->select()
							->from($this, array('profile_key_usp', 'profile_value_usp'))
							->where('id_usr_usp = ?', $id)
							->where('profile_key_usp = ?','profession');
		

        $result = $this->fetchAll($where)->current();
		if (!empty($result)) {
            return $result;
        }
        else {
            return null;
        }
	}
	
	//Get user's phone
	public function getUserPhone($id=-1)
	{
		$where = $this->select()
							->from($this, array('profile_key_usp', 'profile_value_usp'))
							->where('id_usr_usp = ?', $id)
							->where('profile_key_usp = ?','phone');
		

        $result = $this->fetchAll($where)->current();
		if (!empty($result)) {
            return $result;
        }
        else {
            return null;
        }
	}
	
	//Get user's OpenID
	public function getUserOpenid($id=-1)
	{
		$where = $this->select()
							->from($this, array('profile_key_usp', 'profile_value_usp'))
							->where('id_usr_usp = ?', $id)
							->where('profile_key_usp = ?','openid');
		

        $result = $this->fetchAll($where)->current();
		if (!empty($result)) {
            return $result;
        }
        else {
            return null;
        }
	}
	
	//Get user's birthday
	public function getUserBirthday($id=-1)
	{
		$where = $this->select()
							->from($this, array('profile_key_usp', 'profile_value_usp'))
							->where('id_usr_usp = ?', $id)
							->where('profile_key_usp = ?','birthday');
		

        $result = $this->fetchAll($where)->current();
		if (!empty($result)) {
            return $result;
        }
        else {
            return null;
        }
	}
	
	//Get user's company
	public function getUserCom($id=-1)
	{
		$where = $this->select()
							->from($this, array('profile_key_usp', 'profile_value_usp'))
							->where('id_usr_usp = ?', $id)
							->where('profile_key_usp = ?','company');
		

        $result = $this->fetchAll($where)->current();
		if (!empty($result)) {
            return $result;
        }
        else {
            return null;
        }
	}
	
	//Get user's biography
	public function getUserBio($id=-1)
	{
		$where = $this->select()
							->from($this, array('profile_key_usp', 'profile_value_usp'))
							->where('id_usr_usp = ?', $id)
							->where('profile_key_usp = ?','biography');
		

        $result = $this->fetchAll($where)->current();
		if (!empty($result)) {
            return $result;
        }
        else {
            return null;
        }
	}
	
	//Get user's city
	public function getUserCity($id=-1)
	{
		$where = $this->select()
							->from($this, array('profile_key_usp', 'profile_value_usp'))
							->where('id_usr_usp = ?', $id)
							->where('profile_key_usp = ?','city');
		

        $result = $this->fetchAll($where)->current();
		if (!empty($result)) {
            return $result;
        }
        else {
            return null;
        }
	}
	
	//Get user's country
	public function getUserCountry($id)
	{
		/*$where = $this->select()
							->from($this)
							->where('id_usr_usp = ?', $id)
							->where('profile_key_usp = ?','country');	*/	
		$names = new Default_Model_UserCountry();
        $results = $this->fetchAll();
		$c_id=0;
		foreach ($results as $result)
		{
			if (($result->id_usr_usp == $id) && ($result->profile_key_usp=='country'))
			{
				//
				//Keep the old user data's works
				//
				if (($result->profile_value_usp == '') || ($result->profile_value_usp == 'None'))
				{
					$result->profile_value_usp = 1;
					$result->save();
				}
				$name = $names->fetchRow('id_ctr='.$result->profile_value_usp);		
				if (isset($name->name_ctr))
					return $name->name_ctr;	
				else
					return "None";
			}			
		}	
		return "None";
	}
	
	//Get user's surname
	public function getUserSurname($id=-1)
	{
		$where = $this->select()
							->from($this, array('profile_key_usp', 'profile_value_usp'))
							->where('id_usr_usp = ?', $id)
							->where('profile_key_usp = ?','surname');
		

        $result = $this->fetchAll($where)->current();
		if (!empty($result)) {
            return $result;
        }
        else {
            return null;
        }
	}

    /**
     * getUserEmployment
     *
     * Get user employment (status)
     *
     * @param id_usr User id
     * @return string
     *
     * @author Mikko Korpinen
     */
	public function getUserEmployment($usr_id=-1)
	{
		$where = $this->select()
							->from($this, array('profile_key_usp', 'profile_value_usp'))
							->where('id_usr_usp = ?', $usr_id)
							->where('profile_key_usp = ?','employment');


        $result = $this->fetchAll($where)->current();
		if (!empty($result)) {
            return $result;
        }
        else {
            return null;
        }
	}
	
	//Get user category
	public function getUsercate($id=-1)
	{
		$where = $this->select()
							->from($this, array('profile_key_usp', 'profile_value_usp'))
							->where('id_usr_usp = ?', $id)
							->where('profile_key_usp = ?','user category');
		

        $result = $this->fetchAll($where)->current();
		if (!empty($result)) {
            return $result;
        }
        else {
            return null;
        }
	}
	
	//get user's firstname
	public function getUserFirstname($id=-1)
	{
		$where = $this->select()
							->from($this, array('profile_key_usp', 'profile_value_usp'))
							->where('id_usr_usp = ?', $id)
							->where('profile_key_usp = ?','firstname');
		

        $result = $this->fetchAll($where)->current();
		if (!empty($result)) {
            return $result;
        }
        else {
            return null;
        }
	}
    
    //Get user's roles
	public function getUserRoles($id=-1)
	{
		$select = $this->select()
				->from($this, array('profile_value_usp'))
				->where("`profile_key_usp` = 'permissions'")
                ->where("`id_usr_usp` = $id");
        
		$result = $this->fetchAll($select)->toArray();
        
        if (!empty($result)) {
            return json_decode($result[0]['profile_value_usp']);
        }
        
        return array();
	}
	
	public function getUserInfoById($id)
	{
		// select from the UserProfile table by the ID
		$select = $this->select()
							->from($this)
                            ->where('id_usr_usp = ?', $id);		
		
		$results = $this->fetchAll($select);
		
		foreach ($results as $result) { 
			//Put into the array the key and value
			$data[$result->profile_key_usp] = $result->profile_value_usp;
			$data[$result->profile_key_usp . "_publicity"] = $result->public_usp;
		}
        
		//return the array of data(key+value)
		if($data == null) {
			$data[] = "empty";
		}
		return $data;	
		
	}
    
    /*
    *   (Deprecated) init table for new user 
    *
    *   was used to pre-format some values during registration
    *   no longer used, kept only for possible future use -joel
    *
    *   @deprecated because values no longer crash with no pre-existing ones
    *   @author joel peltonen
    *   @param uid string user id
    *   @return boolean success
    */
    public function initNewUser($uid){
        $failure = false;
      
		// next row
        $row5 = $this->createRow();
        
        $row5->id_usr_usp = $uid;
        $row5->profile_key_usp = "gender";
        $row5->profile_value_usp = "";
        $row5->public_usp = 0;
        $row5->created_usp = new Zend_Db_Expr('NOW()');		        

        if(!$row5->save()) {
            $failure = true;
        }
        
		// next row
        $row6 = $this->createRow();
        
        $row6->id_usr_usp = $uid;
        $row6->profile_key_usp = "permissions";
        $row6->profile_value_usp = "[\"user\"]";
        $row6->public_usp = 0;
        $row6->created_usp = new Zend_Db_Expr('NOW()');		        

        if(!$row6->save()) {
            $failure = true;
        }
        
		// next row
        $row7 = $this->createRow();
        
        $row7->id_usr_usp = $uid;
        $row7->profile_key_usp = "profession";
        $row7->profile_value_usp = "";
        $row7->public_usp = 0;
        $row7->created_usp = new Zend_Db_Expr('NOW()');		        

        if(!$row7->save()) {
            $failure = true;
        }
		
		// next row
        $row9 = $this->createRow();
        
        $row9->id_usr_usp = $uid;
        $row9->profile_key_usp = "biography";
        $row9->profile_value_usp = "";
        $row9->public_usp = 0;
        $row9->created_usp = new Zend_Db_Expr('NOW()');		        

        if(!$row9->save()) {
            $failure = true;
        }
		
		// next row
        $row11 = $this->createRow();
        
        $row11->id_usr_usp = $uid;
        $row11->profile_key_usp = "birthday";
        $row11->profile_value_usp = "";
        $row11->public_usp = 0;
        $row11->created_usp = new Zend_Db_Expr('NOW()');		        

        if(!$row11->save()) {
            $failure = true;
        }

		// next row
        $row12 = $this->createRow();
        
        $row12->id_usr_usp = $uid;
        $row12->profile_key_usp = "country";
        $row12->profile_value_usp = 1;
        $row12->public_usp = 0;
        $row12->created_usp = new Zend_Db_Expr('NOW()');		        

        if(!$row12->save()) {
            $failure = true;
        }
		
		// next row
        $row13 = $this->createRow();
        
        $row13->id_usr_usp = $uid;
        $row13->profile_key_usp = "phone";
        $row13->profile_value_usp = "";
        $row13->public_usp = 0;
        $row13->created_usp = new Zend_Db_Expr('NOW()');		        

        if(!$row13->save()) {
            $failure = true;
        }
        
    	// next row
        $row14 = $this->createRow();
        
        $row14->id_usr_usp = $uid;
        $row14->profile_key_usp = "openid";
        $row14->profile_value_usp = "";
        $row14->public_usp = 0;
        $row14->created_usp = new Zend_Db_Expr('NOW()');		        

        if(!$row14->save()) {
            $failure = true;
        }
		
        return !$failure;
    }
    
	//Search for possible user's attached OpenID
	public function searchUserOpenid($openidQuery)
	{
		
		$where = $this->select()
							->from(array('usr_profiles_usp' => 'usr_profiles_usp'), array('id_usr_usp'))
						//	->from($this, array('id_usr_usp'))
							->where('profile_key_usp = ?','openid')
							->where('profile_value_usp = ?', $openidQuery);

        $result = $this->_db->fetchAll($where);
		if (!empty($result)) {
            return $result[0];
        }
        else {
            return null;
        }
	}    

    /**
    *   Checks for duplicate OpenID accounts in database
    *
    *   @param $openid string OpenID-account
    *   @return boolean TRUE if duplicates exist, FALSE if not
    */
    public function openidDupes($openid)
    {
    	//echo "dupe: " . $openid;
        $select = $this->_db->select()
                        ->from(array('usr_profiles_usp' => 'usr_profiles_usp'), array('profile_key_usp'))
                        ->where('profile_value_usp = ?', $openid)
        ;
        $result = $this->_db->fetchAll($select);
        //return count($result);
		//echo count($result);
        if (count($result) < 1) {
            return 0;
        } else {
            return 1;
        }

    }

    /**
     * getEmployments
     *
     * Employments
     *
     * @return array
     * @author Mikko Korpinen
     */
    public function getEmployments()
    {
        return array(
                    'private_sector' => 'Private sector',
                    'public_sector' => 'Public sector',
                    'education_sector' => 'Education sector',
                    'student' => 'Student',
                    'pentioner' => 'Pentioner',
                    'other' => 'Other',
               );
    }

    /**
     * getEmploymentByEmployment
     *
     * Employment by employment code
     *
     * @return String
     * @param String
     * @author Mikko Korpinen
     */
    public function getEmploymentByEmployment($employment)
    {
        Switch ($employment) {
            case 'private_sector':
                return 'Private sector';
                break;
            case 'public_sector':
                return 'Public sector';
                break;
            case 'education_sector':
                return 'Education sector';
                break;
            case 'student':
                return 'Student';
                break;
            case 'pentioner':
                return 'Pentioner';
                break;
            case 'other':
                return 'Other';
                break;
            default:
                return '';
        }
    }
    
    public function getUsersWithCountry($userIdList) {
    	$select = $this->_db->select()->from(array('usp' => 'usr_profiles_usp'),
    									array('id_usr' => 'id_usr_usp'))
    								->joinLeft(array('usc' => 'countries_ctr'),
                                      			 'usc.iso_ctr = usp.profile_value_usp AND usp.profile_key_usp = "country"',
                                      			 array('countryName' => 'usc.printable_name_ctr',
                                      			 	   'countryIso' => 'usc.iso_ctr'))
	    							->where('profile_key_usp = ?','country')
	    							->where('public_usp = ?','1')
	    							->where('id_usr_usp IN (?)', $userIdList)
	    							->where('usp.profile_value_usp != ?',"0")
	    							->order('id_usr')
    							;
				
        $result = $this->_db->fetchAssoc($select); 
		return $result;
    }
        
    public function getUsersWithCity($userIdList) {
    	$select = $this->_db->select()->from(array('usp' => 'usr_profiles_usp'),
    									array('id_usr' => 'id_usr_usp',
    										 'city' => 'profile_value_usp'))
	    							->where('profile_key_usp = ?','city')
	    							->where('public_usp = ?','1')
	    							->where('id_usr_usp IN (?)', $userIdList)
	    							->where('usp.profile_value_usp != ?',"")
	    							->order('id_usr')
    							;
				
        $result = $this->_db->fetchAssoc($select); 
		return $result;
    }
    
    /**
     * getUsersLocation
     * 
     * Gets users locations (city and country)
     * 
     * @param array $userIdList
     * @return array $list
     * @author Jari Korpela
     */
    public function getUsersLocation($userIdList) {
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
    
    
    public function getCountryAmounts() {
    	$select = $this->_db->select()->from(array('usp' => 'usr_profiles_usp'),
                                      	array('profile_value_usp as countryIso',
                                      	'COUNT(profile_value_usp) AS value'))
                                      ->joinLeft(array('usc' => 'countries_ctr'),
                                      	 'usc.iso_ctr = usp.profile_value_usp AND usp.profile_key_usp = "country"',
                                      	 array('countryName' => 'usc.printable_name_ctr'))
                                      ->where('usp.public_usp = 1')
                                      ->where('usp.profile_key_usp = "country"')
                                      ->where('usp.profile_value_usp != "0"')
                                      ->order('value desc')
                                      ->order('usp.id_usr_usp')
                                      ->group('usp.profile_value_usp')
                                      ;
                                      
       $result = $this->_db->fetchAssoc($select);

       return $result;
    }
    
} // end of class
