<?php
/**
 *  UserRoles -> User roles database model for user roles table.
 *
 *  Copyright (c) <2009>, Pekka Piispanen
 *
 *  This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License 
 *  as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
 * 
 *  This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied  
 *  warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for  
 *  more details.
 * 
 *  You should have received a copy of the GNU General Public License along with this program; if not, write to the Free 
 *  Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 *
 *  License text found in /license/
 */

/**
 *  UserRoles - class
 *
 *  @package    models
 *  @author     Pekka Piispanen
 *  @copyright  2009 Pekka Piispanen
 *  @license    GPL v2
 *  @version    1.0
 */ 
class Default_Model_UserRoles extends Zend_Db_Table_Abstract
{
    // Table name
    protected $_name = 'usr_roles_urr';
    
    // Table primary key
    protected $_primary = 'id_urr';
 
    /**
    *   addRole
    *   
    *   Adds new user role to the database table
    *
    *   @param string $role New role to add
    *   @return bool $return
    */
    public function addRole($newrole = "")
    {
        $return = true;
        
        if($newrole != "")
        {
            // Create a new row
            $role = $this->createRow();
        
            $role->name_urr = $newrole;
            $role->created_urr = new Zend_Db_Expr('NOW()');
            $role->modified_urr = new Zend_Db_Expr('NOW()');
            
            if(!$role->save())
            {
                $return = false;
            }
        }
        else
        {
            $return = false;
        }
        
        return $return;
    }
    
    /**
    *   getRoles
    *   
    *   Gets all possible user roles from the database
    *
    *   @return array $roles Array of roles
    */
    public function getRoles()
    {
        $select = $this->select()
                ->from($this, array('name_urr'));

        $results = $this->fetchAll($select)->toArray();
        
        foreach($results as $result)
        {
            $roles[] = $result['name_urr'];
        }
        return $roles;
    }
}