<?php
/**
 *  UserProfiles -> UserProfiles database model for userprofiles table.
 *
* 	Copyright (c) <2009>, Markus Riihelä
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
 *  @author 		Markus Riihelä & Mikko Sallinen
 *  @copyright 	2009 Markus Riihelä & Mikko Sallinen
 *  @license 	GPL v2
 *  @version 	1.0
 */ 
class Models_UserProfiles extends Zend_Db_Table_Abstract
{
	// Name of table
    protected $_name = 'usr_profiles_usp';
	
	// Primary key of table
	protected $_primary = 'id_usp';
	
	// Tables reference map
	protected $_referenceMap    = array(
        'UserProfile' => array(
            'columns'           => array('id_usr_usp'),
            'refTableClass'     => 'Models_User',
            'refColumns'        => array('id_usr')
        )
    );
	
	/*
    *   setUserFirstName
    *
    *   Changes the users first name.
    *
    *   @return N/A
    */
	public function setUserFirstName($id = -1, $formData)
	{		
		$select = $this->select()
                  ->from($this, array('profile_key_usp', 'profile_value_usp'))
                  ->where('id_usr_usp = ?', $id)
				  ->where('profile_key_usp = ?', 'first_name');
						
		$result = $this->fetchAll($select)->toArray();
				
				
		// this a bit tedious way to do this, I'll work on a better solution when I bump into one -sokuni
		$firstname = array(
			'id_usr_usp' => $id,
			'profile_key_usp' => 'first_name',
			'profile_value_usp' => $formData['first_name'],
			'public_usp' => 1,
			'modified_usp' => new Zend_Db_Expr('NOW()')
		);
				
		if(isset($result[0]['profile_value_usp']))
		{
			$where1[] = $this->getAdapter()->quoteInto('id_usr_usp = ?', $id);
			$where1[] = $this->getAdapter()->quoteInto('profile_key_usp = ?', $firstname['profile_key_usp']);
			$this->update($firstname, $where1);
		}
		else
		{	
			$this->insert($firstname);	
		}

	}
	
	/*
    *   setUserSurname
    *
    *   Changes the users surname.
    *
    *   @return N/A
    */
	public function setUserSurname($id = -1, $formData)
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
			'public_usp' => 1,
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
	
	public function getUserInfoById($id = -1)
	{
		// This result is needed to check whether there are rows in the profile-table for the user...will be removed due to release. -sokuni
		$select = $this->select()
                            ->from($this, array('profile_key_usp', 'profile_value_usp'))
                            ->where('id_usr_usp = ?', $id)
//							->where('profile_key_usp = ?', 'first_name')
//							->orWhere('profile_key_usp = ?', 'surname')
;
        $result = $this->fetchAll($select)->toArray();

		// Just a small re-mapping so that the populate-function knows where to put all the data. 
		// The helper variable is to ensure that a right value hits the right key :) -sokuni
		if($result != null)
		{
			$helper = $result[0]['profile_key_usp'];
			$data[$helper] = $result[0]['profile_value_usp'];
			$helper = $result[1]['profile_key_usp'];
			$data[$helper] = $result[1]['profile_value_usp'];
		
		return $data;
		}
		else
		return null;
	}
    
    /*
    *   initialize table for new user 
    *
    *   This function contains all the functionality needed as a new user
    *   registers. Currently only name + surname
    *
    *   @author Joel Peltonen
    *   @param uid string user id
    *   @param ur1 string user reminder question
    *   @param ura string user reminder answer
    *   @return boolean success
    */
    public function initNewUser($uid, $urq, $ura)
    {
        $failure = false;
        
        // Create a new row
        $row = $this->createRow();
            
        // Set columns values
        $row->id_usr_usp = $uid;
        $row->profile_key_usp = "first_name";
        $row->profile_value_usp = "OIBS";
        $row->public_usp = 1;

        $row->created_usp = new Zend_Db_Expr('NOW()');
        
        // Save row
        if(!$row->save()) {
            $failure = true;
        }
        
        // next row
        $row2 = $this->createRow();
            
        // Set columns values
        $row2->id_usr_usp = $uid;
        $row2->profile_key_usp = "surname";
        $row2->profile_value_usp = "User";
        $row2->public_usp = 1;
        $row2->created_usp = new Zend_Db_Expr('NOW()');
        
        // Save row
        if(!$row2->save()) {
            $failure = true;
        }

        // next row
        $row3 = $this->createRow();
        
        $row3->id_usr_usp = $uid;
        $row3->profile_key_usp = "reminder_question";
        $row3->profile_value_usp = $urq;
        $row3->public_usp = 0;
        $row3->created_usp = new Zend_Db_Expr('NOW()');
        
        // Save row
        if(!$row3->save()) {
            $failure = true;
        }

        
        // next row
        $row4 = $this->createRow();
        
        $row4->id_usr_usp = $uid;
        $row4->profile_key_usp = "reminder_answer";
        $row4->profile_value_usp = $ura;
        $row4->public_usp = 0;
        $row4->created_usp = new Zend_Db_Expr('NOW()');

        // Save row
        if(!$row4->save()) {
            $failure = true;
        }

        return !$failure;
    }
} // end of class
?>
