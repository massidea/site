<?php
/**
 *  UserHasGroup -> UserHasGroup database model for userhasgroup table.
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
 *  UserHasGroup - class
 *
 *  @package     models
 *  @author         Markus Riihelä & Mikko Sallinen, Mikko Aatola
 *  @copyright     2009 Markus Riihelä & Mikko Sallinen
 *  @license     GPL v2
 *  @version     1.0
 */ 
class Default_Model_UserHasGroup extends Zend_Db_Table_Abstract
{
    // Name of table
    protected $_name = 'usr_has_grp';
    
    // Tables reference map
    protected $_referenceMap    = array(
        'UserUser' => array(
            'columns'           => array('id_usr'),
            'refTableClass'     => 'Default_Model_User',
            'refColumns'        => array('id_usr')
        ),
         'UserGroup' => array(
            'columns'           => array('id_grp'),
            'refTableClass'     => 'Default_Model_Groups',
            'refColumns'        => array('id_grp')
        )

    );
    
    /**
     * Adds a user to a group.
     *
     * @author Mikko Aatola
     * @param id_grp id of the group to add the user to
     * @param id_usr id of the user to add to the group
     */
    public function addUserToGroup($id_grp = 0, $id_usr = 0)
    {
        if ($id_grp != 0 && $id_usr != 0) {
            // Create a new row.
            $row = $this->createRow();
            
            // Set values.
            $row->id_grp = $id_grp;
            $row->id_usr = $id_usr;
            
            // Add row to db.
            $row->save();
        }
    }
    
    /*
     * Removes a user from a group.
     *
     * @author Mikko Aatola
     * @param id_grp group id
     * @param id_usr user id
     */
    public function removeUserFromGroup($id_grp = 0, $id_usr = 0)
    {
        $return = false;
    
        $where = $this->getAdapter()->quoteInto('id_grp = ?', $id_grp);
        $where = $this->getAdapter()->quoteInto(
            "$where AND id_usr = ?", $id_usr);
        if($this->delete($where)) {
            $return = true;
        }
        
        return $return;
    }
    
    /**
     * Returns all users in the specified group.
     *
     * @author Mikko Aatola
     * @param id_grp id of the group
     * @return array of users in the specified group
     */
    public function getAllUsersInGroup($id_grp)
    {
        $data = $this->_db->select()
            ->from(array('uhg' => 'usr_has_grp'),
                   array('id_usr'))
            ->join(array('usr' =>'users_usr'),
                   'uhg.id_usr = usr.id_usr',
                   array('login_name_usr'))
            ->where('id_grp = ?', $id_grp);
            
        $result = $this->_db->fetchAll($data);
        
        return $result;
    }

    /**
     * Returns user groups from the user group table.
     *
     * @author Mikko Korpinen
     * @param id_usr user id
     * @return array of data of every group from user
     */
    public function getGroupsByUserId($id_usr)
    {
        $data = $this->_db->select()
            ->from(array('uhg' => 'usr_has_grp'),
                   array('id_grp'))
            ->join(array('ugg' =>'usr_groups_grp'),
                   'uhg.id_grp = ugg.id_grp',
                   array('*'))
            ->where('id_usr = ?', $id_usr);

        $result = $this->_db->fetchAll($data);

        return $result;
    }
    
    /**
     * Checks if a user is in a group.
     *
     * @author Mikko Aatola
     * @param id_grp group id
     * @param id_usr user id
     * @return true if the user is in the group, false if not
     */
    public function userHasGroup($id_grp, $id_usr)
    {
        $select = $this->_db->select()
                        ->from('usr_has_grp', array('id_grp', 'id_usr'))
                        ->where('id_grp = ?', $id_grp)
                        ->where('id_usr = ?', $id_usr);
        $result = $this->_db->fetchAll($select);

        if (!isset($result[0])) {
            return false;
        } else {
            return true;
        }
    }
} // end of class
?>